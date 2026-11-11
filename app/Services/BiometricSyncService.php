<?php

namespace App\Services;

use App\Models\BiometricDevice;
use App\Models\Hr\Attendance;
use App\Models\Hr\Employee;
use App\Models\Hr\HrSetting;
use Carbon\Carbon;

class BiometricSyncService
{
    protected BiometricDeviceService $deviceService;

    public function __construct(BiometricDeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    /**
     * Sync single employee to device
     */
    public function syncEmployeeToDevice(Employee $employee, BiometricDevice $device): array
    {
        // Generate device user ID if not exists
        if (! $employee->device_user_id) {
            $employee->device_user_id = $this->generateDeviceUserId($device);
        } else {
            // Check for potential duplicate ID conflict on this device
            $conflict = Employee::where('biometric_device_id', $device->id)
                ->where('device_user_id', $employee->device_user_id)
                ->where('id', '!=', $employee->id)
                ->exists();

            if ($conflict) {
                // ID conflict detected! Regenerate ID for this user
                $employee->device_user_id = $this->generateDeviceUserId($device);
            }
        }

        // Ensure strictly linked to this device
        if ($employee->biometric_device_id !== $device->id) {
            $employee->biometric_device_id = $device->id;
            $employee->save();
        }

        // Add user to device
        $success = $this->deviceService->addUserToDevice(
            $device,
            $employee->device_user_id,
            $employee->full_name
        );

        if ($success) {
            $employee->last_device_sync_at = now();
            $employee->save();

            return [
                'success' => true,
                'message' => 'Employee synced successfully. Device User ID: '.$employee->device_user_id,
                'device_user_id' => $employee->device_user_id,
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to sync employee to device.',
        ];
    }

    /**
     * Sync all employees to device
     */
    public function syncAllEmployeesToDevice(BiometricDevice $device): array
    {
        $employees = Employee::where('status', 'active')->get();
        $synced = 0;
        $failed = 0;

        foreach ($employees as $employee) {
            $result = $this->syncEmployeeToDevice($employee, $device);
            if ($result['success']) {
                $synced++;
            } else {
                $failed++;
            }
        }

        return [
            'success' => true,
            'synced' => $synced,
            'failed' => $failed,
            'message' => "Synced {$synced} employees. Failed: {$failed}",
        ];
    }

    /**
     * Get punch gap minutes from global settings
     */
    protected function getPunchGapMinutes(): int
    {
        return HrSetting::getPunchGapMinutes();
    }

    /**
     * Helper to parse Carbon datetime strictly bound to attendance record date
     */
    protected function getAttendanceDateTime(string $dateStr, ?string $timeVal): ?Carbon
    {
        if (empty($timeVal)) {
            return null;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $timeVal)) {
            return Carbon::parse($timeVal);
        }

        $timeOnly = Carbon::parse($timeVal)->format('H:i:s');
        return Carbon::parse($dateStr . ' ' . $timeOnly);
    }

    /**
     * Pull attendance logs from device and create attendance records
     */
    public function pullAttendanceFromDevice(BiometricDevice $device): array
    {
        try {
            $logs = $this->deviceService->getAttendanceLogs($device);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'created' => 0,
                'skipped' => 0,
                'duplicates' => 0,
                'failed' => 1,
            ];
        }

        if (empty($logs)) {
            return [
                'success' => true,
                'message' => 'No attendance logs found on device.',
                'created' => 0,
                'skipped' => 0,
                'duplicates' => 0,
                'failed' => 0,
            ];
        }

        // Sort logs by timestamp to ensure we process them in order
        usort($logs, function ($a, $b) {
            return strtotime($a['timestamp']) <=> strtotime($b['timestamp']);
        });

        // 1. Preload all employees mapped by device_user_id in a single query
        $employeesByDeviceUserId = Employee::with('shift')
            ->whereNotNull('device_user_id')
            ->get()
            ->keyBy(fn ($e) => (string) $e->device_user_id);

        $defaultShift = \App\Models\Hr\Shift::where('is_default', true)->first();
        $gap = $this->getPunchGapMinutes();

        // 2. Filter to recent logs (last 60 days) to prevent re-querying ancient history on every sync
        $cutoffDate = Carbon::now()->subDays(60)->startOfDay();
        $recentLogs = array_filter($logs, function ($l) use ($cutoffDate) {
            try {
                return Carbon::parse($l['timestamp'])->gte($cutoffDate);
            } catch (\Exception $e) {
                return false;
            }
        });
        $logsToProcess = !empty($recentLogs) ? $recentLogs : $logs;

        // 3. Preload all relevant attendance records in memory to eliminate N+1 queries
        $employeeIds = $employeesByDeviceUserId->pluck('id')->toArray();
        $minDateStr = !empty($logsToProcess) ? Carbon::parse(reset($logsToProcess)['timestamp'])->toDateString() : $cutoffDate->toDateString();
        
        $existingAttendances = Attendance::whereIn('employee_id', $employeeIds)
            ->where('date', '>=', $minDateStr)
            ->get()
            ->keyBy(fn ($a) => $a->employee_id . '_' . $a->date);

        $created = 0;
        $skipped = 0;
        $duplicates = 0;

        foreach ($logsToProcess as $log) {
            $deviceUserId = (string) ($log['id'] ?? '');
            $employee = $employeesByDeviceUserId->get($deviceUserId);

            if (! $employee) {
                $skipped++;
                continue;
            }

            // Parse timestamp
            $timestamp = Carbon::parse($log['timestamp']);
            $date = $timestamp->toDateString();
            $attKey = $employee->id . '_' . $date;

            // Find or initialize attendance from memory cache
            if (isset($existingAttendances[$attKey])) {
                $attendance = $existingAttendances[$attKey];
            } else {
                $attendance = Attendance::firstOrCreate([
                    'employee_id' => $employee->id,
                    'date' => $date,
                ], [
                    'status' => 'present',
                ]);
                $existingAttendances[$attKey] = $attendance;
            }

            if (! $attendance->check_in_time) {
                // No check-in yet - this is the first punch (Check-In)
                $shift = $employee->shift ?? $defaultShift;
                $isLate = false;
                $lateMinutes = 0;

                if ($shift) {
                    $timeStr = null;

                    if ($employee->custom_start_time) {
                        $timeStr = $employee->custom_start_time;
                    } elseif ($shift->start_time) {
                        $timeStr = \Carbon\Carbon::parse($shift->start_time)->format('H:i:s');
                    }

                    if ($timeStr) {
                        $shiftStart = Carbon::parse($attendance->date.' '.$timeStr);
                        $lateThreshold = $shiftStart->copy()->addMinutes($shift->grace_minutes ?? 0);

                        if ($timestamp->gt($lateThreshold)) {
                            $isLate = true;
                            // Cap late minutes to shift duration / max 720 minutes
                            $lateMinutes = min(720, (int) $shiftStart->diffInMinutes($timestamp));
                        }

                        if ($timestamp->lt($shiftStart)) {
                            $attendance->is_early_in = true;
                            $attendance->early_in_minutes = min(720, (int) $timestamp->diffInMinutes($shiftStart));
                        }
                    }
                }

                $attendance->is_late = $isLate;
                $attendance->late_minutes = $lateMinutes;
                $attendance->status = $isLate ? 'late' : 'present';
                $attendance->check_in_time = $timestamp->toDateTimeString();
                $attendance->check_in_location = 'Biometric Device';
                $attendance->save();
                $created++;
            } else {
                // Existing attendance record
                $shift = $employee->shift ?? $defaultShift;
                $checkInTime = $this->getAttendanceDateTime($attendance->date, $attendance->check_in_time);

                // Quick duplicate check: Punch is before or at check-in time
                if ($checkInTime && $timestamp->lte($checkInTime)) {
                    $duplicates++;
                    continue;
                }

                // Quick duplicate check: Punch is within the configured punch gap
                $minutesSinceCheckIn = $checkInTime ? $checkInTime->diffInMinutes($timestamp) : 999;
                if ($minutesSinceCheckIn < $gap) {
                    $duplicates++;
                    continue;
                }

                // Check-out logic
                $checkOutTime = $this->getAttendanceDateTime($attendance->date, $attendance->check_out_time);

                // Quick duplicate check: Punch is before or at existing check-out time
                if ($checkOutTime && $timestamp->lte($checkOutTime)) {
                    $duplicates++;
                    continue;
                }

                if (! $checkOutTime || $timestamp->gt($checkOutTime)) {
                    $attendance->check_out_time = $timestamp->toDateTimeString();
                    $attendance->check_out_location = 'Biometric Device';

                    // Calculate Early Leave
                    if ($shift) {
                        $endTimeStr = $employee->custom_end_time ?: ($shift->end_time ? \Carbon\Carbon::parse($shift->end_time)->format('H:i:s') : null);
                        if ($endTimeStr) {
                            $shiftEnd = Carbon::parse($attendance->date.' '.$endTimeStr);
                            if ($timestamp->lt($shiftEnd)) {
                                $attendance->is_early_leave = true;
                                $attendance->early_leave_minutes = min(720, (int) $timestamp->diffInMinutes($shiftEnd));
                            } else {
                                $attendance->is_early_leave = false;
                                $attendance->early_leave_minutes = 0;
                            }
                        }
                    }

                    $this->calculateTotalHours($attendance);
                    $attendance->save();
                    $created++;
                }
            }
        }

        \Log::info("Biometric pull completed for {$device->name}: Created {$created}, Duplicates {$duplicates}, Skipped {$skipped}.");

        // Update device last sync time
        $device->last_sync_at = now();
        $device->save();

        return [
            'success' => true,
            'created' => $created,
            'skipped' => $skipped,
            'duplicates' => $duplicates,
            'failed' => 0,
            'last_log_date' => $lastLogDate ?? null,
            'message' => "Processed attendance. Synced: {$created}, Skipped (no employee): {$skipped}, Duplicates ignored: {$duplicates}" . (isset($lastLogDate) ? ". Latest Date: $lastLogDate" : ""),
        ];
    }

    /**
     * Calculate and set total hours worked
     */
    protected function calculateTotalHours(Attendance $attendance): void
    {
        if ($attendance->check_in_time && $attendance->check_out_time) {
            $checkIn = $this->getAttendanceDateTime($attendance->date, $attendance->check_in_time);
            $checkOut = $this->getAttendanceDateTime($attendance->date, $attendance->check_out_time);
            if ($checkIn && $checkOut) {
                if ($checkOut->lt($checkIn)) {
                    $checkOut->addDay();
                }
                $attendance->total_hours = min(24, round($checkOut->diffInMinutes($checkIn) / 60, 2));
            }
        }
    }

    /**
     * Generate unique device user ID
     */
    protected function generateDeviceUserId(BiometricDevice $device): string
    {
        // Get the highest device_user_id for this device
        $lastEmployee = Employee::where('biometric_device_id', $device->id)
            ->whereNotNull('device_user_id')
            ->orderByRaw('CAST(device_user_id AS UNSIGNED) DESC')
            ->first();

        $nextId = $lastEmployee ? (int) $lastEmployee->device_user_id + 1 : 1;

        return (string) $nextId;
    }
}

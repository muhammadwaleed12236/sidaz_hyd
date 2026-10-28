<?php

namespace App\Services;

use App\Models\Hr\Attendance;
use App\Models\Hr\Employee;
use App\Models\Hr\EmployeeSalaryStructure;
use App\Models\Hr\Payroll;
use App\Models\Hr\PayrollDetail;
use App\Models\Hr\SalaryStructure;
use Carbon\Carbon;

class PayrollCalculationService
{
    /**
     * Get the effective salary structure for an employee
     * Handles custom/edited structures vs template structures
     */
    public function getEffectiveSalaryStructure(Employee $employee): ?SalaryStructure
    {
        // Get the active assignment
        $activeAssignment = EmployeeSalaryStructure::where('employee_id', $employee->id)
            ->where('is_active', true)
            ->whereNull('end_date')
            ->with('salaryStructure')
            ->latest('start_date')
            ->first();

        if ($activeAssignment && $activeAssignment->salaryStructure) {
            $structure = $activeAssignment->salaryStructure;

            // If this is a custom assignment, check if there's a child structure for this employee
            if ($activeAssignment->is_custom) {
                // Look for an employee-specific child structure
                $customStructure = SalaryStructure::where('parent_structure_id', $structure->id)
                    ->where('employee_id', $employee->id)
                    ->first();

                if ($customStructure) {
                    return $customStructure;
                }
            }

            return $structure;
        }

        // Fallback to legacy relationship
        if ($employee->salaryStructure) {
            return $employee->salaryStructure;
        }

        // Fallback: match by designation or default structure
        if ($employee->designation) {
            $designationName = strtolower($employee->designation->name);
            $matched = SalaryStructure::whereRaw('LOWER(name) LIKE ?', ['%' . $designationName . '%'])->first();
            if ($matched) {
                return $matched;
            }
        }

        // Default fallback to Labor structure (ID 7) or first available structure
        return SalaryStructure::find(7) ?? SalaryStructure::first();
    }

    /**
     * Calculate allowances with support for both fixed and percentage types
     */
    private function calculateAllowances(SalaryStructure $structure): array
    {
        $allowances = collect($structure->allowances ?? [])->filter(function ($item) {
            return ! empty($item['is_active']) && $item['is_active'] !== 'false';
        });

        $baseSalary = $structure->base_salary ?? 0;
        $totalAllowances = 0;
        $details = [];

        foreach ($allowances as $allowance) {
            $name = $allowance['name'] ?? 'Allowance';
            $type = $allowance['type'] ?? 'fixed'; // 'fixed' or 'percentage'
            $value = floatval($allowance['amount'] ?? 0);

            if ($type === 'percentage') {
                // Calculate based on base salary
                $amount = ($baseSalary * $value) / 100;
                $description = "{$value}% of base salary";
            } else {
                $amount = $value;
                $description = $allowance['description'] ?? null;
            }

            $totalAllowances += $amount;
            $details[] = [
                'name' => $name,
                'amount' => $amount,
                'type' => $type,
                'description' => $description,
            ];
        }

        return [
            'total' => $totalAllowances,
            'details' => $details,
        ];
    }

    /**
     * Calculate fixed deductions from salary structure
     */
    private function calculateFixedDeductions(SalaryStructure $structure): array
    {
        $deductions = collect($structure->deductions ?? [])->filter(function ($item) {
            return ! empty($item['is_active']) && $item['is_active'] !== 'false';
        });

        $baseSalary = $structure->base_salary ?? 0;
        $totalDeductions = 0;
        $details = [];

        foreach ($deductions as $deduction) {
            $name = $deduction['name'] ?? 'Deduction';
            $type = $deduction['type'] ?? 'fixed';
            $value = floatval($deduction['amount'] ?? 0);

            if ($type === 'percentage') {
                $amount = ($baseSalary * $value) / 100;
                $description = "{$value}% of base salary";
            } else {
                $amount = $value;
                $description = $deduction['description'] ?? null;
            }

            $totalDeductions += $amount;
            $details[] = [
                'name' => $name,
                'amount' => $amount,
                'type' => $type,
                'description' => $description,
            ];
        }

        return [
            'total' => $totalDeductions,
            'details' => $details,
        ];
    }

    /**
     * Calculate attendance-based deductions for monthly payroll
     */
    private function calculateMonthlyAttendanceDeductions(
        Employee $employee,
        string $month,
        SalaryStructure $structure
    ): array {
        $startDate = Carbon::parse($month.'-01')->startOfMonth();
        $endDate = Carbon::parse($month.'-01')->endOfMonth();

        $policy = $structure->attendance_deduction_policy ?? [];
        $baseSalary = floatval($structure->base_salary ?? 0);
        $perDayDeduction = floatval($structure->leave_salary_per_day ?? 0);
        
        // Fallback per-day deduction rate to Basic Salary / 30 if not explicitly set
        if ($perDayDeduction <= 0 && $baseSalary > 0) {
            $perDayDeduction = round($baseSalary / 30, 2);
        }

        // Fetch attendance records for the period
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        // Calculate working days (excluding employee's weekly off days)
        $weeklyOffDays = $employee->getWeeklyOffDays();
        $totalWorkingDays = $this->getWorkingDaysInRange($startDate, $endDate, $weeklyOffDays);

        // Attendance stats (exclude weekly off days from absence/leave penalties)
        $daysPresent = $attendances->filter(fn ($att) => strtolower($att->status) === 'present')->count();
        $daysAbsent = $attendances->filter(function ($att) use ($weeklyOffDays) {
            if (strtolower($att->status) !== 'absent') {
                return false;
            }
            $dayOfWeek = Carbon::parse($att->date)->dayOfWeek;
            return !in_array($dayOfWeek, $weeklyOffDays);
        })->count();
        $daysLeave = $attendances->filter(function ($att) use ($weeklyOffDays) {
            if (strtolower($att->status) !== 'leave') {
                return false;
            }
            $dayOfWeek = Carbon::parse($att->date)->dayOfWeek;
            return !in_array($dayOfWeek, $weeklyOffDays);
        })->count();
        $totalDeductionDays = $daysAbsent + $daysLeave;
        $shiftStart = $employee->getStartTime();
        $shiftStartStr = Carbon::parse($shiftStart)->format('H:i:s');
        $graceMinutes = $employee->getGraceMinutes();
        if ($hrPolicy && isset($hrPolicy->late_grace_minutes) && $hrPolicy->late_grace_minutes > 0) {
            $graceMinutes = $hrPolicy->late_grace_minutes;
        }
        $lateThreshold = Carbon::parse($shiftStartStr)->addMinutes($graceMinutes)->format('H:i:s');

        $lateAttendancesList = $attendances->filter(function ($att) use ($lateThreshold) {
            if ($att->check_in_time) {
                $checkInTimeStr = Carbon::parse($att->check_in_time)->format('H:i:s');
                return $checkInTimeStr > $lateThreshold;
            }
            return ($att->is_late || strtolower($att->status ?? '') === 'late');
        })->values();

        $lateCheckIns = $lateAttendancesList->count();
        $earlyCheckOuts = $attendances->where('is_early_leave', true)->count();
        $totalLateMinutes = $attendances->sum('late_minutes');
        $totalEarlyMinutes = $attendances->sum('early_leave_minutes');

        // If no attendance data exists
        $hasAttendanceData = $attendances->count() > 0;

        // Calculate deductions
        $absenceDeduction = 0;
        $lateDeduction = 0;
        $earlyDeduction = 0;
        $deductionDetails = [];

        // Check HR Policy first for absence deduction rate
        $hrPolicy = \App\Models\Hr\HrPolicy::getEffectivePolicyForEmployee($employee->id);
        if ($hrPolicy && $hrPolicy->absence_deduction_type === 'fixed') {
            $effectivePerDayDeduction = floatval($hrPolicy->absence_fixed_amount);
        } else {
            $effectivePerDayDeduction = $perDayDeduction;
        }

        // Absence & Leave deduction (total absent + leave days × per-day deduction)
        if ($totalDeductionDays > 0 && ($effectivePerDayDeduction > 0 || $baseSalary > 0)) {
            $absenceDeduction = round($totalDeductionDays * $effectivePerDayDeduction, 2);
            
            $detailName = "Absence & Leave Deduction ({$totalDeductionDays} days)";
            if ($daysAbsent > 0 && $daysLeave > 0) {
                $detailDesc = "{$totalDeductionDays} days ({$daysAbsent} absent, {$daysLeave} leave) × Rs. " . number_format($effectivePerDayDeduction, 2);
            } elseif ($daysLeave > 0) {
                $detailDesc = "{$daysLeave} leave days × Rs. " . number_format($effectivePerDayDeduction, 2);
            } else {
                $detailDesc = "{$daysAbsent} absent days × Rs. " . number_format($effectivePerDayDeduction, 2);
            }

            $deductionDetails[] = [
                'name' => $detailName,
                'amount' => $absenceDeduction,
                'description' => $detailDesc,
            ];
        }

        // Late check-in penalty based on HR Policy or Structure
        if ($lateCheckIns > 0) {
            if ($hrPolicy && $hrPolicy->late_penalty_type === '3_lates_1_day') {
                $lateDeductionDays = floor($lateCheckIns / 3);
                $lateDeduction = round($lateDeductionDays * $effectivePerDayDeduction, 2);
                if ($lateDeduction > 0) {
                    $deductionDetails[] = [
                        'name' => "Late Check-in Penalty ({$lateCheckIns} late arrivals, {$lateDeductionDays} day deduction)",
                        'amount' => $lateDeduction,
                        'description' => "Policy: Every 3 late check-ins = 1 day salary deduction. ({$lateCheckIns} lates = {$lateDeductionDays} day(s) × Rs. " . number_format($effectivePerDayDeduction, 2) . ")",
                    ];
                }
            } elseif ($hrPolicy && $hrPolicy->late_penalty_type === 'fixed_per_instance' && floatval($hrPolicy->late_penalty_amount) > 0) {
                $lateDeduction = round($lateCheckIns * floatval($hrPolicy->late_penalty_amount), 2);
                if ($lateDeduction > 0) {
                    $deductionDetails[] = [
                        'name' => "Late Check-in Penalty ({$lateCheckIns} times)",
                        'amount' => $lateDeduction,
                        'description' => "{$lateCheckIns} late check-ins × Rs. " . number_format($hrPolicy->late_penalty_amount, 2),
                    ];
                }
            } elseif (! empty($policy['late_penalty_per_instance'])) {
                $latePenalty = floatval($policy['late_penalty_per_instance']);
                $lateDeduction = round($lateCheckIns * $latePenalty, 2);
                $deductionDetails[] = [
                    'name' => "Late Check-in Penalty ({$lateCheckIns} times)",
                    'amount' => $lateDeduction,
                    'description' => "{$lateCheckIns} late check-ins × Rs. {$latePenalty}",
                ];
            } elseif (! empty($policy['late_rules'])) {
                foreach ($lateAttendancesList as $att) {
                    $deduction = $this->calculateLateDeduction(
                        $att->late_minutes ?? 0,
                        $policy['late_rules'],
                        $effectivePerDayDeduction
                    );
                    $lateDeduction += $deduction;
                }
                if ($lateDeduction > 0) {
                    $deductionDetails[] = [
                        'name' => "Late Check-in Penalties ({$lateCheckIns} times)",
                        'amount' => round($lateDeduction, 2),
                        'description' => 'Calculated based on late rules',
                    ];
                }
            }
        }

        // Early check-out penalty strictly based on HR Policy or Structure
        if ($earlyCheckOuts > 0) {
            if ($hrPolicy && $hrPolicy->early_checkout_penalty_type === '3_earlies_1_day') {
                $earlyDeductionDays = floor($earlyCheckOuts / 3);
                $earlyDeduction = round($earlyDeductionDays * $effectivePerDayDeduction, 2);
                if ($earlyDeduction > 0) {
                    $deductionDetails[] = [
                        'name' => "Early Check-out Penalty ({$earlyCheckOuts} early departures, {$earlyDeductionDays} day deduction)",
                        'amount' => $earlyDeduction,
                        'description' => "Policy: Every 3 early leaves = 1 day salary deduction. ({$earlyCheckOuts} earlies = {$earlyDeductionDays} day(s) × Rs. " . number_format($effectivePerDayDeduction, 2) . ")",
                    ];
                }
            } elseif ($hrPolicy && $hrPolicy->early_checkout_penalty_type === 'fixed_per_instance' && floatval($hrPolicy->early_checkout_penalty_amount) > 0) {
                $earlyDeduction = round($earlyCheckOuts * floatval($hrPolicy->early_checkout_penalty_amount), 2);
                if ($earlyDeduction > 0) {
                    $deductionDetails[] = [
                        'name' => "Early Check-out Penalty ({$earlyCheckOuts} times)",
                        'amount' => $earlyDeduction,
                        'description' => "{$earlyCheckOuts} early check-outs × Rs. " . number_format($hrPolicy->early_checkout_penalty_amount, 2),
                    ];
                }
            } elseif (! empty($policy['early_penalty_per_instance'])) {
                $earlyPenalty = floatval($policy['early_penalty_per_instance']);
                $earlyDeduction = round($earlyCheckOuts * $earlyPenalty, 2);
                $deductionDetails[] = [
                    'name' => "Early Check-out Penalty ({$earlyCheckOuts} times)",
                    'amount' => $earlyDeduction,
                    'description' => "{$earlyCheckOuts} early check-outs × Rs. {$earlyPenalty}",
                ];
            } elseif (! empty($policy['early_rules'])) {
                foreach ($attendances->where('is_early_leave', true) as $att) {
                    $deduction = $this->calculateEarlyDeduction(
                        $att->early_leave_minutes ?? 0,
                        $policy['early_rules'],
                        $effectivePerDayDeduction
                    );
                    $earlyDeduction += $deduction;
                }
                if ($earlyDeduction > 0) {
                    $deductionDetails[] = [
                        'name' => "Early Check-out Penalties ({$earlyCheckOuts} times, {$totalEarlyMinutes} min)",
                        'amount' => round($earlyDeduction, 2),
                        'description' => 'Calculated based on early rules',
                    ];
                }
            }
        }

        $totalDeduction = $absenceDeduction + $lateDeduction + $earlyDeduction;

        return [
            'total' => $totalDeduction,
            'details' => $deductionDetails,
            'breakdown' => [
                'has_data' => $hasAttendanceData,
                'total_working_days' => $totalWorkingDays,
                'days_present' => $daysPresent,
                'days_absent' => $daysAbsent,
                'late_check_ins' => $lateCheckIns,
                'early_check_outs' => $earlyCheckOuts,
                'total_late_minutes' => $totalLateMinutes,
                'total_early_minutes' => $totalEarlyMinutes,
                'absence_deduction' => $absenceDeduction,
                'late_deduction' => $lateDeduction,
                'early_deduction' => $earlyDeduction,
            ],
        ];
    }

    /**
     * Calculate working days in a date range (excluding employee-specific weekly off days)
     * @param array $weeklyOffDays Day numbers (0=Sun, 1=Mon ... 6=Sat) to treat as off days
     */
    private function getWorkingDaysInRange(Carbon $startDate, Carbon $endDate, array $weeklyOffDays = [0, 6]): int
    {
        $workingDays = 0;
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            // Exclude configured weekly off days
            if (! in_array($current->dayOfWeek, $weeklyOffDays)) {
                $workingDays++;
            }
            $current->addDay();
        }

        return $workingDays;
    }

    /**
     * Calculate monthly payroll for a salaried employee
     */
    public function calculateMonthlyPayroll(Employee $employee, string $month): array
    {
        // Get effective salary structure (handles custom assignments)
        $structure = $this->getEffectiveSalaryStructure($employee);

        if (! $structure) {
            throw new \Exception('Employee has no salary structure assigned.');
        }

        $baseSalary = $structure->base_salary ?? 0;

        // Calculate allowances (supports fixed and percentage types)
        $allowanceData = $this->calculateAllowances($structure);
        $activeAllowances = $allowanceData['total'];
        $allowanceDetails = $allowanceData['details'];

        // Calculate fixed deductions
        $fixedDeductionData = $this->calculateFixedDeductions($structure);
        $activeFixedDeductions = $fixedDeductionData['total'];
        $fixedDeductionDetails = $fixedDeductionData['details'];

        // Calculate attendance-based deductions
        $attendanceDeductionData = $this->calculateMonthlyAttendanceDeductions($employee, $month, $structure);
        $attendanceDeductions = $attendanceDeductionData['total'];
        $attendanceBreakdown = $attendanceDeductionData['breakdown'];

        // Add attendance deduction details to deduction details
        $allDeductionDetails = array_merge($fixedDeductionDetails, $attendanceDeductionData['details']);

        // Calculate Overtime earnings from attendance
        $otEarnings = 0;
        $otHours    = 0;
        $otAllowanceDetail = [];
        try {
            $otData  = $this->calculateOvertimeForMonth($employee, $month, $baseSalary);
            $otEarnings = $otData['earnings'];
            $otHours    = $otData['hours'];
            if ($otEarnings > 0) {
                $otAllowanceDetail = [[
                    'name'        => 'Overtime',
                    'amount'      => $otEarnings,
                    'type'        => 'fixed',
                    'description' => "Overtime: {$otHours} hrs",
                ]];
            }
        } catch (\Exception $e) {
            // OT calc failure should not block payroll generation
        }

        // Calculate gross salary (includes OT)
        $grossSalary = $baseSalary + $activeAllowances + $otEarnings;

        // Calculate total deductions
        $totalDeductions = $activeFixedDeductions + $attendanceDeductions;

        // Calculate net salary (prevent negative)
        $netSalary = max(0, $grossSalary - $totalDeductions);

        return [
            'payroll_type'             => 'monthly',
            'month'                    => $month,
            'basic_salary'             => $baseSalary,
            'gross_salary'             => $grossSalary,
            'allowances'               => $activeAllowances,
            'deductions'               => $activeFixedDeductions,
            'attendance_deductions'    => $attendanceDeductions,
            'manual_deductions'        => 0,
            'manual_allowances'        => 0,
            'carried_forward_deduction'=> 0,
            'bonuses'                  => 0,
            'net_salary'               => $netSalary,
            'auto_generated'           => true,
            'status'                   => 'generated',
            'allowance_details'        => array_merge($allowanceDetails, $otAllowanceDetail),
            'deduction_details'        => $allDeductionDetails,
            'attendance_breakdown'     => $attendanceBreakdown,
        ];
    }

    /**
     * Calculate daily payroll for a daily wage employee
     */
    public function calculateDailyPayroll(Employee $employee, Attendance $attendance): array
    {
        // Get effective salary structure
        $structure = $this->getEffectiveSalaryStructure($employee);

        if (! $structure || ! $structure->use_daily_wages) {
            throw new \Exception('Employee is not configured for daily wages.');
        }

        $dailyRate = $structure->daily_wages;
        $policy = $structure->attendance_deduction_policy ?? [];
        $carryForward = $structure->carry_forward_deductions ?? false;

        // Start with the daily rate
        $dayEarning = $dailyRate;
        $dayDeduction = 0;
        $deductionDetails = [];

        // Calculate Overtime for daily attendance if policy allows
        $dailyOtHours = 0;
        $dailyOtEarnings = 0;
        $otAllowanceDetails = [];
        try {
            $hrPolicy = \App\Models\Hr\HrPolicy::getEffectivePolicyForEmployee($employee->id);
            if (!$hrPolicy || !isset($hrPolicy->overtime_enabled) || $hrPolicy->overtime_enabled) {
                $shift = $employee->shift ?? \App\Models\Hr\Shift::where('is_default', true)->first();
                $shiftEndStr = $employee->custom_end_time ?: ($shift ? Carbon::parse($shift->end_time)->format('H:i:s') : '18:00:00');
                $standardShiftHours = 9.0;
                if ($shift && floatval($shift->total_hours) > 0) {
                    $standardShiftHours = floatval($shift->total_hours);
                }
                $hourlySalary = ($standardShiftHours > 0) ? ($dailyRate / $standardShiftHours) : 0;
                $minOtMinutes = ($hrPolicy && isset($hrPolicy->overtime_min_minutes) && $hrPolicy->overtime_min_minutes > 0)
                    ? intval($hrPolicy->overtime_min_minutes)
                    : 60;

                $timeOut = $attendance->check_out_time ?: $attendance->clock_out;
                if ($timeOut) {
                    $dateYmd = Carbon::parse($attendance->date)->format('Y-m-d');
                    $shiftEndDt = Carbon::parse($dateYmd . ' ' . $shiftEndStr);
                    $checkOutDt = Carbon::parse($dateYmd . ' ' . Carbon::parse($timeOut)->format('H:i:s'));
                    if ($checkOutDt->gt($shiftEndDt)) {
                        $extraMinutes = $checkOutDt->diffInMinutes($shiftEndDt);
                        if ($extraMinutes >= $minOtMinutes) {
                            $dailyOtHours = round($extraMinutes / 60, 2);
                        }
                    }
                } elseif (floatval($attendance->total_hours ?? 0) > ($standardShiftHours + ($minOtMinutes / 60))) {
                    $dailyOtHours = round(floatval($attendance->total_hours) - $standardShiftHours, 2);
                }

                if ($dailyOtHours > 0) {
                    if ($hrPolicy && $hrPolicy->overtime_rate_type === 'fixed' && $hrPolicy->overtime_fixed_rate > 0) {
                        $dailyOtEarnings = round($dailyOtHours * $hrPolicy->overtime_fixed_rate, 2);
                    } else {
                        $multiplier = ($hrPolicy && $hrPolicy->overtime_multiplier > 0) ? $hrPolicy->overtime_multiplier : 1.5;
                        $dailyOtEarnings = round($dailyOtHours * $hourlySalary * $multiplier, 2);
                    }

                    $otAllowanceDetails[] = [
                        'name' => 'Overtime',
                        'amount' => $dailyOtEarnings,
                        'type' => 'fixed',
                        'description' => "Overtime: {$dailyOtHours} hrs",
                    ];
                }
            }
        } catch (\Exception $e) {}

        $dayEarning = $dailyRate + $dailyOtEarnings;

        // Apply late check-in deductions
        if (($attendance->late_minutes ?? 0) > 0) {
            $lateDeduction = $this->calculateLateDeduction(
                $attendance->late_minutes,
                $policy['late_rules'] ?? [],
                $dailyRate
            );

            if ($lateDeduction > 0) {
                $dayDeduction += $lateDeduction;
                $deductionDetails[] = [
                    'name' => 'Late Check-in ('.$attendance->late_minutes.' min)',
                    'amount' => $lateDeduction,
                    'description' => 'Late arrival deduction for '.Carbon::parse($attendance->date)->format('d/m/Y'),
                ];
            }
        }

        // Apply early check-out deductions
        if (($attendance->early_leave_minutes ?? 0) > 0) {
            $earlyDeduction = $this->calculateEarlyDeduction(
                $attendance->early_leave_minutes,
                $policy['early_rules'] ?? [],
                $dailyRate
            );

            if ($earlyDeduction > 0) {
                $dayDeduction += $earlyDeduction;
                $deductionDetails[] = [
                    'name' => 'Early Leave ('.$attendance->early_leave_minutes.' min)',
                    'amount' => $earlyDeduction,
                    'description' => 'Early departure deduction for '.Carbon::parse($attendance->date)->format('d/m/Y'),
                ];
            }
        }

        // Handle carried forward deductions from previous day
        $carriedForwardDeduction = $employee->pending_deductions ?? 0;
        $totalDeductions = $dayDeduction + $carriedForwardDeduction;

        // Determine net payable and remaining carry-forward
        $netSalary = 0;
        $newPendingDeductions = 0;

        if ($totalDeductions <= $dayEarning) {
            // Can pay full amount after deductions
            $netSalary = $dayEarning - $totalDeductions;
            $newPendingDeductions = 0;
        } else {
            // Deductions exceed daily earning
            if ($carryForward) {
                // Carry forward is allowed
                $netSalary = 0;
                $newPendingDeductions = $totalDeductions - $dayEarning;
            } else {
                // Carry forward not allowed - cap deductions at daily earning
                $netSalary = 0;
                $newPendingDeductions = 0;
                $totalDeductions = $dayEarning;
            }
        }

        // Prevent negative salary
        $netSalary = max(0, $netSalary);

        return [
            'payroll_type' => 'daily',
            'month' => Carbon::parse($attendance->date)->format('Y-m-d'), // Store full date for daily
            'basic_salary' => $dailyRate,
            'gross_salary' => $dayEarning,
            'allowances' => $dailyOtEarnings,
            'deductions' => 0, // Fixed deductions don't apply to daily
            'attendance_deductions' => $dayDeduction,
            'manual_deductions' => 0,
            'manual_allowances' => 0,
            'carried_forward_deduction' => $carriedForwardDeduction,
            'bonuses' => 0,
            'net_salary' => $netSalary,
            'auto_generated' => true,
            'status' => 'generated',
            'carried_forward_to_next' => $newPendingDeductions,
            'new_pending_deductions' => $newPendingDeductions,
            'deduction_details' => $deductionDetails,
            'allowance_details' => $otAllowanceDetails,
            'attendance_breakdown' => [
                'has_data' => true,
                'date' => $attendance->date,
                'status' => $attendance->status,
                'check_in' => $attendance->clock_in,
                'check_out' => $attendance->clock_out,
                'is_late' => $attendance->is_late ?? false,
                'is_early_out' => $attendance->is_early_leave ?? false,
                'late_minutes' => $attendance->late_minutes ?? 0,
                'early_checkout_minutes' => $attendance->early_leave_minutes ?? 0,
                'total_deduction' => $dayDeduction,
            ],
        ];
    }

    /**
     * Calculate late check-in deduction
     */
    private function calculateLateDeduction(int $lateMinutes, array $rules, float $dailyRate): float
    {
        if (empty($rules)) {
            return 0;
        }

        foreach ($rules as $rule) {
            $min = $rule['min_minutes'] ?? 0;
            $max = $rule['max_minutes'] ?? null;

            if ($lateMinutes >= $min && (is_null($max) || $lateMinutes <= $max)) {
                $amount = $rule['amount'] ?? 0;
                $type = $rule['type'] ?? 'fixed';

                if ($type === 'percentage') {
                    return ($dailyRate * $amount) / 100;
                } else {
                    return floatval($amount);
                }
            }
        }

        return 0;
    }

    /**
     * Calculate early check-out deduction
     */
    private function calculateEarlyDeduction(int $earlyMinutes, array $rules, float $dailyRate): float
    {
        if (empty($rules)) {
            return 0;
        }

        foreach ($rules as $rule) {
            $min = $rule['min_minutes'] ?? 0;
            $max = $rule['max_minutes'] ?? null;

            if ($earlyMinutes >= $min && (is_null($max) || $earlyMinutes <= $max)) {
                $amount = $rule['amount'] ?? 0;
                $type = $rule['type'] ?? 'fixed';

                if ($type === 'percentage') {
                    return ($dailyRate * $amount) / 100;
                } else {
                    return floatval($amount);
                }
            }
        }

        return 0;
    }

    /**
     * Save payroll details (allowances and deductions breakdown)
     */
    public function savePayrollDetails(Payroll $payroll, array $allowanceDetails, array $deductionDetails): void
    {
        // Save allowances
        foreach ($allowanceDetails as $detail) {
            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'type' => 'allowance',
                'name' => $detail['name'],
                'amount' => $detail['amount'],
                'description' => $detail['description'] ?? null,
            ]);
        }

        // Save deductions
        foreach ($deductionDetails as $detail) {
            PayrollDetail::create([
                'payroll_id' => $payroll->id,
                'type' => 'deduction',
                'name' => $detail['name'],
                'amount' => $detail['amount'],
                'description' => $detail['description'] ?? null,
            ]);
        }
    }

    /**
     * Update employee's pending deductions
     */
    public function updatePendingDeductions(Employee $employee, float $newPendingDeductions): void
    {
        $employee->update([
            'pending_deductions' => $newPendingDeductions,
        ]);
    }

    /**
     * Calculate overtime earnings for a monthly payroll period.
     * Mirrors the same logic as PayrollController::calculateOvertimeMetrics()
     * so generate and pay always agree.
     */
    public function calculateOvertimeForMonth(Employee $employee, string $month, float $basicSalary): array
    {
        $hrPolicy = \App\Models\Hr\HrPolicy::getEffectivePolicyForEmployee($employee->id);

        // Respect policy-level disable
        if ($hrPolicy && isset($hrPolicy->overtime_enabled) && !$hrPolicy->overtime_enabled) {
            return ['hours' => 0, 'earnings' => 0];
        }

        $shift = $employee->shift ?? \App\Models\Hr\Shift::where('is_default', true)->first();

        $shiftStartStr = $employee->custom_start_time
            ?: ($shift ? Carbon::parse($shift->start_time)->format('H:i:s') : '08:30:00');
        $shiftEndStr   = $employee->custom_end_time
            ?: ($shift ? Carbon::parse($shift->end_time)->format('H:i:s') : '18:00:00');

        // Standard shift hours
        $standardShiftHours = 9.0;
        if ($employee->custom_start_time || $employee->custom_end_time) {
            try {
                $s = Carbon::parse($shiftStartStr);
                $e = Carbon::parse($shiftEndStr);
                $diff = round($e->diffInMinutes($s) / 60, 2);
                if ($diff > 0) $standardShiftHours = $diff;
            } catch (\Exception $ex) {
                $standardShiftHours = 9.0;
            }
        } elseif ($shift && floatval($shift->total_hours) > 0) {
            $standardShiftHours = floatval($shift->total_hours);
        } else {
            try {
                $s = Carbon::parse($shiftStartStr);
                $e = Carbon::parse($shiftEndStr);
                $diff = round($e->diffInMinutes($s) / 60, 2);
                if ($diff > 0) $standardShiftHours = $diff;
            } catch (\Exception $ex) {
                $standardShiftHours = 9.0;
            }
        }

        $perDayRate   = ($basicSalary > 0) ? ($basicSalary / 30) : 0;
        $hourlySalary = ($standardShiftHours > 0) ? ($perDayRate / $standardShiftHours) : 0;
        $minOtMinutes = ($hrPolicy && isset($hrPolicy->overtime_min_minutes) && $hrPolicy->overtime_min_minutes > 0)
            ? intval($hrPolicy->overtime_min_minutes)
            : 60;

        $startDate   = Carbon::parse($month . '-01')->startOfMonth();
        $endDate     = $startDate->copy()->endOfMonth();

        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        $overtimeHoursTotal = 0;

        foreach ($attendances as $att) {
            $timeOut = $att->check_out_time ?: $att->clock_out;
            if ($timeOut) {
                $dateYmd     = Carbon::parse($att->date)->format('Y-m-d');
                $shiftEndDt  = Carbon::parse($dateYmd . ' ' . $shiftEndStr);
                $checkOutDt  = Carbon::parse($dateYmd . ' ' . Carbon::parse($timeOut)->format('H:i:s'));

                if ($checkOutDt->gt($shiftEndDt)) {
                    $extraMinutes = $checkOutDt->diffInMinutes($shiftEndDt);
                    if ($extraMinutes >= $minOtMinutes) {
                        $overtimeHoursTotal += round($extraMinutes / 60, 2);
                    }
                }
            } elseif (floatval($att->total_hours ?? 0) > ($standardShiftHours + ($minOtMinutes / 60))) {
                $overtimeHoursTotal += round(floatval($att->total_hours) - $standardShiftHours, 2);
            }
        }

        $overtimeHoursTotal = round($overtimeHoursTotal, 2);

        if ($hrPolicy && $hrPolicy->overtime_rate_type === 'fixed' && $hrPolicy->overtime_fixed_rate > 0) {
            $overtimeEarnings = round($overtimeHoursTotal * $hrPolicy->overtime_fixed_rate, 2);
        } else {
            $multiplier = ($hrPolicy && $hrPolicy->overtime_multiplier > 0) ? $hrPolicy->overtime_multiplier : 1.5;
            $overtimeEarnings = round($overtimeHoursTotal * $hourlySalary * $multiplier, 2);
        }

        return ['hours' => $overtimeHoursTotal, 'earnings' => $overtimeEarnings];
    }
}

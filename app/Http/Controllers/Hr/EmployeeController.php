<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Department;
use App\Models\Hr\Designation;
use App\Models\Hr\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        if (! auth()->user()->can('hr.employees.view')) {
            abort(403, 'Unauthorized action.');
        }
        $employees = Employee::with(['department', 'designation', 'shift', 'leaves' => function ($q) {
            $q->where('leave_type', 'Casual');
        }])->latest()->paginate(12);
        $departments = Department::all();
        $designations = Designation::all();
        $shifts = \App\Models\Hr\Shift::all();

        return view('hr.employees.index', compact('employees', 'departments', 'designations', 'shifts'));
    }

    public function store(Request $request)
    {
        $hasPortalAccess = $request->boolean('assign_portal_access');

        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:25',
            'department_id' => 'required|exists:hr_departments,id',
            'designation_id' => 'required|exists:hr_designations,id',
            'joining_date' => 'required|date',
            'password' => 'nullable|min:6',
            'punch_gap_minutes' => 'nullable|integer|min:1|max:120',
            'document_degree' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'document_certificate' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'document_hsc_marksheet' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'document_ssc_marksheet' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'document_cv' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ];

        if ($hasPortalAccess) {
            $rules['email'] = 'required|email|max:255|unique:hr_employees,email,'.$request->edit_id;
        } else {
            $rules['email'] = 'nullable|email|max:255|unique:hr_employees,email,'.$request->edit_id;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except(['document_degree', 'document_certificate', 'document_hsc_marksheet', 'document_ssc_marksheet', 'document_cv', 'password', 'casual_leave_days', 'assign_portal_access', 'basic_salary']);
        $data['is_docs_submitted'] = $request->has('is_docs_submitted') ? 1 : 0;

        // If no email provided (portal access disabled), generate internal unique email for DB
        if (empty($data['email'])) {
            $sanitizedName = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($request->first_name . '.' . $request->last_name));
            $data['email'] = $sanitizedName . '.' . rand(1000, 9999) . '@system.local';
        }

        // Handle Custom Shift Logic
        if ($request->shift_id === 'custom') {
            $data['shift_id'] = null; // No standard shift assigned
        } else {
            // Standard shift assigned, clear custom times
            $data['custom_start_time'] = null;
            $data['custom_end_time'] = null;
        }

        // Handle per-employee weekly off days override
        // If weekly_off_days submitted, save them; otherwise null (will use shift's off days)
        $data['weekly_off_days'] = $request->has('employee_weekly_off_days')
            ? $request->input('employee_weekly_off_days', [])
            : null;

        try {
            if ($request->filled('edit_id')) {
                if (! auth()->user()->can('hr.employees.edit')) {
                    return response()->json(['error' => 'Unauthorized action.'], 403);
                }
                $employee = Employee::findOrFail($request->edit_id);

                if ($hasPortalAccess) {
                    // Update or create User
                    if ($employee->user_id) {
                        $user = \App\Models\User::find($employee->user_id);
                        if ($user) {
                            $user->email = $data['email'];
                            $user->name = $request->first_name.' '.$request->last_name;
                            if ($request->filled('password')) {
                                $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
                            }
                            $user->save();
                        }
                    } else {
                        $user = \App\Models\User::where('email', $data['email'])->first();
                        if (! $user) {
                            $user = \App\Models\User::create([
                                'name' => $request->first_name.' '.$request->last_name,
                                'email' => $data['email'],
                                'password' => \Illuminate\Support\Facades\Hash::make($request->filled('password') ? $request->password : '12345678'),
                            ]);
                        } elseif ($request->filled('password')) {
                            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
                            $user->save();
                        }
                        $data['user_id'] = $user->id;
                    }
                } else {
                    // Portal access untoggled
                    $data['user_id'] = null;
                }

                $employee->update($data);
            } else {
                if (! auth()->user()->can('hr.employees.create')) {
                    return response()->json(['error' => 'Unauthorized action.'], 403);
                }

                if ($hasPortalAccess) {
                    // Create or link User Account
                    $user = \App\Models\User::where('email', $data['email'])->first();
                    if (! $user) {
                        $user = \App\Models\User::create([
                            'name' => $request->first_name.' '.$request->last_name,
                            'email' => $data['email'],
                            'password' => \Illuminate\Support\Facades\Hash::make($request->filled('password') ? $request->password : '12345678'),
                        ]);
                    } elseif ($request->filled('password')) {
                        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
                        $user->save();
                    }

                    $data['user_id'] = $user->id;
                } else {
                    $data['user_id'] = null;
                }

                $employee = Employee::create($data);
            }

            // Handle File Uploads (Create/Update in hr_employee_documents)
            $fileFields = ['document_degree', 'document_certificate', 'document_hsc_marksheet', 'document_ssc_marksheet', 'document_cv'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $path = $file->store('employee_docs', 'public');

                    $employee->documents()->updateOrCreate(
                        ['type' => str_replace('document_', '', $field)],
                        ['file_path' => $path, 'file_name' => $file->getClientOriginalName()]
                    );
                }
            }

            // Handle Casual Leave Days Sync
            if ($request->has('casual_leave_days')) {
                $rawDates = $request->casual_leave_days ? explode(',', $request->casual_leave_days) : [];
                $submittedDates = [];
                foreach ($rawDates as $rawDate) {
                    $trimmed = trim($rawDate);
                    if (! empty($trimmed)) {
                        try {
                            $submittedDates[] = \Carbon\Carbon::parse($trimmed)->format('Y-m-d');
                        } catch (\Exception $e) {
                            // Skip unparseable dates
                        }
                    }
                }
                $submittedDates = array_unique($submittedDates);

                // Get existing single-day Casual leaves
                $existingLeaves = $employee->leaves()
                    ->where('leave_type', 'Casual')
                    ->whereRaw('start_date = end_date')
                    ->get();

                $existingDates = $existingLeaves->pluck('start_date')->map(function ($d) {
                    return \Carbon\Carbon::parse($d)->format('Y-m-d');
                })->toArray();

                // 1. Create new leaves
                foreach ($submittedDates as $date) {
                    if (! in_array($date, $existingDates)) {
                        $employee->leaves()->create([
                            'leave_type' => 'Casual',
                            'start_date' => $date,
                            'end_date' => $date,
                            'reason' => 'Casual Leave assigned via Employee Form',
                            'status' => 'approved',
                        ]);
                    }
                }

                // 2. Delete removed leaves
                foreach ($existingLeaves as $leave) {
                    $leaveDate = \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d');
                    if (! in_array($leaveDate, $submittedDates)) {
                        $leave->delete();
                    }
                }
            }

            return response()->json(['success' => 'Employee saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()], 500);
        }
    }

    public function destroy(Employee $employee)
    {
        if (! auth()->user()->can('hr.employees.delete')) {
            abort(403, 'Unauthorized action.');
        }
        // Delete User Account
        if ($employee->user_id) {
            \App\Models\User::destroy($employee->user_id);
        }
        // Delete all casual leave records for this employee
        $employee->leaves()->where('leave_type', 'Casual')->delete();
        $employee->delete();

        return response()->json(['success' => 'Employee deleted successfully']);
    }

    /**
     * Get face encodings for all employees (for Kiosk)
     */
    public function getEncodings()
    {
        $employees = Employee::whereNotNull('face_encoding')
            ->where('status', 'active')
            ->select('id', 'first_name', 'last_name', 'face_encoding', 'face_photo', 'designation_id', 'department_id')
            ->with(['department', 'designation'])
            ->get();

        $data = $employees->map(function ($emp) {
            return [
                'id' => $emp->id,
                'name' => $emp->full_name,
                'department' => $emp->department->name ?? 'N/A',
                'designation' => $emp->designation->name ?? 'N/A',
                'photo' => $emp->face_photo ? asset($emp->face_photo) : null,
                'descriptor' => $emp->face_encoding,
            ];
        });

        return response()->json($data);
    }

    /**
     * Store face encoding for an employee
     */
    public function storeFace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:hr_employees,id',
            'descriptor' => 'required|array',
            'image' => 'nullable|string', // Base64 image
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $employee = Employee::findOrFail($request->employee_id);
        $employee->face_encoding = $request->descriptor;

        // Save face photo if provided
        if ($request->image) {
            $imageData = explode(',', $request->image);
            if (count($imageData) > 1) {
                $decoded = base64_decode($imageData[1]);
                $fileName = 'face_'.$employee->id.'_'.time().'.jpg';
                $path = 'uploads/faces/';

                if (! file_exists(public_path($path))) {
                    mkdir(public_path($path), 0755, true);
                }

                file_put_contents(public_path($path.$fileName), $decoded);
                $employee->face_photo = $path.$fileName;
            }
        }

        $employee->save();

        return response()->json(['success' => 'Face registered successfully for '.$employee->full_name]);
    }

    /**
     * Get monthly attendance and payroll details for a single employee
     */
    public function monthlyDetail(Employee $employee, Request $request)
    {
        if (! auth()->user()->can('hr.employees.view') && ! auth()->user()->can('hr.attendance.view')) {
            abort(403, 'Unauthorized action.');
        }

        $monthStr = $request->get('month', \Carbon\Carbon::now()->format('Y-m'));
        $startDate = \Carbon\Carbon::parse($monthStr . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $today = \Carbon\Carbon::today();

        $employee->load(['department', 'designation', 'shift', 'salaryStructure']);

        // Attendance records for the month
        $attendances = \App\Models\Hr\Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(function($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });

        // Leaves for the month
        $leaves = \App\Models\Hr\Leave::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $endDate->format('Y-m-d'))
            ->whereDate('end_date', '>=', $startDate->format('Y-m-d'))
            ->get();

        // Holidays for the month
        $holidays = \App\Models\Hr\Holiday::whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy(function($item) {
                return \Carbon\Carbon::parse($item->date)->format('Y-m-d');
            });

        // Weekly off days
        $weeklyOffDays = $employee->weekly_off_days ?? ($employee->shift->weekly_off_days ?? ['Sunday']);

        // Build daily timeline
        $dailyRecords = [];
        $presentCount = 0;
        $lateCount = 0;
        $absentCount = 0;
        $leaveCount = 0;
        $holidayCount = 0;
        $offDayCount = 0;
        $totalWorkingHours = 0;
        $totalLateMinutes = 0;

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dateFormatted = $currentDate->format('Y-m-d');
            $dayName = $currentDate->format('l');
            $isPastOrToday = $currentDate->lte($today);

            $att = $attendances[$dateFormatted] ?? null;
            $holiday = $holidays[$dateFormatted] ?? null;
            $leave = $leaves->first(function($l) use ($dateFormatted) {
                return $dateFormatted >= \Carbon\Carbon::parse($l->start_date)->format('Y-m-d') &&
                       $dateFormatted <= \Carbon\Carbon::parse($l->end_date)->format('Y-m-d');
            });

            $status = 'upcoming';
            $statusBadge = 'secondary';
            $clockIn = '-';
            $clockOut = '-';
            $hours = 0;
            $lateMins = 0;
            $notes = '';

            if ($att) {
                $clockIn = $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('h:i A') : ($att->clock_in ?? '-');
                $clockOut = $att->check_out_time ? \Carbon\Carbon::parse($att->check_out_time)->format('h:i A') : ($att->clock_out ?? '-');
                $hours = (float)($att->total_hours ?? 0);
                $lateMins = (int)($att->late_minutes ?? 0);
                $notes = $att->check_in_location ?? '';

                if ($att->status == 'late' || ($att->status == 'present' && $att->is_late)) {
                    $status = 'late';
                    $statusBadge = 'warning';
                    $lateCount++;
                    $totalLateMinutes += $lateMins;
                    $totalWorkingHours += $hours;
                } elseif ($att->status == 'present') {
                    $status = 'present';
                    $statusBadge = 'success';
                    $presentCount++;
                    $totalWorkingHours += $hours;
                } elseif ($att->status == 'leave') {
                    $status = 'leave';
                    $statusBadge = 'info';
                    $leaveCount++;
                } else {
                    $status = 'absent';
                    $statusBadge = 'danger';
                    if ($isPastOrToday) $absentCount++;
                }
            } elseif ($leave) {
                $status = 'leave (' . $leave->leave_type . ')';
                $statusBadge = 'info';
                $notes = $leave->reason ?? 'Approved Leave';
                if ($isPastOrToday) $leaveCount++;
            } elseif ($holiday) {
                $status = 'holiday';
                $statusBadge = 'primary';
                $notes = $holiday->name;
                if ($isPastOrToday) $holidayCount++;
            } elseif (in_array($dayName, $weeklyOffDays)) {
                $status = 'off-day';
                $statusBadge = 'dark';
                $notes = 'Weekly Off (' . $dayName . ')';
                if ($isPastOrToday) $offDayCount++;
            } elseif ($isPastOrToday) {
                $status = 'absent';
                $statusBadge = 'danger';
                $absentCount++;
            }

            $dailyRecords[] = [
                'date' => $currentDate->format('d/m/Y'),
                'day' => $dayName,
                'status' => ucfirst($status),
                'status_badge' => $statusBadge,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'hours' => $hours,
                'late_mins' => $lateMins,
                'notes' => $notes,
            ];

            $currentDate->addDay();
        }

        // Fetch Payroll record for this month if available
        $payroll = \App\Models\Hr\Payroll::where('employee_id', $employee->id)
            ->where('month', $monthStr)
            ->latest()
            ->first();

        $summary = [
            'month_name' => $startDate->format('F Y'),
            'month_code' => $monthStr,
            'total_days' => $startDate->daysInMonth,
            'present' => $presentCount,
            'late' => $lateCount,
            'absent' => $absentCount,
            'leave' => $leaveCount,
            'holiday' => $holidayCount,
            'off_day' => $offDayCount,
            'total_hours' => round($totalWorkingHours, 2),
            'total_late_minutes' => $totalLateMinutes,
        ];

        return response()->json([
            'success' => true,
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->full_name,
                'department' => $employee->department->name ?? 'N/A',
                'designation' => $employee->designation->name ?? 'N/A',
                'shift' => $employee->shift->name ?? 'Default',
                'joining_date' => $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d/m/Y') : 'N/A',
            ],
            'summary' => $summary,
            'daily_records' => $dailyRecords,
            'payroll' => $payroll ? [
                'id' => $payroll->id,
                'status' => ucfirst($payroll->status ?? 'pending'),
                'gross_salary' => number_format($payroll->gross_salary ?? 0, 2),
                'total_allowances' => number_format($payroll->total_allowances ?? 0, 2),
                'total_deductions' => number_format($payroll->total_deductions ?? 0, 2),
                'net_salary' => number_format($payroll->net_salary ?? 0, 2),
                'payment_type' => ucfirst($payroll->payroll_type ?? 'monthly'),
            ] : null
        ]);
    }
}

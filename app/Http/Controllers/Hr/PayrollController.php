<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Attendance;
use App\Models\Hr\Employee;
use App\Models\Hr\Payroll;
use App\Services\PayrollCalculationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollCalculationService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Display paginated payrolls with filters
     */
    public function index(Request $request)
    {
        if (! auth()->user()->can('hr.payroll.view')) {
            abort(403, 'Unauthorized action.');
        }

        $query = Payroll::with(['employee.designation', 'employee.department', 'details']);

        $availableMonths = Payroll::select('month')->distinct()->orderBy('month', 'desc')->pluck('month');
        $selectedMonth = $request->get('month');
        if (! $request->has('month')) {
            $selectedMonth = $availableMonths->first() ?? date('Y-m');
        }

        // Apply filters
        if ($request->filled('type')) {
            $query->where('payroll_type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($selectedMonth && $selectedMonth !== 'all') {
            $query->where('month', $selectedMonth);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $excludedCodes = ['AR', 'AP', 'SALES', 'PURCHASE'];
        $excludedTitles = ['Accounts Receivable', 'Accounts Payable', 'Sales Revenue', 'Purchase Expense'];

        $payrolls = $query->latest()->paginate(50);
        $this->attachAttendanceMetrics($payrolls);
        $employees = Employee::all();
        $accounts = \App\Models\Account::whereNotIn('account_code', $excludedCodes)
            ->whereNotIn('title', $excludedTitles)
            ->orderBy('title')
            ->get();

        return view('hr.payroll.index', compact('payrolls', 'employees', 'availableMonths', 'selectedMonth', 'accounts'))->with('activeTab', 'all');
    }

    /**
     * Show monthly payrolls only
     */
    public function monthly(Request $request)
    {
        if (! auth()->user()->can('hr.payroll.view')) {
            abort(403, 'Unauthorized action.');
        }

        $query = Payroll::with(['employee.designation', 'employee.department', 'details'])
            ->monthly();

        $availableMonths = Payroll::monthly()->select('month')->distinct()->orderBy('month', 'desc')->pluck('month');
        $selectedMonth = $request->get('month');
        if (! $request->has('month')) {
            $selectedMonth = $availableMonths->first() ?? date('Y-m');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($selectedMonth && $selectedMonth !== 'all') {
            $query->where('month', $selectedMonth);
        }

        $excludedCodes = ['AR', 'AP', 'SALES', 'PURCHASE'];
        $excludedTitles = ['Accounts Receivable', 'Accounts Payable', 'Sales Revenue', 'Purchase Expense'];

        $payrolls = $query->latest()->paginate(50);
        $this->attachAttendanceMetrics($payrolls);
        $employees = Employee::whereHas('salaryStructure', function ($q) {
            $q->whereIn('salary_type', ['salary', 'both']);
        })->get();
        $accounts = \App\Models\Account::whereNotIn('account_code', $excludedCodes)
            ->whereNotIn('title', $excludedTitles)
            ->orderBy('title')
            ->get();

        return view('hr.payroll.index', compact('payrolls', 'employees', 'availableMonths', 'selectedMonth', 'accounts'))->with('activeTab', 'monthly');
    }

    /**
     * Show daily payrolls only
     */
    public function daily(Request $request)
    {
        if (! auth()->user()->can('hr.payroll.view')) {
            abort(403, 'Unauthorized action.');
        }

        $query = Payroll::with(['employee.designation', 'employee.department', 'details'])
            ->daily();

        $availableMonths = Payroll::daily()->select('month')->distinct()->orderBy('month', 'desc')->pluck('month');
        $selectedMonth = $request->get('month');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($selectedMonth && $selectedMonth !== 'all') {
            $query->where('month', $selectedMonth);
        }

        $excludedCodes = ['AR', 'AP', 'SALES', 'PURCHASE'];
        $excludedTitles = ['Accounts Receivable', 'Accounts Payable', 'Sales Revenue', 'Purchase Expense'];

        $payrolls = $query->latest()->paginate(50);
        $this->attachAttendanceMetrics($payrolls);
        $employees = Employee::whereHas('salaryStructure', function ($q) {
            $q->where('use_daily_wages', true);
        })->get();
        $accounts = \App\Models\Account::whereNotIn('account_code', $excludedCodes)
            ->whereNotIn('title', $excludedTitles)
            ->orderBy('title')
            ->get();

        return view('hr.payroll.index', compact('payrolls', 'employees', 'availableMonths', 'selectedMonth', 'accounts'))->with('activeTab', 'daily');
    }

    /**
     * Attach attendance breakdown metrics for payroll table display
     */
    private function attachAttendanceMetrics($payrolls)
    {
        foreach ($payrolls as $payroll) {
            if ($payroll->payroll_type === 'daily') {
                $dateStr = $payroll->month;
                $att = Attendance::where('employee_id', $payroll->employee_id)
                    ->where('date', $dateStr)
                    ->first();

                $payroll->attendance_days = 1;
                $payroll->attendance_present = ($att && in_array(strtolower($att->status ?? ''), ['present', 'late'])) ? 1 : 0;
                $payroll->attendance_late = ($att && ($att->is_late || strtolower($att->status ?? '') === 'late')) ? 1 : 0;
                $payroll->attendance_absent = ($att && strtolower($att->status ?? '') === 'absent') ? 1 : 0;
                $otHrs = ($att && ($att->total_hours ?? 0) > 8) ? round($att->total_hours - 8, 1) : 0;
                $payroll->ot_hours = $otHrs;
                $payroll->ot_amount = round($otHrs * (($payroll->basic_salary ?: 0) / 8 * 1.5), 2);
            } else {
                $monthStr = (strlen($payroll->month ?? '') === 7) ? $payroll->month : Carbon::now()->format('Y-m');
                try {
                    $startDate = Carbon::parse($monthStr . '-01')->startOfMonth();
                    $endDate = Carbon::parse($monthStr . '-01')->endOfMonth();
                    $daysInMonth = $startDate->daysInMonth;
                } catch (\Exception $e) {
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    $daysInMonth = 30;
                }

                $attendances = Attendance::where('employee_id', $payroll->employee_id)
                    ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->get();

                $attBreakdown = $this->getAttendanceBreakdown($payroll);

                $presentCount = $attBreakdown['days_present_total'] ?? $attendances->filter(fn($a) => in_array(strtolower($a->status ?? ''), ['present', 'late']))->count();
                $lateCount = $attBreakdown['late_check_ins'] ?? $attendances->filter(fn($a) => strtolower($a->status ?? '') === 'late' || $a->is_late)->count();
                $absentCount = $attBreakdown['days_absent'] ?? $attendances->filter(fn($a) => strtolower($a->status ?? '') === 'absent')->count();

                $otData = $this->calculateOvertimeMetrics($payroll, $attendances);

                $payroll->attendance_days = $daysInMonth;
                $payroll->attendance_present = $presentCount;
                $payroll->attendance_late = $lateCount;
                $payroll->attendance_absent = $absentCount;
                $payroll->ot_hours = $otData['hours'];
                $payroll->ot_amount = $otData['earnings'];

                $gross = $payroll->basic_salary + $payroll->allowances + $payroll->manual_allowances + $otData['earnings'];
                $attDeduction = floatval($attBreakdown['total_deduction'] ?? 0);
                $otherDeductions = floatval($payroll->deductions) + floatval($payroll->manual_deductions) + floatval($payroll->carried_forward_deduction);

                $loanCut = 0;
                if (floatval($payroll->loan_deduction) > 0) {
                    $loanCut = floatval($payroll->loan_deduction);
                } else {
                    $activeLoans = \App\Models\Hr\Loan::where('employee_id', $payroll->employee_id)->active()->get();
                    foreach ($activeLoans as $l) {
                        $rem = max(0, $l->amount - $l->paid_amount);
                        if ($l->installment_amount > 0) {
                            $loanCut += min($l->installment_amount, $rem);
                        } else {
                            $loanCut += $rem;
                        }
                    }
                }

                $totalDeduction = $attDeduction + $otherDeductions + $loanCut;
                $netSalary = max(0, $gross - $totalDeduction);

                $payroll->calculated_gross = $gross;
                $payroll->calculated_total_deductions = $totalDeduction;
                $payroll->calculated_net_salary = $netSalary;
            }
        }
    }

    /**
     * Get detailed payroll breakdown
     */
    public function details($id)
    {
        if (! auth()->user()->can('hr.payroll.view')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $payroll = Payroll::with(['employee.designation', 'details', 'reviewer'])->findOrFail($id);

        // Format payroll period based on type
        $payrollPeriod = $this->formatPayrollPeriod($payroll);

        // Get allowance details
        $allowanceDetails = $payroll->details()->where('type', 'allowance')->get()->map(function ($detail) {
            return [
                'name' => $detail->name,
                'amount' => $detail->amount,
                'description' => $detail->description,
                'calculation_type' => $detail->description ? 'fixed' : 'fixed', // Can enhance this later
            ];
        });

        // Get deduction details (non-attendance)
        $deductionDetails = $payroll->details()->where('type', 'deduction')->get()->map(function ($detail) {
            return [
                'name' => $detail->name,
                'amount' => $detail->amount,
                'description' => $detail->description,
            ];
        });

        // Get attendance breakdown for the payroll period
        $attendanceBreakdown = $this->getAttendanceBreakdown($payroll);

        // Get active loans summary for employee (includes approved and pending loans with remaining balance)
        $activeLoans = \App\Models\Hr\Loan::where('employee_id', $payroll->employee_id)
            ->active()
            ->get();

        $suggestedInstallment = 0;
        foreach ($activeLoans as $l) {
            $rem = max(0, $l->amount - $l->paid_amount);
            $scheduled = \App\Models\Hr\LoanScheduledDeduction::where('loan_id', $l->id)
                ->where('deduction_month', $payroll->month)
                ->where('status', 'pending')
                ->first();

            if ($scheduled && $scheduled->amount > 0) {
                $suggestedInstallment += min($scheduled->amount, $rem);
            } elseif ($l->installment_amount > 0) {
                $suggestedInstallment += min($l->installment_amount, $rem);
            } else {
                $suggestedInstallment += $rem;
            }
        }

        $loanSummary = [
            'has_active_loan' => $activeLoans->count() > 0,
            'total_loan_amount' => (float) $activeLoans->sum('amount'),
            'total_paid_amount' => (float) $activeLoans->sum('paid_amount'),
            'total_remaining' => (float) ($activeLoans->sum('amount') - $activeLoans->sum('paid_amount')),
            'suggested_installment' => (float) $suggestedInstallment,
            'active_loans_count' => $activeLoans->count(),
            'current_saved_deduction' => (float) ($payroll->loan_deduction ?? 0),
            'loans' => $activeLoans->map(function ($l) {
                return [
                    'id' => $l->id,
                    'reason' => $l->reason,
                    'amount' => (float) $l->amount,
                    'paid_amount' => (float) $l->paid_amount,
                    'remaining' => (float) ($l->amount - $l->paid_amount),
                    'installment_amount' => (float) $l->installment_amount,
                ];
            }),
        ];

        $overtimeEarnings = floatval($attendanceBreakdown['overtime_earnings'] ?? 0);
        $totalGrossEarnings = $payroll->basic_salary + $payroll->allowances + $payroll->manual_allowances + $overtimeEarnings;

        return response()->json([
            'payroll' => $payroll,
            'payroll_period' => $payrollPeriod,
            'breakdown' => [
                'earnings' => [
                    'basic_salary' => $payroll->basic_salary,
                    'allowances' => $payroll->allowances,
                    'manual_allowances' => $payroll->manual_allowances,
                    'overtime' => $overtimeEarnings,
                    'total' => max($payroll->gross_salary, $totalGrossEarnings),
                ],
                'deductions' => [
                    'fixed_deductions' => $payroll->deductions,
                    'attendance_deductions' => $payroll->attendance_deductions,
                    'carried_forward' => $payroll->carried_forward_deduction,
                    'carried_forward_to_next' => $payroll->carried_forward_to_next,
                    'manual_deductions' => $payroll->manual_deductions,
                    'loan_deduction' => $payroll->loan_deduction ?? 0,
                    'total' => $payroll->total_deductions,
                ],
                'net_payable' => $payroll->net_salary,
            ],
            'allowance_details' => $allowanceDetails,
            'deduction_details' => $deductionDetails,
            'attendance_breakdown' => $attendanceBreakdown,
            'loan_summary' => $loanSummary,
        ]);
    }

    /**
     * Format payroll period based on payroll type
     */
    private function formatPayrollPeriod($payroll): array
    {
        if ($payroll->payroll_type === 'daily') {
            // For daily: Display Date, Month, and Year (e.g., "15 March 2026")
            $date = \Carbon\Carbon::parse($payroll->month);
            return [
                'type' => 'daily',
                'formatted' => $date->format('d/m/Y'),
                'day' => $date->format('d'),
                'month' => $date->format('F'),
                'year' => $date->format('Y'),
            ];
        } else {
            // For monthly: Display Month and Year only (e.g., "March 2026")
            $date = \Carbon\Carbon::parse($payroll->month . '-01');
            return [
                'type' => 'monthly',
                'formatted' => $date->format('F Y'),
                'month' => $date->format('F'),
                'year' => $date->format('Y'),
            ];
        }
    }

    /**
     * Get attendance breakdown for payroll period
     */
    private function getAttendanceBreakdown($payroll): array
    {
        $employee = $payroll->employee;
        
        // Get HR Terms & Policy for Employee
        $hrPolicy = \App\Models\Hr\HrPolicy::getEffectivePolicyForEmployee($employee->id);
        $perDayRate = ($payroll->basic_salary > 0) ? ($payroll->basic_salary / 30) : 0;
        
        if ($payroll->payroll_type === 'monthly') {
            // For monthly payroll, get attendance stats for the entire month
            $startDate = \Carbon\Carbon::parse($payroll->month . '-01')->startOfMonth();
            $endDate = \Carbon\Carbon::parse($payroll->month . '-01')->endOfMonth();
            
            $totalWorkingDays = $this->getWorkingDaysInRange($startDate, $endDate);
            
            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->orderBy('date', 'asc')
                ->get();
            
            $hasData = $attendances->count() > 0;
            $daysLeave = $attendances->filter(fn($att) => strtolower($att->status) === 'leave')->count();

            $shift = $employee->shift ?? \App\Models\Hr\Shift::where('is_default', true)->first();
            $shiftStartStr = ($shift ? $shift->start_time : '08:30:00');
            $graceMins = $shift ? ($shift->grace_minutes ?? 5) : 5;
            $lateThreshold = \Carbon\Carbon::parse($shiftStartStr)->addMinutes($graceMins)->format('H:i:s');

            $lateAttendancesList = $attendances->filter(function ($att) use ($lateThreshold) {
                if ($att->is_late || strtolower($att->status ?? '') === 'late') {
                    return true;
                }
                if ($att->check_in_time) {
                    $checkInTimeStr = \Carbon\Carbon::parse($att->check_in_time)->format('H:i:s');
                    return $checkInTimeStr > $lateThreshold;
                }
                return false;
            })->values();

            $lateCheckIns = $lateAttendancesList->count();
            $daysPresentOnTime = $attendances->filter(fn($att) => strtolower($att->status) === 'present' && !$att->is_late)->count();
            $daysPresentTotal = $daysPresentOnTime + $lateCheckIns;
            $daysAbsent = $attendances->filter(fn($att) => strtolower($att->status) === 'absent')->count();
            $totalDeductionDays = $daysAbsent + $daysLeave;
            $earlyCheckOuts = $attendances->where('is_early_leave', true)->count();
            
            // Calculate deduction breakdown
            $lateMinutesTotal = $attendances->sum('late_minutes');
            $earlyMinutesTotal = $attendances->sum('early_leave_minutes');
            $totalHoursWorked = round($attendances->sum('total_hours'), 1);
            
            // Calculate Absence Deduction based on HR Policy
            if ($hrPolicy && $hrPolicy->absence_deduction_type === 'fixed') {
                $absenceDeduction = round($totalDeductionDays * $hrPolicy->absence_fixed_amount, 2);
            } else {
                $absenceDeduction = round($totalDeductionDays * $perDayRate, 2);
            }

            // Calculate Late Check-in Deduction based on HR Policy
            $lateDeduction = 0;
            $latePenaltyRate = 0;
            if ($hrPolicy && $hrPolicy->late_penalty_type === '3_lates_1_day') {
                // Every 3 late check-ins deduct 1 day salary
                $lateDeductionDays = floor($lateCheckIns / 3);
                $lateDeduction = round($lateDeductionDays * $perDayRate, 2);
                $latePenaltyRate = round($perDayRate / 3, 2);
            } elseif ($hrPolicy && $hrPolicy->late_penalty_type === 'fixed_per_instance') {
                $lateDeduction = round($lateCheckIns * $hrPolicy->late_penalty_amount, 2);
                $latePenaltyRate = $hrPolicy->late_penalty_amount;
            }

            // Calculate Overtime Hours & Earnings based on unified helper
            $otData = $this->calculateOvertimeMetrics($payroll, $attendances);
            $overtimeHoursTotal = $otData['hours'];
            $overtimeEarnings = $otData['earnings'];
            
            $perDayDeduction = round($perDayRate, 2);
            $absentDays = $attendances->filter(fn($att) => in_array(strtolower($att->status), ['absent', 'leave']))
                ->map(function ($att) use ($perDayDeduction) {
                    return [
                        'date' => \Carbon\Carbon::parse($att->date)->format('d/m/Y'),
                        'day' => \Carbon\Carbon::parse($att->date)->format('l'),
                        'status' => ucfirst($att->status),
                        'deduction' => $perDayDeduction,
                    ];
                })->values()->toArray();

            $earlyPenalty = floatval($policy['early_penalty_per_instance'] ?? 0);
            $earlyRules = $policy['early_rules'] ?? [];
            $lateDays = $lateAttendancesList->map(function ($att, $idx) use ($hrPolicy, $perDayRate) {
                $timeIn = $att->check_in_time ?: $att->clock_in;
                $itemDeduction = 0;

                if ($hrPolicy && $hrPolicy->late_penalty_type === '3_lates_1_day') {
                    // Show full 1 day deduction on every 3rd late arrival
                    $itemDeduction = (($idx + 1) % 3 === 0) ? $perDayRate : 0;
                } elseif ($hrPolicy && $hrPolicy->late_penalty_type === 'fixed_per_instance') {
                    $itemDeduction = $hrPolicy->late_penalty_amount;
                }

                return [
                    'date' => \Carbon\Carbon::parse($att->date)->format('d/m/Y'),
                    'day' => \Carbon\Carbon::parse($att->date)->format('l'),
                    'check_in' => $timeIn ? \Carbon\Carbon::parse($timeIn)->format('h:i A') : 'N/A',
                    'late_minutes' => $att->late_minutes ?? 0,
                    'deduction' => round($itemDeduction, 2),
                ];
            })->toArray();

            $lateDeductionFromDays = array_sum(array_column($lateDays, 'deduction'));
            if ($lateDeductionFromDays > 0) {
                $lateDeduction = $lateDeductionFromDays;
            }

            $earlyDeduction = 0;
            $earlyDays = $attendances->where('is_early_leave', true)->map(function ($att) use ($earlyPenalty, $earlyRules, $perDayRate) {
                $timeOut = $att->check_out_time ?: $att->clock_out;
                $itemDeduction = $earlyPenalty;
                if ($itemDeduction <= 0 && !empty($earlyRules)) {
                    $itemDeduction = $this->calculateEarlyRuleDeduction($att->early_leave_minutes ?? 0, $earlyRules, $perDayRate);
                }
                return [
                    'date' => \Carbon\Carbon::parse($att->date)->format('d/m/Y'),
                    'day' => \Carbon\Carbon::parse($att->date)->format('l'),
                    'check_out' => $timeOut ? \Carbon\Carbon::parse($timeOut)->format('h:i A') : 'N/A',
                    'early_minutes' => $att->early_leave_minutes ?? 0,
                    'deduction' => round($itemDeduction, 2),
                ];
            })->values()->toArray();

            $earlyDeduction = array_sum(array_column($earlyDays, 'deduction'));
            
            return [
                'has_data' => $hasData,
                'data_message' => $hasData ? null : 'Attendance data incomplete for this period',
                'has_attendance_deductions' => $payroll->attendance_deductions > 0,
                'total_working_days' => $totalWorkingDays,
                'total_days_in_month' => $startDate->daysInMonth,
                'month_start_formatted' => $startDate->format('d/m/Y'),
                'month_end_formatted' => $endDate->format('d/m/Y'),
                'days_present' => $daysPresentOnTime,
                'days_present_total' => $daysPresentTotal,
                'days_absent' => $daysAbsent,
                'days_leave' => $daysLeave,
                'late_check_ins' => $lateCheckIns,
                'early_check_outs' => $earlyCheckOuts,
                'late_minutes_total' => $lateMinutesTotal,
                'early_minutes_total' => $earlyMinutesTotal,
                'total_hours_worked' => $totalHoursWorked,
                'overtime_hours' => $overtimeHoursTotal ?? 0,
                'overtime_earnings' => $overtimeEarnings ?? 0,
                'total_deduction' => round($absenceDeduction + $lateDeduction + $earlyDeduction, 2),
                'deduction_details' => [
                    'absence_deduction' => $absenceDeduction,
                    'late_deduction' => ($lateDeduction == 0 && $absenceDeduction == 0 && $payroll->attendance_deductions > 0) ? $payroll->attendance_deductions : $lateDeduction,
                    'early_deduction' => $earlyDeduction,
                    'per_day_rate' => $perDayDeduction,
                    'late_penalty_rate' => $latePenaltyRate ?? 0,
                    'early_penalty_rate' => $earlyPenalty,
                ],
                // Detailed day-by-day records
                'absent_records' => $absentDays,
                'late_records' => $lateDays,
                'early_records' => $earlyDays,
            ];
        } else {
            // For daily payroll
            $date = \Carbon\Carbon::parse($payroll->month);
            
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('date', $date->format('Y-m-d'))
                ->first();
            
            if ($attendance) {
                // Get specific deduction amounts from saved details
                $lateDeductionAmount = $payroll->details
                    ->filter(fn($d) => str_contains(strtolower($d->name), 'late check-in'))
                    ->sum('amount');
                    
                $earlyDeductionAmount = $payroll->details
                    ->filter(fn($d) => str_contains(strtolower($d->name), 'early leave') || str_contains(strtolower($d->name), 'early check-out'))
                    ->sum('amount');

                return [
                    'has_data' => true,
                    'has_attendance_deductions' => $payroll->attendance_deductions > 0,
                    'date' => $date->format('Y-m-d'),
                    'formatted_date' => $date->format('d/m/Y'),
                    'day' => $date->format('l'),
                    'status' => $attendance->status,
                    'check_in' => $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('h:i A') : 'N/A',
                    'check_out' => $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('h:i A') : 'N/A',
                    'is_late' => $attendance->is_late,
                    'is_early_out' => $attendance->is_early_leave,
                    'late_minutes' => $attendance->late_minutes ?? 0,
                    'early_checkout_minutes' => $attendance->early_leave_minutes ?? 0,
                    'total_deduction' => $payroll->attendance_deductions,
                    'late_deduction_amount' => $lateDeductionAmount,
                    'early_deduction_amount' => $earlyDeductionAmount,
                ];
            }
            
            return [
                'has_data' => false,
                'data_message' => 'Attendance data incomplete for this period',
                'has_attendance_deductions' => false,
                'date' => $date->format('Y-m-d'),
                'status' => 'No attendance record',
            ];
        }
    }



    /**
     * Calculate working days in a date range (excluding employee-specific weekly off days)
     * @param array $weeklyOffDays Day numbers (0=Sun, 1=Mon...6=Sat). Defaults to [0,6] (Sat+Sun)
     */
    private function getWorkingDaysInRange($startDate, $endDate, array $weeklyOffDays = [0, 6]): int
    {
        $workingDays = 0;
        $current = $startDate->copy();
        
        while ($current->lte($endDate)) {
            // Exclude configured weekly off days
            if (!in_array($current->dayOfWeek, $weeklyOffDays)) {
                $workingDays++;
            }
            $current->addDay();
        }
        
        return $workingDays;
    }

    /**
     * Generate payroll (manual or single employee)
     */
    public function generate(Request $request)
    {
        if (! auth()->user()->can('hr.payroll.create')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:hr_employees,id',
            'payroll_type' => 'required|in:monthly,daily',
            'month' => 'required_if:payroll_type,monthly',
            'date' => 'required_if:payroll_type,daily',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $employee = Employee::findOrFail($request->employee_id);

            // Check if payroll already exists
            $periodKey = $request->payroll_type === 'monthly' ? $request->month : $request->date;
            $exists = Payroll::where('employee_id', $employee->id)
                ->where('month', $periodKey)
                ->where('payroll_type', $request->payroll_type)
                ->exists();

            if ($exists) {
                return response()->json([
                    'errors' => ['general' => ['Payroll already generated for this employee for the selected period.']],
                ], 422);
            }

            if ($request->payroll_type === 'monthly') {
                $payrollData = $this->payrollService->calculateMonthlyPayroll($employee, $request->month);
            } else { // daily
                $attendance = Attendance::where('employee_id', $employee->id)
                    ->whereDate('date', $request->date)
                    ->first();

                if (! $attendance || ! $attendance->clock_out) {
                    return response()->json([
                        'errors' => ['date' => ['No completed attendance record found for this date.']],
                    ], 422);
                }

                $payrollData = $this->payrollService->calculateDailyPayroll($employee, $attendance);
            }

            // Create payroll
            $payroll = Payroll::create(array_merge(
                ['employee_id' => $employee->id],
                Arr::except($payrollData, ['allowance_details', 'deduction_details', 'new_pending_deductions'])
            ));

            // Save detailed breakdown
            $this->payrollService->savePayrollDetails(
                $payroll,
                $payrollData['allowance_details'] ?? [],
                $payrollData['deduction_details'] ?? []
            );

            // Update pending deductions for daily payroll
            if ($request->payroll_type === 'daily') {
                $this->payrollService->updatePendingDeductions(
                    $employee,
                    $payrollData['new_pending_deductions'] ?? 0
                );
            }

            DB::commit();

            return response()->json([
                'success' => 'Payroll generated successfully.',
                'reload' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'errors' => ['general' => [$e->getMessage()]],
            ], 422);
        }
    }

    /**
     * Generate monthly payrolls for all salaried employees
     */
    public function generateMonthly(Request $request)
    {
        if (! auth()->user()->can('hr.payroll.create')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'month' => 'required|date_format:Y-m',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $activeEmployees = Employee::where('status', 'active')->get();
            $employees = $activeEmployees->filter(function ($emp) {
                $structure = $this->payrollService->getEffectiveSalaryStructure($emp);
                return $structure && in_array($structure->salary_type, ['salary', 'both']);
            });

            $generated = 0;
            $skipped = 0;
            $errors = [];

            foreach ($employees as $employee) {
                // Skip if already exists
                $exists = Payroll::where('employee_id', $employee->id)
                    ->where('month', $request->month)
                    ->where('payroll_type', 'monthly')
                    ->exists();

                if ($exists) {
                    $skipped++;

                    continue;
                }

                try {
                    $payrollData = $this->payrollService->calculateMonthlyPayroll($employee, $request->month);

                    $payroll = Payroll::create(array_merge(
                        ['employee_id' => $employee->id],
                        Arr::except($payrollData, ['allowance_details', 'deduction_details'])
                    ));

                    $this->payrollService->savePayrollDetails(
                        $payroll,
                        $payrollData['allowance_details'] ?? [],
                        $payrollData['deduction_details'] ?? []
                    );

                    $generated++;
                } catch (\Exception $e) {
                    $errors[] = $employee->full_name.': '.$e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => "Monthly payroll generated for {$generated} employees. {$skipped} skipped (already exists).",
                'errors' => $errors,
                'reload' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'errors' => ['general' => [$e->getMessage()]],
            ], 422);
        }
    }

    /**
     * Generate daily payrolls for all daily wage employees for a specific date
     */
    public function generateDaily(Request $request)
    {
        if (! auth()->user()->can('hr.payroll.create')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Fetch employees configured for daily wages
            $activeEmployees = Employee::where('status', 'active')->get();
            $employees = $activeEmployees->filter(function ($emp) {
                $structure = $this->payrollService->getEffectiveSalaryStructure($emp);
                return $structure && $structure->use_daily_wages;
            });

            $generated = 0;
            $skipped = 0;
            $errors = [];

            foreach ($employees as $employee) {
                // Skip if already exists for this date
                $monthStr = Carbon::parse($request->date)->format('Y-m');
                // Check exact date overlap for daily payroll
                $exists = Payroll::where('employee_id', $employee->id)
                    ->where('payroll_type', 'daily')
                    ->whereDate('created_at', $request->date) // Usually we might check a date column, currently daily stores date in 'month' or created_at? 
                    // Let's check how calculateDailyPayroll stores it. It stores 'month' => Y-m-d.
                    ->where('month', $request->date) 
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                // Get attendance for the date
                $attendance = Attendance::where('employee_id', $employee->id)
                    ->whereDate('date', $request->date)
                    ->first();

                if (! $attendance || ! $attendance->clock_out) {
                    $errors[] = $employee->full_name . ': No completed attendance found.';
                    continue;
                }

                try {
                    $payrollData = $this->payrollService->calculateDailyPayroll($employee, $attendance);

                    $payroll = Payroll::create(array_merge(
                        ['employee_id' => $employee->id],
                        Arr::except($payrollData, ['allowance_details', 'deduction_details', 'new_pending_deductions'])
                    ));

                    $this->payrollService->savePayrollDetails(
                        $payroll,
                        $payrollData['allowance_details'] ?? [],
                        $payrollData['deduction_details'] ?? []
                    );

                    $this->payrollService->updatePendingDeductions(
                        $employee,
                        $payrollData['new_pending_deductions'] ?? 0
                    );

                    $generated++;
                } catch (\Exception $e) {
                    $errors[] = $employee->full_name . ': ' . $e->getMessage();
                }
            }

            DB::commit();

            return response()->json([
                'success' => "Daily payroll generated for {$generated} employees. {$skipped} skipped. " . (count($errors) > 0 ? count($errors) . " errors." : ""),
                'errors' => $errors,
                'reload' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'errors' => ['general' => [$e->getMessage()]],
            ], 422);
        }
    }

    /**
     * Update payroll (add manual allowances/deductions, edit notes)
     */
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('hr.payroll.edit')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $payroll = Payroll::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'manual_allowances' => 'nullable|numeric|min:0',
            'manual_deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Update manual adjustments
            $payroll->update([
                'manual_allowances' => $request->manual_allowances ?? 0,
                'manual_deductions' => $request->manual_deductions ?? 0,
                'notes' => $request->notes,
            ]);

            // Recalculate net salary
            $totalDeductions = $payroll->deductions +
                              $payroll->attendance_deductions +
                              $payroll->manual_deductions +
                              $payroll->carried_forward_deduction;

            $grossSalary = $payroll->basic_salary +
                          $payroll->allowances +
                          $payroll->manual_allowances;

            $payroll->update([
                'gross_salary' => $grossSalary,
                'net_salary' => $grossSalary - $totalDeductions,
            ]);

            DB::commit();

            return response()->json([
                'success' => 'Payroll updated successfully.',
                'payroll' => $payroll->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'errors' => ['general' => [$e->getMessage()]],
            ], 422);
        }
    }

    /**
     * Mark payroll as reviewed
     */
    public function markReviewed($id)
    {
        if (! auth()->user()->can('hr.payroll.edit')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $payroll = Payroll::findOrFail($id);

        if (! $payroll->canMarkReviewed()) {
            return response()->json([
                'error' => 'Payroll is not in generated status.',
            ], 403);
        }

        $payroll->update([
            'status' => 'reviewed',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => 'Payroll marked as reviewed successfully.',
        ]);
    }

    /**
     * Mark payroll as paid with detailed payment options & account selection
     */
    public function markPaid(Request $request, $id)
    {
        if (! auth()->user()->can('hr.payroll.edit')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $payroll = Payroll::with('employee')->findOrFail($id);

        if (! $payroll->canMarkPaid()) {
            return response()->json([
                'error' => 'Payroll cannot be marked as paid.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'payment_date' => 'nullable|date',
            'account_id' => 'nullable|exists:accounts,id',
            'payment_method' => 'nullable|string',
            'payment_reference' => 'nullable|string',
            'notes' => 'nullable|string',
            'attendance_deductions' => 'nullable|numeric|min:0',
            'manual_deductions' => 'nullable|numeric|min:0',
            'loan_deduction' => 'nullable|numeric|min:0',
            'manual_allowances' => 'nullable|numeric|min:0',
            'overtime' => 'nullable|numeric|min:0',
            'net_salary' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $paymentDate = $request->input('payment_date', now()->format('Y-m-d'));
        $accountId = $request->input('account_id');
        $paymentMethod = $request->input('payment_method', 'cash');
        $paymentReference = $request->input('payment_reference');
        $notes = $request->input('notes');

        // Get attendance breakdown to fetch overtime earnings
        $attendanceBreakdown = $this->getAttendanceBreakdown($payroll);
        $overtimeEarnings = $request->has('overtime')
            ? floatval($request->input('overtime'))
            : floatval($attendanceBreakdown['overtime_earnings'] ?? 0);

        // Check if customized deductions were passed from payment modal
        $attendanceDeductions = $request->has('attendance_deductions')
            ? floatval($request->input('attendance_deductions'))
            : $payroll->attendance_deductions;

        $manualDeductions = $request->has('manual_deductions')
            ? floatval($request->input('manual_deductions'))
            : $payroll->manual_deductions;

        $loanDeduction = $request->has('loan_deduction')
            ? floatval($request->input('loan_deduction'))
            : ($payroll->loan_deduction ?? 0);

        $manualAllowances = $request->has('manual_allowances')
            ? floatval($request->input('manual_allowances'))
            : $payroll->manual_allowances;

        $grossSalary = $payroll->basic_salary + $payroll->allowances + $manualAllowances + $overtimeEarnings;
        $totalDeductions = $attendanceDeductions + $manualDeductions + $loanDeduction + $payroll->deductions + $payroll->carried_forward_deduction;

        $netSalary = $request->has('net_salary')
            ? floatval($request->input('net_salary'))
            : max(0, $grossSalary - $totalDeductions);

        $payroll->update([
            'status' => 'paid',
            'gross_salary' => $grossSalary,
            'attendance_deductions' => $attendanceDeductions,
            'manual_deductions' => $manualDeductions,
            'loan_deduction' => $loanDeduction,
            'manual_allowances' => $manualAllowances,
            'net_salary' => $netSalary,
            'payment_date' => $paymentDate,
            'account_id' => $accountId,
            'payment_method' => $paymentMethod,
            'payment_reference' => $paymentReference,
            'notes' => $notes ?: $payroll->notes,
        ]);

        // Automatically update active Employee Loans and record LoanPayment logs if loan deduction was taken
        if ($loanDeduction > 0) {
            $remainingDeduction = $loanDeduction;
            $activeLoans = \App\Models\Hr\Loan::where('employee_id', $payroll->employee_id)
                ->active()
                ->orderBy('id', 'asc')
                ->get();

            foreach ($activeLoans as $loan) {
                if ($remainingDeduction <= 0) {
                    break;
                }

                $dueOnLoan = $loan->amount - $loan->paid_amount;
                $payAmountForLoan = min($remainingDeduction, $dueOnLoan);

                if ($payAmountForLoan > 0) {
                    $newPaid = $loan->paid_amount + $payAmountForLoan;
                    $newStatus = ($newPaid >= $loan->amount) ? 'completed' : 'approved';

                    $loan->update([
                        'paid_amount' => $newPaid,
                        'status' => $newStatus,
                    ]);

                    \App\Models\Hr\LoanPayment::create([
                        'loan_id' => $loan->id,
                        'amount' => $payAmountForLoan,
                        'payment_date' => $paymentDate,
                        'type' => 'salary_deduction',
                        'notes' => "Salary Loan Cut for {$payroll->month} (Payroll #{$payroll->id})",
                    ]);

                    // Mark any pending scheduled deduction for this month as deducted
                    \App\Models\Hr\LoanScheduledDeduction::where('loan_id', $loan->id)
                        ->where('deduction_month', $payroll->month)
                        ->where('status', 'pending')
                        ->update(['status' => 'deducted']);

                    $remainingDeduction -= $payAmountForLoan;
                }
            }
        }

        // If an account is selected, record account history / deduct balance
        if ($accountId) {
            $account = \App\Models\Account::find($accountId);
            if ($account) {
                if (class_exists('\App\Models\AccountHistory')) {
                    \App\Models\AccountHistory::create([
                        'account_id' => $account->id,
                        'amount' => $payroll->net_salary,
                        'type' => 'Debit',
                        'description' => "Salary Payment for {$payroll->employee->full_name} ({$payroll->month})",
                        'reference_no' => $paymentReference ?: "PAYROLL-{$payroll->id}",
                        'date' => $paymentDate,
                    ]);
                }
                if (\Schema::hasColumn('accounts', 'current_balance')) {
                    $account->decrement('current_balance', $payroll->net_salary);
                }
            }
        }

        return response()->json([
            'success' => 'Payroll marked as Paid successfully and loan deductions updated.',
        ]);
    }

    /**
     * Delete payroll
     */
    public function destroy($id)
    {
        if (! auth()->user()->can('hr.payroll.delete')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $payroll = Payroll::findOrFail($id);

        // Only allow deletion if not paid
        if ($payroll->status === 'paid') {
            return response()->json([
                'error' => 'Cannot delete paid payroll.',
            ], 403);
        }

        $payroll->delete();

        return response()->json([
            'success' => 'Payroll deleted successfully.',
        ]);
    }

    /**
     * Auto-generate daily payroll when employee checks out
     * This should be called from attendance checkout process
     */
    public function autoGenerateDaily(Employee $employee, Attendance $attendance)
    {
        // Check if employee uses daily wages
        if (! $employee->salaryStructure || ! $employee->salaryStructure->use_daily_wages) {
            return;
        }

        // Check if payroll already exists for this date
        $month = Carbon::parse($attendance->date)->format('Y-m');
        $exists = Payroll::where('employee_id', $employee->id)
            ->where('month', $month)
            ->where('payroll_type', 'daily')
            ->whereDate('created_at', $attendance->date)
            ->exists();

        if ($exists) {
            return;
        }

        try {
            DB::beginTransaction();

            $payrollData = $this->payrollService->calculateDailyPayroll($employee, $attendance);

            $payroll = Payroll::create(array_merge(
                ['employee_id' => $employee->id],
                Arr::except($payrollData, ['allowance_details', 'deduction_details', 'new_pending_deductions'])
            ));

            $this->payrollService->savePayrollDetails(
                $payroll,
                $payrollData['allowance_details'] ?? [],
                $payrollData['deduction_details'] ?? []
            );

            $this->payrollService->updatePendingDeductions(
                $employee,
                $payrollData['new_pending_deductions'] ?? 0
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Auto-generate daily payroll failed: '.$e->getMessage());
        }
    }

    private function calculateLateRuleDeduction(int $lateMinutes, array $rules, float $dailyRate): float
    {
        if (empty($rules)) {
            return 0;
        }

        foreach ($rules as $rule) {
            $min = $rule['min_minutes'] ?? 0;
            $max = $rule['max_minutes'] ?? null;

            if ($lateMinutes >= $min && (is_null($max) || $lateMinutes <= $max)) {
                $amount = floatval($rule['amount'] ?? 0);
                $type = $rule['type'] ?? 'fixed';

                if ($type === 'percentage') {
                    return ($dailyRate * $amount) / 100;
                } else {
                    return $amount;
                }
            }
        }

        return 0;
    }

    private function calculateEarlyRuleDeduction(int $earlyMinutes, array $rules, float $dailyRate): float
    {
        if (empty($rules)) {
            return 0;
        }

        foreach ($rules as $rule) {
            $min = $rule['min_minutes'] ?? 0;
            $max = $rule['max_minutes'] ?? null;

            if ($earlyMinutes >= $min && (is_null($max) || $earlyMinutes <= $max)) {
                $amount = floatval($rule['amount'] ?? 0);
                $type = $rule['type'] ?? 'fixed';

                if ($type === 'percentage') {
                    return ($dailyRate * $amount) / 100;
                } else {
                    return $amount;
                }
            }
        }

        return 0;
    }

    /**
     * Unified calculation for Overtime Hours & Earnings across tables and modals
     */
    private function calculateOvertimeMetrics($payroll, $attendances): array
    {
        $employee = $payroll->employee;
        $hrPolicy = \App\Models\Hr\HrPolicy::getEffectivePolicyForEmployee($employee->id);

        if ($payroll->relationLoaded('details') && $payroll->details) {
            $detailOt = $payroll->details->where('type', 'allowance')
                ->filter(fn($d) => stripos($d->name, 'overtime') !== false || stripos($d->name, 'ot') !== false)
                ->sum('amount');
            if ($detailOt > 0) {
                $otHours = round($attendances->filter(fn($a) => ($a->total_hours ?? 0) > 8)->sum(fn($a) => $a->total_hours - 8), 1);
                return ['hours' => $otHours, 'earnings' => round($detailOt, 2)];
            }
        }

        $shift = $employee->shift ?? \App\Models\Hr\Shift::where('is_default', true)->first();
        $shiftEndStr = $employee->custom_end_time ?: ($shift ? $shift->end_time : '18:00:00');
        $standardShiftHours = ($shift && floatval($shift->total_hours) > 0) ? floatval($shift->total_hours) : 8;

        $overtimeHoursTotal = 0;
        foreach ($attendances as $att) {
            $hoursWorked = floatval($att->total_hours ?? 0);
            $dailyOt = 0;

            if ($hoursWorked > $standardShiftHours) {
                $dailyOt = $hoursWorked - $standardShiftHours;
            }

            if ($dailyOt <= 0 && $att->check_out_time) {
                $shiftEndDt = \Carbon\Carbon::parse($att->date . ' ' . \Carbon\Carbon::parse($shiftEndStr)->format('H:i:s'));
                $checkOutDt = \Carbon\Carbon::parse($att->date . ' ' . \Carbon\Carbon::parse($att->check_out_time)->format('H:i:s'));
                if ($checkOutDt->gt($shiftEndDt)) {
                    $dailyOt = round($checkOutDt->diffInMinutes($shiftEndDt) / 60, 2);
                }
            }

            if ($dailyOt > 0) {
                $overtimeHoursTotal += $dailyOt;
            }
        }

        $overtimeHoursTotal = round($overtimeHoursTotal, 2);
        $perDayRate = ($payroll->basic_salary > 0) ? ($payroll->basic_salary / 30) : 0;
        $hourlySalary = ($standardShiftHours > 0) ? ($perDayRate / $standardShiftHours) : 0;

        if ($hrPolicy && $hrPolicy->overtime_rate_type === 'fixed' && $hrPolicy->overtime_fixed_rate > 0) {
            $overtimeEarnings = round($overtimeHoursTotal * $hrPolicy->overtime_fixed_rate, 2);
        } else {
            $multiplier = ($hrPolicy && $hrPolicy->overtime_multiplier > 0) ? $hrPolicy->overtime_multiplier : 1.5;
            $overtimeEarnings = round($overtimeHoursTotal * $hourlySalary * $multiplier, 2);
        }

        return ['hours' => $overtimeHoursTotal, 'earnings' => $overtimeEarnings];
    }
}

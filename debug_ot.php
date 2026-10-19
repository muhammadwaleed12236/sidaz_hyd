<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Hr\Payroll;
use App\Models\Hr\Attendance;
use App\Models\Hr\HrPolicy;
use App\Models\Hr\Shift;
use Carbon\Carbon;

$payroll = Payroll::with(['employee', 'details'])->find(139);
if (!$payroll) { echo "Not found\n"; exit; }

$employee = $payroll->employee;
$shift = $employee->shift ?? Shift::where('is_default', true)->first();

// Simulate the FIXED code (same as controller after fix)
$shiftEndStr = $employee->custom_end_time
    ?: ($shift ? Carbon::parse($shift->end_time)->format('H:i:s') : '18:00:00');

echo "=== Payroll #{$payroll->id} | Emp #{$payroll->employee_id} ({$employee->name}) | Month: {$payroll->month} ===\n";
echo "Shift end (FIXED): {$shiftEndStr}\n";

$hrPolicy = HrPolicy::getEffectivePolicyForEmployee($employee->id);
$minOt = $hrPolicy ? intval($hrPolicy->overtime_min_minutes) : 60;
echo "Min OT minutes: {$minOt}\n\n";

$startDate = Carbon::parse($payroll->month . '-01')->startOfMonth();
$endDate = $startDate->copy()->endOfMonth();

$attendances = Attendance::where('employee_id', $payroll->employee_id)
    ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
    ->get();

$standardShiftHours = 9.5;
if ($shift && floatval($shift->total_hours) > 0) {
    $standardShiftHours = floatval($shift->total_hours);
}
$perDayRate = ($payroll->basic_salary > 0) ? ($payroll->basic_salary / 30) : 0;
$hourlySalary = ($standardShiftHours > 0) ? ($perDayRate / $standardShiftHours) : 0;

$totalOtHours = 0;
foreach ($attendances as $att) {
    $timeOut = $att->check_out_time ?: $att->clock_out;
    if ($timeOut) {
        $dateYmd = Carbon::parse($att->date)->format('Y-m-d');
        $shiftEndDt = Carbon::parse($dateYmd . ' ' . $shiftEndStr);
        $checkOutDt = Carbon::parse($dateYmd . ' ' . Carbon::parse($timeOut)->format('H:i:s'));
        $extraMinutes = $checkOutDt->gt($shiftEndDt) ? $checkOutDt->diffInMinutes($shiftEndDt) : 0;
        $otHrs = $extraMinutes >= $minOt ? round($extraMinutes / 60, 2) : 0;
        $totalOtHours += $otHrs;
        echo "  {$att->date}: shiftEnd={$shiftEndDt->format('H:i')}, out={$checkOutDt->format('H:i')}, extra={$extraMinutes}min, OT={$otHrs}hrs\n";
    }
}

$multiplier = ($hrPolicy && $hrPolicy->overtime_multiplier > 0) ? $hrPolicy->overtime_multiplier : 1.5;
$totalOtEarnings = round($totalOtHours * $hourlySalary * $multiplier, 2);
echo "\n✅ Total OT Hours: {$totalOtHours}, OT Earnings: Rs.{$totalOtEarnings}\n";

<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrPolicy extends Model
{
    use HasFactory;

    protected $table = 'hr_policies';

    protected $fillable = [
        'name',
        'applies_to',
        'employee_ids',
        'is_default',
        'is_active',
        'overtime_enabled',
        'overtime_min_minutes',
        'overtime_rate_type',
        'overtime_multiplier',
        'overtime_fixed_rate',
        'late_grace_minutes',
        'late_penalty_type',
        'late_penalty_amount',
        'absence_deduction_type',
        'absence_fixed_amount',
        'early_checkout_penalty_type',
        'early_checkout_penalty_amount',
        'min_hours_half_day',
        'half_day_deduction_type',
        'half_day_fixed_amount',
        'notes',
    ];

    protected $casts = [
        'employee_ids' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'overtime_enabled' => 'boolean',
        'overtime_min_minutes' => 'integer',
        'overtime_multiplier' => 'float',
        'overtime_fixed_rate' => 'float',
        'late_grace_minutes' => 'integer',
        'late_penalty_amount' => 'float',
        'absence_fixed_amount' => 'float',
        'early_checkout_penalty_amount' => 'float',
        'min_hours_half_day' => 'float',
        'half_day_fixed_amount' => 'float',
    ];

    /**
     * Get effective HR policy for a specific employee ID
     */
    public static function getEffectivePolicyForEmployee($employeeId): ?self
    {
        // 1. Look for active policy explicitly targeting this employee ID
        $specificPolicy = self::where('is_active', true)
            ->where('applies_to', 'specific')
            ->get()
            ->first(function ($policy) use ($employeeId) {
                return is_array($policy->employee_ids) && in_array($employeeId, $policy->employee_ids);
            });

        if ($specificPolicy) {
            return $specificPolicy;
        }

        // 2. Look for active default / all employees policy
        $defaultPolicy = self::where('is_active', true)
            ->where(function ($q) {
                $q->where('is_default', true)->orWhere('applies_to', 'all');
            })
            ->latest('id')
            ->first();

        if ($defaultPolicy) {
            return $defaultPolicy;
        }

        // 3. Fallback: Return standard default instance if no DB policy exists
        return new self([
            'name' => 'System Standard Policy',
            'applies_to' => 'all',
            'is_default' => true,
            'is_active' => true,
            'overtime_enabled' => true,
            'overtime_min_minutes' => 60,
            'overtime_rate_type' => 'hourly_formula',
            'overtime_multiplier' => 1.5,
            'overtime_fixed_rate' => 0.0,
            'late_grace_minutes' => 15,
            'late_penalty_type' => '3_lates_1_day',
            'late_penalty_amount' => 0.0,
            'absence_deduction_type' => 'salary_divided_by_30',
            'absence_fixed_amount' => 0.0,
            'early_checkout_penalty_type' => 'none',
            'early_checkout_penalty_amount' => 0.0,
            'min_hours_half_day' => 4.0,
        ]);
    }
}

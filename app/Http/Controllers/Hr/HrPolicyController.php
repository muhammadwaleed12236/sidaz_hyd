<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Employee;
use App\Models\Hr\HrPolicy;
use Illuminate\Http\Request;

class HrPolicyController extends Controller
{
    /**
     * Display Terms & Conditions / Attendance Policy Setup Page
     */
    public function index()
    {
        if (! auth()->user()->can('hr.payroll.view') && ! auth()->user()->can('hr.payroll.create')) {
            abort(403, 'Unauthorized action.');
        }

        $policies = HrPolicy::orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();
        $employees = Employee::where('status', 'active')->select('id', 'first_name', 'last_name', 'department_id', 'designation_id')->with(['department', 'designation'])->get();

        return view('hr.policy.index', compact('policies', 'employees'));
    }

    /**
     * Show form to create a new HR Terms & Conditions policy
     */
    public function create()
    {
        if (! auth()->user()->can('hr.payroll.create')) {
            abort(403, 'Unauthorized action.');
        }

        $employees = Employee::where('status', 'active')->select('id', 'first_name', 'last_name', 'department_id', 'designation_id')->with(['department', 'designation'])->get();
        $policy = new HrPolicy([
            'applies_to' => 'all',
            'is_default' => false,
            'is_active' => true,
            'overtime_enabled' => true,
            'overtime_min_minutes' => 60,
            'overtime_rate_type' => 'hourly_formula',
            'overtime_multiplier' => 1.5,
            'overtime_fixed_rate' => 250.0,
            'late_grace_minutes' => 15,
            'late_penalty_type' => '3_lates_1_day',
            'late_penalty_amount' => 200.0,
            'absence_deduction_type' => 'salary_divided_by_30',
            'absence_fixed_amount' => 1000.0,
            'early_checkout_penalty_type' => 'none',
            'early_checkout_penalty_amount' => 0.0,
            'min_hours_half_day' => 5.0,
            'half_day_deduction_type' => 'half_day_salary',
            'half_day_fixed_amount' => 500.0,
        ]);

        return view('hr.policy.form', compact('policy', 'employees'));
    }

    /**
     * Show form to edit an existing HR Terms & Conditions policy
     */
    public function edit(HrPolicy $policy)
    {
        if (! auth()->user()->can('hr.payroll.edit')) {
            abort(403, 'Unauthorized action.');
        }

        $employees = Employee::where('status', 'active')->select('id', 'first_name', 'last_name', 'department_id', 'designation_id')->with(['department', 'designation'])->get();

        return view('hr.policy.form', compact('policy', 'employees'));
    }

    /**
     * Store or Update an HR Policy Rule set
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('hr.payroll.create') && ! auth()->user()->can('hr.payroll.edit')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'applies_to' => 'required|in:all,specific',
                'employee_ids' => 'required_if:applies_to,specific|array',
                'overtime_enabled' => 'nullable|boolean',
                'overtime_min_minutes' => 'required|integer|min:0',
                'overtime_rate_type' => 'required|in:hourly_formula,fixed',
                'overtime_multiplier' => 'nullable|numeric|min:0.1',
                'overtime_fixed_rate' => 'nullable|numeric|min:0',
                'late_grace_minutes' => 'required|integer|min:0',
                'late_penalty_type' => 'required|in:3_lates_1_day,fixed_per_instance,none',
                'late_penalty_amount' => 'nullable|numeric|min:0',
                'absence_deduction_type' => 'required|in:salary_divided_by_30,fixed',
                'absence_fixed_amount' => 'nullable|numeric|min:0',
                'early_checkout_penalty_type' => 'required|in:none,fixed_per_instance,3_earlies_1_day',
                'early_checkout_penalty_amount' => 'nullable|numeric|min:0',
                'min_hours_half_day' => 'required|numeric|min:1|max:12',
                'half_day_deduction_type' => 'required|in:half_day_salary,fixed,none',
                'half_day_fixed_amount' => 'nullable|numeric|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'error' => 'Validation error',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        $isDefault = $request->has('is_default') && $request->is_default == '1';

        // If making default, unset previous default
        if ($isDefault) {
            HrPolicy::where('is_default', true)->update(['is_default' => false]);
        }

        $policyData = [
            'name' => $request->name,
            'applies_to' => $request->applies_to,
            'employee_ids' => $request->applies_to === 'specific' ? array_map('intval', $request->employee_ids ?? []) : null,
            'is_default' => $isDefault,
            'is_active' => $request->has('is_active') ? (bool) $request->is_active : true,
            'overtime_enabled' => $request->has('overtime_enabled'),
            'overtime_min_minutes' => (int) $request->overtime_min_minutes,
            'overtime_rate_type' => $request->overtime_rate_type,
            'overtime_multiplier' => floatval($request->overtime_multiplier ?? 1.5),
            'overtime_fixed_rate' => floatval($request->overtime_fixed_rate ?? 0),
            'late_grace_minutes' => (int) $request->late_grace_minutes,
            'late_penalty_type' => $request->late_penalty_type,
            'late_penalty_amount' => floatval($request->late_penalty_amount ?? 0),
            'absence_deduction_type' => $request->absence_deduction_type,
            'absence_fixed_amount' => floatval($request->absence_fixed_amount ?? 0),
            'early_checkout_penalty_type' => $request->early_checkout_penalty_type,
            'early_checkout_penalty_amount' => floatval($request->early_checkout_penalty_amount ?? 0),
            'min_hours_half_day' => floatval($request->min_hours_half_day ?? 5.0),
            'half_day_deduction_type' => $request->half_day_deduction_type,
            'half_day_fixed_amount' => floatval($request->half_day_fixed_amount ?? 0),
            'notes' => $request->notes,
        ];

        if ($request->filled('policy_id')) {
            $policy = HrPolicy::findOrFail($request->policy_id);
            $policy->update($policyData);
            $message = 'HR Terms & Policy Updated Successfully!';
        } else {
            $policy = HrPolicy::create($policyData);
            $message = 'HR Terms & Policy Created Successfully!';
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('hr.policy.index'),
                'policy' => $policy,
            ]);
        }

        return redirect()->route('hr.policy.index')->with('success', $message);
    }

    /**
     * Toggle policy active state
     */
    public function toggleStatus(HrPolicy $policy)
    {
        if (! auth()->user()->can('hr.payroll.edit')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $policy->is_active = ! $policy->is_active;
        $policy->save();

        return response()->json([
            'success' => true,
            'message' => 'Policy status updated!',
            'is_active' => $policy->is_active,
        ]);
    }

    /**
     * Delete an HR policy
     */
    public function destroy(HrPolicy $policy)
    {
        if (! auth()->user()->can('hr.payroll.delete')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        if ($policy->is_default) {
            return response()->json(['error' => 'Default system policy cannot be deleted.'], 422);
        }

        $policy->delete();

        return response()->json([
            'success' => true,
            'message' => 'Policy deleted successfully!',
        ]);
    }
}

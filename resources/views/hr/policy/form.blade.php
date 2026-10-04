@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    <style>
        :root {
            --hr-primary: #4f46e5;
            --hr-primary-hover: #4338ca;
            --hr-success: #10b981;
            --hr-warning: #f59e0b;
            --hr-danger: #ef4444;
            --hr-info: #06b6d4;
            --hr-purple: #8b5cf6;
            --hr-dark: #0f172a;
            --hr-card-bg: #ffffff;
            --hr-border: #e2e8f0;
        }

        .policy-workspace {
            background-color: #f8fafc;
            min-height: 100vh;
        }

        .form-card {
            border: 1px solid var(--hr-border);
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.03);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .form-card:hover {
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            border-color: #cbd5e1;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--hr-border);
        }

        .form-card.card-indigo::before { background: var(--hr-primary); }
        .form-card.card-info::before { background: var(--hr-info); }
        .form-card.card-warning::before { background: var(--hr-warning); }
        .form-card.card-purple::before { background: var(--hr-purple); }
        .form-card.card-danger::before { background: var(--hr-danger); }

        .form-section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 14px;
        }

        .icon-badge {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 4px;
            display: block;
        }

        /* Clean Modern Custom Select Styling */
        .form-select, select.form-control {
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%3c4f46e5' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 1em 1em !important;
            padding-right: 2.2rem !important;
            padding-left: 0.75rem !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            font-size: 0.85rem !important;
            font-weight: 600 !important;
            color: #0f172a !important;
            background-color: #ffffff !important;
            height: 38px !important;
            width: 100% !important;
        }

        .form-select:focus, select.form-control:focus, .form-control:focus {
            border-color: var(--hr-primary) !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
            outline: none !important;
        }

        .form-control {
            font-size: 0.85rem;
            height: 38px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-weight: 600;
        }

        .input-group-text {
            font-size: 0.8rem;
            font-weight: 700;
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
        }

        /* Segmented Target Scope Toggle */
        .scope-segmented-toggle {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 3px;
            display: flex;
            gap: 4px;
        }

        .scope-segmented-toggle .btn-check:checked + .btn-segmented {
            background-color: var(--hr-primary);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
        }

        .scope-segmented-toggle .btn-segmented {
            color: #475569;
            font-size: 0.8rem;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            padding: 8px 14px;
            transition: all 0.2s ease;
            cursor: pointer;
            text-align: center;
        }

        .scope-segmented-toggle .btn-segmented:hover {
            color: #0f172a;
            background-color: rgba(255, 255, 255, 0.6);
        }

        /* Targeted Employee Grid */
        .emp-grid-box {
            max-height: 220px;
            overflow-y: auto;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px;
            background: #ffffff;
        }

        .emp-grid-box::-webkit-scrollbar { width: 5px; }
        .emp-grid-box::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 8px; }

        .emp-card-item {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 6px 10px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.15s ease;
            user-select: none;
        }

        .emp-card-item.checked {
            border-color: var(--hr-primary);
            background: #eef2ff;
        }

        .avatar-initial {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #4f46e5;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Live Preview Sticky Sidebar */
        .live-preview-panel {
            background: linear-gradient(145deg, #0f172a, #1e293b);
            border-radius: 14px;
            padding: 16px;
            color: #ffffff;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.18);
        }

        .preview-rule-item {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 8px;
            padding: 8px 12px;
            margin-bottom: 8px;
        }

        .sub-help-text {
            font-size: 0.72rem;
            color: #64748b;
            margin-top: 2px;
        }
    </style>

    <div class="main-content policy-workspace">
        <div class="main-content-inner">
            <div class="container-fluid py-3">

                <!-- Header Banner -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <a href="{{ route('hr.policy.index') }}" class="btn btn-xs btn-light border rounded-pill px-3 text-secondary font-weight-bold shadow-sm">
                                <i class="fa fa-arrow-left me-1"></i> Back to Terms List
                            </a>
                            <span class="badge bg-indigo-subtle text-indigo border border-indigo px-3 py-1 rounded-pill font-weight-bold" style="background: #eef2ff; color: #4f46e5;">
                                {{ $policy->exists ? 'Edit Mode (ID: #'.$policy->id.')' : 'New Policy Setup' }}
                            </span>
                        </div>
                        <h4 class="font-weight-bold text-dark mb-0 fs-5">
                            <i class="fa fa-file-contract text-primary me-2"></i>
                            {{ $policy->exists ? 'Edit HR Terms & Conditions Policy' : 'Create HR Terms & Conditions Policy' }}
                        </h4>
                    </div>
                </div>

                <form id="policyFullForm" action="{{ route('hr.policy.store') }}" method="POST">
                    @csrf
                    @if($policy->exists)
                        <input type="hidden" name="policy_id" value="{{ $policy->id }}">
                    @endif

                    <div class="row g-3">

                        <!-- Left Workspace: Policy Configuration Cards -->
                        <div class="col-lg-8">

                            <!-- CARD 1: Title & Target Scope -->
                            <div class="form-card card-indigo p-3 mb-3">
                                <div class="form-section-title">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="icon-badge bg-primary-subtle text-primary" style="background: #eef2ff; color: #4f46e5;">
                                            <i class="fa fa-tag"></i>
                                        </div>
                                        <span>1. Policy Title & Target Audience Scope</span>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Policy Title / Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="policy_name" class="form-control" placeholder="e.g. Standard Terms & Conditions" value="{{ old('name', $policy->name) }}" required>
                                        <div class="sub-help-text">Clear identifier for payroll processing</div>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label">Target Audience Scope <span class="text-danger">*</span></label>
                                        <div class="scope-segmented-toggle">
                                            <input type="radio" class="btn-check" name="applies_to" id="applies_all" value="all" {{ $policy->applies_to === 'all' ? 'checked' : '' }}>
                                            <label class="btn btn-segmented flex-fill" for="applies_all" id="label_all">
                                                🌐 All Active Employees
                                            </label>

                                            <input type="radio" class="btn-check" name="applies_to" id="applies_specific" value="specific" {{ $policy->applies_to === 'specific' ? 'checked' : '' }}>
                                            <label class="btn btn-segmented flex-fill" for="applies_specific" id="label_specific">
                                                🎯 Specific Staff Override
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Employee Grid (shown if specific selected) -->
                                <div id="employeeSelectorSection" class="{{ $policy->applies_to === 'specific' ? '' : 'd-none' }} mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="form-label mb-0" id="selectedEmpCountBadge">Selected: 0 employees</span>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0" style="font-size: 0.7rem;" id="selectAllEmps">Select All</button>
                                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 0.7rem;" id="deselectAllEmps">Clear</button>
                                        </div>
                                    </div>

                                    <input type="text" id="empSearchInput" class="form-control mb-2" placeholder="Search employee name, department...">

                                    <div class="emp-grid-box">
                                        <div class="row g-1" id="empGridContainer">
                                            @php $assignedIds = is_array($policy->employee_ids) ? $policy->employee_ids : []; @endphp
                                            @foreach($employees as $emp)
                                                @php 
                                                    $isChk = in_array($emp->id, $assignedIds); 
                                                    $initials = strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name ?? '', 0, 1));
                                                @endphp
                                                <div class="col-md-6 emp-card-col" data-search="{{ strtolower($emp->first_name . ' ' . $emp->last_name . ' ' . ($emp->department->name ?? '') . ' ' . ($emp->designation->name ?? '')) }}">
                                                    <div class="emp-card-item d-flex align-items-center gap-2 {{ $isChk ? 'checked' : '' }}" onclick="toggleEmpCard(this)">
                                                        <input class="form-check-input emp-checkbox me-0 pointer-event" type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" id="emp_cb_{{ $emp->id }}" {{ $isChk ? 'checked' : '' }}>
                                                        <div class="avatar-initial">{{ $initials }}</div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <div class="font-weight-bold text-dark text-truncate" style="font-size: 0.75rem;">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                                            <div class="text-muted text-truncate" style="font-size: 0.68rem;">{{ $emp->department->name ?? 'Staff' }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- RULES GRID: 2 Columns of Clean Structured Cards -->
                            <div class="row g-3">

                                <!-- CARD 2: Overtime Policy -->
                                <div class="col-md-6">
                                    <div class="form-card card-info p-3 h-100">
                                        <div class="form-section-title">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="icon-badge bg-info-subtle text-info" style="background: #e0f2fe; color: #0284c7;">
                                                    <i class="fa fa-clock"></i>
                                                </div>
                                                <span>2. Overtime Policy</span>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" name="overtime_enabled" id="overtime_enabled" value="1" {{ old('overtime_enabled', $policy->overtime_enabled) ? 'checked' : '' }}>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Overtime Grace Threshold</label>
                                            <div class="input-group">
                                                <input type="number" name="overtime_min_minutes" id="overtime_min_minutes" class="form-control" value="{{ old('overtime_min_minutes', $policy->overtime_min_minutes ?? 60) }}" min="0">
                                                <span class="input-group-text">Minutes after shift</span>
                                            </div>
                                            <div class="sub-help-text">Extra work after shift end >= threshold counts as overtime</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Overtime Calculation Method</label>
                                            <select name="overtime_rate_type" id="overtime_rate_type" class="form-select">
                                                <option value="hourly_formula" {{ old('overtime_rate_type', $policy->overtime_rate_type) === 'hourly_formula' ? 'selected' : '' }}>Formula: (Salary / 30 / Shift Hrs) × Multiplier</option>
                                                <option value="fixed" {{ old('overtime_rate_type', $policy->overtime_rate_type) === 'fixed' ? 'selected' : '' }}>Fixed Hourly Rate (Rs. / hour)</option>
                                            </select>
                                        </div>

                                        <div id="multiplierWrapper" class="mb-2">
                                            <label class="form-label">Overtime Multiplier Rate</label>
                                            <div class="input-group">
                                                <input type="number" step="0.1" name="overtime_multiplier" id="overtime_multiplier" class="form-control" value="{{ old('overtime_multiplier', $policy->overtime_multiplier ?? 1.5) }}">
                                                <span class="input-group-text">x Rate (1.5x)</span>
                                            </div>
                                        </div>

                                        <div id="fixedRateWrapper" class="mb-2 d-none">
                                            <label class="form-label">Fixed Overtime Rate per Hour</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rs.</span>
                                                <input type="number" step="1" name="overtime_fixed_rate" id="overtime_fixed_rate" class="form-control" value="{{ old('overtime_fixed_rate', $policy->overtime_fixed_rate ?? 250) }}">
                                                <span class="input-group-text">/ hour</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CARD 3: Late Check-in Policy -->
                                <div class="col-md-6">
                                    <div class="form-card card-warning p-3 h-100">
                                        <div class="form-section-title">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="icon-badge bg-warning-subtle text-warning" style="background: #fef3c7; color: #d97706;">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                </div>
                                                <span>3. Late Arrival & Penalty Policy</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Late Grace Period</label>
                                            <div class="input-group">
                                                <input type="number" name="late_grace_minutes" id="late_grace_minutes" class="form-control" value="{{ old('late_grace_minutes', $policy->late_grace_minutes ?? 15) }}">
                                                <span class="input-group-text">Minutes after shift start</span>
                                            </div>
                                            <div class="sub-help-text">Check-in within grace period is marked ON TIME</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Late Penalty Rule</label>
                                            <select name="late_penalty_type" id="late_penalty_type" class="form-select">
                                                <option value="3_lates_1_day" {{ old('late_penalty_type', $policy->late_penalty_type) === '3_lates_1_day' ? 'selected' : '' }}>🚨 3 Late Check-ins = 1 Day Salary Deduction</option>
                                                <option value="fixed_per_instance" {{ old('late_penalty_type', $policy->late_penalty_type) === 'fixed_per_instance' ? 'selected' : '' }}>💵 Fixed Deduction Amount per Late</option>
                                                <option value="none" {{ old('late_penalty_type', $policy->late_penalty_type) === 'none' ? 'selected' : '' }}>🟢 No Penalty (Attendance Log Only)</option>
                                            </select>
                                        </div>

                                        <div id="lateFixedWrapper" class="mb-2 d-none">
                                            <label class="form-label">Fixed Late Penalty Amount</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rs.</span>
                                                <input type="number" name="late_penalty_amount" id="late_penalty_amount" class="form-control" value="{{ old('late_penalty_amount', $policy->late_penalty_amount ?? 200) }}">
                                                <span class="input-group-text">/ late</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CARD 4: Half Duty & Short Work Policy -->
                                <div class="col-md-6">
                                    <div class="form-card card-purple p-3 h-100">
                                        <div class="form-section-title">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="icon-badge bg-purple-subtle text-purple" style="background: #f3e8ff; color: #7c3aed;">
                                                    <i class="fa fa-user-clock"></i>
                                                </div>
                                                <span>4. Half Duty & Short Work Policy</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Half Duty Minimum Hours Threshold</label>
                                            <div class="input-group">
                                                <input type="number" step="0.5" name="min_hours_half_day" id="min_hours_half_day" class="form-control" value="{{ old('min_hours_half_day', $policy->min_hours_half_day ?? 5.0) }}" min="1" max="12">
                                                <span class="input-group-text">Hours worked</span>
                                            </div>
                                            <div class="sub-help-text">Work less than this threshold (e.g. 5 hrs) counts as Half Duty</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Half Duty Deduction Rule</label>
                                            <select name="half_day_deduction_type" id="half_day_deduction_type" class="form-select">
                                                <option value="half_day_salary" {{ old('half_day_deduction_type', $policy->half_day_deduction_type) === 'half_day_salary' ? 'selected' : '' }}>🌓 Deduct 0.5 Day Salary (Basic / 30 / 2)</option>
                                                <option value="fixed" {{ old('half_day_deduction_type', $policy->half_day_deduction_type) === 'fixed' ? 'selected' : '' }}>💵 Fixed Deduction Amount per Half Duty</option>
                                                <option value="none" {{ old('half_day_deduction_type', $policy->half_day_deduction_type) === 'none' ? 'selected' : '' }}>🟢 No Deduction (Log Only)</option>
                                            </select>
                                        </div>

                                        <div id="halfDayFixedWrapper" class="mb-2 d-none">
                                            <label class="form-label">Fixed Half Duty Amount</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rs.</span>
                                                <input type="number" name="half_day_fixed_amount" id="half_day_fixed_amount" class="form-control" value="{{ old('half_day_fixed_amount', $policy->half_day_fixed_amount ?? 500) }}">
                                                <span class="input-group-text">/ half-duty</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CARD 5: Absence & Leave Policy -->
                                <div class="col-md-6">
                                    <div class="form-card card-danger p-3 h-100">
                                        <div class="form-section-title">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="icon-badge bg-danger-subtle text-danger" style="background: #ffe4e6; color: #e11d48;">
                                                    <i class="fa fa-calendar-times"></i>
                                                </div>
                                                <span>5. Absence & Leave Deduction Policy</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Absence Deduction Calculation Method</label>
                                            <select name="absence_deduction_type" id="absence_deduction_type" class="form-select">
                                                <option value="salary_divided_by_30" {{ old('absence_deduction_type', $policy->absence_deduction_type) === 'salary_divided_by_30' ? 'selected' : '' }}>📅 Daily Salary Rate (Basic Salary / 30)</option>
                                                <option value="fixed" {{ old('absence_deduction_type', $policy->absence_deduction_type) === 'fixed' ? 'selected' : '' }}>💵 Fixed Deduction Amount per Absence Day</option>
                                            </select>
                                            <div class="sub-help-text">Deducted per unexcused absence day in monthly payroll</div>
                                        </div>

                                        <div id="absenceFixedWrapper" class="mb-2 d-none">
                                            <label class="form-label">Fixed Absence Rate per Day</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rs.</span>
                                                <input type="number" name="absence_fixed_amount" id="absence_fixed_amount" class="form-control" value="{{ old('absence_fixed_amount', $policy->absence_fixed_amount ?? 1000) }}">
                                                <span class="input-group-text">/ day</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- Right Workspace: Activation & Live Preview Panel -->
                        <div class="col-lg-4">

                            <div class="position-sticky" style="top: 15px;">

                                <!-- Control Box -->
                                <div class="form-card p-3 mb-3">
                                    <h6 class="font-weight-bold text-dark mb-3 d-flex align-items-center gap-2" style="font-size: 0.88rem;">
                                        <i class="fa fa-sliders-h text-primary"></i> Policy Controls & Actions
                                    </h6>

                                    <div class="form-check form-switch mb-2 p-2 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                                        <label class="form-check-label font-weight-bold text-dark mb-0 pointer-event" style="font-size: 0.78rem;" for="is_active">
                                            Active Policy Status
                                        </label>
                                        <input class="form-check-input ms-0" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $policy->is_active) ? 'checked' : '' }}>
                                    </div>

                                    <div class="form-check form-switch mb-3 p-2 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                                        <label class="form-check-label font-weight-bold text-dark mb-0 pointer-event" style="font-size: 0.78rem;" for="is_default">
                                            Global System Default
                                        </label>
                                        <input class="form-check-input ms-0" type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default', $policy->is_default) ? 'checked' : '' }}>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Early Checkout Penalty Rule</label>
                                        <select name="early_checkout_penalty_type" id="early_checkout_penalty_type" class="form-select">
                                            <option value="none" {{ old('early_checkout_penalty_type', $policy->early_checkout_penalty_type) === 'none' ? 'selected' : '' }}>No Penalty</option>
                                            <option value="3_earlies_1_day" {{ old('early_checkout_penalty_type', $policy->early_checkout_penalty_type) === '3_earlies_1_day' ? 'selected' : '' }}>3 Early Checkouts = 1 Day Salary</option>
                                            <option value="fixed_per_instance" {{ old('early_checkout_penalty_type', $policy->early_checkout_penalty_type) === 'fixed_per_instance' ? 'selected' : '' }}>Fixed Amount per Early Checkout</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Internal Documentation Notes</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="2" placeholder="Optional policy notes...">{{ old('notes', $policy->notes) }}</textarea>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg font-weight-bold shadow-sm rounded-3 py-2 fs-6" id="savePolicyBtn">
                                            <i class="fa fa-save me-1"></i> Save Terms & Policy
                                        </button>
                                        <a href="{{ route('hr.policy.index') }}" class="btn btn-outline-secondary btn-sm font-weight-bold py-1.5">
                                            Cancel & Return
                                        </a>
                                    </div>
                                </div>

                                <!-- Live Summary Panel -->
                                <div class="live-preview-panel">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="font-weight-bold" style="font-size: 0.82rem; color: #a5b4fc;"><i class="fa fa-eye me-1"></i> Live Rules Summary</span>
                                        <span class="badge bg-success py-0 px-2" style="font-size: 0.65rem;">Auto Updating</span>
                                    </div>

                                    <div class="preview-rule-item">
                                        <div class="text-muted" style="font-size: 0.65rem; color: #94a3b8 !important;">SCOPE AUDIENCE:</div>
                                        <div class="font-weight-bold text-white small" id="previewScope">Global All Employees</div>
                                    </div>

                                    <div class="preview-rule-item">
                                        <div class="text-muted" style="font-size: 0.65rem; color: #94a3b8 !important;">OVERTIME:</div>
                                        <div class="font-weight-bold text-info small" id="previewOvertime">Enabled (@ 1.5x Rate)</div>
                                    </div>

                                    <div class="preview-rule-item">
                                        <div class="text-muted" style="font-size: 0.65rem; color: #94a3b8 !important;">LATE PENALTY:</div>
                                        <div class="font-weight-bold text-warning small" id="previewLate">3 Lates = 1 Day Deduction</div>
                                    </div>

                                    <div class="preview-rule-item">
                                        <div class="text-muted" style="font-size: 0.65rem; color: #94a3b8 !important;">HALF DUTY RULE:</div>
                                        <div class="font-weight-bold small" style="color: #c084fc;" id="previewHalfDay">< 5.0 Hrs = 0.5 Day Salary</div>
                                    </div>

                                    <div class="preview-rule-item mb-0">
                                        <div class="text-muted" style="font-size: 0.65rem; color: #94a3b8 !important;">ABSENCE DEDUCTION:</div>
                                        <div class="font-weight-bold text-danger-light small" style="color: #fca5a5;" id="previewAbsence">Salary / 30 Per Day</div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function updateEmpCountBadge() {
            var count = $('.emp-checkbox:checked').length;
            $('#selectedEmpCountBadge').text('Selected: ' + count + ' employee' + (count === 1 ? '' : 's'));
        }

        function toggleEmpCard(el) {
            var cb = $(el).find('.emp-checkbox');
            cb.prop('checked', !cb.prop('checked'));
            if (cb.prop('checked')) {
                $(el).addClass('checked');
            } else {
                $(el).removeClass('checked');
            }
            updateEmpCountBadge();
            updateLivePreview();
        }

        function updateLivePreview() {
            // Scope
            var isSpecific = $('#applies_specific').is(':checked');
            if (isSpecific) {
                var cnt = $('.emp-checkbox:checked').length;
                $('#previewScope').html('🎯 Specific (' + cnt + ' targeted staff)');
            } else {
                $('#previewScope').html('🌐 Global (All Active Staff)');
            }

            // Overtime
            var otEnabled = $('#overtime_enabled').is(':checked');
            if (!otEnabled) {
                $('#previewOvertime').html('<span class="text-muted">Disabled</span>');
            } else {
                var otType = $('#overtime_rate_type').val();
                var mins = $('#overtime_min_minutes').val() || '60';
                if (otType === 'fixed') {
                    var rate = $('#overtime_fixed_rate').val() || '0';
                    $('#previewOvertime').html('⚡ Rs. ' + rate + '/hr (After ' + mins + 'm)');
                } else {
                    var mult = $('#overtime_multiplier').val() || '1.5';
                    $('#previewOvertime').html('⚡ ' + mult + 'x Hourly Rate (After ' + mins + 'm)');
                }
            }

            // Late
            var lateType = $('#late_penalty_type').val();
            var grace = $('#late_grace_minutes').val() || '15';
            if (lateType === '3_lates_1_day') {
                $('#previewLate').html('🚨 3 Lates = 1 Day Deduction (' + grace + 'm Grace)');
            } else if (lateType === 'fixed_per_instance') {
                var lAmt = $('#late_penalty_amount').val() || '0';
                $('#previewLate').html('💵 Rs. ' + lAmt + ' / Late (' + grace + 'm Grace)');
            } else {
                $('#previewLate').html('🟢 No Penalty (' + grace + 'm Grace)');
            }

            // Half Day
            var hHrs = $('#min_hours_half_day').val() || '5.0';
            var hType = $('#half_day_deduction_type').val();
            if (hType === 'half_day_salary') {
                $('#previewHalfDay').html('🌓 < ' + hHrs + ' Hrs = 0.5 Day Salary');
            } else if (hType === 'fixed') {
                var hAmt = $('#half_day_fixed_amount').val() || '500';
                $('#previewHalfDay').html('💵 < ' + hHrs + ' Hrs = Rs. ' + hAmt + ' Deduct');
            } else {
                $('#previewHalfDay').html('🟢 < ' + hHrs + ' Hrs (Log Only)');
            }

            // Absence
            var absType = $('#absence_deduction_type').val();
            if (absType === 'fixed') {
                var aAmt = $('#absence_fixed_amount').val() || '0';
                $('#previewAbsence').html('💵 Rs. ' + aAmt + ' / Absence Day');
            } else {
                $('#previewAbsence').html('📅 Basic Salary / 30 Per Day');
            }
        }

        $(document).ready(function() {

            updateEmpCountBadge();
            updateLivePreview();

            // Scope Radio Toggle Listener
            $('input[name="applies_to"]').on('change', function() {
                if ($(this).val() === 'specific') {
                    $('#employeeSelectorSection').removeClass('d-none');
                } else {
                    $('#employeeSelectorSection').addClass('d-none');
                }
                updateLivePreview();
            });

            // Employee search filter
            $('#empSearchInput').on('keyup', function() {
                var q = $(this).val().toLowerCase();
                $('.emp-card-col').each(function() {
                    var text = $(this).data('search');
                    if (text.indexOf(q) !== -1) {
                        $(this).removeClass('d-none');
                    } else {
                        $(this).addClass('d-none');
                    }
                });
            });

            // Select / Deselect All
            $('#selectAllEmps').on('click', function() {
                $('.emp-checkbox').prop('checked', true);
                $('.emp-card-item').addClass('checked');
                updateEmpCountBadge();
                updateLivePreview();
            });

            $('#deselectAllEmps').on('click', function() {
                $('.emp-checkbox').prop('checked', false);
                $('.emp-card-item').removeClass('checked');
                updateEmpCountBadge();
                updateLivePreview();
            });

            // Input listener triggers for live preview
            $('input, select, textarea').on('input change', function() {
                updateLivePreview();
            });

            // Toggle Overtime rate wrappers
            $('#overtime_rate_type').on('change', function() {
                if ($(this).val() === 'fixed') {
                    $('#multiplierWrapper').addClass('d-none');
                    $('#fixedRateWrapper').removeClass('d-none');
                } else {
                    $('#multiplierWrapper').removeClass('d-none');
                    $('#fixedRateWrapper').addClass('d-none');
                }
                updateLivePreview();
            }).trigger('change');

            // Toggle Late penalty wrappers
            $('#late_penalty_type').on('change', function() {
                if ($(this).val() === 'fixed_per_instance') {
                    $('#lateFixedWrapper').removeClass('d-none');
                } else {
                    $('#lateFixedWrapper').addClass('d-none');
                }
                updateLivePreview();
            }).trigger('change');

            // Toggle Half Day deduction wrappers
            $('#half_day_deduction_type').on('change', function() {
                if ($(this).val() === 'fixed') {
                    $('#halfDayFixedWrapper').removeClass('d-none');
                } else {
                    $('#halfDayFixedWrapper').addClass('d-none');
                }
                updateLivePreview();
            }).trigger('change');

            // Toggle Absence fixed wrappers
            $('#absence_deduction_type').on('change', function() {
                if ($(this).val() === 'fixed') {
                    $('#absenceFixedWrapper').removeClass('d-none');
                } else {
                    $('#absenceFixedWrapper').addClass('d-none');
                }
                updateLivePreview();
            }).trigger('change');

            // Safe notification helper
            function showNotify(msg, type) {
                if (typeof toastr !== 'undefined') {
                    if (type === 'success') toastr.success(msg);
                    else toastr.error(msg);
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: type, text: msg, timer: 1500, showConfirmButton: false });
                } else {
                    alert(msg);
                }
            }

            // Form Submit via AJAX with redirect
            $('#policyFullForm').on('submit', function(e) {
                e.preventDefault();

                var appliesTo = $('input[name="applies_to"]:checked').val();
                if (appliesTo === 'specific' && $('.emp-checkbox:checked').length === 0) {
                    showNotify('Please select at least 1 employee for Specific Targeted Policy.', 'error');
                    return false;
                }

                var btn = $('#savePolicyBtn');
                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving Policy...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: "POST",
                    data: $(this).serialize(),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (typeof response === 'string' && response.indexOf('<html') !== -1) {
                            btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Terms & Policy');
                            showNotify('Session expired or unexpected response. Please refresh.', 'error');
                            return;
                        }
                        showNotify(response.message || 'Policy Saved Successfully!', 'success');
                        setTimeout(function() {
                            window.location.href = response.redirect || "{{ route('hr.policy.index') }}";
                        }, 500);
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Save Terms & Policy');
                        var msg = 'Failed to save policy.';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.error) msg = xhr.responseJSON.error;
                            else if (xhr.responseJSON.message) msg = xhr.responseJSON.message;
                            else if (xhr.responseJSON.errors) {
                                var errs = Object.values(xhr.responseJSON.errors).flat();
                                msg = errs.join('\n');
                            }
                        }
                        showNotify(msg, 'error');
                    }
                });
            });

        });
    </script>
    @endpush
@endsection

@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    <style>
        .policy-table-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .policy-table th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 12px 14px;
            border-bottom: 2px solid #e2e8f0;
            vertical-align: middle;
        }
        .policy-table td {
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.84rem;
        }
        .policy-table tr:hover td {
            background-color: #f8fafc;
        }
        .policy-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 16px;
            display: inline-block;
        }
        .rule-pill {
            font-size: 0.74rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .emp-chip {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 12px;
            background: #f1f5f9;
            color: #334155;
            font-size: 0.72rem;
            font-weight: 600;
            margin: 2px;
            border: 1px solid #e2e8f0;
        }
        .modal-rule-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px;
            background: #ffffff;
            height: 100%;
        }
        .icon-badge {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid py-4">

                <!-- Header -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                    <div>
                        <h4 class="font-weight-bold mb-1 text-dark">
                            <i class="fa fa-file-contract text-primary me-2"></i> HR Attendance Policies
                        </h4>
                        <p class="text-muted small mb-0">Manage Overtime, Late Rules, Half Duty & Absence Deductions.</p>
                    </div>
                    <div>
                        <a href="{{ route('hr.policy.create') }}" class="btn btn-primary font-weight-bold px-3 rounded-pill">
                            <i class="fa fa-plus-circle me-1"></i> Create Policy
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-primary border-4">
                            <div class="small text-muted font-weight-bold">TOTAL POLICIES</div>
                            <div class="h4 font-weight-bold text-dark mb-0 mt-1">{{ $policies->count() }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-success border-4">
                            <div class="small text-muted font-weight-bold">DEFAULT POLICY</div>
                            <div class="h6 font-weight-bold text-success mb-0 mt-1 text-truncate">
                                {{ optional($policies->where('is_default', true)->first())->name ?? 'System Standard' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-warning border-4">
                            <div class="small text-muted font-weight-bold">SPECIFIC POLICIES</div>
                            <div class="h4 font-weight-bold text-warning mb-0 mt-1">
                                {{ $policies->where('applies_to', 'specific')->count() }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 p-3 bg-white border-start border-info border-4">
                            <div class="small text-muted font-weight-bold">OVERTIME ACTIVE</div>
                            <div class="h4 font-weight-bold text-info mb-0 mt-1">
                                {{ $policies->where('overtime_enabled', true)->count() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row-wise Policy List Table -->
                <div class="policy-table-card">
                    <div class="table-responsive">
                        <table class="table policy-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="min-width: 200px;">Policy & Scope</th>
                                    <th style="min-width: 160px;">Overtime</th>
                                    <th style="min-width: 200px;">Late & Half Duty</th>
                                    <th style="min-width: 160px;">Absence</th>
                                    <th class="text-center" style="width: 80px;">Status</th>
                                    <th class="text-end" style="min-width: 190px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($policies as $policy)
                                    @php
                                        $empCount = is_array($policy->employee_ids) ? count($policy->employee_ids) : 0;
                                        $assignedEmpList = [];
                                        if ($policy->applies_to === 'specific' && is_array($policy->employee_ids)) {
                                            foreach($policy->employee_ids as $empId) {
                                                $emp = $employees->firstWhere('id', $empId);
                                                if ($emp) {
                                                    $assignedEmpList[] = [
                                                        'id' => $emp->id,
                                                        'name' => $emp->first_name . ' ' . $emp->last_name,
                                                        'department' => optional($emp->department)->name ?? 'Staff'
                                                    ];
                                                }
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="font-weight-bold text-dark fs-6">{{ $policy->name }}</div>
                                            <div class="d-flex flex-wrap gap-1 mt-1">
                                                @if($policy->is_default)
                                                    <span class="policy-badge" style="background:#dcfce7; color:#15803d; border:1px solid #86efac;">
                                                        <i class="fa fa-star me-1"></i> Default
                                                    </span>
                                                @endif

                                                @if($policy->applies_to === 'all')
                                                    <span class="policy-badge" style="background:#e0e7ff; color:#4338ca; border:1px solid #c7d2fe;">
                                                        🌐 All Staff
                                                    </span>
                                                @else
                                                    <span class="policy-badge" style="background:#fef3c7; color:#b45309; border:1px solid #fde68a;">
                                                        🎯 {{ $empCount }} Staff
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($policy->overtime_enabled)
                                                <div class="rule-pill mb-1" style="background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">
                                                    <i class="fa fa-clock text-info"></i>
                                                    {{ $policy->overtime_rate_type === 'fixed' ? 'Rs. '.number_format($policy->overtime_fixed_rate,0).'/hr' : $policy->overtime_multiplier.'x Rate' }}
                                                </div>
                                                <div class="sub-help-text text-muted" style="font-size: 0.71rem;">
                                                    Grace: > {{ $policy->overtime_min_minutes }}m
                                                </div>
                                            @else
                                                <span class="badge bg-light text-muted border">Disabled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="mb-1" style="font-size: 0.78rem;">
                                                <span class="fw-bold text-dark"><i class="fa fa-exclamation-circle text-warning me-1"></i> Late:</span>
                                                <span class="text-danger font-weight-bold">
                                                    @if($policy->late_penalty_type === '3_lates_1_day')
                                                        3 Lates = 1 Day
                                                    @elseif($policy->late_penalty_type === 'fixed_per_instance')
                                                        Rs. {{ number_format($policy->late_penalty_amount, 0) }} / Late
                                                    @else
                                                        {{ $policy->late_grace_minutes }}m Grace
                                                    @endif
                                                </span>
                                            </div>
                                            <div style="font-size: 0.78rem;">
                                                <span class="fw-bold text-dark"><i class="fa fa-user-clock me-1" style="color:#8b5cf6;"></i> Half Duty:</span>
                                                <span class="text-dark font-weight-bold">
                                                    @if(($policy->half_day_deduction_type ?? 'half_day_salary') === 'half_day_salary')
                                                        0.5 Day (&lt; {{ $policy->min_hours_half_day ?? 5.0 }}h)
                                                    @elseif(($policy->half_day_deduction_type ?? 'half_day_salary') === 'fixed')
                                                        Rs. {{ number_format($policy->half_day_fixed_amount ?? 500, 0) }} (&lt; {{ $policy->min_hours_half_day ?? 5.0 }}h)
                                                    @else
                                                        Log Only (&lt; {{ $policy->min_hours_half_day ?? 5.0 }}h)
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size: 0.78rem;">
                                                <i class="fa fa-calendar-times text-danger me-1"></i>
                                                @if($policy->absence_deduction_type === 'salary_divided_by_30')
                                                    1 Day Salary
                                                @else
                                                    Rs. {{ number_format($policy->absence_fixed_amount, 0) }} / Day
                                                @endif
                                            </div>
                                            @if(($policy->early_checkout_penalty_type ?? 'none') !== 'none')
                                                <div class="sub-help-text text-muted" style="font-size: 0.71rem;">
                                                    Early Out: {{ $policy->early_checkout_penalty_type === '3_earlies_1_day' ? '3 Earlies = 1 Day' : 'Rs. '.$policy->early_checkout_penalty_amount }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input toggle-status" type="checkbox" data-id="{{ $policy->id }}" {{ $policy->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">
                                                <!-- View Details Button -->
                                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2 font-weight-bold view-policy-btn"
                                                    data-policy="{{ json_encode($policy) }}"
                                                    data-employees="{{ json_encode($assignedEmpList) }}">
                                                    <i class="fa fa-eye me-1"></i> View
                                                </button>

                                                <!-- Edit Button -->
                                                <a href="{{ route('hr.policy.edit', $policy->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2 font-weight-bold">
                                                    <i class="fa fa-edit me-1"></i> Edit
                                                </a>

                                                <!-- Delete Button -->
                                                @if(!$policy->is_default)
                                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 delete-policy-btn" data-id="{{ $policy->id }}" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fa fa-file-contract text-muted mb-2" style="font-size: 2rem;"></i>
                                            <h6 class="font-weight-bold text-dark mb-1">No Policy Configured</h6>
                                            <a href="{{ route('hr.policy.create') }}" class="btn btn-primary btn-sm font-weight-bold rounded-pill px-3 mt-1">
                                                <i class="fa fa-plus me-1"></i> Create Policy
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Complete Detail View Modal -->
    <div class="modal fade" id="viewPolicyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="icon-badge bg-primary text-white">
                            <i class="fa fa-file-contract"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold mb-0 text-white" id="modalPolicyTitle">Policy Summary</h5>
                            <span class="small text-light opacity-75" id="modalPolicySub">Attendance & Payroll Rules Overview</span>
                        </div>
                    </div>
                    <button type="button" class="close text-white border-0 bg-transparent opacity-75" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; line-height: 1; outline: none;"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body p-3" style="background: #f8fafc;">

                    <!-- Scope Banner -->
                    <div class="card border-0 shadow-sm rounded-3 p-3 mb-3 bg-white">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <span class="text-muted small font-weight-bold me-2">SCOPE:</span>
                                <span id="modalScopeBadge" class="policy-badge"></span>
                            </div>
                            <div>
                                <span class="text-muted small font-weight-bold me-2">STATUS:</span>
                                <span id="modalStatusBadge" class="badge"></span>
                            </div>
                        </div>
                        <div id="modalTargetEmployeesWrapper" class="mt-2 d-none border-top pt-2">
                            <div class="small font-weight-bold text-dark mb-1" id="modalEmpCountHeading">
                                <i class="fa fa-users text-primary me-1"></i> Assigned Staff:
                            </div>
                            <div id="modalEmpChipsList" class="d-flex flex-wrap gap-1" style="max-height: 100px; overflow-y: auto;"></div>
                        </div>
                    </div>

                    <!-- 4 Main Rule Cards Grid -->
                    <div class="row g-2">

                        <!-- Overtime Card -->
                        <div class="col-md-6">
                            <div class="modal-rule-card">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="icon-badge" style="background: #e0f2fe; color: #0284c7;">
                                        <i class="fa fa-clock"></i>
                                    </div>
                                    <h6 class="font-weight-bold text-dark mb-0 fs-6">1. Overtime</h6>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="text-muted small">Status:</span>
                                    <span class="font-weight-bold small" id="modalOvertimeStatus"></span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="text-muted small">Grace:</span>
                                    <span class="font-weight-bold small text-dark" id="modalOvertimeMin"></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Rate:</span>
                                    <span class="font-weight-bold small text-primary" id="modalOvertimeRate"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Late Penalty Card -->
                        <div class="col-md-6">
                            <div class="modal-rule-card">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="icon-badge" style="background: #fef3c7; color: #d97706;">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </div>
                                    <h6 class="font-weight-bold text-dark mb-0 fs-6">2. Late Rules</h6>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="text-muted small">Grace:</span>
                                    <span class="font-weight-bold small text-dark" id="modalLateGrace"></span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="text-muted small">Rule:</span>
                                    <span class="font-weight-bold small text-danger" id="modalLateType"></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Rate:</span>
                                    <span class="font-weight-bold small text-dark" id="modalLateAmount"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Half Duty Card -->
                        <div class="col-md-6">
                            <div class="modal-rule-card">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="icon-badge" style="background: #f3e8ff; color: #9333ea;">
                                        <i class="fa fa-user-clock"></i>
                                    </div>
                                    <h6 class="font-weight-bold text-dark mb-0 fs-6">3. Half Duty</h6>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="text-muted small">Duty Limit:</span>
                                    <span class="font-weight-bold small text-dark" id="modalHalfDayMin"></span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="text-muted small">Rule:</span>
                                    <span class="font-weight-bold small text-dark" id="modalHalfDayType"></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Rate:</span>
                                    <span class="font-weight-bold small text-dark" id="modalHalfDayAmount"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Absence Card -->
                        <div class="col-md-6">
                            <div class="modal-rule-card">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="icon-badge" style="background: #fee2e2; color: #dc2626;">
                                        <i class="fa fa-calendar-times"></i>
                                    </div>
                                    <h6 class="font-weight-bold text-dark mb-0 fs-6">4. Absence & Early Out</h6>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="text-muted small">Absence:</span>
                                    <span class="font-weight-bold small text-dark" id="modalAbsenceType"></span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                    <span class="text-muted small">Early Out:</span>
                                    <span class="font-weight-bold small text-dark" id="modalEarlyType"></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Rate:</span>
                                    <span class="font-weight-bold small text-dark" id="modalEarlyAmount"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Notes Section -->
                    <div id="modalNotesWrapper" class="mt-2 d-none">
                        <div class="card border-0 shadow-sm rounded-3 p-2 bg-white">
                            <div class="small font-weight-bold text-muted mb-1"><i class="fa fa-sticky-note me-1"></i> Notes:</div>
                            <div class="small text-dark" id="modalNotesContent"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white rounded-bottom-4 py-2">
                    <a href="#" id="modalEditBtn" class="btn btn-primary btn-sm rounded-pill px-3 font-weight-bold">
                        <i class="fa fa-edit me-1"></i> Edit Policy
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3 close-modal-btn" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {

            // Open View Modal
            $('.view-policy-btn').on('click', function() {
                var policy = $(this).data('policy');
                var employees = $(this).data('employees') || [];

                $('#modalPolicyTitle').text(policy.name);
                $('#modalEditBtn').attr('href', "/hr/terms-conditions/" + policy.id + "/edit");

                // Scope Badge
                if (policy.is_default) {
                    $('#modalScopeBadge').attr('style', 'background:#dcfce7; color:#15803d; border:1px solid #86efac;')
                        .html('<i class="fa fa-star me-1"></i> Default Policy');
                } else if (policy.applies_to === 'all') {
                    $('#modalScopeBadge').attr('style', 'background:#e0e7ff; color:#4338ca; border:1px solid #c7d2fe;')
                        .html('🌐 All Staff');
                } else {
                    $('#modalScopeBadge').attr('style', 'background:#fef3c7; color:#b45309; border:1px solid #fde68a;')
                        .html('🎯 Specific Staff (' + employees.length + ')');
                }

                // Status Badge
                if (policy.is_active) {
                    $('#modalStatusBadge').attr('class', 'badge bg-success').text('Active');
                } else {
                    $('#modalStatusBadge').attr('class', 'badge bg-secondary').text('Inactive');
                }

                // Target Employees List
                if (policy.applies_to === 'specific' && employees.length > 0) {
                    $('#modalTargetEmployeesWrapper').removeClass('d-none');
                    $('#modalEmpCountHeading').html('<i class="fa fa-users text-primary me-1"></i> Assigned Staff (' + employees.length + '):');
                    var chipsHtml = '';
                    $.each(employees, function(idx, emp) {
                        chipsHtml += '<span class="emp-chip"><i class="fa fa-user me-1 text-primary" style="font-size:0.65rem;"></i> ' + emp.name + ' <small class="text-muted ms-1">(' + emp.department + ')</small></span>';
                    });
                    $('#modalEmpChipsList').html(chipsHtml);
                } else {
                    $('#modalTargetEmployeesWrapper').addClass('d-none');
                }

                // Overtime
                if (policy.overtime_enabled) {
                    $('#modalOvertimeStatus').html('<span class="text-success font-weight-bold"><i class="fa fa-check-circle me-1"></i> Active</span>');
                    $('#modalOvertimeMin').text('> ' + policy.overtime_min_minutes + ' min');
                    if (policy.overtime_rate_type === 'fixed') {
                        $('#modalOvertimeRate').text('Rs. ' + parseFloat(policy.overtime_fixed_rate || 0).toFixed(0) + ' / hr');
                    } else {
                        $('#modalOvertimeRate').text(parseFloat(policy.overtime_multiplier || 1.5).toFixed(1) + 'x Rate');
                    }
                } else {
                    $('#modalOvertimeStatus').html('<span class="text-muted"><i class="fa fa-times-circle me-1"></i> Disabled</span>');
                    $('#modalOvertimeMin').text('None');
                    $('#modalOvertimeRate').text('None');
                }

                // Late Penalty
                $('#modalLateGrace').text(policy.late_grace_minutes + ' min');
                if (policy.late_penalty_type === '3_lates_1_day') {
                    $('#modalLateType').text('3 Lates = 1 Day');
                    $('#modalLateAmount').text('1 Day Salary');
                } else if (policy.late_penalty_type === 'fixed_per_instance') {
                    $('#modalLateType').text('Fixed Penalty');
                    $('#modalLateAmount').text('Rs. ' + parseFloat(policy.late_penalty_amount || 0).toFixed(0));
                } else {
                    $('#modalLateType').text('None');
                    $('#modalLateAmount').text('None');
                }

                // Half Duty
                var halfMin = policy.min_hours_half_day ? parseFloat(policy.min_hours_half_day).toFixed(1) : '5.0';
                $('#modalHalfDayMin').text('< ' + halfMin + ' Hours');
                if ((policy.half_day_deduction_type || 'half_day_salary') === 'half_day_salary') {
                    $('#modalHalfDayType').html('<span class="text-danger font-weight-bold">0.5 Day Salary</span>');
                    $('#modalHalfDayAmount').text('Half Day Rate');
                } else if (policy.half_day_deduction_type === 'fixed') {
                    $('#modalHalfDayType').html('<span class="text-danger font-weight-bold">Fixed Amount</span>');
                    $('#modalHalfDayAmount').text('Rs. ' + parseFloat(policy.half_day_fixed_amount || 0).toFixed(0));
                } else {
                    $('#modalHalfDayType').html('<span class="text-muted">Log Only</span>');
                    $('#modalHalfDayAmount').text('Rs. 0');
                }

                // Absence & Early Out
                if (policy.absence_deduction_type === 'salary_divided_by_30') {
                    $('#modalAbsenceType').text('1 Day Salary');
                } else {
                    $('#modalAbsenceType').text('Rs. ' + parseFloat(policy.absence_fixed_amount || 0).toFixed(0) + ' / Day');
                }

                if (policy.early_checkout_penalty_type === '3_earlies_1_day') {
                    $('#modalEarlyType').text('3 Earlies = 1 Day');
                    $('#modalEarlyAmount').text('1 Day Salary');
                } else if (policy.early_checkout_penalty_type === 'fixed_per_instance') {
                    $('#modalEarlyType').text('Fixed Penalty');
                    $('#modalEarlyAmount').text('Rs. ' + parseFloat(policy.early_checkout_penalty_amount || 0).toFixed(0));
                } else {
                    $('#modalEarlyType').text('None');
                    $('#modalEarlyAmount').text('None');
                }

                // Notes
                if (policy.notes && $.trim(policy.notes) !== '') {
                    $('#modalNotesWrapper').removeClass('d-none');
                    $('#modalNotesContent').text(policy.notes);
                } else {
                    $('#modalNotesWrapper').addClass('d-none');
                }

                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    try {
                        var modalObj = bootstrap.Modal.getInstance(document.getElementById('viewPolicyModal')) || new bootstrap.Modal(document.getElementById('viewPolicyModal'));
                        modalObj.show();
                    } catch(err) {
                        $('#viewPolicyModal').modal('show');
                    }
                } else {
                    $('#viewPolicyModal').modal('show');
                }
            });

            // Universal Modal Close Handler (supports BS4 & BS5)
            $(document).on('click', '[data-dismiss="modal"], [data-bs-dismiss="modal"], .close-modal-btn', function() {
                $('#viewPolicyModal').modal('hide');
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    try {
                        var modalObj = bootstrap.Modal.getInstance(document.getElementById('viewPolicyModal'));
                        if (modalObj) modalObj.hide();
                    } catch(e) {}
                }
            });

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

            // Toggle Policy Status
            $('.toggle-status').on('change', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: "/hr/terms-conditions/" + id + "/toggle",
                    type: "POST",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        showNotify(response.message || 'Status updated!', 'success');
                    },
                    error: function() {
                        showNotify('Failed to update status.', 'error');
                    }
                });
            });

            // Delete Policy
            $('.delete-policy-btn').on('click', function() {
                var id = $(this).data('id');
                if (confirm('Are you sure you want to delete this policy?')) {
                    $.ajax({
                        url: "/hr/terms-conditions/" + id,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            showNotify(response.message || 'Deleted successfully!', 'success');
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        },
                        error: function(xhr) {
                            var msg = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Failed to delete policy.';
                            showNotify(msg, 'error');
                        }
                    });
                }
            });

        });
    </script>
    @endpush
@endsection

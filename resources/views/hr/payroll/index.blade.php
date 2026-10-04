@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    <style>
        .payroll-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--hr-border);
            padding-bottom: 0;
        }

        .payroll-tab {
            padding: 12px 24px;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 600;
            color: var(--hr-text-light);
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            bottom: -2px;
        }

        .payroll-tab.active {
            color: #6366f1;
            border-bottom-color: #6366f1;
        }

        .payroll-tab:hover {
            color: var(--hr-text);
        }

        .payroll-card {
            background: var(--hr-card);
            border: 1px solid var(--hr-border);
            border-radius: 14px;
            padding: 20px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .payroll-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(180deg, #6366f1, #8b5cf6);
        }

        .payroll-card.monthly::before {
            background: linear-gradient(180deg, #3b82f6, #2563eb);
        }

        .payroll-card.daily::before {
            background: linear-gradient(180deg, #22c55e, #16a34a);
        }

        .payroll-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        }

        .payroll-type-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .payroll-type-badge.monthly {
            background: #dbeafe;
            color: #1e40af;
        }

        .payroll-type-badge.daily {
            background: #d1fae5;
            color: #065f46;
        }

        .salary-display {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            margin-top: 16px;
        }

        .salary-display.monthly {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .salary-display.daily {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .salary-display .amount {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .salary-display .label {
            font-size: 0.8rem;
            opacity: 0.95;
        }

        .breakdown-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 0.875rem;
            border-bottom: 1px dashed var(--hr-border);
        }

        .breakdown-row:last-child {
            border-bottom: none;
        }

        .breakdown-row .label {
            color: var(--hr-text-light);
        }

        .breakdown-row .value {
            font-weight: 600;
            color: var(--hr-text);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-badge.generated {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.reviewed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.paid {
            background: #d1fae5;
            color: #065f46;
        }

        .month-badge {
            background: #f8fafc;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--hr-text);
            border: 1px solid var(--hr-border);
        }

        .payroll-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
        }

        .payroll-actions .btn {
            flex: 1;
            padding: 8px;
            font-size: 0.875rem;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--hr-text-light);
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--hr-border);
            margin-bottom: 16px;
        }

        .modal-detail-section {
            margin-bottom: 24px;
        }

        .modal-detail-section h6 {
            font-weight: 700;
            color: var(--hr-text);
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--hr-border);
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed var(--hr-border);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row .label {
            color: var(--hr-text-light);
            font-weight: 500;
        }

        .detail-row .value {
            font-weight: 600;
            color: var(--hr-text);
        }

        .detail-row.total {
            font-size: 1.1rem;
            padding-top: 12px;
            margin-top: 8px;
            border-top: 2px solid var(--hr-border);
        }

        .policy-table-card {
            background: #ffffff;
            border: 1px solid var(--hr-border, #e2e8f0);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .policy-table {
            margin-bottom: 0;
            width: 100%;
        }

        .policy-table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .policy-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.875rem;
        }

        .policy-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .hr-avatar-sm {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .net-payable {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 24px;
            border-radius: 16px;
            text-align: center;
            margin-top: 24px;
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
            position: relative;
            overflow: hidden;
        }

        .net-payable::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 40%, rgba(255, 255, 255, 0.1) 45%, transparent 50%);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .net-payable .label {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .net-payable .amount {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1.2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Modern Expandable Sections */
        .expandable-section {
            border: 1px solid var(--modern-border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .expandable-section:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
            border-color: #cbd5e1;
        }

        .expandable-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            background: white;
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }

        .expandable-header:hover {
            background: #f8fafc;
        }

        .expandable-header.active {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
        }

        .expandable-header.active .expand-icon {
            transform: rotate(180deg);
            color: white;
        }

        .expandable-header.active .expandable-value {
            color: white;
        }

        .expandable-header.active .detail-item-label {
            color: rgba(255, 255, 255, 0.9);
        }

        .expandable-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            font-size: 1rem;
        }

        .expandable-title i {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .expandable-value {
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--modern-text);
        }

        .expand-icon {
            font-size: 0.9rem;
            transition: transform 0.3s ease, color 0.2s ease;
            color: var(--modern-text-light);
            background: rgba(0, 0, 0, 0.05);
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .expandable-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
            background: #f8fafc;
            padding: 0 20px;
        }

        .expandable-content.active {
            max-height: 1000px;
            padding: 20px;
            border-top: 1px solid var(--modern-border);
        }

        /* Scrollable Attendance Details */
        .attendance-details-scroll {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .attendance-details-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .attendance-details-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .attendance-details-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .attendance-details-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .section-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid var(--modern-border);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f5f9;
            color: var(--modern-text);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .section-header i {
            color: var(--modern-primary);
            font-size: 1.1rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 16px;
            background: white;
            border-radius: 8px;
            margin-bottom: 8px;
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        .detail-item:hover {
            border-color: var(--modern-border);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-item-label {
            color: var(--modern-text-light);
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-item-value {
            font-weight: 600;
            color: var(--modern-text);
            font-size: 1rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px dashed var(--modern-border);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row.total {
            background: #f0fdf4;
            padding: 16px;
            border-radius: 12px;
            margin-top: 16px;
            border: 1px solid #bbf7d0;
        }

        .detail-row.total .label {
            font-weight: 700;
            color: #166534;
        }

        .detail-row.total .value {
            font-size: 1.2rem;
            font-weight: 800;
            color: #15803d;
        }

        .detail-row.total-deduction {
            background: #fef2f2;
            padding: 16px;
            border-radius: 12px;
            margin-top: 16px;
            border: 1px solid #fecaca;
        }

        .detail-row.total-deduction .label {
            font-weight: 700;
            color: #991b1b;
        }

        .detail-row.total-deduction .value {
            font-size: 1.2rem;
            font-weight: 800;
            color: #b91c1c;
        }

        .no-data-message {
            text-align: center;
            padding: 30px;
            color: var(--modern-text-light);
            font-style: italic;
            font-size: 0.9rem;
            background: white;
            border-radius: 8px;
        }

        .period-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            padding: 16px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 24px;
            box-shadow: 0 8px 20px -6px rgba(99, 102, 241, 0.4);
        }

        .attendance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .stat-box {
            background: white;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid var(--modern-border);
        }

        .stat-box .count {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
            display: block;
        }

        .stat-box .text {
            font-size: 0.75rem;
            color: var(--modern-text-light);
            text-transform: uppercase;
            font-weight: 600;
        }

        .period-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 20px;
        }

        .attendance-stat {
            background: #f8fafc;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
            border-left: 3px solid #6366f1;
        }

        .attendance-stat-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
        }

        .stat-highlight {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 700;
            color: #92400e;
        }

        .stat-highlight.danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .stat-highlight.success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid px-2 px-md-4">
                <!-- Page Header -->
                <div class="page-header d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="page-title"><i class="fa fa-money-bill-wave"></i> Payroll Management</h1>
                        <p class="page-subtitle">Manage monthly and daily employee payroll</p>
                    </div>
                    <div class="d-flex gap-2">
                        @can('hr.payroll.create')
                            <div class="dropdown">
                                <button class="btn btn-create dropdown-toggle" type="button" id="generateDropdown"
                                    data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-plus-circle me-1"></i> Generate Payroll
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-end shadow" aria-labelledby="generateDropdown" style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 8px;">
                                    <a class="dropdown-item py-2 px-3 rounded" href="javascript:void(0)" id="generateMonthlyBtn"
                                        data-toggle="modal" data-target="#generateMonthlyModal"
                                        data-bs-toggle="modal" data-bs-target="#generateMonthlyModal">
                                        <i class="fa fa-calendar-alt me-2 text-primary"></i> Generate Monthly Payroll
                                    </a>
                                    <a class="dropdown-item py-2 px-3 rounded" href="javascript:void(0)" id="generateDailyBtn"
                                        data-toggle="modal" data-target="#generateDailyModal"
                                        data-bs-toggle="modal" data-bs-target="#generateDailyModal">
                                        <i class="fa fa-calendar-day me-2 text-success"></i> Generate Daily Payroll
                                    </a>
                                    <div class="dropdown-divider my-1"></div>
                                    <a class="dropdown-item py-2 px-3 rounded" href="javascript:void(0)" id="generateBtn"
                                        data-toggle="modal" data-target="#generatePayrollModal"
                                        data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                                        <i class="fa fa-hand-holding-usd me-2 text-warning"></i> Manual / Single Entry
                                    </a>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>

                @php
                    $monthlyQuery = \App\Models\Hr\Payroll::monthly();
                    $dailyQuery = \App\Models\Hr\Payroll::daily();
                    if (!empty($selectedMonth) && $selectedMonth !== 'all') {
                        $monthlyQuery->where('month', $selectedMonth);
                        $dailyQuery->where('month', $selectedMonth);
                    }
                    $monthlyCount = $monthlyQuery->count();
                    $dailyCount = $dailyQuery->count();
                @endphp

                <!-- Tabs -->
                <div class="payroll-tabs">
                    <a href="{{ route('hr.payroll.index', ['month' => $selectedMonth ?? '']) }}"
                        class="payroll-tab {{ ($activeTab ?? 'all') === 'all' ? 'active' : '' }}">
                        <i class="fa fa-list"></i> All Payrolls ({{ $monthlyCount + $dailyCount }})
                    </a>
                    <a href="{{ route('hr.payroll.monthly', ['month' => $selectedMonth ?? '']) }}"
                        class="payroll-tab {{ ($activeTab ?? '') === 'monthly' ? 'active' : '' }}">
                        <i class="fa fa-calendar-alt"></i> Monthly ({{ $monthlyCount }})
                    </a>
                    <a href="{{ route('hr.payroll.daily', ['month' => $selectedMonth ?? '']) }}"
                        class="payroll-tab {{ ($activeTab ?? '') === 'daily' ? 'active' : '' }}">
                        <i class="fa fa-calendar-day"></i> Daily ({{ $dailyCount }})
                    </a>
                </div>

                <!-- Payrolls Card -->
                <div class="hr-card">
                    <div class="hr-header">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="search-box">
                                <i class="fa fa-search"></i>
                                <input type="search" id="payrollSearch" placeholder="Search by employee name...">
                            </div>
                            @if(isset($availableMonths) && count($availableMonths) > 0)
                                <div class="d-flex align-items-center gap-2">
                                    <label class="small text-muted fw-bold mb-0 text-nowrap"><i class="fa fa-calendar-alt text-primary me-1"></i> Month:</label>
                                    <select class="form-select form-select-sm" style="min-width: 160px; border-radius: 8px; font-weight: 600;" onchange="window.location.href = this.value">
                                        @foreach($availableMonths as $m)
                                            @php
                                                $mFormatted = \Carbon\Carbon::parse($m . '-01')->format('F Y');
                                                $currentRoute = request()->routeIs('hr.payroll.monthly') ? route('hr.payroll.monthly', ['month' => $m]) : (request()->routeIs('hr.payroll.daily') ? route('hr.payroll.daily', ['month' => $m]) : route('hr.payroll.index', ['month' => $m]));
                                            @endphp
                                            <option value="{{ $currentRoute }}" {{ ($selectedMonth ?? '') === $m ? 'selected' : '' }}>
                                                {{ $mFormatted }}
                                            </option>
                                        @endforeach
                                        @php
                                            $allRoute = request()->routeIs('hr.payroll.monthly') ? route('hr.payroll.monthly', ['month' => 'all']) : (request()->routeIs('hr.payroll.daily') ? route('hr.payroll.daily', ['month' => 'all']) : route('hr.payroll.index', ['month' => 'all']));
                                        @endphp
                                        <option value="{{ $allRoute }}" {{ ($selectedMonth ?? '') === 'all' ? 'selected' : '' }}>
                                            All Months (History)
                                        </option>
                                    </select>
                                </div>
                            @endif
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary btn-sm active" data-status="all">All</button>
                                <button class="btn btn-outline-warning btn-sm" data-status="generated">Pending</button>
                                <button class="btn btn-outline-success btn-sm" data-status="paid">Paid</button>
                            </div>
                        </div>
                        <span class="text-muted small" id="payrollCount">{{ $payrolls->total() }} payrolls</span>
                    </div>

                    <div class="table-responsive" id="payrollGrid">
                        <table class="table policy-table align-middle mb-0" id="payrollTable">
                            <thead>
                                <tr>
                                    <th class="ps-4">Employee</th>
                                    <th class="text-end">Basic Salary</th>
                                    <th class="text-center">Days</th>
                                    <th class="text-center">Present</th>
                                    <th class="text-center">Late</th>
                                    <th class="text-center">Absent</th>
                                    <th class="text-center">OT Hrs</th>
                                    <th class="text-end">OT Amount</th>
                                    <th class="text-end">Deduction</th>
                                    <th class="text-end">Net Salary</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrolls as $payroll)
                                    <tr class="payroll-row {{ $payroll->payroll_type }}"
                                        data-id="{{ $payroll->id }}"
                                        data-name="{{ strtolower($payroll->employee->full_name ?? '') }}"
                                        data-status="{{ $payroll->status }}" data-type="{{ $payroll->payroll_type }}">
                                        <td class="ps-4" style="min-width: 220px;">
                                            <div class="d-flex align-items-center" style="gap: 12px;">
                                                <div style="background: {{ $payroll->payroll_type === 'monthly' ? 'linear-gradient(135deg, #3b82f6, #2563eb)' : 'linear-gradient(135deg, #22c55e, #16a34a)' }}; width: 38px; height: 38px; min-width: 38px; border-radius: 10px; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0;">
                                                    {{ strtoupper(substr($payroll->employee->first_name ?? 'U', 0, 1) . substr($payroll->employee->last_name ?? 'N', 0, 1)) }}
                                                </div>
                                                <div style="min-width: 0; flex: 1;">
                                                    <div class="fw-bold text-dark text-nowrap mb-0" style="font-size: 0.9rem; line-height: 1.3;">{{ $payroll->employee->full_name ?? 'Unknown' }}</div>
                                                    <div class="small text-muted text-nowrap" style="font-size: 0.78rem; line-height: 1.3;">
                                                        <span>{{ $payroll->employee->designation->name ?? 'N/A' }}</span>
                                                        <span class="text-muted">• {{ $payroll->month }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end fw-semibold text-dark">
                                            {{ number_format($payroll->basic_salary, 0) }}
                                        </td>
                                        <td class="text-center fw-semibold text-secondary fs-6">
                                            {{ $payroll->attendance_days ?? 30 }}
                                        </td>
                                        <td class="text-center fw-bold text-success fs-6">
                                            {{ $payroll->attendance_present ?? 0 }}
                                        </td>
                                        <td class="text-center fw-bold text-warning fs-6">
                                            {{ $payroll->attendance_late ?? 0 }}
                                        </td>
                                        <td class="text-center fw-bold text-danger fs-6">
                                            {{ $payroll->attendance_absent ?? 0 }}
                                        </td>
                                        <td class="text-center fw-bold text-info fs-6">
                                            {{ $payroll->ot_hours ?? 0 }}
                                        </td>
                                        <td class="text-end fw-semibold text-dark">
                                            {{ number_format($payroll->ot_amount ?? 0, 0) }}
                                        </td>
                                        <td class="text-end text-danger fw-semibold">
                                            {{ number_format($payroll->total_deductions, 0) }}
                                        </td>
                                        <td class="text-end fw-bold text-success fs-6">
                                            {{ number_format($payroll->net_salary, 0) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="status-badge {{ $payroll->status }}">
                                                @if ($payroll->status === 'generated')
                                                    <i class="fa fa-clock me-1"></i>Pending
                                                @elseif($payroll->status === 'reviewed')
                                                    <i class="fa fa-eye me-1"></i>Reviewed
                                                @else
                                                    <i class="fa fa-check me-1"></i>Paid
                                                @endif
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-1">
                                                @can('hr.payroll.view')
                                                    <button class="btn btn-sm btn-outline-primary view-details-btn px-2 py-1" title="View Details" data-id="{{ $payroll->id }}">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                @endcan

                                                @if ($payroll->canEdit())
                                                    @can('hr.payroll.edit')
                                                        <button class="btn btn-sm btn-outline-warning edit-payroll-btn px-2 py-1" title="Edit" data-id="{{ $payroll->id }}">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    @endcan
                                                @endif



                                                @if ($payroll->canMarkPaid())
                                                    @can('hr.payroll.edit')
                                                        <button class="btn btn-sm btn-success mark-paid-btn px-2 py-1" title="Mark Paid" data-id="{{ $payroll->id }}">
                                                            <i class="fa fa-hand-holding-usd me-1"></i> Pay
                                                        </button>
                                                    @endcan
                                                @endif

                                                @can('hr.payroll.delete')
                                                    @if ($payroll->status !== 'paid')
                                                        <button class="btn btn-sm btn-outline-danger delete-btn px-2 py-1" title="Delete" data-id="{{ $payroll->id }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fa fa-money-bill-wave text-muted mb-3" style="font-size: 3rem;"></i>
                                                <p class="fw-bold mb-1">No payrolls generated yet.</p>
                                                <p class="text-muted small mb-0">Click "Generate Payroll" to create payroll entries.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 border-top">
                        {{ $payrolls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Payroll Modal -->
    <div class="modal fade" id="generatePayrollModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header gradient text-white d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(135deg, #6366f1, #8b5cf6) !important;">
                    <h5 class="modal-title text-white mb-0">
                        <i class="fa fa-plus-circle me-1"></i>
                        <span>Generate Manual Payroll</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; font-size: 1.5rem; opacity: 0.9; cursor: pointer;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="generatePayrollForm" action="{{ route('hr.payroll.generate') }}" method="POST"
                    data-ajax-validate="true">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group-modern">
                            <label class="form-label"><i class="fa fa-bookmark"></i> Payroll Type</label>
                            <select name="payroll_type" class="form-select" required id="payrollTypeSelect">
                                <option value="">Select Type</option>
                                <option value="monthly">Monthly</option>
                                <option value="daily">Daily</option>
                            </select>
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label"><i class="fa fa-user"></i> Employee</label>
                            <select name="employee_id" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-modern" id="monthField">
                            <label class="form-label"><i class="fa fa-calendar"></i> Month</label>
                            <input type="month" name="month" class="form-control" required>
                        </div>
                        <div class="form-group-modern" id="dateField" style="display: none;">
                            <label class="form-label"><i class="fa fa-calendar-day"></i> Date</label>
                            <input type="date" name="date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer-modern">
                        <button type="button" class="btn btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                            <i class="fa fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-save"
                            style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                            <i class="fa fa-check"></i>
                            <span>Generate</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Generate Daily Payrolls Modal -->
    <div class="modal fade" id="generateDailyModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header gradient text-white d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(135deg, #10b981, #059669) !important;">
                    <h5 class="modal-title text-white mb-0">
                        <i class="fa fa-calendar-day me-1"></i>
                        <span>Generate Daily Payrolls</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; font-size: 1.5rem; opacity: 0.9; cursor: pointer;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="generateDailyForm" action="{{ route('hr.payroll.generate-daily') }}" method="POST"
                    data-ajax-validate="true">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            This will generate payroll for all active daily-wage employees for the selected date.
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label"><i class="fa fa-calendar-day"></i> Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer-modern">
                        <button type="button" class="btn btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                            <i class="fa fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-save"
                            style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fa fa-check"></i>
                            <span>Generate All</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Generate Monthly Payrolls Modal -->
    <div class="modal fade" id="generateMonthlyModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header gradient text-white d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(135deg, #3b82f6, #2563eb) !important;">
                    <h5 class="modal-title text-white mb-0">
                        <i class="fa fa-calendar-alt me-1"></i>
                        <span>Generate Monthly Payrolls</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; font-size: 1.5rem; opacity: 0.9; cursor: pointer;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="generateMonthlyForm" action="{{ route('hr.payroll.generate-monthly') }}" method="POST"
                    data-ajax-validate="true">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            This will generate monthly payroll for all active salaried employees for the selected month.
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label"><i class="fa fa-calendar"></i> Month</label>
                            <input type="month" name="month" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer-modern">
                        <button type="button" class="btn btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                            <i class="fa fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-save"
                            style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                            <i class="fa fa-check"></i>
                            <span>Generate All</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header gradient text-white d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(135deg, #6366f1, #4f46e5) !important;">
                    <h5 class="modal-title text-white mb-0">
                        <i class="fa fa-file-invoice me-1"></i>
                        <span>Payroll Details</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; font-size: 1.5rem; opacity: 0.9; cursor: pointer;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="detailsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Payroll Modal -->
    <div class="modal fade" id="editPayrollModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header gradient text-white d-flex justify-content-between align-items-center"
                    style="background: linear-gradient(135deg, #f59e0b, #d97706) !important;">
                    <h5 class="modal-title text-white mb-0">
                        <i class="fa fa-edit me-1"></i>
                        <span>Edit Payroll</span>
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"
                        style="background: none; border: none; font-size: 1.5rem; opacity: 0.9; cursor: pointer;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editPayrollForm" method="POST" data-ajax-validate="true">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group-modern">
                            <label class="form-label"><i class="fa fa-plus-circle"></i> Manual Allowances</label>
                            <input type="number" name="manual_allowances" class="form-control" step="0.01"
                                min="0" value="0">
                            <small class="text-muted">Additional allowances not in salary structure</small>
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label"><i class="fa fa-minus-circle"></i> Manual Deductions</label>
                            <input type="number" name="manual_deductions" class="form-control" step="0.01"
                                min="0" value="0">
                            <small class="text-muted">Additional deductions not in salary structure</small>
                        </div>
                        <div class="form-group-modern">
                            <label class="form-label"><i class="fa fa-sticky-note"></i> Notes</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Add notes or comments..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer-modern">
                        <button type="button" class="btn btn-cancel" data-dismiss="modal" data-bs-dismiss="modal">
                            <i class="fa fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-save"
                            style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fa fa-save"></i>
                            <span>Save Changes</span>
                        </button>
                    </div>
                </form>
    <!-- Pay Payroll Modal -->
    <div class="modal fade" id="payPayrollModal" tabindex="-1" role="dialog" aria-labelledby="payPayrollModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <h5 class="modal-title font-weight-bold" id="payPayrollModalLabel">
                        <i class="fa fa-hand-holding-usd me-2"></i> Complete Salary Payment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="payPayrollForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="payPayrollId" name="payroll_id">
                    <div class="modal-body p-4">
                        <!-- Employee & Breakdown Slip -->
                        <div id="payBreakdownContent">
                            <div class="text-center py-4">
                                <i class="fa fa-spinner fa-spin fa-2x text-success mb-2"></i>
                                <div class="text-muted fw-semibold">Loading salary details...</div>
                            </div>
                        </div>

                        <!-- Payment Source Account & Method Options -->
                        <div class="card border-0 bg-light rounded-3 p-3 mt-3">
                            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2">
                                <i class="fa fa-university text-primary me-2"></i> Payment Source & Method Details
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold small">Paying Account (Bank / Cash)</label>
                                    <select name="account_id" class="form-select shadow-sm">
                                        <option value="">-- Select Payment Account (Optional) --</option>
                                        @if(isset($accounts) && count($accounts) > 0)
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}">
                                                    {{ $acc->title }} {{ isset($acc->current_balance) ? '- Rs. '.number_format($acc->current_balance, 0) : '' }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-dark fw-bold small">Payment Date</label>
                                    <input type="date" name="payment_date" class="form-control shadow-sm" value="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-dark fw-bold small">Payment Notes / Remarks (Optional)</label>
                                    <input type="text" name="notes" class="form-control shadow-sm" placeholder="Add optional payment remarks...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3 border-top d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal" data-dismiss="modal">
                            <i class="fa fa-times me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success px-4 py-2 fw-bold text-white shadow-sm" id="paySubmitBtn">
                            <i class="fa fa-check-circle me-1"></i> Confirm & Pay Salary
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Tab switching
            $('.payroll-tab').click(function() {
                $(this).addClass('active').siblings().removeClass('active');
                var tab = $(this).data('tab');

                $('.payroll-row, .payroll-card').each(function() {
                    if (tab === 'all') {
                        $(this).show();
                    } else {
                        $(this).toggle($(this).data('type') === tab);
                    }
                });
                updateCount();
            });

            // Status filter
            $('[data-status]').click(function() {
                $(this).addClass('active').siblings().removeClass('active');
                var status = $(this).data('status');

                $('.payroll-row, .payroll-card').each(function() {
                    if (status === 'all') {
                        $(this).show();
                    } else {
                        $(this).toggle($(this).data('status') === status);
                    }
                });
                updateCount();
            });

            // Search
            $('#payrollSearch').on('input', function() {
                var q = $(this).val().toLowerCase();
                $('.payroll-row, .payroll-card').each(function() {
                    var name = $(this).data('name') || '';
                    $(this).toggle(name.indexOf(q) !== -1);
                });
                updateCount();
            });

            function updateCount() {
                $('#payrollCount').text($('.payroll-row:visible, .payroll-card:visible').length + ' payrolls');
            }

            // Generate payroll modal
            $('#generateBtn').click(function() {
                $('#generatePayrollForm')[0].reset();
                safeShowModal('#generatePayrollModal');
            });

            // Generate monthly modal
            $('#generateMonthlyBtn').click(function() {
                $('#generateMonthlyForm')[0].reset();
                safeShowModal('#generateMonthlyModal');
            });

            // Generate daily modal
            $('#generateDailyBtn').click(function() {
                $('#generateDailyForm')[0].reset();
                safeShowModal('#generateDailyModal');
            });

            // Payroll type change
            $('#payrollTypeSelect').change(function() {
                if ($(this).val() === 'daily') {
                    $('#monthField').hide().find('input').prop('required', false);
                    $('#dateField').show().find('input').prop('required', true);
                } else {
                    $('#monthField').show().find('input').prop('required', true);
                    $('#dateField').hide().find('input').prop('required', false);
                }
            });

            // View details
            $(document).on('click', '.view-details-btn', function() {
                var id = $(this).data('id');
                safeShowModal('#detailsModal');

                $.ajax({
                    url: '/hr/payroll/' + id + '/details',
                    type: 'GET',
                    success: function(response) {
                        renderDetails(response);
                    },
                    error: function() {
                        $('#detailsContent').html(
                            '<div class="alert alert-danger">Failed to load details.</div>');
                    }
                });
            });

            function formatCurrency(amount) {
                var num = parseFloat(amount) || 0;
                return num.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
            }

            function formatMinsToHours(minutes) {
                var mins = parseInt(minutes) || 0;
                if (mins <= 0) return '0m';
                if (mins < 60) return mins + 'm';
                var hrs = Math.floor(mins / 60);
                var rem = mins % 60;
                if (rem > 0) {
                    return hrs + 'h ' + rem + 'm';
                } else {
                    return hrs + 'h';
                }
            }

            function renderDetails(data) {
                // Header with Period & Employee
                var headerHtml = `
                    <div class="d-flex align-items-center justify-content-between mb-3 p-3 bg-light rounded-3 border">
                        <div class="d-flex align-items-center gap-3">
                            <div style="background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem;">
                                ${data.payroll.employee.first_name.charAt(0)}${data.payroll.employee.last_name.charAt(0)}
                            </div>
                            <div>
                                <h6 style="margin:0; font-weight:700;" class="text-dark">${data.payroll.employee.first_name} ${data.payroll.employee.last_name}</h6>
                                <div class="text-muted small">${data.payroll.employee.designation ? data.payroll.employee.designation.name : 'N/A'}</div>
                            </div>
                        </div>
                        <div class="text-end d-flex gap-2 align-items-center">
                             <div class="badge bg-white text-dark border px-3 py-2 fw-semibold">
                                <i class="fa fa-tag me-1 text-muted"></i>
                                ${data.payroll.payroll_type.charAt(0).toUpperCase() + data.payroll.payroll_type.slice(1)} Payroll
                            </div>
                             <div class="badge bg-primary px-3 py-2 fw-bold" style="font-size: 0.85rem;">
                                <i class="fa fa-calendar-alt me-1"></i> ${data.payroll_period.formatted}
                            </div>
                        </div>
                    </div>
                `;

                var basicSalary = parseFloat(data.breakdown.earnings.basic_salary) || 0;
                var allowances = (parseFloat(data.breakdown.earnings.allowances) || 0) + (parseFloat(data.breakdown.earnings.manual_allowances) || 0);
                var overtime = parseFloat(data.attendance_breakdown.overtime_earnings) || 0;
                var grossEarnings = basicSalary + allowances + overtime;

                var lateDeduction = parseFloat(data.attendance_breakdown.deduction_details?.late_deduction || data.attendance_breakdown.late_deduction) || 0;
                var absentDeduction = parseFloat(data.attendance_breakdown.deduction_details?.absence_deduction || data.attendance_breakdown.absence_deduction) || 0;
                var loanDeduction = parseFloat(data.breakdown.deductions?.loan_deduction || data.payroll?.loan_deduction) || 0;
                var otherDeductions = (parseFloat(data.breakdown.deductions.fixed_deductions) || 0) + (parseFloat(data.breakdown.deductions.manual_deductions) || 0) + (parseFloat(data.breakdown.deductions.carried_forward) || 0);
                var totalDeduction = parseFloat(data.breakdown.deductions.total) || (lateDeduction + absentDeduction + loanDeduction + otherDeductions);
                var netSalary = parseFloat(data.breakdown.net_payable) || (grossEarnings - totalDeduction);

                var slipCardHtml = `
                    <div class="card border shadow-sm rounded-3 mb-3 bg-white overflow-hidden">
                        <div class="card-header bg-light py-2 px-3 fw-bold text-dark d-flex justify-content-between align-items-center border-bottom">
                            <span><i class="fa fa-file-invoice-dollar text-primary me-2"></i> Pay Salary Breakdown Slip</span>
                            <span class="badge ${data.payroll.status === 'paid' ? 'bg-success' : 'bg-warning text-dark'} px-2 py-1">${data.payroll.status.toUpperCase()}</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-4">
                                <!-- Gross Earnings Column -->
                                <div class="col-md-6 border-end">
                                    <div class="text-success fw-bold border-bottom pb-1 mb-2 small text-uppercase d-flex justify-content-between">
                                        <span><i class="fa fa-plus-circle me-1"></i> Earnings</span>
                                        <span>Amount (Rs.)</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-dashed">
                                        <span class="text-secondary">Basic Salary</span>
                                        <span class="fw-semibold text-dark">${formatCurrency(basicSalary)}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-dashed">
                                        <span class="text-secondary">Allowances</span>
                                        <span class="fw-semibold text-dark">${formatCurrency(allowances)}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-dashed">
                                        <span class="text-secondary">Overtime</span>
                                        <span class="fw-semibold text-dark">${formatCurrency(overtime)}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-2 mt-3 bg-light px-3 rounded fw-bold text-success" style="font-size: 1.05rem; border: 1px solid #bbf7d0;">
                                        <span>Gross Earnings</span>
                                        <span>${formatCurrency(grossEarnings)}</span>
                                    </div>
                                </div>

                                <!-- Total Deductions Column -->
                                <div class="col-md-6">
                                    <div class="text-danger fw-bold border-bottom pb-1 mb-2 small text-uppercase d-flex justify-content-between">
                                        <span><i class="fa fa-minus-circle me-1"></i> Deductions</span>
                                        <span>Amount (Rs.)</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-dashed">
                                        <span class="text-secondary">Late Deduction</span>
                                        <span class="fw-semibold text-danger">${formatCurrency(lateDeduction)}</span>
                                    </div>
                                    <div class="d-flex justify-content-between py-1 border-bottom border-dashed">
                                        <span class="text-secondary">Absent Deduction</span>
                                        <span class="fw-semibold text-danger">${formatCurrency(absentDeduction)}</span>
                                    </div>
                                    ${loanDeduction > 0 ? `
                                        <div class="d-flex justify-content-between py-1 border-bottom border-dashed">
                                            <span class="text-secondary">Loan Deduction</span>
                                            <span class="fw-semibold text-danger">${formatCurrency(loanDeduction)}</span>
                                        </div>
                                    ` : ''}
                                    ${otherDeductions > 0 ? `
                                        <div class="d-flex justify-content-between py-1 border-bottom border-dashed">
                                            <span class="text-secondary">Other Deductions</span>
                                            <span class="fw-semibold text-danger">${formatCurrency(otherDeductions)}</span>
                                        </div>
                                    ` : ''}
                                    <div class="d-flex justify-content-between py-2 mt-3 bg-light px-3 rounded fw-bold text-danger" style="font-size: 1.05rem; border: 1px solid #fecaca;">
                                        <span>Total Deduction</span>
                                        <span>${formatCurrency(totalDeduction)}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- NET SALARY Banner -->
                            <div class="net-payable py-3 px-4 mt-3 d-flex align-items-center justify-content-between" style="border-radius: 12px; background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);">
                                <div>
                                    <div class="label text-white-50 mb-0 small fw-bold" style="letter-spacing: 1px;">NET SALARY</div>
                                    <div class="small text-white-50">${data.payroll.status.toUpperCase()}</div>
                                </div>
                                <div class="amount mb-0" style="font-size: 2.2rem; font-weight: 800; color: white;">Rs. ${formatCurrency(netSalary)}</div>
                            </div>
                        </div>
                    </div>
                `;

                var attendanceOverviewHtml = '';
                if (data.payroll.payroll_type === 'monthly') {
                    attendanceOverviewHtml = `
                        <div class="card border-0 shadow-sm rounded-3 mb-3 p-3" style="background: #f8fafc; border: 1px solid #cbd5e1 !important;">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-2 pb-2 border-bottom">
                                <div>
                                    <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 0.95rem;">
                                        <i class="fa fa-calendar-check text-primary me-2"></i>
                                        Monthly Attendance Summary (${data.attendance_breakdown.month_start_formatted || '01'} to ${data.attendance_breakdown.month_end_formatted || '30'})
                                    </h6>
                                    <small class="text-muted">Working Days: <b>${data.attendance_breakdown.total_working_days || 0}</b></small>
                                </div>
                                <div>
                                    <a href="{{ route('hr.attendance.ledger') }}?employee_id=${data.payroll.employee_id}&month=${data.payroll.month}" target="_blank" class="btn btn-sm btn-dark font-weight-bold px-3 shadow-sm">
                                        <i class="fa fa-book-open me-1"></i> Movement Ledger <i class="fa fa-external-link-alt ms-1" style="font-size: 0.7rem;"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="row g-2 text-center mt-1">
                                <div class="col-4 col-md-2">
                                    <div class="p-2 rounded bg-white border border-success">
                                        <div class="small text-success font-weight-bold" style="font-size: 0.68rem;">PRESENT</div>
                                        <div class="font-weight-bold text-success" style="font-size: 1.15rem;">${data.attendance_breakdown.days_present || 0}</div>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="p-2 rounded bg-white border border-danger">
                                        <div class="small text-danger font-weight-bold" style="font-size: 0.68rem;">ABSENT</div>
                                        <div class="font-weight-bold text-danger" style="font-size: 1.15rem;">${data.attendance_breakdown.days_absent || 0}</div>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="p-2 rounded bg-white border border-warning">
                                        <div class="small text-warning font-weight-bold" style="font-size: 0.68rem;">LATE</div>
                                        <div class="font-weight-bold text-warning" style="font-size: 1.15rem;">${data.attendance_breakdown.late_check_ins || 0}</div>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="p-2 rounded bg-white border border-info">
                                        <div class="small text-info font-weight-bold" style="font-size: 0.68rem;">LEAVE</div>
                                        <div class="font-weight-bold text-info" style="font-size: 1.15rem;">${data.attendance_breakdown.days_leave || 0}</div>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="p-2 rounded bg-white border">
                                        <div class="small text-muted font-weight-bold" style="font-size: 0.68rem;">OT HOURS</div>
                                        <div class="font-weight-bold text-info" style="font-size: 1.15rem;">${data.attendance_breakdown.overtime_hours || 0}</div>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <div class="p-2 rounded bg-white border border-danger">
                                        <div class="small text-danger font-weight-bold" style="font-size: 0.68rem;">ATT. DEDUCT</div>
                                        <div class="font-weight-bold text-danger" style="font-size: 1.05rem;">Rs. ${formatCurrency(data.breakdown.deductions.attendance_deductions)}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                var html = `
                    ${headerHtml}
                    ${slipCardHtml}
                    ${attendanceOverviewHtml}
                `;

                if (data.payroll.notes) {
                    html += `
                        <div class="alert alert-warning py-2 fs-7 small d-flex align-items-center mt-2">
                            <i class="fa fa-sticky-note me-2 text-warning"></i> 
                            <span class="fst-italic text-truncate">${data.payroll.notes}</span>
                        </div>
                    `;
                }

                $('#detailsContent').html(html);
            }

            // Toggle expandable sections
            window.toggleExpandable = function(header) {
                const content = $(header).next('.expandable-content');
                const isActive = $(header).hasClass('active');

                if (isActive) {
                    $(header).removeClass('active');
                    content.removeClass('active');
                } else {
                    $(header).addClass('active');
                    content.addClass('active');
                }
            };

            // Edit payroll
            $(document).on('click', '.edit-payroll-btn', function() {
                var id = $(this).data('id');
                $('#editPayrollForm').attr('action', '/hr/payroll/' + id);
                $('#editPayrollModal').modal('show');
            });

            // Mark reviewed
            $(document).on('click', '.mark-reviewed-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Mark as Reviewed?',
                    text: 'This will update the payroll status to reviewed.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Yes, Mark Reviewed'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/hr/payroll/' + id + '/mark-reviewed',
                            type: 'PATCH',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Success', response.success, 'success')
                                        .then(() => location.reload());
                                }
                            }
                        });
                    }
                });
            });

            // Helper for Bootstrap 4/5 modal safety
            function safeShowModal(selector) {
                var el = document.querySelector(selector);
                if (!el) return;
                
                // Move modal element to body to prevent dark backdrop trapping bug
                if (el.parentNode !== document.body) {
                    document.body.appendChild(el);
                }

                try {
                    if (window.bootstrap && bootstrap.Modal) {
                        var inst = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
                        inst.show();
                        return;
                    }
                } catch(e) {}

                if (typeof $(selector).modal === 'function') {
                    $(selector).modal('show');
                } else {
                    $(selector).addClass('show').css({ 'display': 'block', 'z-index': 1055 });
                    $('body').addClass('modal-open');
                }
            }

            function safeHideModal(selector) {
                var el = document.querySelector(selector);
                if (!el) return;
                try {
                    if (window.bootstrap && bootstrap.Modal) {
                        var inst = bootstrap.Modal.getInstance(el);
                        if (inst) { inst.hide(); }
                    }
                } catch(e) {}
                if (typeof $(selector).modal === 'function') {
                    $(selector).modal('hide');
                }
                $(selector).removeClass('show').css('display', 'none');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
            }

            // Mark paid modal handler
            $(document).on('click', '.mark-paid-btn', function() {
                var id = $(this).data('id');
                $('#payPayrollId').val(id);
                $('#payPayrollForm').attr('action', '/hr/payroll/' + id + '/mark-paid');
                $('#payBreakdownContent').html(`
                    <div class="text-center py-4">
                        <i class="fa fa-spinner fa-spin fa-2x text-success mb-2"></i>
                        <div class="text-muted fw-semibold">Loading salary details...</div>
                    </div>
                `);
                safeShowModal('#payPayrollModal');

                $.ajax({
                    url: '/hr/payroll/' + id + '/details',
                    type: 'GET',
                    success: function(response) {
                        var emp = (response.payroll && response.payroll.employee) ? response.payroll.employee : {};
                        var fName = emp.first_name || '';
                        var lName = emp.last_name || '';
                        var initials = ((fName ? fName.charAt(0) : 'E') + (lName ? lName.charAt(0) : '')).toUpperCase();
                        var fullName = (fName + ' ' + lName).trim() || 'Employee';
                        var designationName = (emp.designation && emp.designation.name) ? emp.designation.name : 'N/A';
                        var periodFormatted = (response.payroll_period && response.payroll_period.formatted) ? response.payroll_period.formatted : '';

                        var header = `
                            <div class="d-flex align-items-center justify-content-between mb-3 p-3 bg-white rounded-3 border shadow-sm">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="background: linear-gradient(135deg, #10b981, #059669); color: white; width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem;">
                                        ${initials}
                                    </div>
                                    <div>
                                        <h6 style="margin:0; font-weight:700;" class="text-dark">${fullName}</h6>
                                        <div class="text-muted small">${designationName}</div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="badge bg-light text-dark border px-3 py-2 fw-semibold">
                                        Period: ${periodFormatted}
                                    </div>
                                </div>
                            </div>
                        `;

                        var basicSalary = parseFloat(response.breakdown?.earnings?.basic_salary) || 0;
                        var allowances = (parseFloat(response.breakdown?.earnings?.allowances) || 0) + (parseFloat(response.breakdown?.earnings?.manual_allowances) || 0);
                        var overtime = parseFloat(response.attendance_breakdown?.overtime_earnings) || 0;
                        var grossEarnings = basicSalary + allowances + overtime;

                        var lateDeduction = parseFloat(response.attendance_breakdown?.deduction_details?.late_deduction || response.attendance_breakdown?.late_deduction) || 0;
                        var absentDeduction = parseFloat(response.attendance_breakdown?.deduction_details?.absence_deduction || response.attendance_breakdown?.absence_deduction) || 0;
                        var otherDeductions = (parseFloat(response.breakdown?.deductions?.fixed_deductions) || 0) + (parseFloat(response.breakdown?.deductions?.manual_deductions) || 0) + (parseFloat(response.breakdown?.deductions?.carried_forward) || 0);

                        var loanSummary = response.loan_summary || {};
                        var hasActiveLoan = loanSummary.has_active_loan || false;
                        var totalLoan = parseFloat(loanSummary.total_loan_amount) || 0;
                        var paidLoan = parseFloat(loanSummary.total_paid_amount) || 0;
                        var remainingLoan = parseFloat(loanSummary.total_remaining) || 0;
                        var suggestedInstallment = parseFloat(loanSummary.suggested_installment) || 0;

                        var defaultLoanCut = 0;
                        if (parseFloat(response.payroll?.loan_deduction) > 0) {
                            defaultLoanCut = parseFloat(response.payroll.loan_deduction);
                        } else if (hasActiveLoan) {
                            if (suggestedInstallment > 0) {
                                defaultLoanCut = Math.min(suggestedInstallment, remainingLoan);
                            } else {
                                defaultLoanCut = remainingLoan;
                            }
                        }

                        var totalDeduction = lateDeduction + absentDeduction + otherDeductions + defaultLoanCut;
                        var netSalary = Math.max(0, grossEarnings - totalDeduction);

                        var loanAlertHtml = '';
                        if (hasActiveLoan || totalLoan > 0) {
                            loanAlertHtml = `
                                <div class="p-2 px-3 mb-3 rounded-3 border d-flex align-items-center justify-content-between" style="background-color: #fffbeb; border-color: #fef3c7 !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-hand-holding-usd text-warning fs-5"></i>
                                        <div class="small text-dark">
                                            <strong>Active Employee Loan:</strong> Total: <b>Rs. ${formatCurrency(totalLoan)}</b> | Paid: <b class="text-success">Rs. ${formatCurrency(paidLoan)}</b> | Remaining Balance: <b class="text-danger">Rs. ${formatCurrency(remainingLoan)}</b>
                                        </div>
                                    </div>
                                    ${suggestedInstallment > 0 ? `<span class="badge bg-warning text-dark border px-2 py-1">Monthly Cut: Rs. ${formatCurrency(suggestedInstallment)}</span>` : ''}
                                </div>
                            `;
                        }

                        var breakdownHtml = `
                            ${header}
                            ${loanAlertHtml}
                            <input type="hidden" id="grossEarningsHidden" value="${grossEarnings}">
                            <input type="hidden" id="attendanceDeductionsHidden" name="attendance_deductions" value="${lateDeduction + absentDeduction}">
                            <input type="hidden" id="manualDeductionsHidden" name="manual_deductions" value="${otherDeductions}">
                            <input type="hidden" id="loanDeductionHidden" name="loan_deduction" value="${defaultLoanCut}">
                            <input type="hidden" id="netSalaryHidden" name="net_salary" value="${netSalary}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded-3 border h-100">
                                        <div class="text-success fw-bold border-bottom pb-2 mb-2 small text-uppercase d-flex justify-content-between align-items-center">
                                            <span>Earnings</span><span>Amount</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 small">
                                            <span class="text-muted">Basic Salary</span>
                                            <span class="fw-semibold">Rs. ${formatCurrency(basicSalary)}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 small">
                                            <span class="text-muted">Allowances</span>
                                            <span class="fw-semibold">Rs. ${formatCurrency(allowances)}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 small">
                                            <span class="text-muted">Overtime</span>
                                            <span class="fw-semibold">Rs. ${formatCurrency(overtime)}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-2 mt-2 border-top fw-bold text-success">
                                            <span>Gross Earnings</span>
                                            <span>Rs. ${formatCurrency(grossEarnings)}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded-3 border h-100">
                                        <div class="text-danger fw-bold border-bottom pb-2 mb-2 small text-uppercase d-flex justify-content-between align-items-center">
                                            <span>Deductions</span>
                                            <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.7rem;">Editable (Rs.)</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-1 small mb-1">
                                            <span class="text-muted">Late Deduction</span>
                                            <div class="input-group input-group-sm" style="width: 125px;">
                                                <span class="input-group-text bg-light text-muted px-2">Rs.</span>
                                                <input type="number" step="1" min="0" name="late_deduction_input" class="form-control text-danger fw-bold text-end deduction-calc-input px-2 shadow-none" value="${Math.round(lateDeduction)}">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-1 small mb-1">
                                            <span class="text-muted">Absent Deduction</span>
                                            <div class="input-group input-group-sm" style="width: 125px;">
                                                <span class="input-group-text bg-light text-muted px-2">Rs.</span>
                                                <input type="number" step="1" min="0" name="absent_deduction_input" class="form-control text-danger fw-bold text-end deduction-calc-input px-2 shadow-none" value="${Math.round(absentDeduction)}">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-1 small mb-1">
                                            <span class="text-muted d-flex align-items-center">
                                                Loan Deduction
                                                ${hasActiveLoan ? `<span class="badge bg-warning text-dark ms-1" style="font-size:0.6rem;">Active</span>` : ''}
                                            </span>
                                            <div class="input-group input-group-sm" style="width: 125px;">
                                                <span class="input-group-text bg-light text-muted px-2">Rs.</span>
                                                <input type="number" step="1" min="0" ${remainingLoan > 0 ? `max="${Math.round(remainingLoan)}"` : ''} name="loan_deduction_input" class="form-control text-danger fw-bold text-end deduction-calc-input px-2 shadow-none" value="${Math.round(defaultLoanCut)}">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-1 small mb-1">
                                            <span class="text-muted">Other Deduction</span>
                                            <div class="input-group input-group-sm" style="width: 125px;">
                                                <span class="input-group-text bg-light text-muted px-2">Rs.</span>
                                                <input type="number" step="1" min="0" name="manual_deductions_input" class="form-control text-danger fw-bold text-end deduction-calc-input px-2 shadow-none" value="${Math.round(otherDeductions)}">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center py-2 mt-2 border-top fw-bold text-danger">
                                            <span>Total Deductions</span>
                                            <span id="totalDeductionSpan">Rs. ${formatCurrency(totalDeduction)}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="net-payable py-2 px-3 mt-3 d-flex align-items-center justify-content-between rounded-3" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                                <div>
                                    <div class="small text-white-50 fw-bold" style="letter-spacing: 1px;">TOTAL NET PAYABLE</div>
                                    <div class="small text-white-50">${fullName}'s Salary</div>
                                </div>
                                <div id="netSalaryDisplay" style="font-size: 1.6rem; font-weight: 800; color: white;">Rs. ${formatCurrency(netSalary)}</div>
                            </div>
                        `;

                        $('#payBreakdownContent').html(breakdownHtml);
                    },
                    error: function() {
                        $('#payBreakdownContent').html('<div class="alert alert-warning p-3 mb-0">Salary breakdown could not be loaded, but you can still proceed with payment below.</div>');
                    }
                });
            });

            // Live deduction recalculation listener inside payment modal
            $(document).on('input change', '.deduction-calc-input', function() {
                var late = parseFloat($('input[name="late_deduction_input"]').val()) || 0;
                var absent = parseFloat($('input[name="absent_deduction_input"]').val()) || 0;
                var loan = parseFloat($('input[name="loan_deduction_input"]').val()) || 0;
                var manual = parseFloat($('input[name="manual_deductions_input"]').val()) || 0;

                var totalAttendance = late + absent;
                var totalDeduction = totalAttendance + manual + loan;

                $('#attendanceDeductionsHidden').val(totalAttendance);
                $('#manualDeductionsHidden').val(manual);
                $('#loanDeductionHidden').val(loan);

                $('#totalDeductionSpan').text('Rs. ' + formatCurrency(totalDeduction));

                var gross = parseFloat($('#grossEarningsHidden').val()) || 0;
                var net = Math.max(0, gross - totalDeduction);

                $('#netSalaryHidden').val(net);
                $('#netSalaryDisplay').text('Rs. ' + formatCurrency(net));
            });

            // Form Submit for payPayrollForm
            $(document).on('submit', '#payPayrollForm', function(e) {
                e.preventDefault();
                let form = $(this);
                let btn = $('#paySubmitBtn');
                let originalContent = btn.html();

                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Processing Payment...');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(res) {
                        safeHideModal('#payPayrollModal');
                        Swal.fire({
                            title: 'Paid!',
                            text: res.success || 'Payroll marked as paid.',
                            icon: 'success',
                            confirmButtonColor: '#10b981'
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(originalContent);
                        let err = xhr.responseJSON?.error || 'Failed to process payment.';
                        if (xhr.responseJSON?.errors) {
                            err = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        Swal.fire('Error', err, 'error');
                    }
                });
            });

            // Delete payroll
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Delete Payroll?',
                    text: 'This cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Yes, delete!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/hr/payroll/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Deleted!', response.success, 'success')
                                        .then(() => location.reload());
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush

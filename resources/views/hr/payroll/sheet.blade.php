@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    @php
        $formattedMonth = (!empty($selectedMonth) && $selectedMonth !== 'all')
            ? \Carbon\Carbon::parse($selectedMonth . '-01')->format('F Y')
            : 'All Periods (Summary)';

        $totalEmployees = $payrolls->count();
        $totalBasic = 0;
        $totalOtHours = 0;
        $totalOtAmount = 0;
        $totalAllowances = 0;
        $totalGross = 0;
        $totalDeductions = 0;
        $totalNetSalary = 0;
        $totalPaidAmount = 0;
        $totalPendingAmount = 0;

        foreach ($payrolls as $p) {
            $basic = floatval($p->basic_salary);
            $otHrs = floatval($p->ot_hours ?? 0);
            $otAmt = floatval($p->ot_amount ?? 0);
            $allow = floatval($p->allowances) + floatval($p->manual_allowances ?? 0);
            $gross = floatval($p->calculated_gross ?? ($basic + $allow + $otAmt));
            $ded = floatval($p->calculated_total_deductions ?? $p->total_deductions ?? 0);
            $net = floatval($p->calculated_net_salary ?? $p->net_salary ?? 0);
            $paid = ($p->status === 'paid') ? $net : 0;
            $pending = ($p->status !== 'paid') ? $net : 0;

            $totalBasic += $basic;
            $totalOtHours += $otHrs;
            $totalOtAmount += $otAmt;
            $totalAllowances += $allow;
            $totalGross += $gross;
            $totalDeductions += $ded;
            $totalNetSalary += $net;
            $totalPaidAmount += $paid;
            $totalPendingAmount += $pending;
        }
    @endphp

    <style>
        .payroll-sheet-wrapper {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            padding: 24px;
            margin-bottom: 30px;
        }

        .sheet-company-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .sheet-sub-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .sheet-meta-text {
            font-size: 0.82rem;
            color: #64748b;
        }

        .kpi-summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            text-align: center;
        }

        .kpi-summary-box .kpi-label {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .kpi-summary-box .kpi-val {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }

        .salary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            margin-top: 15px;
        }

        .salary-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.73rem;
            letter-spacing: 0.4px;
            padding: 10px 8px;
            border: 1px solid #1e293b;
            vertical-align: middle;
            text-align: center;
            white-space: nowrap;
        }

        .salary-table td {
            padding: 8px 7px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
            color: #1e293b;
            font-size: 0.81rem;
        }

        .salary-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .salary-table tbody tr:hover {
            background-color: #eff6ff;
        }

        .salary-table tfoot th,
        .salary-table tfoot td {
            background-color: #e2e8f0;
            color: #0f172a;
            font-weight: 800;
            border: 1px solid #94a3b8;
            padding: 10px 7px;
            font-size: 0.82rem;
        }

        .badge-status-paid {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
            white-space: nowrap;
        }

        .badge-status-pending {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fcd34d;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
            white-space: nowrap;
        }

        .sig-block {
            text-align: center;
            padding-top: 50px;
        }

        .sig-line {
            border-top: 1.5px solid #0f172a;
            width: 80%;
            margin: 0 auto 6px auto;
        }

        .sig-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: #334155;
            text-transform: uppercase;
        }

        /* PRINT STYLES */
        @media print {
            @page {
                size: landscape;
                margin: 6mm 6mm 6mm 6mm;
            }

            body,
            .main-content,
            .main-content-inner,
            .container-fluid {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                box-shadow: none !important;
            }

            .no-print,
            header,
            .sidebar-menu,
            .sidebar-wrapper,
            .page-container .sidebar,
            .header-area,
            .page-title-area,
            nav.navbar,
            footer,
            .whatsapp-support-btn,
            .whatsapp-floating-btn {
                display: none !important;
            }

            .page-container {
                padding-left: 0 !important;
            }

            .payroll-sheet-wrapper {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .salary-table {
                font-size: 8.5pt !important;
                width: 100% !important;
            }

            .salary-table th {
                background-color: #f1f5f9 !important;
                color: #000000 !important;
                border: 1px solid #000000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                font-size: 7.5pt !important;
                padding: 4px 3px !important;
            }

            .salary-table td {
                border: 1px solid #000000 !important;
                padding: 4px 3px !important;
                font-size: 8pt !important;
            }

            .salary-table tfoot th,
            .salary-table tfoot td {
                background-color: #f8fafc !important;
                color: #000000 !important;
                border: 1.5px solid #000000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .kpi-summary-box {
                border: 1px solid #94a3b8 !important;
                padding: 6px 8px !important;
            }

            .kpi-summary-box .kpi-label {
                font-size: 6.5pt !important;
            }

            .kpi-summary-box .kpi-val {
                font-size: 9.5pt !important;
            }

            .sig-block {
                padding-top: 35px !important;
                page-break-inside: avoid;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner pt-3">
            <div class="container-fluid px-2 px-md-4">

                {{-- Action / Navigation Bar (Hidden on print) --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 no-print">
                    <div>
                        <h2 class="font-weight-bold text-dark mb-1">
                            <i class="fa fa-file-invoice-dollar text-primary me-2"></i> Salary & Payroll Sheet
                        </h2>
                        <p class="text-muted small mb-0">Row-wise printable payroll register with attendance, overtime, deductions, and payment status</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-primary fw-bold shadow-sm" onclick="window.print();" style="border-radius: 10px; padding: 9px 18px;">
                            <i class="fa fa-print me-1"></i> Print Salary Sheet
                        </button>
                        <a href="{{ route('hr.payroll.index', ['month' => $selectedMonth]) }}" class="btn btn-outline-secondary fw-bold" style="border-radius: 10px; padding: 9px 16px;">
                            <i class="fa fa-arrow-left me-1"></i> Back to Payroll
                        </a>
                    </div>
                </div>

                {{-- Filter Card (Hidden on print) --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 p-3 bg-white no-print">
                    <form method="GET" action="{{ route('hr.payroll.sheet') }}" id="sheetFilterForm">
                        <div class="row g-3 align-items-end">
                            {{-- Month Filter --}}
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label text-muted small font-weight-bold mb-1">
                                    <i class="fa fa-calendar-alt text-primary me-1"></i> SELECT MONTH
                                </label>
                                <select name="month" class="form-select form-select-sm fw-bold" onchange="this.form.submit()">
                                    @foreach($availableMonths as $m)
                                        <option value="{{ $m }}" {{ ($selectedMonth ?? '') === $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($m . '-01')->format('F Y') }}
                                        </option>
                                    @endforeach
                                    <option value="all" {{ ($selectedMonth ?? '') === 'all' ? 'selected' : '' }}>All Months (History)</option>
                                </select>
                            </div>

                            {{-- Type Filter --}}
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label text-muted small font-weight-bold mb-1">PAYROLL TYPE</label>
                                <select name="type" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="all" {{ ($selectedType ?? 'all') === 'all' ? 'selected' : '' }}>All Types</option>
                                    <option value="monthly" {{ ($selectedType ?? '') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="daily" {{ ($selectedType ?? '') === 'daily' ? 'selected' : '' }}>Daily Wages</option>
                                </select>
                            </div>

                            {{-- Status Filter --}}
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label text-muted small font-weight-bold mb-1">PAYMENT STATUS</label>
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="all" {{ ($selectedStatus ?? 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="paid" {{ ($selectedStatus ?? '') === 'paid' ? 'selected' : '' }}>Paid Only</option>
                                    <option value="generated" {{ ($selectedStatus ?? '') === 'generated' ? 'selected' : '' }}>Pending / Unpaid</option>
                                    <option value="reviewed" {{ ($selectedStatus ?? '') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                </select>
                            </div>

                            {{-- Department Filter --}}
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label text-muted small font-weight-bold mb-1">DEPARTMENT</label>
                                <select name="department_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="all">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ ($selectedDepartment ?? '') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Quick Reset --}}
                            <div class="col-12 col-md-2 d-flex gap-2">
                                <a href="{{ route('hr.payroll.sheet') }}" class="btn btn-outline-danger btn-sm w-100 fw-bold">
                                    <i class="fa fa-undo me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Printable Sheet Wrapper --}}
                <div class="payroll-sheet-wrapper">
                    
                    {{-- Formal Sheet Header --}}
                    <div class="text-center pb-3 border-bottom mb-3">
                        <div class="sheet-company-title">SIDAZ PHARMACEUTICALS</div>
                        <div class="sheet-sub-title">MONTHLY SALARY & PAYROLL REGISTER</div>
                        <div class="sheet-meta-text">
                            <strong>Period:</strong> {{ $formattedMonth }} 
                            &nbsp;|&nbsp; <strong>Printed On:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}
                            &nbsp;|&nbsp; <strong>Total Staff:</strong> {{ $totalEmployees }}
                            @if(!empty($selectedType) && $selectedType !== 'all')
                                &nbsp;|&nbsp; <strong>Type:</strong> {{ ucfirst($selectedType) }}
                            @endif
                        </div>
                    </div>

                    {{-- Summary KPI Boxes (Screen & Print) --}}
                    <div class="row g-2 mb-3">
                        <div class="col-4 col-md-2">
                            <div class="kpi-summary-box">
                                <div class="kpi-label">Basic Salary</div>
                                <div class="kpi-val">Rs. {{ number_format($totalBasic, 0) }}</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="kpi-summary-box">
                                <div class="kpi-label">Overtime ({{ round($totalOtHours, 1) }} hrs)</div>
                                <div class="kpi-val text-info">Rs. {{ number_format($totalOtAmount, 0) }}</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="kpi-summary-box">
                                <div class="kpi-label">Allowances</div>
                                <div class="kpi-val">Rs. {{ number_format($totalAllowances, 0) }}</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="kpi-summary-box">
                                <div class="kpi-label">Deductions</div>
                                <div class="kpi-val text-danger">Rs. {{ number_format($totalDeductions, 0) }}</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="kpi-summary-box" style="border-color: #86efac; background: #f0fdf4;">
                                <div class="kpi-label text-success">Total Paid</div>
                                <div class="kpi-val text-success">Rs. {{ number_format($totalPaidAmount, 0) }}</div>
                            </div>
                        </div>
                        <div class="col-4 col-md-2">
                            <div class="kpi-summary-box" style="border-color: #fde68a; background: #fffbeb;">
                                <div class="kpi-label text-warning">Pending Pay</div>
                                <div class="kpi-val text-warning">Rs. {{ number_format($totalPendingAmount, 0) }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Row-wise Salary Sheet Table --}}
                    <div class="table-responsive">
                        <table class="salary-table">
                            <thead>
                                <tr>
                                    <th style="width: 3%;">#</th>
                                    <th style="width: 7%;">Emp Code</th>
                                    <th style="width: 16%; text-align: left;">Employee Name & Designation</th>
                                    <th style="width: 7%; text-align: right;">Basic (Rs.)</th>
                                    <th style="width: 8%;">Att (P/L/A)</th>
                                    <th style="width: 5%;">OT Hrs</th>
                                    <th style="width: 6%; text-align: right;">OT Amt</th>
                                    <th style="width: 6%; text-align: right;">Allow.</th>
                                    <th style="width: 7%; text-align: right;">Gross (Rs.)</th>
                                    <th style="width: 7%; text-align: right;">Deductions</th>
                                    <th style="width: 8%; text-align: right;">Net Payable</th>
                                    <th style="width: 8%; text-align: right;">Paid Amt</th>
                                    <th style="width: 6%;">Status</th>
                                    <th style="width: 6%;">Signature</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrolls as $index => $p)
                                    @php
                                        $emp = $p->employee;
                                        $empName = $emp ? $emp->full_name : 'N/A';
                                        $empCode = $emp->code ?? ('#' . ($emp->id ?? $p->employee_id));
                                        $desig = $emp->designation->name ?? 'Staff';
                                        $dept = $emp->department->name ?? '';

                                        $basic = floatval($p->basic_salary);
                                        $days = $p->attendance_days ?? 30;
                                        $present = $p->attendance_present ?? 0;
                                        $late = $p->attendance_late ?? 0;
                                        $absent = $p->attendance_absent ?? 0;

                                        $otHrs = floatval($p->ot_hours ?? 0);
                                        $otAmt = floatval($p->ot_amount ?? 0);
                                        $allow = floatval($p->allowances) + floatval($p->manual_allowances ?? 0);
                                        $gross = floatval($p->calculated_gross ?? ($basic + $allow + $otAmt));
                                        $ded = floatval($p->calculated_total_deductions ?? $p->total_deductions ?? 0);
                                        $net = floatval($p->calculated_net_salary ?? $p->net_salary ?? 0);

                                        $isPaid = ($p->status === 'paid');
                                        $paidAmt = $isPaid ? $net : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                        <td class="text-center font-monospace small">{{ $empCode }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $empName }}</div>
                                            <div class="small text-muted" style="font-size: 0.73rem;">
                                                {{ $desig }} @if($dept) - {{ $dept }} @endif
                                            </div>
                                        </td>
                                        <td class="text-end fw-semibold">{{ number_format($basic, 0) }}</td>
                                        <td class="text-center">
                                            <span title="Present" class="text-success fw-bold">{{ $present }}</span> /
                                            <span title="Late" class="text-warning fw-bold">{{ $late }}</span> /
                                            <span title="Absent" class="text-danger fw-bold">{{ $absent }}</span>
                                            <span class="text-muted small">({{ $days }}d)</span>
                                        </td>
                                        <td class="text-center fw-bold text-info">
                                            {{ $otHrs > 0 ? round($otHrs, 1) : '-' }}
                                        </td>
                                        <td class="text-end fw-semibold">
                                            {{ $otAmt > 0 ? number_format($otAmt, 0) : '-' }}
                                        </td>
                                        <td class="text-end">
                                            {{ $allow > 0 ? number_format($allow, 0) : '-' }}
                                        </td>
                                        <td class="text-end fw-semibold">{{ number_format($gross, 0) }}</td>
                                        <td class="text-end text-danger fw-semibold">
                                            {{ $ded > 0 ? number_format($ded, 0) : '-' }}
                                        </td>
                                        <td class="text-end fw-bold text-dark" style="background-color: #f1f5f9;">
                                            {{ number_format($net, 0) }}
                                        </td>
                                        <td class="text-end fw-bold {{ $isPaid ? 'text-success' : 'text-muted' }}">
                                            {{ $isPaid ? number_format($paidAmt, 0) : '0' }}
                                        </td>
                                        <td class="text-center">
                                            @if($isPaid)
                                                <span class="badge-status-paid">PAID</span>
                                                @if($p->payment_date)
                                                    <div class="small text-muted" style="font-size: 0.68rem;">{{ \Carbon\Carbon::parse($p->payment_date)->format('d/m') }}</div>
                                                @endif
                                            @else
                                                <span class="badge-status-pending">PENDING</span>
                                            @endif
                                        </td>
                                        <td class="text-center" style="border-bottom: 1px dotted #94a3b8;">
                                            {{-- Empty line for physical signature on printout --}}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="14" class="text-center py-4 text-muted">
                                            <i class="fa fa-info-circle me-1"></i> No payroll records found for the selected month and filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end text-uppercase">Grand Total ({{ $totalEmployees }} Staff):</th>
                                    <th class="text-end">{{ number_format($totalBasic, 0) }}</th>
                                    <th class="text-center">-</th>
                                    <th class="text-center text-info">{{ round($totalOtHours, 1) }}</th>
                                    <th class="text-end text-info">{{ number_format($totalOtAmount, 0) }}</th>
                                    <th class="text-end">{{ number_format($totalAllowances, 0) }}</th>
                                    <th class="text-end">{{ number_format($totalGross, 0) }}</th>
                                    <th class="text-end text-danger">{{ number_format($totalDeductions, 0) }}</th>
                                    <th class="text-end text-dark">{{ number_format($totalNetSalary, 0) }}</th>
                                    <th class="text-end text-success">{{ number_format($totalPaidAmount, 0) }}</th>
                                    <th class="text-center">
                                        @if($totalPendingAmount > 0)
                                            <span class="small text-danger fw-bold">Due: {{ number_format($totalPendingAmount, 0) }}</span>
                                        @else
                                            <span class="small text-success fw-bold">Cleared</span>
                                        @endif
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Formal Approval / Signature Section --}}
                    <div class="row mt-4 pt-3 sig-block">
                        <div class="col-3">
                            <div class="sig-line"></div>
                            <div class="sig-title">Prepared By (HR)</div>
                        </div>
                        <div class="col-3">
                            <div class="sig-line"></div>
                            <div class="sig-title">Checked By (Accounts)</div>
                        </div>
                        <div class="col-3">
                            <div class="sig-line"></div>
                            <div class="sig-title">Disbursed By (Cash / Bank)</div>
                        </div>
                        <div class="col-3">
                            <div class="sig-line"></div>
                            <div class="sig-title">Approved By (Director / CEO)</div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

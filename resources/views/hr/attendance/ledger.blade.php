@extends('admin_panel.layout.app')

@section('content')
    @include('hr.partials.hr-styles')

    <style>
        .ledger-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }

        .ledger-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 24px 28px;
            color: #ffffff;
        }

        .kpi-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            height: 100%;
        }

        .kpi-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .kpi-value {
            font-size: 1.4rem;
            font-weight: 800;
            line-height: 1.2;
            margin-top: 4px;
        }

        .status-badge-present { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .status-badge-late { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .status-badge-absent { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .status-badge-leave { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }

        @media print {
            .no-print { display: none !important; }
            .ledger-header { background: #ffffff !important; color: #000000 !important; }
            .ledger-card { border: none !important; box-shadow: none !important; }
            body { background: #ffffff !important; }
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner pt-3">
            <div class="container-fluid">
                
                {{-- Header --}}
                <div class="ledger-header rounded-3 mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div>
                        <h2 class="text-white font-weight-bold mb-1"><i class="fa fa-book-open me-2 text-info"></i> Employee Attendance Ledger</h2>
                        <p class="text-white-50 mb-0">Complete movement and check-in / check-out log history of employees</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 no-print">
                        <button type="button" class="btn btn-light text-dark font-weight-bold" onclick="window.print();">
                            <i class="fa fa-print me-1"></i> Print Ledger
                        </button>
                        <a href="{{ route('hr.attendance.index') }}" class="btn btn-info font-weight-bold text-white">
                            <i class="fa fa-clock me-1"></i> Daily Attendance
                        </a>
                    </div>
                </div>

                {{-- Filter Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 p-3 bg-white no-print">
                    <form id="ledgerFilterForm" method="GET" action="{{ route('hr.attendance.ledger') }}">
                        <div class="row g-3 align-items-end">
                            
                            {{-- Quick Month Picker --}}
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label text-muted small font-weight-bold">MONTH (QUICK)</label>
                                <input type="month" name="month" class="form-control form-control-sm font-weight-bold" value="{{ $monthStr ?? '' }}" onchange="document.getElementById('date_from').value=''; document.getElementById('date_to').value=''; this.form.submit();">
                            </div>

                            {{-- Date From --}}
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label text-muted small font-weight-bold">DATE FROM</label>
                                <input type="date" id="date_from" name="date_from" class="form-control form-control-sm" value="{{ $startDate }}">
                            </div>

                            {{-- Date To --}}
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label text-muted small font-weight-bold">DATE TO</label>
                                <input type="date" id="date_to" name="date_to" class="form-control form-control-sm" value="{{ $endDate }}">
                            </div>

                            {{-- Employee Select --}}
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label text-muted small font-weight-bold">EMPLOYEE</label>
                                <select name="employee_id" class="form-select form-select-sm">
                                    <option value="">All Employees</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ $selectedEmployee == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->full_name }} (#{{ $emp->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Department Select --}}
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label text-muted small font-weight-bold">DEPARTMENT</label>
                                <select name="department_id" class="form-select form-select-sm">
                                    <option value="">All Departments</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $selectedDepartment == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Select --}}
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label text-muted small font-weight-bold">STATUS</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">All Statuses</option>
                                    <option value="present" {{ $selectedStatus == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="late" {{ $selectedStatus == 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="absent" {{ $selectedStatus == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="leave" {{ $selectedStatus == 'leave' ? 'selected' : '' }}>Leave</option>
                                </select>
                            </div>

                            {{-- Actions --}}
                            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold"><i class="fa fa-filter me-1"></i> Apply Filters</button>
                                <a href="{{ route('hr.attendance.ledger') }}" class="btn btn-light btn-sm border px-3"><i class="fa fa-sync me-1"></i> Reset</a>
                            </div>

                        </div>
                    </form>
                </div>

                {{-- Summary KPI Cards --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-2">
                        <div class="kpi-box">
                            <div class="kpi-title">TOTAL LOGS</div>
                            <div class="kpi-value text-dark">{{ number_format($summary['total_logs']) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="kpi-box border-success">
                            <div class="kpi-title text-success"><i class="fa fa-check-circle me-1"></i> PRESENT</div>
                            <div class="kpi-value text-success">{{ number_format($summary['present']) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="kpi-box border-warning">
                            <div class="kpi-title text-warning"><i class="fa fa-exclamation-triangle me-1"></i> LATE</div>
                            <div class="kpi-value text-warning">{{ number_format($summary['late']) }}</div>
                            <div class="small text-muted" style="font-size: 0.72rem;">{{ number_format($summary['total_late_mins']) }} mins</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="kpi-box border-danger">
                            <div class="kpi-title text-danger"><i class="fa fa-times-circle me-1"></i> ABSENT</div>
                            <div class="kpi-value text-danger">{{ number_format($summary['absent']) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="kpi-box border-info">
                            <div class="kpi-title text-info"><i class="fa fa-umbrella-beach me-1"></i> LEAVE</div>
                            <div class="kpi-value text-info">{{ number_format($summary['leave']) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="kpi-box" style="border-color: #7c3aed;">
                            <div class="kpi-title" style="color: #7c3aed;"><i class="fa fa-clock me-1"></i> TOTAL HOURS</div>
                            <div class="kpi-value" style="color: #7c3aed;">{{ number_format($summary['total_hours'], 1) }} hrs</div>
                        </div>
                    </div>
                </div>

                {{-- Ledger Table Card --}}
                <div class="ledger-card mb-4">
                    <div class="px-4 py-3 bg-white border-bottom d-flex align-items-center justify-content-between">
                        <div class="fw-bold text-dark"><i class="fa fa-list me-2 text-primary"></i> Attendance Movement Ledger Log</div>
                        <div class="text-muted small">Showing {{ $attendances->firstItem() ?? 0 }} - {{ $attendances->lastItem() ?? 0 }} of {{ $attendances->total() }} entries</div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.88rem;">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 45px;" class="text-center">#</th>
                                    <th style="width: 110px;">Date & Day</th>
                                    <th>Employee</th>
                                    <th>Department & Shift</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 120px;" class="text-success"><i class="fa fa-sign-in-alt me-1"></i> Check In</th>
                                    <th style="width: 120px;" class="text-danger"><i class="fa fa-sign-out-alt me-1"></i> Check Out</th>
                                    <th style="width: 100px;">Hours</th>
                                    <th style="width: 120px;">Late / Early</th>
                                    <th>Location / Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendances as $index => $att)
                                    @php
                                        $emp = $att->employee;
                                        $dateObj = \Carbon\Carbon::parse($att->date);
                                        $isLate = $att->is_late || $att->status == 'late';
                                        
                                        $badgeClass = match($att->status) {
                                            'present' => $isLate ? 'status-badge-late' : 'status-badge-present',
                                            'late' => 'status-badge-late',
                                            'absent' => 'status-badge-absent',
                                            'leave' => 'status-badge-leave',
                                            default => 'status-badge-present'
                                        };
                                        $statusLabel = $isLate ? 'Late' : ucfirst($att->status);
                                    @endphp
                                    <tr>
                                        <td class="text-center text-muted fw-bold">{{ $attendances->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $dateObj->format('d/m/Y') }}</div>
                                            <div class="small text-muted">{{ $dateObj->format('l') }}</div>
                                        </td>
                                        <td>
                                            @if($emp)
                                                <div class="fw-bold text-dark">{{ $emp->full_name }}</div>
                                                <div class="small text-muted">ID: #{{ $emp->id }} • {{ $emp->designation->name ?? 'N/A' }}</div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small fw-semibold text-dark">{{ $emp->department->name ?? 'N/A' }}</div>
                                            <div class="small text-muted"><i class="fa fa-clock me-1"></i>{{ $emp->shift->name ?? 'Default' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge px-3 py-1 rounded-pill {{ $badgeClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($att->check_in_time)
                                                <span class="fw-bold text-success"><i class="fa fa-sign-in-alt me-1"></i>{{ \Carbon\Carbon::parse($att->check_in_time)->format('h:i A') }}</span>
                                            @elseif($att->clock_in)
                                                <span class="fw-bold text-success">{{ \Carbon\Carbon::parse($att->clock_in)->format('h:i A') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($att->check_out_time)
                                                <span class="fw-bold text-danger"><i class="fa fa-sign-out-alt me-1"></i>{{ \Carbon\Carbon::parse($att->check_out_time)->format('h:i A') }}</span>
                                            @elseif($att->clock_out)
                                                <span class="fw-bold text-danger">{{ \Carbon\Carbon::parse($att->clock_out)->format('h:i A') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-dark">
                                            {{ $att->total_hours > 0 ? number_format($att->total_hours, 2) . ' hrs' : '-' }}
                                        </td>
                                        <td>
                                            @if($isLate && $att->late_minutes > 0)
                                                <span class="badge bg-warning text-dark"><i class="fa fa-exclamation-triangle me-1"></i>Late {{ $att->late_minutes }}m</span>
                                            @elseif($att->is_early_leave && $att->early_leave_minutes > 0)
                                                <span class="badge bg-info"><i class="fa fa-clock me-1"></i>Early {{ $att->early_leave_minutes }}m</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            {{ $att->check_in_location ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted">
                                            <i class="fa fa-folder-open fa-3x mb-3 text-muted"></i>
                                            <p class="mb-0 fw-semibold">No attendance movement records found matching selected filters.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 bg-white border-top d-flex justify-content-center no-print">
                        {{ $attendances->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

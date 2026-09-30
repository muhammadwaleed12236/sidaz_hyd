@extends('admin_panel.layout.app')

@section('content')
    <style>
        .dummy-stat-card {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
            background-color: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .dummy-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08) !important;
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .premium-card {
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03) !important;
            background-color: #ffffff;
        }

        .filter-panel {
            background-color: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 10px !important;
            padding: 16px !important;
        }

        .filter-panel label {
            font-size: 11px;
            font-weight: 700 !important;
            color: #475569 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-panel .form-control {
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            font-weight: 500 !important;
            color: #1e293b !important;
            height: 38px !important;
        }

        .btn-premium-primary {
            background-color: #2563eb !important;
            border: 1px solid #1d4ed8 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            height: 38px !important;
            padding: 0 16px !important;
        }
        .btn-premium-primary:hover {
            background-color: #1d4ed8 !important;
        }

        .btn-premium-secondary {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #475569 !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            height: 38px !important;
            padding: 0 16px !important;
        }
        .btn-premium-secondary:hover {
            background-color: #f1f5f9 !important;
            color: #1e293b !important;
        }

        .premium-table {
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            overflow: visible !important;
        }

        .premium-table thead th {
            background-color: #f8fafc !important;
            color: #334155 !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #cbd5e1 !important;
            padding: 12px 10px !important;
        }

        .premium-table tbody td {
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 12px 10px !important;
            font-size: 13px !important;
            color: #334155 !important;
            position: relative;
        }

        .premium-table tbody tr:hover td {
            background-color: #f8fafc !important;
        }

        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }

        .premium-table .dropdown-menu {
            border-radius: 8px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            border: 1px solid #cbd5e1 !important;
            z-index: 99999 !important;
            margin-top: 4px;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid py-4">

                <!-- Header Title & Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-file-signature text-primary"></i> Warranty Sale Invoices
                        </h4>
                        <p class="text-muted mb-0 small">Create, manage, and print standalone DRAP warranty sale invoices & gate passes</p>
                    </div>
                    <div>
                        <a href="{{ route('dummy-invoices.create') }}" class="btn btn-primary px-4 shadow-sm fw-bold align-items-center d-inline-flex gap-2" style="height: 40px;">
                            <i class="fas fa-plus-circle"></i> Create Warranty Invoice
                        </a>
                    </div>
                </div>

                <!-- KPI Summary Stat Cards -->
                @php
                    $totalInvoicesCount = $invoices->total();
                    $totalRevenueSum = $invoices->sum('total_amount');
                    $latestInv = $invoices->first();
                @endphp

                <div class="row g-3 mb-4">
                    <div class="col-md-4 col-sm-6">
                        <div class="card dummy-stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase">Total Warranty Invoices</span>
                                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalInvoicesCount) }}</h4>
                                </div>
                                <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="card dummy-stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase">Total Invoiced Amount</span>
                                    <h4 class="fw-bold text-success mb-0 mt-1">Rs. {{ number_format($totalRevenueSum, 2) }}</h4>
                                </div>
                                <div class="stat-icon-wrapper bg-success-subtle text-success">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="card dummy-stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase">Latest Invoice</span>
                                    <h4 class="fw-bold text-primary mb-0 mt-1">
                                        {{ $latestInv ? '#'.$latestInv->invoice_no : 'N/A' }}
                                    </h4>
                                </div>
                                <div class="stat-icon-wrapper bg-info-subtle text-info">
                                    <i class="fas fa-receipt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card premium-card">
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 mb-4">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ session('success') }}</span>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3 mb-4">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ session('error') }}</span>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Filter Search Panel -->
                        <div class="card filter-panel mb-4">
                            <div class="card-body p-0">
                                <form method="GET" action="{{ route('dummy-invoices.index') }}" class="row g-3 align-items-end">
                                    <div class="col-md-9">
                                        <label class="form-label mb-1">Search Warranty Invoices</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white text-muted border-end-0"><i class="fas fa-search"></i></span>
                                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by Invoice #, Customer Name, or Gate Pass #..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex gap-2">
                                        @if(request('search'))
                                            <a href="{{ route('dummy-invoices.index') }}" class="btn btn-premium-secondary w-50 d-flex align-items-center justify-content-center">
                                                <i class="fas fa-undo me-1"></i>Reset
                                            </a>
                                        @endif
                                        <button type="submit" class="btn btn-premium-primary {{ request('search') ? 'w-50' : 'w-100' }}">
                                            <i class="fas fa-search me-1"></i>Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Data Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle premium-table mb-0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Invoice #</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Licence & Gate Pass</th>
                                        <th class="text-end">Total Amount</th>
                                        <th class="pe-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $inv)
                                        @php
                                            $custName = $inv->customer_name ?: 'Walk-in Customer';
                                            $avatarLetter = strtoupper(substr($custName, 0, 1));
                                        @endphp
                                        <tr>
                                            <td class="ps-3 fw-bold text-primary font-monospace">
                                                #{{ $inv->invoice_no }}
                                            </td>
                                            <td class="text-nowrap small text-muted font-monospace">
                                                {{ \Carbon\Carbon::parse($inv->invoice_date)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary-subtle text-primary me-2 fw-bold d-flex align-items-center justify-content-center rounded-circle"
                                                        style="width: 32px; height: 32px; font-size: 13px;">
                                                        {{ $avatarLetter }}
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold text-dark d-block">{{ $custName }}</span>
                                                        @if($inv->customer_address)
                                                            <span class="text-muted small" style="font-size: 11px;">{{ Str::limit($inv->customer_address, 45) }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($inv->licence_no)
                                                    <div class="small"><span class="text-muted">Licence:</span> <strong class="text-dark">{{ $inv->licence_no }}</strong></div>
                                                @endif
                                                @if($inv->gate_pass_no)
                                                    <div class="small"><span class="text-muted">Gate Pass:</span> <span class="badge bg-light text-dark border">{{ $inv->gate_pass_no }}</span></div>
                                                @endif
                                                @if(!$inv->licence_no && !$inv->gate_pass_no)
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold text-success font-monospace">
                                                Rs. {{ number_format($inv->total_amount, 2) }}
                                            </td>
                                            <td class="pe-3 text-center">
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-xs btn-outline-secondary dropdown-toggle shadow-sm fw-bold px-2 py-1 dummy-action-dropdown-btn" type="button" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v me-1 text-primary"></i> Action
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right dropdown-menu-end shadow border-0 py-2" style="font-size: 12px; min-width: 170px;">
                                                        <li>
                                                            <a href="{{ route('dummy-invoices.print', $inv->id) }}" target="_blank" class="dropdown-item text-primary fw-bold d-flex align-items-center gap-2">
                                                                <i class="fas fa-print"></i> Print Invoice
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('dummy-invoices.edit', $inv->id) }}" class="dropdown-item text-dark d-flex align-items-center gap-2">
                                                                <i class="fas fa-edit text-muted"></i> Edit Invoice
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider my-1"></li>
                                                        <li>
                                                            <form action="{{ route('dummy-invoices.destroy', $inv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this warranty invoice?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                                                    <i class="fas fa-trash-alt"></i> Delete Invoice
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <i class="fas fa-file-invoice fa-3x mb-3 text-secondary opacity-50"></i>
                                                <p class="mb-0 fw-bold">No Warranty Sale Invoices Found</p>
                                                <small class="text-muted">Create your first standalone DRAP warranty sale invoice without inventory stock impact.</small>
                                                <div class="mt-3">
                                                    <a href="{{ route('dummy-invoices.create') }}" class="btn btn-primary px-4 shadow-sm fw-bold">
                                                        <i class="fas fa-plus-circle me-1"></i> Create First Invoice
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                @if($invoices->hasPages())
                    <div class="mt-3 d-flex justify-content-end">
                        {{ $invoices->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Action Dropdown Toggle Click Handler
            $(document).on('click', '.dummy-action-dropdown-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const $parent = $(this).closest('.dropdown');
                const $menu = $parent.find('.dropdown-menu');
                $('.dropdown-menu').not($menu).removeClass('show');
                $menu.toggleClass('show');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });
        });
    </script>
@endsection

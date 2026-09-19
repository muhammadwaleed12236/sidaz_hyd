@extends('admin_panel.layout.app')

@section('content')
    <style>
        /* Modern Executive ERP Dashboard Cards */
        .sale-stat-card {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04) !important;
            background-color: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .sale-stat-card:hover {
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

        .filter-panel .form-control,
        .filter-panel .form-select {
            border: 1px solid #cbd5e1 !important;
            border-radius: 6px !important;
            font-weight: 500 !important;
            color: #1e293b !important;
            height: 38px !important;
        }

        .filter-panel .form-control:focus,
        .filter-panel .form-select:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
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

        .btn-xs {
            padding: 4px 8px !important;
            font-size: 11px !important;
            border-radius: 5px !important;
            font-weight: 600 !important;
            line-height: 1.2 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 3px;
        }

        .premium-table {
            border: 1px solid #e2e8f0 !important;
            border-radius: 8px !important;
            overflow: hidden;
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
        }

        .premium-table tbody tr:hover td {
            background-color: #f8fafc !important;
        }
    </style>

    <div class="main-content">
        <div class="main-content-inner">
            <div class="container-fluid py-4">

                <!-- Header Title & Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-chart-line text-primary"></i> Sales Management
                        </h4>
                        <p class="text-muted mb-0 small">Overview of all sales orders, direct sales invoices, and delivery statuses</p>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a class="btn btn-outline-danger px-3 shadow-sm fw-bold align-items-center d-inline-flex gap-2"
                            href="{{ route('sale.return.index') }}">
                            <i class="fas fa-undo"></i> Sale Returns
                        </a>
                        <a class="btn btn-outline-primary px-3 shadow-sm fw-bold align-items-center d-inline-flex gap-2"
                            href="{{ route('sale.index', ['status' => 'sale_order']) }}">
                            <i class="fas fa-file-invoice"></i> Sale Orders
                        </a>
                        @can('sales.create')
                            <a class="btn btn-primary px-4 shadow-sm fw-bold align-items-center d-inline-flex gap-2"
                                href="{{ route('sale.add') }}">
                                <i class="fas fa-plus-circle"></i> Create Sale
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- KPI Summary Stat Cards -->
                @php
                    $totalCount = $sales->count();
                    $netRevenueSum = $sales->sum('total_net');
                    $bookedCount = $sales->filter(fn($s) => in_array($s->sale_status, ['booked', 'sale_order', 'pending']))->count();
                    $deliveredCount = $sales->filter(fn($s) => in_array($s->sale_status, ['posted', 'delivered', 'dispatched']))->count();
                @endphp

                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="card sale-stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase">Total Invoices</span>
                                    <h4 class="fw-bold text-dark mb-0 mt-1">{{ number_format($totalCount) }}</h4>
                                </div>
                                <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card sale-stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase">Total Revenue</span>
                                    <h4 class="fw-bold text-success mb-0 mt-1">Rs. {{ number_format($netRevenueSum, 2) }}</h4>
                                </div>
                                <div class="stat-icon-wrapper bg-success-subtle text-success">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card sale-stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase">Sale Orders (Booked)</span>
                                    <h4 class="fw-bold text-primary mb-0 mt-1">{{ number_format($bookedCount) }}</h4>
                                </div>
                                <div class="stat-icon-wrapper bg-info-subtle text-info">
                                    <i class="fas fa-bookmark"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="card sale-stat-card p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted small fw-bold text-uppercase">Delivered / Complete</span>
                                    <h4 class="fw-bold text-emerald mb-0 mt-1" style="color: #059669;">{{ number_format($deliveredCount) }}</h4>
                                </div>
                                <div class="stat-icon-wrapper bg-success-subtle text-success">
                                    <i class="fas fa-check-double"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Pills Filter --}}
                <div class="mb-3 d-flex gap-2 flex-wrap">
                    <a href="{{ route('sale.index', ['status' => 'all']) }}"
                        class="btn btn-sm {{ request('status') == 'all' || !request('status') ? 'btn-dark' : 'btn-outline-secondary' }} rounded-pill px-3 shadow-sm fw-bold">
                        All Sales
                    </a>
                    <a href="{{ route('sale.index', ['status' => 'sale_order']) }}"
                        class="btn btn-sm {{ request('status') == 'sale_order' || request('status') == 'booked' ? 'btn-primary text-white' : 'btn-outline-primary' }} rounded-pill px-3 shadow-sm fw-bold">
                        <i class="fas fa-file-invoice me-1"></i> Sale Orders
                    </a>
                    <a href="{{ route('sale.index', ['status' => 'ready']) }}"
                        class="btn btn-sm {{ request('status') == 'ready' ? 'btn-warning text-dark' : 'btn-outline-warning' }} rounded-pill px-3 shadow-sm fw-bold">
                        <i class="fas fa-box me-1"></i> Ready
                    </a>
                    <a href="{{ route('sale.index', ['status' => 'delivered']) }}"
                        class="btn btn-sm {{ request('status') == 'delivered' || request('status') == 'posted' ? 'btn-success text-white' : 'btn-outline-success' }} rounded-pill px-3 shadow-sm fw-bold">
                        <i class="fas fa-check-circle me-1"></i> Delivered / Complete
                    </a>
                    <a href="{{ route('sale.index', ['status' => 'returned']) }}"
                        class="btn btn-sm {{ request('status') == 'returned' ? 'btn-danger' : 'btn-outline-danger' }} rounded-pill px-3 shadow-sm fw-bold">
                        <i class="fas fa-undo me-1"></i> Returned
                    </a>
                </div>

                <div class="card premium-card">
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center gap-2 rounded-3 mb-4">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ session('success') }}</span>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger d-flex align-items-center gap-2 rounded-3 mb-4">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ session('error') }}</span>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- AJAX Filter Panel --}}
                        <div class="card filter-panel mb-4">
                            <div class="card-body p-0">
                                <form id="filterForm" class="row g-3 align-items-end" autocomplete="off">
                                    <div class="col-md-2">
                                        <label class="form-label mb-1">Quick Filter</label>
                                        <select id="quick_filter" class="form-select">
                                            <option value="custom">Custom Range</option>
                                            <option value="daily">Daily (Today)</option>
                                            <option value="weekly">Weekly (This Week)</option>
                                            <option value="monthly">Monthly (This Month)</option>
                                            <option value="yearly">Yearly (This Year)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-1">From Date</label>
                                        <input type="text" class="form-control datepicker-custom bg-white" name="from_date" id="filter_from_date" placeholder="dd/mm/yyyy">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-1">To Date</label>
                                        <input type="text" class="form-control datepicker-custom bg-white" name="to_date" id="filter_to_date" placeholder="dd/mm/yyyy">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-1">Bill / Invoice #</label>
                                        <input type="text" class="form-control" name="bill_no" id="filter_bill_no" placeholder="Search bill #...">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-1">Customer</label>
                                        <select class="form-select" name="customer_id" id="filter_customer_id">
                                            <option value="">All Customers</option>
                                            @foreach ($customers as $c)
                                                <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex gap-2">
                                        <button type="button" class="btn btn-premium-secondary w-50" id="btnReset">
                                            <i class="fas fa-undo me-1"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-premium-primary w-50" id="btnSearch">
                                            <i class="fas fa-search me-1"></i>Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="sales-table" class="table table-hover align-middle datanew premium-table" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3 ps-3 text-secondary fw-bold text-uppercase small">Bill #</th>
                                        <th class="py-3 text-secondary fw-bold text-uppercase small">Customer</th>
                                        <th class="py-3 text-secondary fw-bold text-uppercase small">Products</th>
                                        <th class="py-3 text-secondary fw-bold text-uppercase small text-center">Qty</th>
                                        <th class="py-3 text-secondary fw-bold text-uppercase small text-end">Gross</th>
                                        <th class="py-3 text-secondary fw-bold text-uppercase small text-end">Inline Disc</th>
                                        <th class="py-3 text-secondary fw-bold text-uppercase small text-end">Add. Disc</th>
                                        <th class="py-3 text-secondary fw-bold text-uppercase small text-end">Net Total</th>
                                        <th class="py-3 text-secondary fw-bold text-uppercase small">Date</th>
                                        <th class="py-3 text-secondary fw-bold text-uppercase small">Status</th>
                                        <th class="py-3 pe-3 text-secondary fw-bold text-uppercase small text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="salesTableBody">
                                    @include('admin_panel.sale.partials.sales_table_body')
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            function initDataTable() {
                if ($.fn.DataTable.isDataTable('.datanew')) {
                    $('.datanew').DataTable().destroy();
                }
                $('.datanew').DataTable({
                    "bFilter": true,
                    "sDom": 'fBtlpi',
                    "ordering": true,
                    "order": [[0, 'desc']],
                    "language": {
                        search: ' ',
                        sSearch: '',
                        searchPlaceholder: "Search sales...",
                        info: "_START_ - _END_ of _TOTAL_ items",
                        paginate: {
                            next: ' <i class="fa fa-angle-right"></i>',
                            previous: '<i class="fa fa-angle-left"></i> '
                        }
                    },
                    initComplete: (settings, json) => {
                        $('.dataTables_filter').appendTo('#tableSearch');
                        $('.dataTables_filter font').remove();
                    }
                });
            }

            initDataTable();

            // Datepickers
            $('.datepicker-custom').datepicker({
                dateFormat: 'dd/mm/yy',
                autoclose: true,
                todayHighlight: true
            });

            // Quick Filter Handler
            $('#quick_filter').change(function() {
                const val = $(this).val();
                if (val === 'custom') return;

                let fromDate = '', toDate = '';
                const today = new Date();
                const formatDate = (d) => {
                    let day = ("0" + d.getDate()).slice(-2);
                    let month = ("0" + (d.getMonth() + 1)).slice(-2);
                    return `${day}/${month}/${d.getFullYear()}`;
                };

                if (val === 'daily') {
                    fromDate = toDate = formatDate(today);
                } else if (val === 'weekly') {
                    const first = today.getDate() - today.getDay();
                    const firstDay = new Date(today.setDate(first));
                    const lastDay = new Date(today.setDate(first + 6));
                    fromDate = formatDate(firstDay);
                    toDate = formatDate(lastDay);
                } else if (val === 'monthly') {
                    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    fromDate = formatDate(firstDay);
                    toDate = formatDate(lastDay);
                } else if (val === 'yearly') {
                    const firstDay = new Date(today.getFullYear(), 0, 1);
                    const lastDay = new Date(today.getFullYear(), 11, 31);
                    fromDate = formatDate(firstDay);
                    toDate = formatDate(lastDay);
                }

                $('#filter_from_date').val(fromDate);
                $('#filter_to_date').val(toDate);
                fetchFilteredSales();
            });

            // AJAX Filter Submit
            $('#filterForm').submit(function(e) {
                e.preventDefault();
                fetchFilteredSales();
            });

            $('#btnReset').click(function() {
                $('#filterForm')[0].reset();
                $('#quick_filter').val('custom');
                fetchFilteredSales();
            });

            function fetchFilteredSales() {
                const formData = $('#filterForm').serialize();
                const currentStatus = "{{ request('status', 'all') }}";

                $.ajax({
                    url: "{{ route('sale.index') }}",
                    type: "GET",
                    data: formData + "&status=" + currentStatus,
                    success: function(response) {
                        $('#salesTableBody').html(response.html);
                        initDataTable();
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to fetch sales data.', 'error');
                    }
                });
            }

            // Confirm Booking Action
            $(document).on('click', '.confirm-booking-btn', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                Swal.fire({
                    title: 'Confirm Sale Order?',
                    text: "This will convert the sale order into a confirmed sale invoice.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Confirm!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection

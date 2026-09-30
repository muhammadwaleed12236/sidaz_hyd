@extends('admin_panel.layout.app')
@section('content')

@if (session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            confirmButtonColor: '#4f46e5'
        });
    });
    </script>
@endif

<style>
    /* ── ERP COMPACT DESIGN SYSTEM ── */
    .erp-page { background: #f8fafc; min-height: calc(100vh - 60px); padding: 12px 0; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    
    :root {
        --erp-primary:    #4f46e5;
        --erp-primary-lt: #eef2ff;
        --erp-success:    #10b981;
        --erp-success-lt: #ecfdf5;
        --erp-warning:    #f59e0b;
        --erp-warning-lt: #fffbeb;
        --erp-danger:     #ef4444;
        --erp-danger-lt:  #fef2f2;
        --erp-border:     #e2e8f0;
        --erp-bg:         #f8fafc;
        --erp-card-bg:    #ffffff;
        --erp-text:       #0f172a;
        --erp-muted:      #64748b;
        --erp-radius:     8px;
        --erp-shadow:     0 1px 3px rgba(15,23,42,0.05);
    }

    .erp-card {
        background: var(--erp-card-bg);
        border-radius: var(--erp-radius);
        border: 1px solid var(--erp-border);
        box-shadow: var(--erp-shadow);
        overflow: hidden;
    }

    .erp-card-header {
        padding: 12px 18px;
        border-bottom: 1px solid var(--erp-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        background: #ffffff;
    }
    .page-head-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--erp-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .head-kpis {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-left: 8px;
    }
    .kpi-pill {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .kpi-pill strong { color: var(--erp-text); font-weight: 700; }
    .kpi-pill.success { background: var(--erp-success-lt); color: #047857; border-color: #a7f3d0; }
    .kpi-pill.danger { background: var(--erp-danger-lt); color: #b91c1c; border-color: #fecaca; }

    .erp-hdr-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px;
    }
    .btn-hdr {
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.81rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all .15s;
        border: 1px solid transparent;
        text-decoration: none;
        cursor: pointer;
        height: 34px;
        box-sizing: border-box;
    }
    .btn-hdr-outline { background: #fff; color: var(--erp-muted); border-color: var(--erp-border); }
    .btn-hdr-outline:hover { border-color: #94a3b8; color: var(--erp-text); background: var(--erp-bg); }
    .btn-hdr-primary { background: var(--erp-primary); color: #fff; border-color: var(--erp-primary); box-shadow: 0 1px 3px rgba(79,70,229,0.2); }
    .btn-hdr-primary:hover { background: #4338ca; border-color: #4338ca; color: #fff; }

    /* Desktop Table */
    .erp-table-wrap { padding: 0; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    #customerTable {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 0.83rem;
        margin-bottom: 0 !important;
    }
    #customerTable thead th {
        background: #f1f5f9 !important;
        color: #475569 !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.71rem;
        letter-spacing: 0.5px;
        padding: 9px 12px;
        border-bottom: 1px solid var(--erp-border) !important;
        white-space: nowrap;
    }
    #customerTable tbody td {
        padding: 8px 12px;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: var(--erp-text);
        vertical-align: middle;
        white-space: nowrap;
    }
    #customerTable tbody tr:hover { background: #f8fafc !important; }

    /* Badges */
    .cust-id-badge { background: #f1f5f9; color: #475569; font-weight: 700; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; font-family: monospace; }
    
    .status-badge { padding: 3px 10px; border-radius: 12px; font-size: 0.72rem; font-weight: 700; white-space: nowrap; }
    .status-active { background: var(--erp-success-lt); color: #047857; border: 1px solid #a7f3d0; }
    .status-inactive { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }

    /* Action buttons */
    .btn-act {
        border-radius: 4px; padding: 4px 8px; font-size: 0.75rem;
        font-weight: 600; display: inline-flex; align-items: center; gap: 4px;
        border: 1px solid transparent; transition: all .12s; cursor: pointer;
        line-height: 1.3; white-space: nowrap; flex-shrink: 0; height: 28px;
    }
    .btn-act-view { background: #e0f2fe; color: #0284c7; border-color: #bae6fd; }
    .btn-act-view:hover { background: #0284c7; color: #fff; }
    .btn-act-edit { background: var(--erp-primary-lt); color: var(--erp-primary); border-color: #c7d2fe; }
    .btn-act-edit:hover { background: var(--erp-primary); color: #fff; }
    .btn-act-deact { background: var(--erp-danger-lt); color: #b91c1c; border-color: #fecaca; }
    .btn-act-deact:hover { background: #ef4444; color: #fff; }
    .btn-act-toggle { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
    .btn-act-toggle:hover { background: #e2e8f0; color: #0f172a; }

    /* Mobile view cards */
    .mobile-cust-cards { display: none; padding: 10px; }
    @media (max-width: 768px) {
        .erp-page { padding: 8px 0; }
        .erp-card-header { flex-direction: column; align-items: flex-start; gap: 8px; padding: 10px 12px; }
        .erp-hdr-actions { width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
        .btn-hdr { width: 100%; justify-content: center; height: 34px; font-size: 0.78rem; }
        .erp-table-wrap { display: none !important; }
        .mobile-cust-cards { display: flex; flex-direction: column; gap: 8px; }
    }

    .cust-mcard {
        background: #ffffff;
        border: 1px solid var(--erp-border);
        border-radius: 8px;
        padding: 10px 12px;
        box-shadow: 0 1px 3px rgba(15,23,42,0.03);
    }
    .cust-mcard-hd { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
    .cust-mcard-body {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .cust-mcard-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 6px !important;
        margin-top: 8px;
        width: 100%;
    }
    .cust-mcard-actions .btn-act { width: 100% !important; justify-content: center !important; height: 32px !important; font-size: 0.76rem !important; border-radius: 6px !important; }
</style>

<div class="erp-page">
    <div class="container-fluid px-2">
        <div class="erp-card">
            
            {{-- Header Bar --}}
            <div class="erp-card-header">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <h1 class="page-head-title"><i class="fas fa-users text-primary me-1"></i> Customer Directory</h1>
                    <div class="head-kpis">
                        <span class="kpi-pill">Total: <strong>{{ count($customers) }}</strong></span>
                        <span class="kpi-pill success">Active: <strong>{{ $customers->where('status', 'active')->count() }}</strong></span>
                        <span class="kpi-pill danger">Inactive: <strong>{{ $customers->where('status', '!=', 'active')->count() }}</strong></span>
                    </div>
                </div>
                <div class="erp-hdr-actions">
                    <a href="{{ route('customers.inactive') }}" class="btn-hdr btn-hdr-outline">
                        <i class="fas fa-user-slash"></i> Inactive Customers
                    </a>
                    @can('customers.view')
                        <a href="{{ route('customers.ledger') }}" class="btn-hdr btn-hdr-outline">
                            <i class="fas fa-book"></i> Ledger
                        </a>
                        <a href="{{ route('customer.payments') }}" class="btn-hdr btn-hdr-outline">
                            <i class="fas fa-receipt"></i> Payments
                        </a>
                    @endcan
                    @can('customers.create')
                        <a href="{{ route('customers.create') }}" class="btn-hdr btn-hdr-primary">
                            <i class="fas fa-plus"></i> Add Customer
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Desktop Table View --}}
            <div class="erp-table-wrap">
                <table id="customerTable" class="table align-middle nowrap">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 70px;">Cust ID</th>
                            <th>Customer Name</th>
                            <th>Mobile</th>
                            <th>Credit Limit</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td class="text-center"><span class="cust-id-badge">#{{ $customer->customer_id }}</span></td>
                                <td class="fw-semibold text-dark">{{ $customer->customer_name }}</td>
                                <td><i class="fas fa-phone-alt me-1 text-muted" style="font-size:0.68rem;"></i> {{ $customer->mobile }}</td>
                                <td class="fw-semibold text-primary">
                                    {{ $customer->balance_range == 0 ? 'Unlimited' : 'Rs. ' . number_format($customer->balance_range, 0) }}
                                </td>
                                <td class="text-center">
                                    @if(strtolower($customer->status) === 'active')
                                        <span class="status-badge status-active"><i class="fas fa-circle me-1" style="font-size: 7px;"></i> Active</span>
                                    @else
                                        <span class="status-badge status-inactive"><i class="fas fa-circle me-1" style="font-size: 7px;"></i> Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1 align-items-center">
                                        @include('admin_panel.partials.action_buttons', [
                                            'editRoute' => route('customers.edit', $customer->id),
                                            'deleteRoute' => route('customers.destroy', $customer->id),
                                            'editIsLink' => true,
                                            'permissions' => [
                                                'edit' => 'customers.edit',
                                                'delete' => 'customers.delete',
                                            ],
                                            'dataId' => $customer->id,
                                        ])

                                        @can('customers.edit')
                                            <a href="{{ route('customers.toggleStatus', $customer->id) }}"
                                                class="btn-act btn-act-toggle"
                                                title="Toggle Active Status">
                                                <i class="fa-solid {{ strtolower($customer->status) === 'active' ? 'fa-toggle-on text-success' : 'fa-toggle-off text-muted' }}"></i>
                                            </a>
                                        @endcan
                                        @can('customers.view')
                                            <a href="{{ route('customer.payments') }}" class="btn-act btn-act-view" title="Customer Payments">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>{{-- /erp-table-wrap --}}

            {{-- Mobile Cards View (< 768px) --}}
            <div class="mobile-cust-cards">
                @foreach ($customers as $customer)
                    <div class="cust-mcard">
                        <div class="cust-mcard-hd">
                            <div>
                                <span class="cust-id-badge">#{{ $customer->customer_id }}</span>
                                <div class="fw-bold text-dark fs-6 mt-1">{{ $customer->customer_name }}</div>
                                <div class="text-muted small mt-1"><i class="fas fa-phone-alt me-1"></i> {{ $customer->mobile }}</div>
                            </div>
                            <div>
                                @if(strtolower($customer->status) === 'active')
                                    <span class="status-badge status-active">Active</span>
                                @else
                                    <span class="status-badge status-inactive">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <div class="cust-mcard-body">
                            <div>
                                <div style="font-size:0.65rem; font-weight:700; color:var(--erp-muted); text-transform:uppercase;">Credit Limit</div>
                                <div class="fw-bold text-primary fs-6">
                                    {{ $customer->balance_range == 0 ? 'Unlimited' : 'Rs. ' . number_format($customer->balance_range, 0) }}
                                </div>
                            </div>
                        </div>

                        <div class="cust-mcard-actions">
                            @can('customers.edit')
                                <a href="{{ route('customers.edit', $customer->id) }}" class="btn-act btn-act-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endcan
                            @can('customers.edit')
                                <a href="{{ route('customers.toggleStatus', $customer->id) }}" class="btn-act btn-act-toggle">
                                    <i class="fa-solid {{ strtolower($customer->status) === 'active' ? 'fa-toggle-on text-success' : 'fa-toggle-off' }}"></i> Toggle Status
                                </a>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>

        </div>{{-- /erp-card --}}
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        if($('#customerTable').length) {
            $('#customerTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "order": [[0, 'desc']],
                "language": {
                    "search": "Search Customers:",
                    "lengthMenu": "Show _MENU_ entries"
                }
            });
        }
    });
</script>
@endsection

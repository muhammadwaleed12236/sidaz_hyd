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
    .btn-hdr-primary { background: #2563eb; color: #fff; border-color: #2563eb; box-shadow: 0 1px 3px rgba(37,99,235,0.2); }
    .btn-hdr-primary:hover { background: #1d4ed8; border-color: #1d4ed8; color: #fff; }

    /* Desktop Table */
    .erp-table-wrap { padding: 0; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    #default-datatable {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 0.83rem;
        margin-bottom: 0 !important;
    }
    #default-datatable thead th {
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
    #default-datatable tbody td {
        padding: 8px 12px;
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: var(--erp-text);
        vertical-align: middle;
        white-space: nowrap;
    }
    #default-datatable tbody tr:hover { background: #f8fafc !important; }

    /* Badges */
    .sup-id-badge { background: #f1f5f9; color: #475569; font-weight: 700; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; font-family: monospace; }
    .finance-badge { background: #fdf4ff; color: #c026d3; font-weight: 600; font-size: 0.72rem; padding: 2px 7px; border-radius: 4px; }
    
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
    .btn-act-edit { background: var(--erp-primary-lt); color: var(--erp-primary); border-color: #c7d2fe; }
    .btn-act-edit:hover { background: var(--erp-primary); color: #fff; }
    .btn-act-deact { background: var(--erp-danger-lt); color: #b91c1c; border-color: #fecaca; }
    .btn-act-deact:hover { background: #ef4444; color: #fff; }

    /* Mobile Cards View */
    .mobile-vendor-cards { display: none; padding: 10px; }
    @media (max-width: 768px) {
        .erp-page { padding: 8px 0; }
        .erp-card-header { flex-direction: column; align-items: flex-start; gap: 8px; padding: 10px 12px; }
        .erp-hdr-actions { width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
        .btn-hdr { width: 100%; justify-content: center; height: 34px; font-size: 0.78rem; }
        .erp-table-wrap { display: none !important; }
        .mobile-vendor-cards { display: flex; flex-direction: column; gap: 8px; }
    }

    .vendor-mcard {
        background: #ffffff;
        border: 1px solid var(--erp-border);
        border-radius: 8px;
        padding: 10px 12px;
        box-shadow: 0 1px 3px rgba(15,23,42,0.03);
    }
    .vendor-mcard-hd { display: flex; align-items: flex-start; justify-content: space-between; gap: 8px; }
    .vendor-mcard-body {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px dashed #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .vendor-mcard-actions {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 6px !important;
        margin-top: 8px;
        width: 100%;
    }
    .vendor-mcard-actions .btn-act { width: 100% !important; justify-content: center !important; height: 32px !important; font-size: 0.76rem !important; border-radius: 6px !important; }
</style>

<div class="erp-page">
    <div class="container-fluid px-2">
        <div class="erp-card">
            
            {{-- Header Bar --}}
            <div class="erp-card-header">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <h1 class="page-head-title"><i class="fas fa-truck text-primary me-1" style="color:#2563eb;"></i> Vendor Directory</h1>
                    <div class="head-kpis">
                        <span class="kpi-pill">Total: <strong>{{ count($vendors) }}</strong></span>
                        <span class="kpi-pill success">Active: <strong>{{ $vendors->where('status', 1)->count() }}</strong></span>
                        <span class="kpi-pill danger">Inactive: <strong>{{ $vendors->where('status', 0)->count() }}</strong></span>
                    </div>
                </div>
                <div class="erp-hdr-actions">
                    <a href="{{ url('vendors-ledger') }}" class="btn-hdr btn-hdr-outline">
                        <i class="fas fa-book"></i> Ledger
                    </a>
                    <a href="{{ route('vendor.payments') }}" class="btn-hdr btn-hdr-outline">
                        <i class="fas fa-money-check-alt"></i> Payments
                    </a>
                    <a href="{{ url('vendor/bilties') }}" class="btn-hdr btn-hdr-outline">
                        <i class="fas fa-shipping-fast"></i> Bilty
                    </a>
                    @can('vendors.create')
                        <a href="{{ route('vendors.create') }}" class="btn-hdr btn-hdr-primary">
                            <i class="fas fa-plus"></i> Add Vendor
                        </a>
                    @endcan
                </div>
            </div>

            {{-- Desktop Table View --}}
            <div class="erp-table-wrap">
                <table id="default-datatable" class="table align-middle nowrap">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">ID</th>
                            <th>Vendor Info</th>
                            <th>Contact</th>
                            <th>Financials</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 130px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendors as $v)
                            <tr>
                                <td class="text-center id"><span class="sup-id-badge">#{{ $v->id }}</span></td>
                                
                                <td>
                                    <div class="name fw-bold text-dark">{{ $v->name }}</div>
                                    @if($v->company_name)
                                        <div class="text-muted small"><i class="fas fa-building me-1" style="font-size:0.65rem;"></i>{{ $v->company_name }}</div>
                                    @endif
                                </td>
                                
                                <td>
                                    @if($v->contact_person)
                                        <div class="contact_person fw-semibold text-dark small"><i class="fas fa-user-tie me-1 text-muted"></i>{{ $v->contact_person }}</div>
                                    @endif
                                    <div class="phone text-muted small"><i class="fas fa-phone-alt me-1"></i>{{ $v->phone }}</div>
                                </td>

                                <td>
                                    <span class="finance-badge mb-1">Limit: Rs. {{ number_format($v->credit_limit, 2) }}</span>
                                    <div class="text-muted small">OB: Rs. {{ number_format($v->opening_balance, 2) }}</div>
                                </td>

                                <td class="text-center status" data-status="{{ $v->status }}">
                                    @if($v->status)
                                        <span class="status-badge status-active"><i class="fas fa-circle me-1" style="font-size: 7px;"></i> Active</span>
                                    @else
                                        <span class="status-badge status-inactive"><i class="fas fa-circle me-1" style="font-size: 7px;"></i> Inactive</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @include('admin_panel.partials.action_buttons', [
                                        'editRoute' => route('vendors.edit', $v->id),
                                        'deleteRoute' => url('vendor/delete/' . $v->id),
                                        'editIsLink' => true,
                                        'permissions' => [
                                            'edit' => 'vendors.edit',
                                            'delete' => 'vendors.delete',
                                        ],
                                        'dataId' => $v->id,
                                        'deleteMsg' => 'Are you sure you want to delete this vendor?',
                                    ])
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>{{-- /erp-table-wrap --}}

            {{-- Mobile Cards View (< 768px) --}}
            <div class="mobile-vendor-cards">
                @foreach ($vendors as $v)
                    <div class="vendor-mcard">
                        <div class="vendor-mcard-hd">
                            <div>
                                <span class="sup-id-badge">#{{ $v->id }}</span>
                                <div class="fw-bold text-dark fs-6 mt-1">{{ $v->name }}</div>
                                @if($v->company_name)
                                    <div class="text-muted small"><i class="fas fa-building me-1"></i>{{ $v->company_name }}</div>
                                @endif
                                <div class="text-muted small mt-1"><i class="fas fa-phone-alt me-1"></i>{{ $v->phone }}</div>
                            </div>
                            <div>
                                @if($v->status)
                                    <span class="status-badge status-active">Active</span>
                                @else
                                    <span class="status-badge status-inactive">Inactive</span>
                                @endif
                            </div>
                        </div>

                        <div class="vendor-mcard-body">
                            <div>
                                <div style="font-size:0.65rem; font-weight:700; color:var(--erp-muted); text-transform:uppercase;">Credit Limit</div>
                                <div class="fw-bold text-dark fs-6">Rs. {{ number_format($v->credit_limit, 2) }}</div>
                            </div>
                            <div class="text-end">
                                <div style="font-size:0.65rem; font-weight:700; color:var(--erp-muted); text-transform:uppercase;">Opening Bal</div>
                                <div class="text-muted small">Rs. {{ number_format($v->opening_balance, 2) }}</div>
                            </div>
                        </div>

                        <div class="vendor-mcard-actions">
                            @can('vendors.edit')
                                <a href="{{ route('vendors.edit', $v->id) }}" class="btn-act btn-act-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endcan
                            @can('vendors.delete')
                                <form action="{{ url('vendor/delete/' . $v->id) }}" method="GET" class="d-inline w-100" onsubmit="return confirm('Are you sure you want to delete this vendor?')">
                                    <button type="submit" class="btn-act btn-act-deact w-100">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
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
        if($('#default-datatable').length) {
            $('#default-datatable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "order": [[0, 'desc']],
                "language": {
                    "search": "Search Vendors:",
                    "lengthMenu": "Show _MENU_ entries"
                }
            });
        }
    });
</script>
@endsection

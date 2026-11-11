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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .sup-page {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 40px;
    }

    /* Page Header */
    .sup-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        border-radius: 16px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .sup-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        margin-bottom: 2px;
        letter-spacing: -0.02em;
    }
    .sup-sub {
        font-size: 0.82rem;
        color: #64748b;
    }

    .btn-sup-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff !important;
        border: none;
        padding: 10px 20px;
        font-size: 0.86rem;
        font-weight: 600;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.22);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-sup-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
    }

    .sup-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .sup-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #default-datatable thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 20px;
    }
    #default-datatable tbody td {
        padding: 14px 20px;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-completed {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }
    .status-ordered {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .status-draft {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .btn-confirm-order {
        background: #16a34a;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 4px 10px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(22, 163, 74, 0.2);
    }
    .btn-confirm-order:hover {
        background: #15803d;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(22, 163, 74, 0.3);
    }
</style>

<div class="sup-page container-fluid px-3 px-md-4 pt-3">
    <div class="sup-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h3 class="sup-title"><i class="fas fa-shopping-cart text-primary me-2" style="color: #2563eb !important;"></i>Material Purchases</h3>
            <div class="sup-sub">Manage raw materials and packaging purchases</div>
        </div>
        <div class="vendor-action-hub">
            <a href="{{ route('material-purchases.create') }}" class="btn-sup-primary">
                <i class="fas fa-plus"></i> New Purchase
            </a>
        </div>
    </div>

    <div class="sup-card">
        <div class="sup-card-header">
            <div class="fw-bold text-dark"><i class="fas fa-list me-1 text-muted"></i> All Material Purchases</div>
        </div>
        <div class="sup-table-wrap p-3">
            <table class="table align-middle datanew" id="default-datatable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 70px;">Inv No</th>
                        <th>Date</th>
                        <th>Vendor</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $p)
                        <tr>
                            <td class="text-center"><span class="badge bg-light text-dark fw-bold border">#{{ $p->invoice_no }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($p->purchase_date)->format('d M, Y') }}</td>
                            <td>{{ $p->vendor->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-secondary">{{ $p->purchase_type }}</span></td>
                            <td class="fw-bold text-dark">Rs. {{ number_format($p->total_amount, 2) }}</td>
                            <td class="text-success">Rs. {{ number_format($p->paid_amount, 2) }}</td>
                            <td>
                                @if($p->status === 'completed')
                                    <span class="status-badge status-completed"><i class="fas fa-check-circle" style="font-size: 10px;"></i> Completed</span>
                                @elseif($p->status === 'ordered')
                                    <span class="status-badge status-ordered"><i class="fas fa-clipboard-list" style="font-size: 10px;"></i> Ordered</span>
                                @else
                                    <span class="status-badge status-draft"><i class="fas fa-clock" style="font-size: 10px;"></i> Draft</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end align-items-center">
                                    @if($p->status === 'ordered')
                                        <a href="{{ route('material-purchases.edit', $p->id) }}" class="btn-confirm-order" title="Confirm and Receive Order">
                                            <i class="fas fa-check-double"></i> Confirm & Receive
                                        </a>
                                    @endif
                                    <a href="{{ route('material-purchases.show', $p->id) }}" class="btn btn-sm btn-light border" title="View"><i class="fas fa-eye text-primary"></i></a>
                                    <a href="{{ route('material-purchases.edit', $p->id) }}" class="btn btn-sm btn-light border" title="Edit"><i class="fas fa-edit text-warning"></i></a>
                                    <form action="{{ route('material-purchases.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this purchase? This will reverse any stock and accounting entries.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border" title="Delete"><i class="fas fa-trash text-danger"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('.datanew')) {
            $('.datanew').DataTable().destroy();
        }
        $('.datanew').DataTable({
            "pageLength": 10,
            "order": [],
            "language": {
                "search": "",
                "searchPlaceholder": "Search purchases..."
            }
        });
    });
</script>
@endsection

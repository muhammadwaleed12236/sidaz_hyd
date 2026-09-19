@extends('admin_panel.layout.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold text-primary mb-1">
                <i class="fas fa-file-signature me-2"></i>Warranty Sale Invoices
            </h3>
            <p class="text-muted small mb-0">Create and print standalone warranty sale invoices for custom customers (No stock/ledger impact).</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('dummy-invoices.create') }}" class="btn btn-primary px-4 py-2 shadow-sm fw-bold">
                <i class="fas fa-plus-circle me-2"></i>Create Warranty Invoice
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-0">
            <form method="GET" action="{{ route('dummy-invoices.index') }}" class="row g-2 justify-content-between">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by Invoice # or Customer Name..." value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                @if(request('search'))
                    <div class="col-auto">
                        <a href="{{ route('dummy-invoices.index') }}" class="btn btn-outline-danger"><i class="fas fa-times me-1"></i> Clear Filter</a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase fs-7 text-muted">
                        <tr>
                            <th class="ps-4">Invoice #</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Licence No</th>
                            <th>Gate Pass #</th>
                            <th class="text-end">Total Amount</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">#{{ $inv->invoice_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($inv->invoice_date)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $inv->customer_name }}</span>
                                    @if($inv->customer_address)
                                        <div class="small text-muted">{{ Str::limit($inv->customer_address, 40) }}</div>
                                    @endif
                                </td>
                                <td>{{ $inv->licence_no ?? '-' }}</td>
                                <td>{{ $inv->gate_pass_no ?? '-' }}</td>
                                <td class="text-end fw-bold text-dark">Rs. {{ number_format($inv->total_amount, 2) }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('dummy-invoices.print', $inv->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Print Warranty Invoice">
                                            <i class="fas fa-print me-1"></i> Print
                                        </a>
                                        <a href="{{ route('dummy-invoices.edit', $inv->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Invoice">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('dummy-invoices.destroy', $inv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Invoice">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-file-invoice fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0">No Warranty Sale Invoices found.</p>
                                    <a href="{{ route('dummy-invoices.create') }}" class="btn btn-sm btn-primary mt-2">Create First Invoice</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($invoices->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

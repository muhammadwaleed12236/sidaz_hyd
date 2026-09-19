@extends('admin_panel.layout.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fa fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
            <!-- PAGE HEADER -->
            <div class="card-header bg-white py-3 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                <div>
                    <h4 class="mb-0 font-weight-bold text-dark">
                        <i class="fa fa-cogs text-primary mr-2"></i> Production Batches & Manufacturing
                    </h4>
                    <span class="text-muted small"><strong>Production Rule:</strong> "Material available hai, ab product banana hai" (Produce batch with Batch #, Mfg & Exp dates).</span>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('production.create') }}" class="btn btn-primary font-weight-bold shadow-sm" style="border-radius: 8px;">
                        <i class="fa fa-plus me-1"></i> Produce New Batch
                    </a>
                </div>
            </div>

            <!-- BATCHES TABLE -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="bg-light text-uppercase small text-muted font-weight-bold">
                            <tr>
                                <th>Batch #</th>
                                <th>Finished Product</th>
                                <th>Produced Qty</th>
                                <th>Customer Demand / Sale Order</th>
                                <th>Mfg & Exp Dates</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                                <tr>
                                    <td class="font-weight-bold text-primary align-middle">
                                        {{ $batch->batch_no }}
                                    </td>
                                    <td class="text-left align-middle font-weight-bold text-dark">
                                        {{ $batch->product->item_name ?? 'N/A' }}
                                        <small class="text-muted d-block">SKU: {{ $batch->product->item_code ?? '-' }}</small>
                                    </td>
                                    <td class="align-middle font-weight-bold text-success" style="font-size: 1rem;">
                                        {{ (float)$batch->quantity }} Pcs
                                    </td>
                                    <td class="align-middle">
                                        @if($batch->sale)
                                            <span class="badge badge-light border text-dark font-weight-bold px-2 py-1">
                                                Order #{{ $batch->sale->invoice_no }} ({{ $batch->sale->customer_relation->customer_name ?? (!empty($batch->sale->walkin_name) ? $batch->sale->walkin_name : 'Walk-in Customer') }})
                                            </span>
                                            <small class="d-block text-success font-weight-bold mt-1">Status: Ready</small>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">General Warehouse Stock</span>
                                        @endif
                                    </td>
                                    <td class="align-middle small">
                                        <div><strong>Mfg:</strong> {{ $batch->mfg_date ? \Carbon\Carbon::parse($batch->mfg_date)->format('d M Y') : '-' }}</div>
                                        <div><strong>Exp:</strong> {{ $batch->exp_date ? \Carbon\Carbon::parse($batch->exp_date)->format('d M Y') : '-' }}</div>
                                    </td>
                                    <td class="align-middle">
                                        @if($batch->status == 'produced')
                                            <span class="badge badge-info font-weight-bold px-3 py-1">Produced in Stock</span>
                                        @elseif($batch->status == 'ready_for_delivery')
                                            <span class="badge badge-success font-weight-bold px-3 py-1">✅ Ready for Delivery</span>
                                        @elseif($batch->status == 'dispatched')
                                            <span class="badge badge-dark font-weight-bold px-3 py-1">🚚 Dispatched / Stock Out</span>
                                        @endif
                                    </td>
                                    <td class="text-right align-middle">
                                        @if($batch->status == 'ready_for_delivery' || $batch->status == 'produced')
                                            <form action="{{ route('production.dispatch', $batch->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-dark font-weight-bold" onclick="return confirm('Mark this batch as Dispatched / Delivered stock out?')">
                                                    <i class="fa fa-truck mr-1"></i> Stock Out / Deliver
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge badge-secondary">Completed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa fa-cogs fa-2x mb-2 d-block text-secondary"></i>
                                        No production batches created yet. Click <strong>"Produce New Batch"</strong> to start manufacturing.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($batches->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $batches->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

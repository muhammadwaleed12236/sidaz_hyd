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

        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
            <!-- PAGE HEADER -->
            <div class="card-header bg-white py-3 d-flex flex-wrap align-items-center justify-content-between gap-3 border-bottom">
                <div>
                    <h4 class="mb-0 font-weight-bold text-dark">
                        <i class="fa fa-industry text-primary mr-2"></i> Material Requisitions
                    </h4>
                    <span class="text-muted small"><strong>Requisition Rule:</strong> "Humein material chahiye" (Raw material shortage list to acquire stock).</span>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('material-purchases.create') }}" class="btn btn-outline-primary btn-sm font-weight-bold">
                        <i class="fa fa-shopping-cart mr-1"></i> Material Purchase (Purchase Stock)
                    </a>
                    <a href="{{ route('production.create') }}" class="btn btn-success btn-sm font-weight-bold">
                        <i class="fa fa-cogs mr-1"></i> Go to Production ("Material Available Hai")
                    </a>
                </div>
            </div>

            <!-- FILTER TABS -->
            <div class="bg-light px-3 py-2 border-bottom d-flex align-items-center justify-content-between">
                <div class="btn-group">
                    <a href="{{ route('material-requisitions.index', ['status' => 'active']) }}" class="btn btn-sm {{ ($status ?? 'active') === 'active' ? 'btn-primary font-weight-bold' : 'btn-outline-secondary' }}">
                        <i class="fa fa-clock-o mr-1"></i> Active Requisitions
                    </a>
                    <a href="{{ route('material-requisitions.index', ['status' => 'fulfilled']) }}" class="btn btn-sm {{ ($status ?? '') === 'fulfilled' ? 'btn-primary font-weight-bold' : 'btn-outline-secondary' }}">
                        <i class="fa fa-check-circle mr-1"></i> Fulfilled
                    </a>
                    <a href="{{ route('material-requisitions.index', ['status' => 'all']) }}" class="btn btn-sm {{ ($status ?? '') === 'all' ? 'btn-primary font-weight-bold' : 'btn-outline-secondary' }}">
                        <i class="fa fa-list mr-1"></i> All
                    </a>
                </div>
            </div>

            <!-- REQUISITIONS TABLE -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="bg-light text-uppercase small text-muted font-weight-bold">
                            <tr>
                                <th>Req #</th>
                                <th>Order / Customer</th>
                                <th class="text-left" style="min-width: 350px;">Raw Material Breakdown Table</th>
                                <th>Stock Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requisitions as $req)
                                @php
                                    $liveShortageCount = 0;
                                    foreach($req->items as $item) {
                                        $currentLiveStock = (float)($item->rawMaterial->current_stock ?? 0);
                                        $liveShortage = max(0, (float)$item->required_qty - $currentLiveStock);
                                        if ($liveShortage > 0) {
                                            $liveShortageCount++;
                                        }
                                    }
                                    $hasLiveShortage = $liveShortageCount > 0;
                                @endphp
                                <tr>
                                    <td class="font-weight-bold text-primary align-middle">
                                        {{ $req->requisition_no }}
                                        <small class="d-block text-muted">{{ $req->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td class="align-middle">
                                        @if($req->sale)
                                            <a href="{{ route('sales.invoice', $req->sale->id) }}" target="_blank" class="font-weight-bold text-dark d-block">
                                                #{{ $req->sale->invoice_no }}
                                            </a>
                                            <small class="text-muted">{{ $req->sale->customer_relation->customer_name ?? (!empty($req->sale->walkin_name) ? $req->sale->walkin_name : 'Walk-in Customer') }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td class="text-left align-middle p-2">
                                        <table class="table table-sm table-bordered mb-0 bg-white" style="font-size:0.8rem;">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-left">Raw Material</th>
                                                    <th class="text-right">Required</th>
                                                    <th class="text-right">Stock</th>
                                                    <th class="text-right">Shortage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($req->items as $item)
                                                    @php
                                                        $liveStock = (float)($item->rawMaterial->current_stock ?? 0);
                                                        $liveShortage = max(0, (float)$item->required_qty - $liveStock);
                                                    @endphp
                                                    <tr class="{{ $liveShortage > 0 ? 'table-warning' : 'table-light' }}">
                                                        <td class="font-weight-bold text-dark">
                                                            {{ $item->rawMaterial->name ?? 'Material' }}
                                                        </td>
                                                        <td class="text-right font-weight-bold">
                                                            {{ (float)$item->required_qty }} {{ $item->unit }}
                                                        </td>
                                                        <td class="text-right text-info font-weight-bold">
                                                            {{ $liveStock }} {{ $item->unit }}
                                                        </td>
                                                        <td class="text-right font-weight-bold {{ $liveShortage > 0 ? 'text-danger' : 'text-success' }}">
                                                            @if($liveShortage > 0)
                                                                {{ $liveShortage }} {{ $item->unit }}
                                                            @else
                                                                0
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="align-middle">
                                        @if($req->status === 'fulfilled')
                                            <span class="badge badge-secondary px-3 py-2">
                                                <i class="fa fa-check-circle mr-1"></i> Production Completed
                                            </span>
                                            <small class="d-block text-muted mt-1">Requisition Fulfilled</small>
                                        @elseif($hasLiveShortage)
                                            <span class="badge badge-danger px-3 py-2">
                                                <i class="fa fa-exclamation-triangle mr-1"></i> Material Shortage
                                            </span>
                                            <small class="d-block text-muted mt-1">Purchase Material First</small>
                                        @else
                                            <span class="badge badge-success px-3 py-2">
                                                <i class="fa fa-check-circle mr-1"></i> Stock Available
                                            </span>
                                            <small class="d-block text-success mt-1">Ready for Production</small>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        @if($req->status === 'fulfilled')
                                            <span class="badge badge-light text-success font-weight-bold p-2 border border-success">
                                                <i class="fa fa-check-circle mr-1"></i> Batch Produced
                                            </span>
                                        @elseif($hasLiveShortage)
                                            <a href="{{ route('material-purchases.create') }}" class="btn btn-sm btn-outline-danger font-weight-bold">
                                                <i class="fa fa-shopping-cart mr-1"></i> Purchase Material
                                            </a>
                                        @else
                                            <a href="{{ route('production.create', ['sale_id' => $req->sale_id]) }}" class="btn btn-sm btn-success font-weight-bold">
                                                <i class="fa fa-cogs mr-1"></i> Produce Batch
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fa fa-info-circle fa-2x mb-2 d-block text-secondary"></i>
                                        No active material requisitions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($requisitions->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $requisitions->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

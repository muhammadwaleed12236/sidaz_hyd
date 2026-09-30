@extends('admin_panel.layout.app')

@section('content')
<link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}">

<style>
    :root {
        --primary-color: #4f46e5;
        --bg-body: #f8fafc;
        --border-color: #e2e8f0;
        --radius-lg: 16px;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-body);
        color: #0f172a;
    }

    .mr-page-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 15px 15px 30px;
    }

    .mr-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }
</style>

<div class="main-content">
    <div class="main-content-inner">
        <div class="container mr-page-container">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="font-weight-bold text-dark mb-1">
                        <i class="fa fa-industry text-primary me-2"></i> Requisition {{ $requisition->requisition_no }}
                    </h3>
                    <p class="text-muted small mb-0">Created on {{ $requisition->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <a href="{{ route('material-requisitions.index') }}" class="btn btn-light btn-sm font-weight-bold border shadow-sm" style="border-radius: 9px;">
                    <i class="fa fa-arrow-left me-1"></i> Back to Requisitions
                </a>
            </div>

            <div class="mr-card mb-4">
                <div class="p-4 border-bottom bg-light">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Requisition No:</span>
                            <span class="font-weight-bold text-primary fs-5">{{ $requisition->requisition_no }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Associated Sales Order:</span>
                            @if($requisition->sale)
                                <a href="{{ route('sales.invoice', $requisition->sale->id) }}" target="_blank" class="font-weight-bold text-dark">
                                    #{{ $requisition->sale->invoice_no }}
                                </a>
                            @else
                                <span class="text-muted font-weight-bold">Direct Production Request</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">Status:</span>
                            <span class="badge bg-primary px-3 py-2 text-uppercase font-weight-bold">{{ $requisition->status }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <h6 class="font-weight-bold text-dark mb-3">
                        <i class="fa fa-flask text-success me-1"></i> Required Raw Materials & Stock Breakdown
                    </h6>

                    <div class="table-responsive border rounded-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                                <tr>
                                    <th class="py-3 px-3">Raw Material Item</th>
                                    <th class="py-3 px-3 text-end">Required Qty</th>
                                    <th class="py-3 px-3 text-end">Live Stock</th>
                                    <th class="py-3 px-3 text-end">Shortage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requisition->items as $item)
                                    @php
                                        $reqQty = (float)$item->required_qty;
                                        $liveStock = (float)($item->rawMaterial->current_stock ?? 0);
                                        $liveShortage = max(0, $reqQty - $liveStock);
                                    @endphp
                                    <tr style="{{ $liveShortage > 0 ? 'background: #fff5f5;' : '' }}">
                                        <td class="py-3 px-3 font-weight-bold text-dark">
                                            {{ $item->rawMaterial->name ?? 'Material' }}
                                        </td>
                                        <td class="py-3 px-3 text-end font-weight-bold text-dark">
                                            {{ $reqQty }} {{ $item->unit }}
                                        </td>
                                        <td class="py-3 px-3 text-end font-weight-bold text-info">
                                            {{ $liveStock }} {{ $item->unit }}
                                        </td>
                                        <td class="py-3 px-3 text-end font-weight-bold {{ $liveShortage > 0 ? 'text-danger' : 'text-success' }}">
                                            @if($liveShortage > 0)
                                                {{ $liveShortage }} {{ $item->unit }}
                                            @else
                                                Available
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

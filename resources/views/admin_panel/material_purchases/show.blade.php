@extends('admin_panel.layout.app')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .mpur-view-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 50px;
    }

    .mpur-view-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 28px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    .mpur-view-title {
        font-weight: 800;
        font-size: 1.35rem;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .info-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 700;
        font-size: 0.92rem;
        color: #0f172a;
    }

    .info-card-body {
        padding: 24px;
    }

    .meta-label {
        font-size: 0.73rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .meta-value {
        font-size: 0.95rem;
        color: #0f172a;
        font-weight: 600;
    }

    /* EXCEL SPREADSHEET TABLE GRID FOR SHOW VIEW */
    .table-excel-view {
        border-collapse: collapse !important;
        width: 100%;
        margin-bottom: 0 !important;
        background-color: #ffffff;
    }

    .table-excel-view th {
        background: #f1f5f9 !important;
        color: #334155 !important;
        font-size: 0.72rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 12px !important;
        border: 1px solid #cbd5e1 !important;
        vertical-align: middle;
        text-align: center;
    }

    .table-excel-view td {
        padding: 10px 12px !important;
        border: 1px solid #cbd5e1 !important;
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .summary-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 0.88rem;
    }

    .summary-item.grand-total {
        border-top: 2px solid #cbd5e1;
        padding-top: 12px;
        margin-top: 10px;
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
    }

    @media print {
        body * { visibility: hidden; }
        .printable-area, .printable-area * { visibility: visible; }
        .printable-area { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
</style>

<div class="mpur-view-page container-fluid px-3 px-md-4 pt-3 printable-area">
    <!-- HEADER BAR -->
    <div class="mpur-view-header d-flex flex-wrap align-items-center justify-content-between gap-3 no-print">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                <i class="fas fa-file-invoice fa-lg"></i>
            </div>
            <div>
                <h3 class="mpur-view-title mb-0">Material Purchase Invoice</h3>
                <span class="text-muted small">Invoice #<strong>{{ $purchase->invoice_no }}</strong> &bull; Recorded {{ \Carbon\Carbon::parse($purchase->created_at)->format('d M, Y h:i A') }}</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" onclick="window.print()" class="btn btn-outline-dark fw-bold px-3">
                <i class="fas fa-print me-1"></i> Print Invoice
            </button>
            <a href="{{ route('material-purchases.edit', $purchase->id) }}" class="btn btn-warning fw-bold text-dark px-3">
                <i class="fas fa-edit me-1"></i> Edit Purchase
            </a>
            <a href="{{ route('material-purchases.index') }}" class="btn btn-outline-secondary fw-semibold px-3">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- MAIN INVOICE DETAILS GRID -->
    <div class="row g-4">
        <!-- VENDOR & PURCHASE META INFO -->
        <div class="col-lg-8">
            <div class="info-card h-100 mb-0">
                <div class="info-card-header">
                    <span><i class="fas fa-building text-primary me-2"></i>Vendor & Purchase Information</span>
                    <div class="d-flex gap-2">
                        @if($purchase->status == 'completed')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 fw-bold" style="font-size:0.75rem;">
                                <i class="fas fa-check-circle me-1"></i> COMPLETED
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 fw-bold" style="font-size:0.75rem;">
                                <i class="fas fa-clock me-1"></i> DRAFT
                            </span>
                        @endif

                        @if($purchase->payment_status == 'Paid')
                            <span class="badge bg-success text-white px-3 py-2 fw-bold" style="font-size:0.75rem;">PAID</span>
                        @elseif($purchase->payment_status == 'Partial')
                            <span class="badge bg-info text-white px-3 py-2 fw-bold" style="font-size:0.75rem;">PARTIAL</span>
                        @else
                            <span class="badge bg-danger text-white px-3 py-2 fw-bold" style="font-size:0.75rem;">UNPAID / PENDING</span>
                        @endif
                    </div>
                </div>
                <div class="info-card-body">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="meta-label">Vendor / Supplier Name</div>
                            <div class="meta-value text-primary fs-5">{{ $purchase->vendor->name ?? 'N/A' }}</div>
                            @if(!empty($purchase->vendor->company_name))
                                <div class="small text-muted fw-semibold">{{ $purchase->vendor->company_name }}</div>
                            @endif
                            @if(!empty($purchase->vendor->phone))
                                <div class="small text-muted"><i class="fas fa-phone me-1"></i>{{ $purchase->vendor->phone }}</div>
                            @endif
                        </div>
                        <div class="col-sm-3">
                            <div class="meta-label">Invoice Number</div>
                            <div class="meta-value font-monospace text-dark">{{ $purchase->invoice_no }}</div>
                        </div>
                        <div class="col-sm-3">
                            <div class="meta-label">Purchase Date</div>
                            <div class="meta-value text-dark">{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</div>
                        </div>

                        <div class="col-sm-4">
                            <div class="meta-label">Material Category Type</div>
                            <div class="meta-value"><span class="badge bg-light text-dark border px-2 py-1">{{ $purchase->purchase_type }}</span></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="meta-label">Payment Method</div>
                            <div class="meta-value text-dark">{{ $purchase->payment_method }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="meta-label">Payment Status</div>
                            <div class="meta-value text-dark">{{ $purchase->payment_status }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TRANSPORT & LOGISTICS INFO -->
        <div class="col-lg-4">
            <div class="info-card h-100 mb-0">
                <div class="info-card-header">
                    <span><i class="fas fa-truck text-secondary me-2"></i>Transport & Logistics</span>
                </div>
                <div class="info-card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="meta-label">Transporter / Logistics Service</div>
                            <div class="meta-value">{{ $purchase->transport_name ?: '-' }}</div>
                        </div>
                        <div class="col-12">
                            <div class="meta-label">Driver Name & Contact</div>
                            <div class="meta-value">{{ $purchase->driver_name ?: '-' }}</div>
                            @if($purchase->driver_contact)
                                <div class="small text-muted"><i class="fas fa-phone me-1"></i>{{ $purchase->driver_contact }}</div>
                            @endif
                        </div>
                        <div class="col-12">
                            <div class="meta-label">Vehicle / Truck No.</div>
                            <div class="meta-value font-monospace">{{ $purchase->vehicle_no ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PURCHASED ITEMS TABLE (EXCEL SPREADSHEET STYLE) -->
    <div class="info-card mt-4">
        <div class="info-card-header">
            <span><i class="fas fa-boxes text-warning me-2"></i>Purchased Material Items List</span>
            <span class="badge bg-secondary rounded-pill px-3 py-1">{{ count($purchase->items) }} Item(s)</span>
        </div>
        <div class="p-0 table-responsive">
            <table class="table-excel-view mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="min-width: 220px;" class="text-start">Material Item Name</th>
                        <th style="width: 100px;">Type</th>
                        <th style="width: 100px;">Batch No</th>
                        <th style="width: 130px;">Mfg / Exp Dates</th>
                        <th style="width: 100px;" class="text-end">Qty</th>
                        <th style="width: 110px;" class="text-end">Unit Price</th>
                        <th style="width: 90px;" class="text-end">Disc</th>
                        <th style="width: 90px;" class="text-end">Tax</th>
                        <th style="width: 120px;" class="text-end">Total Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $index => $item)
                    <tr>
                        <td class="text-center fw-bold text-muted bg-light">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->item->name ?? 'Unknown Item' }}</div>
                            @if(!empty($item->item->code))
                                <div class="small text-muted font-monospace">Code: {{ $item->item->code }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->item_type == \App\Models\RawMaterial::class)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size:0.7rem;">Raw Material</span>
                            @else
                                <span class="badge bg-info-subtle text-info border border-info-subtle" style="font-size:0.7rem;">Packaging</span>
                            @endif
                        </td>
                        <td class="text-center font-monospace">{{ $item->batch_no ?: '-' }}</td>
                        <td class="text-center small">
                            @if($item->mfg_date)
                                <div><span class="text-muted">Mfg:</span> {{ \Carbon\Carbon::parse($item->mfg_date)->format('d/m/Y') }}</div>
                            @endif
                            @if($item->exp_date)
                                <div><span class="text-muted">Exp:</span> {{ \Carbon\Carbon::parse($item->exp_date)->format('d/m/Y') }}</div>
                            @endif
                            @if(!$item->mfg_date && !$item->exp_date)
                                -
                            @endif
                        </td>
                        <td class="text-end fw-bold">
                            {{ number_format($item->qty, 2) }}
                            <small class="text-muted fw-normal ms-1">{{ $item->item->unit->short_name ?? '' }}</small>
                        </td>
                        <td class="text-end font-monospace">Rs. {{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-end text-danger font-monospace">
                            {{ $item->discount > 0 ? '- Rs. ' . number_format($item->discount, 2) : '-' }}
                        </td>
                        <td class="text-end text-info font-monospace">
                            {{ $item->tax > 0 ? '+ Rs. ' . number_format($item->tax, 2) : '-' }}
                        </td>
                        <td class="text-end fw-bold text-primary font-monospace">Rs. {{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- REMARKS & SUMMARY BREAKDOWN -->
    <div class="row g-4 mt-1">
        <div class="col-lg-7">
            @if(!empty($purchase->remarks))
            <div class="info-card h-100 mb-0">
                <div class="info-card-header">
                    <span><i class="fas fa-comment-alt text-muted me-2"></i>Purchase Remarks / Notes</span>
                </div>
                <div class="info-card-body text-secondary" style="white-space: pre-line;">
                    {{ $purchase->remarks }}
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="summary-box shadow-sm">
                <div class="summary-item">
                    <span class="text-secondary fw-semibold">Items Subtotal:</span>
                    <span class="fw-bold text-dark font-monospace">Rs. {{ number_format($purchase->subtotal, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="text-secondary fw-semibold">Total Line Discount:</span>
                    <span class="fw-bold text-danger font-monospace">- Rs. {{ number_format($purchase->total_discount, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="text-secondary fw-semibold">Total Tax:</span>
                    <span class="fw-bold text-info font-monospace">+ Rs. {{ number_format($purchase->total_tax, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="text-secondary fw-semibold">Freight / Transport Charges:</span>
                    <span class="fw-bold text-secondary font-monospace">+ Rs. {{ number_format($purchase->transport_charges, 2) }}</span>
                </div>
                
                <div class="summary-item grand-total">
                    <span>Grand Total Amount:</span>
                    <span class="text-primary font-monospace">Rs. {{ number_format($purchase->total_amount, 2) }}</span>
                </div>

                <div class="summary-item mt-3 pt-3 border-top">
                    <span class="fw-bold text-dark">Amount Paid Now:</span>
                    <span class="text-success fw-bold fs-6 font-monospace">Rs. {{ number_format($purchase->paid_amount, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="fw-bold text-dark">Remaining Balance Due:</span>
                    <span class="text-danger fw-bold fs-6 font-monospace">Rs. {{ number_format($purchase->balance_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@extends('admin_panel.layout.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center justify-content-between flex-wrap gap-2" role="alert">
                <div>
                    <i class="fa fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
                @if(session('last_generated_invoice_id'))
                    <div>
                        <a href="{{ route('sales.invoice', session('last_generated_invoice_id')) }}" target="_blank" class="btn btn-sm btn-dark font-weight-bold shadow-sm">
                            <i class="fa fa-print mr-1"></i> View / Print Invoice #{{ session('last_generated_invoice_no') }}
                        </a>
                    </div>
                @endif
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
                                <th>Production Cost</th>
                                <th>Customer Demand / Sale Order</th>
                                <th>Mfg & Exp Dates</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                                @php
                                    $cost = $batch->costing;
                                @endphp
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
                                    <td class="align-middle text-left" style="min-width: 175px;">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="text-muted small">Total Cost:</span>
                                            <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">Rs. {{ number_format($cost['grand_total_cost'], 2) }}</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="text-muted small">Per Unit:</span>
                                            <span class="badge badge-success font-weight-bold px-2 py-1" style="font-size: 0.82rem;" title="Cost to produce 1 single finished piece">
                                                Rs. {{ number_format($cost['cost_per_piece'], 2) }} / Pc
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between small text-muted mb-2" style="font-size: 0.72rem;">
                                            <span title="Total Raw Material Cost">RM: Rs. {{ number_format($cost['total_raw_cost'], 0) }}</span>
                                            <span title="Total Packaging Material Cost">PM: Rs. {{ number_format($cost['total_packaging_cost'], 0) }}</span>
                                        </div>
                                        <button type="button" class="btn btn-xs btn-outline-primary btn-block font-weight-bold py-1 shadow-sm" data-toggle="modal" data-target="#costModal_{{ $batch->id }}" data-bs-toggle="modal" data-bs-target="#costModal_{{ $batch->id }}">
                                            <i class="fa fa-calculator mr-1"></i> View Cost Details
                                        </button>
                                    </td>
                                    <td class="align-middle">
                                        @if($batch->sale)
                                            <span class="badge badge-light border text-dark font-weight-bold px-2 py-1">
                                                Order #{{ $batch->sale->invoice_no }} ({{ $batch->sale->customer_relation->customer_name ?? (!empty($batch->sale->walkin_name) ? $batch->sale->walkin_name : 'Walk-in Customer') }})
                                            </span>
                                            <small class="d-block text-success font-weight-bold mt-1">Status: {{ ucfirst($batch->sale->sale_status ?? 'Ready') }}</small>
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
                                        <div class="d-inline-flex align-items-center gap-1 flex-wrap justify-content-end">
                                            <button type="button" class="btn btn-sm btn-outline-info font-weight-bold py-1 px-2" data-toggle="modal" data-target="#costModal_{{ $batch->id }}" data-bs-toggle="modal" data-bs-target="#costModal_{{ $batch->id }}" title="View Batch Costing Breakdown">
                                                <i class="fa fa-calculator"></i>
                                            </button>
                                            @if($batch->status == 'dispatched')
                                                <span class="badge badge-secondary px-2 py-1">Completed</span>
                                                @if($batch->sale_id)
                                                    <a href="{{ route('sales.invoice', $batch->sale_id) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2 font-weight-bold" title="View / Print Invoice">
                                                        <i class="fa fa-print me-1"></i> #{{ $batch->sale->invoice_no ?? $batch->sale_id }}
                                                    </a>
                                                @endif
                                            @elseif($batch->sale_id && ($batch->status == 'ready_for_delivery' || $batch->status == 'produced'))
                                                <!-- Linked to Customer Order -> Stock Out / Deliver button -->
                                                <form action="{{ route('production.dispatch', $batch->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-dark font-weight-bold shadow-sm" onclick="return confirm('Mark this batch as Dispatched / Delivered stock out for Order #{{ $batch->sale->invoice_no ?? '' }}?')">
                                                        <i class="fa fa-truck mr-1"></i> Deliver
                                                    </button>
                                                </form>
                                                @if($batch->sale_id)
                                                    <a href="{{ route('sales.invoice', $batch->sale_id) }}" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2" title="View Order Details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endif
                                            @elseif(!$batch->sale_id && $batch->status == 'produced')
                                                <!-- General Warehouse Stock -> NO blind stock out! Button to Generate Invoice / Sell -->
                                                <button type="button" class="btn btn-sm btn-success font-weight-bold shadow-sm" data-toggle="modal" data-target="#invoiceModal_{{ $batch->id }}" data-bs-toggle="modal" data-bs-target="#invoiceModal_{{ $batch->id }}">
                                                    <i class="fa fa-file-invoice-dollar mr-1"></i> Invoice / Sell
                                                </button>
                                            @else
                                                <span class="badge badge-secondary">In Stock</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
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

<!-- MODALS FOR GENERAL WAREHOUSE STOCK BATCHES -->
@foreach($batches as $batch)
    @if(!$batch->sale_id && $batch->status == 'produced')
        @php
            $prodPrice = (float)($batch->product->sale_price_per_piece ?: ($batch->product->retail_price ?: ($batch->product->wholesale_price ?: 0)));
            $defaultTotal = round((float)$batch->quantity * $prodPrice, 2);
        @endphp
        <div class="modal fade" id="invoiceModal_{{ $batch->id }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel_{{ $batch->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <form action="{{ route('production.generate-invoice', $batch->id) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-success text-white py-2 px-3">
                            <h6 class="modal-title font-weight-bold mb-0" id="modalLabel_{{ $batch->id }}">
                                <i class="fa fa-file-invoice-dollar mr-1"></i> Generate Invoice & Stock Out
                            </h6>
                            <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.4rem;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-3 text-left">
                            <div class="alert alert-light border py-2 px-3 mb-3 small">
                                <div><strong>Product:</strong> {{ $batch->product->item_name }} ({{ $batch->product->item_code }})</div>
                                <div class="mt-1">
                                    <strong>Batch:</strong> <span class="badge badge-primary">{{ $batch->batch_no }}</span> &bull; 
                                    <strong>Available in Batch:</strong> <span class="badge badge-success">{{ (float)$batch->quantity }} Pcs</span>
                                </div>
                            </div>

                            <!-- Customer Type Selection -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold small mb-1">Customer Type <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="customer_type" id="modal_cust_reg_{{ $batch->id }}" value="registered" checked onchange="toggleModalCustomerType({{ $batch->id }}, 'registered')">
                                        <label class="form-check-label small font-weight-bold" for="modal_cust_reg_{{ $batch->id }}">Registered Customer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="customer_type" id="modal_cust_walkin_{{ $batch->id }}" value="walkin" onchange="toggleModalCustomerType({{ $batch->id }}, 'walkin')">
                                        <label class="form-check-label small font-weight-bold" for="modal_cust_walkin_{{ $batch->id }}">Walk-in Customer</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Registered Customer Dropdown -->
                            <div class="mb-3" id="modal_reg_box_{{ $batch->id }}">
                                <label class="form-label small font-weight-bold mb-1">Choose Customer <span class="text-danger">*</span></label>
                                <select name="customer_id" id="modal_cust_id_{{ $batch->id }}" class="form-control form-control-sm select2">
                                    <option value="">-- Select Customer --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->customer_name }} ({{ $c->mobile ?? 'No Mobile' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Walk-in Customer Input -->
                            <div class="mb-3" id="modal_walkin_box_{{ $batch->id }}" style="display: none;">
                                <label class="form-label small font-weight-bold mb-1">Customer / Party Name <span class="text-danger">*</span></label>
                                <input type="text" name="walkin_name" id="modal_walkin_name_{{ $batch->id }}" class="form-control form-control-sm" placeholder="Enter customer name e.g. Dr. Salman / Al-Razi Pharmacy">
                            </div>

                            <!-- Quantity & Price Row -->
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label small font-weight-bold mb-1">Invoice Qty (Pcs) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" min="0.0001" max="{{ (float)$batch->quantity }}" name="quantity" id="modal_qty_{{ $batch->id }}" class="form-control form-control-sm font-weight-bold text-success" value="{{ (float)$batch->quantity }}" required oninput="calcModalNet({{ $batch->id }})">
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Batch Available: {{ (float)$batch->quantity }} Pcs</small>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small font-weight-bold mb-1">Unit Price (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" name="unit_price" id="modal_price_{{ $batch->id }}" class="form-control form-control-sm font-weight-bold" value="{{ $prodPrice }}" required oninput="calcModalNet({{ $batch->id }})">
                                </div>
                            </div>

                            <!-- Discount & Net Amount Row -->
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label small font-weight-bold mb-1">Discount (PKR)</label>
                                    <input type="number" step="0.01" min="0" name="discount" id="modal_disc_{{ $batch->id }}" class="form-control form-control-sm" value="0" oninput="calcModalNet({{ $batch->id }})">
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label small font-weight-bold mb-1">Net Bill Amount</label>
                                    <div class="form-control form-control-sm font-weight-bold text-primary bg-light" id="modal_net_{{ $batch->id }}">Rs. {{ number_format($defaultTotal, 2) }}</div>
                                </div>
                            </div>

                            <!-- Payment Option -->
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="paid_now" id="modal_paid_{{ $batch->id }}" value="1">
                                <label class="form-check-label small" for="modal_paid_{{ $batch->id }}">
                                    <strong>Payment Received (Cash):</strong> Mark invoice fully paid now
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer bg-light py-2 px-3 d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success btn-sm font-weight-bold px-3">
                                <i class="fa fa-check mr-1"></i> Create Invoice & Stock Out
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

<!-- MODALS FOR DETAILED BATCH COSTING BREAKDOWN -->
@foreach($batches as $batch)
    @php
        $cost = $batch->costing;
        $batchQty = (float)$batch->quantity > 0 ? (float)$batch->quantity : 1;
    @endphp
    <div class="modal fade" id="costModal_{{ $batch->id }}" tabindex="-1" role="dialog" aria-labelledby="costModalLabel_{{ $batch->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                <!-- MODAL HEADER -->
                <div class="modal-header py-3 px-3 px-md-4 text-white d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <div>
                        <h5 class="modal-title font-weight-bold mb-1 text-white" id="costModalLabel_{{ $batch->id }}">
                            <i class="fa fa-calculator mr-2 text-warning"></i> Production Costing & Recipe Sheet
                        </h5>
                        <div class="small text-light" style="opacity: 0.9;">
                            Batch: <strong class="badge badge-warning text-dark px-2">{{ $batch->batch_no }}</strong> &bull; 
                            Product: <strong>{{ $batch->product->item_name ?? 'N/A' }}</strong>
                        </div>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="background:none; border:none; font-size:1.6rem; opacity:0.9;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- MODAL BODY -->
                <div class="modal-body p-3 p-md-4 text-left" id="printableCost_{{ $batch->id }}" style="background-color: #f8fafc;">
                    
                    <!-- BATCH HEADER INFO CARD -->
                    <div class="card border-0 shadow-sm rounded-lg mb-3" style="background: #ffffff;">
                        <div class="card-body p-3">
                            <div class="row align-items-center text-center text-md-left">
                                <div class="col-12 col-md-6 mb-2 mb-md-0 border-md-right">
                                    <div class="text-muted small text-uppercase font-weight-bold">Finished Product</div>
                                    <h5 class="font-weight-bold text-dark mb-0 mt-1">
                                        {{ $batch->product->item_name ?? 'N/A' }}
                                    </h5>
                                    <div class="small text-muted mt-1">
                                        <span>Code: <strong>{{ $batch->product->item_code ?? '-' }}</strong></span> &bull; 
                                        <span>Warehouse: <strong>{{ $batch->warehouse->name ?? 'Default Warehouse' }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 text-md-right">
                                    <div class="text-muted small text-uppercase font-weight-bold">Batch Output Quantity</div>
                                    <div class="font-weight-bold text-success my-1" style="font-size: 1.4rem;">
                                        {{ number_format((float)$batch->quantity, 2) }} <span style="font-size: 0.95rem;">Pieces</span>
                                    </div>
                                    <div class="small text-muted">
                                        <span>Mfg: <strong>{{ $batch->mfg_date ? \Carbon\Carbon::parse($batch->mfg_date)->format('d M Y') : '-' }}</strong></span> &bull; 
                                        <span>Exp: <strong>{{ $batch->exp_date ? \Carbon\Carbon::parse($batch->exp_date)->format('d M Y') : '-' }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4 KEY KPI STATS CARDS (MOBILE RESPONSIVE 2X2 OR 4 COLUMNS) -->
                    <div class="row mb-3 g-2">
                        <!-- KPI 1: Cost Per Piece (PROMINENT) -->
                        <div class="col-6 col-lg-3 mb-2">
                            <div class="card border-0 shadow-sm h-100 p-2 text-center" style="background: #e8f5e9; border-left: 4px solid #2e7d32 !important; border-radius: 8px;">
                                <div class="text-muted small font-weight-bold">Cost Per Single Piece</div>
                                <div class="text-success font-weight-bold mt-1" style="font-size: 1.25rem;">
                                    Rs. {{ number_format($cost['cost_per_piece'], 2) }}
                                </div>
                                <div class="small text-muted" style="font-size: 0.7rem;">1 unit product cost</div>
                            </div>
                        </div>

                        <!-- KPI 2: Total Batch Cost -->
                        <div class="col-6 col-lg-3 mb-2">
                            <div class="card border-0 shadow-sm h-100 p-2 text-center" style="background: #e3f2fd; border-left: 4px solid #1976d2 !important; border-radius: 8px;">
                                <div class="text-muted small font-weight-bold">Total Batch Cost</div>
                                <div class="text-primary font-weight-bold mt-1" style="font-size: 1.25rem;">
                                    Rs. {{ number_format($cost['grand_total_cost'], 2) }}
                                </div>
                                <div class="small text-muted" style="font-size: 0.7rem;">Combined material cost</div>
                            </div>
                        </div>

                        <!-- KPI 3: Total Raw Material Cost -->
                        <div class="col-6 col-lg-3 mb-2">
                            <div class="card border-0 shadow-sm h-100 p-2 text-center" style="background: #fff8e1; border-left: 4px solid #f57f17 !important; border-radius: 8px;">
                                <div class="text-muted small font-weight-bold">Raw Material Cost</div>
                                <div class="text-dark font-weight-bold mt-1" style="font-size: 1.15rem;">
                                    Rs. {{ number_format($cost['total_raw_cost'], 2) }}
                                </div>
                                <div class="small text-muted" style="font-size: 0.7rem;">{{ count($cost['raw_materials']) }} Raw Materials</div>
                            </div>
                        </div>

                        <!-- KPI 4: Total Packaging Cost -->
                        <div class="col-6 col-lg-3 mb-2">
                            <div class="card border-0 shadow-sm h-100 p-2 text-center" style="background: #f3e5f5; border-left: 4px solid #7b1fa2 !important; border-radius: 8px;">
                                <div class="text-muted small font-weight-bold">Packaging Cost</div>
                                <div class="text-dark font-weight-bold mt-1" style="font-size: 1.15rem;">
                                    Rs. {{ number_format($cost['total_packaging_cost'], 2) }}
                                </div>
                                <div class="small text-muted" style="font-size: 0.7rem;">{{ count($cost['packaging_materials']) }} Packaging Items</div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 1: RAW MATERIALS BREAKDOWN -->
                    <div class="card border-0 shadow-sm rounded-lg mb-3" style="background: #ffffff;">
                        <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                            <div class="font-weight-bold text-dark">
                                <i class="fa fa-flask text-warning mr-2"></i> 1. Raw Materials Consumed (خام مال کی کھپت اور لاگت)
                            </div>
                            <span class="badge badge-light border text-dark font-weight-bold">
                                Subtotal: Rs. {{ number_format($cost['total_raw_cost'], 2) }}
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0 align-middle text-center" style="font-size: 0.85rem;">
                                    <thead class="bg-light text-muted font-weight-bold small text-uppercase">
                                        <tr>
                                            <th style="width: 40px;">#</th>
                                            <th class="text-left">Raw Material Name</th>
                                            <th>Batch Consumed</th>
                                            <th class="text-info">Qty In 1 Piece</th>
                                            <th>Rate / Unit</th>
                                            <th class="text-right">Total Cost</th>
                                            <th class="text-right text-success">Cost / 1 Piece</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($cost['raw_materials'] as $rm)
                                            <tr>
                                                <td class="text-muted">{{ $loop->iteration }}</td>
                                                <td class="text-left font-weight-bold text-dark">
                                                    {{ $rm['name'] }}
                                                </td>
                                                <td class="font-weight-bold">
                                                    {{ number_format($rm['consumed_qty'], 4) }} <small class="text-muted">{{ $rm['unit'] }}</small>
                                                </td>
                                                <td class="text-info font-weight-bold">
                                                    {{ number_format($rm['qty_per_piece'], 6) }} <small>{{ $rm['unit'] }}</small>
                                                </td>
                                                <td>
                                                    Rs. {{ number_format($rm['rate'], 2) }}
                                                </td>
                                                <td class="text-right font-weight-bold text-dark">
                                                    Rs. {{ number_format($rm['total_cost'], 2) }}
                                                </td>
                                                <td class="text-right font-weight-bold text-success">
                                                    Rs. {{ number_format($rm['cost_per_piece'], 4) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-3 text-muted">
                                                    No raw materials movement recorded for this batch.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-light font-weight-bold">
                                        <tr>
                                            <td colspan="5" class="text-right text-dark">Total Raw Material Cost:</td>
                                            <td class="text-right text-primary">Rs. {{ number_format($cost['total_raw_cost'], 2) }}</td>
                                            <td class="text-right text-success">
                                                Rs. {{ number_format($cost['total_raw_cost'] / $batchQty, 4) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: PACKAGING MATERIALS BREAKDOWN -->
                    <div class="card border-0 shadow-sm rounded-lg mb-3" style="background: #ffffff;">
                        <div class="card-header bg-white py-2 px-3 border-bottom d-flex align-items-center justify-content-between">
                            <div class="font-weight-bold text-dark">
                                <i class="fa fa-box-open text-info mr-2"></i> 2. Packaging Materials Consumed (پیکنگ میٹریل لاگت)
                            </div>
                            <span class="badge badge-light border text-dark font-weight-bold">
                                Subtotal: Rs. {{ number_format($cost['total_packaging_cost'], 2) }}
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm mb-0 align-middle text-center" style="font-size: 0.85rem;">
                                    <thead class="bg-light text-muted font-weight-bold small text-uppercase">
                                        <tr>
                                            <th style="width: 40px;">#</th>
                                            <th class="text-left">Packaging Item</th>
                                            <th>Batch Consumed</th>
                                            <th class="text-info">Qty In 1 Piece</th>
                                            <th>Rate / Unit</th>
                                            <th class="text-right">Total Cost</th>
                                            <th class="text-right text-success">Cost / 1 Piece</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($cost['packaging_materials'] as $pm)
                                            <tr>
                                                <td class="text-muted">{{ $loop->iteration }}</td>
                                                <td class="text-left font-weight-bold text-dark">
                                                    {{ $pm['name'] }}
                                                </td>
                                                <td class="font-weight-bold">
                                                    {{ number_format($pm['consumed_qty'], 2) }} <small class="text-muted">{{ $pm['unit'] }}</small>
                                                </td>
                                                <td class="text-info font-weight-bold">
                                                    {{ number_format($pm['qty_per_piece'], 4) }} <small>{{ $pm['unit'] }}</small>
                                                </td>
                                                <td>
                                                    Rs. {{ number_format($pm['rate'], 2) }}
                                                </td>
                                                <td class="text-right font-weight-bold text-dark">
                                                    Rs. {{ number_format($pm['total_cost'], 2) }}
                                                </td>
                                                <td class="text-right font-weight-bold text-success">
                                                    Rs. {{ number_format($pm['cost_per_piece'], 4) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-3 text-muted">
                                                    No packaging materials movement recorded for this batch.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="bg-light font-weight-bold">
                                        <tr>
                                            <td colspan="5" class="text-right text-dark">Total Packaging Cost:</td>
                                            <td class="text-right text-primary">Rs. {{ number_format($cost['total_packaging_cost'], 2) }}</td>
                                            <td class="text-right text-success">
                                                Rs. {{ number_format($cost['total_packaging_cost'] / $batchQty, 4) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: GRAND COSTING SUMMARY & PIECE FORMULA -->
                    <div class="card border-0 shadow-sm rounded-lg" style="background: #ffffff; border: 1px solid #e0e0e0 !important;">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-7 mb-2 mb-md-0">
                                    <h6 class="font-weight-bold text-dark mb-2">
                                        <i class="fa fa-info-circle text-primary mr-1"></i> Cost Calculation Formula:
                                    </h6>
                                    <div class="small text-muted">
                                        <div class="d-flex justify-content-between py-1 border-bottom">
                                            <span>Subtotal Raw Materials:</span>
                                            <span class="font-weight-bold text-dark">Rs. {{ number_format($cost['total_raw_cost'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 border-bottom">
                                            <span>Subtotal Packaging Materials:</span>
                                            <span class="font-weight-bold text-dark">+ Rs. {{ number_format($cost['total_packaging_cost'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1 border-bottom">
                                            <span class="text-primary font-weight-bold">Grand Total Production Batch Cost:</span>
                                            <span class="text-primary font-weight-bold">= Rs. {{ number_format($cost['grand_total_cost'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between py-1">
                                            <span>Divided by Produced Output:</span>
                                            <span class="font-weight-bold">&divide; {{ number_format((float)$batch->quantity, 0) }} Pcs</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-5 text-center bg-light p-3 rounded">
                                    <div class="text-uppercase small font-weight-bold text-muted">Single Product Cost</div>
                                    <div class="font-weight-bold text-success my-1" style="font-size: 1.8rem;">
                                        Rs. {{ number_format($cost['cost_per_piece'], 2) }}
                                    </div>
                                    <div class="small text-muted font-weight-bold">
                                        (فی پروڈکٹ کی کل تیاری لاگت)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- MODAL FOOTER -->
                <div class="modal-footer bg-light py-2 px-3 d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-outline-dark btn-sm font-weight-bold" onclick="printCostSheet('printableCost_{{ $batch->id }}', '{{ $batch->batch_no }}', '{{ addslashes($batch->product->item_name ?? '') }}')">
                        <i class="fa fa-print mr-1"></i> Print Cost Sheet
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('js')
<script>
    function toggleModalCustomerType(batchId, type) {
        if (type === 'walkin') {
            $('#modal_reg_box_' + batchId).hide();
            $('#modal_walkin_box_' + batchId).show();
            $('#modal_cust_id_' + batchId).val('').trigger('change.select2');
        } else {
            $('#modal_reg_box_' + batchId).show();
            $('#modal_walkin_box_' + batchId).hide();
            $('#modal_walkin_name_' + batchId).val('');
        }
    }

    function calcModalNet(batchId) {
        const qty = parseFloat($('#modal_qty_' + batchId).val()) || 0;
        const price = parseFloat($('#modal_price_' + batchId).val()) || 0;
        const disc = parseFloat($('#modal_disc_' + batchId).val()) || 0;
        const total = Math.max(0, (qty * price) - disc);
        $('#modal_net_' + batchId).text('Rs. ' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }

    function printCostSheet(divId, batchNo, productName) {
        const content = document.getElementById(divId).innerHTML;
        const printWindow = window.open('', '', 'height=750,width=950');
        printWindow.document.write('<!DOCTYPE html><html><head><title>Batch Cost Sheet - ' + batchNo + '</title>');
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">');
        printWindow.document.write('<style>body{padding:25px; font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;} .table td, .table th{padding:6px; font-size:12px;} .card{border:1px solid #ddd !important; margin-bottom:15px;} .badge{border:1px solid #ccc; padding:3px 6px;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">');
        printWindow.document.write('  <div><h3 class="mb-0 font-weight-bold">SIDAZ PHARMACEUTICALS</h3><p class="text-muted mb-0">Manufacturing & Batch Costing Sheet</p></div>');
        printWindow.document.write('  <div class="text-right"><strong>Batch: ' + batchNo + '</strong><br><small>' + productName + '</small></div>');
        printWindow.document.write('</div>');
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        setTimeout(function() {
            printWindow.print();
            printWindow.close();
        }, 500);
    }
</script>
@endsection

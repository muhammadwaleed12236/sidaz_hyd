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
                                        @if($batch->status == 'dispatched')
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <span class="badge badge-secondary px-2 py-1">Completed</span>
                                                @if($batch->sale_id)
                                                    <a href="{{ route('sales.invoice', $batch->sale_id) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2 font-weight-bold" title="View / Print Invoice">
                                                        <i class="fa fa-print me-1"></i> #{{ $batch->sale->invoice_no ?? $batch->sale_id }}
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif($batch->sale_id && ($batch->status == 'ready_for_delivery' || $batch->status == 'produced'))
                                            <!-- Linked to Customer Order -> Stock Out / Deliver button -->
                                            <div class="d-inline-flex align-items-center gap-1">
                                                <form action="{{ route('production.dispatch', $batch->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-dark font-weight-bold shadow-sm" onclick="return confirm('Mark this batch as Dispatched / Delivered stock out for Order #{{ $batch->sale->invoice_no ?? '' }}?')">
                                                        <i class="fa fa-truck mr-1"></i> Stock Out / Deliver
                                                    </button>
                                                </form>
                                                @if($batch->sale_id)
                                                    <a href="{{ route('sales.invoice', $batch->sale_id) }}" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2" title="View Order Details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif(!$batch->sale_id && $batch->status == 'produced')
                                            <!-- General Warehouse Stock -> NO blind stock out! Button to Generate Invoice / Sell -->
                                            <button type="button" class="btn btn-sm btn-success font-weight-bold shadow-sm" data-toggle="modal" data-target="#invoiceModal_{{ $batch->id }}" data-bs-toggle="modal" data-bs-target="#invoiceModal_{{ $batch->id }}">
                                                <i class="fa fa-file-invoice-dollar mr-1"></i> Generate Invoice / Sell
                                            </button>
                                        @else
                                            <span class="badge badge-secondary">In Stock</span>
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
</script>
@endsection

@extends('admin_panel.layout.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .prod-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        padding-bottom: 50px;
    }

    .prod-header {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 20px 28px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
    }

    .prod-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .card-section-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1e293b;
        padding-bottom: 10px;
        border-bottom: 2px dashed #e2e8f0;
        margin-bottom: 20px;
    }
</style>

<div class="prod-page container-fluid px-3 px-md-4 pt-3">
    <!-- HEADER BAR -->
    <div class="prod-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-success text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width:48px; height:48px;">
                <i class="fas fa-cogs fa-lg"></i>
            </div>
            <div>
                <h3 class="font-weight-bold mb-0 text-dark">Produce Batch / Manufacturing</h3>
                <span class="text-muted small"><strong>Production Rule:</strong> "Material available hai, ab product banana hai"</span>
            </div>
        </div>

        <a href="{{ route('production.index') }}" class="btn btn-outline-secondary px-4 font-weight-bold" style="border-radius: 10px;">
            <i class="fas fa-arrow-left me-1"></i> Back to Production Batches
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="fa fa-exclamation-triangle me-2"></i> <strong>Production Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="prod-card">
        <form action="{{ route('production.store') }}" method="POST" id="productionForm">
            @csrf
            <div class="p-4">
                
                <div class="card-section-title d-flex align-items-center gap-2">
                    <i class="fas fa-boxes text-success"></i> 1. Product & Demand Selection
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small font-weight-bold">Select Product to Produce <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-control select2" required onchange="onProductChange()">
                            <option value="">Select Finished Product</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ $selectedProductId == $p->id ? 'selected' : '' }}>
                                    {{ $p->item_name }} ({{ $p->item_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small font-weight-bold">Customer Order / Demand (Optional)</label>
                        <select name="sale_id" id="sale_id" class="form-control select2" onchange="onSaleChange()">
                            <option value="">-- No Customer Order (General Warehouse Production) --</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ $selectedSaleId == $s->id ? 'selected' : '' }}>
                                    Order #{{ $s->invoice_no }} — {{ $s->customer_relation->customer_name ?? $s->walkin_name ?? 'Walk-in' }} (Total: {{ $s->total_items }} Pcs)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">If selected, this batch will be linked to customer demand & status marked <strong>"Ready for Delivery"</strong>.</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small font-weight-bold">Quantity to Produce (Pcs) <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="quantity" id="quantity" class="form-control font-weight-bold text-success" placeholder="e.g. 100" required value="100">
                    </div>
                </div>

                <div class="card-section-title d-flex align-items-center gap-2">
                    <i class="fas fa-barcode text-success"></i> 2. Batch & Expiry Details
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small font-weight-bold">Batch Number <span class="text-danger">*</span></label>
                        <input type="text" name="batch_no" class="form-control font-weight-bold" value="{{ $nextBatchNo }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small font-weight-bold">Manufacturing Date (Mfg) <span class="text-danger">*</span></label>
                        <input type="date" name="mfg_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small font-weight-bold">Expiry Date (Exp) <span class="text-danger">*</span></label>
                        <input type="date" name="exp_date" class="form-control" value="{{ date('Y-m-d', strtotime('+2 years')) }}" required>
                    </div>
                </div>

            </div>

            <!-- ACTION FOOTER -->
            <div class="bg-light px-4 py-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small"><i class="fas fa-info-circle text-success me-1"></i> Production deducts raw material stock & adds finished product batch to warehouse.</span>
                <button type="submit" class="btn btn-success px-5 font-weight-bold py-2 shadow-sm" style="border-radius: 10px; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); border: none;">
                    <i class="fas fa-check-circle me-1"></i> Produce Batch Now
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });
    });
</script>
@endsection

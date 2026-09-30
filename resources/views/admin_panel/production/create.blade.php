@extends('admin_panel.layout.app')

@section('content')
<link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}">

<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #4338ca;
        --success-color: #10b981;
        --danger-color: #f43f5e;
        --bg-body: #f8fafc;
        --border-color: #e2e8f0;
        --radius-md: 10px;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-body);
        color: #0f172a;
    }

    /* Remove top margin/gap */
    .main-content, .main-content-inner, .content-wrapper {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .prod-page-wrapper {
        padding: 10px 15px 30px !important;
        max-width: 100%;
    }

    /* Header Bar */
    .prod-header-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 12px 20px;
        margin-bottom: 14px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .prod-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Main Form Card */
    .prod-form-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .card-section-title {
        font-weight: 700;
        font-size: 0.88rem;
        color: #1e293b;
        padding-bottom: 8px;
        border-bottom: 1px dashed var(--border-color);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Form Controls Styling */
    .form-label-compact {
        font-size: 0.78rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 4px;
    }

    .form-control-compact {
        font-size: 0.82rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 7px 12px;
    }

    .form-control-compact:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.12);
    }

    /* Recipe Audit Table Styling */
    .recipe-audit-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 16px;
        margin-top: 10px;
    }

    .mini-audit-table {
        width: 100%;
        font-size: 0.78rem;
        margin-bottom: 0;
    }

    .mini-audit-table th {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .mini-audit-table td {
        padding: 7px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
</style>

<div class="prod-page-wrapper">
    <!-- HEADER BAR -->
    <div class="prod-header-card">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm" style="width:42px; height:42px; background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;">
                <i class="fas fa-cogs"></i>
            </div>
            <div>
                <h3 class="prod-title">Produce Batch / Manufacturing</h3>
                <span class="text-muted small" style="font-size: 0.78rem;"><strong>Production Rule:</strong> Deducts raw material stock & manufactures finished product batch into warehouse</span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('material-requisitions.index') }}" class="btn btn-outline-primary btn-sm font-weight-bold px-3 py-1" style="border-radius: 6px; font-size: 0.78rem;">
                <i class="fa fa-list-alt me-1"></i> Material Requisitions
            </a>
            <a href="{{ route('production.index') }}" class="btn btn-outline-secondary btn-sm font-weight-bold px-3 py-1" style="border-radius: 6px; font-size: 0.78rem;">
                <i class="fas fa-arrow-left me-1"></i> Back to Production Batches
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2" role="alert" style="border-radius: 8px; background: #fff5f5; border-left: 4px solid #ef4444 !important;">
            <div>
                <i class="fa fa-exclamation-triangle me-2 text-danger fs-5"></i> 
                <strong>Production Stock Alert:</strong> {{ session('error') }}
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('material-purchases.create') }}" class="btn btn-sm btn-danger font-weight-bold px-3 py-1 shadow-sm" style="border-radius: 6px; font-size: 0.76rem;">
                    <i class="fas fa-shopping-cart me-1"></i> Purchase Raw Material
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="prod-form-card">
        <form action="{{ route('production.store') }}" method="POST" id="productionForm">
            @csrf
            <div class="p-3 p-md-4">
                
                <!-- SECTION 1: PRODUCT & DEMAND SELECTION -->
                <div class="card-section-title">
                    <i class="fas fa-boxes text-success"></i> 1. Product & Demand Selection
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label-compact">Select Product to Produce <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-control select2" required onchange="onProductChange()">
                            <option value="">-- Select Finished Product --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ $selectedProductId == $p->id ? 'selected' : '' }}>
                                    {{ $p->item_name }} ({{ $p->item_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label-compact">Customer Order / Demand (Optional)</label>
                        <select name="sale_id" id="sale_id" class="form-control select2" onchange="onSaleChange()">
                            <option value="">-- No Customer Order (General Warehouse Production) --</option>
                            @foreach($sales as $s)
                                <option value="{{ $s->id }}" {{ $selectedSaleId == $s->id ? 'selected' : '' }}>
                                    Order #{{ $s->invoice_no }} — {{ $s->customer_relation->customer_name ?? $s->walkin_name ?? 'Walk-in' }} (Total: {{ $s->total_items }} Pcs)
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">If linked to customer order, batch status will automatically mark <strong>"Ready for Delivery"</strong> upon completion.</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label-compact">Quantity to Produce (Pcs) <span class="text-danger">*</span></label>
                        <input type="number" step="0.0001" name="quantity" id="quantity" class="form-control form-control-compact font-weight-bold text-success" placeholder="e.g. 100" required value="100" oninput="updateRecipeAudit()">
                    </div>
                </div>

                <!-- DEMAND BANNER FOR PARTIAL BATCH TRACKING -->
                <div id="saleOrderDemandBanner" style="display: none;"></div>

                <!-- LIVE BOM RECIPE & STOCK AUDIT PREVIEW -->
                <div id="recipeAuditContainer" class="mb-4" style="display: none;">
                    <div class="recipe-audit-box">
                        <div class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa fa-flask text-primary"></i>
                                <span class="font-weight-bold text-dark small" id="auditTitle">BOM Recipe Stock Readiness Audit</span>
                                <span class="text-muted" style="font-size: 0.72rem;" id="auditBatchSizeLabel"></span>
                            </div>
                            <div id="auditSummaryBadge"></div>
                        </div>

                        <div class="table-responsive">
                            <table class="table mini-audit-table">
                                <thead>
                                    <tr>
                                        <th>Raw Material Ingredient</th>
                                        <th class="text-end">Standard Recipe Qty</th>
                                        <th class="text-end">Batch Required Qty</th>
                                        <th class="text-end">Current Stock</th>
                                        <th class="text-end">Shortage / Delta Status</th>
                                    </tr>
                                </thead>
                                <tbody id="auditTableBody">
                                    <!-- Dynamic rows injected via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: BATCH & EXPIRY METADATA -->
                <div class="card-section-title">
                    <i class="fas fa-barcode text-success"></i> 2. Batch & Expiry Details
                </div>

                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <label class="form-label-compact">Batch Number <span class="text-danger">*</span></label>
                        <input type="text" name="batch_no" class="form-control form-control-compact font-weight-bold text-primary" value="{{ $nextBatchNo }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-compact">Manufacturing Date (Mfg) <span class="text-danger">*</span></label>
                        <input type="date" name="mfg_date" class="form-control form-control-compact" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label-compact">Expiry Date (Exp) <span class="text-danger">*</span></label>
                        <input type="date" name="exp_date" class="form-control form-control-compact" value="{{ date('Y-m-d', strtotime('+2 years')) }}" required>
                    </div>
                </div>

            </div>

            <!-- ACTION FOOTER -->
            <div class="bg-light px-4 py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="text-muted small" style="font-size: 0.78rem;">
                    <i class="fas fa-info-circle text-success me-1"></i> Production deducts raw material stock & adds finished product batch to warehouse.
                </span>
                <button type="submit" class="btn btn-success px-4 font-weight-bold py-2 shadow-sm" style="border-radius: 8px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 0.85rem;">
                    <i class="fas fa-check-circle me-1"></i> Produce Batch Now
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    const formulationsData = @json($formulations);
    const salesData = @json($sales);

    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });
        
        // Auto-select product and quantity if sale_id is pre-selected in URL
        if ($('#sale_id').val()) {
            onSaleChange(false);
        }
        
        updateRecipeAudit();
    });

    function onProductChange() {
        updateRecipeAudit();
    }

    function onSaleChange(autoSelectProduct = true) {
        const saleId = $('#sale_id').val();
        const $infoBanner = $('#saleOrderDemandBanner');

        if (!saleId) {
            $infoBanner.slideUp(150).html('');
            updateRecipeAudit();
            return;
        }

        const sale = salesData.find(s => s.id == saleId);
        if (sale && sale.items && sale.items.length > 0) {
            const selectedProdId = $('#product_id').val();
            let targetItem = sale.items.find(i => i.product_id == selectedProdId);
            if (!targetItem) {
                targetItem = sale.items[0];
                if (targetItem.product_id && (autoSelectProduct || !selectedProdId)) {
                    $('#product_id').val(targetItem.product_id).trigger('change.select2');
                }
            }

            const totalOrdered = parseFloat(targetItem.total_ordered_pieces || targetItem.quantity || 0);
            const alreadyProduced = parseFloat(targetItem.already_produced_pieces || 0);
            const remainingNeeded = parseFloat(targetItem.remaining_pieces !== undefined ? targetItem.remaining_pieces : Math.max(0, totalOrdered - alreadyProduced));

            // Auto-fill quantity box with remaining pieces needed!
            if (remainingNeeded > 0) {
                $('#quantity').val(remainingNeeded);
            } else if (totalOrdered > 0) {
                $('#quantity').val(totalOrdered);
            }

            // Display customer order demand banner
            const bannerHtml = `
                <div class="alert alert-info border-0 shadow-sm mb-3 py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="border-radius: 8px; background: #e0f2fe; color: #0369a1; border-left: 4px solid #0284c7 !important; font-size: 0.8rem;">
                    <div>
                        <i class="fa fa-info-circle me-1 fs-6"></i>
                        <strong>Customer Order Demand (#${sale.invoice_no}):</strong>
                        Total Ordered: <strong>${formatQty(totalOrdered)} Pcs</strong> &bull;
                        Already Produced: <span class="badge bg-secondary px-2">${formatQty(alreadyProduced)} Pcs</span> &bull;
                        Remaining Needed: <span class="badge ${remainingNeeded > 0 ? 'bg-danger' : 'bg-success'} px-2">${formatQty(remainingNeeded)} Pcs</span>
                    </div>
                    <div>
                        ${remainingNeeded > 0 
                            ? `<span class="badge bg-warning text-dark font-weight-bold"><i class="fa fa-clock me-1"></i> Partial Order (Auto-set to ${formatQty(remainingNeeded)} Pcs)</span>`
                            : `<span class="badge bg-success font-weight-bold"><i class="fa fa-check me-1"></i> Order Demand 100% Produced</span>`
                        }
                    </div>
                </div>
            `;
            $infoBanner.html(bannerHtml).slideDown(150);
        } else {
            $infoBanner.slideUp(150).html('');
        }
        updateRecipeAudit();
    }

    function formatQty(val) {
        const num = parseFloat(val);
        if (isNaN(num)) return '0';
        return Number(num.toFixed(4)).toString();
    }

    function updateRecipeAudit() {
        const productId = $('#product_id').val();
        const qtyToProduce = parseFloat($('#quantity').val()) || 0;
        const $container = $('#recipeAuditContainer');
        const $tbody = $('#auditTableBody');
        const $badge = $('#auditSummaryBadge');
        const $batchLabel = $('#auditBatchSizeLabel');

        if (!productId || qtyToProduce <= 0) {
            $container.hide();
            return;
        }

        const productFormulations = formulationsData[productId];
        if (!productFormulations || productFormulations.length === 0) {
            $container.show();
            $batchLabel.text('');
            $tbody.html(`
                <tr>
                    <td colspan="5" class="text-center text-warning py-3">
                        <i class="fa fa-exclamation-circle me-1"></i> No active BOM formulation recipe found for this product.
                    </td>
                </tr>
            `);
            $badge.html('<span class="badge bg-warning text-dark">No Recipe Set</span>');
            return;
        }

        const formulation = productFormulations[0];
        const rawMaterials = formulation.raw_materials || [];
        const packagingMaterials = formulation.packaging_materials || [];
        const batchSize = parseFloat(formulation.batch_size) > 0 ? parseFloat(formulation.batch_size) : 1;

        $batchLabel.text(`(Formula Recipe Batch Size: ${formatQty(batchSize)} Pcs)`);

        if (rawMaterials.length === 0 && packagingMaterials.length === 0) {
            $container.show();
            $tbody.html(`
                <tr>
                    <td colspan="5" class="text-center text-warning py-3">
                        <i class="fa fa-exclamation-circle me-1"></i> Formulation recipe contains no raw or packaging material items.
                    </td>
                </tr>
            `);
            $badge.html('<span class="badge bg-warning text-dark">Empty Recipe</span>');
            return;
        }

        let html = '';
        let totalShortageCount = 0;
        let shortageDetails = [];

        // 1. Process Raw Materials
        rawMaterials.forEach(item => {
            const rm = item.raw_material;
            if (!rm) return;

            const wastePct = parseFloat(item.waste_percent) || 0;
            const baseRecipeQty = parseFloat(item.quantity) || 0;
            const qtyPerPiece = (baseRecipeQty / batchSize) * (1 + (wastePct / 100));
            const requiredQty = qtyPerPiece * qtyToProduce;
            const currentStock = parseFloat(rm.current_stock) || 0;
            const unitName = rm.unit ? rm.unit.name : 'Units';
            const shortage = Math.max(0, requiredQty - currentStock);

            const formattedBaseRecipe = formatQty(baseRecipeQty);
            const formattedPerPc = formatQty(qtyPerPiece);
            const formattedRequired = formatQty(requiredQty);
            const formattedStock = formatQty(currentStock);
            const formattedShortage = formatQty(shortage);

            if (shortage > 0) {
                totalShortageCount++;
                shortageDetails.push(`Insufficient Raw Material '${rm.name}'. Required: ${formattedRequired} ${unitName}, Available: ${formattedStock} ${unitName}. (Shortage: ${formattedShortage} ${unitName})`);
            }

            html += `
                <tr style="${shortage > 0 ? 'background: #fff5f5;' : ''}">
                    <td class="font-weight-bold text-dark">
                        <span class="badge bg-primary-subtle text-primary border border-primary me-1" style="font-size: 0.68rem;">Raw Material</span>
                        ${rm.name || 'Material'}
                    </td>
                    <td class="text-end font-weight-bold text-secondary">
                        ${formattedBaseRecipe} <span class="text-muted small">${unitName}</span>
                        <small class="d-block text-muted" style="font-size: 0.68rem; font-weight: 500;">(${formattedPerPc} / Pc)</small>
                    </td>
                    <td class="text-end font-weight-bold text-dark">
                        ${formattedRequired} <span class="text-muted small">${unitName}</span>
                    </td>
                    <td class="text-end font-weight-bold text-info">
                        ${formattedStock} <span class="text-muted small">${unitName}</span>
                    </td>
                    <td class="text-end">
                        ${shortage > 0 
                            ? `<span class="badge bg-danger-subtle text-danger font-weight-bold px-2 py-1" style="font-size: 0.7rem;">
                                 <i class="fa fa-arrow-down me-1"></i>Short ${formattedShortage} ${unitName}
                               </span>`
                            : `<span class="badge bg-success-subtle text-success font-weight-bold px-2 py-1" style="font-size: 0.7rem;">
                                 <i class="fa fa-check me-1"></i>In Stock
                               </span>`
                        }
                    </td>
                </tr>
            `;
        });

        // 2. Process Packaging Materials
        packagingMaterials.forEach(item => {
            const pm = item.packaging_material;
            if (!pm) return;

            const wastePct = parseFloat(item.waste_percent) || 0;
            const baseRecipeQty = parseFloat(item.quantity) || 0;
            const qtyPerPiece = (baseRecipeQty / batchSize) * (1 + (wastePct / 100));
            const requiredQty = qtyPerPiece * qtyToProduce;
            const currentStock = parseFloat(pm.current_stock) || 0;
            const unitName = pm.unit ? pm.unit.name : 'Piece';
            const shortage = Math.max(0, requiredQty - currentStock);

            const formattedBaseRecipe = formatQty(baseRecipeQty);
            const formattedPerPc = formatQty(qtyPerPiece);
            const formattedRequired = formatQty(requiredQty);
            const formattedStock = formatQty(currentStock);
            const formattedShortage = formatQty(shortage);

            if (shortage > 0) {
                totalShortageCount++;
                shortageDetails.push(`Insufficient Packaging Material '${pm.name}'. Required: ${formattedRequired} ${unitName}, Available: ${formattedStock} ${unitName}. (Shortage: ${formattedShortage} ${unitName})`);
            }

            html += `
                <tr style="${shortage > 0 ? 'background: #fff5f5;' : ''}">
                    <td class="font-weight-bold text-dark">
                        <span class="badge bg-purple-subtle text-purple border me-1" style="font-size: 0.68rem; color: #7c3aed; background: #f5f3ff; border-color: #ddd6fe !important;">Packaging</span>
                        ${pm.name || 'Packaging Material'}
                    </td>
                    <td class="text-end font-weight-bold text-secondary">
                        ${formattedBaseRecipe} <span class="text-muted small">${unitName}</span>
                        <small class="d-block text-muted" style="font-size: 0.68rem; font-weight: 500;">(${formattedPerPc} / Pc)</small>
                    </td>
                    <td class="text-end font-weight-bold text-dark">
                        ${formattedRequired} <span class="text-muted small">${unitName}</span>
                    </td>
                    <td class="text-end font-weight-bold text-info">
                        ${formattedStock} <span class="text-muted small">${unitName}</span>
                    </td>
                    <td class="text-end">
                        ${shortage > 0 
                            ? `<span class="badge bg-danger-subtle text-danger font-weight-bold px-2 py-1" style="font-size: 0.7rem;">
                                 <i class="fa fa-arrow-down me-1"></i>Short ${formattedShortage} ${unitName}
                               </span>`
                            : `<span class="badge bg-success-subtle text-success font-weight-bold px-2 py-1" style="font-size: 0.7rem;">
                                 <i class="fa fa-check me-1"></i>In Stock
                               </span>`
                        }
                    </td>
                </tr>
            `;
        });

        $tbody.html(html);
        $container.show();

        if (totalShortageCount > 0) {
            $badge.html(`
                <span class="badge bg-danger-subtle text-danger border border-danger font-weight-bold px-2 py-1" style="font-size: 0.75rem;">
                    <i class="fa fa-exclamation-triangle me-1"></i> ${totalShortageCount} Material Shortage(s)
                </span>
            `);

            // Dynamically show live TV News Ticker BEFORE button click
            const tickerHtml = `
                <div class="news-headline-ticker">
                    <div class="ticker-header-badge">
                        <span class="ticker-dot"></span>
                        <i class="fas fa-exclamation-triangle"></i> LOW STOCK PREVIEW
                    </div>
                    <div class="ticker-marquee-wrap">
                        <div class="ticker-marquee-content">
                            <span class="ticker-msg">
                                <strong>⚠️ SHORTAGE ALERT:</strong> ${shortageDetails.join(' &nbsp;&bull;&nbsp; ')} &nbsp;&mdash;&nbsp; 
                                <span class="ticker-highlight">Low Stock Warning: Please purchase stock so you can produce batch!</span>
                            </span>
                            <span class="ticker-msg">
                                <strong>⚠️ SHORTAGE ALERT:</strong> ${shortageDetails.join(' &nbsp;&bull;&nbsp; ')} &nbsp;&mdash;&nbsp; 
                                <span class="ticker-highlight">Low Stock Warning: Please purchase stock so you can produce batch!</span>
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('material-purchases.create') }}" class="ticker-action-btn">
                        <i class="fas fa-shopping-cart"></i> Purchase Stock Now
                    </a>
                </div>
            `;
            $('#dynamicTickerContainer').html(tickerHtml).slideDown(200);

        } else {
            $badge.html(`
                <span class="badge bg-success-subtle text-success border border-success font-weight-bold px-2 py-1" style="font-size: 0.75rem;">
                    <i class="fa fa-check-circle me-1"></i> 100% Stock Ready
                </span>
            `);
            $('#dynamicTickerContainer').slideUp(200).html('');
        }
    }
</script>
@endsection

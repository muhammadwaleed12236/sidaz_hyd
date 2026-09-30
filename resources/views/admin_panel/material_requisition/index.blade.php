@extends('admin_panel.layout.app')

@section('content')
<link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/fonts/inter/inter.css') }}">

<style>
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #4338ca;
        --primary-light: #eef2ff;
        --success-color: #10b981;
        --danger-color: #f43f5e;
        --bg-body: #f8fafc;
        --border-color: #e2e8f0;
        --radius-md: 10px;
        --radius-lg: 14px;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-body);
        color: #0f172a;
    }

    /* Remove layout gap */
    .main-content, .main-content-inner, .content-wrapper {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    .mr-page-wrapper {
        padding: 10px 15px 30px !important;
        max-width: 100%;
    }

    /* --- Compact Single-Line Top Bar --- */
    .mr-header-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 10px 18px;
        margin-bottom: 12px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .mr-header-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .mr-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-pills {
        display: flex;
        gap: 4px;
        background: #f1f5f9;
        padding: 3px;
        border-radius: 8px;
    }

    .filter-pill-item {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 6px;
        color: #64748b;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .filter-pill-item:hover {
        color: var(--primary-color);
    }

    .filter-pill-item.active {
        background: #ffffff;
        color: var(--primary-color);
        font-weight: 700;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    .compact-search-box {
        position: relative;
        width: 240px;
    }

    .compact-search-input {
        padding: 6px 12px 6px 32px;
        font-size: 0.8rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: #f8fafc;
        width: 100%;
    }

    .compact-search-input:focus {
        background: #ffffff;
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.12);
    }

    .compact-search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.8rem;
    }

    /* --- Table Card --- */
    .table-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .mr-accordion-table {
        margin: 0;
        font-size: 0.85rem;
    }

    .mr-accordion-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 10px 16px;
        border-bottom: 1px solid var(--border-color);
    }

    .mr-accordion-table td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }

    /* Stock Readiness Progress Bar */
    .stock-health-bar {
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        width: 100px;
    }

    .stock-health-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    /* --- Mobile Cards Layout --- */
    .mobile-mr-cards {
        display: none;
        flex-direction: column;
        gap: 12px;
        padding: 10px;
    }

    .mr-mobile-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 12px 14px;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    }

    /* ════════════════════════════════════════════════════
       MOBILE RESPONSIVE RULES (≤ 768px)
    ════════════════════════════════════════════════════ */
    @media (max-width: 768px) {
        .mr-page-wrapper {
            padding: 8px 8px 20px !important;
        }

        .mr-header-card {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            padding: 12px;
        }

        .mr-header-left {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            width: 100%;
        }

        .mr-title {
            font-size: 0.98rem;
        }

        .filter-pills {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            display: flex;
            padding: 3px;
            -webkit-overflow-scrolling: touch;
        }

        .filter-pill-item {
            flex: 1;
            text-align: center;
            padding: 5px 8px;
            font-size: 0.74rem;
        }

        .mr-hdr-actions-group {
            width: 100%;
            flex-direction: column;
            gap: 8px;
        }

        .compact-search-box {
            width: 100% !important;
        }

        .mr-mobile-btn-grid {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 6px !important;
            width: 100%;
        }

        .mr-mobile-btn-grid .btn {
            width: 100% !important;
            justify-content: center !important;
            height: 36px !important;
            display: inline-flex !items-center;
            align-items: center;
            font-size: 0.75rem !important;
        }

        /* Toggle Table vs Cards */
        .table-responsive {
            display: none !important;
        }
        .mobile-mr-cards {
            display: flex !important;
        }
    }
</style>

<div class="container-fluid mr-page-wrapper">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm py-2 px-3 mb-2" style="border-radius: 8px; font-size: 0.85rem;" role="alert">
            <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Compact Top Bar (Title + Search + Filter + Actions) -->
    <div class="mr-header-card">
        <div class="mr-header-left">
            <h2 class="mr-title">
                <i class="fa fa-industry text-primary"></i> Material Requisitions
            </h2>

            <!-- Filter Pills -->
            <div class="filter-pills">
                <a href="{{ route('material-requisitions.index', ['status' => 'active']) }}" class="filter-pill-item {{ ($status ?? 'active') === 'active' ? 'active' : '' }}">
                    Active
                </a>
                <a href="{{ route('material-requisitions.index', ['status' => 'fulfilled']) }}" class="filter-pill-item {{ ($status ?? '') === 'fulfilled' ? 'active' : '' }}">
                    Fulfilled
                </a>
                <a href="{{ route('material-requisitions.index', ['status' => 'all']) }}" class="filter-pill-item {{ ($status ?? '') === 'all' ? 'active' : '' }}">
                    All
                </a>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 mr-hdr-actions-group">
            <!-- Live Search Box -->
            <div class="compact-search-box">
                <i class="fa fa-search compact-search-icon"></i>
                <input type="text" id="requisitionSearchInput" class="compact-search-input" placeholder="Search Req #, Order, Material...">
            </div>

            <div class="d-flex align-items-center gap-2 mr-mobile-btn-grid">
                <a href="{{ route('material-purchases.create') }}" class="btn btn-outline-danger btn-sm font-weight-bold px-3 py-1" style="border-radius: 6px; font-size: 0.78rem;">
                    <i class="fa fa-shopping-cart me-1"></i> Purchase Material
                </a>
                <a href="{{ route('production.create') }}" class="btn btn-success btn-sm font-weight-bold px-3 py-1" style="border-radius: 6px; font-size: 0.78rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: 0;">
                    <i class="fa fa-cogs me-1"></i> Produce Batch
                </a>
            </div>
        </div>
    </div>

    <!-- Requisitions Card Container -->
    <div class="table-card">

        {{-- ── 1. Desktop Table View (> 768px) ── --}}
        <div class="table-responsive">
            <table class="table mr-accordion-table align-middle mb-0" id="requisitionsTable">
                <thead>
                    <tr>
                        <th style="width: 140px;">Req # &amp; Date</th>
                        <th style="width: 180px;">Sales Order / Customer</th>
                        <th>BOM Recipe &amp; Material Stock Audit</th>
                        <th class="text-center" style="width: 140px;">Status</th>
                        <th class="text-end" style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requisitions as $req)
                        @php
                            $bomAuditItems = [];
                            $totalShortageCount = 0;
                            $totalItemsCount = 0;
                            $availableItemsCount = 0;
                            $productsSummary = [];

                            $reqTotalOrderedPcs = 0;
                            $reqTotalProducedPcs = 0;

                            if ($req->sale && $req->sale->items) {
                                foreach ($req->sale->items as $sItem) {
                                    if ($sItem->is_manual || !$sItem->product_id) continue;
                                    $product = $sItem->product;
                                    if (!$product) continue;

                                    $orderedPieces = (float)($sItem->total_pieces > 0 ? $sItem->total_pieces : ($sItem->qty * ($product->pieces_per_box ?? 1)));
                                    $reqTotalOrderedPcs += $orderedPieces;

                                    $producedPieces = (float) \App\Models\ProductionBatch::where('sale_id', $req->sale_id)
                                        ->where('product_id', $sItem->product_id)
                                        ->sum('quantity');
                                    $reqTotalProducedPcs += $producedPieces;

                                    $productsSummary[] = $product->item_name . ' (' . (float)$orderedPieces . ' Pcs)';

                                    $formulation = $product->formulations->where('status', 'active')->first() ?? $product->formulations->last();
                                    if (!$formulation) continue;

                                    $batchSize = (float)($formulation->batch_size > 0 ? $formulation->batch_size : 1);

                                    // Raw Materials
                                    if ($formulation->rawMaterials) {
                                        foreach ($formulation->rawMaterials as $frm) {
                                            $rm = $frm->rawMaterial;
                                            if (!$rm) continue;

                                            $key = 'RM_' . $rm->id;
                                            $baseQty = (float)$frm->quantity;
                                            $wastePct = (float)($frm->waste_percent ?? 0);
                                            $qtyPerPiece = ($baseQty / $batchSize) * (1 + ($wastePct / 100));
                                            $requiredQty = round($qtyPerPiece * $orderedPieces, 4);
                                            $currentStock = (float)($rm->current_stock ?? 0);
                                            $unitName = $rm->unit ? $rm->unit->name : 'Unit';

                                            if (!isset($bomAuditItems[$key])) {
                                                $bomAuditItems[$key] = [
                                                    'type' => 'Raw Material',
                                                    'name' => $rm->name,
                                                    'unit' => $unitName,
                                                    'base_qty' => $baseQty,
                                                    'batch_size' => $batchSize,
                                                    'qty_per_piece' => $qtyPerPiece,
                                                    'required_qty' => 0.0,
                                                    'current_stock' => $currentStock,
                                                ];
                                            }
                                            $bomAuditItems[$key]['required_qty'] += $requiredQty;
                                        }
                                    }

                                    // Packaging Materials
                                    if ($formulation->packagingMaterials) {
                                        foreach ($formulation->packagingMaterials as $fpm) {
                                            $pm = $fpm->packagingMaterial;
                                            if (!$pm) continue;

                                            $key = 'PM_' . $pm->id;
                                            $baseQty = (float)$fpm->quantity;
                                            $wastePct = (float)($fpm->waste_percent ?? 0);
                                            $qtyPerPiece = ($baseQty / $batchSize) * (1 + ($wastePct / 100));
                                            $requiredQty = round($qtyPerPiece * $orderedPieces, 4);
                                            $currentStock = (float)($pm->current_stock ?? 0);
                                            $unitName = $pm->unit ? $pm->unit->name : 'Piece';

                                            if (!isset($bomAuditItems[$key])) {
                                                $bomAuditItems[$key] = [
                                                    'type' => 'Packaging Material',
                                                    'name' => $pm->name,
                                                    'unit' => $unitName,
                                                    'base_qty' => $baseQty,
                                                    'batch_size' => $batchSize,
                                                    'qty_per_piece' => $qtyPerPiece,
                                                    'required_qty' => 0.0,
                                                    'current_stock' => $currentStock,
                                                ];
                                            }
                                            $bomAuditItems[$key]['required_qty'] += $requiredQty;
                                        }
                                    }
                                }
                            }

                            $reqRemainingPcs = max(0, $reqTotalOrderedPcs - $reqTotalProducedPcs);

                            if (empty($bomAuditItems) && $req->items) {
                                foreach ($req->items as $item) {
                                    $rm = $item->rawMaterial;
                                    $key = 'RM_' . ($rm->id ?? $item->id);
                                    $requiredQty = (float)$item->required_qty;
                                    $currentStock = (float)($rm->current_stock ?? 0);
                                    $unitName = $item->unit ?? ($rm->unit ? $rm->unit->name : 'Unit');

                                    $bomAuditItems[$key] = [
                                        'type' => 'Raw Material',
                                        'name' => $rm->name ?? 'Material Item',
                                        'unit' => $unitName,
                                        'base_qty' => $requiredQty,
                                        'batch_size' => 1,
                                        'qty_per_piece' => $requiredQty,
                                        'required_qty' => $requiredQty,
                                        'current_stock' => $currentStock,
                                    ];
                                }
                            }

                            foreach ($bomAuditItems as &$bItem) {
                                $totalItemsCount++;
                                $bItem['shortage'] = max(0, (float)$bItem['required_qty'] - (float)$bItem['current_stock']);
                                if ($bItem['shortage'] > 0) {
                                    $totalShortageCount++;
                                } else {
                                    $availableItemsCount++;
                                }
                            }

                            $hasLiveShortage = $totalShortageCount > 0;
                            $stockPct = $totalItemsCount > 0 ? round(($availableItemsCount / $totalItemsCount) * 100) : 100;
                            $productSummaryText = !empty($productsSummary) ? implode(', ', $productsSummary) : 'Direct Production Batch';
                            $searchText = strtolower($req->requisition_no . ' ' . ($req->sale->invoice_no ?? '') . ' ' . ($req->sale->customer_relation->customer_name ?? '') . ' ' . $productSummaryText);
                        @endphp

                        <tr class="req-row-item" id="main_row_{{ $req->id }}" data-search="{{ $searchText }}">
                            
                            <!-- 1. Req # & Date -->
                            <td class="align-top py-3">
                                <span class="font-weight-bold text-primary d-block" style="font-size: 0.92rem;">
                                    {{ $req->requisition_no }}
                                </span>
                                <small class="text-muted d-block" style="font-size: 0.74rem;">
                                    <i class="far fa-calendar-alt me-1"></i>{{ $req->created_at->format('d M Y') }}
                                </small>
                            </td>

                            <!-- 2. Sales Order / Customer -->
                            <td class="align-top py-3">
                                @if($req->sale)
                                    <a href="{{ route('sales.invoice', $req->sale->id) }}" target="_blank" class="font-weight-bold text-dark text-decoration-none d-block">
                                        #{{ $req->sale->invoice_no }}
                                    </a>
                                    <small class="text-muted d-block text-truncate" style="max-width: 170px;">
                                        <i class="far fa-user me-1"></i>{{ $req->sale->customer_relation->customer_name ?? (!empty($req->sale->walkin_name) ? $req->sale->walkin_name : 'Walk-in Customer') }}
                                    </small>
                                @else
                                    <span class="badge bg-light text-secondary border font-weight-bold" style="font-size: 0.72rem;">Direct Production</span>
                                @endif
                                <small class="text-primary font-weight-bold d-block mt-1 text-truncate" style="max-width: 170px; font-size: 0.72rem;">
                                    <i class="fa fa-box-open me-1"></i>{{ $productSummaryText }}
                                </small>
                                @if($reqTotalOrderedPcs > 0)
                                    <small class="d-block mt-1 font-weight-bold {{ $reqRemainingPcs > 0 ? 'text-warning' : 'text-success' }}" style="font-size: 0.7rem;">
                                        Ordered: {{ (float)$reqTotalOrderedPcs }} | Produced: {{ (float)$reqTotalProducedPcs }} | <span class="badge {{ $reqRemainingPcs > 0 ? 'bg-warning text-dark' : 'bg-success' }} px-1">{{ (float)$reqRemainingPcs }} Remaining</span>
                                    </small>
                                @endif
                            </td>

                            <!-- 3. BOM Recipe & Raw/Packaging Material Breakdown -->
                            <td class="p-2">
                                <div class="border rounded-2 overflow-hidden" style="background: #fafafa; border-color: #e2e8f0 !important;">
                                    <div class="d-flex align-items-center justify-content-between px-2 py-1 bg-light border-bottom">
                                        <span class="text-uppercase font-weight-bold text-secondary" style="font-size: 0.68rem;">
                                            <i class="fa fa-vials text-primary me-1"></i> Raw &amp; Packaging Materials Audit
                                        </span>
                                        <button type="button" class="btn btn-xs btn-outline-primary py-0 px-2 font-weight-bold" data-bs-toggle="modal" data-bs-target="#auditModal_{{ $req->id }}" style="font-size: 0.68rem; border-radius: 4px;">
                                            <i class="fa fa-eye me-1"></i> View Audit Modal
                                        </button>
                                    </div>
                                    <table class="table table-sm table-borderless mb-0 align-middle" style="font-size: 0.78rem;">
                                        <thead style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0; font-size: 0.7rem;">
                                            <tr class="text-secondary text-uppercase font-weight-bold">
                                                <th class="ps-2 py-1">Material / Packaging Item</th>
                                                <th class="text-end py-1">Standard Recipe Qty</th>
                                                <th class="text-end py-1">Required Qty</th>
                                                <th class="text-end py-1">Current Stock</th>
                                                <th class="text-end pe-2 py-1">Shortage Delta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bomAuditItems as $bItem)
                                                <tr style="{{ $bItem['shortage'] > 0 ? 'background: #fff5f5;' : '' }}">
                                                    <td class="ps-2 py-1 font-weight-bold text-dark">
                                                        @if($bItem['type'] === 'Raw Material')
                                                            <span class="badge bg-primary-subtle text-primary border border-primary me-1" style="font-size: 0.65rem;">RM</span>
                                                        @else
                                                            <span class="badge bg-purple-subtle text-purple border me-1" style="font-size: 0.65rem; color: #7c3aed; background: #f5f3ff; border-color: #ddd6fe !important;">PM</span>
                                                        @endif
                                                        {{ $bItem['name'] }}
                                                    </td>
                                                    <td class="text-end py-1 font-weight-bold text-secondary">
                                                        {{ (float)$bItem['base_qty'] }} <span class="text-muted small">{{ $bItem['unit'] }}</span>
                                                        <small class="d-block text-muted" style="font-size: 0.66rem; font-weight: 500;">({{ (float)round($bItem['qty_per_piece'], 4) }} / Pc)</small>
                                                    </td>
                                                    <td class="text-end py-1 font-weight-bold text-dark">
                                                        {{ (float)$bItem['required_qty'] }} <span class="text-muted small">{{ $bItem['unit'] }}</span>
                                                    </td>
                                                    <td class="text-end py-1 font-weight-bold text-info">
                                                        {{ (float)$bItem['current_stock'] }} <span class="text-muted small">{{ $bItem['unit'] }}</span>
                                                    </td>
                                                    <td class="text-end pe-2 py-1">
                                                        @if($bItem['shortage'] > 0)
                                                            <span class="badge bg-danger-subtle text-danger font-weight-bold px-2 py-1" style="font-size: 0.7rem;">
                                                                <i class="fa fa-arrow-down me-1"></i>Short {{ (float)$bItem['shortage'] }} {{ $bItem['unit'] }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-success-subtle text-success font-weight-bold px-2 py-1" style="font-size: 0.7rem;">
                                                                <i class="fa fa-check me-1"></i>In Stock
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>

                            <!-- 4. Status (Red / Green Badge system with Modal trigger) -->
                            <td class="text-center align-top py-3">
                                <div class="mb-2">
                                    @if($req->status === 'fulfilled' || ($reqTotalOrderedPcs > 0 && $reqRemainingPcs <= 0))
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary font-weight-bold px-2 py-1" style="font-size: 0.75rem;">
                                            <i class="fa fa-check-double me-1"></i> Fulfilled
                                        </span>
                                    @elseif($reqTotalProducedPcs > 0 && $reqRemainingPcs > 0)
                                        <button type="button" class="btn p-0 border-0 bg-transparent text-start" data-bs-toggle="modal" data-bs-target="#auditModal_{{ $req->id }}">
                                            <span class="badge bg-warning-subtle text-warning border border-warning font-weight-bold px-2 py-1 shadow-sm" style="font-size: 0.75rem; cursor: pointer; color: #b45309; background: #fffbeb;">
                                                <i class="fa fa-clock me-1"></i> Partial: {{ (float)$reqTotalProducedPcs }}/{{ (float)$reqTotalOrderedPcs }} Pcs
                                            </span>
                                        </button>
                                    @elseif($hasLiveShortage)
                                        <button type="button" class="btn p-0 border-0 bg-transparent text-start" data-bs-toggle="modal" data-bs-target="#auditModal_{{ $req->id }}">
                                            <span class="badge bg-danger-subtle text-danger border border-danger font-weight-bold px-2 py-1 shadow-sm" style="font-size: 0.75rem; cursor: pointer;">
                                                <i class="fa fa-exclamation-triangle me-1"></i> {{ $totalShortageCount }} Shortage(s)
                                            </span>
                                        </button>
                                    @else
                                        <button type="button" class="btn p-0 border-0 bg-transparent text-start" data-bs-toggle="modal" data-bs-target="#auditModal_{{ $req->id }}">
                                            <span class="badge bg-success-subtle text-success border border-success font-weight-bold px-2 py-1 shadow-sm" style="font-size: 0.75rem; cursor: pointer;">
                                                <i class="fa fa-check-circle me-1"></i> 100% Stock Ready
                                            </span>
                                        </button>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center justify-content-center gap-1 mt-1">
                                    <div class="stock-health-bar" style="width: 65px; height: 5px;">
                                        <div class="stock-health-fill {{ $stockPct >= 100 ? 'bg-success' : 'bg-danger' }}" style="width: {{ $stockPct }}%;"></div>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem; font-weight: 600;">{{ $stockPct }}%</small>
                                </div>

                                <button type="button" class="btn btn-sm btn-link text-decoration-none font-weight-bold p-0 mt-1" data-bs-toggle="modal" data-bs-target="#auditModal_{{ $req->id }}" style="font-size: 0.72rem;">
                                    <i class="fa fa-expand-alt me-1"></i> Audit Modal
                                </button>
                            </td>

                            <!-- 5. Actions -->
                            <td class="text-end align-top py-3">
                                <div class="d-flex flex-column align-items-end gap-1">
                                    @if($req->status === 'fulfilled' || ($reqTotalOrderedPcs > 0 && $reqRemainingPcs <= 0))
                                        <span class="btn btn-sm btn-light text-success font-weight-bold disabled border border-success py-1 px-2" style="font-size: 0.75rem; border-radius: 6px;">
                                            <i class="fa fa-check me-1"></i> Produced
                                        </span>
                                    @elseif($reqRemainingPcs > 0 && $reqTotalProducedPcs > 0)
                                        @if($hasLiveShortage)
                                            <a href="{{ route('material-purchases.create') }}" class="btn btn-sm btn-outline-danger font-weight-bold py-1 px-2 shadow-sm mb-1" style="border-radius: 6px; font-size: 0.75rem;">
                                                <i class="fa fa-shopping-cart me-1"></i> Purchase Stock
                                            </a>
                                        @endif
                                        <a href="{{ route('production.create', ['sale_id' => $req->sale_id]) }}" class="btn btn-sm btn-warning font-weight-bold py-1 px-2 shadow-sm text-dark" style="border-radius: 6px; font-size: 0.75rem; background: #f59e0b; border: 0;">
                                            <i class="fa fa-cogs me-1"></i> Produce Remaining ({{ (float)$reqRemainingPcs }} Pcs)
                                        </a>
                                    @elseif($hasLiveShortage)
                                        <a href="{{ route('material-purchases.create') }}" class="btn btn-sm btn-outline-danger font-weight-bold py-1 px-2 shadow-sm mb-1" style="border-radius: 6px; font-size: 0.75rem;">
                                            <i class="fa fa-shopping-cart me-1"></i> Purchase Stock
                                        </a>
                                        <a href="{{ route('production.create', ['sale_id' => $req->sale_id]) }}" class="btn btn-sm btn-outline-success font-weight-bold py-1 px-2 shadow-sm" style="border-radius: 6px; font-size: 0.75rem;">
                                            <i class="fa fa-cogs me-1"></i> Produce This Batch
                                        </a>
                                    @else
                                        <a href="{{ route('production.create', ['sale_id' => $req->sale_id]) }}" class="btn btn-sm btn-success font-weight-bold py-1 px-2 shadow-sm" style="border-radius: 6px; font-size: 0.75rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: 0;">
                                            <i class="fa fa-cogs me-1"></i> Produce Batch
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- MODAL POPUP FOR DETAILED BOM & STOCK READINESS AUDIT -->
                        <div class="modal fade" id="auditModal_{{ $req->id }}" tabindex="-1" aria-labelledby="auditModalLabel_{{ $req->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                                    
                                    <!-- Modal Header -->
                                    <div class="modal-header px-4 py-3 text-white" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-bottom: 2px solid #334155;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-primary text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                                                <i class="fa fa-clipboard-list"></i>
                                            </div>
                                            <div>
                                                <h5 class="modal-title font-weight-bold mb-0" id="auditModalLabel_{{ $req->id }}" style="font-size: 1rem;">
                                                    BOM Recipe &amp; Stock Readiness Audit — {{ $req->requisition_no }}
                                                </h5>
                                                <small class="text-white-50" style="font-size: 0.75rem;">Comprehensive Raw Material &amp; Packaging Material Audit</small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="modal-body p-4" style="background: #f8fafc;">
                                        
                                        <!-- Target Demand Summary Box -->
                                        <div class="p-3 mb-3 bg-white border rounded-3 shadow-sm d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <div>
                                                <small class="text-uppercase text-secondary font-weight-bold d-block" style="font-size: 0.68rem; letter-spacing: 0.05em;">Target Product(s) &amp; Customer Demand</small>
                                                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 0.92rem;">
                                                    <i class="fa fa-box-open text-primary me-1"></i>{{ $productSummaryText }}
                                                </h6>
                                                <small class="text-muted" style="font-size: 0.78rem;">
                                                    @if($req->sale)
                                                        <i class="far fa-file-alt me-1"></i> Order #{{ $req->sale->invoice_no }} &bull; Customer: {{ $req->sale->customer_relation->customer_name ?? $req->sale->walkin_name ?? 'Walk-in' }}
                                                    @else
                                                        <i class="fa fa-industry me-1"></i> Direct Warehouse Production
                                                    @endif
                                                </small>
                                            </div>

                                            <div>
                                                @if($hasLiveShortage)
                                                    <span class="badge bg-danger-subtle text-danger border border-danger font-weight-bold px-3 py-2" style="font-size: 0.82rem; border-radius: 8px;">
                                                        <i class="fa fa-exclamation-triangle me-1"></i> {{ $totalShortageCount }} Shortage(s) Detected
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success border border-success font-weight-bold px-3 py-2" style="font-size: 0.82rem; border-radius: 8px;">
                                                        <i class="fa fa-check-circle me-1"></i> 100% Stock Ready
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Full Ingredients & Packaging Audit Table -->
                                        <div class="table-responsive border rounded-3 bg-white shadow-sm overflow-hidden">
                                            <table class="table align-middle mb-0" style="font-size: 0.82rem;">
                                                <thead style="background: #f1f5f9; border-bottom: 1.5px solid #cbd5e1;">
                                                    <tr class="text-uppercase text-secondary font-weight-bold" style="font-size: 0.72rem;">
                                                        <th class="ps-3 py-2">Raw Material / Packaging Ingredient</th>
                                                        <th class="text-end py-2">Standard Recipe Qty</th>
                                                        <th class="text-end py-2">Batch Required Qty</th>
                                                        <th class="text-end py-2">Current Stock</th>
                                                        <th class="text-center pe-3 py-2">Shortage / Delta Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($bomAuditItems as $bItem)
                                                        <tr style="{{ $bItem['shortage'] > 0 ? 'background: #fff5f5;' : '' }}">
                                                            <td class="ps-3 py-2 font-weight-bold text-dark">
                                                                @if($bItem['type'] === 'Raw Material')
                                                                    <span class="badge bg-primary-subtle text-primary border border-primary me-2" style="font-size: 0.68rem;">Raw Material</span>
                                                                @else
                                                                    <span class="badge bg-purple-subtle text-purple border me-2" style="font-size: 0.68rem; color: #7c3aed; background: #f5f3ff; border-color: #ddd6fe !important;">Packaging Material</span>
                                                                @endif
                                                                {{ $bItem['name'] }}
                                                            </td>
                                                            <td class="text-end py-2 font-weight-bold text-secondary">
                                                                {{ (float)$bItem['base_qty'] }} <span class="text-muted small">{{ $bItem['unit'] }}</span>
                                                                <small class="d-block text-muted" style="font-size: 0.7rem; font-weight: 500;">({{ (float)round($bItem['qty_per_piece'], 4) }} / Pc)</small>
                                                            </td>
                                                            <td class="text-end py-2 font-weight-bold text-dark">
                                                                {{ (float)$bItem['required_qty'] }} <span class="text-muted small">{{ $bItem['unit'] }}</span>
                                                            </td>
                                                            <td class="text-end py-2 font-weight-bold text-info">
                                                                {{ (float)$bItem['current_stock'] }} <span class="text-muted small">{{ $bItem['unit'] }}</span>
                                                            </td>
                                                            <td class="text-center pe-3 py-2">
                                                                @if($bItem['shortage'] > 0)
                                                                    <span class="badge bg-danger-subtle text-danger font-weight-bold px-2 py-1" style="font-size: 0.74rem;">
                                                                        <i class="fa fa-arrow-down me-1"></i>Short {{ (float)$bItem['shortage'] }} {{ $bItem['unit'] }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-success-subtle text-success font-weight-bold px-2 py-1" style="font-size: 0.74rem;">
                                                                        <i class="fa fa-check me-1"></i>In Stock
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="modal-footer px-4 py-3 bg-light border-top d-flex justify-content-between align-items-center">
                                        <button type="button" class="btn btn-secondary btn-sm px-3 font-weight-bold" data-bs-dismiss="modal" style="border-radius: 6px;">
                                            Close
                                        </button>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($hasLiveShortage)
                                                <a href="{{ route('material-purchases.create') }}" class="btn btn-danger btn-sm font-weight-bold px-3 py-1 shadow-sm" style="border-radius: 6px;">
                                                    <i class="fa fa-shopping-cart me-1"></i> Purchase Shortage Stock
                                                </a>
                                                <a href="{{ route('production.create', ['sale_id' => $req->sale_id]) }}" class="btn btn-outline-success btn-sm font-weight-bold px-3 py-1 shadow-sm" style="border-radius: 6px;">
                                                    <i class="fa fa-cogs me-1"></i> Produce This Batch
                                                </a>
                                            @else
                                                <a href="{{ route('production.create', ['sale_id' => $req->sale_id]) }}" class="btn btn-success btn-sm font-weight-bold px-3 py-1 shadow-sm" style="border-radius: 6px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: 0;">
                                                    <i class="fa fa-cogs me-1"></i> Produce Batch Now
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fa fa-info-circle fa-2x mb-1 d-block text-secondary"></i>
                                No active material requisitions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>{{-- /table-responsive --}}

        {{-- ── 2. Mobile Cards View (≤ 768px) ── --}}
        <div class="mobile-mr-cards">
            @forelse($requisitions as $req)
                @php
                    $bomAuditItems = [];
                    $totalShortageCount = 0;
                    $totalItemsCount = 0;
                    $availableItemsCount = 0;
                    $productsSummary = [];

                    if ($req->sale && $req->sale->items) {
                        foreach ($req->sale->items as $sItem) {
                            if ($sItem->is_manual || !$sItem->product_id) continue;
                            $product = $sItem->product;
                            if (!$product) continue;

                            $orderedPieces = (float)($sItem->total_pieces > 0 ? $sItem->total_pieces : ($sItem->qty * ($product->pieces_per_box ?? 1)));
                            $productsSummary[] = $product->item_name . ' (' . (float)$orderedPieces . ' Pcs)';

                            $formulation = $product->formulations->where('status', 'active')->first() ?? $product->formulations->last();
                            if (!$formulation) continue;

                            $batchSize = (float)($formulation->batch_size > 0 ? $formulation->batch_size : 1);

                            // Raw Materials
                            if ($formulation->rawMaterials) {
                                foreach ($formulation->rawMaterials as $frm) {
                                    $rm = $frm->rawMaterial;
                                    if (!$rm) continue;

                                    $key = 'RM_' . $rm->id;
                                    $baseQty = (float)$frm->quantity;
                                    $wastePct = (float)($frm->waste_percent ?? 0);
                                    $qtyPerPiece = ($baseQty / $batchSize) * (1 + ($wastePct / 100));
                                    $requiredQty = round($qtyPerPiece * $orderedPieces, 4);
                                    $currentStock = (float)($rm->current_stock ?? 0);
                                    $unitName = $rm->unit ? $rm->unit->name : 'Unit';

                                    if (!isset($bomAuditItems[$key])) {
                                        $bomAuditItems[$key] = [
                                            'type' => 'Raw Material',
                                            'name' => $rm->name,
                                            'unit' => $unitName,
                                            'base_qty' => $baseQty,
                                            'batch_size' => $batchSize,
                                            'qty_per_piece' => $qtyPerPiece,
                                            'required_qty' => 0.0,
                                            'current_stock' => $currentStock,
                                        ];
                                    }
                                    $bomAuditItems[$key]['required_qty'] += $requiredQty;
                                }
                            }

                            // Packaging Materials
                            if ($formulation->packagingMaterials) {
                                foreach ($formulation->packagingMaterials as $fpm) {
                                    $pm = $fpm->packagingMaterial;
                                    if (!$pm) continue;

                                    $key = 'PM_' . $pm->id;
                                    $baseQty = (float)$fpm->quantity;
                                    $wastePct = (float)($fpm->waste_percent ?? 0);
                                    $qtyPerPiece = ($baseQty / $batchSize) * (1 + ($wastePct / 100));
                                    $requiredQty = round($qtyPerPiece * $orderedPieces, 4);
                                    $currentStock = (float)($pm->current_stock ?? 0);
                                    $unitName = $pm->unit ? $pm->unit->name : 'Piece';

                                    if (!isset($bomAuditItems[$key])) {
                                        $bomAuditItems[$key] = [
                                            'type' => 'Packaging Material',
                                            'name' => $pm->name,
                                            'unit' => $unitName,
                                            'base_qty' => $baseQty,
                                            'batch_size' => $batchSize,
                                            'qty_per_piece' => $qtyPerPiece,
                                            'required_qty' => 0.0,
                                            'current_stock' => $currentStock,
                                        ];
                                    }
                                    $bomAuditItems[$key]['required_qty'] += $requiredQty;
                                }
                            }
                        }
                    }

                    if (empty($bomAuditItems) && $req->items) {
                        foreach ($req->items as $item) {
                            $rm = $item->rawMaterial;
                            $key = 'RM_' . ($rm->id ?? $item->id);
                            $requiredQty = (float)$item->required_qty;
                            $currentStock = (float)($rm->current_stock ?? 0);
                            $unitName = $item->unit ?? ($rm->unit ? $rm->unit->name : 'Unit');

                            $bomAuditItems[$key] = [
                                'type' => 'Raw Material',
                                'name' => $rm->name ?? 'Material Item',
                                'unit' => $unitName,
                                'base_qty' => $requiredQty,
                                'batch_size' => 1,
                                'qty_per_piece' => $requiredQty,
                                'required_qty' => $requiredQty,
                                'current_stock' => $currentStock,
                            ];
                        }
                    }

                    foreach ($bomAuditItems as &$bItem) {
                        $totalItemsCount++;
                        $bItem['shortage'] = max(0, (float)$bItem['required_qty'] - (float)$bItem['current_stock']);
                        if ($bItem['shortage'] > 0) {
                            $totalShortageCount++;
                        } else {
                            $availableItemsCount++;
                        }
                    }

                    $hasLiveShortage = $totalShortageCount > 0;
                    $stockPct = $totalItemsCount > 0 ? round(($availableItemsCount / $totalItemsCount) * 100) : 100;
                    $productSummaryText = !empty($productsSummary) ? implode(', ', $productsSummary) : 'Direct Production Batch';
                    $searchText = strtolower($req->requisition_no . ' ' . ($req->sale->invoice_no ?? '') . ' ' . ($req->sale->customer_relation->customer_name ?? '') . ' ' . $productSummaryText);
                @endphp

                <div class="mr-mobile-card req-row-item" data-search="{{ $searchText }}">
                    {{-- Header: Req #, Date & Status --}}
                    <div class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                        <div>
                            <span class="font-weight-bold text-primary" style="font-size: 0.9rem;">{{ $req->requisition_no }}</span>
                            <small class="text-muted d-block" style="font-size: 0.72rem;">
                                <i class="far fa-calendar-alt me-1"></i>{{ $req->created_at->format('d M Y') }}
                            </small>
                        </div>
                        <div class="text-end">
                            @if($req->status === 'fulfilled')
                                <span class="badge bg-secondary-subtle text-secondary border font-weight-bold" style="font-size: 0.72rem;">
                                    Fulfilled
                                </span>
                            @elseif($hasLiveShortage)
                                <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#auditModal_{{ $req->id }}">
                                    <span class="badge bg-danger-subtle text-danger border font-weight-bold px-2 py-1" style="font-size: 0.72rem;">
                                        <i class="fa fa-exclamation-triangle me-1"></i> {{ $totalShortageCount }} Shortage
                                    </span>
                                </button>
                            @else
                                <button type="button" class="btn p-0 border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#auditModal_{{ $req->id }}">
                                    <span class="badge bg-success-subtle text-success border font-weight-bold px-2 py-1" style="font-size: 0.72rem;">
                                        <i class="fa fa-check-circle me-1"></i> Stock Ready
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Customer Order Info & Product --}}
                    @if($req->sale)
                        <div class="py-1 mb-1 text-secondary" style="font-size: 0.78rem;">
                            <i class="far fa-file-alt text-primary me-1"></i> <strong>Order #{{ $req->sale->invoice_no }}</strong>
                            &bull; {{ $req->sale->customer_relation->customer_name ?? 'Walk-in Customer' }}
                        </div>
                    @endif
                    <div class="text-primary font-weight-bold mb-2" style="font-size: 0.78rem;">
                        <i class="fa fa-box-open me-1"></i>{{ $productSummaryText }}
                    </div>

                    {{-- Material Breakdown Mini Table --}}
                    <div class="bg-light p-2 rounded-2 mb-2 border" style="font-size: 0.76rem;">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="font-weight-bold text-uppercase text-muted" style="font-size: 0.68rem;">
                                Stock Readiness ({{ $stockPct }}%)
                            </span>
                            <button type="button" class="btn btn-xs btn-link text-primary p-0 text-decoration-none font-weight-bold" data-bs-toggle="modal" data-bs-target="#auditModal_{{ $req->id }}" style="font-size: 0.7rem;">
                                <i class="fa fa-expand-alt me-1"></i> Audit Modal
                            </button>
                        </div>
                        @foreach($bomAuditItems as $bItem)
                            <div class="d-flex align-items-center justify-content-between py-1 border-bottom border-light" style="{{ $bItem['shortage'] > 0 ? 'background:#fff5f5;' : '' }}">
                                <div>
                                    @if($bItem['type'] === 'Raw Material')
                                        <span class="badge bg-primary-subtle text-primary border me-1" style="font-size: 0.62rem;">RM</span>
                                    @else
                                        <span class="badge bg-purple-subtle text-purple border me-1" style="font-size: 0.62rem; color: #7c3aed; background: #f5f3ff; border-color: #ddd6fe !important;">PM</span>
                                    @endif
                                    <strong>{{ $bItem['name'] }}</strong>
                                    <div class="text-muted" style="font-size: 0.7rem;">
                                        Recipe: {{ (float)$bItem['base_qty'] }} {{ $bItem['unit'] }} ({{ (float)round($bItem['qty_per_piece'], 4) }}/Pc) | Req: {{ (float)$bItem['required_qty'] }} {{ $bItem['unit'] }} | Stock: {{ (float)$bItem['current_stock'] }} {{ $bItem['unit'] }}
                                    </div>
                                </div>
                                <div>
                                    @if($bItem['shortage'] > 0)
                                        <span class="badge bg-danger-subtle text-danger font-weight-bold" style="font-size: 0.68rem;">Short {{ (float)$bItem['shortage'] }}</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success font-weight-bold" style="font-size: 0.68rem;">In Stock</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Touch Actions --}}
                    <div class="d-flex gap-2">
                        @if($req->status === 'fulfilled')
                            <button class="btn btn-sm btn-light text-success font-weight-bold disabled border w-100 py-2" style="font-size: 0.78rem;">
                                <i class="fa fa-check me-1"></i> Produced
                            </button>
                        @elseif($hasLiveShortage)
                            <a href="{{ route('material-purchases.create') }}" class="btn btn-sm btn-outline-danger font-weight-bold flex-grow-1 py-2" style="font-size: 0.78rem;">
                                <i class="fa fa-shopping-cart me-1"></i> Purchase Stock
                            </a>
                            <a href="{{ route('production.create', ['sale_id' => $req->sale_id]) }}" class="btn btn-sm btn-outline-success font-weight-bold flex-grow-1 py-2" style="font-size: 0.78rem;">
                                <i class="fa fa-cogs me-1"></i> Produce Batch
                            </a>
                        @else
                            <a href="{{ route('production.create', ['sale_id' => $req->sale_id]) }}" class="btn btn-sm btn-success font-weight-bold w-100 py-2" style="font-size: 0.78rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: 0;">
                                <i class="fa fa-cogs me-1"></i> Produce Batch
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted bg-white rounded border">
                    <i class="fa fa-info-circle fa-2x mb-1 d-block text-secondary"></i>
                    No active material requisitions found.
                </div>
            @endforelse
        </div>

        @if($requisitions->hasPages())
            <div class="p-2 bg-white border-top d-flex justify-content-end">
                {{ $requisitions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@section('js')
<script>
    // Instant Live Client-Side Search (Works for both Table & Mobile Cards)
    $('#requisitionSearchInput').on('keyup', function() {
        const query = $(this).val().toLowerCase().trim();
        $('.req-row-item').each(function() {
            const searchText = $(this).data('search') || '';
            if (searchText.indexOf(query) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
</script>
@endsection

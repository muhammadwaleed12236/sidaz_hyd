<?php

namespace App\Http\Controllers;

use App\Models\Formulation;
use App\Models\MaterialRequisition;
use App\Models\MaterialStockMovement;
use App\Models\PackagingMaterial;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\ProductionBatchItem;
use App\Models\RawMaterial;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\SystemNotification;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductionBatch::with(['product', 'sale.customer_relation', 'warehouse', 'items.rawMaterial'])
            ->orderBy('id', 'desc');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $batches = $query->paginate(15);
        $customers = \App\Models\Customer::where('status', 'active')->orWhereNull('status')->orderBy('customer_name')->get();

        return view('admin_panel.production.index', compact('batches', 'customers'));
    }

    public function create(Request $request)
    {
        $products = Product::where('is_active', 1)->orderBy('item_name')->get();

        // Get active customer sales/orders (Booked or Posted)
        $sales = Sale::with(['customer_relation', 'items.product'])
            ->whereIn('sale_status', ['booked', 'posted'])
            ->orderBy('id', 'desc')
            ->get();

        foreach ($sales as $s) {
            foreach ($s->items as $item) {
                if (!$item->product_id) continue;
                $ordered = (float) ($item->total_pieces > 0 ? $item->total_pieces : ($item->qty * ($item->product->pieces_per_box ?? 1)));
                $produced = (float) ProductionBatch::where('sale_id', $s->id)
                    ->where('product_id', $item->product_id)
                    ->sum('quantity');

                $item->total_ordered_pieces = $ordered;
                $item->already_produced_pieces = $produced;
                $item->remaining_pieces = max(0, $ordered - $produced);
            }
        }

        $selectedSaleId = $request->input('sale_id');
        $selectedProductId = $request->input('product_id');

        $lastBatchId = ProductionBatch::max('id') ?? 0;
        $nextBatchNo = 'BATCH-' . date('Y') . '-' . str_pad($lastBatchId + 1, 4, '0', STR_PAD_LEFT);

        $formulations = Formulation::with(['rawMaterials.rawMaterial.unit', 'packagingMaterials.packagingMaterial.unit'])
            ->get()
            ->groupBy('product_id');

        $customers = \App\Models\Customer::where('status', 'active')->orWhereNull('status')->orderBy('customer_name')->get();

        return view('admin_panel.production.create', compact('products', 'sales', 'customers', 'nextBatchNo', 'selectedSaleId', 'selectedProductId', 'formulations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.0001',
            'batch_no' => 'required|string|unique:production_batches,batch_no',
            'mfg_date' => 'required|date',
            'exp_date' => 'required|date|after_or_equal:mfg_date',
            'customer_mode' => 'nullable|in:none,existing_order,direct_customer',
            'customer_id' => 'nullable|exists:customers,id',
            'walkin_name' => 'nullable|string|max:255',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $qtyToProduce = (float) $request->quantity;
            $customerMode = $request->input('customer_mode', 'none');
            $saleId = null;
            $createdDirectSale = null;
            $autoDispatchDirect = false;

            if ($customerMode === 'existing_order') {
                $saleId = $request->sale_id ?: null;
            } elseif ($customerMode === 'direct_customer') {
                $isWalkin = $request->input('customer_type') === 'walkin';
                $directCustomerId = $isWalkin ? null : ($request->input('customer_id') ?: null);
                $walkinName = $isWalkin ? $request->input('walkin_name') : null;
                $unitPrice = (float) $request->input('unit_price', 0);
                if ($unitPrice <= 0) {
                    $unitPrice = (float) ($product->sale_price_per_piece ?: ($product->retail_price ?: 0));
                }
                $autoDispatchDirect = $request->boolean('auto_dispatch_invoice');
                $paidNow = $request->boolean('paid_now');
                $totalBill = round($qtyToProduce * $unitPrice, 2);

                $createdDirectSale = Sale::create([
                    'invoice_no' => Sale::generateInvoiceNo(),
                    'customer_id' => $directCustomerId,
                    'walkin_name' => $walkinName,
                    'total_bill_amount' => $totalBill,
                    'total_extradiscount' => 0,
                    'total_net' => $totalBill,
                    'cash' => $paidNow ? $totalBill : 0,
                    'change' => 0,
                    'total_items' => $qtyToProduce,
                    'sale_status' => $autoDispatchDirect ? 'delivered' : 'booked',
                    'discount_type' => 'pkr',
                ]);

                $ppb = ($product->pieces_per_box > 0) ? $product->pieces_per_box : 1;

                SaleItem::create([
                    'sale_id' => $createdDirectSale->id,
                    'product_id' => $product->id,
                    'warehouse_id' => $request->warehouse_id ?? 1,
                    'brand_id' => is_numeric($product->brand_id) ? (int)$product->brand_id : null,
                    'category_id' => is_numeric($product->category_id) ? (int)$product->category_id : null,
                    'sub_category_id' => is_numeric($product->sub_category_id) ? (int)$product->sub_category_id : null,
                    'unit_id' => is_numeric($product->unit_id) ? (int)$product->unit_id : null,
                    'qty' => $ppb > 0 ? ($qtyToProduce / $ppb) : $qtyToProduce,
                    'total_pieces' => $qtyToProduce,
                    'loose_pieces' => 0,
                    'price' => $unitPrice,
                    'price_per_piece' => $unitPrice,
                    'total' => $totalBill,
                    'discount_amount' => 0,
                    'discount_percent' => 0,
                ]);

                $saleId = $createdDirectSale->id;
            } elseif ($request->filled('sale_id')) {
                $saleId = $request->sale_id;
            }

            // 1. Fetch formulation for finished product
            $formulation = Formulation::where('product_id', $product->id)
                ->where('status', 'active')
                ->with(['rawMaterials.rawMaterial.unit'])
                ->first();

            if (!$formulation) {
                $formulation = Formulation::where('product_id', $product->id)
                    ->with(['rawMaterials.rawMaterial.unit'])
                    ->latest()
                    ->first();
            }

            if (!$formulation || $formulation->rawMaterials->isEmpty()) {
                throw new \Exception("Product '{$product->item_name}' does not have a formulation/recipe set up.");
            }

            $batchSize = (float) ($formulation->batch_size > 0 ? $formulation->batch_size : 1);

            // 2. Verify raw materials stock availability
            $rmConsumption = [];
            foreach ($formulation->rawMaterials as $frm) {
                $rm = $frm->rawMaterial;
                if (!$rm) continue;

                $qtyPerPiece = ($frm->quantity / $batchSize) * (1 + (($frm->waste_percent ?? 0) / 100));
                $requiredRMQty = round($qtyPerPiece * $qtyToProduce, 4);

                $currentRMStock = (float) ($rm->current_stock ?? 0);
                if ($currentRMStock < $requiredRMQty) {
                    $shortage = $requiredRMQty - $currentRMStock;
                    $unitName = $rm->unit ? $rm->unit->name : 'units';
                    throw new \Exception("Insufficient Raw Material '{$rm->name}'. Required: {$requiredRMQty} {$unitName}, Available: {$currentRMStock} {$unitName}. (Shortage: {$shortage} {$unitName})");
                }

                $rmConsumption[] = [
                    'rm' => $rm,
                    'required_qty' => $requiredRMQty,
                    'unit' => $rm->unit ? $rm->unit->name : 'Unit',
                ];
            }

            // 2.5 Verify packaging materials stock availability
            $pmConsumption = [];
            foreach ($formulation->packagingMaterials as $fpm) {
                $pm = $fpm->packagingMaterial;
                if (!$pm) continue;

                $qtyPerPiece = ($fpm->quantity / $batchSize) * (1 + (($fpm->waste_percent ?? 0) / 100));
                $requiredPMQty = round($qtyPerPiece * $qtyToProduce, 4);

                $currentPMStock = (float) ($pm->current_stock ?? 0);
                if ($currentPMStock < $requiredPMQty) {
                    $shortage = $requiredPMQty - $currentPMStock;
                    $unitName = $pm->unit ? $pm->unit->name : 'units';
                    throw new \Exception("Insufficient Packaging Material '{$pm->name}'. Required: {$requiredPMQty} {$unitName}, Available: {$currentPMStock} {$unitName}. (Shortage: {$shortage} {$unitName})");
                }

                $pmConsumption[] = [
                    'pm' => $pm,
                    'required_qty' => $requiredPMQty,
                    'unit' => $pm->unit ? $pm->unit->name : 'Unit',
                ];
            }

            // Determine status: If auto-dispatched -> dispatched, if customer order linked -> ready_for_delivery, else -> produced
            if ($autoDispatchDirect) {
                $status = 'dispatched';
            } elseif ($saleId) {
                $status = 'ready_for_delivery';
            } else {
                $status = 'produced';
            }

            // 3. Create Production Batch Header
            $batch = ProductionBatch::create([
                'batch_no' => $request->batch_no,
                'product_id' => $product->id,
                'sale_id' => $saleId,
                'warehouse_id' => $request->warehouse_id ?? 1,
                'quantity' => $qtyToProduce,
                'mfg_date' => $request->mfg_date,
                'exp_date' => $request->exp_date,
                'status' => $status,
                'notes' => $request->notes,
            ]);

            // 4. Consume Raw Materials (Deduct Stock & Create Movements)
            foreach ($rmConsumption as $cons) {
                $rm = $cons['rm'];
                $consumedQty = $cons['required_qty'];

                ProductionBatchItem::create([
                    'production_batch_id' => $batch->id,
                    'raw_material_id' => $rm->id,
                    'consumed_qty' => $consumedQty,
                    'unit' => $cons['unit'],
                ]);

                // Deduct current_stock directly from raw_materials table
                RawMaterial::where('id', $rm->id)->decrement('current_stock', $consumedQty);

                // Add material movement log
                MaterialStockMovement::create([
                    'item_type' => RawMaterial::class,
                    'item_id' => $rm->id,
                    'type' => 'out',
                    'qty' => $consumedQty,
                    'ref_type' => 'PRODUCTION',
                    'ref_id' => $batch->id,
                    'note' => "Consumed for Batch #{$batch->batch_no} ({$product->item_name})"
                ]);
            }

            // 4.5 Consume Packaging Materials
            foreach ($pmConsumption as $cons) {
                $pm = $cons['pm'];
                $consumedQty = $cons['required_qty'];

                // Deduct current_stock directly from packaging_materials table
                \App\Models\PackagingMaterial::where('id', $pm->id)->decrement('current_stock', $consumedQty);

                // Add material movement log
                MaterialStockMovement::create([
                    'item_type' => \App\Models\PackagingMaterial::class,
                    'item_id' => $pm->id,
                    'type' => 'out',
                    'qty' => $consumedQty,
                    'ref_type' => 'PRODUCTION',
                    'ref_id' => $batch->id,
                    'note' => "Consumed for Batch #{$batch->batch_no} ({$product->item_name})"
                ]);
            }

            // 5. Add Finished Product to Warehouse Stock
            $ppb = $product->pieces_per_box > 0 ? $product->pieces_per_box : 1;
            $whStock = WarehouseStock::firstOrCreate(
                [
                    'warehouse_id' => $request->warehouse_id ?? 1,
                    'product_id' => $product->id,
                ],
                [
                    'quantity' => 0,
                    'total_pieces' => 0,
                    'price' => $product->sale_price_per_piece ?? 0,
                ]
            );

            $whStock->increment('total_pieces', $qtyToProduce);
            $whStock->update(['quantity' => $whStock->total_pieces / $ppb]);

            // Add finished product stock movement (IN)
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'qty' => $qtyToProduce,
                'ref_type' => 'PRODUCTION',
                'ref_id' => $batch->id,
                'note' => "Produced Batch #{$batch->batch_no} ({$qtyToProduce} pcs)"
            ]);

            $successMsg = "Batch #{$batch->batch_no} produced successfully! Finished product stock added to warehouse.";

            // 5.5 If Direct Customer Invoice & Stock Out was selected -> Deliver immediately!
            if ($autoDispatchDirect && $createdDirectSale) {
                $whStock->decrement('total_pieces', $qtyToProduce);
                $whStock->update(['quantity' => $whStock->total_pieces / $ppb]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'qty' => -$qtyToProduce,
                    'ref_type' => 'PRODUCTION_DISPATCH',
                    'ref_id' => $batch->id,
                    'note' => "Dispatched Batch #{$batch->batch_no} ({$qtyToProduce} pcs) for Invoice #{$createdDirectSale->invoice_no}"
                ]);

                if ($createdDirectSale->customer_id) {
                    app(\App\Http\Controllers\SaleController::class)->updateLedger($createdDirectSale);
                    $balanceService = app(\App\Services\BalanceService::class);
                    $custForVoucher = $createdDirectSale->customer_relation ?? \App\Models\Customer::find($createdDirectSale->customer_id);
                    if ($custForVoucher) {
                        $balanceService->createSaleVoucher(
                            $custForVoucher,
                            $createdDirectSale->total_net,
                            $createdDirectSale->invoice_no,
                            date('Y-m-d')
                        );
                    }
                    if ($createdDirectSale->cash > 0) {
                        app(\App\Services\TransactionService::class)->createReceiptFromSale($createdDirectSale);
                    }
                }

                $custDisplayName = $createdDirectSale->customer_relation ? $createdDirectSale->customer_relation->customer_name : ($createdDirectSale->walkin_name ?: 'Walk-in Customer');
                $successMsg = "Batch #{$batch->batch_no} produced & Sale Invoice #{$createdDirectSale->invoice_no} generated and dispatched to {$custDisplayName} ({$qtyToProduce} pcs)!";
            } elseif ($createdDirectSale) {
                $custDisplayName = $createdDirectSale->customer_relation ? $createdDirectSale->customer_relation->customer_name : ($createdDirectSale->walkin_name ?: 'Walk-in Customer');
                $successMsg = "Batch #{$batch->batch_no} produced & Order #{$createdDirectSale->invoice_no} booked for {$custDisplayName}. Ready for delivery!";
            }

            // 6. Update linked Requisition and Customer Sale Order status (for existing orders)
            if ($saleId && !$createdDirectSale) {
                $sale = Sale::with('items.product')->find($saleId);
                if ($sale) {
                    $allSaleProductsCompleted = true;

                    foreach ($sale->items as $sItem) {
                        if ($sItem->is_manual || !$sItem->product_id) continue;

                        $orderedPcs = (float) ($sItem->total_pieces > 0 ? $sItem->total_pieces : ($sItem->qty * ($sItem->product->pieces_per_box ?? 1)));
                        $producedPcs = (float) ProductionBatch::where('sale_id', $saleId)
                            ->where('product_id', $sItem->product_id)
                            ->sum('quantity');

                        if ($producedPcs < $orderedPcs) {
                            $allSaleProductsCompleted = false;
                            break;
                        }
                    }

                    $totalProducedForProduct = (float) ProductionBatch::where('sale_id', $saleId)
                        ->where('product_id', $product->id)
                        ->sum('quantity');

                    $orderedForProduct = 0;
                    foreach ($sale->items as $sItem) {
                        if ($sItem->product_id == $product->id) {
                            $orderedForProduct += (float) ($sItem->total_pieces > 0 ? $sItem->total_pieces : ($sItem->qty * ($product->pieces_per_box ?? 1)));
                        }
                    }
                    if ($orderedForProduct <= 0) $orderedForProduct = $qtyToProduce;

                    $remainingForProduct = max(0, $orderedForProduct - $totalProducedForProduct);

                    if ($allSaleProductsCompleted) {
                        MaterialRequisition::where('sale_id', $saleId)->update([
                            'status' => 'fulfilled',
                            'notes' => "Production Fully Completed ({$totalProducedForProduct} / {$orderedForProduct} Pcs) via Batch #{$batch->batch_no}"
                        ]);

                        $reqs = MaterialRequisition::where('sale_id', $saleId)->get();
                        $reqIds = $reqs->pluck('id')->toArray();
                        if (!empty($reqIds)) {
                            SystemNotification::where('source_type', 'App\Models\MaterialRequisition')
                                ->whereIn('source_id', $reqIds)
                                ->delete();
                        }

                        Sale::where('id', $saleId)->update([
                            'sale_status' => 'ready_for_delivery'
                        ]);

                        $successMsg = "Batch #{$batch->batch_no} produced! Order #{$sale->invoice_no} is fully completed ({$totalProducedForProduct} Pcs) & ready for delivery.";
                    } else {
                        MaterialRequisition::where('sale_id', $saleId)->update([
                            'status' => 'in_production',
                            'notes' => "Partially Produced: {$totalProducedForProduct} / {$orderedForProduct} Pcs (Remaining: {$remainingForProduct} Pcs via Batch #{$batch->batch_no})"
                        ]);

                        Sale::where('id', $saleId)->update([
                            'sale_status' => 'booked'
                        ]);

                        $successMsg = "Batch #{$batch->batch_no} produced! Partial production: {$totalProducedForProduct} / {$orderedForProduct} Pcs ({$remainingForProduct} Pcs remaining to produce & deliver).";
                    }
                }
            }

            DB::commit();

            $redirect = redirect()->route('production.index')->with('success', $successMsg);
            if ($createdDirectSale) {
                $redirect->with([
                    'last_generated_invoice_id' => $createdDirectSale->id,
                    'last_generated_invoice_no' => $createdDirectSale->invoice_no,
                ]);
            }
            return $redirect;

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function dispatch($id)
    {
        try {
            DB::beginTransaction();

            $batch = ProductionBatch::with(['product', 'sale.customer_relation'])->findOrFail($id);

            if ($batch->status === 'dispatched') {
                return redirect()->back()->with('error', "Batch #{$batch->batch_no} is already marked as Dispatched!");
            }

            // 1. Deduct finished product stock from Warehouse
            $whId = $batch->warehouse_id ?: 1;
            $product = $batch->product;
            $whStock = WarehouseStock::where('product_id', $batch->product_id)
                ->where('warehouse_id', $whId)
                ->lockForUpdate()
                ->first();

            $qtyDispatched = (float) $batch->quantity;

            if ($whStock) {
                $whStock->decrement('total_pieces', $qtyDispatched);
                $ppb = ($product && $product->pieces_per_box > 0) ? $product->pieces_per_box : 1;
                $whStock->update(['quantity' => $whStock->total_pieces / $ppb]);
            }

            // 2. Add StockMovement log (OUT)
            StockMovement::create([
                'product_id' => $batch->product_id,
                'type' => 'out',
                'qty' => -$qtyDispatched,
                'ref_type' => 'PRODUCTION_DISPATCH',
                'ref_id' => $batch->id,
                'note' => "Dispatched Batch #{$batch->batch_no} ({$qtyDispatched} pcs)" . ($batch->sale ? " for Order #{$batch->sale->invoice_no}" : "")
            ]);

            // 3. Mark Batch as dispatched
            $batch->update(['status' => 'dispatched']);

            // 4. Handle Linked Customer Sale (Fulfill Order and Post to Ledger if needed)
            if ($batch->sale) {
                $sale = $batch->sale;

                // Check if all batches for this sale are dispatched
                $pendingBatchesCount = ProductionBatch::where('sale_id', $sale->id)
                    ->where('id', '!=', $batch->id)
                    ->where('status', '!=', 'dispatched')
                    ->count();

                if ($pendingBatchesCount == 0) {
                    $sale->update(['sale_status' => 'delivered']);
                }

                // If sale was booked / draft and not yet posted to Ledger, post it now
                $alreadyPosted = \App\Models\JournalEntry::where('party_type', \App\Models\Customer::class)
                    ->where('party_id', $sale->customer_id)
                    ->where('description', 'like', "%{$sale->invoice_no}%")
                    ->exists();

                if (!$alreadyPosted && $sale->customer_id) {
                    // Legacy Ledger
                    app(\App\Http\Controllers\SaleController::class)->updateLedger($sale);

                    // Professional Journal Entry (Accounts Receivable)
                    $balanceService = app(\App\Services\BalanceService::class);
                    $custForVoucher = $sale->customer_relation ?? \App\Models\Customer::find($sale->customer_id);
                    if ($custForVoucher) {
                        $balanceService->createSaleVoucher(
                            $custForVoucher,
                            $sale->total_net,
                            $sale->invoice_no,
                            $sale->created_at ? $sale->created_at->format('Y-m-d') : date('Y-m-d')
                        );
                    }

                    // Auto Receipt if cash payment was recorded
                    if ($sale->cash > 0) {
                        $transactionService = app(\App\Services\TransactionService::class);
                        $transactionService->createReceiptFromSale($sale);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', "Batch #{$batch->batch_no} marked as Dispatched! Warehouse stock deducted ({$qtyDispatched} pcs) and customer delivery updated.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Batch Dispatch Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Failed to dispatch batch: ' . $e->getMessage());
        }
    }

    public function generateInvoiceFromBatch(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0.0001',
            'unit_price' => 'required|numeric|min:0',
            'customer_type' => 'required|in:registered,walkin',
            'customer_id' => 'required_if:customer_type,registered|nullable|exists:customers,id',
            'walkin_name' => 'required_if:customer_type,walkin|nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $batch = ProductionBatch::with(['product', 'warehouse'])->findOrFail($id);

            if ($batch->status === 'dispatched') {
                throw new \Exception("Batch #{$batch->batch_no} is already marked as Dispatched/Completed.");
            }

            $product = $batch->product;
            $qtyToSell = (float) $request->quantity;

            if ($qtyToSell > (float)$batch->quantity) {
                throw new \Exception("Invoice quantity ({$qtyToSell} Pcs) exceeds available batch quantity ({$batch->quantity} Pcs).");
            }

            $whId = $batch->warehouse_id ?: 1;
            $whStock = WarehouseStock::where('product_id', $batch->product_id)
                ->where('warehouse_id', $whId)
                ->lockForUpdate()
                ->first();

            if (!$whStock || $whStock->total_pieces < $qtyToSell) {
                $avail = $whStock ? $whStock->total_pieces : 0;
                throw new \Exception("Insufficient warehouse stock for {$product->item_name}. Available: {$avail} Pcs, Requested: {$qtyToSell} Pcs.");
            }

            $isWalkin = $request->customer_type === 'walkin';
            $customerId = $isWalkin ? null : ($request->customer_id ?: null);
            $walkinName = $isWalkin ? $request->walkin_name : null;
            $unitPrice = (float) $request->unit_price;
            $discount = (float) ($request->discount ?? 0);
            $totalBill = round($qtyToSell * $unitPrice, 2);
            $totalNet = max(0, $totalBill - $discount);
            $paidNow = $request->boolean('paid_now');

            // Create Sale Invoice
            $sale = Sale::create([
                'invoice_no' => Sale::generateInvoiceNo(),
                'customer_id' => $customerId,
                'walkin_name' => $walkinName,
                'total_bill_amount' => $totalBill,
                'total_extradiscount' => $discount,
                'total_net' => $totalNet,
                'cash' => $paidNow ? $totalNet : 0,
                'change' => 0,
                'total_items' => $qtyToSell,
                'sale_status' => 'delivered',
                'discount_type' => 'pkr',
            ]);

            $ppb = ($product && $product->pieces_per_box > 0) ? $product->pieces_per_box : 1;

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'warehouse_id' => $whId,
                'brand_id' => is_numeric($product->brand_id) ? (int)$product->brand_id : null,
                'category_id' => is_numeric($product->category_id) ? (int)$product->category_id : null,
                'sub_category_id' => is_numeric($product->sub_category_id) ? (int)$product->sub_category_id : null,
                'unit_id' => is_numeric($product->unit_id) ? (int)$product->unit_id : null,
                'qty' => $ppb > 0 ? ($qtyToSell / $ppb) : $qtyToSell,
                'total_pieces' => $qtyToSell,
                'loose_pieces' => 0,
                'price' => $unitPrice,
                'price_per_piece' => $unitPrice,
                'total' => $totalBill,
                'discount_amount' => $discount,
                'discount_percent' => $totalBill > 0 ? round(($discount / $totalBill) * 100, 2) : 0,
            ]);

            // Deduct warehouse stock
            $whStock->decrement('total_pieces', $qtyToSell);
            $whStock->update(['quantity' => $whStock->total_pieces / $ppb]);

            // Add stock movement (OUT)
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'qty' => -$qtyToSell,
                'ref_type' => 'PRODUCTION_DISPATCH',
                'ref_id' => $batch->id,
                'note' => "Dispatched Batch #{$batch->batch_no} ({$qtyToSell} pcs) for Invoice #{$sale->invoice_no}"
            ]);

            // Post ledger and journal entry
            if ($customerId) {
                app(\App\Http\Controllers\SaleController::class)->updateLedger($sale);
                $balanceService = app(\App\Services\BalanceService::class);
                $custForVoucher = $sale->customer_relation ?? \App\Models\Customer::find($customerId);
                if ($custForVoucher) {
                    $balanceService->createSaleVoucher($custForVoucher, $sale->total_net, $sale->invoice_no, date('Y-m-d'));
                }
                if ($sale->cash > 0) {
                    app(\App\Services\TransactionService::class)->createReceiptFromSale($sale);
                }
            }

            // Update batch status and quantity
            if ($qtyToSell >= (float)$batch->quantity) {
                $batch->update([
                    'sale_id' => $sale->id,
                    'status' => 'dispatched',
                ]);
            } else {
                $remainingBatchQty = (float)$batch->quantity - $qtyToSell;
                $batch->update([
                    'quantity' => $remainingBatchQty,
                    'notes' => trim(($batch->notes ?? '') . " | Invoiced {$qtyToSell} Pcs on #{$sale->invoice_no}"),
                ]);
            }

            DB::commit();

            $custName = $isWalkin ? ($walkinName ?: 'Walk-in Customer') : ($sale->customer_relation->customer_name ?? 'Customer');

            return redirect()->route('production.index')->with([
                'success' => "Invoice #{$sale->invoice_no} generated and stock dispatched successfully for {$custName} ({$qtyToSell} Pcs)!",
                'last_generated_invoice_id' => $sale->id,
                'last_generated_invoice_no' => $sale->invoice_no,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
        }
    }
}

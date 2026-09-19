<?php

namespace App\Http\Controllers;

use App\Models\Formulation;
use App\Models\MaterialRequisition;
use App\Models\MaterialStockMovement;
use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\ProductionBatchItem;
use App\Models\RawMaterial;
use App\Models\Sale;
use App\Models\StockMovement;
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

        return view('admin_panel.production.index', compact('batches'));
    }

    public function create(Request $request)
    {
        $products = Product::where('is_active', 1)->orderBy('item_name')->get();

        // Get active customer sales/orders (Booked or Posted)
        $sales = Sale::with(['customer_relation', 'items.product'])
            ->whereIn('sale_status', ['booked', 'posted'])
            ->orderBy('id', 'desc')
            ->get();

        $selectedSaleId = $request->input('sale_id');
        $selectedProductId = $request->input('product_id');

        $lastBatchId = ProductionBatch::max('id') ?? 0;
        $nextBatchNo = 'BATCH-' . date('Y') . '-' . str_pad($lastBatchId + 1, 4, '0', STR_PAD_LEFT);

        return view('admin_panel.production.create', compact('products', 'sales', 'nextBatchNo', 'selectedSaleId', 'selectedProductId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.0001',
            'batch_no' => 'required|string|unique:production_batches,batch_no',
            'mfg_date' => 'required|date',
            'exp_date' => 'required|date|after_or_equal:mfg_date',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $qtyToProduce = (float) $request->quantity;
            $saleId = $request->sale_id ?: null;

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

            // Determine status: If customer sale order linked -> ready_for_delivery, else -> produced
            $status = $saleId ? 'ready_for_delivery' : 'produced';

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

            // Add finished product stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'qty' => $qtyToProduce,
                'ref_type' => 'PRODUCTION',
                'ref_id' => $batch->id,
                'note' => "Produced Batch #{$batch->batch_no} ({$qtyToProduce} pcs)"
            ]);

            // 6. Update linked Requisition and Customer Sale Order status
            if ($saleId) {
                MaterialRequisition::where('sale_id', $saleId)->update([
                    'status' => 'fulfilled',
                    'notes' => "Production completed via Batch #{$batch->batch_no}"
                ]);

                Sale::where('id', $saleId)->update([
                    'sale_status' => 'ready'
                ]);
            }

            DB::commit();

            return redirect()->route('production.index')->with('success', "Batch #{$batch->batch_no} produced successfully! Finished product stock added to warehouse.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function dispatch($id)
    {
        $batch = ProductionBatch::with(['product', 'sale.customer_relation'])->findOrFail($id);

        $batch->update(['status' => 'dispatched']);

        if ($batch->sale) {
            $batch->sale->update(['sale_status' => 'delivered']);
        }

        return redirect()->back()->with('success', "Batch #{$batch->batch_no} marked as Dispatched / Delivered to Customer!");
    }
}

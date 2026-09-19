<?php

namespace App\Services;

use App\Models\Formulation;
use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionItem;
use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\Sale;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ProductionRequisitionService
{
    /**
     * Calculate Raw Material Requirements for an array of products and quantities.
     * $items = [ ['product_id' => 1, 'qty' => 10], ... ]
     */
    public function calculateRequirements(array $items): array
    {
        $rmRequirements = [];

        foreach ($items as $item) {
            $productId = $item['product_id'] ?? null;
            $orderedPieces = (float) ($item['qty'] ?? $item['total_pieces'] ?? 0);

            if (!$productId || $orderedPieces <= 0) {
                continue;
            }

            $product = Product::find($productId);
            if (!$product) {
                continue;
            }

            // Find active formulation, fallback to latest formulation
            $formulation = Formulation::where('product_id', $productId)
                ->where('status', 'active')
                ->with(['rawMaterials.rawMaterial.unit'])
                ->first();

            if (!$formulation) {
                $formulation = Formulation::where('product_id', $productId)
                    ->with(['rawMaterials.rawMaterial.unit'])
                    ->latest()
                    ->first();
            }

            if (!$formulation || $formulation->rawMaterials->isEmpty()) {
                continue;
            }

            $batchSize = (float) ($formulation->batch_size > 0 ? $formulation->batch_size : 1);

            foreach ($formulation->rawMaterials as $frm) {
                $rm = $frm->rawMaterial;
                if (!$rm) {
                    continue;
                }

                $rmId = $rm->id;
                $baseQty = (float) $frm->quantity;
                $wastePercent = (float) ($frm->waste_percent ?? 0);

                // Quantity required per piece
                $qtyPerPiece = ($baseQty / $batchSize) * (1 + ($wastePercent / 100));
                // Total quantity required for the ordered amount
                $reqQty = round($qtyPerPiece * $orderedPieces, 4);

                $unitName = $rm->unit ? $rm->unit->name : 'Unit';

                if (!isset($rmRequirements[$rmId])) {
                    $rmRequirements[$rmId] = [
                        'raw_material_id' => $rmId,
                        'name' => $rm->name,
                        'code' => $rm->code,
                        'unit' => $unitName,
                        'required_qty' => 0.0,
                        'current_stock' => (float) ($rm->current_stock ?? 0),
                        'products' => [],
                    ];
                }

                $rmRequirements[$rmId]['required_qty'] += $reqQty;
                $rmRequirements[$rmId]['products'][] = [
                    'product_name' => $product->item_name,
                    'ordered_pieces' => $orderedPieces,
                    'rm_qty' => $reqQty,
                ];
            }
        }

        // Compute shortage
        foreach ($rmRequirements as $rmId => &$data) {
            $data['required_qty'] = round($data['required_qty'], 4);
            $data['current_stock'] = round($data['current_stock'], 4);
            $data['shortage_qty'] = max(0.0, round($data['required_qty'] - $data['current_stock'], 4));
            $data['is_short'] = $data['shortage_qty'] > 0;
        }

        return $rmRequirements;
    }

    /**
     * Check raw material stock for a Sale order, create Requisition & Notification if short.
     * If raw materials are fully available (Shortage == 0 for all), NO notification is generated.
     */
    public function checkAndCreateRequisitionForSale(Sale $sale): ?MaterialRequisition
    {
        $sale->load('items.product');

        $items = [];
        foreach ($sale->items as $item) {
            if ($item->is_manual || !$item->product_id) {
                continue;
            }
            $items[] = [
                'product_id' => $item->product_id,
                'qty' => $item->total_pieces > 0 ? $item->total_pieces : ($item->qty * ($item->product->pieces_per_box ?? 1)),
            ];
        }

        if (empty($items)) {
            return null;
        }

        $rmRequirements = $this->calculateRequirements($items);
        if (empty($rmRequirements)) {
            return null;
        }

        // Check if ANY raw material is short
        $hasShortage = false;
        $shortageList = [];

        foreach ($rmRequirements as $rmId => $data) {
            if ($data['is_short']) {
                $hasShortage = true;
                $shortageList[] = $data;
            }
        }

        // Check if a requisition already exists for this Sale Order
        $requisition = MaterialRequisition::where('sale_id', $sale->id)->first();

        // Rule: If Raw Material is COMPLETE for all items and NO prior requisition exists -> no requisition or notification needed
        if (!$hasShortage && !$requisition) {
            Log::info("Sale #{$sale->invoice_no}: Raw Materials complete. No shortage notification needed.");
            return null;
        }

        if ($requisition) {
            // Remove old items to refresh with updated requirements
            $requisition->items()->delete();
            $requisition->update([
                'notes' => "Auto-updated for Sale Order #{$sale->invoice_no}",
            ]);
        } else {
            // Create new MaterialRequisition record
            $lastReqId = MaterialRequisition::max('id') ?? 0;
            $reqNo = 'REQ-' . str_pad($lastReqId + 1, 5, '0', STR_PAD_LEFT);

            $requisition = MaterialRequisition::create([
                'sale_id' => $sale->id,
                'requisition_no' => $reqNo,
                'status' => 'pending',
                'notes' => "Auto-generated for Sale Order #{$sale->invoice_no}",
            ]);
        }

        $messageLines = [];
        $messageLines[] = "Production Requisition Needed for Sale Invoice #{$sale->invoice_no}:";

        foreach ($rmRequirements as $rmId => $data) {
            MaterialRequisitionItem::create([
                'material_requisition_id' => $requisition->id,
                'raw_material_id' => $rmId,
                'required_qty' => $data['required_qty'],
                'available_qty' => $data['current_stock'],
                'shortage_qty' => $data['shortage_qty'],
                'unit' => $data['unit'],
            ]);

            if ($data['is_short']) {
                $messageLines[] = "• {$data['name']}: Required {$data['required_qty']} {$data['unit']} | Available: {$data['current_stock']} {$data['unit']} | ⚠️ Shortage: {$data['shortage_qty']} {$data['unit']}";
            } else {
                $messageLines[] = "• {$data['name']}: Required {$data['required_qty']} {$data['unit']} | Available: {$data['current_stock']} {$data['unit']} | ✅ Complete";
            }
        }

        // Create System Notification ONLY IF there is a shortage
        if ($hasShortage) {
            $targetUserIds = User::pluck('id')->toArray();
            if (!empty($targetUserIds)) {
                $notificationData = [
                    'title' => "🏭 Production Requisition #{$requisition->requisition_no} (Sale #{$sale->invoice_no})",
                    'message' => implode("\n", $messageLines),
                    'type' => 'warning',
                    'source_id' => $requisition->id,
                    'source_type' => 'App\Models\MaterialRequisition',
                    'action_url' => route('material-requisitions.index'),
                    'is_read' => false,
                ];

                SystemNotification::createForUsers($targetUserIds, $notificationData);
            }
        }

        return $requisition;
    }
}

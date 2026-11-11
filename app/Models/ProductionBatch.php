<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $calculatedCosting = null;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function items()
    {
        return $this->hasMany(ProductionBatchItem::class);
    }

    /**
     * Get computed costing details for this production batch
     */
    public function getCostingAttribute()
    {
        if ($this->calculatedCosting !== null) {
            return $this->calculatedCosting;
        }

        return $this->calculatedCosting = $this->calculateCosting();
    }

    /**
     * Calculate item-by-item raw material & packaging material costing
     */
    public function calculateCosting()
    {
        $qty = (float) $this->quantity;
        if ($qty <= 0) $qty = 1;

        $movements = MaterialStockMovement::where('ref_type', 'PRODUCTION')
            ->where('ref_id', $this->id)
            ->where('type', 'out')
            ->get();

        $rawMaterials = [];
        $packagingMaterials = [];
        $totalRawCost = 0;
        $totalPackagingCost = 0;

        if ($movements->isNotEmpty()) {
            foreach ($movements as $m) {
                $itemClass = $m->item_type;
                $item = class_exists($itemClass) ? $itemClass::with('unit')->find($m->item_id) : null;
                $itemName = $item ? $item->name : 'Item #' . $m->item_id;
                $unitName = ($item && $item->unit) ? ($item->unit->name ?? $item->unit) : '-';

                $latestPurchasePrice = MaterialPurchaseItem::where(function($q) use ($itemClass) {
                    $q->where('item_type', $itemClass)
                      ->orWhere('item_type', '\\' . ltrim($itemClass, '\\'))
                      ->orWhere('item_type', class_basename($itemClass));
                })
                ->where('item_id', $m->item_id)
                ->latest('id')
                ->value('unit_price');

                $rate = $latestPurchasePrice !== null ? (float)$latestPurchasePrice : (float)($item->price ?? 0);
                $consumedQty = (float) $m->qty;
                $itemTotalCost = round($consumedQty * $rate, 2);
                $qtyPerPiece = $qty > 0 ? ($consumedQty / $qty) : 0;
                $costPerPiece = $qty > 0 ? round($itemTotalCost / $qty, 4) : 0;

                $record = [
                    'id' => $m->item_id,
                    'name' => $itemName,
                    'unit' => $unitName,
                    'consumed_qty' => $consumedQty,
                    'qty_per_piece' => $qtyPerPiece,
                    'rate' => $rate,
                    'total_cost' => $itemTotalCost,
                    'cost_per_piece' => $costPerPiece,
                ];

                if ($itemClass === RawMaterial::class || str_contains($itemClass, 'RawMaterial')) {
                    $rawMaterials[] = $record;
                    $totalRawCost += $itemTotalCost;
                } else {
                    $packagingMaterials[] = $record;
                    $totalPackagingCost += $itemTotalCost;
                }
            }
        } else {
            // Fallback to formulation if movement records were not generated
            $product = $this->product;
            if ($product) {
                $formulation = Formulation::where('product_id', $product->id)
                    ->with(['rawMaterials.rawMaterial.unit', 'packagingMaterials.packagingMaterial.unit'])
                    ->first();

                if ($formulation) {
                    $batchSize = (float)($formulation->batch_size > 0 ? $formulation->batch_size : 1);
                    foreach ($formulation->rawMaterials as $frm) {
                        $rm = $frm->rawMaterial;
                        if (!$rm) continue;
                        $qtyPerPiece = ($frm->quantity / $batchSize) * (1 + (($frm->waste_percent ?? 0) / 100));
                        $requiredQty = round($qtyPerPiece * $qty, 4);

                        $latestPrice = MaterialPurchaseItem::where(function($q) {
                            $q->where('item_type', RawMaterial::class)
                              ->orWhere('item_type', '\\' . ltrim(RawMaterial::class, '\\'))
                              ->orWhere('item_type', 'RawMaterial');
                        })
                        ->where('item_id', $rm->id)
                        ->latest('id')
                        ->value('unit_price');

                        $rate = $latestPrice !== null ? (float)$latestPrice : (float)($rm->price ?? 0);
                        $total = round($requiredQty * $rate, 2);

                        $rawMaterials[] = [
                            'id' => $rm->id,
                            'name' => $rm->name,
                            'unit' => ($rm->unit && isset($rm->unit->name)) ? $rm->unit->name : 'Unit',
                            'consumed_qty' => $requiredQty,
                            'qty_per_piece' => $qtyPerPiece,
                            'rate' => $rate,
                            'total_cost' => $total,
                            'cost_per_piece' => $qty > 0 ? round($total / $qty, 4) : 0,
                        ];
                        $totalRawCost += $total;
                    }

                    foreach ($formulation->packagingMaterials as $fpm) {
                        $pm = $fpm->packagingMaterial;
                        if (!$pm) continue;
                        $qtyPerPiece = ($fpm->quantity / $batchSize) * (1 + (($fpm->waste_percent ?? 0) / 100));
                        $requiredQty = round($qtyPerPiece * $qty, 4);

                        $latestPrice = MaterialPurchaseItem::where(function($q) {
                            $q->where('item_type', PackagingMaterial::class)
                              ->orWhere('item_type', '\\' . ltrim(PackagingMaterial::class, '\\'))
                              ->orWhere('item_type', 'PackagingMaterial');
                        })
                        ->where('item_id', $pm->id)
                        ->latest('id')
                        ->value('unit_price');

                        $rate = $latestPrice !== null ? (float)$latestPrice : 0;
                        $total = round($requiredQty * $rate, 2);

                        $packagingMaterials[] = [
                            'id' => $pm->id,
                            'name' => $pm->name,
                            'unit' => ($pm->unit && isset($pm->unit->name)) ? $pm->unit->name : 'Unit',
                            'consumed_qty' => $requiredQty,
                            'qty_per_piece' => $qtyPerPiece,
                            'rate' => $rate,
                            'total_cost' => $total,
                            'cost_per_piece' => $qty > 0 ? round($total / $qty, 4) : 0,
                        ];
                        $totalPackagingCost += $total;
                    }
                }
            }
        }

        $grandTotal = $totalRawCost + $totalPackagingCost;
        $costPerFinishedPiece = $qty > 0 ? round($grandTotal / $qty, 2) : 0;

        return [
            'raw_materials' => $rawMaterials,
            'packaging_materials' => $packagingMaterials,
            'total_raw_cost' => $totalRawCost,
            'total_packaging_cost' => $totalPackagingCost,
            'grand_total_cost' => $grandTotal,
            'cost_per_piece' => $costPerFinishedPiece,
        ];
    }
}

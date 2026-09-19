<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequisition;
use App\Services\ProductionRequisitionService;
use Illuminate\Http\Request;

class MaterialRequisitionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active');

        $query = MaterialRequisition::with(['sale.customer_relation', 'items.rawMaterial.unit'])
            ->orderBy('id', 'desc');

        if ($status === 'active') {
            $query->whereIn('status', ['pending', 'in_production']);
        } elseif ($status !== 'all') {
            $query->where('status', $status);
        }

        $requisitions = $query->paginate(15);

        return view('admin_panel.material_requisition.index', compact('requisitions', 'status'));
    }

    public function show($id)
    {
        $requisition = MaterialRequisition::with(['sale.customer_relation', 'items.rawMaterial.unit'])
            ->findOrFail($id);

        return view('admin_panel.material_requisition.show', compact('requisition'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_production,fulfilled',
        ]);

        $requisition = MaterialRequisition::findOrFail($id);
        $requisition->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $requisition->notes,
        ]);

        return redirect()->back()->with('success', "Requisition status updated to {$request->status}.");
    }

    public function checkRawMaterialsAjax(Request $request)
    {
        $productIds = $request->input('product_id', []);
        $quantities = $request->input('qty', []);
        $totalPiecesArr = $request->input('total_pieces', []);

        $items = [];
        foreach ($productIds as $idx => $pid) {
            if (!$pid) continue;

            $totalPieces = (float) ($totalPiecesArr[$idx] ?? 0);
            if ($totalPieces <= 0) {
                $totalPieces = (float) ($quantities[$idx] ?? 0);
            }

            if ($totalPieces > 0) {
                $items[] = [
                    'product_id' => $pid,
                    'qty' => $totalPieces,
                ];
            }
        }

        $service = new ProductionRequisitionService();
        $rmRequirements = $service->calculateRequirements($items);

        $hasShortage = false;
        foreach ($rmRequirements as $rm) {
            if ($rm['is_short']) {
                $hasShortage = true;
                break;
            }
        }

        return response()->json([
            'ok' => true,
            'requirements' => array_values($rmRequirements),
            'has_shortage' => $hasShortage,
        ]);
    }
}

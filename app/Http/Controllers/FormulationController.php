<?php

namespace App\Http\Controllers;

use App\Models\Formulation;
use App\Models\FormulationRawMaterial;
use App\Models\FormulationPackagingMaterial;
use App\Models\Product;
use App\Models\Hr\Department;
use App\Models\Unit;
use App\Models\RawMaterial;
use App\Models\PackagingMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormulationController extends Controller
{
    public function index()
    {
        $formulations = Formulation::with(['product', 'department', 'batchUnit'])->orderBy('id', 'desc')->get();
        return view('admin_panel.formulations.index', compact('formulations'));
    }

    public function create()
    {
        $products = Product::orderBy('item_name')->get();
        $departments = Department::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $rawMaterials = RawMaterial::with('unit')->where('status', 1)->orderBy('name')->get();
        $packagingMaterials = PackagingMaterial::with('unit')->where('status', 1)->orderBy('name')->get();
        
        $nextCode = 'FORM-' . str_pad(Formulation::count() + 1, 4, '0', STR_PAD_LEFT);

        return view('admin_panel.formulations.create', compact('products', 'departments', 'units', 'rawMaterials', 'packagingMaterials', 'nextCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'formulation_code' => 'required|unique:formulations',
            'product_id' => 'required|exists:products,id',
            'batch_size' => 'required|numeric|min:0.01',
            'batch_unit_id' => 'required|exists:units,id',
            'version' => 'required|string',
            'status' => 'required|in:draft,active,inactive',
            'raw_material_id' => 'array',
            'packaging_material_id' => 'array',
        ]);

        try {
            DB::beginTransaction();

            $formulation = Formulation::create([
                'formulation_code' => $request->formulation_code,
                'doc_no' => $request->doc_no,
                'batch_no' => $request->batch_no,
                'qty_of_dropper' => $request->qty_of_dropper,
                'weight_per_bottle' => $request->weight_per_bottle,
                'bmr_no' => $request->bmr_no,
                'total_packs' => $request->total_packs,
                'issue_date' => $request->issue_date,
                'mfg_date' => $request->mfg_date,
                'exp_date' => $request->exp_date,
                'company_name' => $request->company_name,
                'product_id' => $request->product_id,
                'department_id' => $request->department_id,
                'batch_size' => $request->batch_size,
                'batch_unit_id' => $request->batch_unit_id,
                'version' => $request->version,
                'effective_date' => $request->effective_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            if ($request->has('raw_material_id')) {
                for ($i = 0; $i < count($request->raw_material_id); $i++) {
                    $rmId = $request->raw_material_id[$i] ?? null;
                    if (!empty($rmId)) {
                        $rmUnitId = $request->rm_unit_id[$i] ?? null;
                        if (empty($rmUnitId) || !is_numeric($rmUnitId)) {
                            $rmObj = RawMaterial::find($rmId);
                            $rmUnitId = $rmObj ? $rmObj->unit_id : null;
                        }
                        FormulationRawMaterial::create([
                            'formulation_id' => $formulation->id,
                            'raw_material_id' => $rmId,
                            'unit_id' => $rmUnitId,
                            'quantity' => $request->rm_quantity[$i] ?? 0,
                            'waste_percent' => $request->rm_waste_percent[$i] ?? 0,
                            'notes' => $request->rm_notes[$i] ?? null,
                        ]);
                    }
                }
            }

            if ($request->has('packaging_material_id')) {
                for ($i = 0; $i < count($request->packaging_material_id); $i++) {
                    if ($request->packaging_material_id[$i]) {
                        FormulationPackagingMaterial::create([
                            'formulation_id' => $formulation->id,
                            'packaging_material_id' => $request->packaging_material_id[$i],
                            'quantity' => $request->pm_quantity[$i] ?? 0,
                            'notes' => $request->pm_notes[$i] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('formulations.index')->with('success', 'Formulation created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $formulation = Formulation::with([
            'product', 'department', 'batchUnit',
            'rawMaterials.rawMaterial', 'rawMaterials.unit',
            'packagingMaterials.packagingMaterial'
        ])->findOrFail($id);

        return view('admin_panel.formulations.show', compact('formulation'));
    }

    public function edit($id)
    {
        $formulation = Formulation::with(['rawMaterials', 'packagingMaterials'])->findOrFail($id);
        $products = Product::orderBy('item_name')->get();
        $departments = Department::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();
        $rawMaterials = RawMaterial::with('unit')->where('status', 1)->orderBy('name')->get();
        $packagingMaterials = PackagingMaterial::with('unit')->where('status', 1)->orderBy('name')->get();

        return view('admin_panel.formulations.edit', compact('formulation', 'products', 'departments', 'units', 'rawMaterials', 'packagingMaterials'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'formulation_code' => 'required|unique:formulations,formulation_code,' . $id,
            'product_id' => 'required|exists:products,id',
            'batch_size' => 'required|numeric|min:0.01',
            'batch_unit_id' => 'required|exists:units,id',
            'version' => 'required|string',
            'status' => 'required|in:draft,active,inactive',
        ]);

        try {
            DB::beginTransaction();

            $formulation = Formulation::findOrFail($id);
            $formulation->update([
                'formulation_code' => $request->formulation_code,
                'product_id' => $request->product_id,
                'department_id' => $request->department_id,
                'batch_size' => $request->batch_size,
                'batch_unit_id' => $request->batch_unit_id,
                'version' => $request->version,
                'effective_date' => $request->effective_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Clear existing and re-add
            $formulation->rawMaterials()->delete();
            $formulation->packagingMaterials()->delete();

            if ($request->has('raw_material_id')) {
                for ($i = 0; $i < count($request->raw_material_id); $i++) {
                    $rmId = $request->raw_material_id[$i] ?? null;
                    if (!empty($rmId)) {
                        $rmUnitId = $request->rm_unit_id[$i] ?? null;
                        if (empty($rmUnitId) || !is_numeric($rmUnitId)) {
                            $rmObj = RawMaterial::find($rmId);
                            $rmUnitId = $rmObj ? $rmObj->unit_id : null;
                        }
                        FormulationRawMaterial::create([
                            'formulation_id' => $formulation->id,
                            'raw_material_id' => $rmId,
                            'unit_id' => $rmUnitId,
                            'quantity' => $request->rm_quantity[$i] ?? 0,
                            'waste_percent' => $request->rm_waste_percent[$i] ?? 0,
                            'notes' => $request->rm_notes[$i] ?? null,
                        ]);
                    }
                }
            }

            if ($request->has('packaging_material_id')) {
                for ($i = 0; $i < count($request->packaging_material_id); $i++) {
                    if ($request->packaging_material_id[$i]) {
                        FormulationPackagingMaterial::create([
                            'formulation_id' => $formulation->id,
                            'packaging_material_id' => $request->packaging_material_id[$i],
                            'quantity' => $request->pm_quantity[$i] ?? 0,
                            'notes' => $request->pm_notes[$i] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('formulations.index')->with('success', 'Formulation updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $formulation = Formulation::findOrFail($id);
            // Dependencies (e.g. Production Batches) could be checked here in the future
            $formulation->delete();
            DB::commit();
            return redirect()->route('formulations.index')->with('success', 'Formulation deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}

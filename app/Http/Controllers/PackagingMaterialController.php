<?php

namespace App\Http\Controllers;

use App\Models\PackagingMaterial;
use App\Models\Hr\Department;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackagingMaterialController extends Controller
{
    /**
     * Display a listing of the packaging materials.
     */
    public function index()
    {
        $packagingMaterials = PackagingMaterial::with(['department', 'unit', 'capacityUnit'])->orderBy('id', 'desc')->get();
        $departments = Department::get();
        $units = Unit::where('status', 1)->get();
        
        return view('admin_panel.packaging_material.index', compact('packagingMaterials', 'departments', 'units'));
    }

    /**
     * Store a newly created resource in storage or update an existing one.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:packaging_materials,code,' . $request->edit_id,
            'packaging_type' => 'required|string|in:Bottle,Box,Cap,Label,Carton,Seal,Wrapper,Other',
            'variant' => 'nullable|string|max:100',
            'department_id' => 'required|exists:hr_departments,id',
            'unit_id' => 'required|exists:units,id',
            'capacity' => 'nullable|numeric|min:0',
            'capacity_unit_id' => 'nullable|exists:units,id',
            'min_stock' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if ($request->filled('edit_id')) {
            $packagingMaterial = PackagingMaterial::findOrFail($request->edit_id);
            $msg = [
                'success' => 'Packaging Material Updated Successfully',
                'reload' => true
            ];
        } else {
            $packagingMaterial = new PackagingMaterial();
            $msg = [
                'success' => 'Packaging Material Created Successfully',
                'redirect' => route('packaging_materials.index')
            ];
        }

        $packagingMaterial->name = $request->name;
        $packagingMaterial->code = strtoupper($request->code);
        $packagingMaterial->packaging_type = $request->packaging_type;
        $packagingMaterial->variant = $request->variant;
        $packagingMaterial->department_id = $request->department_id;
        $packagingMaterial->unit_id = $request->unit_id;
        $packagingMaterial->capacity = $request->capacity;
        $packagingMaterial->capacity_unit_id = $request->capacity_unit_id;
        $packagingMaterial->min_stock = $request->min_stock ?? 0;
        $packagingMaterial->description = $request->description;
        $packagingMaterial->status = $request->status;
        $packagingMaterial->save();

        $packagingMaterial->load('unit');
        $msg['data'] = $packagingMaterial;

        return response()->json($msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $packagingMaterial = PackagingMaterial::find($id);
        if ($packagingMaterial) {
            $packagingMaterial->delete();
            return response()->json([
                'success' => 'Packaging Material Deleted Successfully',
                'reload' => route('packaging_materials.index'),
            ]);
        } else {
            return response()->json(['error' => 'Packaging Material Not Found']);
        }
    }
}

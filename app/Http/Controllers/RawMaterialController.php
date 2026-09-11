<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\Hr\Department;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RawMaterialController extends Controller
{
    /**
     * Display a listing of the raw materials.
     */
    public function index()
    {
        $rawMaterials = RawMaterial::with(['department', 'unit'])->orderBy('id', 'desc')->get();
        $departments = Department::get();
        $units = Unit::where('status', 1)->get();
        
        return view('admin_panel.raw_material.index', compact('rawMaterials', 'departments', 'units'));
    }

    /**
     * Store a newly created resource in storage or update an existing one.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:raw_materials,code,' . $request->edit_id,
            'department_id' => 'required|exists:hr_departments,id',
            'unit_id' => 'required|exists:units,id',
            'type' => 'required|string|in:Ingredient,Chemical,Powder,Liquid,Other',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if ($request->filled('edit_id')) {
            $rawMaterial = RawMaterial::findOrFail($request->edit_id);
            $msg = [
                'success' => 'Raw Material Updated Successfully',
                'reload' => true
            ];
        } else {
            $rawMaterial = new RawMaterial();
            $msg = [
                'success' => 'Raw Material Created Successfully',
                'redirect' => route('raw_materials.index')
            ];
        }

        $rawMaterial->name = $request->name;
        $rawMaterial->code = strtoupper($request->code);
        $rawMaterial->department_id = $request->department_id;
        $rawMaterial->unit_id = $request->unit_id;
        $rawMaterial->type = $request->type;
        $rawMaterial->price = $request->price ?? 0;
        $rawMaterial->min_stock = $request->min_stock ?? 0;
        $rawMaterial->reorder_level = $request->reorder_level ?? 0;
        $rawMaterial->description = $request->description;
        $rawMaterial->status = $request->status;
        $rawMaterial->save();

        $rawMaterial->load('unit');
        $msg['data'] = $rawMaterial;

        return response()->json($msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $rawMaterial = RawMaterial::find($id);
        if ($rawMaterial) {
            $rawMaterial->delete();
            return response()->json([
                'success' => 'Raw Material Deleted Successfully',
                'reload' => route('raw_materials.index'),
            ]);
        } else {
            return response()->json(['error' => 'Raw Material Not Found']);
        }
    }
}

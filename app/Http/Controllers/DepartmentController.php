<?php

namespace App\Http\Controllers;

use App\Models\Hr\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the departments.
     */
    public function index()
    {
        $departments = Department::orderBy('id', 'desc')->get();
        return view('admin_panel.department.index', compact('departments'));
    }

    /**
     * Store a newly created resource in storage or update an existing one.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:departments,code,' . $request->edit_id,
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        if ($request->edit_id) {
            $department = Department::findOrFail($request->edit_id);
            $department->update([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'status' => $request->status,
            ]);
            return response()->json([
                'success' => 'Department updated successfully!',
                'reload' => true
            ]);
        } else {
            Department::create([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description,
                'status' => $request->status,
            ]);
            return response()->json([
                'success' => 'Department created successfully!',
                'redirect' => route('departments.index')
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        
        return redirect()->back()->with('success', 'Department deleted successfully!');
    }
}

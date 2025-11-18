<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->paginate(10);
        return view('admin.employees.index', compact('employees'));
    }

    public function create(Request $request, $id=null)
    {

        $employee = Employee::where('id', $id)->first();
        $title = 'Tambah Pegawai';
        return view('admin.employees.form',compact('employee','title'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id',
            'email' => 'required|email|unique:employees,email',
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'sosmed' => 'nullable',
            'status' => 'boolean',
        ]);

        if($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('employees', 'public');
        }

        $data['sosmed'] = $data['sosmed'] ?? null;

        Employee::create($data);

        return redirect()->route('employees.index')->with('success','Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        $title = 'Edit Data Pegawai';
        return view('admin.employees.form', compact('employee','title'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'employee_id' => 'required|unique:employees,employee_id,' . $employee->id,
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'sosmed' => 'nullable|array',
            'status' => 'boolean',
        ]);

        if($request->hasFile('image')){
            // Hapus foto lama
            if($employee->image){
                Storage::disk('public')->delete($employee->image);
            }
            $data['image'] = $request->file('image')->store('employees', 'public');
        }

        $data['sosmed'] = $data['sosmed'] ?? null;

        $employee->update($data);

        return redirect()->route('employees.index')->with('success','Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if($employee->image){
            Storage::disk('public')->delete($employee->image);
        }
        $employee->delete();
        return redirect()->route('employees.index')->with('success','Employee deleted successfully.');
    }
}

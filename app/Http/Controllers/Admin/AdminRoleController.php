<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminRole;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    public function index()
    {
        $roles = AdminRole::with('admin')->get();
        $admins = Admin::orderBy('name')->get();
        $modules = ['Order', 'Product', 'User', 'FileManager', 'SystemInfo']; // daftar modul
        return view('admin.roles.index', compact('roles', 'admins', 'modules'));
    }

    public function create()
    {
        $admins = Admin::orderBy('name')->get();
        $modules = ['Order', 'Product', 'User', 'Settings']; // daftar modul yang bisa dipilih

        return view('admin.roles.create', compact('admins', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'module' => 'required|array',
        ]);

        AdminRole::create([
            'admin_id' => $request->admin_id,
            'module' => $request->module, // array akan otomatis disimpan sebagai JSON
            'view_access' => $request->has('view_access'),
            'edit_access' => $request->has('edit_access'),
            'full_access' => $request->has('full_access'),
        ]);

        return redirect()->back()->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(AdminRole $role)
    {
        $admins = Admin::orderBy('name')->get();
        $modules = ['Order', 'Product', 'User', 'FileManager', 'System','AdminRole','Admin'];
        return view('admin.roles.edit', compact('role', 'admins', 'modules'));
    }

    public function update(Request $request, AdminRole $role)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'module' => 'required|array',
        ]);

        $role->update([
            'admin_id' => $request->admin_id,
            'module' => $request->module,
            'view_access' => $request->has('view_access'),
            'edit_access' => $request->has('edit_access'),
            'full_access' => $request->has('full_access'),
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(AdminRole $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Role berhasil dihapus.');
    }
}

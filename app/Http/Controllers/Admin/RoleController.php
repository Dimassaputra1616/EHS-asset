<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\ActivityLogger;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('admin.roles.edit', $row->id).'" class="btn btn-action btn-edit me-1" title="Edit"><i class="bi bi-pencil"></i></a>';
                    if($row->name != 'admin'){
                        $btn .= '<form action="'.route('admin.roles.destroy', $row->id).'" method="POST" class="d-inline">
                                    '.csrf_field().method_field('DELETE').'
                                    <button type="submit" class="btn btn-action btn-delete" title="Delete" onclick="return confirm(\'Are you sure?\')"><i class="bi bi-trash"></i></button>
                                </form>';
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.roles.index');
    }

    public function create()
    {
        $permissions = Permission::get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permission);

        ActivityLogger::log('Create Role', "Admin created role '{$role->name}' with permissions: " . implode(', ', $request->permission));

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'permission' => 'required',
        ]);

        $oldName = $role->name;
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permission);

        ActivityLogger::log('Update Role', "Admin updated role '{$oldName}' (now: '{$role->name}') with permissions: " . implode(', ', $request->permission));

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        if ($role->name == 'admin') {
            return redirect()->route('admin.roles.index')->with('error', 'Cannot delete admin role');
        }
        
        $roleName = $role->name;
        $role->delete();

        ActivityLogger::log('Delete Role', "Admin deleted role '{$roleName}'.");

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
    }
}

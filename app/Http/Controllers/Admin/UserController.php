<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles')->select('users.*');
            $allRoles = Role::pluck('name')->toArray();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function($row) use ($allRoles){
                    $userRoles = $row->roles->pluck('name')->toArray();
                    $currentRole = reset($userRoles) ?: '';
                    
                    if ($row->id == auth()->id()) {
                        // Return static pill badge for locked self to prevent text truncation issues
                        return '<span class="role-select-locked"><i class="bi bi-shield-lock me-1"></i>'.ucfirst($currentRole).' (Locked)</span>';
                    }
                    
                    $options = '';
                    foreach ($allRoles as $role) {
                        $selected = ($role == $currentRole) ? 'selected' : '';
                        $options .= '<option value="'.$role.'" '.$selected.'>'.ucfirst($role).'</option>';
                    }
                    
                    $roleClass = ($currentRole == 'admin') ? 'role-admin' : (($currentRole == 'staff') ? 'role-staff' : 'role-other');
                    
                    return '<select class="role-select role-select-badge '.$roleClass.'" data-user-id="'.$row->id.'">
                                '.$options.'
                            </select>';
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('admin.users.edit', $row->id).'" class="btn btn-action btn-edit me-1" title="Edit"><i class="bi bi-pencil"></i></a>';
                    if($row->id != auth()->id()){
                        $btn .= '<form action="'.route('admin.users.destroy', $row->id).'" method="POST" class="d-inline">
                                    '.csrf_field().method_field('DELETE').'
                                    <button type="submit" class="btn btn-action btn-delete" title="Delete" onclick="return confirm(\'Are you sure?\')"><i class="bi bi-trash"></i></button>
                                </form>';
                    }
                    return $btn;
                })
                ->rawColumns(['role', 'action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRole = $user->roles->pluck('name')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'roles' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if(!empty($request->password)){
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function updateRole(Request $request, User $user)
    {
        if ($user->id == auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own role.'
            ], 403);
        }

        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user->syncRoles([$request->role]);

        return response()->json([
            'success' => true,
            'message' => "Role of {$user->name} successfully updated to " . ucfirst($request->role) . "."
        ]);
    }
}

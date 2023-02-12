<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //Role
    function role() {
        $permission = Permission::all();
        $roles = Role::all();
        $users = User::all();
        return view('admin.role.role', [
            'permission'=> $permission,
            'roles'=> $roles,
            'users'=> $users,
        ]);
    }

    // Add permission
    function add_permission(Request $request) {
        Permission::create(['name' => $request->permission]);
        return back()->with('permission', $request->permission.' permission created successfully');
    }

    // Add Role
    function add_role(Request $request) {
        $role = Role::create(['name' => $request->role_name]);
        $role->givePermissionTo($request->permission);
        return back()->with('role', $request->role_name.' role created successfully');
    }

    // asign_role
    function asign_role(Request $request) {
        $user = User::find($request->user_id);
        // For Multiple roles
        // $user->assignRole($request->role_id);
        // For single roles
        $user->syncRoles($request->role_id);
        return back()->with('assign','Role assigned successfully');
    }

    // remove role
    function remove_role($user_id) {
        $user = User::find($user_id);
        $user->syncRoles([]);
        $user->syncPermissions([]);
        return back()->with('remove','Role removed successfully');
    }

    // edit role
    function edit_role($role_id) {
        $permission = Permission::all();
        $role_info = Role::find($role_id);
        return view('admin.role.edit_role', [
            'role' => $role_info,
            'permissions' => $permission,
        ]);
    }

    // update role
    function update_role(Request $request) {
        $role = Role::find($request->role_id);
        $role->syncPermissions([$request->permission]);
        return back()->withSuccess('Role updated successfully');
    }
}

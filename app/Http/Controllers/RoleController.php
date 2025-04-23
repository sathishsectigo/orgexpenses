<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array', // Ensure permissions is an array
            'permissions.*' => 'exists:permissions,id', // Each permission must exist in the permissions table
        ]);

        // Create the role
        $role = Role::create(['name' => $request->name]);

        // Attach selected permissions to the role
        $role->permissions()->attach($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array', // Ensure permissions is an array
            'permissions.*' => 'exists:permissions,id', // Each permission must exist in the permissions table
        ]);
    
        // Update the role's name
        $role->update(['name' => $request->name]);
    
        // Sync permissions (this will update the role_permissions table)
        $role->permissions()->sync($request->permissions ?? []);
    
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $role = Roles::create($request->all());

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    public function getAllRoles()
    {
        $roles = Roles::all();
        return response()->json($roles);
    }

    public function getRole($id)
    {
        $role = Roles::findOrFail($id);
        return response()->json($role);
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $role = Roles::findOrFail($id);
        $role->update($request->all());

        return response()->json(['message' => 'Role updated successfully', 'role' => $role], 200);
    }

    public function deleteRole($id)
    {
        $role = Roles::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully'], 204);
    }
}

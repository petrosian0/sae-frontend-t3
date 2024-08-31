<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('roles'); // The Blade view for managing roles
    }

    public function fetchRolesData()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function show($id)
    {
        try {
            $role = Role::findOrFail($id);
            return response()->json($role);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Role not found.'], 404);
        }
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $validated = $request->validate([
            'role_name' => 'required|string|max:255',
            'is_active' => 'required|integer',
        ]);

        // Create the role
        $role = Role::create($validated);

        // Return success response
        return response()->json(['success' => 'Role created successfully.', 'role' => $role]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:255',
            'is_active' => 'required|integer',
        ]);

        $role = Role::findOrFail($id);
        $role->update($validated);

        return response()->json(['success' => 'Role updated successfully.']);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['success' => 'Role deleted successfully.']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(): JsonResponse
    {
        $roles = Role::all();
        return response()->json([
            'status' => true,
            'data' => $roles
        ], 200);
    }

    /**
     * Provide all permissions (useful for the "Create Role" form).
     */
    public function create(): JsonResponse
    {
        $permissions = Permission::all();
        return response()->json([
            'status' => true,
            'data' => $permissions
        ], 200);
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:100|unique:roles,name',
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id',
        ], [
            'name.required' => 'Role name is required.',
            'name.unique' => 'This role name already exists.',
            'permission.required' => 'At least one permission must be selected.',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        // Sync permissions using IDs
        $role->syncPermissions($validated['permission']);

        return response()->json([
            'status' => true,
            'message' => 'Role created successfully',
            'data' => $role->load('permissions')
        ], 201);
    }

    /**
     * Display the specific role with its permissions.
     */
    public function show($id): JsonResponse
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Role not found'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $role
        ], 200);
    }

    /**
     * Edit data: Returns the role, its current permissions, and ALL available permissions.
     */
    public function edit($id): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Role not found'], 404);
        }

        $allPermissions = Permission::all();
        $rolePermissions = $role->permissions()->pluck('id')->toArray();

        return response()->json([
            'status' => true,
            'data' => [
                'role' => $role,
                'all_permissions' => $allPermissions,
                'role_permissions_ids' => $rolePermissions
            ]
        ], 200);
    }

    /**
     * Update the role and its permissions.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Role not found'], 404);
        }

        $validated = $request->validate([
            'name' => ['required', 'max:100', Rule::unique('roles')->ignore($id)],
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id',
        ], [
            'name.required' => 'Role name is required.',
            'permission.required' => 'At least one permission must be selected.',
        ]);

        $role->update(['name' => $validated['name']]);

        // Sync permissions
        $role->syncPermissions($validated['permission']);

        return response()->json([
            'status' => true,
            'message' => 'Role updated successfully',
            'data' => $role->load('permissions')
        ], 200);
    }

    /**
     * Delete the role.
     */
    public function destroy($id): JsonResponse
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['status' => false, 'message' => 'Role not found'], 404);
        }

        // Optional: Check if role is assigned to users before deleting
        if ($role->users()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete role assigned to users.'
            ], 422);
        }

        $role->delete();

        return response()->json([
            'status' => true,
            'message' => 'Role deleted successfully'
        ], 200);
    }
}

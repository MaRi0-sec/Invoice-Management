<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return response()->json(['data' => $users], 200);
    }

    public function create()
    {
        $roles = Role::all();
        return response()->json(['data' => $roles], 200);
    }

    public function store(StoreUsersRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'status'   => $validated['status'],
        ]);

        if (isset($validated['roles_name'])) {
            $user->assignRole($validated['roles_name']);
        }

        return response()->json([
            'status'  => true,
            'message' => 'User created successfully',
            'data'    => $user
        ], 201);
    }

    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $roles = Role::all();

        return response()->json([
            'status' => true,
            'data' => [
                'user'  => $user,
                'roles' => $roles
            ]
        ], 200);
    }

    public function update(UpdateUsersRequest $request, $id)
    {
        $validated = $request->validated();

        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'This User Not Exists'], 404);
        }
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        };

        $user->update($validated);

        $user->syncRoles($validated['roles_name']);

        return response()->json(['status' => true, 'message' => 'Edit User'], 200);
    }

    public function destroy($user_id)
    {
        $user = User::where('id', $user_id)->first();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'This User Not Exists'], 404);
        }
        $user->forceDelete();

        return response()->json(['status' => true, 'message' => 'Delete Done'], 200);
    }
}

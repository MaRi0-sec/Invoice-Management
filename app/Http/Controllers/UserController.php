<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUsersRequest;
use App\Http\Requests\UpdateUsersRequest;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:قائمة المستخدمين')->only(['index']);

        $this->middleware('permission:اضافة مستخدم')->only([
            'create',
            'store'
        ]);

        $this->middleware('permission:تعديل مستخدم')->only([
            'edit',
            'update'
        ]);

        $this->middleware('permission:حذف مستخدم')->only([
            'destroy'
        ]);
    }

    public function index()
    {
        $users = User::all();
        return view('pages.users.show_users', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('pages.users.add', compact('roles'));
    }

    public function store(StoreUsersRequest $request)
    {

        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'status' => $validated['status'],
        ]);

        $user->assignRole($request->roles_name);

        return redirect()->route('users.index')
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('pages.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUsersRequest $request, $id)
    {
        $validated = $request->validated();

        $user = User::findOrFail($id);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        };

        $user->update($validated);

        $user->syncRoles($request->roles_name);

        return redirect()->route('Users.index')
            ->with('success', 'تم التعديل بنجاح');
    }

    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        $user->forceDelete();

        return redirect()->route('Users.index')
            ->with('delete', 'تم حذف المستخدم');
    }
}

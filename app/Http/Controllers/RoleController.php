<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function __construct()
    {
        // عرض الصلاحيات
        $this->middleware('permission:عرض صلاحية')->only([
            'index',
            'show'
        ]);

        // إضافة صلاحية
        $this->middleware('permission:اضافة صلاحية')->only([
            'create',
            'store'
        ]);

        // تعديل صلاحية
        $this->middleware('permission:تعديل صلاحية')->only([
            'edit',
            'update'
        ]);

        // حذف صلاحية
        $this->middleware('permission:حذف صلاحية')->only([
            'destroy'
        ]);
    }

    public function index(Request $request)
    {
        $roles = Role::all();
        return view('pages.roles.index', compact('roles'));
    }

    public function create()
    {
        $permission = Permission::get();
        return view('pages.roles.create', compact('permission'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate(
            [
                'name' => 'required|max:100|unique:roles,name',
                'permission' => 'required|array',
                'permission.*' => 'exists:permissions,id',
            ],
            [
                'name.required' => 'حقل اسم الصلاحية مطلوب',
                'name.max' => 'اسم الصلاحية يجب ألا يتجاوز 100 حرف',
                'name.unique' => 'اسم الصلاحية مستخدم بالفعل',

                'permission.required' => 'يجب اختيار صلاحية واحدة على الأقل',
                'permission.*.exists' => 'الصلاحية المختارة غير موجودة في النظام',
            ]
        );

        $permission = Permission::whereIn('id', $validated['permission'])->get();

        $newRole = Role::create([
            'name' => $validated['name'],
        ]);

        $newRole->givePermissionTo($permission);

        return redirect()->route('roles.index')->with('success', 'تم اضافة الصلاحيه بنجاح');
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return view('pages.roles.show', compact('role'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::all();

        return view('pages.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'max:100', Rule::unique('roles')->ignore($id)],
                'permission' => 'required|array',
                'permission.*' => 'exists:permissions,id',
            ],
            [
                'name.required' => 'حقل اسم الصلاحية مطلوب',
                'name.max' => 'اسم الصلاحية يجب ألا يتجاوز 100 حرف',
                'name.unique' => 'اسم الصلاحية مستخدم بالفعل',

                'permission.required' => 'يجب اختيار صلاحية واحدة على الأقل',
                'permission.*.exists' => 'الصلاحية المختارة غير موجودة في النظام',
            ]
        );

        $role = Role::findOrFail($id);

        $role->update([
            'name' => $validated['name'],
        ]);

        $permission = Permission::whereIn('id', $validated['permission'])->get();

        $role->syncPermissions($permission);

        return back()->with('success', 'تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        $role->forceDelete();

        return redirect()->route('roles.index')
            ->with('delete', 'تم الحذف بنجاح');
    }
}

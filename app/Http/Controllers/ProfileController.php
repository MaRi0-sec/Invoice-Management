<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.profile');
    }

    public function update(Request $request, $id)
    {

        if ((int)$id !== (int)Auth::id()) {
            return back()->with('error', 'غير مصرح لك بتعديل هذا الملف.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            "email" =>
            [
                'required',
                'email',
                Rule::unique('users')->ignore(auth::id())
            ],
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        return back()->with('success', 'تم التحديث بنجاح');
    }
}

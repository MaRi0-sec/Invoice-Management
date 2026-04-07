<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class ResetPasswordController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'email' => ['required', 'email']
            ]
        );

        $status = Password::sendResetLink($request->only('email'));

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required'],

            'email' => ['required', 'email', 'exists:users,email'],

            'password' => [
                'required',
                'confirmed', // لازم يطابق password_confirmation
                PasswordRule::min(8)
                //->mixedCase()   // حروف كبيرة وصغيرة
                //->letters()     // يحتوي على حروف
                //->numbers()     // يحتوي على أرقام
                //->symbols()     // يحتوي على رموز
                //->uncompromised(), // مش موجود في leaks
            ],
        ]);


        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),

            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'تم تغيير كلمة المرور بنجاح')
            : back()->withErrors(['email' => [__($status)]]);
    }
}

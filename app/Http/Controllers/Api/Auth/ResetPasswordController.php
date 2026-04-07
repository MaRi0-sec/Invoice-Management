<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى البريد الإلكتروني'
            ], 200);
        }

        return response()->json([
            'message'   => 'فشل في إرسال الرابط',
            'errors'    => ['email' => __($status)]
        ], 400);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],

            'email' => ['required', 'email', 'exists:users,email'],

            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8),
            ],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),

            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'تم تغيير كلمة المرور بنجاح'
            ], 200);
        }

        return response()->json([
            'message' => 'فشل في إعادة تعيين كلمة المرور',
            'errors' => ['email' => [__($status)]]
        ], 400);
    }
}

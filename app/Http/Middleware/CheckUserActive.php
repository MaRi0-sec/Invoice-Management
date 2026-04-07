<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckUserActive
{

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->status == 0) {
            Auth::logout();
            Session::invalidate();
            Session::regenerateToken();
            return redirect()->route('login')->with('error', ' الحساب غير مفعل سيتم التفعيل خلال 48 ساعه');
        }
        return $next($request);
    }
}

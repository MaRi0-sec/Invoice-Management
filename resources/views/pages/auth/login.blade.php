@extends('layouts.master2')

@section('css')
<style>
    body { background-color: #f4f7fb; }
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        width: 100%;
        max-width: 450px;
    }
    .project-name {
        font-size: 24px;
        font-weight: 700;
        color: #0162e8;
        text-align: center;
        margin-bottom: 5px;
    }
    .login-title {
        text-align: center;
        color: #777;
        font-size: 14px;
        margin-bottom: 30px;
    }
    .form-control {
        height: 45px;
        border-radius: 8px;
    }
    .btn-login {
        background: linear-gradient(45px, #0162e8, #014db7);
        color: white;
        height: 48px;
        font-weight: 600;
        border-radius: 10px;
        width: 100%;
        border: none;
        margin-top: 15px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important ; 
        box-shadow: 0 4px 15px rgba(1, 98, 232, 0.2);
        cursor: pointer;
    }

    .btn-login:hover {
        background: #014db7;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(1, 98, 232, 0.3);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <h2 class="project-name">نظام إدارة الفواتير</h2>
        <p class="login-title">سجل دخولك للمتابعة</p>

        @if (session('status'))
            <div class="alert alert-success text-center mb-3">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger text-center mb-3">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="form-group mb-3">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="email@example.com">
            </div>

            <div class="form-group mb-2">
                <div class="d-flex justify-content-between">
                    <label>كلمة المرور</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('forgetPassword') }}" class="text-sm text-primary">نسيت كلمة السر؟</a>
                    @endif
                </div>
                <input type="password" name="password" class="form-control" required placeholder="********">
            </div>

            <div class="form-check mb-4 mt-2 d-flex align-items-center">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" style="margin-inline-end: 10px;">
                <label class="form-check-label text-muted" for="remember" style="padding-inline-start: 25px;">
                    تذكرني
                </label>
            </div>

            <button type="submit" class="btn btn-login">تسجيل الدخول</button>
        </form>

        @if (Route::has('register'))
            <div class="text-center mt-4 text-muted">
                ليس لديك حساب؟ <a href="{{ route('register') }}" class="text-primary">إنشاء حساب جديد</a>
            </div>
        @endif
    </div>
</div>
@endsection
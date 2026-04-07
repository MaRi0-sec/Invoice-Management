@extends('layouts.master2')

@section('css')
<style>
    body { background-color: #f4f7fb; }
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
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
        border: 1px solid #e1e5ef;
    }
    .form-control:focus {
        border-color: #0162e8;
        box-shadow: 0 0 0 0.2rem rgba(1, 98, 232, 0.1);
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important; 
        box-shadow: 0 4px 15px rgba(1, 98, 232, 0.2);
        cursor: pointer;
    }
    .btn-login:hover {
        background: #014db7;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(1, 98, 232, 0.3);
        color: white;
    }
    label {
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }
</style>
@endsection

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <h2 class="project-name">نظام إدارة الفواتير</h2>
        <p class="login-title">إنشاء حساب جديد للمتابعة</p>

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 px-3">
                    @foreach ($errors->all() as $error)
                        <li class="tx-13">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="form-group mb-3">
                <label>الاسم بالكامل</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus placeholder="أدخل اسمك بالكامل">
            </div>

            <div class="form-group mb-3">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="email@example.com">
            </div>

            <div class="form-group mb-3">
                <label>كلمة المرور</label>
                <input type="password" name="password" class="form-control" required placeholder="********">
            </div>

            <div class="form-group mb-4">
                <label>تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="********">
            </div>

            <button type="submit" class="btn btn-login">إنشاء حساب</button>
        </form>

        <div class="text-center mt-4 text-muted">
            لديك حساب بالفعل؟ <a href="{{ route('login') }}" class="text-primary">تسجيل الدخول</a>
        </div>
    </div>
</div>
@endsection
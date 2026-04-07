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
        line-height: 1.6;
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
        margin-bottom: 8px;
        color: #333;
    }
</style>
@endsection

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <h2 class="project-name">نظام إدارة الفواتير</h2>
        <p class="login-title">نسيت كلمة السر؟ أدخل بريدك الإلكتروني لإرسال رابط استعادة الوصول.</p>

        @if (session('status'))
            <div class="alert alert-success text-center mb-4 tx-13">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0 px-3 tx-13">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('resetPassword') }}">
            @csrf

            <div class="form-group mb-4">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="email@example.com">
            </div>

            <button type="submit" class="btn btn-login">إرسال رابط استعادة كلمة المرور</button>
        </form>

        <div class="text-center mt-4 text-sm text-muted">
            أو العودة إلى <a href="{{ route('login') }}" class="text-primary font-weight-bold">تسجيل الدخول</a>
        </div>
    </div>
</div>
@endsection
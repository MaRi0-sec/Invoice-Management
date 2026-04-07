@extends('layouts.master')

@section('css')
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />
@endsection

@section('page-header')





    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">ملف المستخدم</h2>
                <p class="mg-b-0 text-muted">عرض وتعديل بيانات الحساب الشخصي</p>
            </div>
        </div>
    </div>
    @endsection

@section('content')
    <div class="row row-sm">
        <div class="col-lg-4">
            <div class="card mg-b-20">
                <div class="card-body">
                    <div class="pl-0">
                        <div class="main-profile-overview">
                            <div class="main-img-user profile-user text-center">
                                <img alt="" src="{{URL::asset('assets/img/faces/6.jpg')}}" class="rounded-circle" style="width: 120px; height: 120px; border: 3px solid #705ec8;">
                            </div>
                            <div class="d-flex justify-content-between mg-b-20 mt-3">
                                <div>
                                    <h5 class="main-profile-name text-primary">{{ Auth::user()->name }}</h5>
                                    <p class="main-profile-name-text text-muted">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <h6>نبذة تعريفية</h6>
                            <div class="main-profile-bio">
                                مستخدم نشط في نظام إدارة الفواتير، لديه صلاحيات كاملة لإدارة المعاملات المالية.
                            </div>
                            <hr class="mg-y-10">
                            <label class="main-content-label tx-13 mg-b-15">التواصل</label>
                            <div class="main-profile-social-list">
                                <div class="media">
                                    <div class="media-icon bg-primary-gradient text-white">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="media-body">
                                        <span>رقم الهاتف</span>
                                        <div>010XXXXXXXX</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطا</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


                    <div class="tabs-menu">
                        <ul class="nav nav-tabs profile-nav border-0">
                            <li class=""><a href="#edit" class="active btn btn-primary-gradient" data-toggle="tab">تعديل الملف الشخصي</a></li>
                        </ul>
                    </div>
                    <div class="tab-content border-0">
                        <div class="tab-pane active" id="edit">
                            <form class="form-horizontal mt-4" action="{{route('update-profile' , Auth::user()->id)}}" method="POST">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">الاسم</label>
                                        </div>
                                        <input type="hidden" class="form-control" name="id" value="{{ Auth::user()->id }}">
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="name" placeholder="اسم المستخدم" value="{{ Auth::user()->name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">البريد الإلكتروني</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="email" class="form-control" name="email" placeholder="Email" value="{{ Auth::user()->email }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">كلمة المرور الجديدة</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" name="password" placeholder="اتركه فارغاً إذا لا تريد التغيير">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">تأكيد كلمة المرور </label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="اتركه فارغاً إذا لا تريد التغيير">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card-footer text-left">
                                    <button type="submit" class="btn btn-primary-gradient waves-effect waves-light">تحديث البيانات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script src="{{URL::asset('assets/js/modal-popup.js')}}"></script>
<script src="{{URL::asset('assets/js/index.js')}}"></script>
@endsection
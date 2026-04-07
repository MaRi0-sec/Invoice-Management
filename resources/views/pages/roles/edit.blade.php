@extends('layouts.master')
@section('css')
<!--Internal  Font Awesome -->
<link href="{{URL::asset('assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<!--Internal  treeview -->
<link href="{{URL::asset('assets/plugins/treeview/treeview-rtl.css')}}" rel="stylesheet" type="text/css" />
    <!--Internal   Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

@section('title')
تعديل الصلاحيات - مورا سوفت للادارة القانونية
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الصلاحيات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل
                الصلاحيات</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')

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


@if (session()->has('success'))
    <script>
        window.onload = function() {
            notif({
                msg: " تم التعديل بنجاح",
                type: "success"
            });
        }

    </script>
@endif


<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('put')

    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mg-b-20">
                <div class="card-body">

                    <div class="main-content-label mg-b-5">
                        <div class="col-xs-7 col-sm-7 col-md-7">
                            <div class="form-group">
                                <p>اسم الصلاحية :</p>

                                <input type="text" name="name" value="{{$role->name}}" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <!-- col -->
                        <div class="col-lg-4">
                            <ul id="treeview1">
                                <li>
                                    <a href="#">الصلاحيات</a>
                                    <ul>

                                        @foreach($permissions as $permission)

                                            <label style="font-size:16px;">
                                                <input type="checkbox" 
                                                    name="permission[]" 
                                                    
                                                    value="{{ $permission->id }}"
                                                    
                                                    {{$role->permissions->contains($permission->id) ? 'checked' : ''}}

                                                    class="name">
                                                    
                                                {{ $permission->name }}
                                            </label><br>

                                        @endforeach

                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <!-- /col -->

                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                            <button type="submit" class="btn btn-main-primary">
                                تاكيد
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</form>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Treeview js -->
<script src="{{URL::asset('assets/plugins/treeview/treeview.js')}}"></script>
<!--Internal  Notify js -->
<script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
<div class="main-header sticky side-header nav nav-item">
    <div class="container-fluid">
        <div class="main-header-left">
            <div class="responsive-logo">
                <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/logo.png')}}" class="logo-1" alt="logo"></a>
                <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/logo-white.png')}}" class="dark-logo-1" alt="logo"></a>
                <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="logo-2" alt="logo"></a>
                <a href="{{ url('/' . $page='index') }}"><img src="{{URL::asset('assets/img/brand/favicon.png')}}" class="dark-logo-2" alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>
        </div>

        <div class="main-header-right">
            <div class="nav nav-item navbar-nav-right ml-auto">
                
		<div class="dropdown nav-item main-header-notification">
			<a class="new nav-link" href="#">
				<svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
					<path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
				</svg>
				@if(auth()->user()->unreadNotifications->count() > 0)
					<span class="pulse"></span>
				@endif
			</a>
			<div class="dropdown-menu shadow-lg" style="width: 300px; border-radius: 10px; overflow: hidden;">
				<div class="menu-header-content bg-primary text-right p-3">
					<div class="d-flex align-items-center justify-content-between">
						<h6 class="dropdown-title mb-0 tx-15 text-white font-weight-semibold">الإشعارات</h6>
						<span class="badge badge-pill badge-warning">{{auth()->user()->unreadNotifications->count()}} جديد</span>
					</div>
				</div>
				
				<div class="main-notification-list" style="max-height: 350px; overflow-y: auto;">
					@forelse(auth()->user()->unreadNotifications as $notification)
						<a class="d-flex p-3 border-bottom" href="{{route('invoiceDetails.edit', $notification->data['invoice_id'])}}" style="text-decoration: none; transition: background 0.2s;">
							<div class="wd-100p">
								<div class="d-flex justify-content-between align-items-center mb-1">
									<h5 class="notification-label mb-0 text-dark font-weight-bold" style="font-size: 13px;">
										{{$notification->data['message'] ?? 'إشعار جديد'}}
									</h5>
									<small class="text-primary" style="font-size: 10px;">{{$notification->created_at->diffForHumans()}}</small>
								</div>
								<p class="mb-0 text-muted tx-12">بواسطة: {{ $notification->data['auth_invoice'] ?? 'النظام' }}</p>
							</div>
						</a>
					@empty
						<div class="p-4 text-center text-muted">
							<i class="far fa-bell-slash d-block mb-2 tx-30"></i>
							<span class="tx-13">لا توجد إشعارات غير مقروءة</span>
						</div>
					@endforelse
				</div>
				
				<div class="dropdown-footer text-center p-2 bg-light">
					<a href="#" class="tx-12 font-weight-bold">عرض جميع الإشعارات</a>
				</div>
			</div>
		</div>

                <div class="dropdown main-profile-menu nav nav-item nav-link">
                    <a class="profile-user d-flex" href="">
                        <img alt="user-img" src="{{URL::asset('assets/img/faces/6.jpg')}}" class="rounded-circle shadow-sm">
                    </a>
                    <div class="dropdown-menu">
                        <div class="main-header-profile bg-primary p-3">
                            <div class="d-flex wd-100p">
                                <div class="main-img-user">
                                    <img alt="user-img" src="{{URL::asset('assets/img/faces/6.jpg')}}" class="border">
                                </div>
                                <div class="mr-3 my-auto">
                                    <h6 class="text-white mb-0">{{ auth()->user()->name }}</h6>
                                    <span class="text-white-80 small">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{route('profile')}}"><i class="bx bx-user-circle"></i> الملف الشخصي</a>
                        <hr class="my-2">
                        <form action="{{route('logout')}}" method="post">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bx bx-log-out"></i> تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
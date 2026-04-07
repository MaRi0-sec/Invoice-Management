<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">

            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-xl brround"
                        src="{{ URL::asset('assets/img/faces/6.jpg') }}"><span
                        class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::user()->name }}</h4>
                    <span class="mb-0 text-muted">{{ Auth::user()->email }}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="slide">
                <a class="side-menu__item" href="{{ route('home') }}"><svg
                        xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                        <path
                            d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                    </svg><span class="side-menu__label">الرئيسية</span></a>
            </li>

@can('الفواتير')
<li class="side-item side-item-category">الفواتير</li>

<li class="slide">
    <a class="side-menu__item" data-toggle="slide" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
            <path d="M0 0h24v24H0V0z" fill="none" />
            <path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3" />
            <path d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2z"/>
        </svg>

        <span class="side-menu__label">الفواتير</span>
        <i class="angle fe fe-chevron-down"></i>
    </a>

    <ul class="slide-menu">

        @can('قائمة الفواتير')
        <li>
            <a class="slide-item" href="{{ route('invoice.index') }}">
                قائمة الفواتير
            </a>
        </li>
        @endcan


        @can('الفواتير المدفوعة جزئيا')
        <li>
            <a class="slide-item" href="{{ route('partialInvoices') }}">
                الفواتير المدفوعة جزئيا
            </a>
        </li>
        @endcan


        @can('ارشيف الفواتير')
        <li>
            <a class="slide-item" href="{{ route('archive.index') }}">
                ارشيف الفواتير
            </a>
        </li>
        @endcan

    </ul>
</li>
@endcan

@can('المستخدمين')
<li class="side-item side-item-category">المستخدمين</li>

<li class="slide">
    <a class="side-menu__item" data-toggle="slide" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
            <path d="M0 0h24v24H0V0z" fill="none"/>
            <path d="M15 11V4H4v8.17l.59-.58.58-.59H6z" opacity=".3"/>
            <path d="M21 6h-2v9H6v2c0 .55.45 1 1 1h11l4 4V7c0-.55-.45-1-1-1z"/>
        </svg>

        <span class="side-menu__label">المستخدمين</span>
        <i class="angle fe fe-chevron-down"></i>
    </a>

    <ul class="slide-menu">

        @can('قائمة المستخدمين')
        <li>
            <a class="slide-item" href="{{ route('Users.index') }}">
                قائمة المستخدمين
            </a>
        </li>
        @endcan


        @can('صلاحيات المستخدمين')
        <li>
            <a class="slide-item" href="{{ route('Roles.index') }}">
                صلاحيات المستخدمين
            </a>
        </li>
        @endcan

    </ul>
</li>
@endcan

@can('الاعدادات')
<li class="side-item side-item-category">الاعدادات</li>

<li class="slide">
    <a class="side-menu__item" data-toggle="slide" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
            class="side-menu__icon" viewBox="0 0 24 24">
            <g>
                <rect fill="none" />
            </g>
            <g>
                <path
                    d="M21,5c-1.11-0.35-2.33-0.5-3.5-0.5c-1.95,0-4.05,0.4-5.5,1.5c-1.45-1.1-3.55-1.5-5.5-1.5S2.45,4.9,1,6v14.65
                    c0,0.25,0.25,0.5,0.5,0.5c0.1,0,0.15-0.05,0.25-0.05C3.1,20.45,5.05,20,6.5,20c1.95,0,4.05,0.4,5.5,1.5
                    c1.35-0.85,3.8-1.5,5.5-1.5c1.65,0,3.35,0.3,4.75,1.05c0.1,0.05,0.15,0.05,0.25,0.05c0.25,0,0.5-0.25,0.5-0.5V6
                    C22.4,5.55,21.75,5.25,21,5z"/>
            </g>
        </svg>

        <span class="side-menu__label">الاعدادات</span>
        <i class="angle fe fe-chevron-down"></i>
    </a>

    <ul class="slide-menu">

        @can('الاقسام')
        <li>
            <a class="slide-item" href="{{ route('section.index') }}">
                الاقسام
            </a>
        </li>
        @endcan


        @can('المنتجات')
        <li>
            <a class="slide-item" href="{{ route('product.index') }}">
                المنتجات
            </a>
        </li>
        @endcan

    </ul>
</li>
@endcan
        </ul>
    </div>
</aside>
<!-- main-sidebar -->

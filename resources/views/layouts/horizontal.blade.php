@php
    $currentRoute = request()->route()->getName();
@endphp

<style>
    @media (max-width: 768px) {
        .CLOCK {
            display: none !important;
        }

        .NAMES {
            display: none !important;
        }
    }
</style>

<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('/') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('/images/logo-topbar.png') }}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('/images/logo-topbar.png') }}" alt="" height="50">
                    </span>
                </a>

                <a href="{{ route('/') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('/images/logo-topbar.png') }}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('/images/logo-topbar.png') }}" alt="" height="50">
                    </span>
                </a>

            </div>

            <div class="d-inline-block">
                <button class="btn header-item noti-icon" style="color: white;cursor: default;">
                    <div>Damage & Disposal Management System</div>
                </button>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light"
                data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <!--
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="@lang('translation.Search')">
                    <span class="bx bx-search-alt"></span>
                </div>
            </form>

            <div class="dropdown dropdown-mega d-none d-lg-block ml-2">
                <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                    <span key="t-megamenu">@lang('translation.Mega_Menu')</span>
                    <i class="mdi mdi-chevron-down"></i>
                </button>
                <div class="dropdown-menu dropdown-megamenu">
                    <div class="row">
                        <div class="col-sm-8">

                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="font-size-14 mt-0" key="t-ui-components">@lang('translation.UI_Components')</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);" key="t-lightbox">@lang('translation.Lightbox')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-range-slider">@lang('translation.Range_Slider')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-sweet-alert">@lang('translation.Sweet_Alert')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-rating">@lang('translation.Rating')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-forms">@lang('translation.Forms')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-tables">@lang('translation.Tables')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-charts">@lang('translation.Charts')</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-md-4">
                                    <h5 class="font-size-14 mt-0" key="t-applications">@lang('translation.Applications')</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);" key="t-ecommerce">@lang('translation.Ecommerce')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-calendar">@lang('translation.Calendars')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-email">@lang('translation.Email')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-projects">@lang('translation.Projects')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-tasks">@lang('translation.Tasks')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-contacts">@lang('translation.Contacts')</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-md-4">
                                    <h5 class="font-size-14 mt-0" key="t-extra-pages">@lang('translation.Extra_Pages')</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);" key="t-light-sidebar">@lang('translation.Light_Sidebar')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-compact-sidebar">@lang('translation.Compact_Sidebar')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-horizontal">@lang('translation.Horizontal_layout')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-maintenance">@lang('translation.Maintenance')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-coming-soon">@lang('translation.Coming_Soon')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-timeline">@lang('translation.Timeline')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-faqs">@lang('translation.FAQs')</a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="font-size-14 mt-0" key="t-ui-components">@lang('translation.UI_Components')</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);" key="t-lightbox">@lang('translation.Lightbox')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-range-slider">@lang('translation.Range_Slider')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-sweet-alert">@lang('translation.Sweet_Alert')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-rating">@lang('translation.Rating')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-forms">@lang('translation.Forms')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-tables">@lang('translation.Tables')</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-charts">@lang('translation.Charts')</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-sm-5">
                                    <div>
                                        <img src="{{ URL::asset('/assets/images/megamenu-img.png') }}" alt="" class="img-fluid mx-auto d-block">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>-->
        </div>

        <div class="d-flex">
            <div class="d-inline-block CLOCK">
                <button class="btn header-item noti-icon">
                    <div id="time"></div>
                </button>
            </div>
            <!-- <div class="dropdown d-inline-block d-lg-none ml-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="@lang('translation.Search')" aria-label="Search input">

                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> -->

            <!-- <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @switch(Session::get('lang'))
    @case('ru')
        <img src="{{ URL::asset('/assets/images/flags/russia.jpg') }}" alt="Header Language" height="16"> <span class="align-middle">Russian</span>
    @break

    @case('it')
        <img src="{{ URL::asset('/assets/images/flags/italy.jpg') }}" alt="Header Language" height="16"> <span class="align-middle">Italian</span>
    @break

    @case('de')
        <img src="{{ URL::asset('/assets/images/flags/germany.jpg') }}" alt="Header Language" height="16"> <span class="align-middle">German</span>
    @break

    @case('es')
        <img src="{{ URL::asset('/assets/images/flags/spain.jpg') }}" alt="Header Language" height="16"> <span class="align-middle">Spanish</span>
    @break

    @default
        <img src="{{ URL::asset('/assets/images/flags/us.jpg') }}" alt="Header Language" height="16"> <span class="align-middle">English</span>
@endswitch
                </button>
                <div class="dropdown-menu dropdown-menu-end">


                    <a href="{{ url('index/en') }}" class="dropdown-item notify-item language" data-lang="eng">
                        <img src="{{ URL::asset('/assets/images/flags/us.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">English</span>
                    </a>
                    <a href="{{ url('index/es') }}" class="dropdown-item notify-item language" data-lang="sp">
                        <img src="{{ URL::asset('/assets/images/flags/spain.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Spanish</span>
                    </a>

                    <a href="{{ url('index/de') }}" class="dropdown-item notify-item language" data-lang="gr">
                        <img src="{{ URL::asset('/assets/images/flags/germany.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">German</span>
                    </a>

                    <a href="{{ url('index/it') }}" class="dropdown-item notify-item language" data-lang="it">
                        <img src="{{ URL::asset('/assets/images/flags/italy.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Italian</span>
                    </a>

                    <a href="{{ url('index/ru') }}" class="dropdown-item notify-item language" data-lang="ru">
                        <img src="{{ URL::asset('/assets/images/flags/russia.jpg') }}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Russian</span>
                    </a>
                </div>
            </div> -->

            <!-- <div class="dropdown d-none d-lg-inline-block ml-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-customize"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <div class="px-lg-2">
                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('/assets/images/brands/github.png') }}" alt="Github">
                                    <span>GitHub</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('/assets/images/brands/bitbucket.png') }}" alt="bitbucket">
                                    <span>Bitbucket</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('/assets/images/brands/dribbble.png') }}" alt="dribbble">
                                    <span>Dribbble</span>
                                </a>
                            </div>
                        </div>

                        <div class="row no-gutters">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('/assets/images/brands/dropbox.png') }}" alt="dropbox">
                                    <span>Dropbox</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('/assets/images/brands/mail_chimp.png') }}" alt="mail_chimp">
                                    <span>Mail Chimp</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{ URL::asset('/assets/images/brands/slack.png') }}" alt="slack">
                                    <span>Slack</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <div class="d-inline-block">
                <button class="btn header-item noti-icon NAMES" style="color: white;cursor: default;">
                    <div>{{ Auth::user()->fullname }}</div>
                </button>
            </div>

            @php
                $sections = DB::table('u_section')
                    ->where('u_id', Auth::user()->id)
                    ->pluck('section')
                    ->toArray();
            @endphp

            <div class="d-inline-block">
                <button class="btn header-item noti-icon NAMES" style="color: white; cursor: default;">
                    <div>{{ strtoupper(implode(' , ', $sections)) }}</div>
                </button>
            </div>

            <div class="dropdown d-none d-lg-inline-block ml-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                    <i class="bx bx-fullscreen"></i>
                </button>
            </div>

            <!-- <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-bell bx-tada"></i>
                    <span class="badge bg-danger rounded-pill">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0" key="t-notifications"> @lang('translation.Notifications') </h6>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small" key="t-view-all"> @lang('translation.View_All')</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i class="bx bx-cart"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mt-0 mb-1" key="t-your-order">@lang('translation.Your_order_is_placed')</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-grammer">@lang('translation.If_several_languages_coalesce_the_grammar')</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">@lang('translation.3_min_ago')</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <img src="{{ URL::asset('/assets/images/users/avatar-3.jpg') }}" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-grow-1">
                                    <h6 class="mt-0 mb-1">@lang('translation.James_Lemire')</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-simplified">@lang('translation.It_will_seem_like_simplified_English')</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-hours-ago">@lang('translation.1_hours_ago')</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                        <i class="bx bx-badge-check"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mt-0 mb-1" key="t-shipped">@lang('translation.Your_item_is_shipped')</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-grammer">@lang('translation.If_several_languages_coalesce_the_grammar')</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">@lang('translation.3_min_ago')</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <img src="{{ URL::asset('/assets/images/users/avatar-4.jpg') }}" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-grow-1">
                                    <h6 class="mt-0 mb-1">@lang('translation.Salena_Layfield')</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-occidental">@lang('translation.As_a_skeptical_Cambridge_friend_of_mine_occidental')</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-hours-ago">@lang('translation.1_hours_ago')</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">@lang('translation.View_More')</span>
                        </a>
                    </div>
                </div>
            </div> -->

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user"
                        src="{{ isset(Auth::user()->avatar) || Auth::user()->avatar != '' ? asset(Auth::user()->avatar) : asset('images/user-icon.jpg') }}"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1" key="t-henry"
                        style="color:whitesmoke;">{{ ucfirst(Auth::user()->name) }}</span>
                    <i style="color:whitesmoke;" class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                        data-bs-target=".update-profile"><i class="bx bx-user font-size-16 align-middle me-1"></i>
                        <span key="t-profile">โปรไฟล์</span></a>
                    <a class="dropdown-item d-block" href="#" data-bs-toggle="modal"
                        data-bs-target=".change-password"><i class="bx bx-wrench font-size-16 align-middle me-1"></i>
                        <span key="t-settings">เปลี่ยนรหัสผ่าน</span></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="javascript:void();"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                            class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span
                            key="t-logout">ออกจากระบบ</span></a>
                    <form id="logout-form" action="{{ route('custom-logout') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="bx bx-cog bx-spin"></i>
                </button>
            </div>

        </div>
    </div>

</header>

<div class="topnav" style="background-color:white;">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">

                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-home me-2"></i>
                            Dashboard
                        </a>
                    </li> --}}

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex justify-content-between align-items-center menu-btn"
                            href="#" id="damageDropdown" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <span>
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                ระบบสินค้าชำรุด / ทำลาย
                                <div class="arrow-down"></div>
                            </span>
                        </a>

                        <ul class="dropdown-menu w-100" aria-labelledby="damageDropdown">

                            <li>
                                <a class="dropdown-item" href="{{ route('damage-report') }}">
                                    📄 แจ้งรายการ
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('manager-approve') }}">
                                    👤 อนุมัติ (Manager)
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('admin-approve') }}">
                                    🧑‍💼 ผู้บริหารอนุมัติ
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('branch.sap') }}">
                                    📑 บันทึก SAP
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('hr.approve') }}">
                                    💰 หักเงินเดือน (HR)
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('destroy.list') }}">
                                    🔥 ทำลายสินค้า
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('discount.list') }}">
                                    🏷️ ปริ้นสติ๊กเกอร์
                                </a>
                            </li>

                        </ul>
                    </li>


                    {{-- <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" href="#" id="requireDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">

                            <i class="fas fa-pencil-alt me-2"></i>
                            การแจ้งงานของท่าน
                            <div class="arrow-down"></div>
                        </a> --}}

                    {{-- <ul class="dropdown-menu" aria-labelledby="requireDropdown"> --}}

                    {{-- หน้า Assign (ใช้หน้าเดิม) --}}
                    {{-- <li>
                                <a class="dropdown-item" href="{{ route('require_ticket') }}">
                                    Assign @if (Count_Complete() != 0)
                                        <span style="color:red">({{ Count_Complete() }})</span>
                                    @endif
                                </a>
                            </li> --}}

                    {{-- ประกาศ --}}
                    {{-- <li>
                                <a class="dropdown-item" href="{{ route('announcements.index') }}">
                                    Announce
                                </a>
                            </li> --}}

                    {{-- ถามตอบ --}}
                    {{-- <li>
                                <a class="dropdown-item" href="{{ route('require.question') }}">
                                    Q&A
                                </a>
                            </li> --}}

                    {{-- Memo --}}
                    {{-- <li>
                                <a class="dropdown-item" href="{{ route('memo.index') }}">
                                    Memo @if (CountMemo() > 0)
                                        <span style="color:red"> ({{ CountMemo() }})</span>
                                    @endif
                                </a>
                            </li> --}}

                    {{-- </ul>
                    </li> --}}


                    {{-- <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" href="#" id="requireDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">

                            <i class="fas fa-inbox me-2"></i>
                            การแจ้งงานถึงท่าน
                            <div class="arrow-down"></div>
                        </a> --}}

                    {{-- <ul class="dropdown-menu" aria-labelledby="requireDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('request_ticket') }}">
                                    Assign @if (Auth::user()->role == 'manager' && Count_Pending() != 0)
                                        <span style="color:red">({{ Count_Pending() }})</span>
                                    @elseif(Auth::user()->role == 'user' && Count_Assignment() != 0)
                                        <span style="color:red">({{ Count_Assignment() }})</span>
                                    @endif
                                </a>
                            </li>

                            @php
                                $announceCount = CountAnnouncement();
                            @endphp

                            <li>
                                <a class="dropdown-item" href="{{ route('announcements.home') }}">
                                    Announce
                                    @if ($announceCount > 0)
                                        <span style="color:red"> ({{ $announceCount }})</span>
                                    @endif
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('question.reply.index') }}">
                                    Q&A @if (CountQA() > 0)
                                        <span style="color:red"> ({{ CountQA() }})</span>
                                    @endif
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('memo-to-me.index') }}">
                                    Memo @if (CountYourMemo() > 0)
                                        <span style="color:red"> ({{ CountYourMemo() }})</span>
                                    @endif
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" href="#" id="requireDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">

                            <i class="fas fa-chart-bar me-2"></i>
                            รายงาน
                            <div class="arrow-down"></div>
                        </a>

                        <ul class="dropdown-menu">

                            <li>
                                <a class="dropdown-item" href="{{ route('report.assign') }}">
                                    Assign
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('report.question') }}">
                                    Q&A
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('report.memo') }}">
                                    Memo
                                </a>
                            </li>

                        </ul>
                    </li> --}}


                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('repair_home') }}"
                            id="topnav-dashboard" role="button"
                            @if ($currentRoute == '/repair') style="color: black;" @endif>
                            <i class="fas fas fa-tools me-2"></i><span key="t-dashboards">งานซ่อมรอดำเนินการ</span>
                        </a>
                    </li> --}}

                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="{{ route('history') }}"
                            id="topnav-dashboard" role="button"
                            @if ($currentRoute == '/history') style="color: black;" @endif>
                            <i class="fas fa-search me-2"></i><span key="t-dashboards">ติดตามงานซ่อม</span>
                        </a>
                    </li> --}}

                    @if (Auth::user()->role == 'admin')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-DC"
                                role="button">
                                <i class="fas fa-cogs me-2"></i><span key="setting">ตั้งค่า</span>
                                <div class="arrow-down"></div>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="manage-user">
                                {{-- <a href="{{ route('export-home') }}" class="dropdown-item" key="manage-user"
                                    style="display: flex;align-items: center;"><i style="margin-left:3px;padding-right:3px;"
                                        class="fas fa-file-alt me-2"></i>รายงาน</a> --}}

                                {{-- <a class="dropdown-item dropdown-item dropdown-toggle arrow-none" href="#"
                                        id="topnav-email" role="button">
                                        <i style="margin-left:3px;padding-right:3px;"
                                            class="fas fa-file-alt me-2"></i><span key="t-calendar">รายงาน</span>
                                        <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-email">
                                        <a href="{{ route('export-home') }}" class="dropdown-item" key="manage-user"
                                            style="display: flex;align-items: center;"><i
                                                style="margin-left:3px;padding-right:3px;"
                                                class="fas fa-file-alt me-2"></i>รายงานสรุปสถานะ</a>
                                    </div>
                                </div> --}}
                                <a href="{{ route('user_manage') }}" class="dropdown-item" key="manage-user"
                                    style="display: flex;align-items: center;"><i
                                        class="fas fa-users-cog me-2"></i>จัดการผู้ใช้งาน</a>

                                <a href="{{ route('branch_manage') }}" class="dropdown-item" key="manage-user"
                                    style="display: flex;align-items: center;"><i
                                        class="fas fa-store-alt me-2"></i>จัดการสาขา</a>

                                {{-- <a href="{{ route('type_request_manage') }}" class="dropdown-item"
                                    key="manage-type-request" style="display: flex;align-items: center;">
                                    <i class="fas fa-tasks me-2"></i>จัดการประเภทงาน
                                </a> --}}

                                {{-- <a href="{{ route('brand_manage') }}" class="dropdown-item" key="manage-user"
                                    style="display: flex;align-items: center;"><i
                                        class="fas fa-tags me-2"></i>จัดการแบรนด์</a> --}}
                                {{-- <a href="{{ route('/') }}" class="dropdown-item" key="manage-excel"
                                style="display: flex;align-items: center;"><i
                                    class="fas fa-edit me-2"></i>จัดการใบซ่อม</a> --}}
                            </div>
                        </li>
                    @endif


                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-DC" role="button">
                            <i class="bx bxs-edit me-2"></i><span key="setting">ประเมินผลการปฏิบัติงาน</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="manage-user">
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-DC" role="button">
                                <i class="bx bxs-edit me-2"></i><span key="setting">ประเมินผลการทดลองงาน</span>
                                <div class="arrow-down"></div>
                            </a>
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-DC" role="button">
                                <i class="bx bxs-edit me-2"></i><span key="setting">ประเมินผลประจำปี</span>
                                <div class="arrow-down">
                                    
                                </div>
                            </a>
                            <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-DC" role="button">
                                <i class="bx bxs-edit me-2"></i><span key="setting">รายงานการประเมินผล</span>
                                <div class="arrow-down">
                                    
                                </div>
                            </a>
                            @if (count(getYears()) > 0)
                                @foreach (getYears() as $item)
                                    <a href="{{ route('annual_employee', ['Year' => $item->year, 'Term' => $item->term]) }}"
                                        class="dropdown-item" key="manage-user"
                                        style="display: flex; justify-content: center;align-items: center;">
                                        <span><i
                                            class="mdi mdi-calendar-month me-2"></i>{{ $item->year }} - {{ $item->term }}</span>
                                    </a>
                                @endforeach
                            @else
                                <a href="#" class="dropdown-item"
                                    style="display: flex; justify-content: center;">
                                    <span>ไม่มีข้อมูล</span>
                                </a>
                            @endif

                        </div>
                    </li> --}}


                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-pages"
                            role="button">
                            <i class="bx bx-customize me-2"></i><span key="t-apps">ประเมินผลการปฏิบัติงาน</span>
                            <div class="arrow-down"></div>
                        </a>

                    </li> --}}



                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-DC" role="button">
                            <i class="bx bx-cog me-2"></i><span key="setting">ตั้งค่าระบบ</span>
                            <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="manage-user">
                            <a href="" class="dropdown-item" key="manage-excel"
                                style="display: flex;align-items: center;"><i
                                    class="mdi mdi-file-excel-outline me-2"></i>นำเข้า & ส่งออก</a>
                        </div>
                    </li> --}}

                </ul>
            </div>

            {{-- <a class="nav-link dropdown-toggle arrow-none float-end" href="{{ asset('user-manual.pdf') }}" target="_blank" id="topnav-dashboard" role="button" @if ($currentRoute == '/') style="color: black;" @endif>
            <i class="bx bx-book-open me-2 font-size-14"></i> <span key="t-dashboards">User Manual</span>
            </a> --}}

        </nav>
    </div>
</div>

<div class="modal fade change-password" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">เปลี่ยนรหัสผ่าน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="change-password">
                    @csrf
                    <input type="hidden" value="{{ Auth::user()->id }}" id="data_id">
                    <div class="mb-3">
                        <label for="current_password">รหัสผ่านปัจจุบัน</label>
                        <input id="current-password" type="password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            name="current_password" autocomplete="current_password" placeholder=""
                            value="{{ old('current_password') }}">
                        <div class="text-danger" id="current_passwordError" data-ajax-feedback="current_password">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="newpassword">รหัสผ่านใหม่</label>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password"
                            autocomplete="new_password" placeholder="">
                        <div class="text-danger" id="passwordError" data-ajax-feedback="password"></div>
                    </div>

                    <div class="mb-3">
                        <label for="userpassword">ยืนยันรหัสผ่านใหม่</label>
                        <input id="password-confirm" type="password" class="form-control"
                            name="password_confirmation" autocomplete="new_password" placeholder="">
                        <div class="text-danger" id="password_confirmError" data-ajax-feedback="password-confirm">
                        </div>
                    </div>

                    <div class="mt-3 d-grid">
                        <button class="btn btn-primary waves-effect waves-light UpdatePassword"
                            data-id="{{ Auth::user()->id }}" type="submit">บันทึก</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--  Update Profile example -->
<div class="modal fade update-profile" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">โปรไฟล์</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="post" enctype="multipart/form-data" id="update-profile">
                    @csrf
                    <input type="hidden" value="{{ Auth::user()->id }}" id="data_id">

                    <div class="row">
                        <div class="mb-3 col-xl-6">
                            <label for="avatar">ภาพโปรไฟล์</label>
                            <!-- <div class="input-group">
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" autofocus>
                                <label class="input-group-text" for="avatar">อัพโหลด</label>
                            </div> -->
                            <div class="text-start">
                                <img src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('/assets/images/users/user-icon.jpg') }}"
                                    alt="" class="rounded-circle avatar-lg">
                            </div>
                            <div class="text-danger" role="alert" id="avatarError" data-ajax-feedback="avatar">
                            </div>
                        </div>
                        <div class="mb-3 col-xl-6">
                            <!-- <div class="text-start">
                                <img src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('/assets/images/users/user-icon.jpg') }}" alt="" class="rounded-circle avatar-lg">
                            </div>
                            <div class="text-danger" role="alert" id="avatarError" data-ajax-feedback="avatar"></div> -->
                        </div>
                    </div>

                    <div class="row">

                        <div class="mb-3 col-xl-6">
                            <label for="fullname" class="form-label">Fullname</label>
                            <input readonly type="text"
                                class="form-control @error('u_tel') is-invalid @enderror bg-light"
                                value="{{ Auth::user()->fullname }}" name="fullname" autofocus
                                placeholder="ระบุจังหวัด">
                            <div class="text-danger" id="u_telError" data-ajax-feedback="name"></div>
                        </div>
                        <div class="mb-3 col-xl-6">
                            <label for="username" class="form-label">Username</label>
                            <input readonly type="text"
                                class="form-control @error('u_tel') is-invalid @enderror bg-light"
                                value="{{ Auth::user()->username }}" name="username" autofocus
                                placeholder="ระบุจังหวัด">
                            <div class="text-danger" id="u_telError" data-ajax-feedback="name"></div>
                        </div>

                        <div class="mb-3 col-xl-6">
                            <label for="username" class="form-label">Section</label>
                            <input readonly type="text"
                                class="form-control @error('u_section') is-invalid @enderror bg-light"
                                value="{{ strtoupper(implode(' , ', $sections)) }}" name="u_section" autofocus
                                placeholder="ระบุสาขา">
                            <div class="text-danger" id="u_sectionError" data-ajax-feedback="name"></div>
                        </div>

                        <div class="mb-3 col-xl-6">
                            <label for="username" class="form-label">Role</label>
                            <input readonly type="text"
                                class="form-control @error('u_branch') is-invalid @enderror bg-light"
                                value="{{ strtoupper(Auth::user()->role) }}" name="u_branch" autofocus
                                placeholder="ระบุสาขา">
                            <div class="text-danger" id="u_branchError" data-ajax-feedback="name"></div>
                        </div>
                    </div>

                    <div class="mt-3 d-grid">
                        <!-- <button class="btn btn-primary waves-effect waves-light UpdateProfile" data-id="{{ Auth::user()->id }}" type="submit">บันทึก</button> -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            aria-label="Close">ปิด</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

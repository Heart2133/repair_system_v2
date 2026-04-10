@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Login')
@endsection

@section('css')
    <!-- owl.carousel css -->
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/owl.carousel/owl.carousel.min.css') }}">

    <style>
        @media (max-width: 768px) {
            .BGLARGE {
                display: none;
            }
        }
    </style>
@endsection

@section('body')

    <body class="auth-body-bg">
    @endsection

    @section('content')

        <div>
            <div class="container-fluid p-0">
                <div class="row g-0">

                    <div class="col-xl-9 BGLARGE">
                        <div class="auth-full-bg pt-lg-5 p-4">
                            <div class="w-100">
                                <div class="bg-overlay">

                                    <!-- Carousel -->
                                    <div id="demo" class="carousel slide" data-bs-ride="carousel"
                                        style="width:100%;height:100%;">

                                        <!-- Indicators/dots -->

                                        {{-- <div class="carousel-indicators"></div> --}}

                                        <div class="carousel-inner" style="width:100%;height:100%;">
                                            <img src="{{ asset('/background/assign.jpg') }}" alt="" class="d-block"
                                                style="width:100%;height:100%;object-fit: cover;">
                                        </div>

                                        <!-- Left and right controls/icons -->

                                        {{-- <button class="carousel-control-prev" type="button" data-bs-target="#demo"
                                            data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#demo"
                                            data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button> --}}

                                    </div>

                                    {{-- <div id="demo" class="carousel slide carousel-fade" data-bs-interval="4000" data-bs-ride="carousel" style="width: 100%; height: 100%;">
                                        <div class="carousel-inner" style="width: 100%; height: 100%;">
                                            <div class="carousel-item active">
                                                <img src="{{ asset('/background/tms.png') }}" class="d-block w-100"
                                                     style="height: 100vh; object-fit: cover;">
                                            </div>
                                            <div class="carousel-item">
                                                <img src="{{ asset('/background/tms_au.jpg') }}" class="d-block w-100"
                                                     style="height: 100vh; object-fit: cover;">
                                            </div>
                                        </div>
                                    
                                        <!-- ปุ่มเลื่อนซ้าย/ขวา -->
                                        <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    </div> --}}


                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-xl-3">
                        <div class="auth-full-page-content p-md-5 p-4" style="padding-top:10px !important;">
                            <div class="w-100">

                                <div class="d-flex flex-column h-100">
                                    <div class="mb-0 mb-md-3 d-flex justify-content-center">
                                        <a href="{{ route('/') }}" class="d-block auth-logo">
                                            <img src="{{ URL::asset('/assets/images/logo-hwh.png') }}" alt=""
                                                height="100" class="auth-logo-dark">
                                            <img src="{{ URL::asset('/assets/images/logo-hwh.png') }}" alt=""
                                                height="18" class="auth-logo-light">
                                        </a>
                                    </div>
                                    <div class="my-3">

                                        <div>
                                            <h5 class="text-primary">Welcome to</h5>
                                            <p>Damage & Disposal Management System (DDMS)</p>
                                        </div>

                                        <div class="mt-4">
                                            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">ชื่อผู้ใช้</label>
                                                    <input name="username" type="text"
                                                        class="form-control @error('username') is-invalid @enderror"
                                                        value="" id="username" placeholder="กรอกชื่อผู้ใช้"
                                                        autocomplete="username" autofocus>
                                                    @error('username')
                                                        @if ($message == 'The u id field is required.')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>Please enter Username</strong>
                                                            </span>
                                                        @else
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @endif
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">รหัสผ่าน</label>
                                                    <div
                                                        class="input-group auth-pass-inputgroup @error('password') is-invalid @enderror">
                                                        <input type="password" name="password"
                                                            class="form-control  @error('password') is-invalid @enderror"
                                                            id="userpassword" value="" placeholder="กรอกรหัสผ่าน"
                                                            aria-label="Password" aria-describedby="password-addon">
                                                        <button class="btn btn-light " type="button" id="password-addon"><i
                                                                class="mdi mdi-eye-outline"></i></button>
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>Please enter Password</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <a href="javascript: void(0);" id="forgotpassword"
                                                    class="text-muted">ลืมรหัสผ่าน ?</a>

                                                <div class="float-end mb-3">
                                                    <input class="form-check-input" type="checkbox" id="remember"
                                                        {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="remember">
                                                        จำฉัน
                                                    </label>
                                                </div>


                                                <div
                                                    class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }} mt-3">

                                                    <div class="col-md-8">
                                                        <div class="captcha">
                                                            <span>{!! captcha_img() !!}</span>
                                                            <button type="button" class="btn btn-light btn-refresh mr-5"><i
                                                                    class="mdi mdi-refresh"></i></button>
                                                        </div>
                                                        <input id="captcha" type="text" class="form-control mt-2"
                                                            placeholder="กรอกผลลัพธ์" name="captcha">

                                                        @if ($errors->has('captcha'))
                                                            @if ($errors->first('captcha') == 'validation.captcha')
                                                                <span class="help-block text-danger">
                                                                    <strong>Capcha verification is invalid.</strong>
                                                                </span>
                                                            @else
                                                                <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('captcha') }}</strong>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                        </div>

                                        <div class="mt-4 d-grid">
                                            <button class="btn btn-primary waves-effect waves-light"
                                                type="submit">เข้าสู่ระบบ</button>
                                        </div>

                                        </form>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <p class="mb-0" style="margin-top: -20px;">Damage & Disposal Management System (DDMS)
                                        <br>Hardwarehouse Corporation Co., Ltd. ©
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script>
                                        <script>
                                            //document.write(new Date().getFullYear())
                                        </script>
                                    </p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container-fluid -->
        </div>

        <div class="modal fade bs-example-modal-center" id="modal-forgotpassword" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ลืมรหัสผ่าน</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center font-size-14">
                        <!--<img src="{{ URL::asset('/assets/images/logo_hwh.svg') }}" alt="" height="60">-->
                        <p class="mt-2">โปรดติดต่อทีมงานเพื่อขอรหัสผ่าน</p>
                        <!--<p>Please email to purchasing@hardwarehouse.co.th for your new password.
                                        Our administrator will send a new password to your email account shortly.
                                    </p>-->





                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

    @endsection
    @section('script')
        <!-- owl.carousel js -->
        <script src="{{ URL::asset('/assets/libs/owl.carousel/owl.carousel.min.js') }}"></script>
        <!-- auth-2-carousel init -->
        <script src="{{ URL::asset('/assets/js/pages/auth-2-carousel.init.js') }}"></script>
        {!! htmlScriptTagJsApi() !!}

        <script>
            $(document).on('click', '#forgotpassword', function() {
                $('#modal-forgotpassword').modal('show')
            });

            $(".btn-refresh").click(function() {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('refresh_captcha') }}',
                    success: function(data) {
                        $(".captcha span").html(data.captcha);
                    }
                });
            });
        </script>
    @endsection

@extends('layouts.master-without-nav')

@section('title')
@lang('translation.Login')
@endsection

@section('css')
<!-- owl.carousel css -->
<link rel="stylesheet" href="{{ URL::asset('/assets/libs/owl.carousel/owl.carousel.min.css') }}">
@endsection

@section('body')

<body class="auth-body-bg">
    @endsection

    @section('content')

    <div>
        <div class="container-fluid p-0">
            <div class="row g-0">

                <div class="col-xl-9">
                    <div class="auth-full-bg pt-lg-5 p-4">
                        <div class="w-100">
                            <div class="bg-overlay">

                                <!-- Carousel -->
                                <div id="demo" class="carousel slide" data-bs-ride="carousel" style="width:100%;height:100%;">

                                    <!-- Indicators/dots -->

                                    <div class="carousel-indicators">
                                        @foreach($notices as $key => $val)
                                        @if($key == 0 )
                                        <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                                        @else
                                        <button type="button" data-bs-target="#demo" data-bs-slide-to="{{$key}}"></button>
                                        @endif
                                        @endforeach
                                    </div>

                                    <div class="carousel-inner" style="width:100%;height:100%;">

                                        @foreach($notices as $key => $val)
                                        @if($key == 0 )
                                        <div class="carousel-item active" style="width:100%;height:100%; text-shadow: black 0.3em 0.3em 0.3em;">
                                            <img src="{{asset('/upload/notices_banner/'.$val->notices_banner)}}" alt="{{$val->notices_name}}" class="d-block" style="width:100%;height:100%;object-fit: cover;">
                                            <!-- <div class="carousel-caption mb-4" style="backdrop-filter: blur(1px); background-color:rgba( 255, 255, 255, 0.2);">
                                                <h1 style="opacity: 1;">{{$val->notices_name}}</h1>
                                                <a target="_blank" style="color:azure; font-size:large;" href="{{asset('/upload/notices_file/'.$val->notices_file)}}">[Click]</a>
                                            </div> -->
                                        </div>
                                        @else
                                        <div class="carousel-item" style="width:100%;height:100%; text-shadow: black 0.3em 0.3em 0.3em">
                                            <img src="{{asset('/upload/notices_banner/'.$val->notices_banner)}}" alt="{{$val->notices_name}}" class="d-block" style="width:100%;height:100%;object-fit: cover;">

                                            <!-- <div class="carousel-caption mb-4" style="backdrop-filter: blur(1px); background-color:rgba( 255, 255, 255, 0.2);">
                                                <h1>{{$val->notices_name}}</h1>
                                                <a target="_blank" style="color:azure; font-size:large;" href="{{asset('/upload/notices_file/'.$val->notices_file)}}">[Click]</a>
                                            </div>-->
                                        </div>
                                        @endif
                                        @endforeach


                                        <!-- <div class="carousel-item">
                                            <img src="https://www.w3schools.com/bootstrap5/chicago.jpg" alt="Chicago" class="d-block" style="width:100%; height:100vh;">
                                            <div class="carousel-caption">
                                                <h3>Chicago</h3>
                                                <p>Thank you, Chicago!</p>
                                            </div>
                                        </div>
                                        <div class="carousel-item">
                                            <img src="https://www.w3schools.com/bootstrap5/ny.jpg" alt="New York" class="d-block" style="width:100%; height:100vh; ">
                                            <div class="carousel-caption">
                                                <h3>New York</h3>
                                                <a href="#" target="_blank" style="color:azure">[ Click ]</a>
                                                <p></p>
                                            </div>
                                        </div> -->
                                    </div>

                                    <!-- Left and right controls/icons -->

                                    <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </button>

                                </div>
                            </div>

                            <!-- <div class="d-flex h-100 flex-column">

                                <div class="p-4 mt-auto">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-7">
                                            <div class="text-center">

                                                <h4 class="mb-3"><i class="bx bxs-quote-alt-left text-primary h1 align-middle me-3"></i>ประกาศสำคัญ ถึงท่านเจ้าของกิจการ &nbsp;
                                                    <i class="bx bxs-quote-alt-right text-primary h1 align-middle"></i>
                                                </h4>

                                                <div dir="ltr">
                                                    <div class="owl-carousel owl-theme auth-review-carousel" id="auth-review-carousel">


                                                        @foreach($notices as $key => $val)
                                                        <div class="item">
                                                            <div class="py-3">
                                                                <p class="font-size-16 mb-4">{{$val->notices_name}}</p>

                                                                <div>
                                                                    <a target="_blank" class="font-size-16 text-primary" href="{{asset('/upload/notices_file/'.$val->notices_file)}}">[Click]</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endforeach


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <!-- end col -->

                <div class="col-xl-3">
                    <div class="auth-full-page-content p-md-5 p-4">
                        <div class="w-100">

                            <div class="d-flex flex-column h-100">
                                <div class="mb-4 mb-md-3 d-flex justify-content-center">
                                    <a href="{{ route('/') }}" class="d-block auth-logo">
                                        <img src="{{ URL::asset('/assets/images/logo-hwh.png') }}" alt="" height="100" class="auth-logo-dark">
                                        <img src="{{ URL::asset('/assets/images/logo-hwh.png') }}" alt="" height="18" class="auth-logo-light">
                                    </a>
                                </div>
                                <div class="my-5">

                                    <div>
                                        <h5 class="text-primary">ยินดีต้อนรับ !</h5>
                                        <p class="text-muted">เข้าสู่ระบบ Vendor Management System.</p>
                                    </div>

                                    <div class="mt-4">
                                        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                                                <input name="username" type="text" class="form-control @error('username') is-invalid @enderror" value="" id="username" placeholder="กรุณากรอกชื่อผู้ใช้" autocomplete="username" autofocus>
                                                @error('username')
                                                @if( $message == 'The u id field is required.')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>กรุณาระบุชื่อผู้ใช้</strong>
                                                </span>
                                                @else
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง</strong>
                                                </span>
                                                @endif
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">รหัสผ่าน</label>
                                                <div class="input-group auth-pass-inputgroup @error('password') is-invalid @enderror">
                                                    <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror" id="userpassword" value="" placeholder="กรุณากรอกรหัสผ่าน" aria-label="Password" aria-describedby="password-addon">
                                                    <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                    @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>กรุณาระบุรหัสผ่าน</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <a href="javascript: void(0);" id="forgotpassword" class="text-muted">ลืมรหัสผ่าน ?</a>

                                            <div class="float-end mb-3">
                                                <input class="form-check-input" type="checkbox" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">
                                                    จดจำฉัน
                                                </label>
                                            </div>
                                            

                                            <div class="mb-3 @error('g-recaptcha-response') is-invalid @enderror">
                                                {!! htmlFormSnippet() !!}
                                            </div>

                                            @error('g-recaptcha-response')
                                            <span class="invalid-feedback" role="alert">
                                                <strong class="text-danger">{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                                            @enderror
                                            </span>
                                    </div>


                                    <div class="mt-4 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">เข้าสู่ระบบ </button>
                                    </div>

                                    </form>
                                </div>
                            </div>

                            <div class="text-center">
                                <p class="mb-0" style="margin-top: -20px;">Vendor Management System by Hardwarehouse Corporation Co., Ltd. © 2023<script>
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

    <div class="modal fade bs-example-modal-center" id="modal-forgotpassword" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ลืมรหัสผ่าน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center font-size-14">
                    <!--<img src="{{ URL::asset('/assets/images/logo_hwh.svg') }}" alt="" height="60">-->
                    <p class="mt-2">กรุณาติดต่อเพื่อขอรหัสผ่านใหม่ที่ Line Officail @hwhpurchasing</p>
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
    </script>
    @endsection
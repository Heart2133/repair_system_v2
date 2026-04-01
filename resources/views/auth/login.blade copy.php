@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Login')
@endsection

@section('css')
    {{-- <style>
        .register-link {
            text-decoration: none;
            /* ลบขีดเส้นใต้ออกจากลิงก์ปกติ */
            position: relative;
        }

        .register-link::after {
            content: '';
            /* สร้างเส้นขีดล่าง */
            position: absolute;
            left: 0;
            bottom: -2px;
            /* ระยะห่างระหว่างเส้นกับข้อความ */
            width: 0;
            height: 2px;
            /* ความหนาของเส้น */
            background-color: #007bff;
            /* สีของเส้น */
            transition: width 0.3s ease-in-out;
        }

        .register-link:hover::after {
            width: 100%;
            /* เพิ่มความกว้างเส้นเต็มลิงก์เมื่อ hover */
        }
    </style> --}}
    <style>
        .horizontal-line-container {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
        }

        .horizontal-line-container::before,
        .horizontal-line-container::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid black;
            /* เส้นทึบ */
            margin: 0 10px;
            /* ระยะห่างระหว่างเส้นกับข้อความ */
        }

        .horizontal-line-container span {
            white-space: nowrap;
            /* ป้องกันข้อความแตกบรรทัด */
            font-size: 16px;
            /* ขนาดข้อความ */
        }
    </style>
@endsection

@section('body')

    <body>
    @endsection

    @section('content')
        <div class="account-pages my-1 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="card-body pt-4">
                                <div class="auth-logo"
                                    style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <img src="{{ URL::asset('/images/logo-topbar.png') }}" alt="" width="120px;">
                                    <div style="margin-top: 10px;font-size:18px;color:black;">HWH E-RECEIPT</div>
                                    {{-- <div>สำหรับท่านที่ต้องการลงทะเบียนขอ E-Tax ครั้งแรก</div> --}}
                                    {{-- <div>(Please enter your data to login.)</div> --}}
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-primary text-center mt-3 register-link" style=""
                                        onclick="window.location.href='{{ url('register') }}';">
                                        <i class="mdi mdi-account me-1"></i>
                                        ลงทะเบียนขอรหัสผ่าน คลิกที่นี่
                                    </button>
                                </div>

                                <div class="horizontal-line-container">
                                    <span style="color:black;font-size:14px;">หรือ</span>
                                </div>

                                <div class=""
                                    style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <div style="color: black">เข้าสู่ระบบเพื่อทำการขอ E-Receipt</div>
                                    <div style="color: black">(Login to make E-Receipt request.)</div>
                                </div>

                                <div class="p-2 mt-2">

                                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="username" class="form-label">อีเมล (Email)</label>
                                            <input name="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" value=""
                                                id="username" placeholder="กรอกอีเมล" autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">รหัสผ่าน (Password)</label>
                                            <div
                                                class="input-group auth-pass-inputgroup @error('password') is-invalid @enderror">
                                                <input type="password" name="password"
                                                    class="form-control  @error('password') is-invalid @enderror"
                                                    id="userpassword" value="" placeholder="กรอกหัสผ่าน"
                                                    aria-label="Password" aria-describedby="password-addon">
                                                <button class="btn btn-light " type="button" id="password-addon"><i
                                                        class="mdi mdi-eye-outline"></i></button>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                จดจำฉัน (Remember Me)
                                            </label>
                                        </div>

                                        <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }} mt-3">

                                            <div class="col-md-8">
                                                <div class="captcha">
                                                    <span>{!! captcha_img() !!}</span>
                                                    <button type="button" class="btn btn-light btn-refresh mr-5"><i
                                                            class="mdi mdi-refresh"></i></button>
                                                </div>
                                                <input id="captcha" type="text" class="form-control mt-2"
                                                    placeholder="กรอกผลลัพธ์" name="captcha" required>

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

                                        <div class="mt-3 d-grid">
                                            <button class="btn btn-primary waves-effect waves-light" type="submit">
                                                Login</button>
                                        </div>
                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        <div class="mt-2 text-center">
                                            <a href="{{ route('forgot_password') }}" class="text-muted"><i
                                                    class="mdi mdi-lock me-1"></i>ลืมรหัสผ่าน (Forgot password)</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end account-pages -->

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

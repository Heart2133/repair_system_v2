@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Register')
@endsection
@section('css')
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">
@endsection
@section('body')

    <body>
    @endsection

    @section('content')
        <div class="account-pages mt-1 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            {{-- <div class="bg-primary bg-soft">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="text-primary p-4">
                                            <h5 class="text-primary">Free Register</h5>
                                            <p>Get your free HardwareHouse account now.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{ URL::asset('/assets/images/profile-img.png') }}" alt=""
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div> --}}
                            <div class="card-body pt-4">
                                <div class="auth-logo"
                                    style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <img src="{{ URL::asset('/images/logo-topbar.png') }}" alt="" width="120px;">
                                    <div style="margin-top: 10px;font-size:18px;color:black;">Create New Account</div>
                                    <div style="color:black;margin-top: 10px;">โปรดป้อนข้อมูลของคุณด้านล่างเพื่อลงทะเบียน
                                    </div>
                                    <div style="color:black;">Please enter your data below to sign up</div>
                                </div>
                                <div class="p-2">
                                    @if (session('error'))
                                        <div class="alert alert-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <form method="POST" class="form-horizontal" action="{{ route('register') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">อีเมล (Email) <span
                                                    style="color: red;">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="useremail" value="{{ old('email') }}" name="email"
                                                placeholder="กรอกอีเมล" autofocus required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="tel" class="form-label">เบอร์มือถือ (Telephone) <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" class="form-control @error('tel') is-invalid @enderror"
                                                value="{{ old('tel') }}" id="tel" name="tel" autofocus required
                                                placeholder="กรอกเบอร์มือถือ 10 หลัก">
                                            @error('tel')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" id="username" name="name" autofocus required
                                                placeholder="Enter username">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div> --}}

                                        <div class="mb-3">
                                            <label for="userpassword" class="form-label">รหัสผ่าน (Password) <span
                                                    style="color: red;">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="userpassword" name="password" placeholder="กรอกรหัสผ่าน" autofocus
                                                required>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="confirmpassword" class="form-label">ยืนยันรหัสผ่าน (Confirm
                                                Password) <span style="color: red;">*</span></label>
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                id="confirmpassword" name="password_confirmation"
                                                name="password_confirmation" placeholder="กรอกยืนยันรหัสผ่าน" autofocus
                                                required>
                                            @error('password_confirmation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        {{-- <div class="mb-3">
                                            <label for="userdob">Date of Birth</label>
                                            <div class="input-group" id="datepicker1">
                                                <input type="text" class="form-control @error('dob') is-invalid @enderror" placeholder="dd-mm-yyyy"
                                                    data-date-format="dd-mm-yyyy" data-date-container='#datepicker1' data-date-end-date="0d" value="{{ old('dob') }}"
                                                    data-provide="datepicker" name="dob" autofocus required>
                                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                @error('dob')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        {{-- <div class="mb-3">
                                            <label for="avatar">Profile Picture</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="inputGroupFile02" name="avatar" autofocus required>
                                                <label class="input-group-text" for="inputGroupFile02">Upload</label>
                                            </div>
                                            @error('avatar')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div> --}}
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

                                        <div class="mt-4"
                                            style="display:flex;flex-direction: column;align-items:center;justify-content:center;">
                                            <button class="btn btn-primary waves-effect waves-light" style="width:100%"
                                                type="submit">Sign Up</button>
                                            <button class="btn btn-link w-md waves-effect waves-light BTNBACK"
                                                type="button" style="width:100%;margin-top:10px;">Back</button>
                                        </div>

                                        {{-- <div class="mt-4 text-center">
                                            <p>Already have an account ? <a href="{{ url('login') }}"
                                                    class="fw-medium text-primary">
                                                    Login</a> </p>
                                        </div> --}}
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
        <!-- owl.carousel js -->
        <script src="{{ URL::asset('/assets/libs/owl.carousel/owl.carousel.min.js') }}"></script>
        <!-- auth-2-carousel init -->
        <script src="{{ URL::asset('/assets/js/pages/auth-2-carousel.init.js') }}"></script>
        {!! htmlScriptTagJsApi() !!}

        <script>
            $(".btn-refresh").click(function() {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('refresh_captcha') }}',
                    success: function(data) {
                        $(".captcha span").html(data.captcha);
                    }
                });
            });
            $(".BTNBACK").on("click", function() {
                history.back();
            });
        </script>
    @endsection

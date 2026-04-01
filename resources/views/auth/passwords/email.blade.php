@extends('layouts.master-without-nav')

@section('title')
    @lang('translation.Recover_Password')
@endsection

@section('body')

    <body>
    @endsection

    @section('content')
        <div class="account-pages my-5 pt-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card overflow-hidden">
                            <div class="card-body pt-5">
                                <div class="auth-logo"
                                    style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <img src="{{ URL::asset('/images/logo-topbar.png') }}" alt="" width="200px;">
                                    <h4 style="margin-top: 10px;">Forgot Password</h4>
                                    <p style="margin-top: 10px;font-size:14px;">Please enter your email.</p>
                                    <p align="center" style="font-size:12px;color:rgb(138, 138, 138);">The system will send you the reset password <br>
                                        link to your email within 15 minutes.</p>
                                </div>

                                <div class="p-2">
                                    {{-- @if (session('status'))
                                        <div class="alert alert-success text-center mb-4" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif --}}
                                    <form class="form-horizontal" method="POST" action="{{ route('forgot_password_email') }}">
                                        @csrf
                                        <div class="mb-3" style="display:flex;flex-direction: column;align-items:center;justify-content:center;">
                                            {{-- <label for="useremail" class="form-label">Email</label> --}}
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" placeholder="Enter your email here"
                                                value="{{ old('email') }}" id="email" style="width:80%;">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert" style="display:flex;align-items:center;justify-content:center;">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="text-end" style="display:flex;flex-direction: column;align-items:center;justify-content:center;">
                                            <button class="btn btn-primary w-md waves-effect waves-light"
                                                type="submit" style="width:80%;">Submit</button>
                                                <button class="btn btn-link w-md waves-effect waves-light BTNBACK"
                                                type="button" style="width:80%;margin-top:10px;">Back</button>
                                        </div>

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
    <script>
        $(".BTNBACK").on("click",function(){
            history.back();
        });
    </script>
    @endsection

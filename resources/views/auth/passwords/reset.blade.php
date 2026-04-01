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
                                    <h4 style="margin-top: 10px;">HWH Request Management System</h4>
                                    <p>Please enter password below to reset password.</p>
                                </div>
                                <div class="p-2">
                                    <form id="changePassword">
                                        @csrf
                                        <input type="hidden" name="token" id="token" value="{{ $token }}">

                                        <div class="mb-3">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" name="password" id="password"
                                                placeholder="Enter Password" required>
                                            <span class="text-danger" id="passwordError"></span>
                                        </div>

                                        <div class="mb-3">
                                            <label for="password-confirm">Confirm Password</label>
                                            <input type="password" class="form-control" name="password_confirmation"
                                                id="password-confirm" placeholder="Enter Confirm Password" required>
                                            <span class="text-danger" id="passwordConfirmError"></span>
                                        </div>

                                        <div class="text-end"
                                            style="display:flex;flex-direction: column;align-items:center;justify-content:center;">
                                            <button class="btn btn-primary w-md waves-effect waves-light" type="submit"
                                                id="BTNsubmit" style="width:100%;">Submit</button>
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
            document.getElementById('changePassword').addEventListener('submit', function(e) {
                e.preventDefault(); // Stop default form submission

                const token = $('#token').val();
                const password = $('#password').val();
                const passwordConfirm = $('#password-confirm').val();

                // Client-side validation
                if (password !== passwordConfirm) {
                    $('#passwordConfirmError').text('Passwords do not match.');
                    return;
                }

                $.ajax({
                    url: "{{ route('change_passwords') }}",
                    type: "POST",
                    data: {
                        password: password,
                        password_confirmation: passwordConfirm,
                        token: token,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        window.location.href = "{{ route('/') }}";
                    },
                    error: function(response) {
                        $('#passwordError').text('');
                        $('#passwordConfirmError').text('');

                        if (response.responseJSON.errors.password) {
                            $('#passwordError').text(response.responseJSON.errors.password);
                        }
                        if (response.responseJSON.errors.password_confirmation) {
                            $('#passwordConfirmError').text(response.responseJSON.errors
                                .password_confirmation);
                        }
                    }
                });
            });
        </script>
    @endsection

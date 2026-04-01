<!-- JAVASCRIPT -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/metismenu/metismenu.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{ URL::asset('assets/libs/node-waves/node-waves.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
<script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>

<!-- apexcharts -->
<script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<!-- Required datatable js -->
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<!-- dashboard init -->
<script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script>
<!-- Datatable init js -->
<script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
<!-- select2 -->
<script src="{{URL::asset('assets/libs/select2/select2.min.js')}}"></script>
<!-- joblist -->
<script src="{{URL::asset('assets/js/pages/job-list.init.js')}}"></script>
<!-- datepicker -->
<script src="{{URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<!-- Sweet Alerts js -->
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
function realtime() {
    // Update the time display every second
    setInterval(() => {
        const now = new Date(); // Get current date and time
        const formattedTime = moment(now).format('LL'); // Use moment.js for formatting
        $('#time').html(formattedTime);
    }, 1000);
}

    $(document).ready(function() {
        moment.locale('en');
        realtime();


        $('#change-password').on('submit', function(event) {
            event.preventDefault();
            var Id = $('#data_id').val();
            var current_password = $('#current-password').val();
            var password = $('#password').val();
            var password_confirm = $('#password-confirm').val();
            $('#current_passwordError').text('');
            $('#passwordError').text('');
            $('#password_confirmError').text('');
            $.ajax({
                url: "{{ url('update-password') }}" + "/" + Id,
                type: "POST",
                data: {
                    "current_password": current_password,
                    "password": password,
                    "password_confirmation": password_confirm,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    $('#current_passwordError').text('');
                    $('#passwordError').text('');
                    $('#password_confirmError').text('');
                    if (response.isSuccess == false) {
                        $('#current_passwordError').text(response.Message);
                    } else if (response.isSuccess == true) {
                        setTimeout(function() {
                            window.location.href = "{{ route('/') }}";
                        }, 1000);
                    }
                },
                error: function(response) {
                    $('#current_passwordError').text(response.responseJSON.errors.current_password);
                    $('#passwordError').text(response.responseJSON.errors.password);
                    $('#password_confirmError').text(response.responseJSON.errors.password_confirmation);
                }
            });
        });


        $('#update-profile').on('submit', function(event) {
            event.preventDefault();
            var Id = $('#data_id').val();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ url('update-profile') }}" + "/" + Id,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#emailError').text('');
                    $('#nameError').text('');
                    $('#dobError').text('');
                    $('#avatarError').text('');
                    if (response.isSuccess == false) {
                        alert(response.Message);
                    } else if (response.isSuccess == true) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                },
                error: function(response) {
                    $('#emailError').text(response.responseJSON.errors.email);
                    $('#nameError').text(response.responseJSON.errors.name);
                    $('#dobError').text(response.responseJSON.errors.dob);
                    $('#avatarError').text(response.responseJSON.errors.avatar);
                }
            });
        });

    });
</script>

@yield('script')

<!-- App js -->
<script src="{{ URL::asset('assets/js/app.min.js')}}"></script>

@yield('script-bottom')
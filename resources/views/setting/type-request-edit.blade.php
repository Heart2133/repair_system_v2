@extends('layouts.master-layouts')

@section('title')
    Edit Type Request
@endsection

@section('css')
    <!-- select2 -->
    <link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        /* บังคับ Select2 ให้สูงเท่า input Bootstrap */
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .select2-selection__rendered {
            line-height: 36px !important;
        }

        .select2-selection__arrow {
            height: 36px !important;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Setting
        @endslot
        @slot('title')
            แก้ไขประเภทงาน
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body border-bottom" style="display:flex;justify-content:space-between;align-items:center;">
                    <div style="margin-right:auto;color:black;font-size:14px;">
                        แก้ไขประเภทงาน
                    </div>
                </div>

                <div class="card-body">

                    {{-- ✅ hidden id --}}
                    <input type="hidden" id="type_id" value="{{ $type->id }}">

                    <form id="edit-type-form">
                        @csrf


                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label mb-1">
                                    ฝ่าย <span class="text-danger">*</span>
                                </label>

                                <select id="section" name="section" class="form-select select2">
                                    <option value="">-- กรุณาเลือกฝ่าย --</option>

                                    @foreach ($branchSections as $branchLabel => $sectionsInBranch)
                                        <optgroup label="{{ $branchLabel }}">
                                            @foreach ($sectionsInBranch as $sec)
                                                <option value="{{ $sec['section_th'] }}"
                                                    {{ trim($sec['section_th']) == trim($type->section) ? 'selected' : '' }}>
                                                    {{ $sec['section_th'] }} ({{ $branchLabel }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label mb-1">
                                    ประเภทงาน
                                </label>

                                <input type="text" id="type_th" name="type_th" class="form-control"
                                    value="{{ $type->type_th }}">
                            </div>

                        </div>

                    </form>

                    <div style="display:flex;justify-content:right;padding-top:20px;">
                        <button class="btn btn-success" id="update-type">
                            บันทึก
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $('#update-type').click(function(e) {
            e.preventDefault();

            const id = $('#type_id').val();
            const section = $('#section').val();
            const type_th = $('#type_th').val().trim();

            if (!section) {
                Swal.fire('กรุณาเลือกฝ่าย', '', 'warning');
                return;
            }

            if (!type_th) {
                Swal.fire('กรุณากรอกประเภทงาน', '', 'warning');
                return;
            }

            Swal.fire({
                title: 'บันทึกข้อมูล?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'ใช่',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm();
                }
            });

            function submitForm() {
                $.ajax({
                    url: "{{ route('type_request_update', ':id') }}".replace(':id', id),
                    method: "POST",
                    data: {
                        section: section,
                        type_th: type_th,
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function() {
                        Swal.fire('สำเร็จ', '', 'success').then(() => {
                            window.location.href = "{{ route('type_request_manage') }}";
                        });
                    },
                    error: function(err) {
                        Swal.fire('ผิดพลาด', '', 'error');
                        console.log(err);
                    }
                });
            }
        });
        $(document).ready(function() {

            $('#section').select2({
                placeholder: "-- กรุณาเลือกฝ่าย --",
                width: '100%',
                minimumResultsForSearch: Infinity
            });
            //  $('#section').val("{{ trim($type->section) }}").trigger('change');

        });
    </script>
@endsection

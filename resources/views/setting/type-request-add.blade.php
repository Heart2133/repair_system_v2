@extends('layouts.master-layouts')

@section('title')
    Add Type Request
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
            เพิ่มประเภทงาน
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body border-bottom"
                    style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="margin-right: auto;color:black;font-size:14px;">
                        เพิ่มประเภทงาน
                    </div>
                </div>

                <div class="card-body">
                    <form id="add-type-form">
                        @csrf

                        <div class="row">

                            {{-- ✅ ฝ่าย --}}
                            <div class="col-12 col-md-6">
                                <label for="section" class="form-label">
                                    ฝ่าย <span class="text-danger">*</span>
                                </label>
                                <select id="section" name="section" class="form-control select2" required>
                                    <option>-- กรุณาเลือกฝ่าย --</option>

                                    @foreach ($branchSections as $branchLabel => $sectionsInBranch)
                                        <optgroup label="{{ $branchLabel }}">
                                            @foreach ($sectionsInBranch as $sec)
                                                <option value="{{ $sec['id'] }}" data-name="{{ $sec['section_th'] }}">
                                                    {{ $sec['section_th'] }} ({{ $branchLabel }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ✅ ประเภทงาน --}}
                            <div class="col-12 col-md-6">
                                <label for="type_th" class="form-label">
                                    ประเภทงาน (TH) <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="type_th" name="type_th" class="form-control"
                                    placeholder="กรอกชื่อประเภทงาน" required>
                            </div>

                        </div>

                        {{-- ✅ ปุ่ม --}}
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-success" id="submit-type">
                                บันทึก
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#add-type-form').submit(function(e) {
            e.preventDefault();

            const section = $('#section').val();
            const section_name = $('#section option:selected').data('name');
            const type_th = $('input[name="type_th"]').val();

            if (!section || !type_th) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกข้อมูลให้ครบ'
                });
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

                // ✅ ต้องประกาศก่อนใช้งาน
                const section_id = $('#section').val();
                const section_name = $('#section option:selected').data('name');
                const type_th = $('input[name="type_th"]').val();

                $.ajax({
                    url: "{{ route('type_request_store') }}",
                    method: "POST",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        section_id: section_id,
                        section: section_name,
                        type_th: type_th
                    },
                    success: function() {
                        Swal.fire('สำเร็จ', '', 'success').then(() => {
                            window.location.href = "{{ route('type_request_manage') }}";
                        });
                    },
                    error: function(err) {
                        console.log(err.responseText);
                        Swal.fire('ผิดพลาด', err.responseText, 'error');
                    }
                });
            }
        });

        $('#section').select2({
            placeholder: "-- กรุณาเลือกฝ่าย --",
            width: '100%',
            minimumResultsForSearch: Infinity
        });
    </script>
@endsection

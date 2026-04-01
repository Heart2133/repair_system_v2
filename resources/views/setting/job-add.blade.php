@extends('layouts.master-layouts')

@section('title')
    เพิ่มระดับตำแหน่ง
@endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            ตั้งค่า
        @endslot
        @slot('title')
            เพิ่มระดับตำแหน่ง
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <div class="card-body border-bottom"
                    style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="margin-right: auto;color:black;font-size:14px;">
                        เพิ่มระดับตำแหน่ง
                    </div>
                </div>

                <div class="card-body">
                    <form id="add-job-form">
                        @csrf
                        <div class="row gy-3">

                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">กรุณาเลือกผู้ใช้งาน</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} {{ $user->lastname }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- ชื่อตำแหน่ง --}}
                            <select name="name" id="name" class="form-control" required>
                                <option value="">-- เลือกตำแหน่ง --</option>
                                <option value="requester">พนักงานผู้ร้องขอ</option>
                                <option value="head_requester">หัวหน้าแผนกของผู้ร้อง</option>
                                <option value="executive">ผู้บริหาร</option>
                                <option value="head_owner">หัวหน้าแผนกที่รับผิดชอบ</option>
                                <option value="owner_staff">ผู้ปฏิบัติงานภายในแผนกเจ้าของงาน</option>
                            </select>

                            {{-- ฝ่าย --}}
                            <div class="col-md-6">
                                <label class="form-label">
                                    ฝ่าย <span style="color:red">*</span>
                                </label>
                                <select name="section_id" id="section_id" class="form-select" required>
                                    <option value="">กรุณาเลือกฝ่าย</option>
                                    @foreach (\App\Models\Section::all() as $section)
                                        <option value="{{ $section->id }}">
                                            {{ $section->section_th }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </form>

                    <div style="display:flex;justify-content:right;padding-top:20px;">
                        <button class="btn btn-success" id="save-job-btn">
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
        $('#save-job-btn').on('click', function(e) {
            e.preventDefault();

            const name = $('#name').val();
            const section_id = $('#section_id').val();
            const user_id = $('#user_id').val();

            if (!name || !section_id || !user_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกข้อมูลให้ครบ'
                });
                return;
            }

            Swal.fire({
                title: "เพิ่มระดับตำแหน่ง?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: "ใช่",
                cancelButtonText: "ยกเลิก",
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm();
                }
            });

            function submitForm() {
                $.ajax({
                    url: "{{ route('jobPosition.store') }}",
                    type: "POST",
                    data: {
                        name: name,
                        section_id: section_id,
                        user_id: user_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ'
                        }).then(() => {
                            window.location.href = "{{ route('jobPosition.index') }}";
                        });
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด'
                        });
                        console.log(err.responseText);
                    }
                });
            }
        });
    </script>
@endsection

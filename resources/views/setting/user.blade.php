@extends('layouts.master-layouts')

@section('title')
    Home
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <!--datepicker-->
    <link href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">
    <!--select2-->
    <link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        /* ทำให้ตารางเลื่อนได้เมื่ออยู่บนมือถือ */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            #datatable {
                white-space: nowrap;
            }
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Home
        @endslot
        @slot('title')
            Home
        @endslot
    @endcomponent

    @php
        use Carbon\Carbon;
        use App\Models\Task_Img;
    @endphp

    <div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 90%; width: 1000px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">รายละเอียด</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- เนื้อหาของรายละเอียดคำสั่งซื้อจะถูกแสดงที่นี่ -->
                    <div id="orderDetailsContent">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">


            <div class="card">
                <div class="card-body border-bottom"
                    style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="margin-right: auto;color:black;font-size:14px;">จัดการผู้ใช้งาน</div>
                    <a href="{{ route('user_add') }}" class="btn btn-success"
                        style="display: flex; justify-content: center; align-items: center; width: 120px;"> + เพิ่มผู้ใช้งาน
                    </a>
                </div>
                <div class="card border border-light" style="padding: 20px;margin: 0px;">
                    <div class="table-responsive">
                        <table id="datatable5" class="table table-bordered mt-5">
                            <thead>
                                <tr style="background:#0656b2;color:white;">
                                    <th>#</th>
                                    <th>ชื่อผู้ใช้งาน</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>บทบาท</th>
                                    <th>ฝ่าย</th>
                                    <th style="text-align: center">สถานะ</th>
                                    <th>เข้าสู่ระบบล่าสุด</th>
                                    <th style="text-align: center">จัดการ</th>
                                </tr>
                            </thead>
                            @foreach ($users as $key => $val)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $val->username }}</td>
                                    <td>{{ $val->fullname }}</td>
                                    <td>{{ strtoupper($val->role) }}</td>
                                    <td>{{ $val->sections }}</td>
                                    <td align="center">
                                        @if ($val->active_status == 1)
                                            <span class="badge" style="background-color: #00c52b;">ใช้งาน</span>
                                        @else
                                            <span class="badge" style="background-color: #e10000;">บล็อค</span>
                                        @endif
                                    </td>
                                    <td>{{ $val->last_login }}</td>
                                    <td>
                                        <ul class="list-unstyled hstack justify-content-center gap-1 mb-0">

                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="แก้ไข">
                                                <a href="{{ route('user_edit', ['id' => $val->id]) }}"
                                                    class="btn btn-sm btn-soft-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </li>
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="บล็อค">
                                                <a data-id="{{ $val->id }}" data-status="{{ $val->active_status }}"
                                                    class="btn btn-sm btn-soft-primary UserBlock">
                                                    <i class="fas fa-lock"></i>
                                                </a>
                                            </li>
                                            {{-- <li data-bs-toggle="tooltip" data-bs-placement="top" title="ลบพนักงาน">
                                                <a class="btn btn-sm btn-soft-danger del_user"
                                                    data-id="{{ $val->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </li> --}}
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="container">
                        
                    </div> --}}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#datatable5').DataTable({
                pageLength: 50, // จำนวนรายการเริ่มต้น
                lengthMenu: [
                    [50, 100],
                    [50, 100]
                ], // กำหนดตัวเลือกเมนูเฉพาะ 50 และ 100
                "language": {
                    "lengthMenu": "แสดง _MENU_ รายการ",
                    "zeroRecords": "ไม่พบข้อมูล",
                    "info": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    "infoEmpty": "ไม่มีข้อมูล",
                    "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                    "search": "ค้นหา:",
                    "paginate": {
                        "first": "หน้าแรก",
                        "last": "หน้าสุดท้าย",
                        "next": "ถัดไป",
                        "previous": "ก่อนหน้า"
                    },
                    "processing": "กำลังโหลดข้อมูล..."
                }
            });
        });
    </script>
    <script>
        $('.UserBlock').on('click', function(e) {
            e.preventDefault(); // Prevent form submission

            let id = $(this).data('id'); // ดึงค่า ID ของผู้ใช้
            let active_status = $(this).data('status'); // ดึงค่า active_status

            // console.log("User ID:", id);
            // console.log("Active Status:", active_status);

            const formData = {
                id: id,
                active_status: active_status,
                _token: $('meta[name="csrf-token"]').attr("content"),
            };

            Swal.fire({
                title: active_status == 1 ? "บล็อคผู้ใช้งาน" : "ปลดบล็อคผู้ใช้งาน",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "ใช่",
                cancelButtonText: "ไม่",
                confirmButtonColor: "#33cc33",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    Submit();
                }
            });

            // Send data via AJAX
            function Submit() {
                $.ajax({
                    url: "{{ route('userblock') }}",
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: active_status == 1 ? "บล็อคผู้ใช้งานเรียบร้อย" :
                                    "ปลดบล็อคผู้ใช้งานเรียบร้อย",
                                confirmButtonText: 'ปิด',
                                confirmButtonColor: '#d33'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message || 'Failed to add user',
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'An error occurred',
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33'
                        });
                        console.error(error);
                    }
                });
            }
        });
        $('.del_user').on('click', function(e) {
            e.preventDefault(); // Prevent form submission
            let id = $(this).data('id'); // ดึงค่า ID ของผู้ใช้

            const formData = {
                id: id,
                _token: $('meta[name="csrf-token"]').attr("content"),
            };

            Swal.fire({
                title: "ลบผู้ใช้งาน",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "ใช่",
                cancelButtonText: "ไม่",
                confirmButtonColor: "#33cc33",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    Submit();
                }
            });

            // Send data via AJAX
            function Submit() {
                $.ajax({
                    url: "{{ route('deleteUser') }}",
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: "ลบผู้ใช้งานเรียบร้อย",
                                confirmButtonText: 'ปิด',
                                confirmButtonColor: '#d33'
                            }).then(() => {
                                window.location.href = "{{ route('user_manage') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message || 'Failed to add user',
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'An error occurred',
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33'
                        });
                        console.error(error);
                    }
                });
            }
        });
    </script>
@endsection

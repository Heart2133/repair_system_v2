@extends('layouts.master-layouts')

@section('title')
    จัดการประเภทงาน
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
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
            Setting
        @endslot
        @slot('title')
            จัดการประเภทงาน
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body border-bottom"
                    style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="margin-right: auto;color:black;font-size:14px;">
                        จัดการประเภทงาน
                    </div>

                    <a href="{{ route('type_request_add') }}" class="btn btn-success" style="width: 150px;">
                        + เพิ่มประเภทงาน
                    </a>
                </div>

                <div class="card border border-light" style="padding: 20px;">
                    <div class="table-responsive">
                        <table id="datatable_type" class="table table-bordered mt-3">
                            <thead>
                                <tr style="background:#0656b2;color:white;">
                                    <th>#</th>
                                    <th>ฝ่าย</th>
                                    <th>ประเภทงาน</th>
                                    <th style="text-align:center;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($types as $key => $row)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $row->section }}</td>
                                        <td>{{ $row->type_th }}</td>
                                        <td align="center">
                                            <ul class="list-unstyled hstack justify-content-center gap-1 mb-0">

                                                {{-- edit --}}
                                                <li data-bs-toggle="tooltip" title="แก้ไข">
                                                    <a href="{{ route('type_request_edit', $row->id) }}"
                                                        class="btn btn-sm btn-soft-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </li>

                                                {{-- delete --}}
                                                <li data-bs-toggle="tooltip" title="ลบ">
                                                    <a class="btn btn-sm btn-soft-danger deleteType"
                                                        data-id="{{ $row->id }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </li>

                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- datatables -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#datatable_type').DataTable({
                pageLength: 50,
                lengthMenu: [
                    [50, 100],
                    [50, 100]
                ],
                language: {
                    lengthMenu: "แสดง _MENU_ รายการ",
                    zeroRecords: "ไม่พบข้อมูล",
                    info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    infoEmpty: "ไม่มีข้อมูล",
                    infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
                    search: "ค้นหา:",
                    paginate: {
                        first: "หน้าแรก",
                        last: "หน้าสุดท้าย",
                        next: "ถัดไป",
                        previous: "ก่อนหน้า"
                    }
                }
            });
        });
    </script>

    {{-- DELETE --}}
    <script>
        $(document).on('click', '.deleteType', function(e) {
            e.preventDefault();

            let id = $(this).data('id');

            const formData = {
                id: id,
                _token: $('meta[name="csrf-token"]').attr("content"),
            };

            Swal.fire({
                title: "ลบประเภทงาน",
                text: "คุณแน่ใจหรือไม่?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "ใช่",
                cancelButtonText: "ไม่",
                confirmButtonColor: "#33cc33",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('type_request_delete', ':id') }}".replace(':id', id),
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr("content"),
                        },
                        success: function() {
                            Swal.fire({
                                icon: 'success',
                                title: "ลบเรียบร้อย",
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection

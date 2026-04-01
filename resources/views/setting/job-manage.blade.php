@extends('layouts.master-layouts')

@section('title')
    จัดการระดับตำแหน่ง
@endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body border-bottom" style="display:flex;justify-content:space-between;align-items:center;">

                    <div style="color:black;font-size:14px;">
                        จัดการระดับตำแหน่ง
                    </div>

                    <a href="{{ route('jobPosition.create') }}" class="btn btn-success" style="width:150px;">
                        เพิ่มระดับตำแหน่ง
                    </a>
                </div>

                <div class="card border border-light" style="padding:20px;margin:0px;">
                    <div class="table-responsive">
                        <table id="datatable5" class="table table-bordered mt-3">
                            <thead>
                                <tr style="background:#0656b2;color:white;">
                                    <th>#</th>
                                    <th>ชื่อตำแหน่ง</th>
                                    <th>ผู้ใช้งาน</th>
                                    <th>ฝ่าย</th>
                                    <th style="text-align:center;">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($positions as $key => $val)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $val->name_label }}</td>
                                        <td>
                                            {{ $val->user->name ?? '-' }}
                                            {{ $val->user->lastname ?? '' }}
                                        </td>

                                        <td>{{ $val->section->section_th ?? '-' }}</td>
                                        <td align="center">
                                            <ul class="list-unstyled hstack justify-content-center gap-1 mb-0">

                                                <li title="แก้ไข">
                                                    <a href="{{ route('jobPosition.edit', $val->id) }}"
                                                        class="btn btn-sm btn-soft-warning">
                                                        <i class="fas fa-cog"></i>
                                                    </a>
                                                </li>

                                                <li title="ลบ">
                                                    <a data-id="{{ $val->id }}"
                                                        class="btn btn-sm btn-soft-danger deletePosition">
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
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#datatable5').DataTable({
                pageLength: 50
            });
        });
    </script>

    <script>
        $('.deletePosition').on('click', function(e) {
            e.preventDefault();

            let id = $(this).data('id');

            Swal.fire({
                title: "ลบระดับตำแหน่ง",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "ใช่",
                cancelButtonText: "ไม่",
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "/job-position/delete/" + id,
                        type: "DELETE",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr("content")
                        },
                        success: function() {
                            Swal.fire('ลบสำเร็จ').then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection

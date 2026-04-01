@extends('layouts.master-layouts')

@section('title')
    จัดการสาขา
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="card">

                <div class="card-body border-bottom d-flex justify-content-between">
                    <div style="margin-right: auto;color:black;font-size:14px;">จัดการสาขา</div>

                    <a href="{{ route('branch_add') }}" class="btn btn-success">
                        + เพิ่มสาขา
                    </a>
                </div>

                <div class="card border border-light" style="padding: 20px;margin: 0px;">

                    <div class="table-responsive">

                        <table id="datatable5" class="table table-bordered mt-5">

                            <thead>
                                <tr style="background:#0656b2;color:white;">
                                    <th>#</th>
                                    <th>รหัสสาขา</th>
                                    <th>SAP Code</th>
                                    <th>ชื่อสาขา</th>
                                    <th>ชื่อบริษัท</th>
                                    <th>เบอร์โทร</th>
                                    <th style="text-align:center">สถานะ</th>
                                    <th style="text-align:center">จัดการ</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($branches as $key => $branch)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        <td>{{ $branch->branch_code }}</td>

                                        <td>{{ $branch->sap_code }}</td>

                                        <td>{{ $branch->branch_desc }}</td>

                                        <td>{{ $branch->company_name }}</td>

                                        <td>{{ $branch->company_tel }}</td>

                                        <td align="center">

                                            @if ($branch->branch_active == 'Y')
                                                <span class="badge bg-success">ใช้งาน</span>
                                            @else
                                                <span class="badge bg-danger">ปิดใช้งาน</span>
                                            @endif

                                        </td>

                                        <td>

                                            <ul class="list-unstyled hstack justify-content-center gap-1 mb-0">

                                                <li>
                                                    <a href="{{ route('branch_edit', ['id' => $branch->id]) }}"
                                                        class="btn btn-sm btn-soft-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('branch_delete', $branch->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button"
                                                            class="btn btn-sm btn-soft-danger btn-delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        document.querySelectorAll('.btn-delete').forEach(function(button) {

            button.addEventListener('click', function(e) {

                let form = this.closest("form");

                Swal.fire({
                    title: 'ต้องการลบสาขานี้หรือไม่?',
                    text: "ข้อมูลจะถูกลบถาวร",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'ลบ',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {

                    if (result.isConfirmed) {
                        form.submit();
                    }

                });

            });

        });
    </script>
@endsection

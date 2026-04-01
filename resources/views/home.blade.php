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

    <div class="modal fade" id="orderAdd" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailModalLabel">เพิ่มใบแจ้งซ่อม</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="border p-3 rounded" style="margin: 10px">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">วันที่เอกสาร</label>
                                    <input type="text" name="datenow" id="datenow" class="form-control"
                                        value="{{ now()->format('Y-m-d') }}" disabled>
                                </div>
                                <div class="col-md-6">

                                    <label class="form-label">สาขา <span style="color: red;">*</span></label>
                                    @if (Auth::user()->role == 'admin')
                                        <select id="branchSelect" class="form-select">
                                            <option value="">-- เลือก --</option>
                                            @foreach (getBranchAll() as $item)
                                                <option value="{{ $item->branch_code }}">{{ $item->branch_code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif(Auth::user()->role == 'cs')
                                        <input type="text" class="form-control" name="hwhbranch" id="hwhbranch"
                                            value="{{ Auth::user()->hwh_branch }}" disabled>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">ชื่อลูกค้า <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="customername" id="customername"
                                        placeholder="กรุณากรอกชื่อ-นามสกุลลูกค้า">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">เบอร์ติดต่อ <span style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="customertel" id="customertel"
                                        placeholder="กรุณากรอกเบอร์ติดต่อลูกค้า">
                                </div>
                            </div>


                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">การรับประกัน <span style="color: red;">*</span></label>
                                    <div class="col-12 d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="warrantyCheck"
                                                value="Y" id="warrantyCheckY">
                                            <label class="form-check-label" for="warrantyCheckY">
                                                ในประกัน
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="warrantyCheck"
                                                value="N" id="warrantyCheckN">
                                            <label class="form-check-label" for="warrantyCheckN">
                                                นอกประกัน
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">ประเภทสินค้า <span style="color: red;">*</span></label>
                                    <div class="col-12 d-flex align-items-center gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="warrantyContry"
                                                value="Y" id="warrantyContryY">
                                            <label class="form-check-label" for="warrantyContryY">
                                                ในประเทศ
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="warrantyContry"
                                                value="N" id="warrantyContryN">
                                            <label class="form-check-label" for="warrantyContryN">
                                                นอกประเทศ
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6">
                            <label class="form-label">เงื่อนไขการรับประกัน </label>
                            <textarea id="brandConditionTextarea" class="form-control" rows="11" disabled></textarea>
                        </div>
                    </div>
                    {{-- </div> --}}

                    {{-- <div class="border p-3 rounded" style="margin: 10px"> --}}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">เบอร์แฟกซ์ Vendor </label>
                                    <input id="vendorfax" type="text" class="form-control" value="" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ผู้ติดต่อ Vendor </label>
                                    <input id="vendorcontact" type="text" class="form-control" value=""
                                        disabled>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6">

                            <label class="form-label">อาการเสีย <span style="color: red;">*</span> <span
                                    style="color:red;">(กรุณาระบุให้ชัดเจน)</span></label>
                            <textarea name="productdetail" id="productdetail" class="form-control" rows="5"
                                placeholder="กรุณากรอกอาการเสีย"></textarea>
                            {{-- <label for=""class="mt-1">เลือกรูปภาพประกอบ <span style="color: red;">*</span></label>
                            <input type="file" id="image" name="image" class="form-control form-control-md"> --}}

                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-primary BTNsave">บันทึก</button>
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
                    <div style="margin-right: auto;color:black;font-size:14px;">งานคำร้อง</div>
                    <a href="#" class="btn btn-success BTNadd" style="margin-left: auto;">
                        เพิ่มใบแจ้งงาน
                    </a>
                </div>
                <div class="card border border-light" style="padding: 20px;margin: 0px;">
                    <div class="table-responsive">
                        <table id="datatable5" class="table table-bordered">
                            <thead>
                                <tr style="background:#556ee6;color:white;">
                                    <th scope="col">#</th>
                                    <th scope="col">ชื่อเรื่อง</th>
                                    <th scope="col">สถานะ </th>
                                    <th scope="col">จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>คอมจอฟ้า</td>
                                    <td>pending</td>
                                    <td>gg</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on('click', '.BTNadd', function() {
            $('#orderAdd').modal("show");
        });
    </script>
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
@endsection

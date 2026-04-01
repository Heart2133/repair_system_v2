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

    <div class="modal fade" id="orderAdd" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มใบแจ้งสินค้าทำลาย</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="row">

                        <!-- 🔵 LEFT -->
                        <div class="col-lg-6">

                            <div class="card mb-3">
                                <div class="card-header">ข้อมูลเอกสาร</div>
                                <div class="card-body">

                                    <!-- ประเภทผู้แจ้ง -->
                                    <div class="mb-3">
                                        <label>ประเภทผู้แจ้ง *</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" name="report_type">
                                                <label class="form-check-label">สาขาแจ้ง</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" name="report_type">
                                                <label class="form-check-label">ลูกค้าแจ้ง</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ประเภทสินค้า -->
                                    <div class="mb-3">
                                        <label>ประเภทสินค้า *</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" name="product_type">
                                                <label class="form-check-label">ในประเทศ</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" class="form-check-input" name="product_type">
                                                <label class="form-check-label">นอกประเทศ</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- สาขา + วันที่ -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>สาขา *</label>
                                            @if (Auth::user()->role == 'admin')
                                                <select class="form-select">
                                                    <option value="">-- เลือก --</option>
                                                    @foreach (getBranchAll() as $item)
                                                        <option value="{{ $item->branch_code }}">
                                                            {{ $item->branch_code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" class="form-control"
                                                    value="{{ Auth::user()->hwh_branch }}" disabled>
                                            @endif
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>วันที่เอกสาร</label>
                                            <input type="text" class="form-control" value="{{ now()->format('Y-m-d') }}"
                                                disabled>
                                        </div>
                                    </div>

                                    <!-- สาเหตุ -->
                                    <div class="mb-3">
                                        <label>สาเหตุความเสียหาย *</label>
                                        <textarea class="form-control" rows="4"></textarea>
                                    </div>

                                    <!-- แนบไฟล์ -->
                                    <div class="mb-3">
                                        <label>แนบรูป / เอกสาร</label>
                                        <input type="file" class="form-control" multiple>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <!-- 🟢 RIGHT -->
                        <div class="col-lg-6">

                            <!-- 📦 สินค้า -->
                            <div class="card mb-3">
                                <div class="card-header">ข้อมูลสินค้า</div>
                                <div class="card-body">

                                    <!-- 🔹 แถวที่ 1 -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>รหัสสินค้า</label>
                                            <input type="text" id="product_code" class="form-control">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label>ชื่อสินค้า</label>
                                            <input type="text" id="product_name" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <!-- 🔹 แถวที่ 2 -->
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label>ราคาต่อหน่วย</label>
                                            <input type="text" id="price" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label>จำนวน</label>
                                            <input type="number" id="qty" class="form-control">
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label>ราคารวม</label>
                                            <input type="text" id="total" class="form-control" readonly>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- 👨‍💼 ผู้รับผิดชอบ -->
                            <div class="card mb-3">
                                <div class="card-header">ผู้รับผิดชอบ</div>
                                <div class="card-body">

                                    <div id="employee-wrapper">

                                        <div class="row mb-2 employee-row">
                                            <div class="col-md-4">
                                                <label>รหัสพนักงาน</label>
                                                <input type="text" class="form-control emp_code">
                                            </div>

                                            <div class="col-md-4">
                                                <label>ชื่อพนักงาน</label>
                                                <input type="text" class="form-control emp_name">
                                            </div>

                                            <div class="col-md-3">
                                                <label>% ความรับผิดชอบ</label>
                                                <input type="number" class="form-control emp_percent">
                                            </div>

                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm btn-remove">X</button>
                                            </div>
                                        </div>

                                    </div>

                                    <button type="button" class="btn btn-success btn-sm mt-2" id="add-employee">
                                        + เพิ่มพนักงาน
                                    </button>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary BTNsave"> บันทึก</button>
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

        $('#add-employee').click(function() {
            let row = `
    <div class="row mb-2 employee-row">
        <div class="col-md-4">
            <input type="text" class="form-control emp_code">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control emp_name">
        </div>
        <div class="col-md-3">
            <input type="number" class="form-control emp_percent">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm btn-remove">X</button>
        </div>
    </div>`;

            $('#employee-wrapper').append(row);
        });

        $(document).on('click', '.btn-remove', function() {
            $(this).closest('.employee-row').remove();
        });

        $(document).on('change', '.emp_code', function() {
            let code = $(this).val();
            let row = $(this).closest('.employee-row');

            $.ajax({
                url: '/api/employee/' + code,
                success: function(res) {
                    if (res) {
                        row.find('.emp_name').val(res.name);
                    } else {
                        row.find('.emp_name').val('');
                        alert('ไม่พบพนักงาน กรุณากรอกชื่อเอง');
                    }
                }
            });
        });

        function validatePercent() {
            let total = 0;

            $('.emp_percent').each(function() {
                total += parseFloat($(this).val()) || 0;
            });

            return total === 100;
        }

        function validateAmount() {
            let totalProduct = parseFloat($('#total').val()) || 0;
            let totalEmp = 0;

            $('.emp_percent').each(function() {
                let percent = parseFloat($(this).val()) || 0;
                totalEmp += (percent / 100) * totalProduct;
            });

            return totalEmp === totalProduct;
        }

        $('.BTNsave').click(function() {

            if (!validateForm()) {
                return;
            }

            // 👉 ผ่านแล้ว ค่อย submit
            alert('ผ่านแล้ว บันทึกได้');

            // ตัวอย่าง submit
            // $('#form-id').submit();
        });

        $('#product_code').on('change', function() {
            let code = $(this).val();

            $.get('/get-product', {
                code: code
            }, function(res) {
                $('#product_name').val(res.product_name);
                $('#price').val(res.price);
            });
        });

        $(document).on('change', '.emp_code', function() {
            let code = $(this).val();
            let row = $(this).closest('.employee-row');

            $.get('/get-employee', {
                code: code
            }, function(res) {
                row.find('.emp_name').val(res.fullname);
            });
        });

        function validateForm() {

            // ✅ 1. ตรวจข้อมูลพื้นฐาน
            if (!$('input[name="report_type"]:checked').val()) {
                alert('กรุณาเลือกประเภทผู้แจ้ง');
                return false;
            }

            if (!$('input[name="product_type"]:checked').val()) {
                alert('กรุณาเลือกประเภทสินค้า');
                return false;
            }

            if (!$('#product_code').val()) {
                alert('กรุณากรอกรหัสสินค้า');
                return false;
            }

            if (!$('#qty').val()) {
                alert('กรุณากรอกจำนวนสินค้า');
                return false;
            }

            // ✅ 2. ตรวจพนักงาน
            let hasEmployee = false;

            $('.employee-row').each(function() {
                let code = $(this).find('.emp_code').val();
                let percent = $(this).find('.emp_percent').val();

                if (code && percent) {
                    hasEmployee = true;
                }
            });

            if (!hasEmployee) {
                alert('กรุณาเพิ่มพนักงานอย่างน้อย 1 คน');
                return false;
            }

            // ✅ 3. ตรวจ % รวม
            let totalPercent = 0;

            $('.emp_percent').each(function() {
                totalPercent += parseFloat($(this).val()) || 0;
            });

            if (totalPercent !== 100) {
                alert('เปอร์เซ็นต์รวมต้องเท่ากับ 100%');
                return false;
            }

            // ✅ 4. ตรวจมูลค่า
            let totalProduct = parseFloat($('#total').val()) || 0;
            let totalEmpAmount = 0;

            $('.emp_percent').each(function() {
                let percent = parseFloat($(this).val()) || 0;
                totalEmpAmount += (percent / 100) * totalProduct;
            });

            // ปัดเศษกัน error ทศนิยม
            if (Math.round(totalEmpAmount) !== Math.round(totalProduct)) {
                alert('มูลค่าความรับผิดชอบไม่ตรงกับราคาสินค้า');
                return false;
            }

            return true;
        }
    </script>
@endsection

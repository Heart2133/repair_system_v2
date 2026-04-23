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
        <div class="modal-dialog" style="max-width: 95%;">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มใบแจ้งสินค้าทำลาย</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <div class="row">

                        <!-- 🔵 ข้อมูลเอกสาร (เต็มจอ) -->
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header">ข้อมูลเอกสาร</div>

                                <!-- ✅ ต้องมี -->
                                <div class="card-body">

                                    <div class="row">

                                        <!-- 🔵 ซ้าย -->
                                        <div class="col-md-6">


                                            <div class="row">

                                                <!-- 🔹 รูปแบบการดำเนินการ -->
                                                <div class="col-md-6 mb-4">
                                                    <label class="mb-2 d-block">
                                                        รูปแบบการดำเนินการ <span class="text-danger">*</span>
                                                    </label>
                                                    <select id="flow_type" class="form-select">
                                                        <option value="">-- เลือก --</option>
                                                        <option value="destroy">ทำลายสินค้า</option>
                                                        <option value="discount">ลดราคา</option>
                                                        <option value="claim">เคลมสินค้า</option>
                                                    </select>
                                                </div>

                                                <!-- 🔹 ประเภทผู้แจ้ง -->
                                                <div class="col-md-6 mb-4">
                                                    <label class="mb-2 d-block">
                                                        ประเภทผู้แจ้ง <span class="text-danger">*</span>
                                                    </label>

                                                    <div class="dropdown w-100">
                                                        <button
                                                            class="btn btn-outline-secondary w-100 text-start dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown" id="dropdownReport">
                                                            เลือกประเภทผู้แจ้ง
                                                        </button>

                                                        <ul class="dropdown-menu w-100" id="reportDropdown">

                                                            <li class="dropdown-item source-option" data-group="all"
                                                                data-value="branch">สาขาแจ้ง</li>

                                                            <li class="dropdown-item source-option" data-group="basic"
                                                                data-value="customer">ลูกค้าแจ้ง</li>

                                                            <li class="dropdown-item source-option" data-group="claim"
                                                                data-value="dc">DC แจ้ง</li>

                                                            <li class="dropdown-item source-option" data-group="claim"
                                                                data-value="purchase_local">จัดซื้อในประเทศ</li>

                                                            <li class="dropdown-item source-option" data-group="claim"
                                                                data-value="purchase_inter">จัดซื้อต่างประเทศ</li>

                                                        </ul>
                                                    </div>

                                                    <!-- hidden -->
                                                    <input type="hidden" id="report_source" name="report_source">
                                                </div>

                                            </div>

                                            <div class="mb-4">
                                                <label class="mb-2 d-block">
                                                    ประเภทสินค้า <span class="text-danger">*</span>
                                                </label>

                                                <div class="d-flex gap-4">
                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input" name="product_type"
                                                            value="domestic">
                                                        <label class="form-check-label">ในประเทศ</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input type="radio" class="form-check-input" name="product_type"
                                                            value="international">
                                                        <label class="form-check-label">นอกประเทศ</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label class="mb-2 d-block">
                                                    ประเภทปัญหา <span class="text-danger">*</span>
                                                </label>

                                                <div class="d-flex flex-column gap-2">
                                                    <div class="form-check">
                                                        <input type="radio" name="issue_type" value="defect"
                                                            class="form-check-input">
                                                        <label class="form-check-label">สินค้าด้อยคุณภาพจากการผลิต</label>
                                                    </div>

                                                    <div class="form-check">
                                                        <input type="radio" name="issue_type" value="claimable"
                                                            class="form-check-input">
                                                        <label
                                                            class="form-check-label">สินค้าเสียหายที่สามารถเคลมได้</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <!-- 🟢 ขวา -->
                                        <div class="col-md-6">

                                            <div class="row">

                                                <!-- สาขา -->
                                                <div class="col-md-6 mb-4">
                                                    <label class="mb-2 d-block">
                                                        สาขา <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select" id="branch_code">
                                                        <option value="">-- เลือก --</option>
                                                        @foreach (getBranchAll() as $item)
                                                            <option value="{{ $item->branch_code }}">
                                                                {{ $item->branch_code }} - {{ $item->branch_desc }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- วันที่เอกสาร -->
                                                <div class="col-md-6 mb-4">
                                                    <label class="mb-2 d-block">วันที่เอกสาร</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ now()->format('Y-m-d') }}" disabled>
                                                </div>

                                            </div>

                                            <div class="mb-4" id="purchase-section" style="display:none;">
                                                <label class="mb-2 d-block">ผู้ดูแลจัดซื้อ</label>
                                                <input type="text" class="form-control" id="purchase_name">
                                            </div>

                                            <div class="mb-4">
                                                <label class="mb-2 d-block">สาเหตุความเสียหาย <span
                                                        class="text-danger">*</span></label>
                                                <textarea id="damage_reason" class="form-control"></textarea>
                                            </div>

                                            <div>
                                                <label class="mb-2 d-block">แนบรูป / เอกสาร</label>
                                                <input type="file" class="form-control" multiple>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- 🟢 ล่าง -->
                        <div class="col-12">
                            <div class="row">

                                <!-- 📦 สินค้า -->
                                <div class="card mb-3">
                                    <div class="card-header">ข้อมูลสินค้า</div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle" id="product-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:7%">รหัสสินค้า</th>
                                                    <th style="width:13%">ชื่อสินค้า</th>
                                                    <th style="width:10%">ราคาต่อหน่วย</th>
                                                    <th style="width:10%">จำนวน</th>
                                                    <th style="width:10%">รวม</th>
                                                    <th style="width:5%">จัดการ</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product-wrapper">

                                                <!-- ✅ row -->
                                                <tr class="product-row">
                                                    <td><input type="text" class="form-control product_code" placeholder="กรุณากรอกรหัสสินค้า"></td>
                                                    <td><input type="text" class="form-control product_name" disabled></td>
                                                    <td><input type="text" class="form-control price" disabled></td>
                                                    <td><input type="number" class="form-control qty" placeholder="กรุณากรอกจำนวน"></td>
                                                    <td><input type="text" class="form-control total" disabled></td>
                                                    <td class="text-center">
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm btn-remove-product">
                                                            ลบ
                                                        </button>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="p-3">
                                        <button type="button" id="add-product" class="btn btn-success btn-sm">
                                            + เพิ่มสินค้า
                                        </button>
                                    </div>
                                </div>

                                <!-- 👨‍💼 ผู้รับผิดชอบ -->
                                <div class="card mb-3">
                                    <div class="card-header">ผู้รับผิดชอบ</div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle" id="employee-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:7%">รหัสพนักงาน</th>
                                                    <th style="width:13%">ชื่อพนักงาน</th>
                                                    <th style="width:10%">% ความรับผิดชอบ</th>
                                                    <th style="width:10%">มูลค่า</th>
                                                    <th style="width:5%">จัดการ</th>
                                                </tr>
                                            </thead>

                                            <tbody id="employee-wrapper">

                                                <!-- ✅ row -->
                                                <tr class="employee-row">
                                                    <td><input type="text" class="form-control emp_code" placeholder="กรุณากรอกรหัสพนักงาน"></td>
                                                    <td><input type="text" class="form-control emp_name" placeholder="กรุณากรอกชื่อพนักงาน"></td>
                                                    <td><input type="number" class="form-control emp_percent" placeholder="กรุณากรอก% ความรับผิดชอบ"></td>
                                                    <td><input type="text" class="form-control emp_amount" disabled>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove">
                                                            ลบ
                                                        </button>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="p-3">
                                        <button type="button" class="btn btn-success btn-sm" id="add-employee">
                                            + เพิ่มพนักงาน
                                        </button>
                                    </div>
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



    <div class="card shadow-sm">

        <!-- 🔷 HEADER -->
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">ระบบจัดการใบแจ้งสินค้าทำลาย</h5>
            <button type="button" class="btn btn-primary BTNadd">
                + สร้างรายการใหม่
            </button>
        </div>
        <!-- 🔷 BODY -->
        <div class="card-body">

            <!-- 📊 SUMMARY -->
            <div class="row mb-4 justify-content-center">

                <div class="col-md-2">
                    <div class="border rounded p-3 text-center">
                        <div class="text-muted">รายการทั้งหมด</div>
                        <h4 class="mb-0">{{ $total ?? 0 }}</h4>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="border rounded p-3 text-center">
                        <div class="text-muted">รออนุมัติ</div>
                        <h4 class="mb-0 text-warning">{{ $pending ?? 0 }}</h4>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="border rounded p-3 text-center">
                        <div class="text-muted">กำลังดำเนินการ</div>
                        <h4 class="mb-0 text-info">{{ $process ?? 0 }}</h4>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="border rounded p-3 text-center">
                        <div class="text-muted">เสร็จสิ้น</div>
                        <h4 class="mb-0 text-success">{{ $success ?? 0 }}</h4>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="border rounded p-3 text-center">
                        <div class="text-muted">รอเคลม</div>
                        <h4 class="mb-0 text-danger">{{ $claim ?? 0 }}</h4>
                    </div>
                </div>

            </div>

            <!-- 📋 TABLE -->
            <div class="table-responsive">
                <table id="datatable5" class="table table-hover table-bordered align-middle">

                    <thead>
                        <tr style="background:#556ee6;color:white;">
                            <th>เลขที่เอกสาร</th>
                            <th>สาขา</th>
                            <th>ประเภท</th>
                            <th>มูลค่า</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($reports as $r)
                            <tr>
                                <td>{{ $r->doc_no }}</td>
                                <td>{{ optional($r->branch)->branch_desc ?: '-' }}</td>
                                <td>{{ $r->report_type }}</td>
                                <td>{{ number_format($r->total_amount, 2) }}</td>
                                <td class="text-center">

                                    @if ($r->status == 'pending')
                                        <span class="badge bg-warning">รออนุมัติ</span>
                                    @elseif(in_array($r->status, [
                                            'approved_manager',
                                            'waiting_branch_sap',
                                            'sap_completed',
                                            'accounting_done',
                                            'waiting_branch_print',
                                            'waiting_accounting',
                                        ]))
                                        <span class="badge bg-info">กำลังดำเนินการ</span>
                                    @elseif($r->status == 'hr_done')
                                        <span class="badge bg-primary">รอทำลาย</span>
                                    @elseif(in_array($r->status, ['destroy_completed', 'completed']))
                                        {{-- ✅ เพิ่มตรงนี้ --}}
                                        <span class="badge bg-success">เสร็จสิ้น</span>
                                    @elseif($r->status == 'rejected')
                                        <span class="badge bg-danger">ไม่อนุมัติ</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $r->status }}</span>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">ไม่มีข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on('change', 'input[name="issue_type"]', function() {

            if ($(this).val() === 'claimable') {
                $('#purchase-section').slideDown();
            } else {
                $('#purchase-section').slideUp();
                $('#purchase_name').val('');
            }

        });

        $(document).on('click', '.BTNadd', function() {
            $('#orderAdd').modal("show");
        });

        $(document).on('click', '#add-product', function() {

            let row = $('.product-row:first').clone();

            // เคลียร์ค่า
            row.find('input').val('');

            $('#product-wrapper').append(row);
        });

        $(document).on('click', '.btn-remove-product', function() {

            if ($('.product-row').length <= 1) {
                alert('ต้องมีสินค้าอย่างน้อย 1 รายการ');
                return;
            }

            $(this).closest('.product-row').remove();
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

        $(document).on('change', '.emp_code', function() {
            let code = $(this).val();
            let row = $(this).closest('.employee-row');

            $.get("{{ url('get-employee') }}", {
                code: code
            }, function(res) {

                if (res && res.emp_name) {
                    row.find('.emp_name').val(res.emp_name);
                } else {
                    row.find('.emp_name').val('');
                    alert('ไม่พบพนักงาน');
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
            let totalProduct = 0;

            $('.product-row').each(function() {
                totalProduct += parseFloat($(this).find('.total').val()) || 0;
            });
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

            if (!$('#flow_type').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกรูปแบบการดำเนินการ'
                });
                return;
            }

            let grandTotal = 0;

            $('.product-row').each(function() {
                grandTotal += parseFloat($(this).find('.total').val()) || 0;
            });

            // 📦 เตรียมข้อมูลสินค้า
            let items = [];

            $('.product-row').each(function() {

                items.push({
                    product_code: $(this).find('.product_code').val(),
                    product_name: $(this).find('.product_name').val(),
                    price: $(this).find('.price').val(),
                    qty: $(this).find('.qty').val(),
                    total: $(this).find('.total').val()
                });

            });

            // 👨‍💼 เตรียมข้อมูลพนักงาน
            let employees = [];

            $('.employee-row').each(function() {

                let percent = parseFloat($(this).find('.emp_percent').val()) || 0;

                employees.push({
                    emp_code: $(this).find('.emp_code').val(),
                    emp_name: $(this).find('.emp_name').val(),
                    percent: percent,
                    amount: (percent / 100) * grandTotal
                });

            });

            // 🚀 ยิง API
            $.ajax({
                url: "{{ url('damage-report/store') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // ✅ ต้องมี
                    branch_code: $('#branch_code').val(),
                    report_type: $('#report_source').val(),
                    product_type: $('input[name="product_type"]:checked').val(),
                    flow_type: $('#flow_type').val(),
                    issue_type: $('input[name="issue_type"]:checked').val(),
                    purchase_name: $('#purchase_name').val(),
                    damage_reason: $('#damage_reason').val(),
                    total_amount: grandTotal,
                    items: items,
                    employees: employees
                },
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ',
                            text: 'ข้อมูลถูกบันทึกเรียบร้อยแล้ว',
                            confirmButtonText: 'ตกลง',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // รีเฟรชหน้า
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: res.error,
                            confirmButtonText: 'ตกลง',
                        });
                    }
                },
            });
        });

        $(document).on('change', '.product_code', function() {

            let row = $(this).closest('.product-row');
            let code = $(this).val();

            $.get("{{ url('get-product') }}", {
                code: code
            }, function(res) {

                if (res) {
                    row.find('.product_name').val(res.product_name);
                    row.find('.price').val(res.price);
                } else {
                    row.find('.product_name').val('');
                    row.find('.price').val('');
                    alert('ไม่พบสินค้า');
                }
            });

        });

        function validateForm() {

            // ✅ 1. ตรวจข้อมูลพื้นฐาน
            if (!$('#report_source').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกประเภทผู้แจ้ง'
                });
                return false;
            }

            if (!$('input[name="product_type"]:checked').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกประเภทสินค้า'
                });
                return false;
            }

            // ✅ ต้องเลือกประเภทปัญหา
            if (!$('input[name="issue_type"]:checked').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกประเภทปัญหา'
                });
                return false;
            }

            // ✅ ถ้าเลือกเคลม ต้องกรอกผู้ดูแลจัดซื้อ
            if ($('input[name="issue_type"]:checked').val() === 'claimable') {
                if (!$('#purchase_name').val()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณากรอกผู้ดูแลจัดซื้อ'
                    });
                    return false;
                }
            }

            // ✅ ต้องกรอกสาเหตุความเสียหาย
            if (!$('textarea').val()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกสาเหตุความเสียหาย'
                });
                return false;
            }
            

            let hasProduct = false;

            $('.product-row').each(function() {
                let code = $(this).find('.product_code').val();
                let qty = $(this).find('.qty').val();

                if (code && qty) {
                    hasProduct = true;
                }
            });

            if (!hasProduct) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเพิ่มสินค้าอย่างน้อย 1 รายการ'
                });
                return false;
            }

            let invalidQty = false;

            $('.product-row').each(function() {

                let qty = $(this).find('.qty').val();

                if (!qty || qty <= 0) {
                    invalidQty = true;
                }

            });

            if (invalidQty) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกจำนวนสินค้าให้ครบและมากกว่า 0'
                });
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
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเพิ่มพนักงานอย่างน้อย 1 คน'
                });
                return false;
            }

            // ✅ 3. ตรวจ % รวม
            let totalPercent = 0;

            $('.emp_percent').each(function() {
                totalPercent += parseFloat($(this).val()) || 0;
            });

            if (totalPercent !== 100) {
                Swal.fire({
                    icon: 'error',
                    title: 'เปอร์เซ็นต์ไม่ถูกต้อง',
                    text: 'เปอร์เซ็นต์รวมต้องเท่ากับ 100%'
                });
                return false;
            }

            // ✅ 4. ตรวจมูลค่า
            let totalProduct = 0;

            $('.product-row').each(function() {
                totalProduct += parseFloat($(this).find('.total').val()) || 0;
            });
            let totalEmpAmount = 0;

            $('.emp_percent').each(function() {
                let percent = parseFloat($(this).val()) || 0;
                totalEmpAmount += (percent / 100) * totalProduct;
            });

            if (Math.round(totalEmpAmount) !== Math.round(totalProduct)) {
                Swal.fire({
                    icon: 'error',
                    title: 'มูลค่าไม่ตรง',
                    text: 'มูลค่าความรับผิดชอบไม่ตรงกับราคาสินค้า'
                });
                return false;
            }

            return true;
        }

        $(document).on('keyup change', '.qty', function() {

            let row = $(this).closest('.product-row');

            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;

            row.find('.total').val(price * qty);
        });

        $(document).on('click', '#add-employee', function() {

            let row = $('.employee-row:first').clone();

            // เคลียร์ค่า
            row.find('input').val('');

            $('#employee-wrapper').append(row);

        });

        $(document).on('click', '.btn-remove', function() {
            let row = $(this).closest('.employee-row');

            // เช็คว่าต้องมีอย่างน้อย 1 คน
            if ($('.employee-row').length <= 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่สามารถลบได้',
                    text: 'ต้องมีพนักงานอย่างน้อย 1 คน'
                });
                return;
            }

            // Confirm ก่อนลบ
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: 'คุณต้องการลบพนักงานคนนี้หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่, ลบเลย',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {

                    row.remove();

                    Swal.fire({
                        icon: 'success',
                        title: 'ลบเรียบร้อย',
                        timer: 1200,
                        showConfirmButton: false
                    });

                }
            });
        });

        $(document).on('click', '.dropdown-item', function() {

            let text = $(this).text();
            let value = $(this).data('value');

            $('#dropdownReport').text(text);
            $('#report_source').val(value);

        });

        $('#flow_type').on('change', function() {

            let flow = $(this).val();

            $('.source-option').hide();
            $('#report_source').val('');
            $('#dropdownReport').text('เลือกประเภทผู้แจ้ง');

            if (flow === 'destroy' || flow === 'discount') {

                $('.source-option[data-group="all"]').show();
                $('.source-option[data-group="basic"]').show();

            } else if (flow === 'claim') {

                $('.source-option[data-group="all"]').show();
                $('.source-option[data-group="claim"]').show();
            }

        });

        function calculateEmployeeAmount() {

            let totalProduct = 0;

            $('.product-row').each(function() {
                totalProduct += parseFloat($(this).find('.total').val()) || 0;
            });

            $('#employee-wrapper tr').each(function() {

                let percent = parseFloat($(this).find('.emp_percent').val()) || 0;

                let amount = (percent / 100) * totalProduct;

                $(this).find('.emp_amount').val(amount.toFixed(2));

            });

        }

        $(document).on('keyup change', '.emp_percent', function() {
            calculateEmployeeAmount();
        });

        $(document).on('keyup change', '.qty', function() {

            let row = $(this).closest('tr');

            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;

            row.find('.total').val(price * qty);

            calculateEmployeeAmount(); // 👈 สำคัญ
        });
    </script>
@endsection

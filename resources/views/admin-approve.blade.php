@extends('layouts.master-layouts')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>อนุมัติรายการสินค้าเสียหาย (ผู้บริหาร)</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>เลขที่เอกสาร</th>
                        <th>สาขา</th>
                        <th>มูลค่า</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($reports as $r)
                        <tr>
                            <td>{{ $r->doc_no }}</td>
                            <td>{{ $r->branch_code }}</td>
                            <td>{{ number_format($r->total_amount, 2) }}</td>
                            <td>
                                <button class="btn btn-info btn-detail" data-id="{{ $r->id }}">
                                    ดูรายละเอียด
                                </button>

                                <button class="btn btn-success btn-approve" data-id="{{ $r->id }}"
                                    data-percent="{{ $r->manager_discount_percent }}" data-flow="{{ $r->flow_type }}">
                                    อนุมัติ
                                </button>

                                <button class="btn btn-danger btn-reject" data-id="{{ $r->id }}">
                                    ไม่อนุมัติ
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">รายละเอียดใบแจ้งสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">

                    <input type="hidden" id="d_id">

                    <!-- 🔵 ข้อมูลเอกสาร -->
                    <div class="card mb-4">
                        <div class="card-header fw-bold">ข้อมูลเอกสาร</div>

                        <div class="card-body">
                            <div class="row g-4">

                                <!-- LEFT -->
                                <div class="col-md-6">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label class="form-label">เลขที่เอกสาร</label>
                                            <input type="text" id="d_doc_no" class="form-control" disabled>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">มูลค่า</label>
                                            <input type="text" id="d_total" class="form-control" disabled>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">รูปแบบการดำเนินการ *</label>
                                            <select id="d_flow_type" class="form-select">
                                                <option value="destroy">ทำลายสินค้า</option>
                                                <option value="discount">ลดราคา</option>
                                                <option value="claim">เคลมสินค้า</option>
                                                <option value="quality">ปรับปรุงคุณภาพสินค้า</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">ประเภทผู้แจ้ง *</label>
                                            <select id="d_report_source" class="form-select">
                                                <option value="">-- เลือกประเภทผู้แจ้ง --</option>
                                                <option value="branch">สาขาแจ้ง</option>
                                                <option value="customer">ลูกค้าแจ้ง</option>
                                                <option value="dc">DC แจ้ง</option>
                                                <option value="purchase_local">จัดซื้อในประเทศ</option>
                                                <option value="purchase_inter">จัดซื้อต่างประเทศ</option>
                                            </select>
                                        </div>

                                        <!-- PRODUCT TYPE -->
                                        <div class="col-md-6">
                                            <label class="form-label d-block">ประเภทสินค้า *</label>
                                            <div class="d-flex gap-4 mt-1">
                                                <div class="form-check">
                                                    <input type="radio" name="d_product_type" value="domestic"
                                                        class="form-check-input">
                                                    <label class="form-check-label">ในประเทศ</label>
                                                </div>

                                                <div class="form-check">
                                                    <input type="radio" name="d_product_type" value="international"
                                                        class="form-check-input">
                                                    <label class="form-check-label">ต่างประเทศ</label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ISSUE TYPE -->
                                        <div class="col-md-6">
                                            <label class="form-label d-block">ประเภทปัญหา *</label>
                                            <div class="d-flex flex-column gap-2 mt-1">
                                                <div class="form-check">
                                                    <input type="radio" name="d_issue_type" value="defect"
                                                        class="form-check-input">
                                                    <label class="form-check-label">
                                                        สินค้าด้อยคุณภาพจากการผลิต
                                                    </label>
                                                </div>

                                                <div class="form-check">
                                                    <input type="radio" name="d_issue_type" value="claimable"
                                                        class="form-check-input">
                                                    <label class="form-check-label">
                                                        สินค้าเสียหายที่สามารถเคลมได้
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-6">
                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <label class="form-label">สาขา *</label>
                                            <select class="form-select" id="d_branch_code">
                                                @foreach (getBranchAll() as $item)
                                                    <option value="{{ $item->branch_code }}">
                                                        {{ $item->branch_code }} - {{ $item->branch_desc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">วันที่เอกสาร</label>
                                            <input type="text" class="form-control" id="d_date" disabled>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">สาเหตุความเสียหาย *</label>
                                            <textarea id="d_damage_reason" class="form-control" rows="3"></textarea>
                                        </div>

                                        <div class="col-md-12">
                                            <label class="form-label">แนบรูป / เอกสาร</label>
                                            <input type="file" class="form-control" multiple>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- 📦 สินค้า -->
                    <div class="card mb-3">
                        <div class="card-header fw-bold">สินค้า</div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>ชื่อสินค้า</th>
                                        <th width="120">ราคา</th>
                                        <th width="120">จำนวน</th>
                                        <th width="150">รวม</th>
                                        <th width="80"></th>
                                    </tr>
                                </thead>
                                <tbody id="detail-products"></tbody>
                            </table>
                        </div>

                        <div class="p-2">
                            <button class="btn btn-success btn-sm btn-add-product">
                                + เพิ่มสินค้า
                            </button>
                        </div>
                    </div>

                    <!-- 👨‍💼 พนักงาน -->
                    <div class="card mb-3" id="employee-box">
                        <div class="card-header fw-bold">พนักงาน</div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>รหัส</th>
                                        <th>ชื่อ</th>
                                        <th width="120">%</th>
                                        <th width="150">มูลค่า</th>
                                        <th width="80"></th>
                                    </tr>
                                </thead>
                                <tbody id="detail-employees"></tbody>
                            </table>
                        </div>

                        <div class="p-2">
                            <button class="btn btn-success btn-sm btn-add-emp">
                                + เพิ่มพนักงาน
                            </button>
                        </div>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button class="btn btn-primary btn-update">บันทึก</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.btn-approve').click(function() {

            let row = $(this).closest('tr');
            let id = $(this).data('id');
            let flowType = ($(this).data('flow') || '')
                .toString()
                .trim()
                .toLowerCase(); // ✅ เพิ่ม

            let total = parseFloat(row.find('td:nth-child(3)').text().replace(/,/g, '')) || 0;

            // =========================
            // 🔥 CASE 1: DESTROY
            // =========================
            if (flowType === 'destroy') {

                Swal.fire({
                    title: 'ยืนยันอนุมัติ',
                    text: 'ต้องการอนุมัติรายการทำลายสินค้านี้ใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน'
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.post("{{ route('damage.admin.action') }}", {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        action: 'approved'
                    }, function(res) {

                        if (!res.success) {
                            Swal.fire('ผิดพลาด', res.error, 'error');
                            return;
                        }

                        Swal.fire('สำเร็จ', 'อนุมัติเรียบร้อย', 'success')
                            .then(() => location.reload());

                    });

                });

                return;
            }


            // =========================
            // 🔥 CASE 2: CLAIM (เพิ่มใหม่)
            // =========================
            if (flowType === 'claim') {

                Swal.fire({
                    title: 'ยืนยันอนุมัติ',
                    text: 'ส่งรายการนี้เข้าสู่กระบวนการเคลมใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน'
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.post("{{ route('damage.admin.action') }}", {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        action: 'approved'
                    }, function(res) {

                        if (!res.success) {
                            Swal.fire('ผิดพลาด', res.error, 'error');
                            return;
                        }

                        Swal.fire('สำเร็จ', 'ส่งเคลมแล้ว', 'success')
                            .then(() => location.reload());

                    });

                });

                return;
            }

            // =========================
            // 🔥 CASE 3: DISCOUNT
            // =========================
            let percentDefault = $(this).data('percent') || 0;

            Swal.fire({
                title: 'กำหนดส่วนลด (ผู้บริหาร)',
                html: `
                <div class="row mt-3 discount-box">
                    <div class="col-md-4">
                        <label>% ส่วนลด</label>
                        <input type="number" id="discount_percent" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>ส่วนลด</label>
                        <input type="text" id="discount_amount" class="form-control" disabled>
                    </div>
                    <div class="col-md-4">
                        <label>สุทธิ</label>
                        <input type="text" id="net_amount" class="form-control" disabled>
                    </div>
                </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'อนุมัติ',
                cancelButtonText: 'ยกเลิก',

                didOpen: () => {

                    const percentInput = document.getElementById('discount_percent');
                    const discountInput = document.getElementById('discount_amount');
                    const netInput = document.getElementById('net_amount');

                    percentInput.focus();
                    percentInput.select();
                    percentInput.value = percentDefault;
                    calculate();

                    function calculate() {
                        let percent = parseFloat(percentInput.value) || 0;

                        if (percent < 0) percent = 0;
                        if (percent > 100) percent = 100;

                        let discount = (total * percent) / 100;
                        let net = total - discount;

                        discountInput.value = discount.toLocaleString(undefined, {
                            minimumFractionDigits: 2
                        });

                        netInput.value = net.toLocaleString(undefined, {
                            minimumFractionDigits: 2
                        });
                    }

                    percentInput.addEventListener('input', calculate);

                    calculate(); // โหลดครั้งแรก
                },

                preConfirm: () => {
                    let percent = document.getElementById('discount_percent').value;

                    if (percent === "") {
                        Swal.showValidationMessage('กรุณาใส่ % ส่วนลด');
                        return false;
                    }

                    return {
                        percent
                    };
                }

            }).then((result) => {

                if (!result.isConfirmed) return;

                $.post("{{ route('damage.admin.action') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: 'approved',
                    manager_discount_percent: result.value.percent
                }, function(res) {

                    if (!res.success) {
                        Swal.fire('ผิดพลาด', res.error, 'error');
                        return;
                    }

                    Swal.fire('สำเร็จ', 'อนุมัติแล้ว', 'success')
                        .then(() => location.reload());
                });

            });

        });

        $('.btn-reject').click(function() {

            let id = $(this).data('id');

            Swal.fire({
                title: 'ไม่อนุมัติ',
                input: 'textarea',
                inputPlaceholder: 'ระบุเหตุผล...',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
            }).then((result) => {

                if (!result.isConfirmed || !result.value) return;

                $.post("{{ route('damage.admin.action') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: 'rejected',
                    remark: result.value
                }, function(res) {

                    Swal.fire('เรียบร้อย', 'ไม่อนุมัติแล้ว', 'success')
                        .then(() => location.reload());

                });

            });

        });

        // ================= LOAD DETAIL =================
        $(document).on('click', '.btn-detail', function() {

            let id = $(this).data('id');

            $('#d_id').val(id);

            $.get("{{ url('damage-report/detail') }}", {
                id: id
            }, function(res) {

                let reportSource = (res.report_type || '').toString().trim().toLowerCase();

                // 🔵 HEADER
                $('#d_doc_no').val(res.doc_no);
                $('#d_branch_code').val(res.branch_code);
                $('#d_flow_type').val(res.flow_type);
                $('#d_total').val(parseFloat(res.total_amount).toLocaleString());
                $('#d_damage_reason').val(res.damage_reason);
                $('#d_report_source').val(reportSource);

                $('input[name="d_product_type"][value="' + res.product_type + '"]').prop('checked', true);
                $('input[name="d_issue_type"][value="' + res.issue_type + '"]').prop('checked', true);

                $('#d_date').val(res.created_at?.substring(0, 10));

                // ================= DISCOUNT =================
                let total = parseFloat(res.total_amount) || 0;
                let percent = parseFloat(res.manager_discount_percent) || 0;

                let discount = (total * percent) / 100;
                let net = total - discount;

                $('#discount_percent').val(percent);
                $('#discount_amount').val(discount.toFixed(2));
                $('#net_amount').val(net.toFixed(2));

                if (res.flow_type === 'discount') {
                    $('.discount-box').show();
                } else {
                    $('.discount-box').hide();
                }

                // ================= PRODUCT =================
                let productHtml = '';

                res.items.forEach(function(i) {
                    productHtml += `
                <tr class="product-row">
                    <td><input class="form-control product_code" value="${i.product_code}"></td>
                    <td><input class="form-control product_name" value="${i.product_name}"></td>
                    <td><input class="form-control price" value="${i.price}"></td>
                    <td><input type="number" class="form-control qty" value="${i.qty}"></td>
                    <td><input class="form-control total" value="${i.total}"></td>
                    <td>
                        <button class="btn btn-danger btn-sm remove-product">ลบ</button>
                    </td>
                </tr>
            `;
                });

                $('#detail-products').html(productHtml);

                calculateTotal();

                // ================= EMPLOYEE =================
                let empHtml = '';

                if (res.employees && res.employees.length > 0) {

                    res.employees.forEach(function(e) {
                        empHtml += `
                    <tr class="employee-row">
                        <td><input class="form-control emp_code" value="${e.emp_code}"></td>
                        <td><input class="form-control emp_name" value="${e.emp_name}"></td>
                        <td><input type="number" class="form-control emp_percent" value="${e.percent}"></td>
                        <td><input class="form-control emp_amount" value="${e.amount}"></td>
                        <td>
                            <button class="btn btn-danger btn-sm remove-emp">ลบ</button>
                        </td>
                    </tr>
                `;
                    });

                    $('#employee-box').show();

                } else {
                    $('#employee-box').hide();
                }

                $('#detail-employees').html(empHtml);

                // 🔥 flow control เหมือน manager
                if (res.flow_type === 'quality') {
                    $('#employee-box').hide();
                }

                // 🚀 SHOW MODAL
                $('#modalDetail').modal('show');
            });

        });


        // ================= UPDATE =================
        $('.btn-update').click(function() {



            let id = $('#d_id').val();
            console.log("ID:", id);
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

            let employees = [];
            $('.employee-row').each(function() {
                employees.push({
                    emp_code: $(this).find('.emp_code').val(),
                    emp_name: $(this).find('.emp_name').val(),
                    percent: $(this).find('.emp_percent').val(),
                    amount: $(this).find('.emp_amount').val()
                });
            });

            $.post("{{ url('damage-report/update') }}", {
                _token: '{{ csrf_token() }}',
                id: id,

                branch_code: $('#d_branch_code').val(),
                flow_type: $('#d_flow_type').val(),
                damage_reason: $('#d_damage_reason').val(),

                report_type: $('#d_report_source').val(),
                product_type: $('input[name="d_product_type"]:checked').val(),
                issue_type: $('input[name="d_issue_type"]:checked').val(),

                items: items,
                employees: employees
            }, function(res) {

                if (res.success) {
                    Swal.fire('สำเร็จ', 'อัปเดตแล้ว', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', res.error, 'error');
                }

            });

        });


        // ================= ADD ROW =================
        $(document).on('click', '.btn-add-product', function() {
            $('#detail-products').append(`
        <tr class="product-row">
            <td><input class="form-control product_code"></td>
            <td><input class="form-control product_name"></td>
            <td><input class="form-control price"></td>
            <td><input type="number" class="form-control qty"></td>
            <td><input class="form-control total"></td>
            <td><button class="btn btn-danger btn-sm remove-product">ลบ</button></td>
        </tr>
    `);
            calculateTotal();
        });

        $(document).on('click', '.btn-add-emp', function() {
            $('#detail-employees').append(`
        <tr class="employee-row">
            <td><input class="form-control emp_code"></td>
            <td><input class="form-control emp_name"></td>
            <td><input type="number" class="form-control emp_percent"></td>
            <td><input class="form-control emp_amount"></td>
            <td><button class="btn btn-danger btn-sm remove-emp">ลบ</button></td>
        </tr>
    `);
        });

        $(document).on('click', '.remove-product', function() {
            $(this).closest('tr').remove();
            calculateTotal();
        });

        $(document).on('click', '.remove-emp', function() {
            $(this).closest('tr').remove();
        });

        $(document).on('input', '.price, .qty', function() {
            let row = $(this).closest('tr');

            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;

            let total = price * qty;

            row.find('.total').val((price * qty).toFixed(2));

            calculateTotal();
        });

        $(document).on('input', '.emp_percent', function() {

            let total = 0;

            $('.product-row').each(function() {
                total += parseFloat($(this).find('.total').val()) || 0;
            });

            $('.employee-row').each(function() {
                let percent = parseFloat($(this).find('.emp_percent').val()) || 0;
                let amount = (total * percent) / 100;

                $(this).find('.emp_amount').val(amount.toFixed(2));
            });

        });

        function calculateEmployee(total) {

            $('.employee-row').each(function() {

                let percent = parseFloat($(this).find('.emp_percent').val()) || 0;

                let amount = (total * percent) / 100;

                $(this).find('.emp_amount').val(amount.toFixed(2));
            });

        }

        $(document).on('blur', '.product_code', function() {

            let code = $(this).val();
            let row = $(this).closest('tr');

            if (!code) return;

            $.get("{{ url('damage-report/get-product') }}", {
                code: code
            }, function(res) {

                if (res) {
                    row.find('.product_name').val(res.product_name);
                    row.find('.price').val(res.price);

                    let qty = parseFloat(row.find('.qty').val()) || 0;
                    row.find('.total').val(res.price * qty);

                    calculateTotal(); // 🔥 สำคัญ
                } else {
                    Swal.fire('ไม่พบสินค้า');
                }

            });

        });

        $(document).on('blur', '.emp_code', function() {

            let code = $(this).val();
            let row = $(this).closest('tr');

            if (!code) return;

            $.get("{{ url('damage-report/get-employee') }}", {
                code: code
            }, function(res) {

                if (res) {
                    row.find('.emp_name').val(res.emp_name);
                } else {
                    Swal.fire('ไม่พบพนักงาน');
                }

            });

        });

        function calculateTotal() {
            let total = 0;

            $('.product-row').each(function() {
                let price = parseFloat($(this).find('.price').val()) || 0;
                let qty = parseFloat($(this).find('.qty').val()) || 0;

                let rowTotal = price * qty;
                $(this).find('.total').val(rowTotal.toFixed(2));

                total += rowTotal;
            });

            $('#d_total').val(total.toLocaleString());

            calculateEmployee(total); // 🔥 อัพเดตพนักงานด้วย
        }
    </script>
@endsection

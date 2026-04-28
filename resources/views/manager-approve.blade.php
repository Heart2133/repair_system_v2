@extends('layouts.master-layouts')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>อนุมัติรายการสินค้าเสียหาย (Manager)</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>เลขที่เอกสาร</th>
                        <th>สาขา</th>
                        <th>มูลค่า</th>
                        {{-- <th>% ส่วนลด</th> --}}
                        <th>จัดการ</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($reports as $r)
                        <tr>
                            <td>{{ $r->doc_no }}</td>
                            <td>{{ $r->branch_code }}</td>
                            <td>{{ number_format($r->total_amount, 2) }}</td>
                            {{-- <td>
                                @if ($r->flow_type == 'discount')
                                    <input type="number" class="form-control manager_percent" placeholder="% ลด">
                                @else
                                    -
                                @endif
                            </td> --}}
                            <td>
                                <button class="btn btn-info btn-detail" data-id="{{ $r->id }}">
                                    ดูรายละเอียด
                                </button>

                                <button class="btn btn-success btn-approve" data-id="{{ $r->id }}"
                                    data-flow="{{ $r->flow_type }}">
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
        <div class="modal-dialog" style="max-width: 95%;">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">รายละเอียดใบแจ้งสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="d_id">
                    <!-- 🔵 ข้อมูลเอกสาร -->
                    <div class="card mb-4">
                        <div class="card-header">ข้อมูลเอกสาร</div>

                        <div class="card-body">
                            <div class="row">

                                <!-- 🔵 ซ้าย -->
                                <div class="col-md-6">
                                    <div class="row">

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">เลขที่เอกสาร</label>
                                            <input type="text" id="d_doc_no" class="form-control" disabled>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">มูลค่า</label>
                                            <input type="text" id="d_total" class="form-control" disabled>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">รูปแบบการดำเนินการ *</label>
                                            <select id="d_flow_type" class="form-select">
                                                <option value="destroy">ทำลายสินค้า</option>
                                                <option value="discount">ลดราคา</option>
                                                <option value="claim">เคลมสินค้า</option>
                                                <option value="quality">ปรับปรุงคุณภาพสินค้า</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">ประเภทผู้แจ้ง *</label>
                                            <select id="d_report_source" class="form-select">
                                                <option value="">-- เลือกประเภทผู้แจ้ง --</option>
                                                <option value="branch">สาขาแจ้ง</option>
                                                <option value="customer">ลูกค้าแจ้ง</option>
                                                <option value="dc">DC แจ้ง</option>
                                                <option value="purchase_local">จัดซื้อในประเทศ</option>
                                                <option value="purchase_inter">จัดซื้อต่างประเทศ</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">ประเภทสินค้า *</label>

                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input type="radio" name="d_product_type" value="domestic"
                                                        class="form-check-input">
                                                    <label class="form-check-label">ในประเทศ</label>
                                                </div>

                                                <div class="form-check">
                                                    <input type="radio" name="d_product_type" value="international"
                                                        class="form-check-input">
                                                    <label class="form-check-label">นอกประเทศ</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">ประเภทปัญหา *</label>

                                            <div class="d-flex flex-column gap-2">
                                                <div class="form-check">
                                                    <input type="radio" name="d_issue_type" value="defect"
                                                        class="form-check-input">
                                                    <label class="form-check-label">สินค้าด้อยคุณภาพจากการผลิต</label>
                                                </div>

                                                <div class="form-check">
                                                    <input type="radio" name="d_issue_type" value="claimable"
                                                        class="form-check-input">
                                                    <label class="form-check-label">สินค้าเสียหายที่สามารถเคลมได้</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- 🟢 ขวา -->
                                <div class="col-md-6">

                                    <div class="row">

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">สาขา *</label>
                                            <select class="form-select" id="d_branch_code">
                                                @foreach (getBranchAll() as $item)
                                                    <option value="{{ $item->branch_code }}">
                                                        {{ $item->branch_code }} - {{ $item->branch_desc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">วันที่เอกสาร</label>
                                            <input type="text" class="form-control" id="d_date" disabled>
                                        </div>

                                    </div>

                                    <div class="mb-4">
                                        <label class="mb-2 d-block">สาเหตุความเสียหาย *</label>
                                        <textarea id="d_damage_reason" class="form-control"></textarea>
                                    </div>

                                    <div>
                                        <label class="mb-2 d-block">แนบรูป / เอกสาร</label>
                                        <input type="file" class="form-control" multiple>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- 📦 สินค้า -->
                    <div class="card mb-3">
                        <div class="card-header">สินค้า</div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>ราคา</th>
                                        <th>จำนวน</th>
                                        <th>รวม</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="detail-products"></tbody>
                            </table>
                        </div>
                        <div class="p-1">
                            <button class="btn btn-success btn-add-product">+ เพิ่มสินค้า</button>
                        </div>

                    </div>

                    <!-- 👨‍💼 พนักงาน -->
                    <div class="card mb-3" id="employee-box">
                        <div class="card-header">พนักงาน</div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>รหัส</th>
                                        <th>ชื่อ</th>
                                        <th>%</th>
                                        <th>มูลค่า</th>
                                    </tr>
                                </thead>
                                <tbody id="detail-employees"></tbody>
                            </table>
                        </div>
                        <div class="p-1">
                            <button class="btn btn-success btn-add-emp">+ เพิ่มพนักงาน</button>
                        </div>
                    </div>

                </div>

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
        $('.btn-approve').on('click', function() {

            const $row = $(this).closest('tr');
            const recordId = $(this).data('id');

            const flowType = ($(this).data('flow') || '')
                .toString()
                .trim()
                .toLowerCase();

            const totalAmount = parseFloat(
                $row.find('td:nth-child(3)').text().replace(/,/g, '')
            ) || 0;

            if (!flowType) {
                Swal.fire('Error', 'flow_type ไม่มีค่า', 'error');
                return;
            }

            // ================= DESTROY =================
            if (flowType === 'destroy') {

                Swal.fire({
                    title: 'ยืนยันการอนุมัติ',
                    text: 'ต้องการอนุมัติรายการนี้ใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.post('{{ url('damage-report/approve-action') }}', {
                        _token: '{{ csrf_token() }}',
                        id: recordId,
                        action: 'approved_manager'
                    }, function() {
                        Swal.fire('สำเร็จ', 'อนุมัติแล้ว', 'success')
                            .then(() => location.reload());
                    });

                });

                return;
            }

            // ================= CLAIM =================
            if (flowType === 'claim') {

                Swal.fire({
                    title: 'ยืนยันส่งเคลม',
                    text: 'ต้องการส่งเคลมหรือไม่?',
                    icon: 'question',
                    showCancelButton: true
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.post('{{ url('damage-report/approve-action') }}', {
                        _token: '{{ csrf_token() }}',
                        id: recordId,
                        action: 'approved_manager'
                    }, function() {
                        Swal.fire('สำเร็จ', 'ส่งเคลมแล้ว', 'success')
                            .then(() => location.reload());
                    });

                });

                return;
            }

            // ================= DISCOUNT =================
            if (flowType === 'discount') {

                Swal.fire({
                    title: 'กำหนดส่วนลด',
                    input: 'number',
                    inputPlaceholder: '% ส่วนลด',
                    showCancelButton: true
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.post('{{ url('damage-report/approve-action') }}', {
                        _token: '{{ csrf_token() }}',
                        id: recordId,
                        action: 'approved_manager',
                        manager_discount_percent: result.value
                    }, function() {
                        Swal.fire('สำเร็จ', 'บันทึกแล้ว', 'success')
                            .then(() => location.reload());
                    });

                });

                return;
            }

            Swal.fire('Error', 'flow_type ไม่ถูกต้อง: ' + flowType, 'error');

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
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                inputValidator: (value) => {
                    if (!value) {
                        return 'กรุณาระบุเหตุผล!';
                    }
                }
            }).then((result) => {

                if (!result.isConfirmed) return;

                // 🔥 แสดง loading
                Swal.fire({
                    title: 'กำลังบันทึก...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.post("{{ route('damage.admin.action') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: 'rejected',
                    remark: result.value
                }, function(res) {

                    if (res.success) {
                        Swal.fire(
                            'เรียบร้อย',
                            'ไม่อนุมัติแล้ว',
                            'success'
                        ).then(() => location.reload());
                    } else {
                        Swal.fire(
                            'ผิดพลาด',
                            res.error || 'เกิดข้อผิดพลาด',
                            'error'
                        );
                    }

                });

            });

        });

        $(document).on('click', '.btn-detail', function() {

            let id = $(this).data('id');

            // $('#d_id').val(res.id);

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

                // 📦 PRODUCT
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
                // 👨‍💼 EMPLOYEE
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


                // 🔥 flow control (เหมือนหน้า create)
                if (res.flow_type === 'quality') {
                    $('#employee-box').hide();
                } else {
                    $('#employee-box').show();
                }

                // 🚀 SHOW MODAL
                $('#modalDetail').modal('show');

            });

        });

        function calculateTotal() {
            let total = 0;

            $('.product-row').each(function() {
                let price = parseFloat($(this).find('.price').val()) || 0;
                let qty = parseFloat($(this).find('.qty').val()) || 0;

                total += price * qty;
            });

            $('#d_total').val(total.toLocaleString());

            calculateEmployee(total); // 🔥 เพิ่มตรงนี้
        }

        $(document).on('click', '.remove-emp', function() {
            $(this).closest('tr').remove();
        });

        $('.btn-update').click(function() {

            let id = $('#d_id').val();

            let total = parseFloat($('#d_total').val().replace(/,/g, '')) || 0;

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
                product_type: $('input[name="d_product_type"]:checked').val(),
                issue_type: $('input[name="d_issue_type"]:checked').val(),
                report_type: $('#d_report_source').val(),
                total_amount: total, // 🔥 เพิ่มตัวนี้
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

        $(document).on('input', '.qty, .price', function() {
            let row = $(this).closest('tr');
            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;

            row.find('.total').val(price * qty);

            calculateTotal(); // ✅ เพิ่มตรงนี้
        });

        $(document).on('click', '.remove-product', function() {
            $(this).closest('tr').remove();
            calculateTotal(); // ✅ ต้องมี
        });

        function calculateEmployee(total) {
            $('.employee-row').each(function() {

                let percent = parseFloat($(this).find('.emp_percent').val()) || 0;

                let amount = (total * percent) / 100;

                $(this).find('.emp_amount').val(amount.toFixed(2));
            });
        }

        $(document).on('input', '.emp_percent', function() {

            let total = 0;

            $('.product-row').each(function() {
                let price = parseFloat($(this).find('.price').val()) || 0;
                let qty = parseFloat($(this).find('.qty').val()) || 0;
                total += price * qty;
            });

            calculateEmployee(total);
        });

        $(document).on('click', '.btn-add-product', function() {

            let html = `
        <tr class="product-row">
            <td><input class="form-control product_code"></td>
            <td><input class="form-control product_name"></td>
            <td><input class="form-control price"></td>
            <td><input type="number" class="form-control qty"></td>
            <td><input class="form-control total" readonly></td>
            <td>
                <button class="btn btn-danger btn-sm remove-product">ลบ</button>
            </td>
        </tr>
    `;

            $('#detail-products').append(html);
        });

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

                    calculateTotal();
                } else {
                    Swal.fire('ไม่พบสินค้า');
                }

            });

        });

        $(document).on('click', '.btn-add-emp', function() {

            let html = `
        <tr class="employee-row">
            <td><input class="form-control emp_code"></td>
            <td><input class="form-control emp_name"></td>
            <td><input type="number" class="form-control emp_percent"></td>
            <td><input class="form-control emp_amount" readonly></td>
            <td>
                <button class="btn btn-danger btn-sm remove-emp">ลบ</button>
            </td>
        </tr>
    `;

            $('#detail-employees').append(html);
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
    </script>
@endsection

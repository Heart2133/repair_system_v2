@extends('layouts.master-layouts')

@section('css')
    <style>
        .table-readonly {
            background-color: #f8f9fa;
        }

        .table-readonly tbody tr {
            background-color: #f1f3f5;
        }

        .table-readonly input {
            background-color: #e9ecef !important;
            border: 1px solid #dee2e6;
            pointer-events: none;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>อนุมัติรายการสินค้าเสียหาย (Manager)</h5>
        </div>

        <!-- 🔷 DETAIL (เหมือน SAP) -->
        <div class="card mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">รายละเอียดใบแจ้งสินค้า</h5>
                <div>
                    <span class="text-muted">เลขที่เอกสาร:</span>
                    <span class="fw-bold text-primary" id="d_doc_no_text">-</span>
                </div>
            </div>

            <div class="card-body">

                <input type="hidden" id="d_id">

                <!-- 🔵 ข้อมูลเอกสาร -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">ข้อมูลเอกสาร</h6>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <!-- LEFT -->
                            <div class="col-md-6">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label>วันที่เอกสาร</label>
                                        <input type="text" class="form-control" id="d_date" disabled>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>สาขา</label>
                                        <input type="text" class="form-control" id="d_branch_code" disabled>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>รูปแบบ</label>
                                        <input type="text" class="form-control" id="d_flow_type" disabled>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>ผู้แจ้ง</label>
                                        <input type="text" class="form-control" id="d_report_source" disabled>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>ประเภทสินค้า</label>
                                        <input type="text" class="form-control" id="d_product_type" disabled>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>ประเภทปัญหา</label>
                                        <input type="text" class="form-control" id="d_issue_type" disabled>
                                    </div>

                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-6">

                                <div class="mb-4">

                                    <label class="mb-2 d-block">
                                        แนบรูป / เอกสาร
                                    </label>

                                    <div id="d_files">

                                        <span class="text-muted">
                                            ไม่มีไฟล์แนบ
                                        </span>

                                    </div>

                                </div>

                                <div class="mb-4">
                                    <label class="mb-2 d-block">สาเหตุความเสียหาย</label>
                                    <textarea id="d_damage_reason" class="form-control" rows="6" disabled></textarea>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <!-- 📦 PRODUCT -->
                <div class="card mb-3">
                    <div class="card-header">สินค้า</div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-readonly">
                            <thead>
                                <tr>
                                    <th>รหัส</th>
                                    <th>ชื่อ</th>
                                    <th>ราคา</th>
                                    <th>จำนวน</th>
                                    <th>รวม</th>
                                </tr>
                            </thead>
                            <tbody id="detail-products"></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">รวม</td>
                                    <td class="text-end fw-bold">
                                        <span id="d_total">0.00</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- 👨‍💼 EMPLOYEE -->
                <div class="card mb-3" id="employee-box">
                    <div class="card-header">พนักงาน</div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-readonly">
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
                </div>

            </div>
        </div>

        <div class="card mb-3" id="discount-box" style="display:none;">

            <div class="card-header">
                ข้อมูลส่วนลด
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">
                        <label>% ส่วนลด</label>

                        <input type="number" class="form-control" id="discount_percent" value="0">
                    </div>

                    <div class="col-md-4">
                        <label>ราคาส่วนลด</label>

                        <input type="text" class="form-control" id="discount_amount" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>สุทธิ</label>

                        <input type="text" class="form-control" id="net_amount" readonly>
                    </div>

                </div>

            </div>

        </div>

        <!-- 🔷 LIST -->
        <div class="card-body d-flex justify-content-end gap-2">

            <input type="hidden" id="first_report_id" value="{{ request('id') }}">

            <button class="btn btn-success btn-manager-approve">
                อนุมัติ
            </button>

            <button class="btn btn-danger btn-reject">
                ไม่อนุมัติ
            </button>

        </div>

    </div>

    <!-- FILE PREVIEW MODAL -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content border-0 shadow-lg rounded-4">

                <div class="modal-header border-0">

                    <h5 class="modal-title fw-bold">
                        ดูไฟล์แนบ
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body text-center" id="previewContainer">

                </div>

            </div>

        </div>

    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            let firstId = $('#first_report_id').val();

            if (firstId) {

                $('#d_id').val(firstId);

                loadDetail(firstId);

            }

        });

        $(document).on('click', '.row-click', function() {

            let id = $(this).data('id');

            $('#d_id').val(id);

            loadDetail(id);

        });

        let currentDetailId = null;
        let currentXHR = null;

        function loadDetail(id) {

            currentDetailId = id;

            // =========================
            // CANCEL REQUEST เก่า
            // =========================
            if (currentXHR) {
                currentXHR.abort();
            }

            // =========================
            // RESET UI
            // =========================
            $('#detail-products').empty();
            $('#detail-employees').empty();

            $('#d_doc_no_text').text('-');

            $('#d_branch_code').val('');
            $('#d_flow_type').val('');
            $('#d_total').text('0.00');
            $('#d_damage_reason').val('');
            $('#d_report_source').val('');
            $('#d_product_type').val('');
            $('#d_issue_type').val('');
            $('#d_date').val('');

            $('#d_files').html(`
        <span class="text-muted">
            ไม่มีไฟล์แนบ
        </span>
    `);

            $('#employee-box').hide();

            // =========================
            // LOAD DATA
            // =========================
            currentXHR = $.ajax({
                url: "{{ url('damage-report/detail') }}",
                method: 'GET',
                data: {
                    id: id
                },
                success: function(res) {

                    // =========================
                    // กัน response มั่ว
                    // =========================
                    if (currentDetailId != id) {
                        return;
                    }

                    let reportSource = (res.report_type || '')
                        .toString()
                        .trim()
                        .toLowerCase();

                    // =========================
                    // HEADER
                    // =========================
                    $('#d_doc_no_text').text(res.doc_no || '-');

                    $('#d_branch_code').val(res.branch_code || '');

                    // flow type
                    let flowText = {
                        destroy: 'ทำลายสินค้า',
                        discount: 'ลดราคา',
                        claim: 'เคลมสินค้า',
                        quality: 'ปรับปรุงคุณภาพสินค้า'
                    };

                    $('#d_flow_type')
                        .val(flowText[res.flow_type] || '-')
                        .attr('data-value', res.flow_type || '');

                    // =========================
                    // DISCOUNT
                    // =========================
                    if (res.flow_type === 'discount') {

                        $('#discount-box').show();

                        let total =
                            parseFloat(
                                res.total_amount || 0
                            );

                        let percent =
                            parseFloat(
                                res.manager_discount_percent || 0
                            );

                        $('#discount_percent')
                            .val(percent);

                        $('#d_total').text(
                            total.toLocaleString(
                                undefined, {
                                    minimumFractionDigits: 2
                                }
                            )
                        );

                        calculateDiscount(total);

                    } else {

                        $('#discount-box').hide();

                    }

                    // product type
                    let productTypeText = {
                        domestic: 'ในประเทศ',
                        international: 'นอกประเทศ'
                    };

                    $('#d_product_type').val(
                        productTypeText[res.product_type] || '-'
                    );

                    // issue type
                    let issueTypeText = {
                        defect: 'สินค้าด้อยคุณภาพจากการผลิต',
                        claimable: 'สินค้าเสียหายที่สามารถเคลมได้'
                    };

                    $('#d_issue_type').val(
                        issueTypeText[res.issue_type] || '-'
                    );

                    $('#d_date').val(
                        res.created_at ?
                        res.created_at.substring(0, 10) :
                        ''
                    );

                    // report type
                    let reportTypeText = {
                        branch: 'สาขาแจ้ง',
                        customer: 'ลูกค้าแจ้ง',
                        dc: 'DC / คลังสินค้า',
                        purchase_local: 'จัดซื้อในประเทศ',
                        purchase_inter: 'จัดซื้อต่างประเทศ'
                    };

                    $('#d_report_source').val(
                        reportTypeText[res.report_type] || '-'
                    );

                    // damage reason
                    $('#d_damage_reason').val(
                        res.damage_reason || ''
                    );

                    // =========================
                    // FILES
                    // =========================
                    let fileHtml = '';

                    if (res.files && res.files.length > 0) {

                        fileHtml = `
                    <div class="input-group">

                        <input type="text"
                               class="form-control"
                               value="${res.files.length} ไฟล์"
                               readonly>

                        <button type="button"
                                class="btn btn-primary btn-preview-file"
                                data-files='${JSON.stringify(res.files)}'>

                            ดูไฟล์

                        </button>

                    </div>
                `;

                    } else {

                        fileHtml = `
                    <span class="text-muted">
                        ไม่มีไฟล์แนบ
                    </span>
                `;
                    }

                    $('#d_files').html(fileHtml);

                    // =========================
                    // PRODUCT
                    // =========================
                    let productHtml = '';

                    if (res.items && res.items.length > 0) {

                        res.items.forEach(i => {

                            productHtml += `
                        <tr class="product-row">

                            <td>
                                <input class="form-control"
                                       value="${i.product_code || ''}"
                                       readonly>
                            </td>

                            <td>
                                <input class="form-control"
                                       value="${i.product_name || ''}"
                                       readonly>
                            </td>

                            <td>
                                <input class="form-control price"
                                       value="${i.price || 0}"
                                       readonly>
                            </td>

                            <td>
                                <input class="form-control qty"
                                       value="${i.qty || 0}"
                                       readonly>
                            </td>

                            <td>
                                <input class="form-control total"
                                       value="${i.total || 0}"
                                       readonly>
                            </td>

                        </tr>
                    `;
                        });
                    }

                    $('#detail-products').html(productHtml);

                    // =========================
                    // EMPLOYEE
                    // =========================
                    let empHtml = '';

                    if (res.employees && res.employees.length > 0) {

                        res.employees.forEach(e => {

                            empHtml += `
                        <tr class="employee-row">

                            <td>
                                <input class="form-control"
                                       value="${e.emp_code || ''}"
                                       readonly>
                            </td>

                            <td>
                                <input class="form-control"
                                       value="${e.emp_name || ''}"
                                       readonly>
                            </td>

                            <td>
                                <input class="form-control"
                                       value="${e.percent || 0}"
                                       readonly>
                            </td>

                            <td>
                                <input class="form-control"
                                       value="${e.amount || 0}"
                                       readonly>
                            </td>

                        </tr>
                    `;
                        });

                        $('#employee-box').show();

                    } else {

                        $('#employee-box').hide();
                    }

                    $('#detail-employees').html(empHtml);

                },

                error: function(xhr) {

                    // ถ้า abort ไม่ต้องแจ้ง
                    if (xhr.statusText === 'abort') {
                        return;
                    }

                    Swal.fire(
                        'ผิดพลาด',
                        'โหลดข้อมูลไม่สำเร็จ',
                        'error'
                    );
                }
            });

        }

        function calculateDiscount(total) {

            let percent =
                parseFloat(
                    $('#discount_percent').val()
                ) || 0;

            let discount =
                (total * percent) / 100;

            let net =
                total - discount;

            $('#discount_amount').val(
                discount.toLocaleString(
                    undefined, {
                        minimumFractionDigits: 2
                    }
                )
            );

            $('#net_amount').val(
                net.toLocaleString(
                    undefined, {
                        minimumFractionDigits: 2
                    }
                )
            );
        }

        $(document).on(
            'input',
            '#discount_percent',
            function() {

                let total =
                    parseFloat(
                        $('#d_total')
                        .text()
                        .replace(/,/g, '')
                    ) || 0;

                calculateDiscount(total);

            }
        );

        $(document).on('click', '.btn-preview-file', function() {

            let files = $(this).data('files');

            let html = '';

            files.forEach(function(f) {

                let file = `{{ asset('storage') }}/${f.file_path}`;

                let ext = f.file_path.split('.').pop().toLowerCase();

                let name = f.file_path.split('/').pop();

                // IMAGE
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {

                    html += `
            <div class="mb-4">

                <img src="${file}"
                     class="img-fluid rounded shadow-sm"
                     style="max-height:500px;object-fit:contain;">

                <div class="mt-2 fw-semibold text-secondary">
                    ${name}
                </div>

            </div>
            `;
                }

                // PDF
                else if (ext === 'pdf') {

                    html += `
            <div class="mb-4">

                <iframe src="${file}"
                        width="100%"
                        height="600px"
                        style="border:none;">
                </iframe>

                <div class="mt-2 fw-semibold text-secondary">
                    ${name}
                </div>

            </div>
            `;
                }

                // OTHER
                else {

                    html += `
            <div class="mb-3">

                <a href="${file}"
                   target="_blank"
                   class="btn btn-primary">

                    เปิดไฟล์ ${name}

                </a>

            </div>
            `;
                }

            });

            $('#previewContainer').html(html);

            $('#filePreviewModal').modal('show');

        });

        $(document).on('click', '.btn-manager-approve', function() {

            const recordId = $('#d_id').val();

            const flowType = $('#d_flow_type').attr('data-value') || '';

            const totalAmount = parseFloat(
                ($('#d_total').text() || '0').replace(/,/g, '')
            ) || 0;

            if (!flowType) {
                Swal.fire('Error', 'flow_type ไม่มีค่า', 'error');
                return;
            }

            // DESTROY
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
                            .then(() => {
                                window.location.href =
                                    "{{ route('damage-report') }}";
                            });

                    });

                });

                return;
            }

            // CLAIM
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

            // DISCOUNT
            // DISCOUNT
            if (flowType === 'discount') {

                let percent =
                    parseFloat(
                        $('#discount_percent').val()
                    ) || 0;

                if (percent < 0 || percent > 100) {

                    Swal.fire(
                        'ผิดพลาด',
                        'กรุณาระบุ % ส่วนลด 0-100',
                        'error'
                    );

                    return;
                }

                $.post(
                    '{{ url('damage-report/approve-action') }}', {
                        _token: '{{ csrf_token() }}',
                        id: recordId,
                        action: 'approved_manager',
                        manager_discount_percent: percent
                    },
                    function(res) {

                        if (!res.success) {

                            Swal.fire(
                                'ผิดพลาด',
                                res.error || 'เกิดข้อผิดพลาด',
                                'error'
                            );

                            return;
                        }

                        Swal.fire(
                            'สำเร็จ',
                            'อนุมัติแล้ว',
                            'success'
                        ).then(() => {
                            window.location.href =
                                "{{ route('damage-report') }}";
                        });

                    }
                );

                return;
            }

            Swal.fire('Error', 'flow_type ไม่ถูกต้อง: ' + flowType, 'error');

        });

        $(document).on('click', '.btn-reject', function() {

            let id = $('#d_id').val();

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
                        ).then(() => {

                            location.reload();

                        });

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

        function calculateTotal() {
            let total = 0;

            $('.product-row').each(function() {
                let price = parseFloat($(this).find('.price').val()) || 0;
                let qty = parseFloat($(this).find('.qty').val()) || 0;

                total += price * qty;
            });

            $('#d_total').text(total.toLocaleString());

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
            <td></td>
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

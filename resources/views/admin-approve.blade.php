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
            <h5>อนุมัติรายการสินค้าเสียหาย (ผู้บริหาร)</h5>
        </div>

        <!-- 🔷 DETAIL -->
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

                    <div class="card-header">
                        พนักงาน
                    </div>

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

        <!-- 🔷 ACTION -->
        <div class="card-body d-flex justify-content-end gap-2">

            <input type="hidden" id="first_report_id" value="{{ $reports->first()->id ?? '' }}">

            <button class="btn btn-success btn-approve">
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

            let id = $('#first_report_id').val();

            if (id) {

                $('#d_id').val(id);

                loadDetail(id);

            }

        });

        // ================= LOAD DETAIL =================
        function loadDetail(id) {

            $.get("{{ url('damage-report/detail') }}", {
                id: id
            }, function(res) {

                let reportSource = (res.report_type || '')
                    .toString()
                    .trim()
                    .toLowerCase();

                $('#d_doc_no_text').text(res.doc_no);

                $('#d_branch_code').val(res.branch_code);

                $('#d_flow_type').val(res.flow_type);

                $('#d_total').text(
                    parseFloat(res.total_amount || 0).toLocaleString()
                );

                $('#d_damage_reason').val(res.damage_reason);

                $('#d_report_source').val(reportSource);

                $('#d_product_type').val(res.product_type || '-');

                $('#d_issue_type').val(res.issue_type || '-');

                // ================= FILES =================
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
                    data-files='${encodeURIComponent(JSON.stringify(res.files))}'>

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

                $('#d_date').val(res.created_at?.substring(0, 10));

                $('.btn-approve')
                    .attr('data-id', res.id)
                    .attr('data-flow', res.flow_type)
                    .attr('data-percent', res.manager_discount_percent || 0);

                $('.btn-reject')
                    .attr('data-id', res.id);

                // ================= PRODUCT =================
                let productHtml = '';

                let total = 0;

                res.items.forEach(i => {

                    let rowTotal = parseFloat(i.total || 0);

                    total += rowTotal;

                    productHtml += `
                    <tr class="product-row">
                        <td>
                            <input class="form-control product_code"
                                value="${i.product_code}" readonly>
                        </td>

                        <td>
                            <input class="form-control product_name"
                                value="${i.product_name}" readonly>
                        </td>

                        <td>
                            <input class="form-control price"
                                value="${i.price}" readonly>
                        </td>

                        <td>
                            <input class="form-control qty"
                                value="${i.qty}" readonly>
                        </td>

                        <td>
                            <input class="form-control total"
                                value="${rowTotal.toFixed(2)}" readonly>
                        </td>
                    </tr>
                `;
                });

                $('#detail-products').html(productHtml);

                $('#d_total').text(total.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                }));

                // ================= EMPLOYEE =================
                let empHtml = '';

                if (res.employees?.length) {

                    res.employees.forEach(e => {

                        empHtml += `
                        <tr class="employee-row">
                            <td>
                                <input class="form-control"
                                    value="${e.emp_code}" readonly>
                            </td>

                            <td>
                                <input class="form-control"
                                    value="${e.emp_name}" readonly>
                            </td>

                            <td>
                                <input class="form-control emp_percent"
                                    value="${e.percent}" readonly>
                            </td>

                            <td>
                                <input class="form-control emp_amount"
                                    value="${e.amount}" readonly>
                            </td>
                        </tr>
                    `;
                    });

                    $('#employee-box').show();

                } else {

                    $('#employee-box').hide();
                }

                $('#detail-employees').html(empHtml);

            });

        }

        // ================= PREVIEW FILE =================
        $(document).on('click', '.btn-preview-file', function() {

            let files = $(this).attr('data-files');

            // 🔥 decode + parse
            files = JSON.parse(decodeURIComponent(files));

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

        // ================= APPROVE =================
        $(document).on('click', '.btn-approve', function() {

            let id = $(this).data('id');

            let flowType = ($(this).data('flow') || '')
                .toString()
                .trim()
                .toLowerCase();

            let total = parseFloat(
                $('#d_total').text().replace(/,/g, '')
            ) || 0;

            // ===== DESTROY =====
            if (flowType === 'destroy') {

                Swal.fire({
                    title: 'ยืนยันอนุมัติ',
                    text: 'ต้องการอนุมัติรายการนี้ใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true
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

                        Swal.fire('สำเร็จ', 'อนุมัติแล้ว', 'success')
                            .then(() => {
                                window.location.href = "{{ route('damage-report') }}";
                            });

                    });

                });

                return;
            }

            // ===== CLAIM =====
            if (flowType === 'claim') {

                Swal.fire({
                    title: 'ยืนยันส่งเคลม',
                    text: 'ต้องการส่งเคลมหรือไม่?',
                    icon: 'question',
                    showCancelButton: true
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
                            .then(() => {
                                window.location.href = "/";
                            });

                    });

                });

                return;
            }

            // ===== DISCOUNT =====
            let percentDefault = $(this).data('percent') || 0;

            Swal.fire({
                title: 'กำหนดส่วนลด',
                html: `
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label>% ส่วนลด</label>
                            <input type="number"
                                id="discount_percent"
                                class="form-control"
                                value="${percentDefault}">
                        </div>

                        <div class="col-md-4">
                            <label>ส่วนลด</label>
                            <input type="text"
                                id="discount_amount"
                                class="form-control"
                                disabled>
                        </div>

                        <div class="col-md-4">
                            <label>สุทธิ</label>
                            <input type="text"
                                id="net_amount"
                                class="form-control"
                                disabled>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'อนุมัติ',

                didOpen: () => {

                    const percentInput =
                        document.getElementById('discount_percent');

                    const discountInput =
                        document.getElementById('discount_amount');

                    const netInput =
                        document.getElementById('net_amount');

                    function calculate() {

                        let percent =
                            parseFloat(percentInput.value) || 0;

                        let discount =
                            (total * percent) / 100;

                        let net = total - discount;

                        discountInput.value =
                            discount.toLocaleString(undefined, {
                                minimumFractionDigits: 2
                            });

                        netInput.value =
                            net.toLocaleString(undefined, {
                                minimumFractionDigits: 2
                            });
                    }

                    percentInput.addEventListener(
                        'input',
                        calculate
                    );

                    calculate();
                },

                preConfirm: () => {

                    let percent =
                        $('#discount_percent').val();

                    if (!percent) {

                        Swal.showValidationMessage(
                            'กรุณากรอก % ส่วนลด'
                        );

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
                        .then(() => {
                            window.location.href = "{{ route('damage-report') }}";
                        });

                });

            });

        });

        // ================= REJECT =================
        $(document).on('click', '.btn-reject', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: 'ไม่อนุมัติ',
                input: 'textarea',
                inputPlaceholder: 'ระบุเหตุผล...',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',

                inputValidator: (value) => {

                    if (!value) {
                        return 'กรุณาระบุเหตุผล!';
                    }
                }

            }).then((result) => {

                if (!result.isConfirmed) return;

                $.post("{{ route('damage.admin.action') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: 'rejected',
                    remark: result.value
                }, function(res) {

                    if (!res.success) {

                        Swal.fire(
                            'ผิดพลาด',
                            res.error || 'เกิดข้อผิดพลาด',
                            'error'
                        );

                        return;
                    }

                    Swal.fire(
                        'เรียบร้อย',
                        'ไม่อนุมัติแล้ว',
                        'success'
                    ).then(() => {
                        window.location.href = "{{ route('damage-report') }}";
                    });

                });

            });

        });
    </script>
@endsection

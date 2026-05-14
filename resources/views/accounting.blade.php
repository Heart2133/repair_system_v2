@extends('layouts.master-layouts')

@section('title')
    Accounting
@endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

    <style>
        .sap-input {
            min-width: 160px;
        }

        /* 🔷 ตาราง readonly */
        .table-readonly {
            background-color: #f8f9fa;
        }

        /* 🔷 แถว */
        .table-readonly tbody tr {
            background-color: #f1f3f5;
        }

        /* 🔷 cell */
        .table-readonly td {
            vertical-align: middle;
        }

        /* 🔷 input style */
        .table-readonly input {
            background-color: #e9ecef !important;
            border: 1px solid #dee2e6;
            color: #495057;
            pointer-events: none;
            box-shadow: none;
        }

        /* 🔷 focus ไม่ต้องมี effect */
        .table-readonly input:focus {
            outline: none;
            box-shadow: none;
        }

        /* 🔷 header */
        .table-readonly thead th {
            background-color: #e9ecef;
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📊 บัญชี (บันทึก SAP)</h5>
        </div>

        <div class="card-body">

            <div class="card mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">รายละเอียดใบแจ้งสินค้า</h5>
                    <div>
                        <span class="text-muted">เลขที่เอกสาร:</span>
                        <span class="fw-bold" id="d_doc_no_text">-</span>
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

                                <!-- 🔵 ซ้าย -->
                                <div class="col-md-6">
                                    <div class="row">

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">วันที่เอกสาร</label>
                                            <input type="text" class="form-control" id="d_date" disabled>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">สาขา</label>
                                            <input type="text" class="form-control" id="d_branch_code" disabled>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">รูปแบบการดำเนินการ</label>
                                            <input type="text" id="d_flow_type" class="form-control" disabled>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">ประเภทผู้แจ้ง</label>
                                            <input type="text" id="d_report_source" class="form-control" disabled>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">ประเภทสินค้า</label>
                                            <input type="text" id="d_product_type" class="form-control" disabled>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">ประเภทปัญหา</label>
                                            <input type="text" id="d_issue_type" class="form-control" disabled>
                                        </div>

                                    </div>
                                </div>

                                <!-- 🟢 ขวา -->
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

                    <!-- 📦 สินค้า -->
                    <div class="card mb-3">
                        <div class="card-header">สินค้า</div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-readonly">
                                <thead>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>ราคา</th>
                                        <th>จำนวน</th>
                                        <th>รวม</th>
                                    </tr>
                                </thead>

                                <tbody id="detail-products"></tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">รวมมูลค่า</td>
                                        <td class="text-end fw-bold">
                                            <span id="d_total">0.00</span>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- 👨‍💼 พนักงาน -->
                    <div class="card mb-3" id="employee-box">
                        <div class="card-header">พนักงาน</div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-readonly">
                                <thead>
                                    <tr>
                                        <th>รหัสพนักงาน</th>
                                        <th>ชื่อพนักงาน</th>
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

            <div class="card-body">

                <div class="card-header">
                    document no.
                </div>

                <div class="row g-3">

                    @forelse($reports as $r)
                        <div class="col-12">

                            <div class="mb-3 p-3 border rounded row-click" data-id="{{ $r->id }}">

                                {{-- SAP DOC --}}
                                <label class="form-label">
                                    SAP Document No.
                                </label>

                                <input type="text" class="form-control sap_doc" style="max-width: 300px;"
                                    value="{{ $r->sap_doc ?? '' }}" {{ $r->sap_doc ? 'disabled' : '' }}>

                                {{-- SAP DATE --}}
                                <label class="form-label mt-2">
                                    SAP Date
                                </label>

                                <input type="date" class="form-control sap_date" style="max-width: 300px;"
                                    value="{{ $r->sap_date ?? '' }}" {{ $r->sap_date ? 'disabled' : '' }}>

                            </div>

                            {{-- BUTTON --}}
                            <div class="text-end mt-3">

                                <button class="btn btn-success btn-save px-4" data-id="{{ $r->id }}"
                                    {{ $r->status != 'waiting_accounting' ? 'disabled' : '' }}>

                                    💾 บันทึก

                                </button>

                            </div>

                        </div>

                    @empty

                        <div class="col-12 text-center">
                            ไม่มีข้อมูล
                        </div>
                    @endforelse

                </div>

            </div>

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
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>

        // ================= PREVIEW FILE =================
        $(document).on('click', '.btn-preview-file', function() {

            let raw = $(this).attr('data-files');

            if (!raw) {
                Swal.fire('ไม่พบไฟล์');
                return;
            }

            let files = [];

            try {

                files = JSON.parse(
                    decodeURIComponent(raw)
                );

            } catch (e) {

                console.error(e);

                Swal.fire('อ่านข้อมูลไฟล์ไม่สำเร็จ');

                return;
            }

            let html = '';

            files.forEach(function(f) {

                let file = `{{ asset('storage') }}/${f.file_path}`;

                let ext = f.file_path.split('.').pop().toLowerCase();

                let name = f.file_path.split('/').pop();

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
                } else if (ext === 'pdf') {

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
                } else {

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


        $(document).on('click', '.btn-save', function() {

            let btn = $(this);

            // 🔥 เปลี่ยนตรงนี้
            let row = btn.closest('.col-12');

            let id = btn.data('id');

            let sap_doc = row.find('.sap_doc').val();
            let sap_date = row.find('.sap_date').val();

            if (!sap_doc || !sap_date) {

                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอก SAP Doc และ SAP Date'
                });

                return;
            }

            Swal.fire({
                title: 'ยืนยันการบันทึก SAP?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'บันทึก',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {

                if (!result.isConfirmed) return;

                $.post("{{ route('accounting.save') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    sap_doc: sap_doc,
                    sap_date: sap_date
                }, function(res) {

                    if (!res.success) {
                        Swal.fire('ผิดพลาด', res.error, 'error');
                        return;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึก SAP สำเร็จ',
                        timer: 1200,
                        showConfirmButton: false
                    }).then(() => {

                        window.location.href = "{{ url('damage-report') }}";

                    });

                });

            });

        });

        $(document).ready(function() {

            let firstRow = $('.row-click').first();

            if (firstRow.length) {
                let id = firstRow.data('id');
                loadDetail(id);
            }

        });

        function loadDetail(id) {

            $('#d_id').val(id);

            $.get("{{ url('damage-report/detail') }}", {
                    id: id
                })
                .done(function(res) {

                    console.log(res);

                    $('#d_doc_no_text').text(res.doc_no || '-');
                    $('#d_branch_code').val(res.branch_code || '-');

                    $('#d_flow_type').val(res.flow_type || '-');
                    $('#d_report_source').val(res.report_type || '-');
                    $('#d_product_type').val(res.product_type || '-');
                    $('#d_issue_type').val(res.issue_type || '-');

                    $('#d_damage_reason').val(res.damage_reason || '-');

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

                    $('#d_total').text(
                        parseFloat(res.total_amount || 0).toLocaleString(undefined, {
                            minimumFractionDigits: 2
                        })
                    );

                    $('#d_date').val(res.created_at ? res.created_at.substring(0, 10) : '');

                    // ===== PRODUCT =====
                    let productHtml = '';

                    if (res.items && res.items.length > 0) {
                        res.items.forEach(function(i) {
                            productHtml += `
                        <tr>
                            <td><input class="form-control" value="${i.product_code}" readonly></td>
                            <td><input class="form-control" value="${i.product_name}" readonly></td>
                            <td><input class="form-control text-end" value="${i.price}" readonly></td>
                            <td><input class="form-control text-center" value="${i.qty}" readonly></td>
                            <td><input class="form-control text-end" value="${i.total}" readonly></td>
                        </tr>
                        `;
                        });
                    } else {
                        productHtml = `<tr><td colspan="5" class="text-center">ไม่มีสินค้า</td></tr>`;
                    }

                    $('#detail-products').html(productHtml);

                    // ===== EMPLOYEE =====
                    let empHtml = '';

                    if (res.employees && res.employees.length > 0 && res.flow_type !== 'quality') {

                        res.employees.forEach(function(e) {
                            empHtml += `
                            <tr>
                                <td><input class="form-control" value="${e.emp_code}" readonly></td>
                                <td><input class="form-control" value="${e.emp_name}" readonly></td>
                                <td><input class="form-control text-center" value="${e.percent}" readonly></td>
                                <td><input class="form-control text-end" value="${e.amount}" readonly></td>
                            </tr>
                            `;
                        });

                        $('#employee-box').show();

                    } else {
                        $('#employee-box').hide();
                    }

                    $('#detail-employees').html(empHtml);

                })
                .fail(function(err) {
                    console.error(err);
                    alert('โหลดข้อมูลไม่สำเร็จ');
                });

        }
    </script>
@endsection

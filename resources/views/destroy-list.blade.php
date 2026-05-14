@extends('layouts.master-layouts')

@section('css')
    <style>
        .table-readonly {
            background-color: #f8f9fa;
        }

        .table-readonly tbody tr {
            background-color: #f1f3f5;
        }

        .table-readonly td {
            vertical-align: middle;
        }

        .table-readonly input {
            background-color: #e9ecef !important;
            border: 1px solid #dee2e6;
            color: #495057;
            pointer-events: none;
            box-shadow: none;
        }

        .table-readonly thead th {
            background-color: #e9ecef;
        }
    </style>
@endsection

@section('content')
    <div class="card">

        <div class="card-header">
            <h5>รายการรอทำลายสินค้า</h5>
        </div>

        {{-- DETAIL --}}
        <div class="card mb-4">

            <div class="card-header d-flex justify-content-between">

                <h5>รายละเอียดใบแจ้งสินค้า</h5>

                <div>
                    <span class="text-muted">เลขที่เอกสาร:</span>
                    <span class="fw-bold" id="d_doc_no_text">-</span>
                </div>

            </div>

            <div class="card-body">

                <input type="hidden" id="report_id" value="{{ $report->id ?? '' }}">

                <div class="row">

                    {{-- LEFT --}}
                    <div class="col-md-6">

                        <div class="mb-3">
                            <label>วันที่เอกสาร</label>
                            <input type="text" id="d_date" class="form-control" disabled>
                        </div>

                        <div class="mb-3">
                            <label>สาขา</label>
                            <input type="text" id="d_branch_code" class="form-control" disabled>
                        </div>

                        <div class="mb-3">
                            <label>Flow Type</label>
                            <input type="text" id="d_flow_type" class="form-control" disabled>
                        </div>

                    </div>

                    <div class="col-md-6">

                        {{-- แนบรูป / เอกสาร --}}
                        <div class="mb-4">

                            <label class="mb-2 d-block">
                                แนบรูป / เอกสาร
                            </label>

                            {{-- FILE เดิม --}}
                            <div id="old-files" class="mb-3">

                                <span class="text-muted">
                                    ไม่มีไฟล์แนบ
                                </span>

                            </div>

                        </div>

                        {{-- สาเหตุ --}}
                        <div class="mb-4">

                            <label class="mb-2 d-block">
                                สาเหตุความเสียหาย
                            </label>

                            <textarea id="d_damage_reason" class="form-control" rows="6" disabled></textarea>

                        </div>

                    </div>

                </div>

                {{-- PRODUCT --}}
                <div class="card mb-3">

                    <div class="card-header">
                        สินค้า
                    </div>

                    <div class="table-responsive">

                        <table class="table table-bordered table-readonly">

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
                                    <td colspan="4" class="text-end fw-bold">
                                        รวม
                                    </td>

                                    <td class="text-end fw-bold">
                                        <span id="d_total">0.00</span>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>

                    </div>

                </div>

                {{-- EMPLOYEE --}}
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
                                    <th>จำนวนเงิน</th>
                                </tr>
                            </thead>

                            <tbody id="detail-employees"></tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================= SAP ========================= --}}
        <div class="card mb-4">

            <div class="card-header">
                SAP Document
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label">
                        SAP Document No.
                    </label>

                    <input type="text" id="sap_doc" class="form-control" style="max-width: 300px;">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        SAP Date
                    </label>

                    <input type="date" id="sap_date" class="form-control" style="max-width: 300px;">

                </div>

            </div>

        </div>

        {{-- ========================= DESTROY FORM ========================= --}}
        <div class="card">

            <div class="card-header">
                ฟอร์มทำลายสินค้า
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <label>สถานที่ทำลาย</label>
                    <input type="text" id="location" class="form-control">
                </div>

                <div class="mb-3">
                    <label>วันที่ทำลาย</label>
                    <input type="date" id="destroy_date" class="form-control">
                </div>

                <div class="mb-3">
                    <label>หมายเหตุ</label>
                    <textarea id="remark" class="form-control"></textarea>
                </div>

                <div class="input-group">

                    <input type="file" id="images" class="form-control" multiple>

                    <button type="button" class="btn btn-primary btn-preview-local-file" disabled>

                        ดูไฟล์

                    </button>

                </div>

                <div class="mt-2 text-muted small" id="file-count">
                    ไม่มีไฟล์แนบ
                </div>

                <div class="d-flex gap-2 mt-3">

                    <button class="btn btn-success" id="btn-save">
                        บันทึกการทำลาย
                    </button>

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

            $(document).ready(function() {

                let id = $('#report_id').val();

                if (id) {
                    loadDetail(id);
                }

            });

            function loadDetail(id) {

                $('#d_id').val(id);

                $('#report_id').val(id);

                $('#btn-print').attr(
                    'href',
                    "{{ url('destroy-print') }}/" + id
                );

                $.get("{{ url('damage-report/detail') }}", {
                        id: id
                    })

                    .done(function(res) {

                        $('#d_doc_no_text').text(res.doc_no || '-');

                        $('#d_branch_code').val(res.branch_code || '-');

                        $('#d_flow_type').val(res.flow_type || '-');

                        $('#d_damage_reason').val(res.damage_reason || '-');

                        // SAP
                        $('#sap_doc').val(res.sap_doc || '');
                        $('#sap_date').val(res.sap_date || '');

                        // ================= OLD FILES =================
                        let oldFileHtml = '';

                        if (res.files && res.files.length > 0) {

                            oldFileHtml = `
        <div class="input-group">

            <input type="text"
                class="form-control"
                value="${res.files.length} ไฟล์"
                readonly>

            <button type="button"
                class="btn btn-secondary btn-preview-file"
                data-files='${encodeURIComponent(JSON.stringify(res.files))}'>

                ดูไฟล์

            </button>

        </div>
    `;

                        } else {

                            oldFileHtml = `
        <span class="text-muted">
            ไม่มีไฟล์แนบ
        </span>
    `;
                        }

                        $('#old-files').html(oldFileHtml);

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
                            parseFloat(res.total_amount || 0)
                            .toLocaleString(undefined, {
                                minimumFractionDigits: 2
                            })
                        );

                        $('#d_date').val(
                            res.created_at ?
                            res.created_at.substring(0, 10) :
                            ''
                        );

                        // PRODUCT
                        let productHtml = '';

                        if (res.items && res.items.length > 0) {

                            res.items.forEach(function(i) {

                                productHtml += `
                <tr>
                    <td>
                        <input class="form-control"
                               value="${i.product_code}"
                               readonly>
                    </td>

                    <td>
                        <input class="form-control"
                               value="${i.product_name}"
                               readonly>
                    </td>

                    <td>
                        <input class="form-control text-end"
                               value="${i.price}"
                               readonly>
                    </td>

                    <td>
                        <input class="form-control text-center"
                               value="${i.qty}"
                               readonly>
                    </td>

                    <td>
                        <input class="form-control text-end"
                               value="${i.total}"
                               readonly>
                    </td>
                </tr>
                `;
                            });

                        }

                        $('#detail-products').html(productHtml);

                        // EMPLOYEE
                        let empHtml = '';

                        if (res.employees && res.employees.length > 0) {

                            res.employees.forEach(function(e) {

                                empHtml += `
                <tr>

                    <td>
                        <input class="form-control"
                               value="${e.emp_code}"
                               readonly>
                    </td>

                    <td>
                        <input class="form-control"
                               value="${e.emp_name}"
                               readonly>
                    </td>

                    <td>
                        <input class="form-control text-center"
                               value="${e.percent}"
                               readonly>
                    </td>

                    <td>
                        <input class="form-control text-end"
                               value="${e.amount}"
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

                    });

            }

            // =========================
            // SAVE DESTROY
            // =========================
            $('#btn-save').click(function() {

                if (!$('#location').val()) {

                    Swal.fire('กรุณาระบุสถานที่');

                    return;
                }

                if (!$('#destroy_date').val()) {

                    Swal.fire('กรุณาระบุวันที่');

                    return;
                }

                let formData = new FormData();

                formData.append(
                    'report_id',
                    $('#report_id').val()
                );

                formData.append(
                    'location',
                    $('#location').val()
                );

                formData.append(
                    'destroy_date',
                    $('#destroy_date').val()
                );

                formData.append(
                    'remark',
                    $('#remark').val()
                );

                let files = $('#images')[0].files;

                for (let i = 0; i < files.length; i++) {

                    formData.append(
                        'images[]',
                        files[i]
                    );

                }

                formData.append(
                    '_token',
                    '{{ csrf_token() }}'
                );

                $.ajax({

                    url: "{{ route('destroy.store') }}",

                    type: "POST",

                    data: formData,

                    processData: false,

                    contentType: false,

                    success: function(res) {

                        if (!res.success) {

                            Swal.fire(
                                'ผิดพลาด',
                                res.error,
                                'error'
                            );

                            return;
                        }

                        // reload detail destroy files
                        loadDestroyFiles(res.destroy_id);


                        Swal.fire(
                                'สำเร็จ',
                                'บันทึกการทำลายแล้ว',
                                'success'
                            )

                            .then(() => {

                                window.location.href =
                                    "{{ route('damage-report') }}";
                            });

                    }

                });

            });

            function loadDestroyFiles(id) {

                $.get("{{ url('destroy/detail') }}", {
                        id: id
                    })
                    .done(function(res) {

                        let html = '';

                        if (res.files && res.files.length > 0) {

                            html = `
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
                            html = `<span class="text-muted">ไม่มีไฟล์แนบ</span>`;
                        }

                        $('#destroy_files').html(html);

                    });
            }

            $(document).on('click', '.btn-preview-destroy', function() {

                let raw = $(this).attr('data-files');

                let files = JSON.parse(decodeURIComponent(raw));

                let html = '';

                files.forEach(function(f) {

                    let file = `{{ asset('storage') }}/${f.file_path}`;
                    let ext = f.file_path.split('.').pop().toLowerCase();

                    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {

                        html += `
                <img src="${file}" class="img-fluid mb-3">
            `;

                    } else if (ext === 'pdf') {

                        html += `
                <iframe src="${file}" width="100%" height="600px"></iframe>
            `;

                    } else {

                        html += `
                <a href="${file}" target="_blank">เปิดไฟล์</a>
            `;
                    }

                });

                $('#previewContainer').html(html);
                $('#filePreviewModal').modal('show');
            });


            // =========================
            // PREVIEW LOCAL FILE
            // =========================
            $(document).on('click', '.btn-preview-local-file', function() {

                let files = window.previewFiles || [];

                if (!files.length) {

                    Swal.fire('ไม่พบไฟล์');

                    return;
                }

                let html = '';

                files.forEach(function(f) {

                    let file = f.object_url;

                    let ext = f.file_path
                        .split('.')
                        .pop()
                        .toLowerCase();

                    let name = f.file_path;

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

            // =========================
            // PREVIEW BEFORE SAVE
            // =========================
            $('#images').on('change', function() {

                let files = this.files;

                if (!files.length) {

                    $('#file-count').text('ไม่มีไฟล์แนบ');

                    $('.btn-preview-local-file')
                        .prop('disabled', true);

                    return;
                }

                let previewFiles = [];

                for (let i = 0; i < files.length; i++) {

                    previewFiles.push({
                        file_path: files[i].name,
                        object_url: URL.createObjectURL(files[i])
                    });

                }

                // เก็บ global
                window.previewFiles = previewFiles;

                $('#file-count').text(
                    files.length + ' ไฟล์'
                );

                $('.btn-preview-local-file')
                    .prop('disabled', false);

            });
        </script>
    @endsection

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .radio-box {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 12px 20px;
            cursor: pointer;
            transition: 0.2s;
            display: inline-block;
        }

        .radio-box:hover {
            border-color: #0d6efd;
        }

        input[type="radio"]:checked+.radio-box {
            border-color: #0d6efd;
            background-color: #e7f1ff;
            font-weight: 500;
        }

        .radio-hidden {
            display: none;
        }

        #product-table input {
            width: 100%;
        }

        #employee-table input {
            width: 100%;
        }

        table th,
        table td {
            vertical-align: middle;
        }

        .table-fixed {
            table-layout: fixed;
            width: 100%;
        }

        /* 🔥 บังคับทุก cell ไม่ให้ดัน layout */
        .table-fixed th,
        .table-fixed td {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /* 🔥 บังคับ input ไม่ให้ทะลุ */
        .table-fixed input {
            width: 100%;
            min-width: 0;
        }

        /* 🔥 กัน Bootstrap แอบมี margin */
        .table {
            margin-bottom: 0;
        }

        /* 🔥 ทำให้ 2 card ชิดเหมือน table เดียว */
        .card.mb-3+.card.mb-3 {
            margin-top: -1px;
        }

        #modalDetail input[readonly],
        #modalDetail textarea[readonly] {
            background-color: #e9ecef !important;
            pointer-events: none;
            opacity: 1;
        }

        #modalDetail input:disabled,
        #modalDetail select:disabled {
            background-color: #e9ecef !important;
            pointer-events: none;
            opacity: 1;
            color: #6c757d;
        }

        .modal-footer {
            position: sticky;
            bottom: 0;
            background: #fff;
            z-index: 10;
        }
    </style>
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
                <form id="damageForm">

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

                                                    <!-- วันที่เอกสาร -->
                                                    <div class="col-md-6 mb-4">
                                                        <label class="mb-2 d-block">วันที่เอกสาร</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ now()->format('Y-m-d') }}" disabled>
                                                    </div>

                                                    <!-- สาขา -->
                                                    <div class="col-md-6 mb-4">

                                                        <label>สาขา</label>

                                                        {{-- แสดงชื่อสาขา --}}
                                                        <input type="text" class="form-control"
                                                            value="{{ Auth::user()->hwh_branch }}" disabled>

                                                        {{-- ส่ง code จริงเข้า DB --}}
                                                        <input type="hidden" name="branch_code" id="branch_code"
                                                            value="{{ optional(\App\Models\Branch::where('branch_desc', Auth::user()->hwh_branch)->first())->branch_code }}">
                                                    </div>

                                                </div>

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
                                                            <option value="quality">ปรับปรุงคุณภาพสินค้า</option>
                                                        </select>
                                                    </div>

                                                    <!-- 🔹 ประเภทผู้แจ้ง -->
                                                    <div class="col-md-6 mb-4">
                                                        <label class="mb-2 d-block">
                                                            ประเภทผู้แจ้ง <span class="text-danger">*</span>
                                                        </label>

                                                        <select id="report_source" name="report_source" class="form-select"
                                                            disabled>

                                                            <option value="">
                                                                กรุณาเลือกรูปแบบการดำเนินการก่อน
                                                            </option>

                                                            <option value="branch" data-group="all">
                                                                สาขาแจ้ง
                                                            </option>

                                                            <option value="customer" data-group="basic">
                                                                ลูกค้าแจ้ง
                                                            </option>

                                                            <option value="dc" data-group="claim">
                                                                DC แจ้ง
                                                            </option>

                                                            <option value="purchase_local" data-group="claim">
                                                                จัดซื้อในประเทศ
                                                            </option>

                                                            <option value="purchase_inter" data-group="claim">
                                                                จัดซื้อต่างประเทศ
                                                            </option>

                                                        </select>
                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <!-- 🔹 ประเภทสินค้า -->
                                                    <div class="col-md-6 mb-4">
                                                        <label class="mb-2 d-block">
                                                            ประเภทสินค้า <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="d-flex flex-column gap-2">
                                                            <div class="form-check">
                                                                <input type="radio" class="form-check-input"
                                                                    name="product_type" value="domestic">
                                                                <label class="form-check-label">ในประเทศ</label>
                                                            </div>

                                                            <div class="form-check">
                                                                <input type="radio" class="form-check-input"
                                                                    name="product_type" value="international">
                                                                <label class="form-check-label">นอกประเทศ</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- 🔹 ประเภทปัญหา -->
                                                    <div class="col-md-6 mb-4">
                                                        <label class="mb-2 d-block">
                                                            ประเภทปัญหา <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="d-flex flex-column gap-2">
                                                            <div class="form-check">
                                                                <input type="radio" name="issue_type" value="defect"
                                                                    class="form-check-input">
                                                                <label
                                                                    class="form-check-label">สินค้าด้อยคุณภาพจากการผลิต</label>
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

                                            </div>



                                            <!-- 🟢 ขวา -->
                                            <div class="col-md-6">

                                                <div class="mb-4" id="purchase-section" style="display:none;">
                                                    <label class="mb-2 d-block">ผู้ดูแลจัดซื้อ</label>
                                                    <input type="text" class="form-control" id="purchase_name">
                                                </div>

                                                <div class="mb-4 quality-section" style="display:none;">
                                                    <label class="mb-2 d-block">วันที่ลูกค้าแจ้ง</label>
                                                    <input type="date" class="form-control" id="customer_date">
                                                </div>

                                                <div class="mb-4">

                                                    <label class="mb-2 d-block">
                                                        แนบรูป / เอกสาร
                                                    </label>

                                                    <!-- 🔥 preview ไฟล์เก่า -->
                                                    {{-- <div id="d_files" class="mb-3">

                                                        <span class="text-muted">
                                                            ไม่มีไฟล์แนบ
                                                        </span>

                                                    </div> --}}

                                                    <!-- 🔥 upload file ใหม่ -->
                                                    <div class="input-group">

                                                        <input type="file" id="attachments"
                                                            class="form-control attachment-input" multiple>

                                                        <button type="button"
                                                            class="btn btn-outline-primary btn-open-preview" disabled>

                                                            ดูไฟล์

                                                        </button>

                                                    </div>

                                                </div>

                                                <div class="mb-4">
                                                    <label class="mb-2 d-block">สาเหตุความเสียหาย <span
                                                            class="text-danger">*</span></label>
                                                    <textarea id="damage_reason" class="form-control" rows="6"></textarea>
                                                </div>

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
                                        <table class="table table-bordered align-middle table-fixed">
                                            <colgroup>
                                                <col style="width:15%">
                                                <col style="width:25%">
                                                <col style="width:15%">
                                                <col style="width:15%">
                                                <col style="width:20%">
                                                <col style="width:10%">
                                            </colgroup>

                                            <thead>
                                                <tr>
                                                    <th>รหัสสินค้า</th>
                                                    <th>ชื่อสินค้า</th>
                                                    <th>ราคาต่อหน่วย</th>
                                                    <th>จำนวน</th>
                                                    <th>รวม</th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product-wrapper">

                                                <!-- ✅ row -->
                                                <tr class="product-row">
                                                    <td><input type="text" class="form-control product_code"
                                                            placeholder="กรุณากรอกรหัสสินค้า"></td>
                                                    <td><input type="text" class="form-control product_name" disabled>
                                                    </td>
                                                    <td><input type="text" class="form-control price" disabled></td>
                                                    <td><input type="number" class="form-control qty"
                                                            placeholder="กรุณากรอกจำนวน"></td>
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

                                    <div class="p-1">
                                        <button type="button" id="add-product" class="btn btn-success btn-sm">
                                            + เพิ่มสินค้า
                                        </button>
                                    </div>
                                </div>

                                <!-- 👨‍💼 ผู้รับผิดชอบ -->
                                <div class="card mb-3 employee-card">
                                    <div class="card-header">ผู้รับผิดชอบ</div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle table-fixed">
                                            <colgroup>
                                                <col style="width:15%">
                                                <col style="width:25%"> <!-- 🔥 ตรงกับชื่อสินค้า -->
                                                <col style="width:15%">
                                                <col style="width:15%">
                                                <col style="width:20%">
                                                <col style="width:10%">
                                            </colgroup>

                                            <thead>
                                                <tr>
                                                    <th>รหัสพนักงาน</th>
                                                    <th>ชื่อพนักงาน</th>
                                                    <th>% ความรับผิดชอบ</th>
                                                    <th>มูลค่า</th>
                                                    <th></th>
                                                    <th>จัดการ</th>
                                                </tr>
                                            </thead>

                                            <tbody id="employee-wrapper">

                                                <!-- ✅ row -->
                                                <tr class="employee-row">
                                                    <td><input type="text" class="form-control emp_code"
                                                            placeholder="กรุณากรอกรหัสพนักงาน"></td>
                                                    <td><input type="text" class="form-control emp_name"
                                                            placeholder="กรุณากรอกชื่อพนักงาน"></td>
                                                    <td><input type="number" class="form-control emp_percent"
                                                            placeholder="กรุณากรอก% ความรับผิดชอบ"></td>
                                                    <td><input type="text" class="form-control emp_amount" disabled>
                                                    </td>
                                                    <td></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove">
                                                            ลบ
                                                        </button>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="p-1">
                                        <button type="button" class="btn btn-success btn-sm" id="add-employee">
                                            + เพิ่มพนักงาน
                                        </button>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-primary btn-update">
                                บันทึก
                            </button>

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                ปิด
                            </button>
                        </div>

                    </div>
                </form>

            </div>

        </div>

    </div>
    {{-- Model Edit --}}
    <div class="modal fade" id="editModal" tabindex="-1">

        <div class="modal-dialog" style="max-width: 95%;">

            <div class="modal-content">

                <form id="editDamageForm">

                    <input type="hidden" id="edit_id">

                    <!-- HEADER -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            แก้ไขใบแจ้งสินค้าทำลาย
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body">

                        <div class="row">

                            <!-- 🔵 ข้อมูลเอกสาร -->
                            <div class="col-12">

                                <div class="card mb-4">

                                    <div class="card-header">
                                        ข้อมูลเอกสาร
                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            <!-- LEFT -->
                                            <div class="col-md-6">

                                                <div class="row">

                                                    <!-- วันที่เอกสาร -->
                                                    <div class="col-md-6 mb-4">

                                                        <label class="mb-2 d-block">
                                                            วันที่เอกสาร
                                                        </label>

                                                        <input type="text" class="form-control" id="edit_doc_date"
                                                            value="{{ now()->format('Y-m-d') }}" disabled>

                                                    </div>

                                                    <!-- สาขา -->
                                                    <div class="col-md-6 mb-4">

                                                        <label class="mb-2 d-block">
                                                            สาขา
                                                        </label>

                                                        <select class="form-select" id="edit_branch_code">

                                                            <option value="">
                                                                -- เลือก --
                                                            </option>

                                                            @foreach (getBranchAll() as $item)
                                                                <option value="{{ $item->branch_code }}">
                                                                    {{ $item->branch_code }}
                                                                    -
                                                                    {{ $item->branch_desc }}
                                                                </option>
                                                            @endforeach

                                                        </select>

                                                    </div>

                                                    <!-- flow -->
                                                    <div class="col-md-6 mb-4">

                                                        <label class="mb-2 d-block">
                                                            รูปแบบการดำเนินการ
                                                        </label>

                                                        <select id="edit_flow_type" class="form-select">

                                                            <option value="">
                                                                -- เลือก --
                                                            </option>

                                                            <option value="destroy">
                                                                ทำลายสินค้า
                                                            </option>

                                                            <option value="discount">
                                                                ลดราคา
                                                            </option>

                                                            <option value="claim">
                                                                เคลมสินค้า
                                                            </option>

                                                            <option value="quality">
                                                                ปรับปรุงคุณภาพสินค้า
                                                            </option>

                                                        </select>

                                                    </div>

                                                    <!-- report source -->
                                                    <div class="col-md-6 mb-4">

                                                        <label class="mb-2 d-block">
                                                            ประเภทผู้แจ้ง
                                                        </label>

                                                        <select id="edit_report_source" class="form-select">

                                                            <option value="branch">
                                                                สาขาแจ้ง
                                                            </option>

                                                            <option value="customer">
                                                                ลูกค้าแจ้ง
                                                            </option>

                                                            <option value="dc">
                                                                DC แจ้ง
                                                            </option>

                                                            <option value="purchase_local">
                                                                จัดซื้อในประเทศ
                                                            </option>

                                                            <option value="purchase_inter">
                                                                จัดซื้อต่างประเทศ
                                                            </option>

                                                        </select>

                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <!-- product type -->
                                                    <div class="col-md-6 mb-4">

                                                        <label class="mb-2 d-block">
                                                            ประเภทสินค้า
                                                        </label>

                                                        <div class="d-flex flex-column gap-2">

                                                            <div class="form-check">

                                                                <input type="radio" class="form-check-input"
                                                                    name="edit_product_type" value="domestic">

                                                                <label class="form-check-label">
                                                                    ในประเทศ
                                                                </label>

                                                            </div>

                                                            <div class="form-check">

                                                                <input type="radio" class="form-check-input"
                                                                    name="edit_product_type" value="international">

                                                                <label class="form-check-label">
                                                                    นอกประเทศ
                                                                </label>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <!-- issue type -->
                                                    <div class="col-md-6 mb-4">

                                                        <label class="mb-2 d-block">
                                                            ประเภทปัญหา
                                                        </label>

                                                        <div class="d-flex flex-column gap-2">

                                                            <div class="form-check">

                                                                <input type="radio" class="form-check-input"
                                                                    name="edit_issue_type" value="defect">

                                                                <label class="form-check-label">
                                                                    สินค้าด้อยคุณภาพจากการผลิต
                                                                </label>

                                                            </div>

                                                            <div class="form-check">

                                                                <input type="radio" class="form-check-input"
                                                                    name="edit_issue_type" value="claimable">

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

                                                <div class="mb-4" id="edit_purchase_section" style="display:none;">

                                                    <label class="mb-2 d-block">
                                                        ผู้ดูแลจัดซื้อ
                                                    </label>

                                                    <input type="text" class="form-control" id="edit_purchase_name">

                                                </div>

                                                <!-- files -->
                                                <div class="mb-4">

                                                    <label class="mb-2 d-block">
                                                        แนบรูป / เอกสาร
                                                    </label>

                                                    <div id="edit_files" class="mb-2">

                                                        <span class="text-muted">
                                                            ไม่มีไฟล์แนบ
                                                        </span>

                                                    </div>

                                                    <div class="input-group">

                                                        <input type="file" id="edit_attachments" class="form-control"
                                                            multiple>

                                                        <button type="button" class="btn btn-outline-primary">

                                                            ดูไฟล์

                                                        </button>

                                                    </div>

                                                </div>

                                                <!-- reason -->
                                                <div class="mb-4">

                                                    <label class="mb-2 d-block">
                                                        สาเหตุความเสียหาย
                                                    </label>

                                                    <textarea id="edit_damage_reason" class="form-control" rows="6"></textarea>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- PRODUCT -->
                        <div class="card mb-3">

                            <div class="card-header">
                                ข้อมูลสินค้า
                            </div>

                            <div class="table-responsive">

                                <table class="table table-bordered align-middle table-fixed">

                                    <colgroup>
                                        <col style="width:15%">
                                        <col style="width:25%">
                                        <col style="width:15%">
                                        <col style="width:15%">
                                        <col style="width:20%">
                                        <col style="width:10%">
                                    </colgroup>

                                    <thead>

                                        <tr>
                                            <th>รหัสสินค้า</th>
                                            <th>ชื่อสินค้า</th>
                                            <th>ราคาต่อหน่วย</th>
                                            <th>จำนวน</th>
                                            <th>รวม</th>
                                            <th>จัดการ</th>
                                        </tr>

                                    </thead>

                                    <tbody id="edit_product_wrapper">

                                    </tbody>

                                </table>

                            </div>

                            <div class="p-1">

                                <button type="button" class="btn btn-success btn-sm" id="edit_add_product">

                                    + เพิ่มสินค้า

                                </button>

                            </div>

                        </div>

                        <!-- EMPLOYEE -->
                        <div class="card mb-3 employee-card">

                            <div class="card-header">
                                ผู้รับผิดชอบ
                            </div>

                            <div class="table-responsive">

                                <table class="table table-bordered align-middle table-fixed">

                                    <colgroup>
                                        <col style="width:15%">
                                        <col style="width:25%">
                                        <col style="width:15%">
                                        <col style="width:15%">
                                        <col style="width:20%">
                                        <col style="width:10%">
                                    </colgroup>

                                    <thead>

                                        <tr>
                                            <th>รหัสพนักงาน</th>
                                            <th>ชื่อพนักงาน</th>
                                            <th>% ความรับผิดชอบ</th>
                                            <th>มูลค่า</th>
                                            <th></th>
                                            <th>จัดการ</th>
                                        </tr>

                                    </thead>

                                    <tbody id="edit_employee_wrapper">

                                    </tbody>

                                </table>

                            </div>

                            <div class="p-1">

                                <button type="button" class="btn btn-success btn-sm" id="edit_add_employee">

                                    + เพิ่มพนักงาน

                                </button>

                            </div>

                        </div>

                        <!-- FOOTER -->
                        <div class="text-end mt-4">

                            <button type="button" class="btn btn-primary btn-update-edit">

                                บันทึก

                            </button>

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                ปิด

                            </button>

                        </div>

                    </div>

                </form>

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
                            <th>รูปแบบการดำเนินการ</th>
                            <th>สาขา</th>
                            <th>ประเภท</th>
                            <th>มูลค่า</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($reports as $r)
                            <tr>
                                <td>{{ $r->doc_no }}</td>
                                <td class="text-center">
                                    @php
                                        $flowMap = [
                                            'destroy' => ['text' => 'ทำลายสินค้า', 'class' => 'bg-danger'],
                                            'discount' => ['text' => 'ลดราคา', 'class' => 'bg-warning'],
                                            'claim' => ['text' => 'เคลมสินค้า', 'class' => 'bg-primary'],
                                            'quality' => ['text' => 'ปรับปรุงคุณภาพสินค้า', 'class' => 'bg-success'],
                                        ];
                                    @endphp

                                    @if (isset($flowMap[$r->flow_type]))
                                        <span class="badge {{ $flowMap[$r->flow_type]['class'] }}">
                                            {{ $flowMap[$r->flow_type]['text'] }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>{{ optional($r->branch)->branch_desc ?: '-' }}</td>
                                <td>{{ $r->report_type }}</td>
                                <td>{{ number_format($r->total_amount, 2) }}</td>
                                <td class="text-center">

                                    @if ($r->status == 'pending')
                                        <span class="badge bg-warning">รออนุมัติ</span>
                                    @elseif($r->status == 'approved_manager')
                                        <span class="badge bg-success">ผู้จัดการอนุมัติแล้ว</span>
                                    @elseif($r->status == 'waiting_branch_sap')
                                        <span class="badge bg-info">รอ SAP</span>
                                    @elseif($r->status == 'sap_completed')
                                        <span class="badge bg-primary">SAP เรียบร้อย</span>
                                    @elseif($r->status == 'accounting_done')
                                        <span class="badge bg-dark">บัญชีแล้ว</span>
                                    @elseif($r->status == 'waiting_branch_print')
                                        <span class="badge bg-secondary">รอพิมพ์</span>
                                    @elseif($r->status == 'waiting_accounting')
                                        <span class="badge bg-warning">รอบัญชี</span>
                                    @elseif($r->status == 'hr_done')
                                        <span class="badge bg-primary">รอทำลาย</span>
                                    @elseif($r->status == 'destroy_completed')
                                        <span class="badge bg-danger">ทำลายแล้ว</span>
                                    @elseif($r->status == 'completed')
                                        <span class="badge bg-success">เสร็จสิ้น</span>
                                    @elseif($r->status == 'rejected')
                                        <span class="badge bg-danger">ไม่อนุมัติ</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $r->status }}</span>
                                    @endif

                                </td>

                                @php
                                    $icons = [
                                        'pending' => 'bi-clock',
                                        'approved_manager' => 'bi-check-circle text-success',
                                        'waiting_branch_sap' => 'bi-hourglass-split text-warning',
                                        'sap_completed' => 'bi-database-check text-primary',
                                        'accounting_done' => 'bi-calculator text-info',
                                        'waiting_branch_print' => 'bi-printer text-secondary',
                                        'waiting_accounting' => 'bi-hourglass text-warning',
                                        'hr_done' => 'bi-person-check text-success',
                                        'destroy_completed' => 'bi-trash text-danger',
                                        'completed' => 'bi-check2-all text-success',
                                    ];

                                    $statusText = [
                                        'pending' => 'รออนุมัติ',
                                        'approved_manager' => 'ผู้จัดการอนุมัติแล้ว',
                                        'waiting_branch_sap' => 'รอ SAP',
                                        'sap_completed' => 'ทำ SAP แล้ว',
                                        'accounting_done' => 'บัญชีแล้ว',
                                        'waiting_branch_print' => 'รอพิมพ์',
                                        'waiting_accounting' => 'รอบัญชี',
                                        'hr_done' => 'รอทำลาย',
                                        'destroy_completed' => 'ทำลายแล้ว',
                                        'completed' => 'เสร็จสิ้น',
                                    ];
                                @endphp

                                <td class="text-center">

                                    {{-- ถ้า flow discount และเสร็จแล้ว -> เหลือ View อย่างเดียว --}}
                                    @if ($r->flow_type == 'discount' && $r->status == 'completed')
                                        <button class="btn btn-sm btn-outline-primary btn-detail"
                                            data-id="{{ $r->id }}" title="ดูรายละเอียด">

                                            <i class="bi bi-eye"></i>
                                        </button>
                                    @else
                                        {{-- flow ปกติ --}}
                                        @if ($r->status != 'destroy_completed')
                                            <button class="btn btn-sm btn-outline-success btn-approve"
                                                data-id="{{ $r->id }}" data-status="{{ $r->status }}">

                                                <i class="bi {{ $icons[$r->status] ?? 'bi-check-circle' }}"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-primary btn-detail"
                                                data-id="{{ $r->id }}">

                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-warning btn-edit"
                                                data-id="{{ $r->id }}">

                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        @endif


                                        @if ($r->status == 'destroy_completed')
                                            <a href="{{ route('destroy.print', $r->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline-secondary">

                                                <i class="bi bi-printer"></i>

                                            </a>
                                        @endif

                                        {{-- delete แสดงเฉพาะยังไม่ completed --}}
                                        @if ($r->status != 'completed')
                                            <button class="btn btn-sm btn-outline-danger btn-delete"
                                                data-id="{{ $r->id }}">

                                                <i class="bi bi-trash"></i>

                                            </button>
                                        @endif
                                    @endif

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">ไม่มีข้อมูล</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    {{-- detail --}}

    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 95%;">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">รายละเอียดใบแจ้งสินค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="d_id">
                    <!-- 🔵 ข้อมูลเอกสาร -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">ข้อมูลเอกสาร</h6>
                            <div>
                                <span class="text-muted">เลขที่เอกสาร:</span>
                                <span class="fw-bold" id="d_doc_no_text">-</span>
                            </div>
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
                                            <label class="mb-2 d-block">สาขา *</label>
                                            <select class="form-select" id="d_branch_code">
                                                @foreach (getBranchAll() as $item)
                                                    <option value="{{ $item->branch_code }}">
                                                        {{ $item->branch_code }} - {{ $item->branch_desc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                        {{-- <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">มูลค่า</label>
                                            <input type="text" id="d_total" class="form-control" disabled>
                                        </div> --}}

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
                                            <label class="mb-2 d-block">ประเภทสินค้า</label>

                                            <input type="text" id="d_product_type" class="form-control" readonly>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <label class="mb-2 d-block">ประเภทปัญหา</label>

                                            <input type="text" id="d_issue_type" class="form-control" readonly>
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
                                            <span class="text-muted d_files">
                                                ไม่มีไฟล์แนบ
                                            </span>
                                        </div>

                                    </div>

                                    <div class="mb-4">
                                        <label class="mb-2 d-block">สาเหตุความเสียหาย *</label>
                                        <textarea id="d_damage_reason" class="form-control" rows="6"></textarea>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- 📦 สินค้า -->
                    <div class="card mb-3">
                        <div class="card-header">สินค้า</div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle table-fixed">
                                <colgroup>
                                    <col style="width:15%">
                                    <col style="width:25%">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <col style="width:10%">
                                </colgroup>

                                <thead>
                                    <tr>
                                        <th>รหัสสินค้า</th>
                                        <th>ชื่อสินค้า</th>
                                        <th>ราคา</th>
                                        <th>จำนวน</th>
                                        <th>รวม</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>

                                <tbody id="detail-products"></tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">รวมมูลค่า</td>
                                        <td class="text-end fw-bold">
                                            <span id="d_total">0.00</span>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
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
                            <table class="table table-bordered align-middle table-fixed">
                                <colgroup>
                                    <col style="width:15%">
                                    <col style="width:25%">
                                    <col style="width:15%">
                                    <col style="width:15%">
                                    <col style="width:20%">
                                    <col style="width:10%">
                                </colgroup>

                                <thead>
                                    <tr>
                                        <th>รหัสพนักงาน</th>
                                        <th>ชื่อพนักงาน</th>
                                        <th>% ความรับผิดชอบ</th>
                                        <th>มูลค่า</th>
                                        <th></th>
                                        <th>จัดการ</th>
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
                    <button class="btn btn-success" id="btnApproveAction" style="display:none;">
                        อนุมัติ
                    </button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="imagePreviewModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered" style="max-width:700px;">

            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div class="modal-header border-0 py-3 px-4">

                    <h5 class="modal-title fw-bold">
                        ดูไฟล์แนบ
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <!-- BODY -->
                <div class="modal-body bg-light p-4 text-center" id="previewContainer">

                </div>

            </div>

        </div>

    </div>
@endsection

@section('script')
    <script>
        const DetailState = {
            currentId: null,
            xhr: null
        };

        const STEP_FLOW = {
            pending: 'approved_manager',
            approved_manager: 'waiting_branch_sap',
            waiting_branch_sap: 'sap_completed',
            sap_completed: 'waiting_accounting', // เข้า accounting page
            waiting_accounting: 'accounting_done', // กด save accounting
            accounting_done: 'hr_done', // ไป HR
            hr_done: 'destroy_completed',
            destroy_completed: 'completed'
        };

        const STEP_ICON = {
            pending: 'bi-clock',
            approved_manager: 'bi-check-circle text-success',
            waiting_branch_sap: 'bi-hourglass-split text-warning',
            sap_completed: 'bi-database-check text-primary',
            accounting_done: 'bi-calculator text-info',
            waiting_branch_print: 'bi-printer text-secondary',
            waiting_accounting: 'bi-hourglass text-warning',
            hr_done: 'bi-person-check text-success',
            destroy_completed: 'bi-trash text-danger',
            completed: 'bi-check2-all text-success'
        };

        const STEP_REDIRECT = {
            waiting_branch_sap: "{{ url('branch-sap') }}",
            sap_completed: "{{ url('accounting') }}",
            waiting_accounting: "{{ url('accounting') }}",
            accounting_done: "{{ url('accounting') }}",
            hr_done: "{{ url('destroy-list') }}"
        };


        let approveMode = false;
        let currentApproveId = null;

        let previewMode = 'view';


        $(document).on('change', 'input[name="issue_type"]', function() {

            if ($(this).val() === 'claimable') {
                $('#purchase-section').slideDown();
            } else {
                $('#purchase-section').slideUp();
                $('#purchase_name').val('');
            }

        });

        $(document).on('click', '#btnApproveAction', function() {

            let id = $('#d_id').val();
            let currentStatus = $(this).data('status');


            // 🔥 ถ้ารอปริ้น → เปิดหน้าปริ้นเลย
            if (currentStatus == 'waiting_branch_print') {

                window.location.href =
                    '/discount-print/' + id;

                return;
            }


            let nextStatus =
                STEP_FLOW[currentStatus];


            $.post(
                "{{ url('damage-report/next-step') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    next_status: nextStatus
                },
                function(res) {

                    if (res.success) {

                        location.reload();

                    }

                }
            );

        });

        $(document).on('click', '.BTNadd', function() {

            FORM_MODE = 'create';

            isEdit = false;

            // reset form
            $('#damageForm')[0].reset();

            // clear hidden/input พิเศษ
            $('#d_id').val('');

            // reset product table
            $('#product-wrapper').html(`
        <tr class="product-row">
            <td>
                <input type="text"
                       class="form-control product_code"
                       placeholder="กรุณากรอกรหัสสินค้า">
            </td>

            <td>
                <input type="text"
                       class="form-control product_name"
                       disabled>
            </td>

            <td>
                <input type="text"
                       class="form-control price"
                       disabled>
            </td>

            <td>
                <input type="number"
                       class="form-control qty"
                       placeholder="กรุณากรอกจำนวน">
            </td>

            <td>
                <input type="text"
                       class="form-control total"
                       disabled>
            </td>

            <td class="text-center">
                <button type="button"
                        class="btn btn-danger btn-sm btn-remove-product">
                    ลบ
                </button>
            </td>
        </tr>
    `);

            // reset employee table
            $('#employee-wrapper').html(`
        <tr class="employee-row">
            <td>
                <input type="text"
                       class="form-control emp_code"
                       placeholder="กรุณากรอกรหัสพนักงาน">
            </td>

            <td>
                <input type="text"
                       class="form-control emp_name"
                       placeholder="กรุณากรอกชื่อพนักงาน">
            </td>

            <td>
                <input type="number"
                       class="form-control emp_percent"
                       placeholder="กรุณากรอก% ความรับผิดชอบ">
            </td>

            <td>
                <input type="text"
                       class="form-control emp_amount"
                       disabled>
            </td>

            <td></td>

            <td class="text-center">
                <button type="button"
                        class="btn btn-danger btn-sm btn-remove">
                    ลบ
                </button>
            </td>
        </tr>
    `);

            // reset upload
            uploadedFiles = [];

            $('#attachments').val('');

            // reset section
            $('#purchase-section').hide();

            $('.employee-card').show();

            $('#d_total').text('0.00');

            $('.BTNsave').show();

            $('#orderAdd').modal('show');

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

        $(document).on('click', '.btn-delete', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: 'คุณต้องการลบรายการนี้หรือไม่',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่, ลบเลย',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#d33'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ url('damage-report/delete') }}", // 🔥 route ลบ
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(res) {

                            if (res.success) {
                                Swal.fire('ลบสำเร็จ', '', 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('เกิดข้อผิดพลาด', res.error, 'error');
                            }

                        }
                    });

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

            let productSelector = isEdit ?
                '#detail-products .product-row' :
                '#product-wrapper .product-row';

            let employeeSelector = isEdit ?
                '#detail-employees .employee-row' :
                '#employee-wrapper .employee-row';

            let totalProduct = 0;

            $(productSelector).each(function() {
                totalProduct += parseFloat($(this).find('.total').val()) || 0;
            });

            let totalEmp = 0;

            $(employeeSelector).each(function() {
                let percent = parseFloat($(this).find('.emp_percent').val()) || 0;
                totalEmp += (percent / 100) * totalProduct;
            });

            return totalEmp === totalProduct;
        }

        $(document).on('click', '.btn-update-edit', function() {

            let grandTotal = 0;

            // =========================
            // 📦 PRODUCT
            // =========================
            $('#edit_product_wrapper .product-row').each(function() {

                grandTotal += parseFloat($(this).find('.total').val()) || 0;

            });

            let items = [];

            $('#edit_product_wrapper .product-row').each(function() {

                items.push({
                    product_code: $(this).find('.product_code').val(),
                    product_name: $(this).find('.product_name').val(),
                    price: $(this).find('.price').val(),
                    qty: $(this).find('.qty').val(),
                    total: $(this).find('.total').val()
                });

            });

            // =========================
            // 👨‍💼 EMPLOYEE
            // =========================
            let employees = [];

            $('#edit_employee_wrapper .employee-row').each(function() {

                let percent = parseFloat($(this).find('.emp_percent').val()) || 0;

                employees.push({
                    emp_code: $(this).find('.emp_code').val(),
                    emp_name: $(this).find('.emp_name').val(),
                    percent: percent,
                    amount: (percent / 100) * grandTotal
                });

            });

            // =========================
            // 🔥 FORM DATA
            // =========================
            let formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');

            formData.append('id', $('#edit_id').val());

            formData.append('branch_code', $('#edit_branch_code').val());

            formData.append('report_type', $('#edit_report_source').val());

            formData.append(
                'product_type',
                $('input[name="edit_product_type"]:checked').val()
            );

            formData.append('flow_type', $('#edit_flow_type').val());

            formData.append(
                'issue_type',
                $('input[name="edit_issue_type"]:checked').val()
            );

            formData.append(
                'purchase_name',
                $('#edit_purchase_name').val()
            );

            formData.append(
                'damage_reason',
                $('#edit_damage_reason').val()
            );

            formData.append('total_amount', grandTotal);

            formData.append('items', JSON.stringify(items));

            formData.append('employees', JSON.stringify(employees));

            formData.append(
                'deleted_files',
                JSON.stringify(deletedFiles)
            );

            // =========================
            // 📎 FILES
            // =========================
            let files = $('#edit_attachments')[0].files;

            for (let i = 0; i < files.length; i++) {

                formData.append('attachments[]', files[i]);

            }

            // =========================
            // 🚀 AJAX
            // =========================
            $.ajax({

                url: "{{ url('damage-report/update') }}",

                method: 'POST',

                data: formData,

                processData: false,

                contentType: false,

                success: function(res) {

                    if (res.success) {

                        Swal.fire({
                            icon: 'success',
                            title: 'อัปเดตสำเร็จ'
                        }).then(() => {

                            location.reload();

                        });

                    }

                },

                error: function(err) {

                    console.log(err);

                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด'
                    });

                }

            });

        });

        let uploadedFiles = [];

        // ==========================
        // SELECT FILE
        // ==========================
        $(document).on('change', '#attachments', function(e) {

            uploadedFiles = Array.from(e.target.files);

            $('.btn-open-preview')
                .prop('disabled', uploadedFiles.length === 0);

        });


        // ==========================
        // OPEN PREVIEW
        // ==========================
        $(document).on('click', '.btn-open-preview', function() {

            let files = uploadedFiles;

            if (!files || files.length === 0) {
                Swal.fire('ไม่มีไฟล์', 'กรุณาเลือกไฟล์ก่อน', 'warning');
                return;
            }

            let html = '';

            $('#previewContainer').html('');

            Array.from(files).forEach(file => {

                let url = URL.createObjectURL(file);

                if (file.type.startsWith('image/')) {

                    html += `
                <div class="text-center mb-3">
                    <img src="${url}"
                        class="img-fluid rounded-4 shadow-sm"
                        style="max-height:500px;object-fit:contain;">

                    <div class="mt-2 text-muted">
                        ${file.name}
                    </div>
                </div>
            `;

                } else if (file.type === 'application/pdf') {

                    html += `
                <div class="mb-3">
                    <iframe src="${url}"
                        width="100%"
                        height="500px"
                        style="border:none;border-radius:12px;">
                    </iframe>

                    <div class="mt-2 text-muted">
                        ${file.name}
                    </div>
                </div>
            `;
                }

            });

            $('#previewContainer').html(html);

            $('#imagePreviewModal').modal('show');

        });

        // ==========================
        // PREVIEW FILE FROM DATABASE
        // ==========================
        let deletedFiles = [];

        // ==========================
        // PREVIEW FILE FROM DATABASE
        // ==========================
        $(document).on('click', '.btn-preview-file', function() {

            let files = $(this).data('files');

            if (typeof files === 'string') {
                files = JSON.parse(files);
            }

            let html = '';

            if (!files || files.length === 0) {

                Swal.fire('ไม่มีไฟล์', '', 'warning');
                return;
            }

            files.forEach(function(f, index) {

                let fileUrl =
                    `{{ asset('storage') }}/${f.file_path}`;

                let ext = '';

                if (f.file_path.includes('.')) {
                    ext =
                        f.file_path
                        .split('.')
                        .pop()
                        .toLowerCase();
                }

                let name =
                    f.file_name ||
                    f.file_path.split('/').pop();

                // ปุ่มลบ แสดงเฉพาะ edit mode
                let deleteBtn =
                    previewMode === 'edit' ?
                    `
            <button type="button"
                class="btn btn-danger btn-sm mt-2 btn-delete-file"
                data-id="${f.id}"
                data-index="${index}">
                ลบไฟล์
            </button>
            ` :
                    '';

                // IMAGE
                if (
                    ['jpg', 'jpeg', 'png', 'gif', 'webp']
                    .includes(ext)
                ) {

                    html += `
            <div class="mb-4 text-center file-item-${index}">

                <img src="${fileUrl}"
                    class="img-fluid rounded shadow-sm"
                    style="max-height:500px;object-fit:contain;">

                <div class="mt-2 fw-semibold text-secondary">
                    ${name}
                </div>

                ${deleteBtn}

            </div>
            `;
                }

                // PDF
                else if (ext === 'pdf') {

                    html += `
            <div class="mb-4 file-item-${index}">

                <iframe src="${fileUrl}"
                    width="100%"
                    height="600px"
                    style="border:none;">
                </iframe>

                <div class="mt-2 fw-semibold text-secondary">
                    ${name}
                </div>

                ${deleteBtn}

            </div>
            `;
                }

            });

            $('#previewContainer').html(html);
            $('#imagePreviewModal').modal('show');

        });


        // ==========================
        // CLEAR MODAL
        // ==========================
        $('#imagePreviewModal').on('hidden.bs.modal', function() {

            $('#previewContainer').html('');

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

            // ✅ ใช้ wrapper จริง
            let productSelector = isEdit ?
                '#detail-products .product-row' :
                '#product-wrapper .product-row';

            let employeeSelector = isEdit ?
                '#detail-employees .employee-row' :
                '#employee-wrapper .employee-row';

            let reportSource = $('#report_source').val();

            let productType = $('input[name="product_type"]:checked').val();

            let issueType = $('input[name="issue_type"]:checked').val();

            let purchaseName = $('#purchase_name').val();

            let damageReason = isEdit ?
                $('#d_damage_reason').val() :
                $('#damage_reason').val();
            let flowType = $('#flow_type').val();

            // =========================
            // ✅ ตรวจข้อมูลพื้นฐาน
            // =========================
            if (!reportSource) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกประเภทผู้แจ้ง'
                });
                return false;
            }

            if (!productType) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกประเภทสินค้า'
                });
                return false;
            }

            if (!issueType) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกประเภทปัญหา'
                });
                return false;
            }

            if (issueType === 'claimable' && !purchaseName) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกผู้ดูแลจัดซื้อ'
                });
                return false;
            }

            if (!damageReason) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกสาเหตุความเสียหาย'
                });
                return false;
            }

            // =========================
            // ✅ ตรวจสินค้า
            // =========================
            let hasProduct = false;

            let invalidQty = false;

            $(productSelector).each(function() {

                let code = $(this).find('.product_code').val();

                let qty = parseFloat($(this).find('.qty').val());

                if (code && qty > 0) {
                    hasProduct = true;
                }

                if (!qty || qty <= 0) {
                    invalidQty = true;
                }
            });

            if (!hasProduct) {

                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเพิ่มสินค้าอย่างน้อย 1 รายการ'
                });

                return false;
            }

            if (invalidQty) {

                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกจำนวนสินค้าให้ถูกต้อง'
                });

                return false;
            }

            // =========================
            // ✅ ตรวจพนักงาน
            // =========================
            let hasEmployee = false;

            $(employeeSelector).each(function() {

                let code = $(this).find('.emp_code').val();

                let percent = $(this).find('.emp_percent').val();

                if (code && percent !== '') {
                    hasEmployee = true;
                }
            });

            if (flowType !== 'quality') {

                if (!hasEmployee) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณาเพิ่มพนักงานอย่างน้อย 1 คน'
                    });

                    return false;
                }

                let totalPercent = 0;

                $(employeeSelector).find('.emp_percent').each(function() {

                    totalPercent += parseFloat($(this).val()) || 0;

                });

                if (Math.round(totalPercent) !== 100) {

                    Swal.fire({
                        icon: 'error',
                        title: 'เปอร์เซ็นต์รวมต้องเท่ากับ 100%'
                    });

                    return false;
                }
            }

            return true;
        }

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

        // ==========================
        // DELETE FILE
        // ==========================
        $(document).on('click', '.btn-delete-file', function() {

            let id = $(this).data('id');
            let index = $(this).data('index');

            Swal.fire({
                title: 'ยืนยันการลบ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ลบเลย',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({

                        url: "{{ url('damage-report/delete-file') }}",

                        method: 'POST',

                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },

                        success: function(res) {

                            if (res.success) {

                                $(`.file-item-${index}`).remove();

                                Swal.fire(
                                    'ลบสำเร็จ',
                                    '',
                                    'success'
                                );

                            } else {

                                Swal.fire(
                                    'ผิดพลาด',
                                    res.error,
                                    'error'
                                );

                            }

                        }

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

            // reset ค่า
            $('#report_source').val('');

            // ซ่อนทั้งหมดก่อน
            $('#report_source option').hide();

            // ให้ option default แสดงเสมอ
            $('#report_source option[value=""]').show();

            if (flow === 'quality') {
                $('.quality-section').show();
                $('.employee-card').hide();
            } else {
                $('.quality-section').hide();
                $('.employee-card').show();
            }

            if (flow === 'destroy' || flow === 'discount') {

                $('#report_source option[data-group="all"]').show();
                $('#report_source option[data-group="basic"]').show();

            } else if (flow === 'claim') {

                $('#report_source option[data-group="all"]').show();
                $('#report_source option[data-group="claim"]').show();

            } else if (flow === 'quality') {

                $('#report_source option[data-group="all"]').show();
                $('#report_source option[data-group="claim"]').show();
            }

        });

        function calculateTotal(wrapper = '#product-wrapper') {

            let total = 0;

            $(wrapper).find('.product-row').each(function() {

                let price = parseFloat($(this).find('.price').val()) || 0;
                let qty = parseFloat($(this).find('.qty').val()) || 0;

                let rowTotal = price * qty;

                $(this).find('.total').val(rowTotal.toFixed(2));

                total += rowTotal;
            });

            $('#d_total').text(total.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            calculateEmployeeAmount(wrapper, total);
        }

        function calculateEmployee(total) {

            $('#detail-employees .employee-row').each(function() {

                let percent = parseFloat($(this).find('.emp_percent').val()) || 0;

                let amount = (total * percent) / 100;

                $(this).find('.emp_amount').val(amount.toFixed(2));

            });

        }

        function calculateEmployeeAmount(mode = 'add') {

            let productWrapper = mode === 'edit' ?
                '#edit_product_wrapper' :
                '#product-wrapper';

            let employeeWrapper = mode === 'edit' ?
                '#edit_employee_wrapper' :
                '#employee-wrapper';

            let totalProduct = 0;

            $(productWrapper + ' .product-row').each(function() {

                totalProduct += parseFloat($(this).find('.total').val()) || 0;

            });

            $(employeeWrapper + ' .employee-row').each(function() {

                let percent = parseFloat($(this).find('.emp_percent').val()) || 0;

                let amount = (percent / 100) * totalProduct;

                $(this).find('.emp_amount').val(amount.toFixed(2));

            });

        }

        $(document).on('keyup change', '.emp_percent', function() {
            calculateEmployeeAmount();
        });

        $(document).on('keyup change', '#detail-employees .emp_percent', function() {

            calculateTotal();

        });

        $(document).on('keyup change', '.qty', function() {

            let row = $(this).closest('tr');

            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;

            row.find('.total').val(price * qty);

            calculateEmployeeAmount(); // 👈 สำคัญ
        });

        $(document).on('keyup change', '#detail-products .qty', function() {

            let row = $(this).closest('.product-row');

            let price = parseFloat(row.find('.price').val()) || 0;
            let qty = parseFloat(row.find('.qty').val()) || 0;

            row.find('.total').val((price * qty).toFixed(2));

            calculateTotal('#detail-products');
        });

        $(document).on('keyup change', '#edit_product_wrapper .qty, #edit_product_wrapper .price', function() {

            let row = $(this).closest('tr');

            let price = parseFloat(row.find('.price').val()) || 0;

            let qty = parseFloat(row.find('.qty').val()) || 0;

            row.find('.total').val((price * qty).toFixed(2));

            calculateEmployeeAmount('edit');

        });

        $(document).on('keyup change', '#edit_employee_wrapper .emp_percent', function() {

            calculateEmployeeAmount('edit');

        });

        // detail
        $(document).on('click', '.btn-detail', function() {

            previewMode = 'view';

            let id = $(this).attr('data-id');

            // 🔥 ปิด modal เก่าก่อน
            $('#modalDetail').modal('hide');

            // 🔥 reset กันข้อมูลค้าง
            resetDetail();

            // 🔥 abort request เก่า
            if (DetailState.xhr) {
                DetailState.xhr.abort();
            }

            // 🔥 เก็บ request ล่าสุด
            let requestId = id;

            DetailState.currentId = requestId;

            // 📡 request ใหม่
            DetailState.xhr = $.get(
                "{{ url('damage-report/detail') }}", {
                    id: requestId
                },

                function(res) {

                    // ❌ ถ้า response เก่า ให้ทิ้ง
                    if (DetailState.currentId != requestId) {
                        return;
                    }

                    // 🔥 reset อีกรอบกัน DOM ค้าง
                    resetDetail();

                    // ✅ render ใหม่
                    renderDetail(res);

                    // 🚀 เปิด modal
                    $('#modalDetail').modal('show');
                }
            );

        });

        $('#flow_type').on('change', function() {

            let flowType = $(this).val();

            // reset ค่า
            $('#report_source').val('');

            // ยังไม่เลือก flow type
            if (!flowType) {

                $('#report_source')
                    .prop('disabled', true);

                $('#report_source option:first')
                    .text('กรุณาเลือกรูปแบบการดำเนินการก่อน');

                return;
            }

            // เปิด select
            $('#report_source')
                .prop('disabled', false);

            // เปลี่ยนข้อความ option แรก
            $('#report_source option:first')
                .text('-- เลือกประเภทผู้แจ้ง --');

            // ซ่อนทั้งหมดก่อน
            $('#report_source option').hide();

            // แสดง option แรก
            $('#report_source option:first').show();

            // =========================
            // logic ตาม flow_type
            // =========================

            // destroy / discount / quality
            if (
                flowType === 'destroy' ||
                flowType === 'discount' ||
                flowType === 'quality'
            ) {

                $('#report_source option[data-group="all"]').show();
                $('#report_source option[data-group="basic"]').show();
            }

            // claim
            if (flowType === 'claim') {

                $('#report_source option[data-group="claim"]').show();
            }

        });

        function resetDetail() {

            $('#detail-products').html('');
            $('#detail-employees').html('');

            $('#employee-box').hide();

            $('#d_doc_no_text').text('-');

            $('#d_total').text('0.00');

            $('#d_product_type').val('');
            $('#d_issue_type').val('');
            $('#d_date').val('');

            $('#d_files').html(`
    <span class="text-muted">
        ไม่มีไฟล์แนบ
    </span>
    `);

            $('#modalDetail')
                .find('input:not([type=hidden])')
                .val('');

            $('#modalDetail').find('textarea').val('');

            $('#modalDetail')
                .find('input[type=radio], input[type=checkbox]')
                .prop('checked', false);

        }

        function renderDetail(res) {

            let reportSource = (res.report_type || '')
                .toString()
                .trim()
                .toLowerCase();

            // 🔵 HEADER
            $('#d_doc_no_text').text(res.doc_no || '-');
            $('#d_branch_code').val(res.branch_code || '');
            let reportSourceText = {
                branch: 'สาขาแจ้ง',
                dc: 'คลังแจ้ง',
                customer: 'พนักงานแจ้ง'
            };

            $('#d_report_source').val(reportSource);
            $('#d_flow_type').val(res.flow_type || '');
            $('#d_total').text(parseFloat(res.total_amount || 0).toLocaleString());
            $('#d_damage_reason').val(res.damage_reason || '');
            // normalize
            let productType = (res.product_type || '')
                .toString()
                .trim()
                .toLowerCase();

            let issueType = (res.issue_type || '')
                .toString()
                .trim()
                .toLowerCase();


            // mapping แปลงค่าเป็นข้อความไทย
            const productText = {
                domestic: 'ในประเทศ',
                international: 'นอกประเทศ'
            };

            const issueText = {
                defect: 'สินค้าด้อยคุณภาพจากการผลิต',
                claimable: 'สินค้าเสียหายที่สามารถเคลมได้'
            };


            // set ค่า textbox
            $('#d_product_type').val(
                productText[productType] || '-'
            );

            $('#d_issue_type').val(
                issueText[issueType] || '-'
            );

            // ✅ วันที่เอกสาร
            $('#d_date').val(
                res.created_at ?
                res.created_at.substring(0, 10) :
                ''
            );

            // =========================
            // ✅ FILES
            // =========================

            currentPreviewFiles = res.files || [];

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

            // 📦 PRODUCT
            let productHtml = '';

            (res.items || []).forEach(i => {

                productHtml += `
            <tr class="product-row">
                <td><input class="form-control product_code" value="${i.product_code || ''}"></td>
                <td><input class="form-control product_name" value="${i.product_name || ''}"></td>
                <td><input class="form-control price" value="${i.price || 0}"></td>
                <td><input type="number" class="form-control qty" value="${i.qty || 0}"></td>
                <td><input class="form-control total" value="${i.total || 0}"></td>
                <td>
                    <button class="btn btn-danger btn-sm remove-product">ลบ</button>
                </td>
            </tr>
        `;
            });

            $('#detail-products').html(productHtml);

            calculateTotal('#detail-products');

            // 👨‍💼 EMPLOYEE
            let empHtml = '';

            if (res.employees && res.employees.length > 0) {

                res.employees.forEach(e => {

                    empHtml += `
                <tr class="employee-row">
                    <td><input class="form-control emp_code" value="${e.emp_code || ''}"></td>
                    <td><input class="form-control emp_name" value="${e.emp_name || ''}"></td>
                    <td><input type="number" class="form-control emp_percent" value="${e.percent || 0}"></td>
                    <td><input class="form-control emp_amount" value="${e.amount || 0}"></td>
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

            // 🔥 flow control
            if (res.flow_type === 'quality') {
                $('#employee-box').hide();
            }

            // 🔒 lock mode
            lockModal();
        }

        function lockModal() {

            // input + textarea
            $('#modalDetail')
                .find('input, textarea')
                .prop('readonly', true)
                .css({
                    'background-color': '#e9ecef',
                    'pointer-events': 'none',
                    'cursor': 'not-allowed'
                });

            // select + radio + checkbox
            $('#modalDetail')
                .find('select, input[type=radio], input[type=checkbox]')
                .prop('disabled', true)
                .css({
                    'background-color': '#e9ecef',
                    'cursor': 'not-allowed'
                });

            // ปิดปุ่มแก้ไขทั้งหมดใน detail
            $('#modalDetail')
                .find(`
            .btn-add-product,
            .btn-add-emp,
            .remove-product,
            .remove-emp,
            .btn-update
        `)
                .hide();
        }

        $(document).on('click', '.btn-edit', function() {

            previewMode = 'edit';

            uploadedFiles = [];

            $('#attachments').val('');

            FORM_MODE = 'edit';

            $('#editDamageForm')[0].reset();

            let id = $(this).data('id');

            $('#edit_id').val(id);

            $.get("{{ url('damage-report/detail') }}", {
                id: id
            }, function(res) {

                let reportSource = (res.report_type || '').toString().trim().toLowerCase();

                // =========================
                // 🔵 HEADER
                // =========================
                let docDate = res.doc_date || res.created_at || '';

                if (docDate) {
                    docDate = docDate.split('T')[0];
                }

                $('#edit_doc_date').val(docDate);
                $('#edit_branch_code').val(res.branch_code);

                $('#edit_flow_type').val(res.flow_type);

                handleFlowType(res.flow_type, 'edit');
                $('#edit_damage_reason').val(res.damage_reason);

                $('#edit_report_source').val(reportSource);

                $('#edit_purchase_name').val(res.purchase_name || '');

                $('input[name="edit_product_type"]').prop('checked', false);
                $('input[name="edit_issue_type"]').prop('checked', false);

                $('input[name="edit_product_type"][value="' + $.trim(res.product_type) + '"]')
                    .prop('checked', true);

                $('input[name="edit_issue_type"][value="' + $.trim(res.issue_type) + '"]')
                    .prop('checked', true);

                // =========================
                // 📦 PRODUCT
                // =========================
                let productHtml = '';

                if (res.items && res.items.length > 0) {

                    res.items.forEach(function(i) {

                        productHtml += `
                    <tr class="product-row">
                        <td>
                            <input type="text"
                                   class="form-control product_code"
                                   value="${i.product_code || ''}">
                        </td>

                        <td>
                            <input type="text"
                                   class="form-control product_name"
                                   value="${i.product_name || ''}">
                        </td>

                        <td>
                            <input type="text"
                                   class="form-control price"
                                   value="${i.price || 0}">
                        </td>

                        <td>
                            <input type="number"
                                   class="form-control qty"
                                   value="${i.qty || 0}">
                        </td>

                        <td>
                            <input type="text"
                                   class="form-control total"
                                   value="${i.total || 0}">
                        </td>

                        <td class="text-center">
                            <button type="button"
                                    class="btn btn-danger btn-sm btn-remove-product">
                                ลบ
                            </button>
                        </td>
                    </tr>
                `;
                    });

                }

                $('#edit_product_wrapper').html(productHtml);

                // =========================
                // 👨‍💼 EMPLOYEE
                // =========================
                let empHtml = '';

                if (res.employees && res.employees.length > 0) {

                    res.employees.forEach(function(e) {

                        empHtml += `
                    <tr class="employee-row">

                        <td>
                            <input type="text"
                                   class="form-control emp_code"
                                   value="${e.emp_code || ''}">
                        </td>

                        <td>
                            <input type="text"
                                   class="form-control emp_name"
                                   value="${e.emp_name || ''}">
                        </td>

                        <td>
                            <input type="number"
                                   class="form-control emp_percent"
                                   value="${e.percent || 0}">
                        </td>

                        <td>
                            <input type="text"
                                   class="form-control emp_amount"
                                   value="${e.amount || 0}">
                        </td>

                        <td></td>

                        <td class="text-center">
                            <button type="button"
                                    class="btn btn-danger btn-sm btn-remove">
                                ลบ
                            </button>
                        </td>

                    </tr>
                `;
                    });

                }



                $('#edit_employee_wrapper').html(empHtml);

                // =========================
                // 📎 FILES
                // =========================
                let fileHtml = '';

                if (res.files && res.files.length > 0) {

                    fileHtml = `
            <div class="input-group">

            <input type="text"
                class="form-control file-count-input"
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

                $('#edit_files').html(fileHtml);

                // =========================
                // 🔥 FLOW CONTROL
                // =========================
                if (res.flow_type === 'quality') {
                    $('.employee-card').hide();
                } else {
                    $('.employee-card').show();
                }

                // =========================
                // 🔓 ENABLE FORM
                // =========================
                $('#editModal').find('input, textarea')
                    .prop('readonly', false);

                $('#editModal').find('select, input[type=radio], input[type=checkbox]')
                    .prop('disabled', false);

                // =========================
                // 🚀 OPEN MODAL
                // =========================
                $('#editModal').modal('show');

            });

        });

        function handleFlowType(flowType, mode = 'add') {

            let prefix = mode === 'edit' ? '#edit_' : '#';

            // ซ่อนก่อน
            $('.employee-card').show();
            $('.quality-section').hide();
            $('#purchase-section').hide();

            // quality
            if (flowType === 'quality') {
                $('.employee-card').hide();
                $('.quality-section').show();
            }

            // claim
            if (flowType === 'claim') {
                $('#purchase-section').show();
            }

            // enable report source
            $(prefix + 'report_source').prop('disabled', false);

            // reset option
            $(prefix + 'report_source option').hide();

            // show default
            $(prefix + 'report_source option[value=""]').show();

            // mapping
            if (flowType === 'destroy' || flowType === 'discount') {

                $(prefix + 'report_source option[value="branch"]').show();
                $(prefix + 'report_source option[value="customer"]').show();

            } else if (flowType === 'claim') {

                $(prefix + 'report_source option').show();

            } else if (flowType === 'quality') {

                $(prefix + 'report_source option[value="customer"]').show();

            }
        }

        $(document).on('click', '#edit_add_product', function() {

            let html = `

    <tr class="product-row">

        <td>
            <input type="text"
                   class="form-control product_code"
                   placeholder="กรุณากรอกรหัสสินค้า">
        </td>

        <td>
            <input type="text"
                   class="form-control product_name">
        </td>

        <td>
            <input type="text"
                   class="form-control price">
        </td>

        <td>
            <input type="number"
                   class="form-control qty">
        </td>

        <td>
            <input type="text"
                   class="form-control total">
        </td>

        <td class="text-center">

            <button type="button"
                    class="btn btn-danger btn-sm btn-remove-product">

                ลบ

            </button>

        </td>

    </tr>

    `;

            $('#edit_product_wrapper').append(html);

        });

        $(document).on('click', '#edit_add_employee', function() {

            let html = `

    <tr class="employee-row">

        <td>
            <input type="text"
                   class="form-control emp_code"
                   placeholder="กรุณากรอกรหัสพนักงาน">
        </td>

        <td>
            <input type="text"
                   class="form-control emp_name"
                   placeholder="กรุณากรอกชื่อพนักงาน">
        </td>

        <td>
            <input type="number"
                   class="form-control emp_percent">
        </td>

        <td>
            <input type="text"
                   class="form-control emp_amount">
        </td>

        <td></td>

        <td class="text-center">

            <button type="button"
                    class="btn btn-danger btn-sm btn-remove">

                ลบ

            </button>

        </td>

    </tr>

    `;

            $('#edit_employee_wrapper').append(html);

        });

        $(document).on('change', '#flow_type', function() {
            handleFlowType($(this).val(), 'add');
        });

        $(document).on('change', '#edit_flow_type', function() {
            handleFlowType($(this).val(), 'edit');
        });

        $(document).on('click', '.btn-approve', function() {

            let id = $(this).data('id');
            let status = $(this).data('status');

            if (status === 'pending') {
                window.location.href =
                    "{{ route('manager-approve') }}" + "?id=" + id;
                return;
            }

            if (status === 'approved_manager') {
                window.location.href =
                    "{{ route('admin-approve') }}" + "?id=" + id;
                return;
            }

            if (status === 'waiting_branch_sap') {
                window.location.href =
                    "{{ route('branch.sap') }}" + "?id=" + id;
                return;
            }

            if (status === 'waiting_branch_print') {

                window.location.href =
                    "{{ url('discount-print') }}/" + id;

                return;
            }

            if (status === 'waiting_accounting') {
                window.location.href =
                    "{{ url('accounting') }}" + "?id=" + id;
                return;
            }

            if (status === 'accounting_done') {
                window.location.href =
                    "{{ route('hr.approve') }}" + "?id=" + id;
                return;
            }

            if (status === 'hr_done') {
                window.location.href =
                    "{{ route('destroy.list') }}" + "?id=" + id;
                return;
            }

            isEdit = false;

            $('#d_id').val(id);

            // 🔥 ปิด modal เก่า
            $('#modalDetail').modal('hide');

            // 🔥 reset ข้อมูลเก่า
            resetDetail();

            // 🔥 abort request เก่า
            if (DetailState.xhr) {
                DetailState.xhr.abort();
            }

            // 🔥 request ล่าสุด
            let requestId = id;

            DetailState.currentId = requestId;

            // 📡 request ใหม่
            DetailState.xhr = $.get(
                "{{ url('damage-report/detail') }}", {
                    id: requestId
                },

                function(res) {

                    // ❌ ถ้า response เก่า ให้ทิ้ง
                    if (DetailState.currentId != requestId) {
                        return;
                    }

                    resetDetail();

                    // 🔥 reset กัน DOM ค้าง
                    renderDetail(res);

                    $('#modalDetail').modal('show');
                }
            );

        });

        // ✅ เพิ่มสินค้าใน modalDetail
        $(document).on('click', '.btn-add-product', function() {

            let row = $('#detail-products .product-row:first').clone();

            row.find('input').val('');

            $('#detail-products').append(row);

            calculateTotal();
        });

        $(document).on('click', '.remove-product', function() {

            if ($('#detail-products .product-row').length <= 1) {
                alert('ต้องมีสินค้าอย่างน้อย 1 รายการ');
                return;
            }

            $(this).closest('tr').remove();
            calculateTotal();
        });

        // ✅ เพิ่มพนักงานใน modalDetail
        $(document).on('click', '.btn-add-emp', function() {

            let row = $('#detail-employees .employee-row:first').clone();

            row.find('input').val('');

            $('#detail-employees').append(row);

        });

        $(document).on('click', '.remove-emp', function() {

            if ($('#detail-employees .employee-row').length <= 1) {
                alert('ต้องมีพนักงานอย่างน้อย 1 คน');
                return;
            }

            $(this).closest('tr').remove();
        });

        $(document).on('click', '.btn-update', function() {

            // validate
            if (!validateForm()) {
                return;
            }

            let grandTotal = 0;

            // =========================
            // 📦 PRODUCT
            // =========================
            $('#product-wrapper .product-row').each(function() {

                grandTotal += parseFloat(
                    $(this).find('.total').val()
                ) || 0;

            });

            let items = [];

            $('#product-wrapper .product-row').each(function() {

                items.push({
                    product_code: $(this).find('.product_code').val(),
                    product_name: $(this).find('.product_name').val(),
                    price: $(this).find('.price').val(),
                    qty: $(this).find('.qty').val(),
                    total: $(this).find('.total').val()
                });

            });

            // =========================
            // 👨‍💼 EMPLOYEE
            // =========================
            let employees = [];

            $('#employee-wrapper .employee-row').each(function() {

                let percent = parseFloat(
                    $(this).find('.emp_percent').val()
                ) || 0;

                employees.push({
                    emp_code: $(this).find('.emp_code').val(),
                    emp_name: $(this).find('.emp_name').val(),
                    percent: percent,
                    amount: (percent / 100) * grandTotal
                });

            });

            // =========================
            // 📎 FORM DATA
            // =========================
            let formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');

            formData.append(
                'branch_code',
                $('#branch_code').val()
            );

            formData.append(
                'report_type',
                $('#report_source').val()
            );

            formData.append(
                'product_type',
                $('input[name="product_type"]:checked').val()
            );

            formData.append(
                'flow_type',
                $('#flow_type').val()
            );

            formData.append(
                'issue_type',
                $('input[name="issue_type"]:checked').val()
            );

            formData.append(
                'purchase_name',
                $('#purchase_name').val()
            );

            formData.append(
                'damage_reason',
                $('#damage_reason').val()
            );

            formData.append(
                'total_amount',
                grandTotal
            );

            formData.append(
                'items',
                JSON.stringify(items)
            );

            formData.append(
                'employees',
                JSON.stringify(employees)
            );

            // =========================
            // 📎 FILES
            // =========================
            let files = $('#attachments')[0].files;

            for (let i = 0; i < files.length; i++) {

                formData.append(
                    'attachments[]',
                    files[i]
                );

            }

            // =========================
            // 🚀 AJAX
            // =========================
            $.ajax({

                url: "{{ url('damage-report/store') }}",

                method: 'POST',

                data: formData,

                processData: false,

                contentType: false,

                success: function(res) {

                    if (res.success) {

                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกสำเร็จ'
                        }).then(() => {

                            location.reload();

                        });

                    } else {

                        Swal.fire({
                            icon: 'error',
                            title: res.error || 'เกิดข้อผิดพลาด'
                        });

                    }

                },

                error: function(err) {

                    console.log(err);

                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด'
                    });

                }

            });

        });
    </script>
@endsection

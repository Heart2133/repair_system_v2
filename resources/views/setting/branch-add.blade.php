@extends('layouts.master-layouts')

@section('title')
    เพิ่มสาขา
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            ตั้งค่า
        @endslot
        @slot('title')
            เพิ่มสาขา
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-4">เพิ่มสาขา</h4>

                    <form method="POST" action="{{ route('branch_store') }}">
                        @csrf

                        <div class="row">

                            <div class="col-md-3 mb-3">
                                <label>รหัสสาขา</label>
                                <input type="text" name="branch_code" class="form-control" required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>SAP Code</label>
                                <input type="text" name="sap_code" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>ชื่อสาขา (TH)</label>
                                <input type="text" name="branch_desc" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>ชื่อสาขา (EN)</label>
                                <input type="text" name="branch_desc_en" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Line ID</label>
                                <input type="text" name="line_id" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>สถานะ</label>
                                <select name="branch_active" class="form-control">
                                    <option value="Y">ใช้งาน</option>
                                    <option value="N">ปิดใช้งาน</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Company Name</label>
                                <input type="text" name="company_name" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Company Name EN</label>
                                <input type="text" name="company_name_en" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Company Tel</label>
                                <input type="text" name="company_tel" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Company Fax</label>
                                <input type="text" name="company_fax" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Company Address</label>
                                <textarea name="company_addr" class="form-control"></textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label>Company Address EN</label>
                                <textarea name="company_addr_en" class="form-control"></textarea>
                            </div>

                        </div>

                        <div class="text-end">

                            <a href="{{ route('branch_manage') }}" class="btn btn-secondary">
                                ย้อนกลับ
                            </a>

                            <button type="submit" class="btn btn-success">
                                บันทึกข้อมูล
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection

@extends('layouts.master-layouts')

@section('title')
    แก้ไขสาขา
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="card">

                <div class="card-body border-bottom d-flex justify-content-between align-items-center">
                    <div style="color:black;font-size:14px;">
                        แก้ไขสาขา
                    </div>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('branch_update', $branch->id) }}">
                        @csrf

                        <div class="row gy-3">

                            <div class="col-12 col-md-6">
                                <label class="form-label">รหัสสาขา</label>
                                <input type="text" name="branch_code" class="form-control"
                                    value="{{ $branch->branch_code }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">SAP Code</label>
                                <input type="text" name="sap_code" class="form-control" value="{{ $branch->sap_code }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">ชื่อสาขา TH</label>
                                <input type="text" name="branch_desc" class="form-control"
                                    value="{{ $branch->branch_desc }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">ชื่อสาขา EN</label>
                                <input type="text" name="branch_desc_en" class="form-control"
                                    value="{{ $branch->branch_desc_en }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Line ID</label>
                                <input type="text" name="line_id" class="form-control" value="{{ $branch->line_id }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">สถานะ</label>
                                <select name="branch_active" class="form-control">

                                    <option value="Y" {{ $branch->branch_active == 'Y' ? 'selected' : '' }}>
                                        ใช้งาน
                                    </option>

                                    <option value="N" {{ $branch->branch_active == 'N' ? 'selected' : '' }}>
                                        ปิดใช้งาน
                                    </option>

                                </select>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control"
                                    value="{{ $branch->company_name }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Company Name EN</label>
                                <input type="text" name="company_name_en" class="form-control"
                                    value="{{ $branch->company_name_en }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Company Address</label>
                                <textarea name="company_addr" class="form-control" rows="2">{{ $branch->company_addr }}</textarea>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Company Address EN</label>
                                <textarea name="company_addr_en" class="form-control" rows="2">{{ $branch->company_addr_en }}</textarea>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Tel</label>
                                <input type="text" name="company_tel" class="form-control"
                                    value="{{ $branch->company_tel }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Fax</label>
                                <input type="text" name="company_fax" class="form-control"
                                    value="{{ $branch->company_fax }}">
                            </div>

                        </div>

                        <div class="d-flex justify-content-end mt-4 gap-2">
                            <a href="{{ route('branch_manage') }}" class="btn btn-secondary">
                                ย้อนกลับ
                            </a>

                            <button type="submit" class="btn btn-success">
                                บันทึก
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection

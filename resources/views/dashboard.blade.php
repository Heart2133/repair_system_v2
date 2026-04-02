@extends('layouts.master-layouts')

@section('title')
    Dashboard
@endsection

@section('content')

<div class="card shadow-sm">

        <!-- 🔷 HEADER -->
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Dashboard</h5>
        </div>

        <!-- 🔷 BODY -->
        <div class="card-body">

            <!-- 📊 SUMMARY -->
            <div class="row mb-4">

                <div class="col-md-3">
                    <div class="border rounded p-3 text-center">
                        <div class="text-muted">รายการใหม่</div>
                        <h4 class="mb-0">0</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded p-3 text-center">
                        <div class="text-muted">รออนุมัติ</div>
                        <h4 class="mb-0 text-warning">0</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded p-3 text-center">
                        <div class="text-muted">อยู่ระหว่างดำเนินการ</div>
                        <h4 class="mb-0 text-info">0</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded p-3 text-center">
                        <div class="text-muted">เสร็จสิ้น</div>
                        <h4 class="mb-0 text-success">0</h4>
                    </div>
                </div>

            </div>

            <!-- 📌 MY TASK -->
            <div class="row mb-4">
                <div class="col-md-6">

                    <div class="card border">
                        <div class="card-header bg-light">
                            <strong>งานที่ต้องดำเนินการ</strong>
                        </div>

                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-2">
                                <span>รออนุมัติ</span>
                                <span class="badge bg-danger">0</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>รอดำเนินการ SAP</span>
                                <span class="badge bg-warning">0</span>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span>อยู่ระหว่างดำเนินการ</span>
                                <span class="badge bg-success">0</span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- 📋 TABLE -->
            <div class="table-responsive">
                <table id="datatable5" class="table table-hover table-bordered align-middle">

                    <thead>
                        <tr style="background:#556ee6;color:white;">
                            <th style="width: 5%;">#</th>
                            <th>เลขที่เอกสาร / รายการ</th>
                            <th style="width: 15%;">สถานะ</th>
                            <th style="width: 15%;">จัดการ</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td>DR-2026-0001 สินค้าชำรุด</td>
                            <td class="text-center">
                                <span class="badge bg-warning">รออนุมัติ</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary">ดูรายละเอียด</button>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>
    </div>
    
@endsection

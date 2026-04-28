<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DamageReport;
use App\Models\DamageReportItem;
use App\Models\DamageReportEmployee;

class DamageReportController extends Controller
{
    public function index()
    {
        $total = DamageReport::count();

        $pending = DamageReport::where('status', 'pending')->count();

        $claim = DamageReport::where('status', 'waiting_claim_result')->count();

        $process = DamageReport::whereIn('status', [
            'approved_manager',
            'waiting_branch_sap',
            'sap_completed',
            'accounting_done',
            'waiting_branch_print',
            'waiting_accounting',
            'hr_done'
        ])->count();

        $success = DamageReport::whereIn('status', [
            'destroy_completed',
            'completed'
        ])->count();

        $reports = DamageReport::latest()->get();

        return view('home', compact(
            'total',
            'pending',
            'process',
            'success',
            'claim',
            'reports'
        ));
    }

    public function update(Request $request)
    {
        try {

            $report = DamageReport::where('id', $request->id)->first();

            if (!$report) {
                return response()->json([
                    'success' => false,
                    'error' => 'ไม่พบข้อมูล'
                ]);
            }

            // update header
            $report->branch_code = $request->branch_code;
            $report->flow_type = $request->flow_type;
            $report->damage_reason = $request->damage_reason;
            $report->product_type = $request->product_type;
            $report->issue_type = $request->issue_type;
            $report->report_type = $request->report_type;
            $total = 0; // คำนวณ total ใหม่
            $report->save();

            // 🔥 ลบของเก่า
            DamageReportItem::where('damage_report_id', $report->id)->delete();
            DamageReportEmployee::where('damage_report_id', $report->id)->delete();

            // 🔥 insert items ใหม่
            foreach ($request->items as $i) {

                $lineTotal = $i['price'] * $i['qty']; // ✅ คำนวณใหม่

                $total += $lineTotal; // ✅ สะสมยอดรวม

                DamageReportItem::create([
                    'damage_report_id' => $report->id,
                    'product_code' => $i['product_code'],
                    'product_name' => $i['product_name'],
                    'price' => $i['price'],
                    'qty' => $i['qty'],
                    'total' => $lineTotal, // ใช้ค่าที่คำนวณ
                ]);
            }

            $report->total_amount = $total;
            $report->save();


            // 🔥 insert employee ใหม่
            if ($request->employees) {
                foreach ($request->employees as $e) {
                    DamageReportEmployee::create([
                        'damage_report_id' => $report->id,
                        'emp_code' => $e['emp_code'],
                        'emp_name' => $e['emp_name'],
                        'percent' => $e['percent'],
                        'amount' => $e['amount'],
                    ]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    // public function index()
    // {
    //     $reports = DB::table('damage_reports')->get();
    //     return view('home', compact('reports'));
    // }


    public function store(Request $request)
    {
        $request->validate([
            'branch_code'   => 'required',
            'report_type'   => 'required',
            'product_type'  => 'required',
            'flow_type'     => 'required',
            'issue_type'    => 'required',
            'damage_reason' => 'required',
            'items'         => 'required|array|min:1',
            'employees'     => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            // 🔥 generate doc_no
            $today = date('Ymd');

            $last = DB::table('damage_reports')
                ->whereDate('created_at', date('Y-m-d'))
                ->lockForUpdate()
                ->orderBy('doc_no', 'desc')
                ->first();

            $running = $last ? intval(substr($last->doc_no, -4)) + 1 : 1;
            $doc_no = 'DR' . $today . str_pad($running, 4, '0', STR_PAD_LEFT);

            // 🔥 คำนวณ total ใหม่
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalAmount += ($item['price'] * $item['qty']);
            }

            // 🔥 validate %
            $totalPercent = collect($request->employees)->sum('percent');
            if ($totalPercent != 100) {
                return response()->json([
                    'success' => false,
                    'error' => 'เปอร์เซ็นต์ต้องรวม 100%'
                ]);
            }

            // ✅ insert header
            $reportId = DB::table('damage_reports')->insertGetId([
                'doc_no'        => $doc_no,
                'branch_code'   => $request->branch_code,
                'report_type'   => $request->report_type,
                'flow_type'     => $request->flow_type,
                'product_type'  => $request->product_type,
                'issue_type'    => $request->issue_type,
                'purchase_name' => $request->purchase_name,
                'damage_reason' => $request->damage_reason,
                'total_amount'  => $totalAmount,
                'status'        => 'pending',
                'created_by'    => auth()->id() ?? 0,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // ✅ items
            foreach ($request->items as $item) {

                if (empty($item['product_code'])) continue;

                DB::table('damage_report_items')->insert([
                    'damage_report_id' => $reportId,
                    'product_code' => $item['product_code'],
                    'product_name' => $item['product_name'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'total' => $item['price'] * $item['qty'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ✅ employees
            foreach ($request->employees as $emp) {

                DB::table('damage_report_employees')->insert([
                    'damage_report_id' => $reportId,
                    'emp_code' => $emp['emp_code'],
                    'emp_name' => $emp['emp_name'],
                    'percent' => $emp['percent'],
                    'amount' => ($emp['percent'] / 100) * $totalAmount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // ✅ log
            DB::table('damage_report_logs')->insert([
                'report_id' => $reportId,
                'action' => 'created',
                'remark' => 'สร้างรายการ',
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroyStore(Request $request)
    {
        DB::beginTransaction();

        try {

            // 1. save header
            $destroyId = DB::table('damage_destroy')->insertGetId([
                'damage_report_id' => $request->report_id,
                'location' => $request->location,
                'destroy_date' => $request->destroy_date,
                'remark' => $request->remark,
                'created_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. upload images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {

                    $path = $file->store('destroy_images', 'public');

                    DB::table('damage_destroy_images')->insert([
                        'destroy_id' => $destroyId,
                        'image_path' => $path,
                        'created_at' => now()
                    ]);
                }
            }

            DB::table('damage_reports')
                ->where('id', $request->report_id)
                ->update([
                    'status' => 'destroy_completed',
                    'updated_at' => now(),
                ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getProduct(Request $request)
    {
        $product = DB::table('products')
            ->where('product_code', $request->code)
            ->first();

        if (!$product) {
            return response()->json(null);
        }

        return response()->json([
            'product_name' => $product->product_name,
            'price' => $product->price
        ]);
    }

    public function getEmployee(Request $request)
    {
        $emp = DB::table('employees')
            ->where('emp_code', $request->code)
            ->first();

        if (!$emp) {
            return response()->json(null);
        }

        return response()->json([
            'emp_code' => $emp->emp_code,
            'emp_name' => $emp->emp_name
        ]);
    }

    // หน้า manager
    public function managerApprove()
    {
        $reports = DB::table('damage_reports')
            ->where('status', 'pending') // รอ manager อนุมัติ
            ->get();

        return view('manager-approve', compact('reports'));
    }

    // action approve / reject
    public function approveAction(Request $request)
    {
        try {

            $report = DB::table('damage_reports')
                ->where('id', $request->id)
                ->first();

            if (!$report) {
                return response()->json([
                    'success' => false,
                    'error' => 'ไม่พบข้อมูล'
                ]);
            }

            if ($request->action == 'approved_manager') {

                $data = [
                    'status' => 'approved_manager',
                    'approved_manager_by' => auth()->id(),
                    'approved_manager_at' => now(),
                    'updated_at' => now(),
                ];

                // ✅ เฉพาะ discount เท่านั้น
                if ($report->flow_type == 'discount') {
                    $data['manager_discount_percent'] = $request->manager_discount_percent ?? 0;
                }

                DB::table('damage_reports')
                    ->where('id', $request->id)
                    ->update($data);
            } elseif ($request->action == 'rejected') {

                DB::table('damage_reports')
                    ->where('id', $request->id)
                    ->update([
                        'status' => 'rejected',
                        'approve_remark' => $request->remark,
                        'updated_at' => now(),
                    ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // 📌 หน้า Admin
    public function adminApprove()
    {
        $reports = DB::table('damage_reports')
            ->where('status', 'approved_manager') // 🔥 ต้องเป็น process
            ->get();

        return view('admin-approve', compact('reports'));
    }

    // public function adminAction(Request $request)
    // {
    //     DB::table('damage_reports')
    //         ->where('id', $request->id)
    //         ->update([
    //             'status' => $request->action, // approved หรือ rejected
    //             'approve_remark' => $request->remark,
    //             'approved_by' => auth()->id(),
    //             'approved_at' => now(),
    //         ]);

    //     return response()->json(['success' => true]);
    // }
    public function adminAction(Request $request)
    {
        try {

            if ($request->action == 'approved') {

                $report = DB::table('damage_reports')
                    ->where('id', $request->id)
                    ->first();

                if (!$report) {
                    return response()->json([
                        'success' => false,
                        'error' => 'ไม่พบข้อมูล'
                    ]);
                }

                // 🔥 กรณี discount
                if ($report->flow_type == 'discount') {

                    if (!is_numeric($request->manager_discount_percent)) {
                        return response()->json([
                            'success' => false,
                            'error' => 'กรุณาระบุ % ลดราคา'
                        ]);
                    }

                    $percent = $request->manager_discount_percent; // ✅ แก้ตรงนี้

                    $finalPrice = $report->total_amount
                        - ($report->total_amount * $percent / 100);

                    DB::table('damage_reports')
                        ->where('id', $request->id)
                        ->update([
                            'status' => 'waiting_branch_print',
                            'discount_percent' => $percent,
                            'final_price' => $finalPrice,
                            'approved_admin_by' => auth()->id(),
                            'approved_admin_at' => now(),
                            'updated_at' => now(),
                        ]);
                } elseif ($report->flow_type == 'claim') {

                    DB::table('damage_reports')
                        ->where('id', $request->id)
                        ->update([
                            'status' => 'waiting_claim_result', // 🔥 ส่งไป Claim Follow
                            'approved_admin_by' => auth()->id(),
                            'approved_admin_at' => now(),
                            'updated_at' => now(),
                        ]);
                } else {

                    // 🔥 กรณี destroy
                    DB::table('damage_reports')
                        ->where('id', $request->id)
                        ->update([
                            'status' => 'waiting_branch_sap',
                            'approved_admin_by' => auth()->id(),
                            'approved_admin_at' => now(),
                            'updated_at' => now(),
                        ]);
                }
            } elseif ($request->action == 'rejected') {

                DB::table('damage_reports')
                    ->where('id', $request->id)
                    ->update([
                        'status' => 'rejected',
                        'approve_remark' => $request->remark,
                        'updated_at' => now(),
                    ]);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function destroyForm($id)
    {
        $report = DB::table('damage_reports')->where('id', $id)->first();


        return view('destroy-form', compact('report'));
    }

    public function destroyList()
    {
        $reports = DB::table('damage_reports')
            ->where('status', 'hr_done')
            ->where('flow_type', 'destroy') // 🔥 เพิ่มตรงนี้
            ->get();

        return view('destroy-list', compact('reports'));
    }

    public function destroyPrint($id)
    {
        $report = DB::table('damage_reports')->where('id', $id)->first();

        $items = DB::table('damage_report_items')
            ->where('damage_report_id', $id)
            ->get();

        $destroy = DB::table('damage_destroy')
            ->where('damage_report_id', $id)
            ->first();

        return view('destroy-print', compact('report', 'items', 'destroy'));
    }


    public function branchSap()
    {
        $reports = DB::table('damage_reports')
            ->where('status', 'waiting_branch_sap')
            ->where('flow_type', 'destroy')
            ->get();

        return view('branch-sap', compact('reports'));
    }

    public function branchSapSave(Request $request)
    {
        try {

            if (!$request->sap_doc || !$request->sap_date) {
                return response()->json([
                    'success' => false,
                    'error' => 'กรุณากรอก SAP Doc และวันที่'
                ]);
            }

            // ✅ 1. update SAP
            DB::table('damage_reports')
                ->where('id', $request->id)
                ->update([
                    'sap_doc' => $request->sap_doc,
                    'sap_date' => $request->sap_date,
                    'sap_by' => auth()->id(),
                    'status' => 'sap_completed',
                    'updated_at' => now(),
                ]);

            // 🔥 2. ดึงรายการสินค้า
            $items = DB::table('damage_report_items')
                ->where('damage_report_id', $request->id)
                ->get();

            // 🔥 3. บันทึก stock movement (551)
            foreach ($items as $item) {
                DB::table('stock_movements')->insert([
                    'product_code' => $item->product_code,
                    'qty' => $item->qty,
                    'movement_type' => '551',
                    'ref_doc' => $request->sap_doc,
                    'created_at' => now()
                ]);
            }

            // 🔥 เช็คพนักงาน
            $hasEmployee = DB::table('damage_report_employees')
                ->where('damage_report_id', $request->id)
                ->exists();

            $report = DB::table('damage_reports')
                ->where('id', $request->id)
                ->first();

            // 🔥 set status
            if ($report->flow_type == 'claim') {
                $status = 'waiting_claim_result'; // 🔥 เพิ่ม claim flow
            } else {
                $status = $hasEmployee ? 'accounting_done' : 'hr_done';
            }

            DB::table('damage_reports')
                ->where('id', $request->id)
                ->update([
                    'status' => $status,
                    'updated_at' => now(),
                ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function hrApprove()
    {
        $reports = DB::table('damage_reports')
            ->where('status', 'accounting_done')
            ->get();

        return view('hr-approve', compact('reports'));
    }

    public function hrSave(Request $request)
    {
        DB::beginTransaction();

        try {

            // 🔹 ดึงพนักงาน
            $employees = DB::table('damage_report_employees')
                ->where('damage_report_id', $request->id)
                ->get();

            foreach ($employees as $emp) {

                // ✅ บันทึกหักเงินเดือน
                DB::table('salary_deductions')->insert([
                    'emp_code' => $emp->emp_code,
                    'amount' => $emp->amount,
                    'ref_doc' => $request->doc_no,
                    'created_at' => now()
                ]);
            }

            // 🔥 update status
            DB::table('damage_reports')
                ->where('id', $request->id)
                ->update([
                    'status' => 'hr_done',
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function discountList()
    {
        $reports = DB::table('damage_reports')
            ->where('flow_type', 'discount')
            ->where('status', 'waiting_branch_print')
            ->get();

        return view('discount-list', compact('reports'));
    }

    public function discountPrint($id)
    {
        $report = DB::table('damage_reports')->where('id', $id)->first();

        $items = DB::table('damage_report_items')
            ->where('damage_report_id', $id)
            ->get();

        return view('discount-print', compact('report', 'items'));
    }

    public function discountPrintSave(Request $request)
    {
        DB::table('damage_reports')
            ->where('id', $request->id)
            ->update([
                'printed_at' => now(),
                'printed_by' => auth()->id(),
                'status' => 'completed'
            ]);

        return response()->json(['success' => true]);
    }

    public function claimFollow()
    {
        $reports = DB::table('damage_reports')
            ->where('status', 'waiting_claim_result')
            ->get();

        return view('claim.follow', compact('reports'));
    }

    public function claimAction(Request $request)
    {
        $report = DB::table('damage_reports')->where('id', $request->id)->first();

        if (!$report) {
            return response()->json(['error' => 'ไม่พบข้อมูล'], 404);
        }

        DB::beginTransaction();

        try {

            // ✅ เครมได้
            if ($request->action == 'claim_approved') {

                // 1. update report
                DB::table('damage_reports')
                    ->where('id', $request->id)
                    ->update([
                        'status' => 'waiting_accounting',
                        'cn_number' => $request->cn_no,
                        'updated_at' => now()
                    ]);

                // 2. 🔥 insert claim follow
                DB::table('claim_follows')->insert([
                    'report_id'   => $request->id,
                    'claim_status' => 'claimed',
                    'cn_number'   => $request->cn_no,
                    'remark'      => 'เคลมสำเร็จ',
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);

                // 🔥 log
                DB::table('damage_report_logs')->insert([
                    'report_id' => $request->id,
                    'action' => 'claim_approved',
                    'remark' => 'CN: ' . $request->cn_no,
                    'created_at' => now()
                ]);
            }

            // ❌ เครมไม่ได้
            if ($request->action == 'claim_rejected') {

                DB::table('damage_reports')
                    ->where('id', $request->id)
                    ->update([
                        'status' => 'waiting_close_by_executive',
                        'updated_at' => now()
                    ]);

                DB::table('claim_follows')->insert([
                    'report_id'    => $request->id,
                    'claim_status' => 'not_claimed',
                    'remark'       => $request->remark,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ]);

                DB::table('damage_report_logs')->insert([
                    'report_id' => $request->id,
                    'action' => 'claim_rejected',
                    'remark' => $request->remark,
                    'created_at' => now()
                ]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function accounting()
    {
        $reports = DB::table('damage_reports')
            ->where('status', 'waiting_accounting')
            ->get();

        return view('accounting', compact('reports'));
    }

    public function accountingSave(Request $request)
    {
        DB::beginTransaction();

        try {

            DB::table('damage_reports')
                ->where('id', $request->id)
                ->update([
                    'sap_doc' => $request->sap_doc,
                    'status' => 'completed',
                    'updated_at' => now()
                ]);

            DB::table('damage_report_logs')->insert([
                'report_id' => $request->id,
                'action' => 'accounting_saved',
                'remark' => 'SAP: ' . $request->sap_doc,
                'created_at' => now()
            ]);

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function closeCase(Request $request)
    {
        DB::table('damage_reports')
            ->where('id', $request->id)
            ->update([
                'status' => 'closed',
                'updated_at' => now()
            ]);

        DB::table('damage_report_logs')->insert([
            'report_id' => $request->id,
            'action' => 'closed',
            'remark' => 'ปิดเคสโดยผู้บริหาร',
            'created_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function getDetail(Request $request)
    {
        $report = DamageReport::with(['items', 'employees'])->find($request->id);

        return response()->json($report);
    }
}

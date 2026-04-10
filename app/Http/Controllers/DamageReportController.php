<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DamageReport;

class DamageReportController extends Controller
{
    public function index()
    {
        $total = DamageReport::count();

        $pending = DamageReport::where('status', 'pending')->count();

        $process = DamageReport::whereIn('status', [
            'approved_manager',
            'waiting_branch_sap',
            'sap_completed',
            'accounting_done',
            'hr_done'
        ])->count();

        $success = DamageReport::where('status', 'destroy_completed')->count();

        $reports = DamageReport::latest()->get();

        return view('home', compact(
            'total',
            'pending',
            'process',
            'success',
            'reports'
        ));
    }
    // public function index()
    // {
    //     $reports = DB::table('damage_reports')->get();
    //     return view('home', compact('reports'));
    // }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            // ✅ generate doc_no
            $today = date('Ymd');

            $last = DB::table('damage_reports')
                ->whereDate('created_at', date('Y-m-d'))
                ->where('doc_no', 'like', 'DR' . $today . '%')
                ->orderBy('doc_no', 'desc')
                ->first();

            $running = $last ? intval(substr($last->doc_no, -4)) + 1 : 1;

            $doc_no = 'DR' . $today . str_pad($running, 4, '0', STR_PAD_LEFT);


            // 1. บันทึก header
            $reportId = DB::table('damage_reports')->insertGetId([
                'doc_no' => $doc_no, // ✅ ใช้ตัวใหม่
                'branch_code' => $request->branch_code, // ✅ แก้จาก branch_desc
                'report_type' => $request->report_type,
                'flow_type' => $request->flow_type ?? 'destroy',
                'product_type' => $request->product_type,
                'damage_reason' => $request->damage_reason,
                'total_amount' => $request->total_amount,
                'status' => 'pending',
                'created_by' => auth()->id() ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. บันทึกสินค้า
            if (is_array($request->items)) {
                foreach ($request->items as $item) {
                    DB::table('damage_report_items')->insert([
                        'damage_report_id' => $reportId,
                        'product_code' => $item['product_code'] ?? '',
                        'product_name' => $item['product_name'] ?? '',
                        'price' => $item['price'] ?? 0,
                        'qty' => $item['qty'] ?? 0,
                        'total' => $item['total'] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // 3. บันทึกพนักงาน
            if (is_array($request->employees)) {
                foreach ($request->employees as $emp) {
                    DB::table('damage_report_employees')->insert([
                        'damage_report_id' => $reportId,
                        'emp_code' => $emp['emp_code'] ?? '',
                        'emp_name' => $emp['emp_name'] ?? '',
                        'percent' => $emp['percent'] ?? 0,
                        'amount' => $emp['amount'] ?? 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
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

                    if (!$request->discount_percent) {
                        return response()->json([
                            'success' => false,
                            'error' => 'กรุณาระบุ % ลดราคา'
                        ]);
                    }

                    $percent = $request->discount_percent;

                    // 🔥 คำนวณราคาหลังลด
                    $finalPrice = $report->total_amount
                        - ($report->total_amount * $percent / 100);

                    DB::table('damage_reports')
                        ->where('id', $request->id)
                        ->update([
                            'status' => 'waiting_branch_print',
                            'discount_percent' => $percent, // ✅ เพิ่ม
                            'final_price' => $finalPrice,           // ✅ เพิ่ม
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

            // 🔥 set status
            $status = $hasEmployee ? 'accounting_done' : 'hr_done';

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
}

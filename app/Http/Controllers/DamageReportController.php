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
        $process = DamageReport::where('status', 'process')->count();
        $success = DamageReport::where('status', 'success')->count();

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
                'product_type' => $request->product_type,
                'damage_reason' => $request->damage_reason,
                'total_amount' => $request->total_amount,
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
            DB::table('damage_reports')
                ->where('id', $request->id)
                ->update([
                    'status' => $request->action, // success หรือ rejected
                    'approve_remark' => $request->remark,
                    'sap_doc' => $request->sap_doc,
                    'sap_date' => $request->sap_date,
                    'sap_by' => auth()->id(),
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

    // 📌 หน้า Admin
    public function adminApprove()
    {
        $reports = DB::table('damage_reports')
            ->where('status', 'process') // 🔥 ต้องเป็น process
            ->get();

        return view('admin-approve', compact('reports'));
    }

    public function adminAction(Request $request)
    {
        DB::table('damage_reports')
            ->where('id', $request->id)
            ->update([
                'status' => $request->action, // approved หรือ rejected
                'approve_remark' => $request->remark,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    public function destroyForm($id)
    {
        $report = DB::table('damage_reports')->where('id', $id)->first();

        return view('destroy-form', compact('report'));
    }
}

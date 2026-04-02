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

            // 1. บันทึก header
            $reportId = DB::table('damage_reports')->insertGetId([
                'doc_no' => 'DR' . date('YmdHis'),
                'branch_code' => $request->branch_code,
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
}

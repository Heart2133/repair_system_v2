<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamageReportController extends Controller
{
    public function index()
{
    $reports = DB::table('damage_reports')->get();
    return view('damage.index', compact('reports'));
}

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
            'created_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. บันทึกสินค้า
        foreach ($request->items as $item) {
            DB::table('damage_report_items')->insert([
                'damage_report_id' => $reportId,
                'product_code' => $item['product_code'],
                'product_name' => $item['product_name'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'total' => $item['total'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. บันทึกพนักงาน
        foreach ($request->employees as $emp) {
            DB::table('damage_report_employees')->insert([
                'damage_report_id' => $reportId,
                'emp_code' => $emp['emp_code'],
                'emp_name' => $emp['emp_name'],
                'percent' => $emp['percent'],
                'amount' => $emp['amount'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
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

    return response()->json($product);
}

public function getEmployee(Request $request)
{
    $emp = DB::table('users')
        ->where('username', $request->code)
        ->first();

    return response()->json($emp);
}
}

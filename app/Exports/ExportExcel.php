<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Requests;

class ExportExcel implements WithMultipleSheets
{
    protected $branch_code;
    protected $s_id;
    protected $start_date;
    protected $end_date;

    public function __construct(?string $branch_code, ?string $s_id, ?string $start_date, ?string $end_date)
    {
        $this->branch_code = $branch_code ?? 'ALL';
        $this->s_id = $s_id ?? 'ALL';
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function sheets(): array
    {
        $sheets = [];

        // ✅ นับจำนวนข้อมูลทั้งหมด
        $totalCount = Requests::whereBetween('r_date', [$this->start_date, $this->end_date])->count();

        // ✅ เปลี่ยนชื่อแท็บ "All Data (จำนวนทั้งหมด)"
        $sheets[] = new RequestsSheet($this->branch_code, $this->s_id, $this->start_date, $this->end_date, "ทั้งหมด ({$totalCount})");

        // ✅ ถ้าเลือก "ALL" ให้เพิ่ม 10 แท็บแยกตาม `s_id`
        if ($this->s_id == 'ALL') {
            $s_id_labels = [
                "0 ยกเลิก",
                "1 รับแจ้งซ่อม",
                "2 รอส่งซ่อม",
                "3 ส่งให้ VD",
                "4 เสนอราคา",
                "5 ผลการอนุมัติ",
                "6 แจ้งผล VD",
                "7 VD ส่งคืน",
                "8 รอลูกค้ามารับ",
                "9 ลูกค้ามารับแล้ว"
            ];

            for ($i = 0; $i <= 9; $i++) {
                // นับจำนวนข้อมูลของแต่ละ s_id
                $count = Requests::where('s_id', $i)
                    ->whereBetween('r_date', [$this->start_date, $this->end_date])
                    ->count();

                // ✅ เปลี่ยนชื่อแท็บ เช่น "1 รับแจ้งซ่อม (50)"
                $sheetName = "{$s_id_labels[$i]} ({$count})";

                $sheets[] = new RequestsSheet($this->branch_code, $i, $this->start_date, $this->end_date, $sheetName);
            }
        }

        return $sheets;
    }
}

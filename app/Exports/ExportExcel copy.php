<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Requests;

class ExportExcel implements FromCollection, WithHeadings
{
    protected ?string $branch_code;
    protected ?string $s_id;
    protected ?string $start_date;
    protected ?string $end_date;

    // ✅ รับค่าพารามิเตอร์และรองรับค่า null
    public function __construct(?string $branch_code, ?string $s_id, ?string $start_date, ?string $end_date)
    {
        $this->branch_code = $branch_code ?? 'ALL';
        $this->s_id = $s_id ?? 'ALL';
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        $query = Requests::query();

        // ✅ ตรวจสอบก่อนใช้ whereBetween
        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereBetween('r_date', [$this->start_date, $this->end_date]);
        }

        // ✅ ตรวจสอบ branch_code
        if ($this->branch_code !== 'ALL') {
            $query->where('r_branch', $this->branch_code);
        }

        // ✅ ตรวจสอบ s_id
        if ($this->s_id !== 'ALL') {
            $query->where('s_id', $this->s_id);
        }

        // ✅ ดึงเฉพาะคอลัมน์ที่ต้องการ
        $data = $query->get(['r_id']);

        return $data;
    }

//     public function collection()
// {
//     $query = Requests::query();

//     // ตรวจสอบค่าที่ส่งมา
//     dd($this->start_date, $this->end_date);

//     if (!empty($this->start_date) && !empty($this->end_date)) {
//         $query->whereBetween('r_date', [$this->start_date, $this->end_date]);
//     }

//     if ($this->branch_code !== 'ALL') {
//         $query->where('r_branch', $this->branch_code);
//     }

//     if ($this->s_id !== 'ALL') {
//         $query->where('s_id', $this->s_id);
//     }

//     // แสดง Query ที่ Laravel ใช้
//     dd($query->toSql(), $query->getBindings());

//     // ดึงข้อมูลจากฐานข้อมูล
//     $data = $query->get(['r_id']);

//     // ตรวจสอบข้อมูลที่ได้ก่อนส่งออก Excel
//     dd($data);

//     return $data;
// }


    public function headings(): array
    {
        return [
            'เลขที่เอกสาร',
        ];
    }
}

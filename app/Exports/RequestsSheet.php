<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Requests;
use App\Models\Vendor;
// use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RequestsSheet implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    protected $branch_code;
    protected $s_id;
    protected $start_date;
    protected $end_date;
    protected $sheetName;

    public function __construct($branch_code, $s_id, $start_date, $end_date, $sheetName)
    {
        $this->branch_code = $branch_code;
        $this->s_id = $s_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->sheetName = $sheetName;
    }

    public function collection()
    {
        $query = Requests::query()
            ->leftJoin('tb_vendor', 'tb_request.v_id', '=', 'tb_vendor.v_id') // ✅ Join เพื่อดึง v_name
            ->leftJoin('tb_brand', 'tb_request.brand_id', '=', 'tb_brand.brand_id') // ✅ Join เพื่อดึง v_name
            // ->leftJoin('tb_status', 'tb_request.s_id', '=', 'tb_status.id') // ✅ Join เพื่อดึง s_id
            ->select([
                'tb_request.r_id',
                // 'tb_status.id', // ✅ แสดงค่า s_desc แทน s_id
                'tb_request.r_date',
                // 'tb_request.r_tmp_docno',
                'tb_request.r_branch',
                'tb_request.r_approve',
                'tb_request.c_name',
                'tb_request.c_tel',
                'tb_brand.brand_desc',
                'tb_request.i_name',
                'tb_request.i_detail1',
                'tb_request.i_warranty',
                'tb_request.i_contry',
                'tb_vendor.v_name', // ✅ Vendor Name
                'tb_request.qt_no',
                'tb_request.po_no',
                'tb_request.request_price',
                'tb_request.receipt_no',
                // 'tb_request.status_v_tel',
                'tb_request.trans_active',
                'tb_request.create_by',
            ]);

        // กรองวันที่
        if (!empty($this->start_date) && !empty($this->end_date)) {
            $query->whereBetween('tb_request.r_date', [$this->start_date, $this->end_date]);
        }

        // ถ้าเลือกเฉพาะ branch
        if ($this->branch_code !== 'ALL') {
            $query->where('tb_request.r_branch', $this->branch_code);
        }

        // ถ้าเป็น s_id ที่เจาะจง
        if ($this->s_id !== 'ALL') {
            $query->where('tb_request.s_id', $this->s_id);
        }

        // ✅ ใช้ map() เพื่อแปลงค่า r_approve เป็น "อนุมัติ" หรือ "ไม่อนุมัติ"
        return $query->get()->map(function ($item) {
            $item->r_approve = ($item->r_approve == 'Y') ? 'อนุมัติ' : 
                       (($item->r_approve == 'N') ? 'ไม่อนุมัติ' : '');
            $item->i_warranty = ($item->i_warranty == 'Y') ? 'ในประกัน' : 'นอกประกัน';
            $item->i_contry = ($item->i_contry == 'Y') ? 'ในประเทศ' : 'นอกประเทศ';
            // $item->trans_active = ($item->trans_active == 'Y') ? 'ส่งคลัง' : 'ส่ง Vendor';
            $item->trans_active = ($item->trans_active == 'Y') ? 'ส่งคลัง' : 
                       (($item->trans_active == 'N') ? 'ส่ง Vendor' : '');
            return $item;
        });
    }

    // ✅ กำหนดชื่อหัวข้อ (Header)
    public function headings(): array
    {
        return [
            'เลขที่ใบซ่อม',
            // 'สถานะ',
            'วันที่เอกสาร',
            // 'เอกสาร',
            'สาขา',
            'ผลการอนุมัติ',
            'ชื่อลูกค้า',
            'เบอร์โทรลูกค้า',
            'ยี่ห้อสินค้า',
            'ชื่อสินค้า',
            'อาการเสีย',
            'การรับประกัน',
            'ประเภทสินค้า',
            'Vendor',
            'เลขที่ใบเสนอราคา',
            'เลขที่ PO',
            'ราคาซ่อม',
            'เลขที่ใบเสร็จ',
            // 'โทรแจ้ง Vendor',
            'สาขาปลายทาง',
            'ผู้รับแจ้งซ่อม',
        ];
    }

    // ✅ กำหนดชื่อแท็บ
    public function title(): string
    {
        return $this->sheetName;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // แถวที่ 1 (Header)
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '44C8D7'] // พื้นหลังสีน้ำเงิน
                ],
            ],
        ];
    }
}

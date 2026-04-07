<!DOCTYPE html>
<html>
<head>
    <title>เอกสารทำลายสินค้า</title>
    <style>
        body { font-family: Tahoma; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; text-align: left; }
    </style>
</head>
<body onload="window.print()">

    <h3 style="text-align:center;">เอกสารแจ้งนำสินค้าออกไปทำลาย</h3>

    <p>เลขที่เอกสาร: {{ $report->doc_no }}</p>
    <p>สาขา: {{ $report->branch_code }}</p>
    <p>วันที่ทำลาย: {{ $destroy->destroy_date ?? '-' }}</p>
    <p>สถานที่: {{ $destroy->location ?? '-' }}</p>

    <h4>รายการสินค้า</h4>
    <table>
        <thead>
            <tr>
                <th>รหัส</th>
                <th>ชื่อสินค้า</th>
                <th>จำนวน</th>
                <th>ราคา</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $i)
            <tr>
                <td>{{ $i->product_code }}</td>
                <td>{{ $i->product_name }}</td>
                <td>{{ $i->qty }}</td>
                <td>{{ number_format($i->price,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>

    <table>
        <tr>
            <td style="height:80px;">ผู้ดำเนินการ</td>
            <td>LP ตรวจสอบ</td>
        </tr>
    </table>

</body>
</html>
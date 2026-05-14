<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>
        เอกสารทำลายสินค้า
    </title>

    <style>
        @font-face {
            font-family: 'THSarabun';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}")
                format('truetype');
        }

        @font-face {
            font-family: 'THSarabun';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNewBold.ttf') }}")
                format('truetype');
        }

        body {
            font-family: 'THSarabun';
            font-size: 22px;
            margin: 30px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0;
            font-size: 34px;
            font-weight: bold;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .info-table td {
            padding: 6px 0;
            vertical-align: top;
        }

        .info-table .label {
            width: 180px;
            font-weight: bold;
        }

        .section-title {
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 26px;
            font-weight: bold;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .product-table th {
            border: 1px solid #000;
            background-color: #f2f2f2;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        .product-table td {
            border: 1px solid #000;
            padding: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .signature-table {
            width: 100%;
            margin-top: 80px;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-line {
            margin-top: 80px;
        }

        .footer-note {
            margin-top: 40px;
            font-size: 18px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">

        <h2>
            เอกสารแจ้งนำสินค้าออกไปทำลาย
        </h2>

    </div>

    {{-- INFO --}}
    <table class="info-table">

        <tr>

            <td class="label">
                เลขที่เอกสาร
            </td>

            <td>
                {{ $report->doc_no }}
            </td>

        </tr>

        <tr>

            <td class="label">
                สาขา
            </td>

            <td>
                {{ $report->branch_code }}
            </td>

        </tr>

        <tr>

            <td class="label">
                วันที่ทำลาย
            </td>

            <td>
                {{ $destroy->destroy_date ?? '-' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                สถานที่
            </td>

            <td>
                {{ $destroy->location ?? '-' }}
            </td>

        </tr>

        <tr>

            <td class="label">
                หมายเหตุ
            </td>

            <td>
                {{ $destroy->remark ?? '-' }}
            </td>

        </tr>

    </table>

    {{-- PRODUCT --}}
    <div class="section-title">

        รายการสินค้า

    </div>

    <table class="product-table">

        <thead>

            <tr>

                <th width="20%">
                    รหัสสินค้า
                </th>

                <th width="40%">
                    ชื่อสินค้า
                </th>

                <th width="20%">
                    จำนวน
                </th>

                <th width="20%">
                    ราคา
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse ($items as $i)

                <tr>

                    <td>
                        {{ $i->product_code }}
                    </td>

                    <td>
                        {{ $i->product_name }}
                    </td>

                    <td class="text-center">
                        {{ number_format($i->qty) }}
                    </td>

                    <td class="text-right">
                        {{ number_format($i->price, 2) }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="4" class="text-center">
                        ไม่มีรายการสินค้า
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

    {{-- SIGNATURE --}}
    <table class="signature-table">

        <tr>

            <td>

                <div class="signature-line">
                    ..............................................
                </div>

                <div>
                    ผู้ดำเนินการ
                </div>

            </td>

            <td>

                <div class="signature-line">
                    ..............................................
                </div>

                <div>
                    LP ตรวจสอบ
                </div>

            </td>

        </tr>

    </table>

</body>

</html>
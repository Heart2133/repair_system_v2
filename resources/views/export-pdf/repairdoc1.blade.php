<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>เอกสารรับแจ้งซ่อม</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            src: url('{{ asset('fonts/THSarabunNew.ttf') }}') format('truetype');
        }

        body {
            font-family: 'THSarabunNew', sans-serif;
        }

        th {
            font-weight: normal;
            /* หรือ 400 */
        }
    </style>

    <style>
        .triangle {
            position: absolute;
            top: 0;
            right: 0;
            width: 0;
            height: 0;
            border-left: 74px solid transparent;
            border-top: 74px solid #1053A0FF;
            /* สีน้ำเงิน */
        }


        .header-container {
            position: relative;
            width: 100%;
        }
    </style>
</head>

<body>
    <main>
        <div height='490px'>
            {{-- <div class="header-container"> --}}
            <div class="triangle"></div> <!-- สามเหลี่ยมสีน้ำเงิน -->
            <table cellSpacing=0 cellPadding=0 border=0 width='100%' style=''>
                <img class="img-rounded LOGO" src="{{ URL::asset('/assets/images/logo-hwh.png') }}" width="170px">
                <tr>
                    <td colspan=2 style='font-size: 15px; width: 100%;'>
                        <table style='font-size:17px; width: 100%;'>
                            <tr>
                                <td style=" width: 100%;" colspan='5'>
                                    {{ $requests->company_name }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan='5' style="">
                                    {{ $requests->company_addr }}
                                </td>
                            </tr>
                            <tr>
                                <td>โทรศัพท์ {{ $requests->company_tel }} &nbsp; โทรสาร {{ $requests->company_fax }}
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td width='20%' valign=top align=right style='font-size: 21px;'>
                        <table align=right>
                            <tr>
                                <td style='font-size: 25px;' align=right>ใบรับแจ้งซ่อม</td>
                            </tr>
                            <tr>
                                <td style='font-size: 19px;' align=right>(สำหรับลูกค้า)</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            {{-- </div> --}}


            <table width='100%' style='font-size: 17px; '>
                <tr>
                    <td>
                        <table width='100%' style='font-size: 17px; '>
                            <tr>
                                <td style=>ชื่อลูกค้า :</td>
                                <td>{{ $requests->c_name }}</td>
                            </tr>
                            <tr>
                                <td style=>
                                    เบอร์ติดต่อ :</td>
                                <td>{{ $requests->c_tel }}</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table width='100%' style='font-size: 17px; '>
                            <tr>
                                <td style=>เลขที่เอกสาร :</td>
                                <td>{{ $requests->r_id }}</td>
                            </tr>

                            <tr>
                                <td style=>
                                    วันที่เอกสาร :</td>
                                <td>{{ $requests->r_date }}</td>
                            </tr>
                        </table>
                    </td>
                    <td align='right'>
                        <table height="100%" width='100%' style='font-size: 17px; '>
                            <tr>
                                <td>
                                    <div style="float:right; margin-top:-20px">{!! DNS1D::getBarcodeHTML($requests->r_id, 'C39', 1.2) !!} </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div style="margin-left:80%; font-size: 15px; margin-top:-30px; margin-bottom:5px"> {{ $requests->r_id }}
            </div>

            {{-- 
            <table width='100%' border="1" style='font-size: 16px;border-collapse: collapse;'>
                <tbody>
                    <tr style="background:rgb(234, 234, 234);border-color: #dee2e6;">
                        <th style='padding:5px;' width='5%'>No.</th>
                        <th style='padding:5px;' width='58%' align='left'>รายการสินค้าซ่อม</th>
                        <th style='padding:5px;' width='20%' align='left'>แบรนด์</th>
                        <th style='padding:5px;' width='17%' align='left'>การรับประกัน</th>
                    </tr>
                    <tr style="border-bottom: 2px solid #dee2e6;">
                        <td style='padding:5px;' align='center'>1</td>
                        <td style='padding:5px;' align='left'>{{ $requests->i_name }}</td>
                        <td style='padding:5px;' align='left'>{{ $requests->brand_desc }}</td>
                        <td style='padding:5px;' align='left'>{{ $requests->warranty }}</td>
                    </tr>
                </tbody>
            </table> --}}

            <table width="100%" style="font-size: 16px; border-collapse: collapse;">
                <tbody>
                    <!-- Header -->
                    <tr style="background: rgb(234, 234, 234); border-bottom: 1px solid #dee2e6;">
                        <th style="padding-bottom: 5px;  border-bottom: 1px solid #dee2e6;" width="5%">No.</th>
                        <th style="padding-bottom: 5px;  border-bottom: 1px solid #dee2e6;" align='left'
                            width="58%">รายการสินค้าซ่อม</th>
                        <th style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='left'
                            width="20%">แบรนด์</th>
                        <th style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='left'
                            width="17%">การรับประกัน</th>
                    </tr>

                    <!-- Data Row -->
                    <tr style="border-bottom: 1px solid #dee2e6;">
                        <td style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='center'>1</td>
                        <td style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='left'>
                            {{ $requests->i_name }}</td>
                        <td style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='left'>
                            {{ $requests->brand_desc }}</td>
                        <td style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='left'>
                            {{ $requests->warranty }}</td>
                    </tr>
                </tbody>
            </table>

            <table width='100%' style='font-size: 17px; '>
                <tr>
                    <td style=''>รายละเอียดสินค้า / อาการเสีย / หมายเหตุอื่น ๆ</td>
                </tr>
                <tr>
                    <td valign=top width="50%"  style='font-size: 15px;'>{{ strip_tags($requests->i_detail1) }}</td>
                </tr>
            </table>
            <table width='100%' style='font-size: 17px; '>
                <tr>
                    <td>เงื่อนไขการบริการ</td>
                </tr>
                <tr>
                    <td valign=top>
                        <table width='100%' style="border-collapse: collapse;font-size: 15px;">
                            <tr>
                                <td style="vertical-align: top;">1.</td>
                                <td style="padding-left: 10px;">บริษัทฯ รับประกันการซ่อมสินค้าเป็นระยะเวลา 30 วัน
                                    นับตั้งแต่วันที่ท่านมารับสินค้าซ่อมคืน โดยอาการเสียจะต้องเป็นอาการเดิม</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">2.</td>
                                <td style="padding-left: 10px;">กรุณานำใบรับแจ้งซ่อมมาติดต่อรับสินค้าซ่อมคืน บริษัทฯ
                                    ขอสงวนสิทธิ์ไม่จ่ายสินค้าซ่อมคืนหากท่านไม่นำใบรับแจ้งซ่อมมาด้วย</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">3.</td>
                                <td style="padding-left: 10px;">ค่าบริการซ่อมจะเปลี่ยนแปลงตามนโยบายที่บริษัทฯ กำหนด
                                    กรุณาสอบถามราคาจากเจ้าหน้าที่รับซ่อมทุกครั้ง</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">4.</td>
                                <td style="padding-left: 10px;">กรณีที่มีค่าอะไหล่และอุปกรณ์ที่ต้องเปลี่ยน
                                    ทางเจ้าหน้าที่จะติดต่อท่าน
                                    เพื่อแจ้งราคาและขอคำยินยอมการซ่อมจากท่านทุกครั้งก่อนดำเนินการซ่อม</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top;">5.</td>
                                <td style="padding-left: 10px;">กรณีที่ท่านไม่มาติดต่อรับสินค้าซ่อมคืนภายในระยะเวลา 6
                                    เดือน
                                    นับตั้งแต่วันที่ทางเจ้าหน้าที่ติดต่อให้มารับ บริษัทฯ
                                    ขอสงวนสิทธิ์ยกเลิกรายการในระบบและนำสินค้าซ่อมไปทำลายต่อไป</td>
                            </tr>
                        </table>

                    </td>
                </tr>
            </table>
            <table width='100%' style='font-size: 17px;  margin-top:10px ;'>
                <tr>
                    <td width='50%'  align='center'>
                        <table width='100%' style='font-size: 17px; '>
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height='30px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height='30px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height='30px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center'>ผู้รับแจ้งซ่อม (CS)</td>
                                </tr>
                                <tr>
                                    <td align='center'>วันที่ ______/______/______</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width='50%'  align='center'>
                        <table width='100%' style='font-size: 17px; '>
                            <tbody>
                                <tr>
                                    <td height='30px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height='30px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center' style='font-size: 15px;'>
                                        ข้าพเจ้ารับทราบและยินดีปฏิบัติตามเงื่อนไขการบริการทุกประการลูกค้า</td>
                                </tr>
                                <tr>
                                    <td height='30px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='center'>ลูกค้า</td>
                                </tr>
                                <tr>
                                    <td align='center'>วันที่ ______/______/______</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- <tfoot>
                gwrgrwgwrg
            </tfoot> --}}
        </div>

        <footer style="position: fixed; bottom: 0; width: 100%; display: flex; justify-content: flex-end; padding: 0; margin: 0;">
            <div style="text-align: center; padding: 5px; width: 150px; margin-left: auto; margin-right: 0;">
                <img src="{{ URL::asset('/images/qrcodetracking.png') }}" width="100px" style="display: block; margin: 0 auto;">
                <p style="margin: 0; padding-top: 2px; font-size: 15px;">สแกน QR code เพื่อติดตามสถานะ</p>
            </div>
        </footer>

        <!-- <div style='border-style: dashed;border-width: 1px;'></div> -->
        <div style="page-break-after: always;"></div>

        <div class="triangle"></div> <!-- สามเหลี่ยมสีน้ำเงิน -->
        <table cellSpacing=0 cellPadding=0 border=0 width='100%' style=''>
            <img class="img-rounded LOGO" src="{{ URL::asset('/assets/images/logo-hwh.png') }}" width="170px">
            <tr>
                <td colspan=2 width='60%' style='font-size: 15px; '>
                    <table style='font-size:17px; '>
                        <tr>
                            <td colspan='2'>{{ $requests->company_name }}</td>
                        </tr>
                        <tr>
                            <td colspan='2'>{{ $requests->company_addr }}</td>
                        </tr>
                        <tr>
                            <td>โทรศัพท์ {{ $requests->company_tel }} &nbsp; โทรสาร {{ $requests->company_fax }}</td>
                        </tr>
                    </table>
                </td>
                <td width='20%' valign=top align=right style='font-size: 21px;'>
                    <table>
                        <tr>
                            <td style='font-size: 25px;' align=right>ใบรับแจ้งซ่อม</td>
                        </tr>
                        <tr>
                            <td style='font-size: 19px;' align=right>(สำหรับแผนกบริการลูกค้า)</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width='100%' style='font-size: 17px; '>
            <tr>
                <td>
                    <table width='100%' style='font-size: 17px; '>
                        <tr>
                            <td style=>ชื่อลูกค้า :</td>
                            <td>{{ $requests->c_name }}</td>
                        </tr>
                        <tr>
                            <td style=>
                                เบอร์ติดต่อ :</td>
                            <td>{{ $requests->c_tel }}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table width='100%' style='font-size: 17px; '>
                        <tr>
                            <td style=>เลขที่เอกสาร :</td>
                            <td>{{ $requests->r_id }}</td>
                        </tr>
                        <tr>
                            <td style=>
                                วันที่เอกสาร :</td>
                            <td>{{ $requests->r_date }}</td>
                        </tr>
                    </table>
                </td>
                <td align='right'>
                    <table height="100%" width='100%' style='font-size: 17px; '>
                        <tr>
                            <td>
                                <div style="float:right; margin-top:-20px">{!! DNS1D::getBarcodeHTML($requests->r_id, 'C39', 1.2) !!} </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="margin-left:80%; font-size: 15px; margin-top:-30px; margin-bottom:5px"> {{ $requests->r_id }}
        </div>
        {{-- 
        <table width='100%' border='1' style='font-size: 16px;border-collapse: collapse;'>
            <tbody>
                <tr>
                    <th style='padding:5px;' width='5%'>No.</th>
                    <th style='padding:5px;' width='58%'>รายการสินค้าซ่อม</th>
                    <th style='padding:5px;' width='20%'>แบรนด์</th>
                    <th style='padding:5px;' width='17%'>การรับประกัน</th>
                </tr>
                <tr>
                    <td style='padding:5px;' align='center'>1</td>
                    <td style='padding:5px;'>{{ $requests->i_name }}</td>
                    <td style='padding:5px;' align='center'>{{ $requests->brand_desc }}</td>
                    <td style='padding:5px;' align='center'>{{ $requests->warranty }}</td>
                </tr>
            </tbody>
        </table> --}}

        <table width="100%" style="font-size: 16px; border-collapse: collapse;">
            <tbody>
                <!-- Header -->
                <tr style="background: rgb(234, 234, 234); border-bottom: 1px solid #dee2e6;">
                    <th style="padding-bottom: 5px;  border-bottom: 1px solid #dee2e6;" width="5%">No.</th>
                    <th style="padding-bottom: 5px;  border-bottom: 1px solid #dee2e6;" align='left'
                        width="58%">รายการสินค้าซ่อม</th>
                    <th style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='left' width="20%">
                        แบรนด์</th>
                    <th style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='left' width="17%">
                        การรับประกัน</th>
                </tr>

                <!-- Data Row -->
                <tr style="border-bottom: 1px solid #dee2e6;">
                    <td style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='center'>1</td>
                    <td style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='left'>
                        {{ $requests->i_name }}</td>
                    <td style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='left'>
                        {{ $requests->brand_desc }}</td>
                    <td style="padding-bottom: 5px; border-bottom: 1px solid #dee2e6;" align='left'>
                        {{ $requests->warranty }}</td>
                </tr>
            </tbody>
        </table>

        <table width='100%' style='font-size: 17px; '>
            <tr>
                <td style=''>รายละเอียดสินค้า / อาการเสีย / หมายเหตุอื่น ๆ</td>
            </tr>
            <tr>
                <td valign=top style='font-size: 15px; '>{{ strip_tags($requests->i_detail1) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
        <table width='100%' style='font-size: 17px; '>
            <tr>
                <td width='50%' align='center'>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align='center'>ผู้รับแจ้งซ่อม (CS)</td>
                            </tr>
                            <tr>
                                <td align='center'>วันที่ ________/________/________</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width='50%' align='center'>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align='center' style='font-size: 15px;'>
                                    ข้าพเจ้ารับทราบและยินดีปฏิบัติตามเงื่อนไขการบริการทุกประการลูกค้า</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align='center'>ลูกค้า (ผู้ส่งซ่อมสินค้า)</td>
                            </tr>
                            <tr>
                                <td align='center'>วันที่ ________/________/________</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <table width='100%' style='font-size: 17px; '>
            <tr>
                <td width='50%' align='center'>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align='center'>O สินค้าซ่อมเรียบร้อย &nbsp;&nbsp; O ลูกค้าไม่ต้องการซ่อม</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align='center'>ผู้ส่งสินค้าซ่อมคืน (CS)</td>
                            </tr>
                            <tr>
                                <td align='center'>วันที่ ________/________/________</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width='50%' align='center'>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align='center' style='font-size: 15px;'>ข้าพเจ้าได้รับสินค้าซ่อมคืนในสภาพดี
                                    ครบถ้วน</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td height='30px'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td align='center'>ลูกค้า (ผู้รับสินค้าซ่อมคืน)</td>
                            </tr>
                            <tr>
                                <td align='center'>วันที่ ________/________/________</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>


        <div style="page-break-after: always;"></div>
        {{-- 
        



        --}}
        <div class="triangle"></div>
        <table cellSpacing=0 cellPadding=0 border=0 width='100%' style=''>
            <img class="img-rounded LOGO" src="{{ URL::asset('/assets/images/logo-hwh.png') }}" width="170px">
            <tr>
                <td colspan=2 width='60%' style='font-size: 15px; '>
                    <table style='font-size:17px; '>
                        <tr>
                            <td colspan='2'>{{ $requests->company_name }}</td>
                        </tr>
                        <tr>
                            <td colspan='2'>{{ $requests->company_addr }}</td>
                        </tr>
                        <tr>
                            <td>โทรศัพท์ {{ $requests->company_tel }} &nbsp; โทรสาร {{ $requests->company_fax }}</td>
                        </tr>
                    </table>
                </td>
                <td width='20%' valign=top align=right style='font-size: 21px;'>
                    <table align=right>
                        <tr>
                            <td style='font-size: 25px;' align=right>ใบรับแจ้งซ่อม</td>
                        </tr>
                        <tr>
                            <td style='font-size: 19px;' align=right>(สำหรับ Vender)</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan=3 bgcolor=#CCCCCC style='padding-top:2px;padding-bottom:2px;'></td>
            </tr>
        </table>
        <table style='font-size: 17px; '>
            <tr>
                <td valign=top>
                    <table style='font-size: 17px; '>
                        <tr>
                            <td width="80px" style="vertical-align: top;">ชื่อบริษัท :</td>
                            <td width="220px" style="">{{ $requests->v_name }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">
                                ผู้ติดต่อ :</td>
                            <td>{{ $requests->v_contact }}</td>
                        </tr>
                        <tr>
                            <td style="vertical-align: top;">เบอร์โทรศัพท์ :</td>
                            <td>{{ $requests->v_tel }}</td>
                        </tr>
                    </table>
                </td>
                <td valign=top>
                    <table style='font-size: 17px; '>
                        <tr>
                            <td width="101px" style="" align="right">เลขที่เอกสาร :</td>
                            <td width="68%" style="padding-left:20px;">{{ $requests->r_id }}</td>
                        </tr>
                        <tr>
                            <td align="right" style="padding-right: 4px;">
                                วันที่เอกสาร :</td>
                            <td style="padding-left:20px;">{{ $requests->r_date }}</td>
                        </tr>
                        <tr>
                            <td align="right" style="padding-right: 8px;">เบอร์แฟกซ์ :</td>
                            <td style="padding-left:20px;">{{ $requests->v_fax }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width='100%' style='font-size: 17px; '>
            <tr>
                <td colspan=2 bgcolor=#CCCCCC style='padding-top:2px;padding-bottom:2px;'></td>
            </tr>
            <tr>
                <td width='50%' valign=top>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td align=left width="25%" style="vertical-align: top;">แบรนด์สินค้า :</td>
                                <td align=left width="80%">{{ $requests->brand_desc }}</td>
                            </tr>
                            <tr>
                                <td align=left style="vertical-align: top;">
                                    ชื่อสินค้า :</td>
                                <td align=left>{{ $requests->i_name }}</td>
                            </tr>
                            <tr>
                                <td align=left style=>
                                    การรับประกัน :</td>
                                <td align=left>{{ $requests->warranty }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width='50%' valign=top>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td align=left valign=top width="25%">อาการเสีย :</td>
                                <td align=left valign=top>{{ strip_tags($requests->i_detail1) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan=2 bgcolor=#CCCCCC style='padding-top:2px;padding-bottom:2px;'></td>
            </tr>
        </table>
        <table width='100%' border='0' style='font-size: 17px; '>
            <tbody>
                <tr>
                    <td valign=top>
                        <table valign=top width='100%' border='0'>
                            <tr>
                                <td style=>หมายเหตุ</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table valign=top width='100%' border='0'>
                            <tr>
                                <td>หากเป็นสินค้าที่มีค่าใช้จ่ายในการดำเนินการ กรุณาเสนอราคาก่อนซ่อมทุกครั้ง
                                    มิฉะนั้นทางบริษัทจะไม่รับผิดชอบค่าใช้จ่ายใดๆ ที่เกิดขึ้น ทั้งสิ้น</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width='100%' border='0' style='font-size: 16px; '>
            <tbody>
                <tr>
                    <td width='40%' valign=top>
                        <table width='100%' border='0' style='font-size: 16px; '>
                            <tbody>
                                {{-- <tr>
                                    <td height='4px'>&nbsp;</td>
                                </tr> --}}
                                <tr>
                                    <td align='right'>ผู้แจ้งซ่อม (CS)</td>
                                    <td> _____________________________________</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>วันที่ _________/_________/_________</td>
                                </tr>
                                <tr>
                                    <td height='4px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='right'>ผู้จำหน่าย (VD)</td>
                                    <td> _____________________________________</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>วันที่ _________/_________/_________</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width='50%' valign=top>
                        <table width='100%' border='0' style='font-size: 16px; '>
                            <tbody>
                                {{-- <tr>
                                    <td height='4px'>&nbsp;</td>
                                </tr> --}}
                                <tr>
                                    <td align='right'>แผนกตรวจรับสินค้า (GR)</td>
                                    <td> _____________________________________</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>วันที่ _________/_________/_________</td>
                                </tr>
                                <tr>
                                    <td height='4px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='right'>พนักงานป้องกันการสูญหาย (LP)</td>
                                    <td> _____________________________________</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>วันที่ _________/_________/_________</td>
                                </tr>
                                <tr>
                                    <td height='4px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='right'>พนักงานขับรถ (HWH-DS)</td>
                                    <td> _____________________________________</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>วันที่ _________/_________/_________</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width='100%' border='0' style='font-size: 16px; '>
            <tbody>
                <tr>
                    <td height="10px" style=>*** กรุณาติดต่อรับสินค้าไปซ่อมภายใน 7 วัน หลังจากได้รับเอกสารแจ้ง ***
                    </td>
                </tr>
                <tr>
                    <td>(กรุณารับสินค้าไปซ่อมภายในวันที่ _________/_________/_________)</td>
                </tr>
                <tr>
                    <td height="10px"></td>
                </tr>
                <tr>
                    <td align="center" style="text-align: center;">
                        <span>*** กรณีสินค้าที่ทางบริษัทฯ แจ้งให้มารับสินค้าแล้ว
                            และบริษัทผู้รับซ่อมไม่สามารถมารับได้ภายในวันที่กำหนด ***</span><br>
                        <span>*** บริษัทฯ ขอสงวนสิทธิ์ในการชะลอการรับวางบิลจ่ายเงิน โดยไม่ต้องแจ้งให้ทราบล่วงหน้า
                            ***</span>
                    </td>

                </tr>
                <tr>
                    <td height="10px"></td>
                </tr>
                <tr>
                    <td align='center' style=>*** โปรดนำเอกสารฉบับนี้มาติดต่อเพื่อรับสินค้าด้วยทุกครั้ง ***
                    </td>
                </tr>
                <tr>
                    <td align='center' style=>กรณีมีเสนอราคาโปรดติดต่อ คุณตฤณตกานต์ Tel. 081-1384-780</td>
                </tr>
            </tbody>
        </table>

    </main>
</body>

</html>

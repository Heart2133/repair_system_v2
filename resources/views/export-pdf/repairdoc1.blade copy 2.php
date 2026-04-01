<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>repairdoc1</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            src: url('{{ asset('fonts/THSarabunNew.ttf') }}') format('truetype');
        }

        body {
            font-family: 'THSarabunNew', sans-serif;
        }
    </style>
</head>

<body>
    <main>
        <div height='490px'>
            <table cellSpacing=0 cellPadding=0 border=0 width='100%' style=''>
                <tr>
                    <td colspan=2 width='60%' style='font-size: 15px; font-weight: bold;'>
                        <table style='font-size:17px;'>
                            <tr>
                                <td colspan='2'>{{ $requests->company_name }}</td>
                            </tr>
                            <tr>
                                <td colspan='2'>{{ $requests->company_addr }}</td>
                            </tr>
                            <tr>
                                <td>โทรศัพท์ : {{ $requests->company_tel }}</td>
                                <td>โทรสาร : {{ $requests->company_fax }}</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                    <td width='20%' valign=top align=right style='font-size: 21px; font-weight: bold;'>
                        <table>
                            <tr>
                                <td style='font-size: 25px; font-weight: bold;' align=right>ใบรับแจ้งซ่อม</td>
                            </tr>
                            <tr>
                                <td style='font-size: 19px; font-weight: bold;' align=right>(สำหรับลูกค้า)</td>
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
                                <td style='font-weight: bold;'>ชื่อลูกค้า :</td>
                                <td>{{ $requests->c_name }}</td>
                            </tr>
                            <tr>
                                <td style='font-weight: bold;'>เบอร์ติดต่อ :</td>
                                <td>{{ $requests->c_tel }}</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table width='100%' style='font-size: 17px; '>
                            <tr>
                                <td style='font-weight: bold;'>เลขที่เอกสาร :</td>
                                <td>{{ $requests->r_id }}</td>
                            </tr>

                            <tr>
                                <td style='font-weight: bold;'>วันที่เอกสาร :</td>
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
            <div style="margin-left:80%; font-size: 15px; margin-top:-20px; margin-bottom:5px"> {{ $requests->r_id }}
            </div>


            <table width='100%' border='1' style='font-size: 16px; '>
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
            </table>

            <table width='100%' style='font-size: 17px; '>
                <tr>
                    <td style='padding:5px;'><strong>รายละเอียดสินค้า / อาการเสีย / หมายเหตุอื่น ๆ</strong></td>
                </tr>
                <tr>
                    <td valign=top width="50%" height='40px'>{{ strip_tags($requests->i_detail1) }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <table width='100%' style='font-size: 16px; '>
                <tr>
                    <td style='padding:5px;'><strong>เงื่อนไขการบริการ</strong></td>
                </tr>
                <tr>
                    <td valign=top>
                        <table width='100%'>
                            <tr>
                                <td width='5%'>1.</td>
                                <td width='95%'>บริษัทฯ รับประกันการซ่อมสินค้าเป็นระยะเวลา 30 วัน
                                    นับตั้งแต่วันที่ท่านมารับสินค้าซ่อมคืน โดยอาการเสียจะต้องเป็นอาการเดิม</td>
                            </tr>
                            <tr>
                                <td>2.</td>
                                <td>กรุณานำใบรับแจ้งซ่อมมาติดต่อรับสินค้าซ่อมคืน บริษัทฯ
                                    ขอสงวนสิทธิ์ไม่จ่ายสินค้าซ่อมคืนหากท่านไม่นำใบรับแจ้งซ่อมมาด้วย</td>
                            </tr>
                            <tr>
                                <td>3.</td>
                                <td>ค่าบริการซ่อมจะเปลี่ยนแปลงตามนโยบายที่บริษัทฯ กำหนด
                                    กรุณาสอบถามราคาจากเจ้าหน้าที่รับซ่อมทุกครั้ง</td>
                            </tr>
                            <tr>
                                <td>4.</td>
                                <td>กรณีที่มีค่าอะไหล่และอุปกรณ์ที่ต้องเปลี่ยน ทางเจ้าหน้าที่จะติดต่อท่าน
                                    เพื่อแจ้งราคาและขอคำยินยอมการซ่อมจากท่านทุกครั้งก่อนดำเนินการซ่อม</td>
                            </tr>
                            <tr>
                                <td>5.</td>
                                <td>กรณีที่ท่านไม่มาติดต่อรับสินค้าซ่อมคืนภายในระยะเวลา 6 เดือน
                                    นับตั้งแต่วันที่ทางเจ้าหน้าที่ติดต่อให้มารับ บริษัทฯ
                                    ขอสงวนสิทธิ์ยกเลิกรายการในระบบและนำสินค้าซ่อมไปทำลายต่อไป</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width='100%' style='font-size: 17px;  margin-top:10px ;'>
                <tr>
                    <td width='50%' style='border: 1px solid #000000;' align='center'>
                        <table width='100%' style='font-size: 17px; '>
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
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
                    <td width='50%' style='border: 1px solid #000000;' align='center'>
                        <table width='100%' style='font-size: 17px; '>
                            <tbody>
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
                                    <td align='center'>วันที่ ________/________/________</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <!-- <div style='border-style: dashed;border-width: 1px;'></div> -->
        <div style="page-break-after: always;"></div>

        <table cellSpacing=0 cellPadding=0 border=0 width='100%' style=''>
            <tr>
                <td colspan=2 width='60%' style='font-size: 15px; '>
                    <table style='font-size:17px;  font-weight: bold;'>
                        <tr>
                            <td colspan='2'>{{ $requests->company_name }}</td>
                        </tr>
                        <tr>
                            <td colspan='2'>{{ $requests->company_addr }}</td>
                        </tr>
                        <tr>
                            <td>โทรศัพท์ : {{ $requests->company_tel }}</td>
                            <td>โทรสาร : {{ $requests->company_fax }}</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
                <td width='20%' valign=top align=right style='font-size: 21px; font-weight: bold;'>
                    <table>
                        <tr>
                            <td style='font-size: 25px; font-weight: bold;' align=right>ใบรับแจ้งซ่อม</td>
                        </tr>
                        <tr>
                            <td style='font-size: 19px; font-weight: bold;' align=right>(สำหรับแผนกบริการลูกค้า)</td>
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
                            <td style='font-weight: bold;'>ชื่อลูกค้า :</td>
                            <td>{{ $requests->c_name }}</td>
                        </tr>
                        <tr>
                            <td style='font-weight: bold;'>เบอร์ติดต่อ :</td>
                            <td>{{ $requests->c_tel }}</td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table width='100%' style='font-size: 17px; '>
                        <tr>
                            <td style='font-weight: bold;'>เลขที่เอกสาร :</td>
                            <td>{{ $requests->r_id }}</td>
                        </tr>
                        <tr>
                            <td style='font-weight: bold;'>วันที่เอกสาร :</td>
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
        <div style="margin-left:80%; font-size: 15px; margin-top:-20px; margin-bottom:5px"> {{ $requests->r_id }}
        </div>

        <table width='100%' border='1' style='font-size: 16px; '>
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
        </table>

        <table width='100%' style='font-size: 17px; '>
            <tr>
                <td style='padding:5px;'><strong>รายละเอียดสินค้า / อาการเสีย / หมายเหตุอื่น ๆ</strong></td>
            </tr>
            <tr>
                <td valign=top height='40px'>{{ strip_tags($requests->i_detail1) }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
        <table width='100%' style='font-size: 17px; '>
            <tr>
                <td width='50%' style='border: 1px solid #000000;' align='center'>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
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
                <td width='50%' style='border: 1px solid #000000;' align='center'>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td align='center' style='font-size: 15px;'>
                                    ข้าพเจ้ารับทราบและยินดีปฏิบัติตามเงื่อนไขการบริการทุกประการลูกค้า</td>
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
                <td width='50%' style='border: 1px solid #000000;' align='center'>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td align='center'>O สินค้าซ่อมเรียบร้อย &nbsp;&nbsp; O ลูกค้าไม่ต้องการซ่อม</td>
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
                <td width='50%' style='border: 1px solid #000000;' align='center'>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td align='center' style='font-size: 15px;'>ข้าพเจ้าได้รับสินค้าซ่อมคืนในสภาพดี
                                    ครบถ้วน</td>
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
        <table cellSpacing=0 cellPadding=0 border=0 width='100%' style=''>
            <tr>
                <td colspan=2 width='60%' style='font-size: 15px; '>
                    <table style='font-size:17px;  font-weight: bold;'>
                        <tr>
                            <td colspan='2'>{{ $requests->company_name }}</td>
                        </tr>
                        <tr>
                            <td colspan='2'>{{ $requests->company_addr }}</td>
                        </tr>
                        <tr>
                            <td>โทรศัพท์ : {{ $requests->company_tel }}</td>
                            <td>โทรสาร : {{ $requests->company_fax }}</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
                <td width='20%' valign=top align=right style='font-size: 21px; font-weight: bold;'>
                    <table>
                        <tr>
                            <td style='font-size: 27px; font-weight: bold;' align=right>ใบแจ้งซ่อม/ใบเสนอราคา</td>
                        </tr>
                        <tr>
                            <td style='font-size: 21px; font-weight: bold;' align=right>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan=3 bgcolor=#CCCCCC style='padding-top:2px;padding-bottom:2px;'></td>
            </tr>
        </table>
        <table width='100%' style='font-size: 17px; '>
            <tr>
                <td valign=top>
                    <table width='100%' style='font-size: 17px; '>
                        <tr>
                            <td style='font-weight: bold;' width="32%">ชื่อบริษัท :</td>
                            <td width="68%">{{ $requests->v_name }}</td>
                        </tr>
                        <tr>
                            <td style='font-weight: bold;'>ผู้ติดต่อ :</td>
                            <td>{{ $requests->v_contact }}</td>
                        </tr>
                        <tr>
                            <td style='font-weight: bold;'>เบอร์โทรศัพท์ :</td>
                            <td>{{ $requests->v_tel }}</td>
                        </tr>
                    </table>
                </td>
                <td valign=top>
                    <table width='100%' style='font-size: 17px; '>
                        <tr>
                            <td style='font-weight: bold;' width="32%">เลขที่เอกสาร :</td>
                            <td width="68%">{{ $requests->r_id }}</td>
                        </tr>
                        <tr>
                            <td style='font-weight: bold;'>วันที่เอกสาร :</td>
                            <td>{{ $requests->r_date }}</td>
                        </tr>
                        <tr>
                            <td style='font-weight: bold;'>เบอร์แฟกซ์ :</td>
                            <td>{{ $requests->v_fax }}</td>
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
                                <td align=left width="25%" style='font-weight: bold;'>แบรนด์สินค้า :</td>
                                <td align=left width="80%">{{ $requests->brand_desc }}</td>
                            </tr>
                            <tr>
                                <td align=left style='font-weight: bold;'>ชื่อสินค้า :</td>
                                <td align=left>{{ $requests->i_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width='50%' valign=top>
                    <table width='100%' style='font-size: 17px; '>
                        <tbody>
                            <tr>
                                <td align=left valign=top width="25%" style='font-weight: bold;'>อาการเสีย :</td>
                                <td align=left valign=top width="75%">{{ strip_tags($requests->i_detail1) }}</td>
                            </tr>
                            <tr>
                                <td align=left style='font-weight: bold;'>การรับประกัน :</td>
                                <td align=left>{{ $requests->warranty }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan=2 bgcolor=#CCCCCC style='padding-top:2px;padding-bottom:2px;'></td>
            </tr>
        </table>
        <table width='100%' border='0' style='font-size: 18px; '>
            <tbody>
                <tr>
                    <td valign=top>
                        <table valign=top width='100%' border='0'>
                            <tr>
                                <td style='font-weight: bold;'>หมายเหตุ</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table valign=top width='100%' border='0'>
                            <tr>
                                <td>หากเป็นสินค้าที่มีค่าใช้จ่ายในการดำเนินการ กรุณาเสนอราคาก่อนซ่อมทุกครั้ง มิฉะนั้น
                                </td>
                            </tr>
                            <tr>
                                <td>ทางบริษัทจะไม่รับผิดชอบค่าใช้จ่ายใดๆ ที่เกิดขึ้น ทั้งสิ้น /
                                    กรุณาใส่หมายเลขแจ้งซ่อมมาด้วย</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width='100%' border='0' style='font-size: 17px; '>
            <tbody>
                <tr>
                    <td width='50%' valign=top>
                        <table width='100%' border='0' style='font-size: 18px; '>
                            <tbody>
                                {{-- <tr>
                                    <td height='4px'>&nbsp;</td>
                                </tr> --}}
                                <tr>
                                    <td align='right'>ผู้แจ้งซ่อม (CS)</td>
                                    <td> ________________________________________</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>วันที่ __________/__________/__________</td>
                                </tr>
                                <tr>
                                    <td height='4px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='right'>VENDOR</td>
                                    <td> ________________________________________</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>วันที่ __________/__________/__________</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width='50%' valign=top>
                        <table width='100%' border='0' style='font-size: 18px; '>
                            <tbody>
                                {{-- <tr>
                                    <td height='4px'>&nbsp;</td>
                                </tr> --}}
                                <tr>
                                    <td align='right'>คลังสินค้ารับของ</td>
                                    <td> ________________________________________</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>วันที่ __________/__________/__________</td>
                                </tr>
                                <tr>
                                    <td height='4px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='right'>รปภ.</td>
                                    <td> ________________________________________</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>วันที่ __________/__________/__________</td>
                                </tr>
                                <tr>
                                    <td height='4px'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align='right'>คนขับรถ(HWH)</td>
                                    <td> ________________________________________</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>วันที่ __________/__________/__________</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width='100%' border='0' style='font-size: 18px; '>
            <tbody>
                <tr>
                    <td height="50px" style='font-weight: bold;'>*** กรุณาติดต่อรับสินค้าไปซ่อมภายใน 7 วัน หลังจากได้รับเอกสารแจ้ง ***</td>
                </tr>
                <tr>
                    <td>(กรุณารับสินค้าไปซ่อมภายในวันที่ __________/__________/__________)</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td align='center' style='font-weight: bold;'>*** กรณีสินค้าที่ทางบริษัทฯ แจ้งให้มารับสินค้าแล้ว
                        และบริษัทผู้รับซ่อมไม่สามารถมารับได้ภายในวันที่กำหนด บริษัทฯ
                        ขอสงวนสิทธิ์ในการชะลอการรับวางบิลจ่ายเงิน โดยไม่ต้องแจ้งให้ทราบล่วงหน้า ***</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td align='center' style='font-weight: bold;'>*** โปรดนำเอกสารฉบับนี้มาติดต่อเพื่อรับสินค้าด้วยทุกครั้ง ขอบคุณค่ะ ***</td>
                </tr>
                <tr>
                    <td align='center' style='font-weight: bold;'>กรุณาส่งใบเสนอราคาค่าซ่อมมาที่ Fax. 02-7072257,02-7072222 ต่อ 407,409</td>
                </tr>
                <tr>
                    <td align='center' style='font-weight: bold;'>ติดต่อแผนกจัดซื้อ คุณศิริเจริญรัตน์ Tel. 02-7072222 ต่อ 111</td>
                </tr>
            </tbody>
        </table>

    </main>
</body>

</html>

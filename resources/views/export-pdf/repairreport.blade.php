<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>repairReport</title>
    <style>
        @font-face {
            font-family: 'Sarabun';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/Sarabun-Regular.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'Sarabun';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/Sarabun-Bold.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'Sarabun';
            font-style: italic;
            font-weight: normal;
            src: url("{{ public_path('fonts/Sarabun-Italic.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'Sarabun';
            font-style: italic;
            font-weight: bold;
            src: url("{{ public_path('fonts/Sarabun-BoldIltalic.ttf') }}") format('truetype');
        }

        body {
            font-family: 'Sarabun';
        }
    </style>
</head>

<body>
    <table cellSpacing=0 cellPadding=0 border=1 width='100%' class=ptable style='font-family: Sarabun, Arial, Helvetica, sans-serif;'>
        <tbody>
            <tr>
                <td align=center colspan=11 bgcolor='#666666' style='font-size: 14px; font-weight: bold; padding:10px 0;'>
                    @if(isset($start_date) and isset($end_date))
                    <font color='#FFFFFF'><b>REPAIR OF EACH BRANCH REPORT</b><br>รายงานสรุปใบรับแจ้งซ่อมของแต่ละสาขา ตั้งแต่วันที่ {{ $start_date }} ถึงวันที่ {{ $end_date }}</font>
                    @elseif(isset($start_date))
                    <font color='#FFFFFF'><b>REPAIR OF EACH BRANCH REPORT</b><br>รายงานสรุปใบรับแจ้งซ่อมของแต่ละสาขา ตั้งแต่วันที่ {{ $start_date }}</font>
                    @elseif(isset($end_date))
                    <font color='#FFFFFF'><b>REPAIR OF EACH BRANCH REPORT</b><br>รายงานสรุปใบรับแจ้งซ่อมของแต่ละสาขา ถึงวันที่ {{ $end_date }}</font>
                    @else
                    <font color='#FFFFFF'><b>REPAIR OF EACH BRANCH REPORT</b><br>รายงานสรุปใบรับแจ้งซ่อมของแต่ละสาขาทั้งหมด</font>
                    @endif
                </td>
            </tr>

            <tr>
                <td align=center class=medhead bgcolor='#CCCCCC' width='10%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>รหัสสาขา</td>
                <td align=center class=medhead bgcolor='#CCCCCC' width='9%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>ทั้งหมด</td>
                <td align=center class=medhead bgcolor='#CCCCCC' width='9%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>ยกเลิก</td>
                <td align=center class=medhead bgcolor='#CCCCCC' width='9%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>รับแจ้งซ่อม</td>
                <td align=center class=medhead bgcolor='#CCCCCC' width='9%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>รอส่งซ่อม</td>
                <td align=center class=medhead bgcolor='#CCCCCC' width='9%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>ส่งซ่อม</td>
                <td align=center class=medhead bgcolor='#CCCCCC' width='9%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>เสนอราคา</td>
                <td align=center class=medhead bgcolor='#CCCCCC' width='9%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>รออนุมัติซ่อม</td>
                <td align=center class=medhead bgcolor='#CCCCCC' width='9%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>VD ส่งคืน</td>
                <td align=center class=medhead bgcolor='#CCCCCC' width='9%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>รอลูกค้ามารับ</td>
                <td align=center class=medhead bgcolor='#CCCCCC' width='9%' style='font-size: 12px; font-weight: bold; padding:10px 0;'>ลูกค้ามารับแล้ว</td>
            </tr>

            @foreach( $branch as $key=>$val)
            <?php

            $where_date = "r_branch = '$val->branch_code' " ;

            if (isset($start_date) and isset($end_date)) {
                $where_date .= "AND r_date BETWEEN '$start_date' AND '$end_date' ";
            } elseif (isset($start_date)) {
                $where_date .= "AND r_date BETWEEN '$start_date' AND '$now_date' ";
            }elseif(isset($end_date))
            $where_date .= "AND r_date BETWEEN '0000-00-00' AND '$end_date' ";

            $request_count_all = DB::table('tb_request')->whereRaw($where_date)->count();
            $request_count_0 = DB::table('tb_request')->whereRaw($where_date.'and s_id = 0 ')->count();
            $request_count_1 = DB::table('tb_request')->whereRaw($where_date.'and s_id = 1 ')->count();
            $request_count_2 = DB::table('tb_request')->whereRaw($where_date.'and s_id = 2 ')->count();
            $request_count_3 = DB::table('tb_request')->whereRaw($where_date.'and s_id = 3 ')->count();
            $request_count_4 = DB::table('tb_request')->whereRaw($where_date.'and s_id = 4 ')->count();
            $request_count_5 = DB::table('tb_request')->whereRaw($where_date.'and s_id = 5 ')->count();
            $request_count_6 = DB::table('tb_request')->whereRaw($where_date.'and s_id = 6 ')->count();
            $request_count_7 = DB::table('tb_request')->whereRaw($where_date.'and s_id = 7 ')->count();
            $request_count_8 = DB::table('tb_request')->whereRaw($where_date.'and s_id = 8 ')->count();
            ?>
            <tr>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $val->branch_code }} </td>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $request_count_all }}</td>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $request_count_0 }}</td>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $request_count_1 }}</td>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $request_count_2 }}</td>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $request_count_3 }}</td>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $request_count_4 }}</td>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $request_count_5 }}</td>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $request_count_6 }}</td>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $request_count_7 }}</td>
                <td align=center style='font-size: 12px; padding:10px 0;'>{{ $request_count_8 }}</td>
            </tr>
            @endforeach()
        </tbody>
    </table>


</body>

</html>
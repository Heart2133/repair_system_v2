@extends('layouts.master-layouts')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>อนุมัติรายการสินค้าเสียหาย (ผู้บริหาร)</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>เลขที่เอกสาร</th>
                        <th>สาขา</th>
                        <th>มูลค่า</th>
                        <th>จัดการ</th>
                        <th>SAP</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($reports as $r)
                        <tr>
                            <td>{{ $r->doc_no }}</td>
                            <td>{{ $r->branch_code }}</td>
                            <td>{{ number_format($r->total_amount, 2) }}</td>
                            <td>
                                <button class="btn btn-success btn-approve" data-id="{{ $r->id }}">
                                    อนุมัติ
                                </button>

                                <button class="btn btn-danger btn-reject" data-id="{{ $r->id }}">
                                    ไม่อนุมัติ
                                </button>
                            </td>
                            <td>
                                <input type="text" class="form-control sap_doc" placeholder="SAP Doc">
                                <input type="date" class="form-control mt-1 sap_date">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.btn-approve').click(function() {
            let row = $(this).closest('tr');

            let id = $(this).data('id');
            let sap_doc = row.find('.sap_doc').val();
            let sap_date = row.find('.sap_date').val();

            if (!sap_doc || !sap_date) {
                Swal.fire('กรุณากรอก SAP Doc และวันที่');
                return;
            }

            $.post("{{ route('damage.admin.action') }}", {
                _token: '{{ csrf_token() }}',
                id: id,
                action: 'approved',
                sap_doc: sap_doc,
                sap_date: sap_date,
                remark: ''
            }, function(res) {

                Swal.fire('สำเร็จ', 'อนุมัติเรียบร้อย', 'success')
                    .then(() => location.reload());

            });
        });

        $('.btn-reject').click(function() {
            let id = $(this).data('id');

            let remark = prompt("ระบุเหตุผล");

            if (!remark) return;

            $.post('/repair_system_v2/damage-report/admin-action', {
                _token: '{{ csrf_token() }}',
                id: id,
                action: 'rejected',
                remark: remark
            }, function(res) {
                alert('ไม่อนุมัติแล้ว');
                location.reload();
            });
        });
    </script>
@endsection

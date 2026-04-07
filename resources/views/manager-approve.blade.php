@extends('layouts.master-layouts')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>อนุมัติรายการสินค้าเสียหาย (Manager)</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>เลขที่เอกสาร</th>
                        <th>สาขา</th>
                        <th>มูลค่า</th>
                        <th>จัดการ</th>
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
            let id = $(this).data('id');

            $.post('{{ url('damage-report/approve-action') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                action: 'approved_manager',
                remark: ''
            }, function(res) {

                Swal.fire({
                    icon: 'success',
                    title: 'อนุมัติสำเร็จ',
                    text: 'ส่งต่อให้ผู้บริหารแล้ว',
                }).then(() => location.reload());

            });
        });

        $('.btn-reject').click(function() {
            let id = $(this).data('id');

            let remark = prompt("ระบุเหตุผลที่ไม่อนุมัติ");

            if (!remark) return;

            $.post('{{ url('damage-report/approve-action') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                action: 'rejected',
                remark: remark
            }, function(res) {

                Swal.fire({
                    icon: 'error',
                    title: 'ไม่อนุมัติ',
                    text: 'ระบบได้ตีกลับรายการแล้ว',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    location.reload();
                });

            });
        });
    </script>
@endsection

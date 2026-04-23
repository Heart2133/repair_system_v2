@extends('layouts.master-layouts')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>ติดตามผลเคลมสินค้า</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>เลขที่เอกสาร</th>
                        <th>สาขา</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($reports as $r)
                        <tr>
                            <td>{{ $r->doc_no }}</td>
                            <td>{{ $r->branch_code }}</td>
                            <td>
                                @if ($r->status == 'waiting_claim_result')
                                    <span class="badge bg-warning">รอผลเคลม</span>
                                @elseif($r->status == 'waiting_accounting')
                                    <span class="badge bg-info">รอ Accounting</span>
                                @elseif($r->status == 'waiting_close_by_executive')
                                    <span class="badge bg-danger">รอผู้บริหารปิด</span>
                                @endif
                            </td>

                            <td>
                                <button class="btn btn-success btn-claim-ok" data-id="{{ $r->id }}">
                                    เครมได้
                                </button>

                                <button class="btn btn-danger btn-claim-no" data-id="{{ $r->id }}">
                                    เครมไม่ได้
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
        $(document).on('click', '.btn-claim-ok', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: 'กรอก CN No',
                input: 'text',
                inputPlaceholder: 'เช่น CN12345',
                confirmButtonText: 'ยืนยัน',
                showCancelButton: true
            }).then((res) => {

                if (!res.isConfirmed || !res.value) return;

                $.post("{{ route('claim.action') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: 'claim_approved',
                    cn_no: res.value
                }, function(res) {

                    if (!res.success) {
                        Swal.fire('ผิดพลาด', res.error, 'error');
                        return;
                    }

                    Swal.fire('สำเร็จ', 'บันทึก CN เรียบร้อย', 'success')
                        .then(() => location.reload());
                });

            });
        });

        $(document).on('click', '.btn-claim-no', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: 'เหตุผล',
                input: 'textarea',
                inputPlaceholder: 'ระบุเหตุผล...',
                confirmButtonText: 'ยืนยัน',
                showCancelButton: true
            }).then((res) => {

                if (!res.isConfirmed || !res.value) {
                    Swal.fire('แจ้งเตือน', 'กรุณาระบุเหตุผล', 'warning');
                    return;
                }

                $.post("{{ route('claim.action') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: 'claim_rejected',
                    remark: res.value
                }, function(res) {

                    if (!res.success) {
                        Swal.fire('ผิดพลาด', res.error, 'error');
                        return;
                    }

                    Swal.fire('สำเร็จ', 'บันทึกเรียบร้อย', 'success')
                        .then(() => location.reload());
                });

            });
        });
    </script>
@endsection

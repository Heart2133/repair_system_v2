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
                        {{-- <th>% ส่วนลด</th> --}}
                        <th>จัดการ</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($reports as $r)
                        <tr>
                            <td>{{ $r->doc_no }}</td>
                            <td>{{ $r->branch_code }}</td>
                            <td>{{ number_format($r->total_amount, 2) }}</td>
                            {{-- <td>
                                @if ($r->flow_type == 'discount')
                                    <input type="number" class="form-control manager_percent" placeholder="% ลด">
                                @else
                                    -
                                @endif
                            </td> --}}
                            <td>
                                <button class="btn btn-success btn-approve" data-id="{{ $r->id }}"
                                    data-flow="{{ $r->flow_type }}">
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
        $('.btn-approve').on('click', function() {

            const $row = $(this).closest('tr');
            const recordId = $(this).data('id');

            const flowType = ($(this).data('flow') || '')
                .toString()
                .trim()
                .toLowerCase();

            const totalAmount = parseFloat(
                $row.find('td:nth-child(3)').text().replace(/,/g, '')
            ) || 0;

            if (!flowType) {
                Swal.fire('Error', 'flow_type ไม่มีค่า', 'error');
                return;
            }

            // ================= DESTROY =================
            if (flowType === 'destroy') {

                Swal.fire({
                    title: 'ยืนยันการอนุมัติ',
                    text: 'ต้องการอนุมัติรายการนี้ใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.post('{{ url('damage-report/approve-action') }}', {
                        _token: '{{ csrf_token() }}',
                        id: recordId,
                        action: 'approved_manager'
                    }, function() {
                        Swal.fire('สำเร็จ', 'อนุมัติแล้ว', 'success')
                            .then(() => location.reload());
                    });

                });

                return;
            }

            // ================= CLAIM =================
            if (flowType === 'claim') {

                Swal.fire({
                    title: 'ยืนยันส่งเคลม',
                    text: 'ต้องการส่งเคลมหรือไม่?',
                    icon: 'question',
                    showCancelButton: true
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.post('{{ url('damage-report/approve-action') }}', {
                        _token: '{{ csrf_token() }}',
                        id: recordId,
                        action: 'approved_manager'
                    }, function() {
                        Swal.fire('สำเร็จ', 'ส่งเคลมแล้ว', 'success')
                            .then(() => location.reload());
                    });

                });

                return;
            }

            // ================= DISCOUNT =================
            if (flowType === 'discount') {

                Swal.fire({
                    title: 'กำหนดส่วนลด',
                    input: 'number',
                    inputPlaceholder: '% ส่วนลด',
                    showCancelButton: true
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.post('{{ url('damage-report/approve-action') }}', {
                        _token: '{{ csrf_token() }}',
                        id: recordId,
                        action: 'approved_manager',
                        manager_discount_percent: result.value
                    }, function() {
                        Swal.fire('สำเร็จ', 'บันทึกแล้ว', 'success')
                            .then(() => location.reload());
                    });

                });

                return;
            }

            Swal.fire('Error', 'flow_type ไม่ถูกต้อง: ' + flowType, 'error');

        });

        $('.btn-reject').click(function() {

            let id = $(this).data('id');

            Swal.fire({
                title: 'ไม่อนุมัติ',
                input: 'textarea',
                inputPlaceholder: 'ระบุเหตุผล...',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                inputValidator: (value) => {
                    if (!value) {
                        return 'กรุณาระบุเหตุผล!';
                    }
                }
            }).then((result) => {

                if (!result.isConfirmed) return;

                // 🔥 แสดง loading
                Swal.fire({
                    title: 'กำลังบันทึก...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.post("{{ route('damage.admin.action') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: 'rejected',
                    remark: result.value
                }, function(res) {

                    if (res.success) {
                        Swal.fire(
                            'เรียบร้อย',
                            'ไม่อนุมัติแล้ว',
                            'success'
                        ).then(() => location.reload());
                    } else {
                        Swal.fire(
                            'ผิดพลาด',
                            res.error || 'เกิดข้อผิดพลาด',
                            'error'
                        );
                    }

                });

            });

        });
    </script>
@endsection

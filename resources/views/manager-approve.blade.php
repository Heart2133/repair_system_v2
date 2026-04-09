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
        $('.btn-approve').on('click', function() {

            const $row = $(this).closest('tr');
            const recordId = $(this).data('id');

            // 🔹 ดึงยอดรวม และแปลงเป็นตัวเลข
            const totalAmount = parseFloat(
                $row.find('td:nth-child(3)').text().replace(/,/g, '')
            ) || 0;

            Swal.fire({
                title: 'กำหนดส่วนลด (ผู้จัดการ)',

                html: `
            <div class="text-start">

            <div class="mb-3">
                <label class="form-label fw-semibold">% ส่วนลด</label>
                <input type="number" id="discount_percent" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">จำนวนเงินส่วนลด</label>
                <input type="text" id="discount_amount" class="form-control" readonly>
            </div>

            <div>
                <label class="form-label fw-semibold">ราคาสุทธิ</label>
                <input type="text" id="net_amount" class="form-control" readonly>
            </div>

        </div>
        `,

                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',

                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary px-4',
                    cancelButton: 'btn btn-secondary px-4 ms-2'
                },

                didOpen: () => {

                    const percentInput = document.getElementById('discount_percent');
                    const discountInput = document.getElementById('discount_amount');
                    const netInput = document.getElementById('net_amount');

                    // 🔹 คำนวณแบบ Real-time
                    percentInput.addEventListener('input', () => {

                        let percent = parseFloat(percentInput.value) || 0;

                        // กันค่าเกิน 100%
                        if (percent > 100) {
                            percent = 100;
                            percentInput.value = 100;
                        }

                        const discount = (totalAmount * percent) / 100;
                        const netTotal = totalAmount - discount;

                        // 🔹 format ตัวเลข
                        discountInput.value = discount.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        netInput.value = netTotal.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });
                    });
                },

                preConfirm: () => {

                    const percent = parseFloat(
                        document.getElementById('discount_percent').value
                    );

                    if (!percent || percent <= 0) {
                        Swal.showValidationMessage('กรุณาระบุ % ส่วนลดให้ถูกต้อง');
                        return false;
                    }

                    return {
                        percent
                    };
                }

            }).then((result) => {

                if (!result.isConfirmed) return;

                // 🔹 ส่งข้อมูลไป server
                $.ajax({
                    url: '{{ url('damage-report/approve-action') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: recordId,
                        action: 'approved_manager',
                        manager_discount_percent: result.value.percent
                    },

                    success: function() {

                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: 'รายการถูกส่งให้ผู้บริหารเรียบร้อยแล้ว'
                        }).then(() => location.reload());

                    },

                    error: function() {

                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถดำเนินการได้ กรุณาลองใหม่อีกครั้ง'
                        });

                    }
                });

            });

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

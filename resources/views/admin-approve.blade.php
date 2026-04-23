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
                    </tr>
                </thead>

                <tbody>
                    @foreach ($reports as $r)
                        <tr>
                            <td>{{ $r->doc_no }}</td>
                            <td>{{ $r->branch_code }}</td>
                            <td>{{ number_format($r->total_amount, 2) }}</td>
                            {{-- <td>
                                <input type="text" class="form-control sap_doc" placeholder="SAP Doc">
                                <input type="date" class="form-control mt-1 sap_date">
                            </td> --}}
                            {{-- <td>
                                <input type="number" class="form-control discount_percent" placeholder="%">
                            </td>

                            <td>
                                <input type="text" class="form-control discount_amount" readonly>
                            </td>

                            <td>
                                <input type="text" class="form-control final_price" readonly>
                            </td> --}}
                            <td>
                                {{-- <div class="d-flex gap-2"> --}}
                                <button class="btn btn-success btn-approve" data-id="{{ $r->id }}"
                                    data-percent="{{ $r->manager_discount_percent }}" data-flow="{{ $r->flow_type }}">
                                    อนุมัติ
                                </button>

                                <button class="btn btn-danger btn-reject" data-id="{{ $r->id }}">
                                    ไม่อนุมัติ
                                </button>
                                {{-- </div> --}}
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
        //         $('.btn-approve').click(function() {

        //             let row = $(this).closest('tr');
        //             let id = $(this).data('id');

        //             let total = parseFloat(row.find('td:nth-child(3)').text().replace(/,/g, ''));

        //             Swal.fire({
        //                 title: 'อนุมัติรายการสินค้าเสียหาย<br><small>มูลค่า: ' + total.toLocaleString() +
        //                     ' บาท</small>',
        //                 html: `
    //     <div style="text-align:left; font-size:14px">

    //         <div style="margin-bottom:12px;">
    //             <div style="margin-bottom:4px; font-weight:600;">ส่วนลด (%)</div>
    //             <input type="number" id="percent" class="swal2-input" style="margin:0; width:100%;">
    //         </div>

    //         <div style="margin-bottom:12px;">
    //             <div style="margin-bottom:4px; font-weight:600;">มูลค่าส่วนลด (บาท)</div>
    //             <input type="text" id="discount" class="swal2-input" style="margin:0; width:100%;" readonly>
    //         </div>

    //         <div>
    //             <div style="margin-bottom:4px; font-weight:600;">ราคาขายสุทธิ</div>
    //             <input type="text" id="final" class="swal2-input" 
    //                 style="margin:0; width:100%; font-weight:bold; color:#198754;" readonly>
    //         </div>

    //     </div>
    // `,
        //                 confirmButtonText: 'ยืนยันอนุมัติ',
        //                 cancelButtonText: 'ยกเลิก',
        //                 showCancelButton: true,

        //                 customClass: {
        //                     popup: 'rounded-4',
        //                     actions: 'd-flex gap-3',
        //                     confirmButton: 'btn btn-success px-4',
        //                     cancelButton: 'btn btn-secondary px-4'
        //                 },
        //                 buttonsStyling: false,

        //                 didOpen: () => {

        //                     const percentInput = document.getElementById('percent');
        //                     const discountInput = document.getElementById('discount');
        //                     const finalInput = document.getElementById('final');

        //                     percentInput.addEventListener('input', function() {

        //                         let percent = parseFloat(this.value) || 0;

        //                         // กันค่าพัง
        //                         if (percent < 0) percent = 0;
        //                         if (percent > 100) percent = 100;

        //                         const discount = (total * percent) / 100;
        //                         const final = total - discount;

        //                         discountInput.value = discount.toLocaleString(undefined, {
        //                             minimumFractionDigits: 2
        //                         });

        //                         finalInput.value = final.toLocaleString(undefined, {
        //                             minimumFractionDigits: 2
        //                         });
        //                     });
        //                 },

        //                 preConfirm: () => {

        //                     const percent = document.getElementById('percent').value;
        //                     const discount = document.getElementById('discount').value.replace(/,/g, '');
        //                     const final = document.getElementById('final').value.replace(/,/g, '');

        //                     if (percent === "") {
        //                         Swal.showValidationMessage('กรุณาระบุ % ส่วนลด');
        //                         return false;
        //                     }

        //                     return {
        //                         percent,
        //                         discount,
        //                         final
        //                     };
        //                 }

        //             }).then((result) => {

        //                 if (!result.isConfirmed) return;

        //                 const data = result.value;

        //                 $.post("{{ route('damage.admin.action') }}", {
        //                     _token: '{{ csrf_token() }}',
        //                     id: id,
        //                     action: 'approved',
        //                     discount_percent: data.percent,
        //                     discount_amount: data.discount,
        //                     final_price: data.final,
        //                     remark: ''
        //                 }, function(res) {

        //                     // 🔥 เช็คก่อน
        //                     if (!res.success) {
        //                         Swal.fire('ผิดพลาด', res.error || 'เกิดข้อผิดพลาด', 'error');
        //                         return;
        //                     }

        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: 'สำเร็จ',
        //                         text: 'อนุมัติเรียบร้อย',
        //                         confirmButtonText: 'ตกลง'
        //                     }).then(() => location.reload());

        //                 });

        //             });

        //         });
        $('.btn-approve').click(function() {

            let row = $(this).closest('tr');
            let id = $(this).data('id');
            let flowType = ($(this).data('flow') || '')
                .toString()
                .trim()
                .toLowerCase(); // ✅ เพิ่ม
                
            let total = parseFloat(row.find('td:nth-child(3)').text().replace(/,/g, '')) || 0;

            // =========================
            // 🔥 CASE 1: DESTROY
            // =========================
            if (flowType === 'destroy') {

                Swal.fire({
                    title: 'ยืนยันอนุมัติ',
                    text: 'ต้องการอนุมัติรายการทำลายสินค้านี้ใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน'
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.post("{{ route('damage.admin.action') }}", {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        action: 'approved'
                    }, function(res) {

                        if (!res.success) {
                            Swal.fire('ผิดพลาด', res.error, 'error');
                            return;
                        }

                        Swal.fire('สำเร็จ', 'อนุมัติเรียบร้อย', 'success')
                            .then(() => location.reload());

                    });

                });

                return;
            }


            // =========================
            // 🔥 CASE 2: CLAIM (เพิ่มใหม่)
            // =========================
            if (flowType === 'claim') {

                Swal.fire({
                    title: 'ยืนยันอนุมัติ',
                    text: 'ส่งรายการนี้เข้าสู่กระบวนการเคลมใช่หรือไม่?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ยืนยัน'
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.post("{{ route('damage.admin.action') }}", {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        action: 'approved'
                    }, function(res) {

                        if (!res.success) {
                            Swal.fire('ผิดพลาด', res.error, 'error');
                            return;
                        }

                        Swal.fire('สำเร็จ', 'ส่งเคลมแล้ว', 'success')
                            .then(() => location.reload());

                    });

                });

                return;
            }

            // =========================
            // 🔥 CASE 3: DISCOUNT
            // =========================
            let percentDefault = $(this).data('percent') || 0;

            Swal.fire({
                title: 'กำหนดส่วนลด (ผู้บริหาร)',
                html: `
        <div class="text-start">

            <div class="mb-3">
                <label class="form-label fw-semibold">% ส่วนลด</label>
                <input type="number" id="discount_percent" class="form-control" 
                    value="${percentDefault}" min="0" max="100">
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
                confirmButtonText: 'อนุมัติ',
                cancelButtonText: 'ยกเลิก',

                didOpen: () => {

                    const percentInput = document.getElementById('discount_percent');
                    const discountInput = document.getElementById('discount_amount');
                    const netInput = document.getElementById('net_amount');

                    function calculate() {
                        let percent = parseFloat(percentInput.value) || 0;

                        if (percent < 0) percent = 0;
                        if (percent > 100) percent = 100;

                        let discount = (total * percent) / 100;
                        let net = total - discount;

                        discountInput.value = discount.toLocaleString(undefined, {
                            minimumFractionDigits: 2
                        });

                        netInput.value = net.toLocaleString(undefined, {
                            minimumFractionDigits: 2
                        });
                    }

                    percentInput.addEventListener('input', calculate);

                    calculate(); // โหลดครั้งแรก
                },

                preConfirm: () => {
                    let percent = document.getElementById('discount_percent').value;

                    if (percent === "") {
                        Swal.showValidationMessage('กรุณาใส่ % ส่วนลด');
                        return false;
                    }

                    return {
                        percent
                    };
                }

            }).then((result) => {

                if (!result.isConfirmed) return;

                $.post("{{ route('damage.admin.action') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: 'approved',
                    manager_discount_percent: result.value.percent
                }, function(res) {

                    if (!res.success) {
                        Swal.fire('ผิดพลาด', res.error, 'error');
                        return;
                    }

                    Swal.fire('สำเร็จ', 'อนุมัติแล้ว', 'success')
                        .then(() => location.reload());
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
            }).then((result) => {

                if (!result.isConfirmed || !result.value) return;

                $.post("{{ route('damage.admin.action') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: 'rejected',
                    remark: result.value
                }, function(res) {

                    Swal.fire('เรียบร้อย', 'ไม่อนุมัติแล้ว', 'success')
                        .then(() => location.reload());

                });

            });

        });

        // $('.discount_percent').on('input', function() {
        //     let row = $(this).closest('tr');

        //     let percent = parseFloat($(this).val()) || 0;
        //     let total = parseFloat(row.find('td:nth-child(3)').text().replace(/,/g, ''));

        //     let discount = (total * percent) / 100;
        //     let final = total - discount;

        //     row.find('.discount_amount').val(discount.toFixed(2));
        //     row.find('.final_price').val(final.toFixed(2));
        // });
    </script>
@endsection

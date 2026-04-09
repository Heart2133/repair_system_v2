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
                        {{-- <th>SAP</th> --}}
                        {{-- <th>ส่วนลด (%)</th>
                        <th>ส่วนลด (บาท)</th>
                        <th>ราคาขาย</th> --}}
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
                                    data-percent="{{ $r->manager_discount_percent }}">
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

            let total = parseFloat(row.find('td:nth-child(3)').text().replace(/,/g, ''));
            let percent = $(this).data('percent'); // ส่งมาจาก blade

            let discount = (total * percent) / 100;
            let final = total - discount;

            Swal.fire({
                title: 'ยืนยันอนุมัติ',
                html: `
            <p>ส่วนลด: ${percent}%</p>
            <p>ส่วนลด (บาท): ${discount.toFixed(2)}</p>
            <p>ราคาขาย: ${final.toFixed(2)}</p>
        `,
                showCancelButton: true,
                confirmButtonText: 'อนุมัติ'
            }).then((result) => {

                if (!result.isConfirmed) return;

                $.post("{{ route('damage.admin.action') }}", {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: 'approved',
                    discount_percent: percent
                }, function(res) {

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

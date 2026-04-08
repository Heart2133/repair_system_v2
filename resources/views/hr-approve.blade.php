@extends('layouts.master-layouts')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>HR หักเงินเดือน</h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Doc No</th>
                    <th>พนักงาน</th>
                    <th>จำนวนเงิน</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($reports as $r)

                @php
                $emps = DB::table('damage_report_employees')
                    ->where('damage_report_id', $r->id)
                    ->get();
                @endphp

                @foreach ($emps as $emp)
                <tr>
                    <td>{{ $r->doc_no }}</td>
                    <td>{{ $emp->emp_name }}</td>
                    <td>{{ number_format($emp->amount, 2) }}</td>
                    <td>
                        <button class="btn btn-success btn-hr"
                            data-id="{{ $r->id }}"
                            data-doc="{{ $r->doc_no }}">
                            หักเงิน
                        </button>
                    </td>
                </tr>
                @endforeach

                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('script')
<script>
$(document).on('click', '.btn-hr', function() {

    let btn = $(this);
    let id = btn.data('id');
    let doc = btn.data('doc');

    // 🔥 disable ปุ่มกันกดซ้ำ
    btn.prop('disabled', true).text('กำลังบันทึก...');

    $.post("{{ route('hr.save') }}", {
        _token: '{{ csrf_token() }}',
        id: id,
        doc_no: doc
    })
    .done(function(res) {

        if (res.success) {
            Swal.fire('สำเร็จ', 'หักเงินเดือนเรียบร้อย', 'success')
                .then(() => location.reload());
        } else {
            Swal.fire('ผิดพลาด', res.error, 'error');
            btn.prop('disabled', false).text('หักเงิน');
        }

    })
    .fail(function(xhr) {
        console.log(xhr.responseText); // 🔥 debug
        Swal.fire('error', 'เกิดข้อผิดพลาด', 'error');
        btn.prop('disabled', false).text('หักเงิน');
    });

});
</script>
@endsection
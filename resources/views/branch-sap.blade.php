@extends('layouts.master-layouts')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>บันทึก SAP (สาขา)</h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>เลขที่เอกสาร</th>
                    <th>สาขา</th>
                    <th>มูลค่า</th>
                    <th>SAP</th>
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
                            <input type="text" class="form-control sap_doc" placeholder="SAP Doc">
                            <input type="date" class="form-control mt-1 sap_date">
                        </td>

                        <td>
                            <button class="btn btn-primary btn-save" data-id="{{ $r->id }}">
                                บันทึก SAP
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
$('.btn-save').click(function() {

    let row = $(this).closest('tr');

    let id = $(this).data('id');
    let sap_doc = row.find('.sap_doc').val();
    let sap_date = row.find('.sap_date').val();

    if (!sap_doc || !sap_date) {
        Swal.fire('กรุณากรอก SAP Doc และวันที่');
        return;
    }

    $.post("{{ route('branch.sap.save') }}", {
        _token: '{{ csrf_token() }}',
        id: id,
        sap_doc: sap_doc,
        sap_date: sap_date
    }, function(res) {

        if (!res.success) {
            Swal.fire('ผิดพลาด', res.error, 'error');
            return;
        }

        Swal.fire('สำเร็จ', 'บันทึก SAP เรียบร้อย', 'success')
            .then(() => location.reload());

    });

});
</script>
@endsection
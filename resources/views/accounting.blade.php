@extends('layouts.master-layouts')

@section('title')
    Accounting
@endsection

@section('css')
<link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">📊 บัญชี (บันทึก SAP)</h5>
    </div>

    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">

                <thead style="background:#556ee6;color:white;">
                    <tr>
                        <th>เลขที่เอกสาร</th>
                        <th>สาขา</th>
                        <th>มูลค่า</th>
                        <th>SAP Doc</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reports as $r)
                        <tr>
                            <td>{{ $r->doc_no }}</td>
                            <td>{{ $r->branch_code }}</td>
                            <td>{{ number_format($r->total_amount,2) }}</td>

                            <td style="width:200px;">
                                <input type="text"
                                    class="form-control sap_doc"
                                    placeholder="กรอก SAP Doc">
                            </td>

                            <td>
                                <span class="badge bg-warning">รอ Accounting</span>
                            </td>

                            <td>
                                <button class="btn btn-success btn-save"
                                    data-id="{{ $r->id }}">
                                    💾 บันทึก
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">ไม่มีข้อมูล</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection

@section('script')

<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).on('click','.btn-save',function(){

    let btn = $(this);
    let id = btn.data('id');
    let row = btn.closest('tr');
    let sap_doc = row.find('.sap_doc').val();

    if(!sap_doc){
        Swal.fire({
            icon:'warning',
            title:'กรุณากรอก SAP Doc'
        });
        return;
    }

    Swal.fire({
        title: 'ยืนยันการบันทึก?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ใช่',
        cancelButtonText: 'ยกเลิก'
    }).then((result)=>{

        if(!result.isConfirmed) return;

        $.post("{{ route('accounting.save') }}",{
            _token:'{{ csrf_token() }}',
            id:id,
            sap_doc:sap_doc
        },function(res){

            if(!res.success){
                Swal.fire('ผิดพลาด',res.error,'error');
                return;
            }

            Swal.fire({
                icon:'success',
                title:'บันทึกสำเร็จ',
                text:'รายการถูกปิดเรียบร้อย',
                timer:1500,
                showConfirmButton:false
            }).then(()=>{
                location.reload();
            });

        });

    });

});
</script>

@endsection
@extends('layouts.master-layouts')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>ฟอร์มทำลายสินค้า</h5>
        </div>

        <div class="card-body">

            <input type="hidden" id="report_id" value="{{ $report->id }}">

            <div class="mb-3">
                <label>สถานที่ทำลาย</label>
                <input type="text" id="location" class="form-control">
            </div>

            <div class="mb-3">
                <label>วันที่ทำลาย</label>
                <input type="date" id="destroy_date" class="form-control">
            </div>

            <div class="mb-3">
                <label>หมายเหตุ</label>
                <textarea id="remark" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>รูปภาพ</label>
                <input type="file" id="images" multiple class="form-control">
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-success" id="btn-save">
                    บันทึกการทำลาย
                </button>

                <a href="{{ route('destroy.print', $report->id) }}" target="_blank" class="btn btn-primary">
                    พิมพ์เอกสาร
                </a>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#btn-save').click(function() {

            if (!$('#location').val()) {
                Swal.fire('กรุณาระบุสถานที่');
                return;
            }

            if (!$('#destroy_date').val()) {
                Swal.fire('กรุณาระบุวันที่');
                return;
            }

            let formData = new FormData();

            formData.append('report_id', $('#report_id').val());
            formData.append('location', $('#location').val());
            formData.append('destroy_date', $('#destroy_date').val());
            formData.append('remark', $('#remark').val());

            let files = $('#images')[0].files;

            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }

            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('destroy.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {

                    if (!res.success) {
                        Swal.fire('ผิดพลาด', res.error, 'error');
                        return;
                    }

                    Swal.fire('สำเร็จ', 'บันทึกการทำลายแล้ว', 'success')
                        .then(() => window.location.href = "{{ route('destroy.list') }}");

                }
            });

        });
    </script>
@endsection

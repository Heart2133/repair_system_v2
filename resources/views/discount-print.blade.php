@extends('layouts.master-layouts')

@section('content')
    <div class="card">

        <div class="card-header">
            <h5>ปริ้นสติ๊กเกอร์ / ปิดงานส่วนลด</h5>
        </div>

        {{-- DETAIL --}}
        <div class="card mb-4">

            <div class="card-header d-flex justify-content-between">

                <h5>รายละเอียดใบแจ้งสินค้า</h5>

                <div>
                    เลขที่เอกสาร :
                    <b>{{ $report->doc_no }}</b>
                </div>

            </div>

            <div class="card-body">

                {{-- HEADER --}}
                <div class="card mb-4">

                    <div class="card-header">
                        ข้อมูลเอกสาร
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label>วันที่</label>

                                    <input class="form-control" value="{{ $report->created_at }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label>สาขา</label>

                                    <input class="form-control" value="{{ $report->branch_code }}" disabled>
                                </div>

                                <div class="mb-3">
                                    <label>ประเภทงาน</label>

                                    <input class="form-control" value="{{ $report->flow_type }}" disabled>
                                </div>

                            </div>


                            <div class="col-md-6">

                                {{-- แนบรูป/เอกสาร --}}
                                <div class="mb-4">

                                    <label class="mb-2 d-block">
                                        แนบรูป / เอกสาร
                                    </label>

                                    <div id="d_files">

                                        @if (isset($report->files) && count($report->files))
                                            <div class="input-group">

                                                <input type="text" class="form-control"
                                                    value="{{ count($report->files) }} ไฟล์" readonly>

                                                <button type="button" class="btn btn-primary btn-preview-file"
                                                    data-files='@json($report->files)'>

                                                    ดูไฟล์

                                                </button>

                                            </div>
                                        @else
                                            <span class="text-muted">
                                                ไม่มีไฟล์แนบ
                                            </span>
                                        @endif

                                    </div>

                                </div>


                                {{-- สาเหตุ --}}
                                <div class="mb-4">

                                    <label>
                                        สาเหตุความเสียหาย
                                    </label>

                                    <textarea class="form-control" rows="6" disabled>{{ $report->damage_reason }}</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- PRODUCT --}}
                <div class="card mb-4">

                    <div class="card-header">
                        สินค้า
                    </div>

                    <table class="table table-bordered">

                        <thead>

                            <tr>
                                <th>สินค้า</th>
                                <th>ราคา</th>
                                <th>จำนวน</th>
                                <th>รวม</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($items as $item)
                                <tr>

                                    <td>
                                        {{ $item->product_name }}
                                    </td>

                                    <td>
                                        {{ number_format($item->price, 2) }}
                                    </td>

                                    <td>
                                        {{ $item->qty }}
                                    </td>

                                    <td>
                                        {{ number_format($item->qty * $item->price, 2) }}
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- DISCOUNT --}}
                <div class="card mb-4">

                    <div class="card-header">
                        ข้อมูลส่วนลด
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4">

                                <label>% ส่วนลด</label>

                                <input class="form-control" value="{{ $report->discount_percent }}" disabled>

                            </div>

                            <div class="col-md-4">

                                <label>ราคาหลังลด</label>

                                <input class="form-control" value="{{ number_format($report->final_price, 2) }}" disabled>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- BUTTON --}}
        <div class="card-body text-end">

            <button class="btn btn-success" id="btn-print">

                ปริ้น + ปิดงาน

            </button>

        </div>

    </div>

    <!-- FILE PREVIEW MODAL -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5>
                        ดูไฟล์แนบ
                    </h5>

                    <button class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body" id="previewContainer">

                </div>

            </div>

        </div>

    </div>
@endsection

@section('script')
    <script>
        // ======================================
        // PRINT + CLOSE JOB
        // ======================================
        $('#btn-print').click(function() {

            window.print();

        });


        window.onafterprint = function() {

            Swal.fire({
                    title: 'พิมพ์เสร็จแล้วใช่ไหม?',
                    text: 'ระบบจะปิดงานหลังยืนยัน',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'ใช่ ปิดงาน',
                    cancelButtonText: 'ยกเลิก'
                })
                .then((result) => {

                    if (!result.isConfirmed) return;

                    $.post(
                        "{{ route('discount.print.save') }}", {
                            _token: '{{ csrf_token() }}',
                            id: '{{ $report->id }}'
                        },

                        function(res) {

                            if (res.success) {

                                Swal.fire(
                                    'สำเร็จ',
                                    'ปริ้น + ปิดงานเรียบร้อย',
                                    'success'
                                ).then(() => {

                                    window.location.href =
                                        "{{ route('damage-report') }}";

                                });

                            }

                        }
                    );

                });

        };


        // ======================================
        // PREVIEW FILE
        // ======================================
        $(document).on(
            'click',
            '.btn-preview-file',
            function() {

                let files =
                    JSON.parse(
                        decodeURIComponent(
                            $(this).attr('data-files')
                        )
                    );

                let html = '';


                files.forEach(function(f) {

                    let file =
                        "{{ asset('storage') }}/" +
                        f.file_path;

                    let ext =
                        f.file_path
                        .split('.')
                        .pop()
                        .toLowerCase();

                    let name =
                        f.file_name ??
                        f.file_path
                        .split('/')
                        .pop();


                    // ================= IMAGE =================
                    if (
                        ['jpg', 'jpeg', 'png', 'gif', 'webp']
                        .includes(ext)
                    ) {

                        html += `
                    <div class="mb-4 text-center">

                        <img
                        src="${file}"
                        class="img-fluid rounded shadow"
                        style="max-height:500px">

                        <div class="mt-2 fw-semibold">

                            ${name}

                        </div>

                    </div>
                    `;
                    }


                    // ================= PDF =================
                    else if (ext === 'pdf') {

                        html += `
                    <div class="mb-4">

                        <iframe
                        src="${file}"
                        width="100%"
                        height="600"
                        style="border:none">

                        </iframe>

                        <div class="mt-2 fw-semibold">

                            ${name}

                        </div>

                    </div>
                    `;
                    }


                    // ================= OTHER FILE =================
                    else {

                        html += `
                    <div class="mb-3 text-center">

                        <a
                        href="${file}"
                        target="_blank"
                        class="btn btn-primary">

                        เปิดไฟล์ ${name}

                        </a>

                    </div>
                    `;
                    }

                });


                $('#previewContainer')
                    .html(html);


                // Bootstrap 5
                let modal =
                    new bootstrap.Modal(
                        document.getElementById(
                            'filePreviewModal'
                        )
                    );

                modal.show();

            }
        );


        // ======================================
        // CLEAR MODAL
        // ======================================
        $('#filePreviewModal')
            .on(
                'hidden.bs.modal',
                function() {

                    $('#previewContainer')
                        .html('');

                }
            );
    </script>
@endsection

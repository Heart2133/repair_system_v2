@extends('layouts.master-layouts')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5>ปริ้นสติ๊กเกอร์</h5>
        </div>

        <div class="card-body">

            <h4>เลขที่: {{ $report->doc_no }}</h4>
            <p>ส่วนลด: {{ $report->discount_percent }}%</p>
            <p>ราคาขาย: {{ number_format($report->final_price, 2) }}</p>

            <hr>

            @foreach ($items as $item)
                <div class="border p-2 mb-2">
                    <b>{{ $item->product_name }}</b><br>
                    ราคา: {{ number_format($item->price, 2) }} <br>
                    จำนวน: {{ $item->qty }}
                </div>
            @endforeach

            <button class="btn btn-success mt-3" id="btn-print">
                ปริ้น + ปิดงาน
            </button>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#btn-print').click(function() {

            window.print(); // 🔥 ปริ้นก่อน

            // 🔥 หน่วงนิดนึงให้ print ทำงานก่อน
            setTimeout(() => {

                $.post("{{ route('discount.print.save') }}", {
                    _token: '{{ csrf_token() }}',
                    id: '{{ $report->id }}'
                }, function(res) {
                    if (res.success) {
                        alert('ปริ้นเสร็จแล้ว');

                        window.location.href = "{{ route('discount.list') }}";
                    }
                });

            }, 1000); // หน่วง 1 วิ
        });
    </script>
@endsection

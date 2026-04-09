@extends('layouts.master-layouts')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>รายการรอปริ้นสติ๊กเกอร์</h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>เลขที่เอกสาร</th>
                    <th>สาขา</th>
                    <th>มูลค่า</th>
                    <th>% ลด</th>
                    <th>ราคาขาย</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $r)
                <tr>
                    <td>{{ $r->doc_no }}</td>
                    <td>{{ $r->branch_code }}</td>
                    <td>{{ number_format($r->total_amount, 2) }}</td>
                    <td>{{ $r->discount_percent }}%</td>
                    <td>{{ number_format($r->final_price, 2) }}</td>
                    <td>
                        <a href="{{ route('discount.print', $r->id) }}" 
                           class="btn btn-primary">
                            ปริ้นสติ๊กเกอร์
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
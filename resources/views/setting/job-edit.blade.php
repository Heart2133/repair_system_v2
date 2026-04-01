@extends('layouts.master-layouts')

@section('title')
    แก้ไขระดับตำแหน่ง
@endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="card">

            <div class="card-body border-bottom"
                 style="display:flex;justify-content:space-between;align-items:center;">
                <div style="color:black;font-size:14px;">
                    แก้ไขระดับตำแหน่ง
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('jobPosition.update', $position->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row gy-3">

                        {{-- ชื่อตำแหน่ง --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                ชื่อตำแหน่ง <span style="color:red">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ $position->name }}"
                                   required>
                        </div>

                        {{-- ฝ่าย --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                ฝ่าย <span style="color:red">*</span>
                            </label>
                            <select name="section_id" class="form-select" required>
                                <option value="">กรุณาเลือกฝ่าย</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ $section->id == $position->section_id ? 'selected' : '' }}>
                                        {{ $section->section_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- ผู้ใช้งาน --}}
                        <div class="col-md-6">
                            <label class="form-label">
                                ผู้ใช้งาน <span style="color:red">*</span>
                            </label>
                            <select name="user_id" class="form-select" required>
                                <option value="">กรุณาเลือกผู้ใช้งาน</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ $user->id == $position->user_id ? 'selected' : '' }}>
                                        {{ $user->name }} {{ $user->lastname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div style="display:flex;justify-content:flex-end;margin-top:20px;">
                        <a href="{{ route('jobPosition.index') }}"
                           class="btn btn-secondary me-2">
                            ยกเลิก
                        </a>

                        <button type="submit" class="btn btn-primary">
                            บันทึกการแก้ไข
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>
</div>
@endsection
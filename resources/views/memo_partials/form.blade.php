@php
    // mode: add|view|edit
    $p = $mode; // prefix
    $isView = $mode === 'view';
@endphp

<div class="row">
    <div class="mb-3">
        <label class="form-label">รูปภาพประกอบ</label>

        <div class="image-upload-container">
            {{-- img 1 --}}
            <div class="image-box"
                onclick="{{ $isView
                    ? "openPreviewFromSpan('{$p}_imgPreview1')"
                    : "handleImageBoxClick(event,'{$p}_imgPreview1','{$p}_imgUpload1')" }}">

                <input type="file" id="{{ $p }}_imgUpload1" name="img_1" class="hidden-input"
                    accept="image/*" {{ $isView ? 'disabled' : '' }}
                    onchange="previewImage(event,'{{ $p }}_imgPreview1','{{ $p }}_removeBtn1')">

                <span id="{{ $p }}_imgPreview1">
                    @if (!$isView)
                        +
                    @endif
                </span>

                @if (!$isView)
                    <button type="button" class="remove-btn" id="{{ $p }}_removeBtn1"
                        onclick="removeImage(event,'{{ $p }}_imgPreview1','{{ $p }}_imgUpload1','{{ $p }}_removeBtn1')">✖</button>
                @endif
            </div>

            {{-- img 2 --}}
            <div class="image-box"
                onclick="{{ $isView
                    ? "openPreviewFromSpan('{$p}_imgPreview2')"
                    : "handleImageBoxClick(event,'{$p}_imgPreview2','{$p}_imgUpload2')" }}">

                <input type="file" id="{{ $p }}_imgUpload2" name="img_2" class="hidden-input"
                    accept="image/*" {{ $isView ? 'disabled' : '' }}
                    onchange="previewImage(event,'{{ $p }}_imgPreview2','{{ $p }}_removeBtn2')">

                <span id="{{ $p }}_imgPreview2">
                    @if (!$isView)
                        +
                    @endif
                </span>

                @if (!$isView)
                    <button type="button" class="remove-btn" id="{{ $p }}_removeBtn2"
                        onclick="removeImage(event,'{{ $p }}_imgPreview2','{{ $p }}_imgUpload2','{{ $p }}_removeBtn2')">✖</button>
                @endif
            </div>

            {{-- img 3 --}}
            <div class="image-box"
                onclick="{{ $isView
                    ? "openPreviewFromSpan('{$p}_imgPreview3')"
                    : "handleImageBoxClick(event,'{$p}_imgPreview3','{$p}_imgUpload3')" }}">

                <input type="file" id="{{ $p }}_imgUpload3" name="img_3" class="hidden-input"
                    accept="image/*" {{ $isView ? 'disabled' : '' }}
                    onchange="previewImage(event,'{{ $p }}_imgPreview3','{{ $p }}_removeBtn3')">

                <span id="{{ $p }}_imgPreview3">
                    @if (!$isView)
                        +
                    @endif
                </span>

                @if (!$isView)
                    <button type="button" class="remove-btn" id="{{ $p }}_removeBtn3"
                        onclick="removeImage(event,'{{ $p }}_imgPreview3','{{ $p }}_imgUpload3','{{ $p }}_removeBtn3')">✖</button>
                @endif
            </div>
        </div>

        {{-- ใช้เก็บค่าลบรูปตอน edit --}}
        @if ($mode === 'edit')
            <input type="hidden" name="remove_img_1" id="{{ $p }}_remove_img_1" value="0">
            <input type="hidden" name="remove_img_2" id="{{ $p }}_remove_img_2" value="0">
            <input type="hidden" name="remove_img_3" id="{{ $p }}_remove_img_3" value="0">
        @endif
    </div>

    {{-- From --}}
    <div class="mb-3 col-6">
        <label class="form-label">จาก ( From ) :</label>

        @if ($mode === 'add')
            <input type="text" class="form-control bg-light" value="{{ Auth::user()->fullname }}" readonly>
            <input type="text" name="create_by" class="form-control d-none" value="{{ Auth::user()->id }}" readonly>
        @else
            <input type="text" id="{{ $p }}_created_by_name" class="form-control bg-light" readonly>
        @endif
    </div>

    {{-- To --}}
    <div class="mb-3 col-6">
        <label class="form-label">เรียน ( To ) <span class="text-danger">*</span></label>
        <select id="{{ $p }}_to_executive" name="to_executive" class="form-select"
            {{ $isView ? 'disabled' : '' }} required>
            <option value="" selected disabled>กรุณาเลือกผู้บริหาร</option>
            @foreach ($exeUsers as $exeUser)
                <option value="{{ $exeUser->id }}">{{ $exeUser->fullname }}</option>
            @endforeach
        </select>
    </div>

    {{-- Branch --}}
    <div class="mb-3 col-md-6">
        <label class="form-label">สาขาที่ส่งถึง <span style="color:red;">*</span></label>
        <select id="{{ $p }}_to_branch" name="to_branch" class="form-select"
            {{ $isView ? 'disabled' : '' }} required>
            <option value="" selected disabled>กรุณาเลือกสาขา</option>
            @foreach (getBranchAll() as $item)
                <option value="{{ $item->branch_code }}">{{ $item->branch_desc }}</option>
            @endforeach
        </select>
    </div>

    {{-- Section --}}
    <div class="mb-3 col-6">
        <label class="form-label">ฝ่ายที่ส่งถึง <span style="color:red;">*</span></label>
        <select id="{{ $p }}_to_section" name="to_section" class="form-select"
            {{ $isView ? 'disabled' : '' }} required>
            <option value="">กรุณาเลือกสาขาก่อน</option>
        </select>
    </div>

    {{-- CC --}}
    <div class="mb-3 col-6">
        <label class="form-label">CC ( แจ้งเพื่อทราบ )</label>
        <select id="{{ $p }}_cc_sections" name="cc_sections[]" class="form-control" multiple
            {{ $isView ? 'disabled' : '' }}>
            @if (!empty($branchSections))
                @foreach ($branchSections as $branchLabel => $sectionsInBranch)
                    <optgroup label="{{ $branchLabel }}">
                        @foreach ($sectionsInBranch as $sec)
                            <option value="{{ $sec['section_id'] }}">
                                {{ $sec['section_th'] }} ({{ $branchLabel }})
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            @endif
        </select>
    </div>

    {{-- Topic --}}
    <div class="mb-3 col-6">
        <label class="form-label">หัวข้อ <span class="text-danger">*</span></label>
        <input type="text" id="{{ $p }}_topic" name="topic" class="form-control"
            {{ $isView ? 'disabled' : '' }} placeholder="กรุณากรอกหัวข้อ" required>
    </div>
</div>

<label class="form-label fw-bold mb-3">ประเภทเอกสาร <span style="color:red;">*</span></label>

@php
    $actions = [
        ['urgent', 'ด่วน / Urgent'],
        ['for_approval', 'เพื่อการอนุมัติ / For you approval'],
        ['call_back', 'ให้โทร. ไปหา / Please call back'],
        ['for_info', 'เพื่อทราบ / For you information'],
        ['for_comment', 'ความเห็นของท่าน / For you comments'],
        ['you_request', 'ตามที่ท่านร้องขอ / As you requested'],
        ['return', 'โปรดส่งคืน / Please return'],
        ['contact_me', 'โปรดติดต่อฉัน / Please contact me'],
        ['sign', 'โปรดลงนาม / For you signature'],
        ['please_file', 'โปรดเข้าแฟ้ม / Please file'],
        ['please_handle', 'โปรดดำเนินการ / Please handle'],
        ['forward_to', 'โปรดส่งต่อถึง / Please forward to...'],
    ];
@endphp

<div class="mb-4 card">
    <div class="row" style="padding-left: 2%">
        @foreach ($actions as [$val, $label])
            <div class="col-md-4 mb-2">
                <div class="form-check border rounded p-2">
                    <input class="form-check-input" type="checkbox" name="actions_{{ $p }}[]"
                        value="{{ $val }}" id="{{ $p }}_act_{{ $val }}"
                        {{ $isView ? 'disabled' : '' }}>
                    <label class="form-check-label" for="{{ $p }}_act_{{ $val }}">
                        {{ $label }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Detail --}}
<div class="mb-3">
    <label class="form-label">รายละเอียด <span class="text-danger">*</span></label>
    <textarea id="{{ $p }}_detail" name="detail" class="form-control" rows="4"
        {{ $isView ? 'disabled' : '' }} required></textarea>
</div>

{{-- =========================
    STEP EXTRA (Approve result / Assign To / Accept result)
========================= --}}

<div class="row mt-3">
    {{-- Step 2: ผลการอนุมัติ --}}
    <div class="col-12 mb-3 d-none" id="{{ $p }}_approve_block">
        <div class="text-center mb-3">
            ---------------------------- &nbsp; รายละเอียดการตอบรับ MEMO &nbsp; ----------------------------
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label">ผลการอนุมัติ</label>
                <input type="text" id="{{ $p }}_approve_status" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6 mb-2 d-none" id="{{ $p }}_approve_at_div">
                <label class="form-label">เวลาที่อนุมัติ</label>
                <input type="text" id="{{ $p }}_approve_at" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6 mb-2 d-none" id="{{ $p }}_reject_reason_div">
                <label class="form-label">เหตุผลที่ไม่อนุมัติ (ถ้ามี)</label>
                <input id="{{ $p }}_reject_reason" class="form-control bg-light" rows="2" readonly></input>
            </div>
        </div>
    </div>

    {{-- Step 3: Assign To --}}
    <div class="col-12 mb-3 d-none" id="{{ $p }}_assign_block">
        <div class="row">
            <div class="col-md-6 mb-2">
                <label class="form-label">ผู้รับผิดชอบ</label>

                {{-- VIEW = read-only --}}
                @if ($isView)
                    <input type="text" id="{{ $p }}_assigned_to" class="form-control bg-light" readonly>
                @else
                    <select id="{{ $p }}_assigned_to" name="assigned_to" class="form-select">
                        <option value="">-- เลือกผู้รับผิดชอบ --</option>
                    </select>
                @endif
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label">Assign วันที่</label>
                <input type="text" id="{{ $p }}_assigned_at" class="form-control bg-light" readonly>
            </div>
        </div>
    </div>

    {{-- Step 4: Accept result --}}
    <div class="col-12 mb-3 d-none" id="{{ $p }}_accept_block">

        <label class="form-label">รูปผลการดำเนินการ</label>

        <div class="image-upload-container mb-3">
            <div class="image-box" style="cursor: pointer;"
                onclick="openPreviewFromSpan('{{ $p }}_imgReturnPreview1')">
                <span id="{{ $p }}_imgReturnPreview1"></span>
            </div>

            <div class="image-box" style="cursor: pointer;"
                onclick="openPreviewFromSpan('{{ $p }}_imgReturnPreview2')">
                <span id="{{ $p }}_imgReturnPreview2"></span>
            </div>

            <div class="image-box" style="cursor: pointer;"
                onclick="openPreviewFromSpan('{{ $p }}_imgReturnPreview3')">
                <span id="{{ $p }}_imgReturnPreview3"></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">หัวข้อผลการรับทราบ</label>
                <input type="text" id="{{ $p }}_accept_header" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">วันที่รับทราบ</label>
                <input type="text" id="{{ $p }}_accept_at" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">เหตุผล/รายละเอียด</label>
                <textarea id="{{ $p }}_accept_reason" class="form-control bg-light" rows="3" readonly></textarea>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================
    REQUIRED JS HELPERS (ต้องมีในหน้า parent .blade)
    - openImageModal()
    - previewImage()
    - setPreviewFromUrl()
    - setReturnPreviewFromUrl()
    - handleImageBoxClick()
    - openPreviewFromSpan()
========================================================= --}}
<script>
    // คลิกกรอบรูปใน add/edit:
    // - ถ้ายังไม่มีรูป => เปิด file picker
    // - ถ้ามีรูปแล้ว และคลิกที่รูป => เปิด modal ดูรูปใหญ่
    function handleImageBoxClick(e, previewSpanId, inputId) {
        // ถ้าคลิกที่ปุ่มลบ หรือ element อื่นที่มี stop แล้ว ให้ข้าม
        if (e && (e.target.closest('.remove-btn') || e.target.tagName === 'IMG')) return;

        const span = document.getElementById(previewSpanId);
        if (!span) return;

        const img = span.querySelector('img');
        if (img && img.getAttribute('src')) {
            // มีรูปแล้ว => เปิดดูรูปใหญ่
            openImageModal(img.getAttribute('src'));
            return;
        }

        // ไม่มีรูป => เปิดเลือกไฟล์
        const input = document.getElementById(inputId);
        if (input) input.click();
    }

    // ใช้สำหรับโหมด view หรือ return preview: คลิกกรอบแล้วเปิด modal ถ้ามีรูป
    function openPreviewFromSpan(spanId) {
        const span = document.getElementById(spanId);
        if (!span) return;
        const img = span.querySelector('img');
        if (img && img.getAttribute('src')) {
            openImageModal(img.getAttribute('src'));
        }
    }
</script>
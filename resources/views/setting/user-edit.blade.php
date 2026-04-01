@extends('layouts.master-layouts')

@section('title')
    Home
@endsection

@section('css')
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .hidden {
            display: none;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Home
        @endslot
        @slot('title')
            Home
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body border-bottom" style="display:flex;justify-content:space-between;align-items:center;">
                    <div style="margin-right:auto;color:black;font-size:14px;">แก้ไขผู้ใช้งาน</div>
                </div>

                <div class="card-body">

                    {{-- ====== สำคัญ: ส่งข้อมูลเดิมให้ JS prefill (RAW จาก DB) ====== --}}
                    <input type="hidden" id="edit_user_id" value="{{ $id }}">
                    <input type="hidden" id="userRole" value="{{ $user->role }}">
                    <input type="hidden" id="userBranch" value="{{ $user->hwh_branch }}">

                    {{-- ✅ sections RAW จาก DB (อาจเป็น TH หรือ EN) --}}
                    <input type="hidden" id="userSectionsRaw" value='@json($user->sections ?? [])'>
                    <input type="hidden" id="userSectionIds" value='@json($user->section_ids ?? [])'>

                    {{-- departments เดิม --}}
                    <input type="hidden" id="userDepartments" value='@json($user->departments ?? [])'>
                    <input type="hidden" id="userDepartmentIds" value='@json($user->department_ids ?? [])'>

                    <form id="edit-user-form">
                        @csrf
                        <div class="container">
                            <div class="row gy-3">

                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label">ชื่อ</label>
                                    <input type="text" class="form-control" name="name" id="name" required
                                        value="{{ $user->name }}">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="lastname" class="form-label">นามสกุล</label>
                                    <input type="text" class="form-control" name="lastname" id="lastname" required
                                        value="{{ $user->lastname }}">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="username" class="form-label">ชื่อผู้ใช้งาน</label>
                                    <input type="text" class="form-control" name="username" id="username" required
                                        value="{{ $user->username }}">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" name="email" id="email" required
                                        value="{{ $user->email }}">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="branch" class="form-label">สาขา <span style="color:red;">*</span></label>
                                    <select name="branch" id="branch" class="form-select" required>
                                        <option value="">กรุณาเลือกสาขา</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->branch_desc }}"
                                                data-branch-code="{{ $branch->branch_code }}"
                                                {{ $user->hwh_branch == $branch->branch_desc ? 'selected' : '' }}>
                                                {{ $branch->branch_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="role" class="form-label">บทบาท <span style="color:red;">*</span></label>
                                    <select name="role" id="role" class="form-select" required>
                                        <option value="" disabled>กรุณาเลือกบทบาท</option>
                                        <option value="user">User (พนักงาน)</option>
                                        {{-- <option value="division">Division (ผู้ช่วยฝ่าย)</option> --}}
                                        <option value="manager">Manager (ฝ่าย)</option>
                                        <option value="executive">Executive (ผู้บริหาร)</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="job_position" class="form-label">
                                        ตำแหน่ง <span style="color:red;">*</span>
                                    </label>

                                    <select name="job_position_id" id="job_position" class="form-select" required>
                                        <option value="">กรุณาเลือกตำแหน่ง</option>

                                        @foreach ($positions as $position)
                                            <option value="{{ $position->id }}"
                                                {{ $user->job_position_id == $position->id ? 'selected' : '' }}>
                                                {{ $position->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- ✅ ฝ่าย: value เป็น TH --}}
                                <div class="col-12 col-md-6">
                                    <label for="section" class="form-label">ฝ่าย <span
                                            style="color:red;">*</span></label>
                                    <select name="section[]" id="section" class="form-select" disabled>
                                        <option value="" selected disabled>กรุณาเลือกบทบาทก่อน</option>
                                    </select>

                                    {{-- hidden: section_id[] + section_en[] --}}
                                    <div id="section_id_hidden_inputs"></div>
                                    <div id="section_en_hidden_inputs"></div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="department" class="form-label">แผนก <span
                                            style="color:red;">*</span></label>

                                    {{-- role ปกติ: เลือก 1 แผนก --}}
                                    <select id="department" class="form-select" disabled>
                                        <option value="" selected disabled>กรุณาเลือกฝ่ายก่อน</option>
                                    </select>

                                    <div id="department_notice" class="form-text text-success hidden">
                                        ดูได้ทุกแผนกของฝ่ายที่เลือก (จะแสดงรายชื่อด้านบน)
                                    </div>

                                    {{-- ส่งไป backend --}}
                                    <div id="department_hidden_inputs"></div>
                                    <div id="department_id_hidden_inputs"></div>
                                </div>

                            </div>
                        </div>
                    </form>

                    <div style="display:flex;justify-content:right;padding-top:20px;">
                        <button class="btn btn-success" type="button" id="edit-user-submit">บันทึก</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/select2/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            const $branch = $('#branch');
            const $role = $('#role');
            const $section = $('#section');
            const $department = $('#department');

            const $deptNotice = $('#department_notice');

            const $deptHidden = $('#department_hidden_inputs'); // department_th[]
            const $deptIdHidden = $('#department_id_hidden_inputs'); // department_id[]
            const $sectionIdHidden = $('#section_id_hidden_inputs'); // section_id[]
            const $sectionEnHidden = $('#section_en_hidden_inputs'); // section_en[]

            // cache dept by section_en: [{id, th}, ...]
            const deptCache = {};

            function isManagerOrExe() {
                const r = $role.val();
                return r === 'manager' || r === 'executive';
            }

            function destroySelect2($el) {
                if ($.fn.select2 && $el.hasClass("select2-hidden-accessible")) {
                    $el.select2('destroy');
                }
            }

            function parseJson(selector, fallback) {
                try {
                    return JSON.parse($(selector).val() || JSON.stringify(fallback));
                } catch (e) {
                    return fallback;
                }
            }

            // ---------- hidden helpers ----------
            function clearHiddenDepartments() {
                $deptHidden.empty();
                $deptIdHidden.empty();
            }

            function setHiddenDepartmentsFromObjects(deptObjs) {
                clearHiddenDepartments();
                (deptObjs || []).forEach(obj => {
                    if (!obj) return;
                    if (obj.th) $deptHidden.append(
                        `<input type="hidden" name="department[]" value="${obj.th}">`);
                    if (obj.id !== null && obj.id !== undefined && obj.id !== '') {
                        $deptIdHidden.append(
                            `<input type="hidden" name="department_id[]" value="${obj.id}">`);
                    }
                });
            }

            function clearHiddenSectionMeta() {
                $sectionIdHidden.empty();
                $sectionEnHidden.empty();
            }

            // select value = section_th แต่สร้าง hidden section_id[] + section_en[] จาก data-*
            function syncHiddenSectionMeta() {
                clearHiddenSectionMeta();

                let vals = $section.val(); // section_th[]
                if (!vals) vals = [];
                if (!Array.isArray(vals)) vals = [vals];
                vals = vals.filter(v => v && v !== "");

                vals.forEach(secTh => {
                    const $opt = $section.find(`option[value="${CSS.escape(secTh)}"]`);
                    const secId = $opt.data('section-id') ?? $opt.data('sectionid');
                    const secEn = $opt.data('section-en') ?? $opt.data('sectionen');

                    if (secId !== undefined && secId !== null && secId !== '') {
                        $sectionIdHidden.append(
                            `<input type="hidden" name="section_id[]" value="${secId}">`);
                    }
                    if (secEn !== undefined && secEn !== null && secEn !== '') {
                        $sectionEnHidden.append(
                            `<input type="hidden" name="section_en[]" value="${secEn}">`);
                    }
                });
            }

            function getSelectedSectionEns() {
                return $('#section_en_hidden_inputs input[name="section_en[]"]').map(function() {
                    return $(this).val();
                }).get().filter(v => v && v !== "");
            }

            // ---------- reset helpers ----------
            function resetDepartment(message = 'กรุณาเลือกฝ่ายก่อน') {
                destroySelect2($department);

                $department.prop('disabled', true)
                    .removeAttr('multiple')
                    .html(`<option value="" disabled selected hidden>${message}</option>`);

                $deptNotice.addClass('hidden');
                clearHiddenDepartments();

                // ไม่ล้าง cache ตอน prefill จะได้ไม่โหลดซ้ำก็ได้
            }

            function resetSection(message = 'กรุณาเลือกบทบาทก่อน') {
                destroySelect2($section);

                $section.removeAttr('multiple')
                    .prop('disabled', true)
                    .html(`<option value="" disabled selected hidden>${message}</option>`);

                clearHiddenSectionMeta();
                resetDepartment();
            }

            // ---------- map user stored sections (TH/EN) -> TH values in select ----------
            function mapStoredSectionsToTHValues(storedArr) {
                const result = [];
                const uniq = new Set();

                (storedArr || []).forEach(raw => {
                    const s = (raw || '').trim();
                    if (!s) return;

                    // 1) match by value (TH)
                    let $opt = $section.find(`option[value="${CSS.escape(s)}"]`);

                    // 2) match by data-section-en (EN)
                    if (!$opt.length) {
                        $opt = $section.find(`option[data-section-en="${CSS.escape(s)}"]`);
                    }

                    // 3) match by text (TH)
                    if (!$opt.length) {
                        $opt = $section.find('option').filter(function() {
                            return ($(this).text() || '').trim() === s;
                        }).first();
                    }

                    if ($opt.length) {
                        const thVal = $opt.val();
                        if (thVal && !uniq.has(thVal)) {
                            uniq.add(thVal);
                            result.push(thVal);
                        }
                    }
                });

                return result;
            }

            // ---------- union departments (unique by dept id) + show names ----------
            function recomputeDepartmentsUnion() {
                if (!isManagerOrExe()) return;

                const sectionEns = getSelectedSectionEns();
                const map = new Map(); // key = deptId (fallback th)

                sectionEns.forEach(secEn => {
                    const deps = deptCache[secEn] || [];
                    deps.forEach(d => {
                        if (!d) return;
                        const key = d.id || d.th;
                        if (!key) return;
                        if (!map.has(key)) map.set(key, d);
                    });
                });

                const union = Array.from(map.values());
                setHiddenDepartmentsFromObjects(union);

                // show as disabled multi select with selected names
                destroySelect2($department);

                $department.attr('multiple', true)
                    .prop('disabled', true)
                    .empty();

                if (union.length === 0) {
                    $department.append(`<option disabled selected>ไม่พบแผนก</option>`);
                } else {
                    union.forEach(d => {
                        $department.append(`<option value="${d.id ?? ''}" selected>${d.th ?? ''}</option>`);
                    });
                }

                $deptNotice.removeClass('hidden');
            }

            // ---------- fetch departments by section_en ----------
            function fetchDepartmentsForSection(sectionEn) {
                if (!sectionEn) return;

                if (deptCache[sectionEn]) {
                    recomputeDepartmentsUnion();
                    return;
                }

                $.ajax({
                    url: "{{ route('departments-bySection') }}",
                    type: "GET",
                    traditional: true,
                    data: {
                        section_en: [sectionEn]
                    },
                    success: function(data) {
                        const map = new Map();

                        (data || []).forEach(item => {
                            const depId = item.id ?? item.department_id ?? null;
                            const depTH = item.department_th ?? null;

                            const key = depId || depTH;
                            if (!key) return;

                            if (!map.has(key)) map.set(key, {
                                id: depId,
                                th: depTH
                            });
                        });

                        deptCache[sectionEn] = Array.from(map.values());
                        recomputeDepartmentsUnion();
                    },
                    error: function() {
                        deptCache[sectionEn] = [];
                        recomputeDepartmentsUnion();
                    }
                });
            }

            // ---------- normal role: load dept select + prefill ----------
            function loadDepartmentNormalRole(prefillDeptId = null, prefillDeptTH = null) {
                if (isManagerOrExe()) return;

                const sectionEns = getSelectedSectionEns();
                const sectionEn = sectionEns[0] ?? null;

                if (!sectionEn) {
                    resetDepartment();
                    return;
                }

                $department.prop('disabled', true)
                    .removeAttr('multiple')
                    .html('<option value="" disabled selected hidden>กำลังโหลด...</option>');

                $.ajax({
                    url: "{{ route('departments-bySection') }}",
                    type: "GET",
                    traditional: true,
                    data: {
                        section_en: [sectionEn]
                    },
                    success: function(data) {

                        if (!data || data.length === 0) {
                            resetDepartment('ไม่พบแผนก');
                            return;
                        }

                        destroySelect2($department);
                        $department.removeAttr('multiple').empty();
                        $department.append(
                            '<option value="" disabled selected hidden>กรุณาเลือกแผนก</option>');

                        data.forEach(item => {
                            const depId = item.id ?? item.department_id ?? '';
                            const depTH = item.department_th ?? '';
                            $department.append(
                                `<option value="${depId}" data-department-th="${depTH}">${depTH}</option>`
                            );
                        });

                        $deptNotice.addClass('hidden');
                        clearHiddenDepartments();
                        $department.prop('disabled', false);

                        // prefill
                        if (prefillDeptId) {
                            $department.val(String(prefillDeptId)).trigger('change');
                        } else if (prefillDeptTH) {
                            const $opt = $department.find(
                                `option[data-department-th="${CSS.escape(prefillDeptTH)}"]`);
                            if ($opt.length) $department.val($opt.val()).trigger('change');
                        }
                    },
                    error: function() {
                        resetDepartment('โหลดแผนกไม่สำเร็จ');
                    }
                });
            }

            // ---------- load sections by branch (value = TH) ----------
            function loadSectionByBranch(onDonePrefill = null) {

                const branchCode = $branch.find(':selected').data('branch-code');
                const roleVal = $role.val();

                if (!branchCode) {
                    resetSection('กรุณาเลือกสาขาก่อน');
                    return;
                }
                if (!roleVal) {
                    resetSection('กรุณาเลือกบทบาทก่อน');
                    return;
                }

                destroySelect2($section);
                $section.prop('disabled', true)
                    .html('<option value="" disabled selected hidden>กำลังโหลด...</option>');

                resetDepartment();
                clearHiddenSectionMeta();

                $.ajax({
                    url: "{{ route('sections-byBranch') }}",
                    type: "GET",
                    data: {
                        branch: branchCode
                    },
                    success: function(data) {

                        $section.empty();

                        if (!data || data.length === 0) {
                            resetSection('ไม่พบฝ่ายของสาขานี้');
                            return;
                        }

                        $section.append('<option disabled>กรุณาเลือกฝ่าย</option>');

                        data.forEach(item => {
                            const secId = item.section_id ?? item.id ?? '';
                            const secEn = item.section_en ?? '';
                            const secTh = item.section_th ?? '';

                            // ✅ value เป็น TH
                            $section.append(
                                `<option value="${secTh}"
                                    data-section-id="${secId}"
                                    data-section-en="${secEn}"
                                    data-section-th="${secTh}">
                                    ${secTh}
                                </option>`
                            );
                        });

                        $section.prop('disabled', false);

                        if (roleVal === 'manager' || roleVal === 'executive') {
                            $section.attr('multiple', true);

                            $section.select2({
                                placeholder: "กรุณาเลือกฝ่าย",
                                width: '100%',
                                allowClear: true
                            });

                            $section.off('select2:select select2:unselect');

                            $section.on('select2:select', function() {
                                syncHiddenSectionMeta();
                                getSelectedSectionEns().forEach(en =>
                                    fetchDepartmentsForSection(en));
                            });

                            $section.on('select2:unselect', function() {
                                syncHiddenSectionMeta();
                                const ens = getSelectedSectionEns();

                                Object.keys(deptCache).forEach(k => {
                                    if (!ens.includes(k)) delete deptCache[k];
                                });

                                if (ens.length === 0) {
                                    resetDepartment('กรุณาเลือกฝ่ายก่อน');
                                    return;
                                }

                                recomputeDepartmentsUnion();
                            });

                        } else {
                            $section.removeAttr('multiple');
                            $section.off('select2:select select2:unselect');
                        }

                        resetDepartment('กรุณาเลือกฝ่ายก่อน');

                        if (typeof onDonePrefill === 'function') onDonePrefill();
                    },
                    error: function() {
                        resetSection('โหลดฝ่ายไม่สำเร็จ');
                    }
                });
            }

            // ---------- prefill (แก้ให้รองรับ DB ที่เก็บ TH หรือ EN ปนกัน) ----------
            function prefillAfterSectionsLoaded() {
                const roleVal = $role.val();

                const storedSections = parseJson('#userSectionsRaw', []).filter(v => (v || '').trim() !== '');
                const deptTHs = parseJson('#userDepartments', []);
                const deptIds = parseJson('#userDepartmentIds', []);

                // ✅ map stored (TH/EN) -> TH values ที่มีใน option
                const sectionsTHToSelect = mapStoredSectionsToTHValues(storedSections);

                if (roleVal === 'manager' || roleVal === 'executive') {
                    $section.val(sectionsTHToSelect).trigger('change');
                    syncHiddenSectionMeta();

                    // fetch dept ของทุกฝ่ายด้วย EN (จาก hidden)
                    getSelectedSectionEns().forEach(en => fetchDepartmentsForSection(en));

                } else {
                    const firstSecTh = sectionsTHToSelect[0] ?? null;
                    if (firstSecTh) $section.val(firstSecTh).trigger('change');
                    syncHiddenSectionMeta();

                    // prefill dept (role ปกติ)
                    const prefillId = deptIds[0] ?? null;
                    const prefillTH = deptTHs[0] ?? null;
                    loadDepartmentNormalRole(prefillId, prefillTH);
                }
            }

            // ---------- events ----------
            resetSection();

            $branch.on('change', function() {
                loadSectionByBranch(function() {});
            });

            $role.on('change', function() {
                loadSectionByBranch(function() {});
            });

            $section.on('change', function() {
                syncHiddenSectionMeta();

                if (isManagerOrExe()) {
                    const ens = getSelectedSectionEns();

                    Object.keys(deptCache).forEach(sec => {
                        if (!ens.includes(sec)) delete deptCache[sec];
                    });

                    ens.forEach(en => fetchDepartmentsForSection(en));

                    if (ens.length === 0) {
                        resetDepartment('กรุณาเลือกฝ่ายก่อน');
                        return;
                    }

                    recomputeDepartmentsUnion();
                } else {
                    loadDepartmentNormalRole();
                }
            });

            // ---------- init ----------
            const initRole = $('#userRole').val();
            if (initRole) $role.val(initRole);

            loadSectionByBranch(prefillAfterSectionsLoaded);
        });
    </script>

    <script>
        $('#edit-user-submit').on('click', function(e) {
            e.preventDefault();

            const id = $('#edit_user_id').val();

            const name = $('#name').val();
            const lastname = $('#lastname').val();
            const fullname = (name || '') + ' ' + (lastname || '');
            const username = $('#username').val();
            const email = $('#email').val();

            const role = $('#role').val();
            const branch = $('#branch').val();
            const sectionTH = $('#section').val(); // TH (array)

            const job_position_id = $('#job_position').val();

            const isManagerOrExe = (role === 'manager' || role === 'executive');

            if (!username || !name || !lastname || !role || !branch || !sectionTH ||
                (Array.isArray(sectionTH) && sectionTH.length === 0)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ทุกช่องที่มีเครื่องหมาย * จำเป็นต้องกรอก!',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            if (!job_position_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกตำแหน่ง',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // department_th[] + department_id[]
            let departmentsTHToSend = [];
            let departmentIdsToSend = [];

            if (isManagerOrExe) {
                $('#department_hidden_inputs input[name="department[]"]').each(function() {
                    departmentsTHToSend.push($(this).val());
                });
                $('#department_id_hidden_inputs input[name="department_id[]"]').each(function() {
                    departmentIdsToSend.push($(this).val());
                });
            } else {
                const depId = $('#department').val();
                const depTH = $('#department option:selected').data('department-th');
                if (!depId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณาเลือกแผนก',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }
                departmentIdsToSend = [depId];
                departmentsTHToSend = [depTH];
            }

            const sectionIdsToSend = $('#section_id_hidden_inputs input[name="section_id[]"]').map(function() {
                return $(this).val();
            }).get();

            const sectionEnsToSend = $('#section_en_hidden_inputs input[name="section_en[]"]').map(function() {
                return $(this).val();
            }).get();

            const formData = {
                id: id,
                email: email,
                branch: branch,
                username: username,
                name: name,
                lastname: lastname,
                fullname: fullname,
                role: role,

                job_position_id: job_position_id,

                section: sectionTH, // TH
                section_en: sectionEnsToSend, // EN
                section_id: sectionIdsToSend, // ID

                department: departmentsTHToSend,
                department_id: departmentIdsToSend,

                _token: $('meta[name="csrf-token"]').attr("content"),
            };

            Swal.fire({
                title: "แก้ไขผู้ใช้งาน",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "ใช่",
                cancelButtonText: "ไม่",
                confirmButtonColor: "#33cc33",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) Submit();
            });

            function Submit() {
                $.ajax({
                    url: "{{ route('editUserDB') }}",
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'แก้ไขผู้ใช้งานเรียบร้อย',
                                confirmButtonText: 'ปิด',
                                confirmButtonColor: '#d33'
                            }).then(() => {
                                window.location.href = "{{ route('user_manage') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: response.message || 'Failed',
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function(error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            confirmButtonText: 'Close',
                            confirmButtonColor: '#d33'
                        });
                        console.error(error);
                    }
                });
            }
        });
    </script>
@endsection

@extends('layouts.master-layouts')

@section('title')
    Home
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <!--datepicker-->
    <link href="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css">
    <!--select2-->
    <link href="{{ URL::asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert-->
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
            Add User
        @endslot
        @slot('title')
            Add User
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body border-bottom"
                    style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="margin-right: auto;color:black;font-size:14px;">เพิ่มผู้ใช้งาน</div>
                </div>

                <div class="card-body">
                    <form id="add-user-form">
                        @csrf
                        <div class="container">
                            <div class="row gy-3">

                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label">ชื่อ</label><span style="color: red;">*</span>
                                    <input type="text" class="form-control" name="name" id="name" required
                                        placeholder="กรุณากรอกชื่อผู้ใช้งาน">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="lastname" class="form-label">นามสกุล</label><span
                                        style="color: red;">*</span>
                                    <input type="text" class="form-control" name="lastname" id="lastname" required
                                        placeholder="กรุณากรอกนามสกุลผู้ใช้งาน">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="username" class="form-label">ชื่อผู้ใช้งาน ( กรุณาระบุรหัสพนักงาน ) <span
                                            style="color: red;">*</span></label>
                                    <input type="text" class="form-control" name="username" id="username" required
                                        placeholder="กรุณากรอกชื่อผู้ใช้งาน">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">E-mail <span
                                            style="color: red;">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email" required
                                        placeholder="กรุณากรอก E-mail">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="password_add" class="form-label">รหัสผ่าน <span
                                            style="color: red;">*</span></label>
                                    <input type="password" class="form-control" name="password" id="password_add" required
                                        placeholder="กรุณากรอกรหัสผ่าน">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="password_add_confirm" class="form-label">ยืนยันรหัสผ่าน <span
                                            style="color: red;">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation"
                                        id="password_add_confirm" placeholder="กรุณายืนยันรหัสผ่าน" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">สาขา <span style="color: red;">*</span></label>
                                    <select name="branch" id="branch" class="form-select" required>
                                        <option value="">กรุณาเลือกสาขา</option>
                                        @foreach (getBranchById() as $item)
                                            <option value="{{ $item->branch_desc }}"
                                                data-branch-code="{{ $item->branch_code }}">
                                                {{ $item->branch_desc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="role" class="form-label">บทบาท <span
                                            style="color: red;">*</span></label>
                                    <select name="role" id="role" class="form-select">
                                        <option value="">กรุณาเลือกบทบาท</option>
                                        <option value="user">User (พนักงาน)</option>
                                        {{-- <option value="division">Division (ผู้ช่วยฝ่าย)</option> --}}
                                        <option value="manager">Manager (ฝ่าย)</option>
                                        <option value="executive">Executive (ผู้บริหาร)</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="role" class="form-label">ตำแหน่ง <span
                                            style="color: red;">*</span></label>
                                    <select name="job_position_id" class="form-select">

                                        <option value="">กรุณาเลือกตำแหน่ง</option>

                                        @foreach ($positions as $pos)
                                            <option value="{{ $pos->id }}">
                                                {{ $pos->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                {{-- ✅ ฝ่าย: value เป็น TH --}}
                                <div class="col-12 col-md-6">
                                    <label for="section" class="form-label">ฝ่าย <span
                                            style="color: red;">*</span></label>
                                    <select name="section[]" id="section" class="form-select" disabled>
                                        <option value="" selected disabled>กรุณาเลือกบทบาทก่อน</option>
                                    </select>

                                    {{-- ✅ hidden: section_id[] + section_en[] --}}
                                    <div id="section_id_hidden_inputs"></div>
                                    <div id="section_en_hidden_inputs"></div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="department" class="form-label">แผนก <span
                                            style="color: red;">*</span></label>

                                    {{-- role ปกติ: เลือกแผนกได้ --}}
                                    <select name="department[]" id="department" class="form-select" disabled>
                                        <option value="" selected disabled>กรุณาเลือกฝ่ายก่อน</option>
                                    </select>

                                    {{-- ข้อความสำหรับ manager/executive --}}
                                    <div id="department_notice" class="form-text text-success hidden">
                                        ดูได้ทุกแผนกของฝ่ายที่เลือก (จะแสดงรายชื่อด้านบน)
                                    </div>

                                    {{-- hidden: department_th[] + department_id[] --}}
                                    <div id="department_hidden_inputs"></div>
                                    <div id="department_id_hidden_inputs"></div>
                                </div>

                            </div>
                        </div>
                    </form>

                    <div style="display: flex;justify-content: right;padding-top:20px;">
                        <button class="btn btn-success" type="submit" id="add-user-submit">บันทึก</button>
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

            // hidden containers
            const $deptHidden = $('#department_hidden_inputs'); // department_th[]
            const $deptIdHidden = $('#department_id_hidden_inputs'); // department_id[]
            const $sectionIdHidden = $('#section_id_hidden_inputs'); // section_id[]
            const $sectionEnHidden = $('#section_en_hidden_inputs'); // ✅ section_en[]

            // cache departments by section_en
            const deptCache = {}; // { "SEC_EN": [{id, th}, ...] }

            function isManagerOrExe() {
                const r = $role.val();
                return r === 'manager' || r === 'executive';
            }

            function destroySelect2($el) {
                if ($.fn.select2 && $el.hasClass("select2-hidden-accessible")) {
                    $el.select2('destroy');
                }
            }

            // -------------------------
            // Hidden helpers
            // -------------------------
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
                    if (obj.id) $deptIdHidden.append(
                        `<input type="hidden" name="department_id[]" value="${obj.id}">`);
                });
            }

            function clearHiddenSectionMeta() {
                $sectionIdHidden.empty();
                $sectionEnHidden.empty();
            }

            // ✅ สร้าง hidden section_id[] และ section_en[] จาก option ที่ถูกเลือก
            // (ตอนนี้ value ใน select เป็น section_th)
            function syncHiddenSectionMeta() {
                clearHiddenSectionMeta();

                let vals = $section.val(); // section_th[]
                if (!vals) vals = [];
                if (!Array.isArray(vals)) vals = [vals];
                vals = vals.filter(v => v && v !== "");

                vals.forEach(secTh => {
                    const $opt = $section.find(`option[value="${secTh}"]`);

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

            // -------------------------
            // Reset helpers
            // -------------------------
            function resetDepartment(message = 'กรุณาเลือกฝ่ายก่อน') {
                destroySelect2($department);

                $department.removeAttr('multiple')
                    .prop('disabled', true)
                    .html(`<option value="" disabled selected hidden>${message}</option>`);

                $deptNotice.addClass('hidden');
                clearHiddenDepartments();

                Object.keys(deptCache).forEach(k => delete deptCache[k]);
            }

            function resetSection(message = 'กรุณาเลือกบทบาทก่อน') {
                destroySelect2($section);

                $section.removeAttr('multiple')
                    .prop('disabled', true)
                    .html(`<option value="" disabled selected hidden>${message}</option>`);

                clearHiddenSectionMeta();
                resetDepartment();
            }

            // -------------------------
            // manager/executive: รวมแผนกแล้ว "แสดงชื่อแผนก" ใน select (disabled)
            // -------------------------
            function recomputeDepartmentsUnion() {
                if (!isManagerOrExe()) return;

                // ต้องใช้ section_en จาก hidden เพราะ select value เป็น th
                let sectionEns = $('#section_en_hidden_inputs input[name="section_en[]"]').map(function() {
                    return $(this).val();
                }).get();

                sectionEns = (sectionEns || []).filter(v => v && v !== "");

                const map = new Map();

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

                destroySelect2($department);

                $department
                    .attr('multiple', true)
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

            // -------------------------
            // fetch departments for section_en (cache)
            // -------------------------
            function fetchDepartmentsForSectionEn(sectionEn) {
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

                            if (!map.has(key)) {
                                map.set(key, {
                                    id: depId,
                                    th: depTH
                                });
                            }
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

            // -------------------------
            // role ปกติ: โหลดแผนกให้เลือกได้ (ต้องใช้ section_en)
            // -------------------------
            function loadDepartmentNormalRole() {
                if (isManagerOrExe()) return;

                // ได้ section_en จาก hidden
                let sectionEns = $('#section_en_hidden_inputs input[name="section_en[]"]').map(function() {
                    return $(this).val();
                }).get();

                const sectionEn = (sectionEns && sectionEns.length > 0) ? sectionEns[0] : null;

                if (!sectionEn) {
                    resetDepartment();
                    return;
                }

                $department.prop('disabled', true)
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
                        $department.removeAttr('multiple');
                        $department.empty();
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
                    },
                    error: function() {
                        resetDepartment('โหลดแผนกไม่สำเร็จ');
                    }
                });
            }

            // -------------------------
            // Load Section by Branch
            // -------------------------
            function loadSectionByBranch() {

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

                            // ✅ value เป็น TH, แต่เก็บ EN/ID ใน data-*
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

                            $section.val(null).trigger('change');
                            $section.off('select2:select select2:unselect');

                            $section.on('select2:select', function() {
                                syncHiddenSectionMeta();

                                // fetch dept by section_en
                                const ens = $(
                                        '#section_en_hidden_inputs input[name="section_en[]"]')
                                    .map(function() {
                                        return $(this).val();
                                    }).get();

                                (ens || []).forEach(en => fetchDepartmentsForSectionEn(en));
                            });

                            $section.on('select2:unselect', function() {
                                syncHiddenSectionMeta();

                                const ens = $(
                                        '#section_en_hidden_inputs input[name="section_en[]"]')
                                    .map(function() {
                                        return $(this).val();
                                    }).get();

                                // ลบ cache ที่ไม่อยู่ใน ens แล้ว
                                Object.keys(deptCache).forEach(k => {
                                    if (!ens.includes(k)) delete deptCache[k];
                                });

                                if (!ens || ens.length === 0) {
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
                    },
                    error: function() {
                        resetSection('โหลดฝ่ายไม่สำเร็จ');
                    }
                });
            }

            // -------------------------
            // INIT + Events
            // -------------------------
            resetSection();

            $branch.on('change', loadSectionByBranch);
            $role.on('change', loadSectionByBranch);

            $section.on('change', function() {
                syncHiddenSectionMeta();

                if (isManagerOrExe()) {

                    const ens = $('#section_en_hidden_inputs input[name="section_en[]"]').map(function() {
                        return $(this).val();
                    }).get();

                    const sectionEns = (ens || []).filter(v => v && v !== "");

                    // ลบ cache ของฝ่ายที่ถูกเอาออก
                    Object.keys(deptCache).forEach(sec => {
                        if (!sectionEns.includes(sec)) delete deptCache[sec];
                    });

                    // fetch ที่ยังไม่มี cache
                    sectionEns.forEach(en => fetchDepartmentsForSectionEn(en));

                    if (sectionEns.length === 0) {
                        resetDepartment('กรุณาเลือกฝ่ายก่อน');
                        return;
                    }

                    recomputeDepartmentsUnion();

                } else {
                    loadDepartmentNormalRole();
                }
            });

        });
    </script>

    <script>
        $('#add-user-submit').on('click', function(e) {
            e.preventDefault();

            const name = $('#name').val();
            const lastname = $('#lastname').val();
            const username = $('#username').val();
            const password = $('#password_add').val();
            const password_con = $('#password_add_confirm').val();
            const fullname = name + ' ' + lastname;

            const role = $('#role').val();
            const sectionTH = $('#section').val(); // ✅ ตอนนี้เป็น TH (array)

            const isManagerOrExe = (role === 'manager' || role === 'executive');

            const departmentForNormalRole = $('#department').val(); // role ปกติ: department_id

            const jobPosition = $('select[name="job_position_id"]').val();

            if (!username || !password || !name || !lastname || role === "" || !sectionTH || sectionTH.length ===
                0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ทุกช่องที่มีเครื่องหมาย * จำเป็นต้องกรอก!',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            if (!jobPosition) {
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกตำแหน่ง',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            if (!isManagerOrExe) {
                if (!departmentForNormalRole) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณาเลือกแผนก',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }
            }

            if (password !== password_con) {
                Swal.fire({
                    icon: 'error',
                    title: 'รหัสผ่านไม่เหมือนกัน กรุณาลองอีกครั้ง',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            // prepare department_th[] + department_id[]
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

                if (depId) {
                    departmentIdsToSend = [depId];
                    departmentsTHToSend = [depTH];
                }
            }

            const sectionIdsToSend = $('#section_id_hidden_inputs input[name="section_id[]"]').map(function() {
                return $(this).val();
            }).get();

            const sectionEnsToSend = $('#section_en_hidden_inputs input[name="section_en[]"]').map(function() {
                return $(this).val();
            }).get();

            const formData = {
                fullname: fullname,
                name: $('#name').val(),
                lastname: $('#lastname').val(),
                username: $('#username').val(),
                password: $('#password_add').val(),
                password_confirmation: $('#password_add_confirm').val(),
                role: $('#role').val(),

                job_position_id: $('select[name="job_position_id"]').val(),

                // ✅ ฝ่าย: ส่ง TH ที่เป็น value ของ select
                section: sectionTH,

                // ✅ ส่ง EN/ID แยก (เพื่อใช้ query/insert)
                section_en: sectionEnsToSend,
                section_id: sectionIdsToSend,

                department: departmentsTHToSend,
                department_id: departmentIdsToSend,

                email: $('#email').val(),
                branch: $('select[name="branch"]').val(),
                _token: $('meta[name="csrf-token"]').attr("content"),
            };

            Swal.fire({
                title: "เพิ่มผู้ใช้งาน",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "ใช่",
                cancelButtonText: "ไม่",
                confirmButtonColor: "#33cc33",
                cancelButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    Submit();
                }
            });

            function Submit() {
                $.ajax({
                    url: "{{ route('addUserDB') }}",
                    type: "POST",
                    data: formData,
                    // ✅ ห้ามใช้ traditional: true ใน POST ที่ส่ง array เข้า Laravel
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'บันทึกข้อมูลสำเร็จ',
                        }).then(() => {
                            window.location.href = "{{ route('user_manage') }}";
                        });
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                        });
                        console.log(err.responseJSON);
                    }
                });
            }
        });
    </script>
@endsection

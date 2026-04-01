<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use App\Models\UserSection;
use App\Models\Vendor;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Break_;
use Illuminate\Support\Facades\DB;
use App\Models\JobPosition;
use App\Models\UserDepartment;
use Illuminate\Support\Facades\Validator;


class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function user_manage(Request $request)
    {
        $users = DB::table('users')
            ->leftJoin('job_positions', 'users.job_position_id', '=', 'job_positions.id')
            ->leftJoin('u_section', 'users.id', '=', 'u_section.u_id')
            ->select([
                'users.id as id',
                'users.name',
                'users.lastname',
                'users.fullname',
                'users.username',
                'users.role',
                'users.email',
                'job_positions.name as position',
                DB::raw("GROUP_CONCAT(u_section.section SEPARATOR ' , ') as sections"),
                'users.last_login',
                'users.active_status',
                'users.hwh_branch',
            ])
            ->groupBy(
                'users.id',
                'users.name',
                'users.lastname',
                'users.fullname',
                'users.username',
                'users.role',
                'users.last_login',
                'users.active_status',
                'users.hwh_branch',
                'users.email',
                'job_positions.name'
            )
            ->whereNot('users.role', 'admin')
            ->get();

        return view("setting.user", compact('users'));
    }

    public function branch_manage()
    {
        $branches = Branch::orderBy('id', 'desc')->get();

        return view('setting.branch-manage', compact('branches'));
    }

    public function branch_add()
    {
        return view('setting.branch-add');
    }

    public function branch_store(Request $request)
    {
        $data = $request->only([
            'branch_code',
            'sap_code',
            'branch_desc',
            'branch_desc_en',
            'line_id',
            'branch_active',
            'company_name',
            'company_name_en',
            'company_addr',
            'company_addr_en',
            'company_tel',
            'company_fax'
        ]);

        $data['branch_active'] = $data['branch_active'] ?? 'Y';

        Branch::create($data);

        return redirect()->route('branch_manage')
            ->with('success', 'เพิ่มสาขาเรียบร้อย');
    }

    public function branch_edit($id)
    {
        $branch = Branch::findOrFail($id);

        return view('setting.branch-edit', compact('branch'));
    }

    public function branch_update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $data = $request->only([
            'branch_code',
            'sap_code',
            'branch_desc',
            'branch_desc_en',
            'line_id',
            'branch_active',
            'company_name',
            'company_name_en',
            'company_addr',
            'company_addr_en',
            'company_tel',
            'company_fax'
        ]);

        $branch->update($data);

        return redirect()->route('branch_manage')
            ->with('success', 'แก้ไขสาขาเรียบร้อย');
    }

    public function branch_delete($id)
    {
        Branch::where('id', $id)->delete();

        return redirect()->route('branch_manage')
            ->with('success', 'ลบสาขาเรียบร้อย');
    }

    public function job_position_manage(Request $request)
    {
        $positions = DB::table('job_positions')
            ->leftJoin('ho_sections', 'job_positions.section_id', '=', 'ho_sections.id')
            ->select([
                'job_positions.id',
                'job_positions.name as position_name',
                'ho_sections.section_en as section_name',
                'job_positions.created_at',
            ])
            ->get();

        return view("setting.job", compact('positions'));
    }

    public function user_edit(Request $request)
    {
        $id = $request->id;
        $positions = JobPosition::orderBy('name')->get();

        // ------- User + Sections + Departments -------
        $user = DB::table('users')
            ->where('users.id', $id)
            ->leftJoin('u_section', 'users.id', '=', 'u_section.u_id')
            ->leftJoin('u_department', 'users.id', '=', 'u_department.user_id')
            ->select([
                'users.id as id',
                'users.name',
                'users.lastname',
                'users.fullname',
                'users.username',
                'users.role',
                'users.email',
                'users.last_login',
                'users.active_status',
                'users.hwh_branch',
                'users.job_position_id',

                // sections
                DB::raw("GROUP_CONCAT(DISTINCT u_section.section ORDER BY u_section.section SEPARATOR ',') as sections"),
                DB::raw("GROUP_CONCAT(DISTINCT u_section.section_id ORDER BY u_section.section_id SEPARATOR ',') as section_ids"),

                // departments
                DB::raw("GROUP_CONCAT(DISTINCT u_department.department ORDER BY u_department.department SEPARATOR ',') as departments"),
                DB::raw("GROUP_CONCAT(DISTINCT u_department.department_id ORDER BY u_department.department_id SEPARATOR ',') as department_ids"),
            ])
            ->groupBy(
                'users.id',
                'users.name',
                'users.lastname',
                'users.fullname',
                'users.username',
                'users.role',
                'users.email',
                'users.last_login',
                'users.active_status',
                'users.hwh_branch',
                'job_position_id'
            )
            ->first();

        // ------- normalize ให้เป็น array เสมอ -------
        $user->sections = $user->sections
            ? array_values(array_filter(array_map('trim', explode(',', $user->sections))))
            : [];

        $user->section_ids = $user->section_ids
            ? array_values(array_filter(array_map('trim', explode(',', $user->section_ids))))
            : [];

        $user->departments = $user->departments
            ? array_values(array_filter(array_map('trim', explode(',', $user->departments))))
            : [];

        // department_id อาจมี null -> GROUP_CONCAT จะเป็น empty string / null
        $user->department_ids = $user->department_ids
            ? array_values(array_filter(array_map('trim', explode(',', $user->department_ids))))
            : [];

        $branches = Branch::where('branch_active', 'Y')
            ->orderBy('branch_desc')
            ->get();

        return view('setting.user-edit', compact('user', 'id', 'branches', 'positions'));
    }

    public function addUserDB(Request $request)
    {
        // กัน username ซ้ำ
        $existingUser = User::where('username', $request->username)->first();
        if ($existingUser) {
            return response()->json(['error' => 'Username already exists. Please choose a different username.'], 400);
        }

        $role = (string) $request->role;

        if (!$request->job_position_id) {
            return response()->json(['error' => 'กรุณาเลือกตำแหน่ง'], 422);
        }

        // -------------------------
        // normalize ให้เป็น array เสมอ
        // -------------------------
        $sections = $request->input('section', []);
        if (!is_array($sections)) $sections = [$sections];
        $sections = array_values(array_filter($sections, fn($v) => $v !== null && $v !== ''));

        $sectionIds = $request->input('section_id', []);
        if (!is_array($sectionIds)) $sectionIds = [$sectionIds];
        $sectionIds = array_values(array_filter($sectionIds, fn($v) => $v !== null && $v !== ''));

        // department_th[]
        $departmentsTH = $request->input('department', []);
        if (!is_array($departmentsTH)) $departmentsTH = [$departmentsTH];
        $departmentsTH = array_values(array_filter($departmentsTH, fn($v) => $v !== null && $v !== ''));

        // department_id[]
        $departmentIds = $request->input('department_id', []);
        if (!is_array($departmentIds)) $departmentIds = [$departmentIds];
        $departmentIds = array_values(array_filter($departmentIds, fn($v) => $v !== null && $v !== ''));

        // -------------------------
        // validate เบื้องต้น
        // -------------------------
        if (count($sections) === 0) {
            return response()->json(['error' => 'กรุณาเลือกฝ่าย'], 422);
        }

        if (count($sectionIds) === 0) {
            return response()->json(['error' => 'ไม่พบ section_id ของฝ่ายที่เลือก'], 422);
        }

        // manager/exe ต้องมีแผนก (union)
        if (in_array($role, ['manager', 'executive'])) {
            if (count($departmentsTH) === 0 || count($departmentIds) === 0) {
                return response()->json(['error' => 'ไม่พบแผนกจากฝ่ายที่เลือก'], 422);
            }
        } else {
            // role ปกติ ต้องเลือกแผนก 1 อัน (อย่างน้อย)
            if (count($departmentsTH) === 0 || count($departmentIds) === 0) {
                return response()->json(['error' => 'กรุณาเลือกแผนก'], 422);
            }
        }

        // -------------------------
        // กันซ้ำ department โดยใช้ department_id เป็นหลัก (ปลอดภัยสุด)
        // -------------------------
        $deptMap = []; // deptId => deptTH
        $maxDept = max(count($departmentIds), count($departmentsTH));

        for ($i = 0; $i < $maxDept; $i++) {
            $did = $departmentIds[$i] ?? null;
            $dth = $departmentsTH[$i] ?? null;

            if (!$did && !$dth) continue;

            // ถ้ามี id ใช้ id เป็น key
            if ($did) {
                $deptMap[$did] = $dth; // th อาจ null ได้ แต่ส่วนใหญ่จะมี
            } else {
                // fallback กรณีไม่มี id จริงๆ (ไม่แนะนำ แต่กันพัง)
                $deptMap['TH:' . $dth] = $dth;
            }
        }

        DB::beginTransaction();
        try {

            $user = User::create([
                'email'         => $request->email,
                'name'          => $request->name,
                'lastname'      => $request->lastname,
                'fullname'      => $request->fullname,
                'role'          => $role,
                'username'      => $request->username,
                'hwh_branch'    => $request->branch,
                'job_position_id' => $request->job_position_id,
                'u_pwd'         => $request->password,
                'active_status' => 1,
                'password'      => Hash::make($request->password),
            ]);

            // =========================
            // INSERT SECTIONS (+ section_id)
            // =========================
            // พยายามจับคู่ตาม index ก่อน (ต้องให้ JS ส่งตามลำดับเดียวกัน)
            $maxSec = max(count($sections), count($sectionIds));

            for ($i = 0; $i < $maxSec; $i++) {
                $sec   = $sections[$i]   ?? null; // section_en
                $secId = $sectionIds[$i] ?? null; // section_id

                if (!$sec && !$secId) continue;

                UserSection::create([
                    'u_id'       => $user->id,
                    'section_id' => $secId,
                    'section'    => $sec,
                ]);
            }

            // =========================
            // INSERT DEPARTMENTS (department_id + department_th)
            // =========================
            foreach ($deptMap as $key => $deptTH) {

                $deptId = null;

                // key รูปแบบ deptId ปกติ
                if (is_numeric($key)) {
                    $deptId = (int) $key;
                }

                // fallback key "TH:xxx"
                if (is_string($key) && str_starts_with($key, 'TH:')) {
                    $deptId = null;
                    $deptTH = substr($key, 3);
                }

                UserDepartment::create([
                    'user_id'       => $user->id,
                    'department_id' => $deptId,     // ✅ ส่งมาด้วย
                    'department'    => $deptTH,     // ✅ เก็บเป็น department_th
                ]);
            }

            DB::commit();
            return response()->json(['success' => 'User added successfully']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error'   => 'เกิดข้อผิดพลาด',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function user_add()
    {
        $branches = Branch::where('branch_active', 'Y')
            ->orderBy('branch_desc')
            ->get();

        $positions = JobPosition::orderBy('name')->get();

        return view('setting.user-add', compact('branches', 'positions'));
    }

    public function editUserDB(Request $request)
    {
        $id = $request->id;

        // -------- normalize arrays --------
        $role = (string) $request->role;

        $sections = $request->input('section', []);
        if (!is_array($sections)) $sections = [$sections];
        $sections = array_values(array_filter($sections, fn($v) => $v !== null && $v !== ''));

        $sectionIds = $request->input('section_id', []);
        if (!is_array($sectionIds)) $sectionIds = [$sectionIds];
        $sectionIds = array_values(array_filter($sectionIds, fn($v) => $v !== null && $v !== ''));

        $departmentsTH = $request->input('department', []); // department_th[]
        if (!is_array($departmentsTH)) $departmentsTH = [$departmentsTH];
        $departmentsTH = array_values(array_filter($departmentsTH, fn($v) => $v !== null && $v !== ''));

        $departmentIds = $request->input('department_id', []); // department_id[]
        if (!is_array($departmentIds)) $departmentIds = [$departmentIds];
        $departmentIds = array_values(array_filter($departmentIds, fn($v) => $v !== null && $v !== ''));

        // -------- validate --------
        if (count($sections) === 0) {
            return response()->json(['success' => false, 'message' => 'กรุณาเลือกฝ่าย'], 422);
        }
        if (count($sectionIds) === 0) {
            return response()->json(['success' => false, 'message' => 'ไม่พบ section_id ของฝ่ายที่เลือก'], 422);
        }

        // manager/exe ต้องมี dept ที่รวมมาแล้ว
        if (in_array($role, ['manager', 'executive']) && count($departmentsTH) === 0) {
            return response()->json(['success' => false, 'message' => 'ไม่พบแผนกจากฝ่ายที่เลือก'], 422);
        }

        // role ปกติ ต้องมี dept 1 อัน
        if (!in_array($role, ['manager', 'executive']) && (count($departmentsTH) === 0 || count($departmentIds) === 0)) {
            return response()->json(['success' => false, 'message' => 'กรุณาเลือกแผนก'], 422);
        }

        DB::beginTransaction();
        try {

            // -------- update main user --------
            User::where('id', $id)->update([
                'name'       => $request->name,
                'lastname'   => $request->lastname,
                'username'   => $request->username,
                'fullname'   => $request->name . ' ' . $request->lastname,
                'role'       => $role,
                'email'      => $request->email,
                'hwh_branch' => $request->branch,
                'job_position_id' => $request->job_position_id,
            ]);

            // -------- sync sections (delete แล้ว insert ใหม่ทั้งหมด) --------
            UserSection::where('u_id', $id)->delete();

            $max = max(count($sections), count($sectionIds));
            for ($i = 0; $i < $max; $i++) {
                $sec   = $sections[$i] ?? null;     // section_en
                $secId = $sectionIds[$i] ?? null;   // section_id

                if (!$sec && !$secId) continue;

                UserSection::create([
                    'u_id'       => $id,
                    'section_id' => $secId,
                    'section'    => $sec,
                ]);
            }

            // -------- sync departments (delete แล้ว insert ใหม่ทั้งหมด) --------
            UserDepartment::where('user_id', $id)->delete();

            // จับคู่ th กับ id ตาม index
            // manager/exe: มักส่งมาคู่กันทุกตัว
            // role ปกติ: ส่งมา 1 ตัว
            $maxD = max(count($departmentsTH), count($departmentIds));

            for ($i = 0; $i < $maxD; $i++) {
                $depTH = $departmentsTH[$i] ?? null;
                $depId = $departmentIds[$i] ?? null;

                if (!$depTH && !$depId) continue;

                UserDepartment::create([
                    'user_id'       => $id,
                    'department_id' => $depId,     // ✅ ส่งมาด้วยแล้ว
                    'department'    => $depTH,     // ✅ เก็บ TH
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'User edited successfully']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteUser(Request $request)
    {
        User::where('id', $request->id)->delete();

        return response()->json(['success' => 'User added successfully']);
    }

    public function userblock(Request $request)
    {

        if ($request->active_status == 1) {
            User::where('id', $request->id)->update([
                'active_status' => 0,
            ]);
        } else {
            User::where('id', $request->id)->update([
                'active_status' => 1,
            ]);
        }

        return response()->json(['success' => 'User blocked successfully']);
    }


    ////////////////////////////////////////////////////

    public function sectionsByBranch(Request $request)
    {
        $branch = $request->query('branch');

        if (!$branch) {
            return response()->json([]);
        }

        // เรียก helper/service
        $sections = getSectionWithBranch($branch);

        return response()->json($sections);
    }

    public function departmentsBySection(Request $request)
    {
        $sectionEn = $request->query('section_en');
        if (!$sectionEn) return response()->json([]);

        $departments = DB::table('departments')
            ->select('department_th', 'department_en', 'section_en', 'id')
            ->where('section_en', $sectionEn)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($departments);
    }

    public function type_request_manage()
    {
        $types = DB::table('type_request')
            ->leftJoin('ho_sections', 'type_request.section', '=', 'ho_sections.section_th')
            ->leftJoin('branchs', 'ho_sections.branch_code', '=', 'branchs.branch_code')
            ->select(
                'type_request.id',
                'type_request.section',
                'type_request.type_th',
                DB::raw('MIN(branchs.branch_desc) as branch_desc')
            )
            ->groupBy(
                'type_request.id',
                'type_request.section',
                'type_request.type_th'
            )
            ->orderBy('type_request.id')
            ->get();

        return view('setting.type-request', compact('types'));
    }

    public function type_request_add()
    {
        $sections = DB::table('ho_sections')
            ->leftJoin('branchs', 'ho_sections.branch_code', '=', 'branchs.branch_code')
            ->select(
                DB::raw('MIN(ho_sections.id) as id'),
                'ho_sections.section_th',
                'branchs.branch_desc'
            )
            ->where('ho_sections.active', 1)
            ->whereNotNull('branchs.branch_desc')
            ->groupBy('ho_sections.section_th', 'branchs.branch_desc')
            ->orderBy('branchs.branch_desc')
            ->orderBy('ho_sections.section_th')
            ->get();

        // group ตาม branch
        $branchSections = $sections
            ->groupBy('branch_desc')
            ->map(function ($items) {
                return $items
                    ->unique(fn($item) => $item->section_th . '-' . $item->branch_desc)
                    ->values()
                    ->map(fn($item) => [
                        'id' => $item->id,
                        'section_th' => $item->section_th
                    ]);
            });

        return view('setting.type-request-add', compact('branchSections'));
    }

    public function getTypeRequest(Request $request)
    {
        $sectionId = $request->section_id; // ✅ ถูกต้อง

        return DB::table('type_request')
            ->where('section_id', $sectionId)
            ->get();
    }

    public function getTypeBySection(Request $request)
    {
        $sectionId = $request->query('section_id');

        return DB::table('type_request')
            ->where('section_id', $sectionId)
            ->pluck('type_th');
    }

    public function type_request_store(Request $request)
    {
        $request->validate([
            'section' => ['required'],
            'type_th' => ['required', 'string'],
        ]);

        try {
            DB::table('type_request')->insert([
                'section_id' => $request->section_id,
                'section' => $request->section,
                'type_th' => $request->type_th,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function type_request_edit($id)
    {
        $type = DB::table('type_request')
            ->where('id', $id)
            ->first();

        $sections = DB::table('ho_sections')
            ->leftJoin('branchs', 'ho_sections.branch_code', '=', 'branchs.branch_code')
            ->select(
                DB::raw('MIN(ho_sections.id) as id'),
                'ho_sections.section_th',
                'branchs.branch_desc'
            )
            ->where('ho_sections.active', 1)
            ->whereNotNull('branchs.branch_desc')
            ->groupBy('ho_sections.section_th', 'branchs.branch_desc')
            ->orderBy('branchs.branch_desc')
            ->orderBy('ho_sections.section_th')
            ->get();

        $branchSections = $sections
            ->groupBy('branch_desc')
            ->map(function ($items) {
                return $items
                    ->unique(fn($item) => $item->section_th . '-' . $item->branch_desc)
                    ->values()
                    ->map(fn($item) => [
                        'id' => $item->id,
                        'section_th' => $item->section_th
                    ]);
            });

        // 🔥 ตรงนี้สำคัญ
        return view('setting.type-request-edit', compact('type', 'branchSections'));
    }

    public function type_request_update(Request $request, $id)
    {
        $request->validate([
            'section' => ['required', 'string'], // ✅ เปลี่ยนตรงนี้
            'type_th' => ['required', 'string'],
        ]);

        DB::table('type_request')
            ->where('id', $id)
            ->update([
                'section_id' => $request->section_id,
                'type_th' => $request->type_th,
                'updated_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    public function type_request_delete($id)
    {
        $type = DB::table('type_request')->where('id', $id)->first();

        DB::table('type_request')
            ->where('type_th', $type->type_th)
            ->delete();

        return redirect()->route('type_request_manage')
            ->with('success', 'ลบเรียบร้อย');
    }
}

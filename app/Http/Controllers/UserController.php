<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Break_;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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

    // public function create()
    // {
    //    $branchs = DB::table('branchs')
    //     ->select('branch_code', 'branch_desc')
    //     ->orderBy('branch_desc', 'ASC')
    //     ->get();


    //     return view('setting.user-add', compact('branchs'));
    // }

    // public function store(Request $request)
    // {
    //     // validate
    //     $request->validate([
    //         'fullname' => 'required',
    //         'username' => 'required|unique:users,username',
    //         'password' => 'required|confirmed',
    //         'role' => 'required',
    //         'section' => 'required',
    //         'email' => 'required|email|unique:users,email',
    //         'branch' => 'required|exists:branchs,branch_code',
    //     ]);

    //     DB::table('users')->insert([
    //         'fullname' => $request->fullname,
    //         'username' => $request->username,
    //         'password' => Hash::make($request->password),
    //         'role' => $request->role,
    //         'section' => is_array($request->section)
    //             ? json_encode($request->section)
    //             : $request->section,
    //         'email' => $request->email,
    //         'hwh_branch' => $request->branch,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     return response()->json([
    //         'success' => true
    //     ]);
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // public function user_manage(Request $request)
    // {
    //     $users = User::select([
    //         'id',
    //         'fullname',
    //         'username',
    //         'role',
    //         'section',
    //         'last_login',
    //         'active_status',
    //         'hwh_branch',
    //     ])->get();

    //     return view("setting.user", compact('users'));
    // }

    // public function user_edit(Request $request)
    // {
    //     $id = $request->id;
    //     $user = User::where('id', $id)->first();

    //     return view("setting.user-edit", compact('user', 'id'));
    // }


    // public function addUserDB(Request $request)
    // {

    //     User::create([
    //         // 'avatar' => 'images/user-icon.jpg',
    //         'fullname' => $request->fullname,
    //         'role' => $request->role,
    //         'hwh_branch' => $request->branch,
    //         'avatar' => "images/user-icon.jpg",
    //         'username' => $request->username,
    //         'u_pwd' => $request->password,
    //         'active_status' => 1,
    //         'password' => Hash::make($request->password)
    //     ]);

    //     return response()->json(['success' => 'User added successfully']);
    // }

    // public function editUserDB(Request $request)
    // {
    //     if ($request->password == "") {
    //         User::where('id', $request->id)->update([
    //             'username' => $request->username,
    //             'name' => $request->fullname,
    //         ]);
    //     } else {
    //         User::where('id', $request->id)->update([
    //             'username' => $request->username,
    //             'name' => $request->fullname,
    //             'u_pwd' => $request->password,
    //             'password' => Hash::make($request->password),
    //         ]);
    //     }

    //     return response()->json(['success' => 'User saved successfully']);
    // }

    // public function editUserDB(Request $request)
    // {
    //     User::where('id', $request->id)->update([
    //         'username' => $request->username,
    //         'fullname' => $request->fullname,
    //         'hwh_branch' => $request->branch,
    //         'role' => $request->role,
    //     ]);


    //     return response()->json(['success' => 'User saved successfully']);
    // }

    // public function deleteUser(Request $request)
    // {
    //     User::where('id', $request->id)->delete();

    //     return response()->json(['success' => 'User added successfully']);
    // }


    // public function userblock(Request $request)
    // {

    //     if ($request->active_status == 1) {
    //         User::where('id', $request->id)->update([
    //             'active_status' => 0,
    //         ]);
    //     } else {
    //         User::where('id', $request->id)->update([
    //             'active_status' => 1,
    //         ]);
    //     }

    //     return response()->json(['success' => 'User added successfully']);
    // }
}

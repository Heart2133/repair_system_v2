<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */

    public function reset_password($token)
    {
        // ค้นหาผู้ใช้งานโดยใช้ token
        $user = User::where('verification_token', $token)->first();

        // ตรวจสอบว่าพบผู้ใช้หรือไม่
        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired verification link.');
        }

        // ดึง email ก่อนลบ token
        $email = $user->email;

        // ลบ token
        // $user->verification_token = null;
        // $user->save();

        // ส่ง email ไปยัง view
        return view("auth.passwords.reset", ['email' => $email, 'token' => $token]);
    }

    public function change_passwords(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        $user = User::where('verification_token', $request->token)
            ->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired token.');
        }

        // อัปเดตรหัสผ่านใหม่
        $user->u_pwd = $request->password;
        $user->password = Hash::make($request->password);
        $user->verification_token = null; // ลบ token
        $user->save();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully.');
    }
}

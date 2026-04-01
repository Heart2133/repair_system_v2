<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected $redirectTo = RouteServiceProvider::POPUP;

    public function forgot_password()
    {
        return view("auth.passwords.email");
    }

    public function forgot_password_email(Request $request)
    {
        $this->validateRequest($request);

        $user = $this->checkEmail($request);

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found in the database.'])->withInput();
        }

        $user = $this->checkStatus($request);

        if (!$user) {
            return back()->withErrors(['email' => 'Email is not verify. Please verify your email first.'])->withInput();
        }

        $token = $this->create($user); // รับ token ที่อัปเดต

        $user->verification_token = $token; // อัปเดต token ในตัวแปร $user

        $this->sendVerificationEmail($user);

        return redirect()->route('ver_reset')->with([
            'success' => 'Registration successful. Please check your email to verify your account.',
            'email' => $user->email,
        ]);
    }

    protected function create($user)
    {
        $token = Str::random(64);
        User::where('email', $user->email)->update(['verification_token' => $token]);

        return $token; // คืนค่ากลับสำหรับใช้งานต่อ
    }



    protected function validateRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    }

    protected function checkEmail(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            // หากไม่พบ ให้คืนค่า null
            return null;
        }

        return $user;
    }

    protected function checkStatus(Request $request)
    {
        $user = User::where('email', $request->input('email'))
            ->where('status', 1) // ต้องแยก where() ออกมา
            ->first();

        if (!$user) {
            // หากไม่พบ ให้คืนค่า null
            return null;
        }

        return $user;
    }

    protected function sendVerificationEmail($user)
    {
        try {
            // ตรวจสอบค่าของ $user
            if (!$user || !$user->email) {
                throw new \Exception('User or email is invalid.');
            }

            // ดีบักข้อมูล
            // \Log::info('Sending email to: ' . $user->email);

            Mail::send('email.reset-head', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Reset Your Password');
            });
        } catch (\Exception $e) {
            // เก็บ error ลง log
            // \Log::error('Failed to send verification email: ' . $e->getMessage());
            throw new \Exception('Unable to send verification email. Please try again later.');
        }
    }
}

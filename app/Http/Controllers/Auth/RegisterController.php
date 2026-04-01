<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::POPUP;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'tel' => ['required', 'digits:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'captcha' => 'required|captcha',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'tel' => $data['tel'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => '0', // Default status for inactive account
            'role' => "user",
            'u_pwd' => $data['password'],
            'verification_token' => Str::random(64), // Unique verification token
        ]);
    }

    /**
     * Override register method to handle redirection to verification page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate ข้อมูลที่รับเข้ามา
        $this->validator($request->all())->validate();

        try {
            // ✅ สร้าง verification token **ครั้งเดียว**
            $verificationToken = Str::random(64);

            // ✅ ใช้ token เดียวกันกับที่ส่งไปในอีเมล
            $tempUser = new User([
                'tel' => $request->tel,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => '0',
                'role' => "user",
                'u_pwd' => $request->password,
                'verification_token' => $verificationToken, // ✅ ใช้ token ที่สร้างไว้
            ]);

            $this->sendVerificationEmail($tempUser);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to send verification email to ' . $tempUser->email);
        }

        // ✅ บันทึก user พร้อมใช้ token เดียวกัน
        $user = User::create([
            'tel' => $request->tel,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => '0',
            'role' => "user",
            'u_pwd' => $request->password,
            'verification_token' => $verificationToken, // ✅ ใช้ token ที่สร้างไว้
        ]);

        // Redirect ไปหน้า verification
        return redirect()->route('ver_email')->with([
            'success' => 'สมัครสมาชิกสำเร็จ กรุณาตรวจสอบอีเมลของคุณ',
            'email' => $user->email,
        ]);
    }




    /**
     * Send verification email to the user.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    protected function sendVerificationEmail($user)
    {
        try {
            Mail::send('email.verify-head', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Verify Your Email Address');
            });
        } catch (\Exception $e) {
            // Handle email sending errors
            throw new \Exception('Unable to send verification email. Please try again later.');
        }
    }

    /**
     * Handle email verification by token.
     *
     * @param  string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        // dd($user);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired verification link.');
        }

        // Activate the user account
        $user->status = '1';
        $user->verification_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Your email has been verified. You can now log in.');
    }
}

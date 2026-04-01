<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    // protected $redirectToRepair = RouteServiceProvider::REPAIR;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function email()
    {
        return 'email';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function validateLogin(Request $request)
    {
        // Validate input fields
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha',
        ]);

        // dd($request->validate);

        // Check if email exists
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            // Return error if email is not found
            return redirect()->back()->withErrors([
                'username' => 'This username is not available, please try again.',
            ]);
        }

        // Check if account is inactive
        if ($user->active_status === 0) {
            return redirect()->back()->withErrors([
                'username' => 'Your username is inactive. Please contact Admin.',
            ]);
        }

        // Attempt login with email and password
        if (!Auth::attempt($request->only('username', 'password'), $request->filled('remember'))) {
            return redirect()->back()->withErrors([
                'password' => 'The password is incorrect.',
            ]);
        }

        // dd(Auth::user()->role);

        User::where('username', $request->username)->update([
            'last_login' => Carbon::now()->toDateTimeString()
        ]);

        // if (Auth::user()->role == "admin") {
            // echo 'admin';
            // exit;
            return redirect()->intended($this->redirectTo);
        // } else {
        //     // echo 'another';
        //     // exit;
        //     return redirect()->intended($this->redirectToRepair);
        // }
    }



    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
}

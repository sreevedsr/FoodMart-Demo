<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::validate($credentials)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->is_email_verified) {
            return back()->withErrors(['email' => 'Email not verified.']);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        session([
            '2fa_user_id' => $user->id,
            '2fa_otp' => $otp,
            '2fa_expires_at' => now()->addMinutes(5)
        ]);

        // Send OTP via SMTP using Mailable
        Mail::to($user->email)->send(new SendOtpMail($otp));

        return redirect()->route('2fa.form')->with('success', 'OTP sent to your email.');
    }

    public function showSignup()
    {
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Please enter your name.',
            'email.required' => 'We need your email to create an account.',
            'email.unique' => 'This email is already registered. Try logging in.',
            'password.required' => 'Don’t forget to set a password!',
            'password.confirmed' => 'Passwords do not match. Please try again.',
            'password.min' => 'Password must be at least 6 characters long.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function dashboard()
    {
        return redirect()->route('dashboard')->with('swap_user_to_home', true);
    }

    public function show2faForm()
    {
        return view('auth.2fa');
    }

    public function verify2fa(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otp = session('2fa_otp');
        $expiresAt = session('2fa_expires_at');

        if (!$otp || now()->gt($expiresAt)) {
            return back()->withErrors(['OTP expired.']);
        }

        if ($request->otp != $otp) {
            return back()->withErrors(['Invalid OTP.']);
        }

        Auth::loginUsingId(session('2fa_user_id'));
        session()->forget(['2fa_user_id', '2fa_otp', '2fa_expires_at']);

        return redirect()->route('home');
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();
        $field = $request->field;
        $value = $request->value;

        if ($field === 'name')
            $user->name = $value;
        if ($field === 'password' && $value)
            $user->password = Hash::make($value);

        $user->save();

        return response()->json(['success' => true]);
    }

    public function sendOtp(Request $request)
    {
        $user = Auth::user();
        $otp = rand(100000, 999999);

        session([
            'email_otp' => $otp,
            'otp_user_id' => $user->id,
            'email_otp_expires_at' => now()->addMinutes(5)
        ]);

        Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Verify your email');
        });

        return redirect()->route('otp.form')->with('success', 'OTP sent to your email!');
    }

    public function showOtpForm()
    {
        return view('profile.email_otp'); // Blade view for OTP input
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        $otp = session('email_otp');
        $userId = session('otp_user_id');
        $expiresAt = session('email_otp_expires_at');

        if (!$otp || now()->gt($expiresAt)) {
            return back()->withErrors(['otp' => 'OTP expired.']);
        }

        if ($request->otp != $otp) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        $user = User::find($userId);
        $user->is_email_verified = 1; // Update database
        $user->save();

        session()->forget(['email_otp', 'otp_user_id', 'email_otp_expires_at']);

        return redirect()->route('profile')->with('success', 'Email verified successfully!');
    }
    public function show()
{
    return view('dashboard'); // make sure this Blade exists
}

}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;

class AuthController extends Controller
{
    // Show login page (web only)
    public function showLogin(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Use /api/login for API requests'], 400);
        }
        return view('auth.login');
    }

    // Login (web or API)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::validate($credentials)) {
            $message = 'Invalid credentials.';
            if ($request->wantsJson()) return response()->json(['message' => $message], 401);
            return back()->withErrors(['email' => $message])->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->is_email_verified) {
            $message = 'Email not verified.';
            if ($request->wantsJson()) return response()->json(['message' => $message], 403);
            return back()->withErrors(['email' => $message]);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        session([
            '2fa_user_id' => $user->id,
            '2fa_otp' => $otp,
            '2fa_expires_at' => now()->addMinutes(5)
        ]);

        Mail::to($user->email)->send(new SendOtpMail($otp));

        $message = 'OTP sent to your email.';

        if ($request->wantsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->route('2fa.form')->with('success', $message);
    }

    // Show signup page (web only)
    public function showSignup(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Use /api/signup for API requests'], 400);
        }
        return view('auth.signup');
    }

    // Signup (web or API)
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

        if ($request->wantsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'message' => 'Signup successful',
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 201);
        }

        Auth::login($user);
        return redirect()->route('home');
    }

    // Show 2FA form (web only)
    public function show2faForm(Request $request)
    {
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Use /api/2fa-verify for API requests'], 400);
        }
        return view('auth.2fa');
    }

    // Verify OTP (web or API)
    public function verify2fa(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otp = session('2fa_otp');
        $expiresAt = session('2fa_expires_at');

        if (!$otp || now()->gt($expiresAt)) {
            $message = 'OTP expired.';
            if ($request->wantsJson()) return response()->json(['message' => $message], 400);
            return back()->withErrors([$message]);
        }

        if ($request->otp != $otp) {
            $message = 'Invalid OTP.';
            if ($request->wantsJson()) return response()->json(['message' => $message], 400);
            return back()->withErrors([$message]);
        }

        $user = User::find(session('2fa_user_id'));

        if ($request->wantsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;
            session()->forget(['2fa_user_id', '2fa_otp', '2fa_expires_at']);
            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]);
        }

        Auth::loginUsingId($user->id);
        session()->forget(['2fa_user_id', '2fa_otp', '2fa_expires_at']);
        return redirect()->route('home');
    }

    // Logout (web or API)
    public function logout(Request $request)
    {
        if ($request->wantsJson()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    // Merge session cart with user cart
    protected function mergeCart($user)
    {
        $sessionItems = session('cart.items', []);

        if (!empty($sessionItems)) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            foreach ($sessionItems as $item) {
                $cartItem = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                if ($cartItem) {
                    $cartItem->quantity += $item['quantity'];
                    $cartItem->save();
                } else {
                    CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }

            session()->forget('cart.items');
        }
    }
}

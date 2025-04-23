<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OtpNotification;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function generateOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No user found with this email.'], 404);
            }
            return back()->with('error', 'No user found with this email.');
        }

        // Generate OTP
        $otp = Otp::generate($user->email);

        $this->sendOtp($user->email, $otp->otp); // Assumes Otp model handles OTP generation
        if ($request->wantsJson()) {
            return response()->json(['message' => 'OTP sent to your email.'], 200);
        }
        return view('auth.verify-otp', ['email' => $user->email])->with('success', 'OTP sent to your email.');
    }

    public function sendOtp($otpMail, $otpCode)
    {

        $user = User::where('email', $otpMail)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Save OTP to the database
        Otp::create([
            'email' => $user->email,
            'otp' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP via Notification
        $user->notify(new OtpNotification($otpCode));

        return response()->json(['message' => 'OTP sent to your email.'], 200);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|numeric',
        ]);

        if (Otp::verify($request->email, $request->otp)) {  
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'User not found.'], 404);
                }
                return redirect()->route('login')->with('error', 'User not found.');
            }
            Auth::login($user);
            if ($request->wantsJson()) {
                $token = $user->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'message' => 'Login successful!',
                    'token' => $token,
                    'user' => $user,
                ]);
            }
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }
        if ($request->wantsJson()) {
            return response()->json(['error' => 'Invalid or expired OTP.'], 401);
        }

        return redirect()->route('login')->with('error', 'Invalid OTP.');
    }

    public function logout(Request $request)
    {
        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }
        if ($request->wantsJson()) {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully.']);
        }
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}

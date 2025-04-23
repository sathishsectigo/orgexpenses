<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        dd($request);
        $request->validate([
            'name'    => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        if ($user->email !== $request->email) {
            // Generate OTP for old and new email
            Otp::generate($user->email);
            Otp::generate($request->email);

            return back()->with('info', 'OTP sent to both old and new email addresses.');
        }

        $user->update($request->only(['name', 'surname', 'email', 'phone']));
        return back()->with('success', 'Profile updated successfully.');
    }
}

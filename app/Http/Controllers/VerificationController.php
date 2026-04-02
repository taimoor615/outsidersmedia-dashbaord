<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Show the set-password form for the given token.
     */
    public function show(Request $request)
    {
        $token = $request->query('token');

        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'This verification link is invalid or has already been used.');
        }

        return view('auth.verify-email', compact('token', 'user'));
    }

    /**
     * Activate the account — user sets their own password.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'token'                 => 'required|string',
            'password'              => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('verification_token', $request->token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'This verification link is invalid or has already been used.');
        }

        $user->password = bcrypt($request->password);
        $user->save();

        $user->verifyEmail(); // sets is_verified=true, status=active, clears token

        return redirect()->route('login')
            ->with('success', 'Your account has been activated! You can now log in with your new password.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Notifications\DashboardResetPasswordNotification;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function showDashboardForgotPassword()
    {
        return view('profile.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        }

        return back()->withErrors([
            'email' => 'Email tidak ditemukan.'
        ]);
    }

    public function sendDashboardResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.'
            ]);
        }

        $token = Password::createToken($user);

        $user->notify(
            new DashboardResetPasswordNotification($token)
        );

        return back()->with(
            'success',
            'Link reset password telah dikirim ke email Anda.'
        );
    }

    public function showDashboardResetPassword(Request $request, string $token)
    {
        return view('profile.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }
}

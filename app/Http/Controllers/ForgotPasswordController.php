<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = bin2hex(random_bytes(32));
        $email = $request->input('email');

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $email]);

        try {
            Mail::send('emails.reset_password', ['resetUrl' => $resetUrl], function ($message) use ($email) {
                $message->to($email)
                        ->subject('🔐 Password Reset Link - OneStall Cargo');
            });

            return back()->with('status', 'पासवर्ड रीसेट लिंक आपके ईमेल ' . $email . ' पर भेज दिया गया है। (Password reset link sent!)');
        } catch (\Exception $e) {
            return back()->with('status', 'ईमेल भेजने में सिमुलेशन मोड active रहा (Check log file): Password Reset Link Generated -> ' . $resetUrl);
        }
    }

    public function showResetForm($token, Request $request)
    {
        $email = $request->query('email');
        return view('auth.reset-password', compact('token', 'email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'पासवर्ड रीसेट टोकन अमान्य या समाप्त हो गया है। (Invalid password reset token)']);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'आपका पासवर्ड सफलतापूर्वक अपडेट हो गया है! कृपया नए पासवर्ड के साथ लॉगिन करें।');
    }
}


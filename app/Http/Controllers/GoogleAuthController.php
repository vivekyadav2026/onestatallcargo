<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
            } else {
                $user = User::create([
                    'name' => $googleUser->name ?: 'Google User',
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'student',
                    'is_pro' => false,
                ]);
            }

            Auth::login($user, true);

            $pendingCourseId = session()->pull('pending_course_id');
            if ($pendingCourseId) {
                Enrollment::firstOrCreate([
                    'user_id' => $user->id,
                    'course_id' => $pendingCourseId,
                ]);
                session(['current_course_id' => $pendingCourseId]);
                return redirect()->route('student.course')->with('success', 'Google लॉगिन सफल! आप अपने चुने हुए कोर्स में साइन-इन हो गए हैं।');
            }

            return redirect()->route('student.my-courses')->with('success', 'Welcome back, ' . $user->name . '! Signed in with Google.');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Google authentication failed. Please try again. (' . $e->getMessage() . ')',
            ]);
        }
    }
}

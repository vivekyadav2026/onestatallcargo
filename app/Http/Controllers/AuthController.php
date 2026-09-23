<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->has('course_id')) {
            session(['pending_course_id' => $request->query('course_id')]);
        }

        if (Auth::check()) {
            $pendingCourseId = session()->pull('pending_course_id');
            if ($pendingCourseId) {
                return redirect()->route('enroll', $pendingCourseId);
            }
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('student.my-courses');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            $pendingCourseId = session()->pull('pending_course_id') ?? $request->input('course_id');
            if ($pendingCourseId) {
                \App\Models\Enrollment::firstOrCreate([
                    'user_id' => Auth::id(),
                    'course_id' => $pendingCourseId,
                ]);
                session(['current_course_id' => $pendingCourseId]);
                return redirect()->route('student.course')->with('success', 'Logged in and course enrolled successfully!');
            }

            return redirect()->intended(route('student.my-courses'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(Request $request)
    {
        if ($request->has('course_id')) {
            session(['pending_course_id' => $request->query('course_id')]);
        }

        if (Auth::check()) {
            $pendingCourseId = session()->pull('pending_course_id');
            if ($pendingCourseId) {
                return redirect()->route('enroll', $pendingCourseId);
            }
            return redirect()->route('student.my-courses');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:15'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => 'student',
            'is_pro' => false,
        ]);

        Auth::login($user);

        $pendingCourseId = session()->pull('pending_course_id') ?? $request->input('course_id');
        if ($pendingCourseId) {
            \App\Models\Enrollment::firstOrCreate([
                'user_id' => $user->id,
                'course_id' => $pendingCourseId,
            ]);
            session(['current_course_id' => $pendingCourseId]);
            return redirect()->route('student.course')->with('success', 'Account created and course enrolled successfully!');
        }

        return redirect()->route('student.my-courses')->with('success', 'Account created successfully! Welcome to Testwise.');
    }

    public function switchRole(Request $request, $role)
    {
        if ($role === 'admin') {
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                Auth::login($admin);
                return redirect()->route('admin.dashboard')->with('success', 'Switched to Admin Console.');
            }
        } else {
            $student = User::where('email', 'rahul.sharma@testwise.edu')->first() ?: User::where('role', 'student')->first();
            if ($student) {
                Auth::login($student);
                return redirect()->route('student.my-courses')->with('success', 'Switched to Student Portal.');
            }
        }
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }
}

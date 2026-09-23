<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
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
            'role' => 'seller', // default public signups are sellers
        ]);

        Auth::login($user);

        return $this->redirectBasedOnRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
            case 'operations':
                return redirect()->intended(route('admin.dashboard'));
            
            case 'seller':
            case 'aggregator':
            case 'b2b_customer':
            case 'corporate':
                return redirect()->intended(route('seller.dashboard'));
            
            case 'franchise':
                return redirect()->intended(route('hub.dashboard'));
            
            case 'pickup_rider':
            case 'delivery_rider':
            case 'rider':
                return redirect()->intended(route('rider.dashboard'));
                
            case 'b2c_customer':
                return redirect('/track');
                
            case 'courier_partner':
                // For now, route courier partners to a basic tracking view or their own pending dashboard if built.
                return redirect('/track');
                
            default:
                return redirect('/');
        }
    }
}

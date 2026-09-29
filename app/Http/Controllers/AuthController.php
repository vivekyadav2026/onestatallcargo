<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

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
            $user = Auth::user();

            if ($user->status === 'pending') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account is pending admin approval.',
                ])->onlyInput('email');
            }

            if ($user->status === 'rejected') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account registration has been rejected.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return $this->redirectBasedOnRole($user);
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
            case 'super_admin':
            case 'admin':
            case 'operations':
                return redirect()->route('admin.dashboard');
            
            case 'seller':
            case 'aggregator':
            case 'b2b_customer':
            case 'corporate':
                return redirect()->route('seller.dashboard');
            
            case 'franchise':
                return redirect()->route('hub.dashboard');
            
            case 'pickup_rider':
            case 'delivery_rider':
            case 'rider':
                return redirect()->route('rider.dashboard');
                
            case 'b2c_customer':
                return redirect('/track');
                
            case 'courier_partner':
                // For now, route courier partners to a basic tracking view or their own pending dashboard if built.
                return redirect('/track');
                
            default:
                return redirect('/');
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Check status
                if ($user->status === 'pending') {
                    return redirect()->route('login')->withErrors(['email' => 'Your account is pending admin approval.']);
                }
                if ($user->status === 'rejected') {
                    return redirect()->route('login')->withErrors(['email' => 'Your account registration has been rejected.']);
                }
                
                Auth::login($user, true);
                return $this->redirectBasedOnRole($user);
            } else {
                // Register new user (default to seller for self-serve via Google)
                $newUser = new User();
                $newUser->name = $googleUser->getName();
                $newUser->email = $googleUser->getEmail();
                $newUser->password = Hash::make(Str::random(16));
                $newUser->role = 'seller';
                $newUser->status = 'active'; // Sellers don't need initial admin approval, just KYC later
                $newUser->save();
                
                Auth::login($newUser, true);
                return redirect()->route('seller.dashboard');
            }
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to login with Google. Please try again.']);
        }
    }
}

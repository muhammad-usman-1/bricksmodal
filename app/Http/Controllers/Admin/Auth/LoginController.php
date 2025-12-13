<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Notifications\AdminAccountCreated;
use App\Notifications\NewAdminGoogleLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials['type'] = User::TYPE_ADMIN;

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('admin')->user();
            
            // Check if 2FA is enabled
            if ($user->hasTwoFactorEnabled()) {
                // Store user ID and intended URL in session, then logout
                $request->session()->put('login.id', $user->id);
                $request->session()->put('login.remember', $request->boolean('remember'));
                $request->session()->put('url.intended', $request->session()->pull('url.intended', route('admin.home')));
                
                Auth::guard('admin')->logout();
                
                return redirect()->route('admin.login.2fa');
            }
            
            $request->session()->regenerate();

            return redirect()->intended(route('admin.home'));
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Show the 2FA verification form
     */
    public function show2FAForm()
    {
        if (!session('login.id')) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.2fa');
    }

    /**
     * Verify 2FA code and complete login
     */
    public function verify2FA(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:25'],
        ]);

        $userId = session('login.id');
        
        if (!$userId) {
            return redirect()->route('admin.login')->withErrors(['code' => 'Session expired. Please login again.']);
        }

        $user = User::find($userId);
        
        if (!$user || !$user->hasTwoFactorEnabled()) {
            session()->forget(['login.id', 'login.remember', 'url.intended']);
            return redirect()->route('admin.login')->withErrors(['code' => 'Invalid session. Please login again.']);
        }

        // Keep the code as-is, let verifyTwoFactorCode handle the formatting
        $code = trim($request->code);

        // Verify the 2FA code (handles both TOTP codes and recovery codes)
        if (!$user->verifyTwoFactorCode($code)) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
        }

        // Code is valid, complete the login
        $remember = session('login.remember', false);
        $intended = session('url.intended', route('admin.home'));
        
        session()->forget(['login.id', 'login.remember', 'url.intended']);
        
        Auth::guard('admin')->login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended($intended);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->email)->where('type', User::TYPE_ADMIN)->first();

            if (! $user) {
                $creativeRoleId = Role::where('title', 'creative')->value('id');

                $user = User::create([
                    'name' => $googleUser->name ?: Str::before($googleUser->email, '@'),
                    'email' => $googleUser->email,
                    'type' => User::TYPE_ADMIN,
                    'password' => bcrypt(Str::random(16)),
                ]);

                if ($creativeRoleId) {
                    $user->roles()->sync([$creativeRoleId]);
                }

                $user->notify(new AdminAccountCreated($user));
            }

            if ($user->isSuperAdmin()) {
                $user->notify(new NewAdminGoogleLogin($user, now()));
            }

            // Check if 2FA is enabled for Google login
            if ($user->hasTwoFactorEnabled()) {
                // Store user ID and intended URL in session, then redirect to 2FA
                session()->put('login.id', $user->id);
                session()->put('login.remember', false);
                session()->put('url.intended', route('admin.home'));
                
                return redirect()->route('admin.login.2fa');
            }

            Auth::guard('admin')->login($user);

            return redirect()->intended(route('admin.home'));
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->withErrors(['google' => 'Google authentication failed.']);
        }
    }

    public function showUnauthorized()
    {
        return view('admin.auth.unauthorized');
    }
}

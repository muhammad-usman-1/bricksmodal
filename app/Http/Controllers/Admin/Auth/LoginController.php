<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Notifications\AdminAccountCreated;
use App\Notifications\NewAdminGoogleLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        // Clear any existing session before starting login process
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // First, check if user exists
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            // User doesn't exist - ensure no session is created
            throw ValidationException::withMessages([
                'email' => ['These credentials do not exist in our records.'],
            ]);
        }

        // Check if user is admin (by type or by role)
        // If type is missing but user has admin role, restore the type
        if (!$user->type || $user->type !== User::TYPE_ADMIN) {
            // Check if user has admin role
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                // Restore the type field if user has admin role
                $user->type = User::TYPE_ADMIN;
                $user->save();
            } else {
                // User doesn't have admin role - ensure no session is created
                throw ValidationException::withMessages([
                    'email' => ['These credentials do not exist in our records.'],
                ]);
            }
        }

        // Verify password manually before attempting authentication
        if (!Hash::check($credentials['password'], $user->password)) {
            // Invalid password - ensure no session is created
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials. Please check your email and password.'],
            ]);
        }

        // All validations passed - now attempt authentication
        // Only create session if authentication succeeds
        try {
            // Use login() method directly instead of attempt() to have more control
            // This ensures we only create session when we explicitly call login()
            Auth::guard('admin')->login($user, $request->boolean('remember'));

            // Verify the authenticated user (double check)
            $authenticatedUser = Auth::guard('admin')->user();

            if (!$authenticatedUser || $authenticatedUser->id !== $user->id) {
                // Something went wrong - clear any partial session
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => ['Authentication failed. Please try again.'],
                ]);
            }
        } catch (\Exception $e) {
            // If anything goes wrong during login, ensure no session is left
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Re-throw the exception if it's a ValidationException, otherwise throw a generic one
            if ($e instanceof ValidationException) {
                throw $e;
            }

            throw ValidationException::withMessages([
                'email' => ['Authentication failed. Please try again.'],
            ]);
        }

        // Check if 2FA is enabled
        if ($authenticatedUser->hasTwoFactorEnabled()) {
            // Store user ID and intended URL in session, then logout
            $request->session()->put('login.id', $authenticatedUser->id);
            $request->session()->put('login.remember', $request->boolean('remember'));
            $request->session()->put('url.intended', $request->session()->pull('url.intended', route('admin.home')));

            Auth::guard('admin')->logout();

            return redirect()->route('admin.login.2fa');
        }

        // Success - regenerate session for security
        $request->session()->regenerate();

        return redirect()->intended(route('admin.home'));
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

            $user = User::where('email', $googleUser->email)->first();

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
            } else {
                // Ensure user has admin type if they have admin role
                if (!$user->type || $user->type !== User::TYPE_ADMIN) {
                    if ($user->isAdmin() || $user->isSuperAdmin()) {
                        $user->type = User::TYPE_ADMIN;
                        $user->save();
                    } else {
                        return redirect()->route('admin.login')->withErrors(['google' => 'This account does not have admin access.']);
                    }
                }
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

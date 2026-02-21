<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
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
            // Log failed login attempt
            $this->logLoginFailure($credentials['email'], 'User not found', $request);
            
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
            // Log failed login attempt
            $userType = $user->isSuperAdmin() ? 'admin' : ($user->isCreative() ? 'creative' : 'admin');
            $this->logLoginFailure($user->email, 'Invalid password', $request, $userType, $user->id);
            
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
        
        // Log successful login
        $this->logLoginSuccess($authenticatedUser, $request);

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
        $isSaml2FA = session('saml2_2fa_required', false);

        session()->forget(['login.id', 'login.remember', 'url.intended', 'saml2_2fa_required']);

        Auth::guard('admin')->login($user, $remember);
        $request->session()->regenerate();
        
        // Log successful login after 2FA
        $this->logLoginSuccess($user, $request);

        return redirect()->intended($intended);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('admin')->user();
        
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Log logout event
        if ($user) {
            $this->logLogout($user, $request);
        }

        return redirect()->route('admin.login');
    }
    
    /**
     * Log successful login
     */
    private function logLoginSuccess($user, Request $request)
    {
        // Determine exact role: Super Admin > Creative > Admin
        $userType = 'admin';
        if ($user->isSuperAdmin()) {
            $userType = 'super_admin';
        } elseif ($user->isCreative()) {
            $userType = 'creative';
        }
        
        AuditLog::create([
            'event_type' => 'login_success',
            'user_type' => $userType,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_phone' => $user->phone_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_successful' => true,
            'metadata' => [
                'redirect_to' => 'admin_dashboard',
            ],
            'created_at' => now(),
        ]);
    }
    
    /**
     * Log failed login attempt
     */
    private function logLoginFailure($email, $reason, Request $request, $userType = 'admin', $userId = null)
    {
        // If user exists, determine exact role
        if ($userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                if ($user->isSuperAdmin()) {
                    $userType = 'super_admin';
                } elseif ($user->isCreative()) {
                    $userType = 'creative';
                } else {
                    $userType = 'admin';
                }
            }
        }
        
        AuditLog::create([
            'event_type' => 'login_failed',
            'user_type' => $userType,
            'user_id' => $userId,
            'user_email' => $email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_successful' => false,
            'login_failure_reason' => $reason,
            'created_at' => now(),
        ]);
    }
    
    /**
     * Log logout event
     */
    private function logLogout($user, Request $request)
    {
        // Determine exact role: Super Admin > Creative > Admin
        $userType = 'admin';
        if ($user->isSuperAdmin()) {
            $userType = 'super_admin';
        } elseif ($user->isCreative()) {
            $userType = 'creative';
        }
        
        AuditLog::create([
            'event_type' => 'logout',
            'user_type' => $userType,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_phone' => $user->phone_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_successful' => true,
            'created_at' => now(),
        ]);
    }

    public function redirectToGoogle()
    {
        try {
            // Ensure redirect URI matches what's configured in Google Cloud Console
            $redirectUri = config('services.google.redirect');
            
            return Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email'])
                ->redirectUrl($redirectUri)
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Google OAuth redirect error: ' . $e->getMessage());
            return redirect()->route('landing')
                ->with('error', 'oauth_error');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

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
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Google OAuth InvalidStateException: ' . $e->getMessage());
            return redirect()->route('landing')->with('error', 'oauth_error');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            \Log::error('Google OAuth ClientException: ' . $e->getMessage());
            return redirect()->route('landing')->with('error', 'oauth_error');
        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect()->route('landing')->with('error', 'oauth_error');
        }
    }

    public function showUnauthorized()
    {
        return view('admin.auth.unauthorized');
    }

    /**
     * Redirect to Google SAML SSO login
     */
    public function redirectToSaml()
    {
        try {
            // Use direct URL to avoid route resolution issues
            $samlUrl = url('/saml2/google/login');
            \Log::info('SAML2 redirecting to: ' . $samlUrl);
            return redirect($samlUrl);
        } catch (\Exception $e) {
            \Log::error('SAML2 redirect error: ' . $e->getMessage());
            \Log::error('SAML2 redirect stack trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.login')
                ->withErrors(['saml' => 'SAML SSO is not available. Please try again.']);
        }
    }
}

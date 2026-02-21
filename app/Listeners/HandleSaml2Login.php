<?php

namespace App\Listeners;

use Aacotroneo\Saml2\Events\Saml2LoginEvent;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use App\Notifications\AdminAccountCreated;
use App\Notifications\NewAdminGoogleLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HandleSaml2Login
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Saml2LoginEvent $event): void
    {
        $saml2User = $event->getSaml2User();
        $idpName = $event->getIdpName();

        // Get user attributes from SAML response
        $attributes = $saml2User->getAttributes();
        
        // Get email from SAML attributes
        // Google SAML typically provides email in 'http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress' or 'email'
        $email = null;
        if (isset($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'])) {
            $email = is_array($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress']) 
                ? $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'][0] 
                : $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'];
        } elseif (isset($attributes['email'])) {
            $email = is_array($attributes['email']) ? $attributes['email'][0] : $attributes['email'];
        } elseif (isset($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'])) {
            // Fallback: try to extract email from name or use NameID
            $nameId = $saml2User->getNameId();
            if (filter_var($nameId, FILTER_VALIDATE_EMAIL)) {
                $email = $nameId;
            }
        } else {
            // Last resort: use NameID as email if it looks like an email
            $nameId = $saml2User->getNameId();
            if (filter_var($nameId, FILTER_VALIDATE_EMAIL)) {
                $email = $nameId;
            }
        }

        if (!$email) {
            \Log::error('SAML2: Could not extract email from SAML attributes', ['attributes' => $attributes]);
            session()->flash('saml2_error', ['Could not extract email from SAML response']);
            return;
        }

        // Get name from SAML attributes
        // Supports: firstName/lastName (Google Workspace standard mapping)
        $firstName = null;
        $lastName = null;
        $name = null;

        // Google Workspace standard attribute mapping (firstName, lastName)
        if (isset($attributes['firstName'])) {
            $firstName = is_array($attributes['firstName']) ? $attributes['firstName'][0] : $attributes['firstName'];
        }
        if (isset($attributes['lastName'])) {
            $lastName = is_array($attributes['lastName']) ? $attributes['lastName'][0] : $attributes['lastName'];
        }

        // Build full name from firstName + lastName
        if ($firstName || $lastName) {
            $name = trim($firstName . ' ' . $lastName);
        }

        // Fallbacks for other formats
        if (!$name) {
            if (isset($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'])) {
                $name = is_array($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'])
                    ? $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'][0]
                    : $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'];
            } elseif (isset($attributes['name'])) {
                $name = is_array($attributes['name']) ? $attributes['name'][0] : $attributes['name'];
            } elseif (isset($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'])) {
                $name = is_array($attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'])
                    ? $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'][0]
                    : $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'];
            }
        }

        // Find or create user
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Create new user
            $creativeRoleId = Role::where('title', 'creative')->value('id');

            $user = User::create([
                'name' => $name ?: Str::before($email, '@'),
                'email' => $email,
                'type' => User::TYPE_ADMIN,
                'password' => bcrypt(Str::random(16)), // Random password since SAML handles auth
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
                    \Log::warning('SAML2: User does not have admin access', ['email' => $email]);
                    session()->flash('saml2_error', ['This account does not have admin access.']);
                    return;
                }
            }

            // Update name if provided and different
            if ($name && $user->name !== $name) {
                $user->name = $name;
                $user->save();
            }
        }

        // Notify super admin of login
        if ($user->isSuperAdmin()) {
            $user->notify(new NewAdminGoogleLogin($user, now()));
        }

        // Check if 2FA is enabled for SAML login
        if ($user->hasTwoFactorEnabled()) {
            // Store user ID and intended URL in session, then redirect to 2FA
            session()->put('login.id', $user->id);
            session()->put('login.remember', false);
            session()->put('url.intended', route('admin.home'));
            session()->put('saml2_2fa_required', true);
            return;
        }

        // Log in the user
        Auth::guard('admin')->login($user);
        
        // Log successful login
        $this->logLoginSuccess($user);

        // Set intended URL for redirect
        $intendedUrl = $saml2User->getIntendedUrl();
        if ($intendedUrl) {
            session()->put('url.intended', $intendedUrl);
        }
    }

    /**
     * Log successful login
     */
    private function logLoginSuccess($user)
    {
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
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'login_successful' => true,
            'metadata' => [
                'method' => 'saml2',
                'redirect_to' => 'admin_dashboard',
            ],
            'created_at' => now(),
        ]);
    }
}

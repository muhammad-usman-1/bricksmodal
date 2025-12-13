<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class TwoFactorController extends Controller
{
    /**
     * Show the QR code and setup information for enabling 2FA
     */
    public function show()
    {
        $user = Auth::guard('admin')->user();
        
        // If 2FA is already enabled, just show status
        if ($user->hasTwoFactorEnabled()) {
            return view('admin.profile.index', [
                'admin' => $user,
                'twoFactorEnabled' => true,
            ]);
        }

        // Generate secret if not exists
        if (is_null($user->two_factor_secret)) {
            $google2fa = new Google2FA();
            $secret = $google2fa->generateSecretKey();
            $user->two_factor_secret = Crypt::encrypt($secret);
            $user->save();
        }

        $secret = Crypt::decrypt($user->two_factor_secret);
        $google2fa = new Google2FA();
        
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('admin.profile.index', [
            'admin' => $user,
            'twoFactorSecret' => $secret,
            'twoFactorQrCode' => $qrCodeUrl,
            'twoFactorEnabled' => false,
        ]);
    }

    /**
     * Enable two factor authentication after verification
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'min:4', 'max:25'],
        ]);

        $user = Auth::guard('admin')->user();

        if (is_null($user->two_factor_secret)) {
            return back()->withErrors(['code' => 'Please refresh the page and try again.']);
        }

        $secret = Crypt::decrypt($user->two_factor_secret);
        $google2fa = new Google2FA();

        // Clean the code - extract only digits
        $code = preg_replace('/\D/', '', $request->code);
        
        // TOTP codes are typically 6 digits, but handle different lengths
        // Pad with leading zeros if less than 6 digits
        if (strlen($code) < 6) {
            $code = str_pad($code, 6, '0', STR_PAD_LEFT);
        } elseif (strlen($code) > 6) {
            // If longer than 6, take the last 6 digits
            $code = substr($code, -6);
        }

        // Verify the code
        if (!$google2fa->verifyKey($secret, $code, 2)) {
            return back()->withErrors(['code' => 'Invalid verification code. Please try again.']);
        }

        // Generate recovery codes before enabling
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = sprintf('%s-%s-%s-%s',
                Str::random(4),
                Str::random(4),
                Str::random(4),
                Str::random(4)
            );
        }

        // Enable 2FA
        $user->two_factor_confirmed_at = now();
        $user->two_factor_recovery_codes = Crypt::encrypt(json_encode($recoveryCodes));
        $user->save();

        return back()->with('message', 'Two-factor authentication has been enabled successfully.')
                     ->with('recoveryCodes', $recoveryCodes);
    }

    /**
     * Disable two factor authentication
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::guard('admin')->user();

        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->save();

        return back()->with('message', 'Two-factor authentication has been disabled successfully.');
    }

    /**
     * Show recovery codes
     */
    public function showRecoveryCodes()
    {
        $user = Auth::guard('admin')->user();

        if (!$user->hasTwoFactorEnabled()) {
            return back()->withErrors(['error' => 'Two-factor authentication is not enabled.']);
        }

        $recoveryCodes = $user->recoveryCodes();

        return view('admin.profile.index', [
            'admin' => $user,
            'twoFactorEnabled' => true,
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::guard('admin')->user();

        if (!$user->hasTwoFactorEnabled()) {
            return back()->withErrors(['error' => 'Two-factor authentication is not enabled.']);
        }

        // Generate new recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = sprintf('%s-%s-%s-%s',
                Str::random(4),
                Str::random(4),
                Str::random(4),
                Str::random(4)
            );
        }

        $user->two_factor_recovery_codes = Crypt::encrypt(json_encode($recoveryCodes));
        $user->save();

        return back()->with('recoveryCodes', $recoveryCodes)
                     ->with('message', 'Recovery codes have been regenerated. Please save them in a safe place.');
    }
}

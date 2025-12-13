<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangePasswordController extends Controller
{
    public function edit()
    {
        $user = auth('admin')->user();
        
        // Allow all admin users (admin, creative, superadmin) to access Privacy Setup
        if (!$user || !$user->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }
        
        // Get 2FA status
        $twoFactorEnabled = $user->hasTwoFactorEnabled();
        $twoFactorSecret = null;
        $twoFactorQrCode = null;
        
        // If 2FA is not enabled, generate secret for setup
        if (!$twoFactorEnabled && is_null($user->two_factor_secret)) {
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $secret = $google2fa->generateSecretKey();
            $user->two_factor_secret = \Illuminate\Support\Facades\Crypt::encrypt($secret);
            $user->save();
        }
        
        // If 2FA is not enabled but secret exists, show QR code
        if (!$twoFactorEnabled && !is_null($user->two_factor_secret)) {
            $secret = \Illuminate\Support\Facades\Crypt::decrypt($user->two_factor_secret);
            $google2fa = new \PragmaRX\Google2FA\Google2FA();
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name', 'BRICKS Studio'),
                $user->email,
                $secret
            );

            // Generate QR code using SimpleSoftwareIO package
            $qrCodeGenerator = new \SimpleSoftwareIO\QrCode\Generator();
            $twoFactorQrCode = $qrCodeGenerator->format('svg')
                ->size(200)
                ->generate($qrCodeUrl);
            $twoFactorSecret = $secret;
        }
        
        return view('auth.passwords.edit', compact('twoFactorEnabled', 'twoFactorSecret', 'twoFactorQrCode'));
    }

    public function update(UpdatePasswordRequest $request)
    {
        auth('admin')->user()->update($request->validated());

        return redirect()->route('profile.password.edit')->with('message', __('global.change_password_success'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = auth('admin')->user();

        $user->update($request->validated());

        return redirect()->route('profile.password.edit')->with('message', __('global.update_profile_success'));
    }

    public function destroy()
    {
        $user = auth('admin')->user();

        $user->update([
            'email' => time() . '_' . $user->email,
        ]);

        $user->delete();

        return redirect()->route('login')->with('message', __('global.delete_account_success'));
    }
}

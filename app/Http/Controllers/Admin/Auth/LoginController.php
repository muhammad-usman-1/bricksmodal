<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            $request->session()->regenerate();

            return redirect()->intended(route('admin.home'));
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
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

            if (!$user) {
                // Redirect to unauthorized page
                return redirect()->route('admin.unauthorized');
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

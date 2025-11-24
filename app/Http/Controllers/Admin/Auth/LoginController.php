<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $phoneNumber = $request->input('phone_number');
        // dd($phoneNumber);
        // Map phone numbers to admin credentials
        $credentials = [];
        if ($phoneNumber === '56789') {
            $credentials = [
                'email' => 'admin@example.com',
                'password' => '12345678',
            ];
        } elseif ($phoneNumber === '12345') {
            $credentials = [
                'email' => 'superadmin@example.com',
                'password' => '12345678',
            ];
        } else {
            throw ValidationException::withMessages([
                'phone_number' => ['Invalid phone number for admin login.'],
            ]);
        }

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
}

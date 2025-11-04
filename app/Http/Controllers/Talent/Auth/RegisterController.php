<?php

namespace App\Http\Controllers\Talent\Auth;

use App\Http\Controllers\Controller;
use App\Models\TalentProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('talent.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Store registration data in session, do not create any DB record yet
        session(['talent_registration' => [
            'user' => [
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => $data['password'], // hash later
                'type'     => \App\Models\User::TYPE_TALENT,
            ],
            'profile' => [
                'legal_name'   => $data['name'],
                'display_name'=> $data['name'],
                'daily_rate'  => 0,
                'hourly_rate' => 0,
                'onboarding_step' => 'profile',
            ],
        ]]);

        return redirect()->route('talent.onboarding.start');
    }
}

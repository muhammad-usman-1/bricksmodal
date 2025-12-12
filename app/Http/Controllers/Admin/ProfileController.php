<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile.index', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin->id)],
            'phone_country_code' => ['nullable', 'string', 'max:10'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'role_title' => ['nullable', 'string', 'max:255'],
            'member_since' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:5120'],
        ]);

        // Keep legacy name column in sync for initials and other uses
        $data['name'] = trim(($data['first_name'] ?? '') . ' ' . ($data['last_name'] ?? '')) ?: $admin->name;

        if ($request->hasFile('profile_photo')) {
            if ($admin->profile_photo_path) {
                Storage::disk('public')->delete($admin->profile_photo_path);
            }
            $data['profile_photo_path'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $admin->update($data);

        return back()->with('message', 'Profile updated successfully.');
    }
}

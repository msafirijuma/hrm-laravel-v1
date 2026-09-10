<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OfficeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class OfficeSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'platform_name'   => OfficeSetting::get('platform_name', 'HRM System'),
            'support_email'   => OfficeSetting::get('support_email', ''),
            'support_phone'   => OfficeSetting::get('support_phone', ''),
            'tagline'         => OfficeSetting::get('tagline', ''),
            'location'        => OfficeSetting::get('location', ''),
            'notify_new_user' => OfficeSetting::get('notify_new_user', '1'),
            'notify_new_sub'  => OfficeSetting::get('notify_new_sub', '1'),
            'logo'            => OfficeSetting::get('logo', ''),
        ];

        return view('settings.index', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'platform_name' => 'required|string|min:2|max:255',
            'support_email' => 'nullable|email|max:255',
            'support_phone' => 'nullable|string|max:30|regex:/^[0-9+\-\s()]+$/',
            'tagline'       => 'nullable|string|max:255',
            'location'      => 'nullable|string|max:255',
        ], [
            'platform_name.required' => 'Platform name is required.',
            'platform_name.min'      => 'Platform name must be at least 2 characters.',
            'support_email.email'    => 'Please enter a valid email address.',
            'support_phone.regex'    => 'Please enter a valid phone number.',
        ]);

        OfficeSetting::set('platform_name', $request->platform_name);
        OfficeSetting::set('support_email', $request->support_email);
        OfficeSetting::set('support_phone', $request->support_phone);
        OfficeSetting::set('tagline', $request->tagline);
        OfficeSetting::set('location', $request->location);
        return back()->with('success', 'General settings updated.');
    }

    public function updateNotifications(Request $request)
    {
        OfficeSetting::set('notify_new_user', $request->has('notify_new_user') ? '1' : '0');
        OfficeSetting::set('notify_new_sub', $request->has('notify_new_sub') ? '1' : '0');

        return back()->with('success', 'Notification settings updated.');
    }

    public function updateSecurity(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateAppearance(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $old = OfficeSetting::get('logo');
            if ($old) {
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('logo')->store('settings', 'public');
            OfficeSetting::set('logo', $path);
        }

        return back()->with('success', 'Appearance settings updated.');
    }
}
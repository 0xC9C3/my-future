<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SettingsController extends Controller
{
    /**
     * Display the settings view.
     *
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('Dashboard/Settings', [
            'settings' => $request->user()->setting
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|numeric|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => [
                Rule::in(['', 'tls', 'ssl']),
            ],
            'smtp_from_address' => 'nullable|string|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',

            'track_web_access' => 'boolean',
            'track_email_access' => 'boolean',
        ]);

        $setting = $request->user()->setting ?? new Setting();

        $setting->user_id = $request->user()->id;
        $setting->smtp_host = $request->smtp_host ?? '';
        $setting->smtp_port = $request->smtp_port ?? 0;
        $setting->smtp_username = $request->smtp_username ?? ($setting->smtp_username ?? '');
        $setting->smtp_password = $request->smtp_password ?? ($setting->smtp_password ?? '');
        $setting->smtp_encryption = $request->smtp_encryption ?? '';
        $setting->smtp_from_address = $request->smtp_from_address ?? '';
        $setting->smtp_from_name = $request->smtp_from_name ?? '';

        $setting->track_web_access = !!$request->track_web_access;
        $setting->track_email_access = !!$request->track_email_access;

        $setting->save();

        return back();
    }
}

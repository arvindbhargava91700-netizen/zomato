<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SmsConfigurationController extends Controller
{
    protected array $keys = [
        'sms_provider',
        'sms_api_key',
        'sms_api_secret',
        'sms_sender_id',
        'sms_base_url',
        'sms_route',
    ];

    public function edit(): View
    {
        $settings = Setting::group('sms')->keyBy('key');
        return view('manage.admin.sms-configuration.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'sms_provider' => ['nullable', 'string'],
            'sms_api_key' => ['nullable', 'string'],
            'sms_api_secret' => ['nullable', 'string'],
            'sms_sender_id' => ['nullable', 'string'],
            'sms_base_url' => ['nullable', 'string'],
            'sms_route' => ['nullable', 'string'],
        ]);

        foreach ($this->keys as $key) {
            Setting::set($key, $data[$key] ?? null);
        }

        return redirect()->route('admin.sms-configuration.edit')
            ->with('success', 'SMS configuration saved successfully.');
    }
}

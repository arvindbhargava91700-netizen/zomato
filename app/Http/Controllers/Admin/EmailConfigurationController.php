<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class EmailConfigurationController extends Controller
{
    protected array $keys = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    public function edit(): View
    {
        $settings = Setting::group('email')->keyBy('key');
        return view('manage.admin.email-configuration.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'mail_mailer' => ['nullable', 'string'],
            'mail_host' => ['nullable', 'string'],
            'mail_port' => ['nullable', 'integer'],
            'mail_username' => ['nullable', 'string'],
            'mail_password' => ['nullable', 'string'],
            'mail_encryption' => ['nullable', 'in:tls,ssl,null'],
            'mail_from_address' => ['nullable', 'email'],
            'mail_from_name' => ['nullable', 'string'],
        ]);

        foreach ($this->keys as $key) {
            if ($request->has($key)) {
                Setting::set($key, $data[$key] ?? null);
            }
        }

        return redirect()->route('admin.email-configuration.edit')
            ->with('success', 'Email configuration saved successfully.');
    }

    public function test(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'test_email' => ['required', 'email'],
        ]);

        try {
            Mail::raw('This is a test email from your application configuration.', function ($message) use ($data) {
                $message->to($data['test_email'])
                    ->subject('Test Email - Configuration Check');
            });

            return redirect()->route('admin.email-configuration.edit')
                ->with('success', 'Test email sent to ' . $data['test_email'] . '.');
        } catch (\Throwable $e) {
            return redirect()->route('admin.email-configuration.edit')
                ->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}

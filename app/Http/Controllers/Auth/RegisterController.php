<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the application registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'] ?? 4,
        ]);

        $promoCode = $this->getWelcomePromoCode($user);

        $this->sendWelcomeEmail($user, $promoCode);

       return back()->with('success', 'Account created successfully.');
    }

    /**
     * Obtain a promo code for the new user based on the configured source.
     * Falls back to generating a new code if no unused code is available.
     */
    protected function getWelcomePromoCode(User $user): PromoCode
    {
        $source = \App\Models\Setting::get('welcome_promo_source', 'use_unused');

        if ($source === 'use_unused') {
            $unused = PromoCode::where('status', 'active')
                ->where('usage_status', 'unused')
                ->whereNull('assigned_user_id')
                ->where(function ($q) {
                    $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                })
                ->orderBy('id')
                ->first();

            if ($unused) {
                $unused->assigned_user_id = $user->id;
                $unused->save();
                return $unused;
            }
        }

        return $this->generateWelcomePromoCode($user);
    }

    /**
     * Generate a unique "Welcome" promo code assigned to the new user.
     */
    protected function generateWelcomePromoCode(User $user): PromoCode
    {
        $charset = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $max = strlen($charset) - 1;

        do {
            $suffix = '';
            for ($i = 0; $i < 4; $i++) {
                $suffix .= $charset[random_int(0, $max)];
            }
            $code = 'WELCOME-' . $suffix;
        } while (PromoCode::where('code', $code)->exists());

        return PromoCode::create([
            'campaign_name' => 'Welcome',
            'code' => $code,
            'code_type' => 'welcome',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'minimum_order_amount' => 0,
            'maximum_discount_amount' => 100,
            'per_user_limit' => 1,
            'assigned_user_id' => $user->id,
            'usage_status' => 'unused',
            'valid_from' => now(),
            'valid_until' => now()->addDays(30),
            'status' => 'active',
        ]);
    }

    /**
     * Send the welcome email (with the promo code) using the dynamic template.
     */
    protected function sendWelcomeEmail(User $user, PromoCode $promoCode): void
    {
        try {
            $template = EmailTemplate::findBySlug('welcome');
            if (!$template || !$user->email) {
                return;
            }

            $variables = [
                'customer_name' => $user->name,
                'promo_code' => $promoCode->code,
                'app_name' => config('app.name'),
            ];

            $html = $template->renderBody($variables);
            $subject = $template->renderSubject($variables);

            Mail::html($html, function ($message) use ($user, $subject) {
                $message->to($user->email)->subject($subject);
            });
        } catch (\Throwable $e) {
            // Never break registration if email sending fails.
        }
    }
}
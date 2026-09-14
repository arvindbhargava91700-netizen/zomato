<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\CompanySetting;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private ?string $resolvedCurrencySymbol = null;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->applyCompanyTimezone();
        $this->applyMailConfig();

        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $view->with('currencySymbol', $this->currencySymbol());
            $view->with('pendingDiningOffersCount', $this->pendingDiningOffersCount());
            $view->with('pendingRestaurantOffersCount', $this->pendingRestaurantOffersCount());
            $view->with('pendingRestaurantBlogsCount', $this->pendingRestaurantBlogsCount());
            $view->with('deliveryChargePerKm', $this->deliveryChargePerKm());

            $view->with('taxGst', $this->taxGst());
            $view->with('taxPercentage', $this->taxPercentage());
            $view->with('adminEarnings', $this->adminEarnings());
            $view->with('deliveryPartnerEarnings', $this->deliveryPartnerEarnings());
            $view->with('restaurantEarnings', $this->restaurantEarnings());
            $view->with('companyName', $this->companyName());
            $view->with('companyLogo', $this->companyLogo());
            $view->with('companyLogoSm', $this->companyLogoSm());
            $view->with('companyFavicon', $this->companyFavicon());
            $view->with('companySetting', CompanySetting::firstSetting());
            $view->with('socialLinks', $this->socialLinks());
            $view->with('topBrands', Brand::active()->orderBy('name')->take(5)->get());
        });
    }

    /**
     * Apply the timezone saved in company settings at runtime, so the whole
     * app (PHP dates, Eloquent casts, Carbon) uses the configured timezone.
     * Falls back to config('app.timezone') when no setting is saved yet.
     */
    protected function applyCompanyTimezone(): void
    {
        try {
            $timezone = CompanySetting::firstSetting()->timezone;
        } catch (\Throwable $e) {
            $timezone = null;
        }

        if (empty($timezone)) {
            return;
        }

        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);
    }

    /**
     * Apply the SMTP configuration saved in the settings table at runtime,
     * so outgoing mail uses the admin-managed credentials dynamically.
     */
    protected function applyMailConfig(): void
    {
        try {
            $host = Setting::get('mail_host');

            if (empty($host)) {
                return;
            }

            $mailer = Setting::get('mail_mailer', 'smtp') ?: 'smtp';
            $encryption = Setting::get('mail_encryption', 'tls');

            Config::set('mail.default', $mailer);

            Config::set('mail.mailers.smtp.host', $host);
            Config::set('mail.mailers.smtp.port', (int) Setting::get('mail_port', 587));
            Config::set('mail.mailers.smtp.username', Setting::get('mail_username'));
            Config::set('mail.mailers.smtp.password', Setting::get('mail_password'));
            Config::set('mail.mailers.smtp.encryption', $encryption === 'null' ? null : $encryption);

            $fromAddress = Setting::get('mail_from_address');
            if ($fromAddress) {
                Config::set('mail.from.address', $fromAddress);
                Config::set('mail.from.name', Setting::get('mail_from_name', config('app.name')));
            }
        } catch (\Throwable $e) {
            // Ignore if the settings table is not available yet.
        }
    }

    /**
     * Resolve the currency symbol once per request and share it with views.
     */
    protected function currencySymbol(): string
    {
        if ($this->resolvedCurrencySymbol === null) {
            $this->resolvedCurrencySymbol = CompanySetting::firstSetting()->currencySymbol();
        }

        return $this->resolvedCurrencySymbol;
    }

    /**
     * Number of pending COD settlements (shown on the admin sidebar).
     */
    protected function pendingSettlementsCount(): int
    {
        if (!auth('admin')->check()) {
            return 0;
        }

        return \App\Models\CodSettlement::where('status', \App\Models\CodSettlement::STATUS_PENDING)->count();
    }

    /**
     * Number of pending dining offers waiting for admin approval.
     */
    protected function pendingDiningOffersCount(): int
    {
        if (!auth('admin')->check()) {
            return 0;
        }

        return \App\Models\DiningOffer::where('approval_status', 'pending')->count();
    }

    /**
     * Number of pending restaurant banner offers waiting for admin approval.
     */
    protected function pendingRestaurantOffersCount(): int
    {
        if (!auth('admin')->check()) {
            return 0;
        }

        return \App\Models\RestaurantOffer::where('approval_status', 'pending')->count();
    }

    /**
     * Number of pending restaurant blogs waiting for admin approval.
     */
    protected function pendingRestaurantBlogsCount(): int
    {
        if (!auth('admin')->check()) {
            return 0;
        }

        return \App\Models\RestaurantBlog::where('approval_status', 'pending')->count();
    }


    /**
     * Delivery charge per km used for front-end checkout (flat base charge).
     */
    protected function deliveryChargePerKm(): float
    {
        return (float) (\App\Models\Restaurant::query()->value('delivery_charge_per_km') ?? 20);
    }

    /**
     * Raw tax_gst string (e.g. "5%" or "GST 5%").
     */
    protected function taxGst(): ?string
    {
        return \App\Models\CompanySetting::firstSetting()->tax_gst;
    }

    /**
     * Numeric tax percentage extracted from tax_gst (default 0).
     */
    protected function taxPercentage(): float
    {
        return \App\Models\CompanySetting::firstSetting()->taxGstPercentage();
    }

    /**
     * Admin earnings totals (restaurant commission, tax/gst, platform share)
     * read from the incomes table. Shown as badges in the admin sidebar.
     */
    protected function adminEarnings(): array
    {
        $empty = ['commission' => 0.0, 'tax' => 0.0, 'platform' => 0.0];

        if (!auth('admin')->check()) {
            return $empty;
        }

        $base = \App\Models\Income::where('recipient_type', \App\Models\Income::RECIPIENT_PLATFORM);

        return [
            'commission' => (float) (clone $base)->where('income_type', \App\Models\Income::TYPE_RESTAURANT_COMMISSION)->sum('amount'),
            'tax' => (float) (clone $base)->where('income_type', \App\Models\Income::TYPE_TAX)->sum('amount'),
            'platform' => (float) (clone $base)->where('income_type', \App\Models\Income::TYPE_PLATFORM_SHARE)->sum('amount'),
        ];
    }

    /**
     * Company name from settings (used for branding / alt text).
     */
    protected function companyName(): string
    {
        return CompanySetting::firstSetting()->company_name ?: 'Zomo';
    }

    /**
     * Large logo path from settings, falling back to the default theme logo.
     */
    protected function companyLogo(): string
    {
        return CompanySetting::firstSetting()->logo_lg ?: 'front/assets/images/svg/logo.svg';
    }

    /**
     * Small logo path from settings, falling back to the default theme logo.
     */
    protected function companyLogoSm(): string
    {
        return CompanySetting::firstSetting()->logo_sm ?: 'front/assets/images/svg/logo.svg';
    }

    /**
     * Favicon path from settings, falling back to the default theme favicon.
     */
    protected function companyFavicon(): string
    {
        return CompanySetting::firstSetting()->favicon ?: 'front/assets/images/logo/favicon.png';
    }

    /**
     * Total earnings for the authenticated delivery partner, read from the incomes table.
     * Shown as a badge in the delivery partner sidebar.
     */
    protected function deliveryPartnerEarnings(): float
    {
        if (!auth()->check()) {
            return 0.0;
        }

        return (float) \App\Models\Income::where('recipient_type', \App\Models\Income::RECIPIENT_DELIVERY_PARTNER)
            ->where('recipient_id', auth()->id())
            ->sum('amount');
    }

    /**
     * Total earnings for the authenticated restaurant owner, read from the incomes table.
     * Shown as a badge in the restaurant sidebar.
     */
    protected function restaurantEarnings(): float
    {
        if (!auth()->check()) {
            return 0.0;
        }

        return (float) \App\Models\Income::where('recipient_type', \App\Models\Income::RECIPIENT_RESTAURANT)
            ->where('recipient_id', auth()->id())
            ->sum('amount');
    }

    /**
     * Social media links configured in CompanySetting.
     */
    protected function socialLinks(): array
    {
        $setting = CompanySetting::firstSetting();
        return [
            'facebook' => $setting->facebook_url ?: 'https://www.facebook.com/',
            'twitter' => $setting->twitter_url ?: 'https://twitter.com/',
            'linkedin' => $setting->linkedin_url ?: 'https://www.linkedin.com/',
            'instagram' => $setting->instagram_url ?: 'https://www.instagram.com/',
            'youtube' => $setting->youtube_url ?: 'https://www.youtube.com/',
        ];
    }
}

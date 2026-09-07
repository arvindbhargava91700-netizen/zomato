<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'body',
        'category',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Render the template body by replacing {{ key }} placeholders with values and wrapping in dynamic header/footer.
     */
    public function renderBody(array $variables = []): string
    {
        return static::renderWithLayout($this->body ?? '', $variables);
    }

    /**
     * Build HTML social badges from CompanySetting.
     */
    public static function buildSocialLinksHtml(?CompanySetting $company = null): string
    {
        $company = $company ?: CompanySetting::firstSetting();
        $links = [
            'Facebook' => $company->facebook_url ?: 'https://facebook.com',
            'Twitter' => $company->twitter_url ?: 'https://twitter.com',
            'Instagram' => $company->instagram_url ?: 'https://instagram.com',
            'LinkedIn' => $company->linkedin_url,
            'YouTube' => $company->youtube_url,
        ];

        $html = '';
        foreach ($links as $name => $url) {
            if (!empty($url)) {
                $html .= '<a href="' . htmlspecialchars($url) . '" target="_blank" rel="noopener noreferrer" style="display:inline-block;color:#ffffff;background:#2d3342;text-decoration:none;padding:5px 11px;margin:2px 4px;border-radius:4px;font-size:11px;font-weight:600;letter-spacing:0.3px;">' . htmlspecialchars($name) . '</a>';
            }
        }

        return $html;
    }

    /**
     * Render any email body HTML wrapped in the dynamic header and footer with variable replacements.
     */
    public static function renderWithLayout(string $bodyHtml, array $variables = []): string
    {
        $company = CompanySetting::firstSetting();
        $appName = $company?->company_name ?: (Setting::get('mail_from_name') ?: config('app.name', 'Food Management'));
        $supportEmail = Setting::get('mail_from_address') ?: ($company?->email ?: config('mail.from.address'));
        $appDomain = parse_url(config('app.url'), PHP_URL_HOST) ?: 'example.com';
        $supportPhone = $company?->phone ?: (Setting::get('support_phone') ?: '');

        $variables = array_merge([
            'app_name' => $appName,
            'app_domain' => $appDomain,
            'year' => date('Y'),
            'support_email' => $supportEmail,
            'support_phone' => $supportPhone,
            'facebook_url' => $company?->facebook_url ?: 'https://facebook.com',
            'twitter_url' => $company?->twitter_url ?: 'https://twitter.com',
            'instagram_url' => $company?->instagram_url ?: 'https://instagram.com',
            'linkedin_url' => $company?->linkedin_url ?: 'https://linkedin.com',
            'youtube_url' => $company?->youtube_url ?: 'https://youtube.com',
            'social_links_html' => static::buildSocialLinksHtml($company),
        ], $variables);

        $header = Setting::get('email_header_html') ?: static::defaultHeader();
        $footer = Setting::get('email_footer_html') ?: static::defaultFooter();

        $instance = new static();

        return '<div style="font-family:Arial,Helvetica,sans-serif;max-width:600px;margin:0 auto;background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">'
            . $instance->replace($header, $variables)
            . '<div style="padding:26px 28px;color:#333333;line-height:1.6;font-size:14.5px;">' . $instance->replace($bodyHtml, $variables) . '</div>'
            . $instance->replace($footer, $variables)
            . '</div>';
    }

    /**
     * Replace {{ key }} placeholders in a given template fragment.
     */
    public function replace(string $template, array $variables): string
    {
        return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', function ($matches) use ($variables) {
            $key = $matches[1];
            return $variables[$key] ?? '';
        }, $template);
    }

    /**
     * Default branded header (used when no custom header is configured).
     */
    public static function defaultHeader(): string
    {
        return '<div style="background:linear-gradient(135deg,#cb202d,#ff5e62);padding:24px 24px;text-align:center;">'
            . '<div style="font-size:26px;font-weight:800;color:#ffffff;letter-spacing:0.5px;">&#129370; {{ app_name }}</div>'
            . '<div style="font-size:13px;color:#ffe3e4;margin-top:4px;">Fresh food, delivered fast &#128640;</div>'
            . '</div>';
    }

    /**
     * Default branded footer (used when no custom footer is configured).
     */
    public static function defaultFooter(): string
    {
        return '<div style="background:#1f2430;padding:22px 24px;text-align:center;color:#c7ccd6;font-size:12px;line-height:1.7;">'
            . '<div style="margin-bottom:12px;">{{ social_links_html }}</div>'
            . '<div>{{ app_name }} &middot; Support: {{ support_email }}</div>'
            . '<div style="margin-top:6px;">&copy; {{ year }} {{ app_name }}. All rights reserved.</div>'
            . '<div style="margin-top:6px;color:#8a90a0;">You received this email from {{ app_name }}.</div>'
            . '</div>';
    }

    /**
     * Find an active template by its slug.
     */
    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->where('status', 'active')->first();
    }

    /**
     * Render the template subject by replacing {{ key }} placeholders with values.
     */
    public function renderSubject(array $variables = []): string
    {
        $subject = $this->subject ?? '';

        $variables = array_merge([
            'app_name' => config('app.name'),
        ], $variables);

        return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', function ($matches) use ($variables) {
            $key = $matches[1];
            return $variables[$key] ?? '';
        }, $subject);
    }
}

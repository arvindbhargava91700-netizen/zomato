<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        EmailTemplate::updateOrCreate(
            ['slug' => 'welcome'],
            [
                'name' => 'Welcome Email',
                'subject' => 'Welcome to {{ app_name }}! Your promo code inside',
                'category' => 'auth',
                'status' => 'active',
                'body' => '<div style="font-family:Arial,Helvetica,sans-serif;max-width:600px;margin:0 auto;padding:24px;color:#222;">'
                    . '<h2 style="margin:0 0 12px;color:#cb202d;">Welcome, {{ customer_name }}! &#127881;</h2>'
                    . '<p style="margin:0 0 12px;line-height:1.6;">Thank you for joining <strong>{{ app_name }}</strong>. We are thrilled to have you on board.</p>'
                    . '<p style="margin:0 0 12px;line-height:1.6;">As a welcome gift, here is your exclusive promo code to use on your first order:</p>'
                    . '<p style="font-size:24px;font-weight:bold;color:#cb202d;letter-spacing:1px;margin:12px 0;">{{ promo_code }}</p>'
                    . '<p style="margin:0 0 12px;line-height:1.6;">Apply this code at checkout to enjoy your <strong>exclusive welcome discount</strong> on your order.</p>'
                    . '<p style="margin:0 0 4px;line-height:1.6;">Happy ordering!</p>'
                    . '<p style="margin:0;line-height:1.6;">&mdash; Team {{ app_name }}</p>'
                    . '</div>',
            ]
        );

        EmailTemplate::updateOrCreate(
            ['slug' => 'welcome_vendor'],
            [
                'name' => 'Welcome Vendor',
                'subject' => 'Welcome to {{ app_name }} - Your Vendor Account is Ready!',
                'category' => 'auth',
                'status' => 'active',
                'body' => '<div style="font-family:Arial,Helvetica,sans-serif;max-width:600px;margin:0 auto;padding:24px;color:#222;">'
                    . '<h2 style="margin:0 0 12px;color:#cb202d;">Welcome, {{ customer_name }}! &#127881;</h2>'
                    . '<p style="margin:0 0 12px;line-height:1.6;">Thank you for joining <strong>{{ app_name }}</strong> as a <strong>Restaurant Vendor</strong>. Your account has been created and you can now manage your restaurant, menus, and orders.</p>'
                    . '<p style="margin:0 0 12px;line-height:1.6;">Next steps:</p>'
                    . '<ul style="margin:0 0 12px;padding-left:20px;line-height:1.6;">'
                    . '<li>Complete your restaurant profile &amp; KYC documents.</li>'
                    . '<li>Add your food categories and menu items.</li>'
                    . '<li>Start receiving and fulfilling orders.</li>'
                    . '</ul>'
                    . '<p style="margin:0 0 12px;line-height:1.6;">If you have any questions, our vendor support team is here to help at <strong>{{ support_email }}</strong>.</p>'
                    . '<p style="margin:0;line-height:1.6;">&mdash; Team {{ app_name }}</p>'
                    . '</div>',
            ]
        );

        EmailTemplate::updateOrCreate(
            ['slug' => 'welcome_delivery'],
            [
                'name' => 'Welcome Delivery Partner',
                'subject' => 'Welcome aboard, {{ customer_name }}! - Join {{ app_name }} as a Delivery Partner',
                'category' => 'auth',
                'status' => 'active',
                'body' => '<div style="font-family:Arial,Helvetica,sans-serif;max-width:600px;margin:0 auto;padding:24px;color:#222;">'
                    . '<h2 style="margin:0 0 12px;color:#cb202d;">Welcome aboard, {{ customer_name }}! &#129309;</h2>'
                    . '<p style="margin:0 0 12px;line-height:1.6;">Congratulations on becoming a <strong>Delivery Partner</strong> with <strong>{{ app_name }}</strong>. We are excited to have you on the road with us.</p>'
                    . '<p style="margin:0 0 12px;line-height:1.6;">To start delivering, please complete the following:</p>'
                    . '<ul style="margin:0 0 12px;padding-left:20px;line-height:1.6;">'
                    . '<li>Submit your KYC documents (ID &amp; vehicle proof).</li>'
                    . '<li>Set your availability and service area.</li>'
                    . '<li>Download the partner app and go online.</li>'
                    . '</ul>'
                    . '<p style="margin:0 0 12px;line-height:1.6;">Our partner support team is reachable at <strong>{{ support_email }}</strong> for any assistance.</p>'
                    . '<p style="margin:0;line-height:1.6;">&mdash; Team {{ app_name }}</p>'
                    . '</div>',
            ]
        );
    }
}

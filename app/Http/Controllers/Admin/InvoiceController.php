<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanySetting;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function settings()
    {
        $setting = CompanySetting::firstSetting();
        return view('manage.admin.invoices.settings', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        $setting = CompanySetting::firstSetting();

        $data = $request->validate([
            'logo_lg' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'gst_number' => ['nullable', 'string', 'max:255'],
            'invoice_prefix' => ['nullable', 'string', 'max:20'],
            'signature' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'signatory_designation' => ['nullable', 'string', 'max:255'],
            'invoice_terms' => ['nullable', 'string'],
        ]);

        foreach (['signature', 'logo_lg'] as $field) {
            if ($request->hasFile($field)) {
                if ($setting->{$field} && File::exists(public_path($setting->{$field}))) {
                    File::delete(public_path($setting->{$field}));
                }
                $fileName = time().'_'.$field.'_'.Str::random(5).'.'.$request->file($field)->extension();
                $request->file($field)->move(public_path('uploads/settings'), $fileName);
                $data[$field] = 'uploads/settings/'.$fileName;
            }
        }

        $setting->update($data);

        return back()->with('success', 'Invoice settings updated successfully.');
    }

    public function preview()
    {
        $setting = CompanySetting::firstSetting();
        
        // Dummy data for preview
        $invoice = [
            'invoice_number' => ($setting->invoice_prefix ?? 'INV-') . '10001',
            'date' => now()->format($setting->date_format ?? 'd/m/Y'),
            'due_date' => now()->addDays(7)->format($setting->date_format ?? 'd/m/Y'),
            'customer_name' => 'John Doe',
            'customer_email' => 'johndoe@example.com',
            'customer_phone' => '+1234567890',
            'customer_address' => '123 Main Street, Cityville',
            'items' => [
                ['name' => 'Margherita Pizza', 'quantity' => 2, 'price' => 15.00],
                ['name' => 'Garlic Bread', 'quantity' => 1, 'price' => 5.00],
                ['name' => 'Coke', 'quantity' => 2, 'price' => 2.50],
            ],
            'subtotal' => 40.00,
            'tax_percentage' => $setting->taxGstPercentage(),
            'tax_amount' => 40.00 * ($setting->taxGstPercentage() / 100),
            'discount' => 0.00,
            'delivery_fee' => 5.00,
        ];
        $invoice['total'] = $invoice['subtotal'] + $invoice['tax_amount'] + $invoice['delivery_fee'] - $invoice['discount'];

        return view('manage.admin.invoices.template', compact('setting', 'invoice'));
    }
}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice['invoice_number'] }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            background-color: #fff;
            border-radius: 8px;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            padding: 10px;
        }
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
            padding: 10px;
        }
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .footer {
            margin-top: 40px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            font-size: 12px;
            color: #777;
        }
        .logo {
            max-width: 150px;
            max-height: 80px;
        }
        .invoice-title {
            color: #cb202d;
            font-size: 32px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="4">
                <table>
                    <tr>
                        <td style="text-align: left;">
                            <h1 class="invoice-title">INVOICE</h1>
                            Invoice #: <strong>{{ $invoice['invoice_number'] }}</strong><br>
                            Created: {{ $invoice['date'] }}<br>
                            Due: {{ $invoice['due_date'] }}
                        </td>
                        
                        <td class="title" style="text-align: right;">
                            @if(isset($setting->logo_lg) && $setting->logo_lg)
                                <img src="{{ asset($setting->logo_lg) }}" class="logo" alt="{{ $setting->company_name ?? 'Company Logo' }}">
                            @else
                                <h2 style="margin:0; color:#cb202d;">{{ $setting->company_name ?? 'Company Name' }}</h2>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr class="information">
            <td colspan="4">
                <table>
                    <tr>
                        <td>
                            <strong>Billed To:</strong><br>
                            {{ $invoice['customer_name'] }}<br>
                            {{ $invoice['customer_address'] }}<br>
                            {{ $invoice['customer_phone'] }}<br>
                            {{ $invoice['customer_email'] }}
                        </td>
                        
                        <td style="text-align: right;">
                            <strong>From:</strong><br>
                            {{ $setting->company_name ?? 'Your Company' }}<br>
                            {!! nl2br(e($setting->address ?? 'Company Address')) !!}<br>
                            {{ $setting->email ?? 'contact@example.com' }}<br>
                            {{ $setting->phone ?? '123456789' }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr class="heading">
            <td>Item</td>
            <td class="text-center">Quantity</td>
            <td class="text-center">Price</td>
            <td class="text-right">Total</td>
        </tr>
        
        @foreach($invoice['items'] as $item)
        <tr class="item {{ $loop->last ? 'last' : '' }}">
            <td>{{ $item['name'] }}</td>
            <td class="text-center">{{ $item['quantity'] }}</td>
            <td class="text-center">{{ $setting->currencySymbol() }}{{ number_format($item['price'], 2) }}</td>
            <td class="text-right">{{ $setting->currencySymbol() }}{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
        </tr>
        @endforeach
        
        <tr class="total">
            <td colspan="2"></td>
            <td class="text-right" style="padding: 10px;">Subtotal:</td>
            <td class="text-right" style="padding: 10px;">{{ $setting->currencySymbol() }}{{ number_format($invoice['subtotal'], 2) }}</td>
        </tr>

        @if($invoice['discount'] > 0)
        <tr>
            <td colspan="2"></td>
            <td class="text-right" style="padding: 5px 10px; color: green;">Discount:</td>
            <td class="text-right" style="padding: 5px 10px; color: green;">-{{ $setting->currencySymbol() }}{{ number_format($invoice['discount'], 2) }}</td>
        </tr>
        @endif

        @if($invoice['delivery_fee'] > 0)
        <tr>
            <td colspan="2"></td>
            <td class="text-right" style="padding: 5px 10px;">Delivery Fee:</td>
            <td class="text-right" style="padding: 5px 10px;">{{ $setting->currencySymbol() }}{{ number_format($invoice['delivery_fee'], 2) }}</td>
        </tr>
        @endif

        @if($invoice['tax_amount'] > 0)
        <tr>
            <td colspan="2"></td>
            <td class="text-right" style="padding: 5px 10px;">Tax ({{ $setting->tax_gst }}):</td>
            <td class="text-right" style="padding: 5px 10px;">{{ $setting->currencySymbol() }}{{ number_format($invoice['tax_amount'], 2) }}</td>
        </tr>
        @endif
        
        <tr class="total">
            <td colspan="2"></td>
            <td class="text-right" style="padding: 15px 10px; font-size: 18px; border-top: 2px solid #333;"><strong>Total:</strong></td>
            <td class="text-right" style="padding: 15px 10px; font-size: 18px; border-top: 2px solid #333; color: #cb202d;"><strong>{{ $setting->currencySymbol() }}{{ number_format($invoice['total'], 2) }}</strong></td>
        </tr>
    </table>
    
    <div style="margin-top: 40px;">
        <table width="100%">
            <tr>
                <td style="width: 60%; vertical-align: bottom;">
                    <div class="footer text-left" style="border-top: none; padding-top: 0;">
                        @if(isset($setting->invoice_terms) && !empty($setting->invoice_terms))
                            <p style="color: #333; margin-bottom: 5px;"><strong>Terms & Conditions</strong></p>
                            <div style="font-size: 11px; color: #555;">
                                {!! $setting->invoice_terms !!}
                            </div>
                        @else
                            <p>Thank you for your business!</p>
                        @endif
                    </div>
                </td>
                <td style="width: 40%; text-align: right; vertical-align: bottom;">
                    @if(isset($setting->signature) && $setting->signature)
                        <img src="{{ asset($setting->signature) }}" alt="Authorized Signature" style="max-height: 80px; margin-bottom: 5px;"><br>
                    @else
                        <div style="height: 80px; margin-bottom: 5px;"></div>
                    @endif
                    <hr style="border: 0; border-top: 1px solid #333; width: 200px; margin-left: auto; margin-bottom: 5px;">
                    <strong>{{ $setting->signatory_designation ?? 'Authorized Signature' }}</strong>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="text-center" style="margin-top: 20px;">
    <button onclick="window.print()" style="padding: 10px 20px; background: #cb202d; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">Print Invoice</button>
</div>

</body>
</html>

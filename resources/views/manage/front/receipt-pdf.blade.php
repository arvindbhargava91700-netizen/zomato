<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt - {{ $transaction->transaction_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .receipt-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #e23744; /* Zomato-like red */
            margin: 0 0 10px 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }
        .row {
            width: 100%;
            display: table;
            margin-bottom: 20px;
        }
        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            margin-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .info-block p {
            margin: 5px 0;
            font-size: 14px;
        }
        .info-block strong {
            color: #475569;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .table th {
            background-color: #f8fafc;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right !important;
        }
        .total-row td {
            font-weight: bold;
            font-size: 16px;
            border-top: 2px solid #cbd5e1;
            color: #0f172a;
        }
        .footer {
            text-align: center;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 12px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-warning { background-color: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $companySetting->company_name ?? 'Zomato' }}</h1>
            <p>Transaction Receipt</p>
            @if($companySetting && $companySetting->company_address)
                <p>{{ $companySetting->company_address }}</p>
            @endif
        </div>

        <!-- Info Blocks -->
        <div class="row">
            <!-- Left Column: Transaction Details -->
            <div class="col info-block">
                <div class="section-title">Transaction Details</div>
                <p><strong>Transaction ID:</strong> {{ $transaction->transaction_number }}</p>
                <p><strong>Date:</strong> {{ $transaction->paid_at ? $transaction->paid_at->format('d M Y, h:i A') : $transaction->created_at->format('d M Y, h:i A') }}</p>
                <p><strong>Payment Method:</strong> {{ ucwords(str_replace('_', ' ', $transaction->payment_method ?: 'N/A')) }}</p>
                <p><strong>Status:</strong> 
                    @php
                        $badgeClass = 'badge-warning';
                        if ($transaction->status === 'success' || $transaction->status === 'paid') $badgeClass = 'badge-success';
                        elseif ($transaction->status === 'failed') $badgeClass = 'badge-danger';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $transaction->status ?: 'UNKNOWN' }}</span>
                </p>
            </div>
            
            <!-- Right Column: Customer/Order Details -->
            <div class="col info-block text-right">
                <div class="section-title text-right">Customer Details</div>
                <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                @if($transaction->order_id)
                    <p><strong>Order No:</strong> #{{ $transaction->order_id }}</p>
                    @if($transaction->restaurant)
                        <p><strong>Restaurant:</strong> {{ $transaction->restaurant->restaurant_name }}</p>
                    @endif
                @endif
            </div>
        </div>

        <!-- Description/Items Table -->
        <div class="section-title">Description</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- If we have order items -->
                @if($transaction->order && $transaction->order->items && $transaction->order->items->count() > 0)
                    @foreach($transaction->order->items as $item)
                    <tr>
                        <td>
                            {{ $item->name ?: ($item->food ? $item->food->name : 'Food Item') }} 
                            @if($item->qty > 1) x{{ $item->qty }} @endif
                        </td>
                        <td class="text-right">{{ $currencySymbol }}{{ number_format($item->price * $item->qty, 2) }}</td>
                    </tr>
                    @endforeach
                    <!-- Add extra fees if present on the order -->
                    @if($transaction->order->delivery_charge > 0)
                    <tr>
                        <td>Delivery Charge</td>
                        <td class="text-right">{{ $currencySymbol }}{{ number_format($transaction->order->delivery_charge, 2) }}</td>
                    </tr>
                    @endif
                    @if($transaction->order->tax > 0)
                    <tr>
                        <td>Tax/GST</td>
                        <td class="text-right">{{ $currencySymbol }}{{ number_format($transaction->order->tax, 2) }}</td>
                    </tr>
                    @endif
                    @if($transaction->order->discount > 0)
                    <tr>
                        <td>Discount</td>
                        <td class="text-right">-{{ $currencySymbol }}{{ number_format($transaction->order->discount, 2) }}</td>
                    </tr>
                    @endif
                @else
                    <!-- Fallback if no specific order items are found -->
                    <tr>
                        <td>{{ $transaction->note ?: 'Payment for Order #' . $transaction->order_id }}</td>
                        <td class="text-right">{{ $currencySymbol }}{{ number_format($transaction->total_amount, 2) }}</td>
                    </tr>
                @endif
                
                <tr class="total-row">
                    <td class="text-right">Total Paid</td>
                    <td class="text-right">{{ $currencySymbol }}{{ number_format($transaction->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for choosing {{ $companySetting->company_name ?? 'us' }}!</p>
            <p>This is a computer-generated receipt and does not require a physical signature.</p>
        </div>
    </div>
</body>
</html>

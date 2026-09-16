@extends('layouts.admin.main')

@section('title', getPageTitle('Transaction Voucher #' . $transaction->transaction_number . ' - Admin'))

@push('styles')
<style>
    /* -------------------------------------------------------------
       ADMIN FINTECH TRANSACTION SLIP - PREMIUM STYLING
       ------------------------------------------------------------- */
    .slip-page-bg {
        padding: 24px 0 60px;
        min-height: 80vh;
    }

    .voucher-card-wrapper {
        max-width: 680px;
        margin: 0 auto;
        position: relative;
    }

    /* Main Voucher Paper Container */
    .voucher-paper {
        background: #ffffff;
        border-radius: 24px 24px 0 0;
        box-shadow: 0 20px 60px -15px rgba(15, 23, 42, 0.12), 0 0 0 1px rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    /* Sawtooth / Zig-zag Thermal Cut at Bottom */
    .voucher-zigzag-bottom {
        height: 18px;
        background: radial-gradient(circle, transparent, transparent 50%, #ffffff 50%, #ffffff 100%);
        background-size: 16px 16px;
        background-position: -8px -8px;
        position: relative;
        filter: drop-shadow(0 8px 12px rgba(15, 23, 42, 0.08));
    }

    /* Hero Gradients */
    .voucher-hero-credit {
        background: linear-gradient(135deg, #064e3b 0%, #059669 45%, #10b981 100%);
        color: #ffffff;
        position: relative;
        padding: 38px 32px 32px;
        overflow: hidden;
    }

    .voucher-hero-debit {
        background: linear-gradient(135deg, #4c0519 0%, #be123c 45%, #f43f5e 100%);
        color: #ffffff;
        position: relative;
        padding: 38px 32px 32px;
        overflow: hidden;
    }

    /* Ambient Orb Highlights */
    .voucher-hero-credit::before, .voucher-hero-debit::before {
        content: '';
        position: absolute;
        width: 280px;
        height: 280px;
        top: -120px;
        right: -80px;
        background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .voucher-hero-credit::after, .voucher-hero-debit::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        bottom: -90px;
        left: -60px;
        background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    /* Live Pulsing Radar Dot */
    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #34d399;
        display: inline-block;
        position: relative;
        box-shadow: 0 0 0 rgba(52, 211, 153, 0.4);
        animation: pulseAnimation 2s infinite;
    }
    .pulse-dot-red {
        background-color: #f87171;
        box-shadow: 0 0 0 rgba(248, 113, 113, 0.4);
    }
    @keyframes pulseAnimation {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(255, 255, 255, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
    }

    /* Voucher Central Hero Icon Container */
    .hero-icon-bubble {
        width: 76px;
        height: 76px;
        margin: 0 auto 16px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 2px solid rgba(255, 255, 255, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
    }

    /* Perforated Separator Bar with Punch Notches */
    .perforation-strip {
        position: relative;
        height: 32px;
        background: #ffffff;
        display: flex;
        align-items: center;
        margin: 0;
    }

    .perforation-dashed-line {
        width: 100%;
        height: 2px;
        border-top: 2px dashed #e2e8f0;
    }

    .notch-left {
        position: absolute;
        left: -14px;
        top: 50%;
        transform: translateY(-50%);
        width: 28px;
        height: 28px;
        background: #f1f5f9;
        border-radius: 50%;
        border-right: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: inset -3px 0 5px rgba(0, 0, 0, 0.06);
    }

    .notch-right {
        position: absolute;
        right: -14px;
        top: 50%;
        transform: translateY(-50%);
        width: 28px;
        height: 28px;
        background: #f1f5f9;
        border-radius: 50%;
        border-left: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: inset 3px 0 5px rgba(0, 0, 0, 0.06);
    }

    /* Balance Flow Interactive Ribbon */
    .balance-flow-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 16px 20px;
        position: relative;
    }

    .flow-connector-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 13px;
    }

    /* Data Item Grid Box */
    .data-pill-card {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        padding: 14px 16px;
        transition: all 0.2s ease;
    }
    .data-pill-card:hover {
        background: #ffffff;
        border-color: #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .item-icon-wrap {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    /* Rubber Stamp Seal */
    .verified-seal {
        border: 2px dashed #059669;
        color: #059669;
        font-weight: 800;
        font-size: 11px;
        letter-spacing: 2px;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 8px;
        transform: rotate(-6deg);
        display: inline-block;
        background: rgba(16, 185, 129, 0.06);
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
    }

    /* Barcode simulation */
    .barcode-lines {
        height: 48px;
        background: repeating-linear-gradient(
            90deg,
            #0f172a 0px,
            #0f172a 2px,
            transparent 2px,
            transparent 4px,
            #0f172a 4px,
            #0f172a 7px,
            transparent 7px,
            transparent 9px,
            #0f172a 9px,
            #0f172a 11px,
            transparent 11px,
            transparent 14px,
            #0f172a 14px,
            #0f172a 18px,
            transparent 18px,
            transparent 20px,
            #0f172a 20px,
            #0f172a 24px,
            transparent 24px,
            transparent 27px
        );
        opacity: 0.85;
        border-radius: 4px;
    }

    /* Print View Optimization */
    @media print {
        body {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .page-header, .page-header-right, .nxl-navigation, .nxl-header, .nxl-footer, .no-print, .theme-customizer {
            display: none !important;
        }
        .nxl-container {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .slip-page-bg {
            padding: 0 !important;
        }
        .voucher-card-wrapper {
            max-width: 100% !important;
            margin: 0 !important;
        }
        .voucher-paper {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
        }
        .voucher-hero-credit, .voucher-hero-debit {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .badge, .balance-flow-box, .data-pill-card {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endpush

@section('content')
<!-- [ page-header ] start -->
<div class="page-header d-flex align-items-center justify-content-between mb-4 no-print">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10 fw-bold">Admin Wallet Voucher</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.wallet-transactions.index') }}">Wallet Transactions</a></li>
            <li class="breadcrumb-item">Slip #{{ $transaction->id }}</li>
        </ul>
    </div>
    <div class="page-header-right d-flex gap-2">
        <a href="{{ route('admin.wallet-transactions.index') }}" class="btn btn-sm btn-light border shadow-sm px-3">
            <i class="feather-arrow-left me-1"></i>All Wallet Transactions
        </a>
        <button onclick="window.print()" class="btn btn-sm btn-dark shadow-sm px-3">
            <i class="feather-printer me-1"></i>Print Slip
        </button>
    </div>
</div>
<!-- [ page-header ] end -->

@php
    $isCredit = ($transaction->type === 'credit');
    $currencySym = $currencySymbol ?? ($setting ? $setting->currencySymbol() : '₹');
    $companyTitle = $setting->company_name ?? 'ZOMATO PLATFORM';
    
    // Source labels & icons
    $sourceMeta = [
        'delivery_partner_payout' => [
            'label' => 'Delivery Commission Payout',
            'desc'  => 'Order delivery earnings credited to delivery courier',
            'icon'  => 'feather-truck',
            'bg'    => 'bg-soft-success',
            'color' => 'text-success'
        ],
        'cod_cash_collected' => [
            'label' => 'COD Cash Collection (Cash-in-Hand)',
            'desc'  => 'Cash collected from customer on COD order',
            'icon'  => 'feather-dollar-sign',
            'bg'    => 'bg-soft-warning',
            'color' => 'text-warning'
        ],
        'cod_settlement_confirmed' => [
            'label' => 'COD Settlement Cleared',
            'desc'  => 'Cash deposit verified and cleared by admin',
            'icon'  => 'feather-check-circle',
            'bg'    => 'bg-soft-primary',
            'color' => 'text-primary'
        ],
        'wallet_topup' => [
            'label' => 'Customer Wallet Top-up',
            'desc'  => 'Funds added via online payment gateway',
            'icon'  => 'feather-plus-circle',
            'bg'    => 'bg-soft-success',
            'color' => 'text-success'
        ],
        'order_payment' => [
            'label' => 'Order Payment via Wallet',
            'desc'  => 'Wallet funds spent on food order checkout',
            'icon'  => 'feather-shopping-bag',
            'bg'    => 'bg-soft-danger',
            'color' => 'text-danger'
        ],
        'order_restaurant_payout' => [
            'label' => 'Restaurant Order Payout',
            'desc'  => 'Restaurant food share earnings credited',
            'icon'  => 'feather-home',
            'bg'    => 'bg-soft-success',
            'color' => 'text-success'
        ],
        'manual' => [
            'label' => 'Admin Wallet Adjustment',
            'desc'  => 'Manual credit/debit adjustment by platform admin',
            'icon'  => 'feather-sliders',
            'bg'    => 'bg-soft-secondary',
            'color' => 'text-secondary'
        ],
    ];

    $currentSource = $sourceMeta[$transaction->source] ?? [
        'label' => ucwords(str_replace('_', ' ', $transaction->source)),
        'desc'  => $transaction->description ?? 'Wallet ledger record',
        'icon'  => $isCredit ? 'feather-arrow-down-left' : 'feather-arrow-up-right',
        'bg'    => $isCredit ? 'bg-soft-success' : 'bg-soft-danger',
        'color' => $isCredit ? 'text-success' : 'text-danger'
    ];
@endphp

<div class="main-content slip-page-bg">
    <div class="voucher-card-wrapper">
        <div class="voucher-paper">

            <!-- -------------------------------------------------------------
                 1. SLIP HERO HEADER
                 ------------------------------------------------------------- -->
            <div class="{{ $isCredit ? 'voucher-hero-credit' : 'voucher-hero-debit' }} text-center">
                <!-- Top Brand Bar -->
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-white text-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px;">
                            <i class="feather-box text-primary fs-16"></i>
                        </div>
                        <div class="text-start">
                            <span class="fs-13 fw-bold text-white tracking-wide d-block text-uppercase" style="letter-spacing: 1px;">
                                {{ $companyTitle }}
                            </span>
                            <span class="fs-10 text-white-50 text-uppercase fw-semibold">Central Financial Ledger</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-white bg-opacity-20 text-white border border-white border-opacity-40 px-3 py-1 rounded-pill fs-11 fw-semibold d-inline-flex align-items-center gap-2">
                            <span class="pulse-dot {{ $isCredit ? '' : 'pulse-dot-red' }}"></span>
                            {{ $isCredit ? 'Credit Settled' : 'Debit Settled' }}
                        </span>
                    </div>
                </div>

                <!-- Central Icon Bubble -->
                <div class="hero-icon-bubble">
                    <i class="{{ $currentSource['icon'] }} text-white" style="font-size: 34px;"></i>
                </div>

                <!-- Transaction Purpose Tag -->
                <div class="text-white-50 fs-12 fw-bold text-uppercase tracking-wider mb-1">
                    {{ $currentSource['label'] }}
                </div>

                <!-- Large Amount Display -->
                <h1 class="display-4 fw-bolder text-white mb-2" style="letter-spacing: -1.5px;">
                    {{ $isCredit ? '+' : '-' }}{{ $currencySym }}{{ number_format($transaction->amount, 2) }}
                </h1>

                <!-- Subtitle Pill -->
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill my-1" 
                     style="background: rgba(0, 0, 0, 0.18); border: 1px solid rgba(255, 255, 255, 0.25);">
                    <i class="feather-shield text-white fs-12"></i>
                    <span class="text-white fs-12 fw-semibold">
                        {{ $isCredit ? 'Credited to User Wallet' : 'Debited from User Wallet' }}
                    </span>
                </div>

                <!-- Transaction Reference ID & Copy Button -->
                <div class="mt-3 pt-2 text-white-50 fs-12 font-monospace d-flex align-items-center justify-content-center gap-2">
                    <span>TXN: <strong class="text-white">{{ $transaction->transaction_number }}</strong></span>
                    <button type="button" class="btn btn-xs btn-outline-light py-0 px-2 rounded-pill fs-11 text-white border-white border-opacity-50" 
                            id="copyTxnBtn"
                            onclick="navigator.clipboard.writeText('{{ $transaction->transaction_number }}'); this.innerHTML='<i class=\'feather-check me-1\'></i>Copied!'; setTimeout(() => this.innerHTML='<i class=\'feather-copy me-1\'></i>Copy', 1800);" 
                            title="Copy Transaction Number">
                        <i class="feather-copy me-1"></i>Copy
                    </button>
                </div>
            </div>

            <!-- -------------------------------------------------------------
                 2. REALISTIC PERFORATION WITH SIDE NOTCHES
                 ------------------------------------------------------------- -->
            <div class="perforation-strip">
                <div class="notch-left"></div>
                <div class="perforation-dashed-line"></div>
                <div class="notch-right"></div>
            </div>

            <!-- -------------------------------------------------------------
                 3. VOUCHER BODY & AUDIT BREAKDOWN
                 ------------------------------------------------------------- -->
            <div class="p-4 p-md-5 pt-3 pb-4">

                <!-- Balance Progression Ribbon (Before -> Amount -> After) -->
                <div class="balance-flow-box mb-4">
                    <div class="d-flex align-items-center justify-content-between text-center">
                        <div class="flex-fill">
                            <span class="fs-11 text-uppercase text-muted fw-bold d-block mb-1">Previous Balance</span>
                            <span class="fs-15 fw-bold text-secondary">
                                {{ $currencySym }}{{ number_format($transaction->balance_before, 2) }}
                            </span>
                        </div>

                        <div class="px-2">
                            <div class="flow-connector-icon">
                                <i class="feather-arrow-right"></i>
                            </div>
                        </div>

                        <div class="flex-fill">
                            <span class="fs-11 text-uppercase {{ $isCredit ? 'text-success' : 'text-danger' }} fw-bold d-block mb-1">
                                {{ $isCredit ? 'Credit Added' : 'Debit Deducted' }}
                            </span>
                            <span class="fs-15 fw-bolder {{ $isCredit ? 'text-success' : 'text-danger' }}">
                                {{ $isCredit ? '+' : '-' }}{{ $currencySym }}{{ number_format($transaction->amount, 2) }}
                            </span>
                        </div>

                        <div class="px-2">
                            <div class="flow-connector-icon">
                                <i class="feather-arrow-right"></i>
                            </div>
                        </div>

                        <div class="flex-fill">
                            <span class="fs-11 text-uppercase text-muted fw-bold d-block mb-1">Updated Balance</span>
                            <span class="badge bg-dark text-white fs-14 fw-bold px-3 py-1 rounded-pill shadow-sm">
                                {{ $currencySym }}{{ number_format($transaction->balance_after, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Account & Beneficiary Card -->
                @if($transaction->user)
                    <div class="card border rounded-4 bg-light p-3 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fs-11 text-uppercase text-muted fw-bold">
                                <i class="feather-user me-1 text-primary"></i>Account Beneficiary
                            </span>
                            <span class="badge bg-primary text-white fs-10 px-2 py-0">
                                {{ $transaction->user->role?->name ?? 'User' }}
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-white shadow-sm border d-flex align-items-center justify-content-center text-dark fw-bold" style="width: 44px; height: 44px; font-size: 16px;">
                                {{ strtoupper(substr($transaction->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark fs-14">{{ $transaction->user->name }}</h6>
                                <div class="fs-12 text-muted">
                                    {{ $transaction->user->email ?? 'No email' }} • {{ $transaction->user->phone ?? 'No phone' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Transaction Key Details Grid -->
                <div class="row g-3 mb-4">
                    <!-- Description -->
                    <div class="col-12">
                        <div class="data-pill-card d-flex align-items-start gap-3">
                            <div class="item-icon-wrap bg-soft-primary text-primary">
                                <i class="feather-file-text"></i>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fs-11 text-muted text-uppercase fw-bold d-block">Transaction Description</span>
                                <span class="fs-14 fw-bold text-dark">{{ $transaction->description ?? 'Wallet Ledger Transaction' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Category / Source -->
                    <div class="col-sm-6">
                        <div class="data-pill-card d-flex align-items-center gap-3">
                            <div class="item-icon-wrap {{ $currentSource['bg'] }} {{ $currentSource['color'] }}">
                                <i class="{{ $currentSource['icon'] }}"></i>
                            </div>
                            <div>
                                <span class="fs-11 text-muted text-uppercase fw-bold d-block">Transaction Source</span>
                                <span class="fs-13 fw-bold text-dark font-monospace">{{ $transaction->source }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="col-sm-6">
                        <div class="data-pill-card d-flex align-items-center gap-3">
                            <div class="item-icon-wrap bg-soft-info text-info">
                                <i class="feather-calendar"></i>
                            </div>
                            <div>
                                <span class="fs-11 text-muted text-uppercase fw-bold d-block">Settlement Time</span>
                                <span class="fs-13 fw-bold text-dark">
                                    {{ $transaction->created_at ? $transaction->created_at->format('d M Y, h:i A') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- -------------------------------------------------------------
                     4. LINKED ORDER SUMMARY (IF LINKED TO AN ORDER)
                     ------------------------------------------------------------- -->
                @if($order)
                    <div class="card border rounded-4 overflow-hidden mb-4 shadow-sm" style="border-color: #e2e8f0;">
                        <div class="card-header bg-light py-3 px-3 border-bottom d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary text-white px-2 py-1 fs-12 rounded">
                                    <i class="feather-shopping-bag me-1"></i>Linked Order #{{ $order->id }}
                                </span>
                                <span class="fs-12 text-muted fw-semibold">Associated Order Breakdown</span>
                            </div>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <div class="row g-3">
                                <!-- Restaurant Info -->
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="feather-map-pin text-danger fs-16 mt-1"></i>
                                        <div>
                                            <span class="text-muted fs-11 text-uppercase fw-bold d-block">Restaurant</span>
                                            <span class="fw-bold text-dark fs-14">{{ $order->restaurant?->restaurant_name ?? 'Restaurant' }}</span>
                                            <span class="text-muted fs-11 d-block">{{ $order->restaurant?->address ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Info -->
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="feather-user-check text-success fs-16 mt-1"></i>
                                        <div>
                                            <span class="text-muted fs-11 text-uppercase fw-bold d-block">Customer</span>
                                            <span class="fw-bold text-dark fs-14">{{ $order->user?->name ?? 'Customer' }}</span>
                                            <span class="text-muted fs-11 d-block">{{ $order->address?->address ?? 'Customer Address' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Financial Row -->
                                <div class="col-12 pt-3 border-top">
                                    <div class="row g-2 text-center">
                                        <div class="col-4">
                                            <span class="text-muted fs-11 text-uppercase fw-bold d-block">Payment Mode</span>
                                            <span class="badge {{ $order->payment_method === 'cash_on_delivery' ? 'bg-soft-warning text-warning' : 'bg-soft-success text-success' }} fs-11 fw-bold text-uppercase">
                                                {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                                            </span>
                                        </div>
                                        <div class="col-4">
                                            <span class="text-muted fs-11 text-uppercase fw-bold d-block">Order Total</span>
                                            <span class="fw-bold text-dark fs-13">{{ $currencySym }}{{ number_format($order->total, 2) }}</span>
                                        </div>
                                        <div class="col-4">
                                            <span class="text-muted fs-11 text-uppercase fw-bold d-block">Delivery Fee</span>
                                            <span class="fw-bold text-success fs-13">{{ $currencySym }}{{ number_format($order->delivery_charge, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Metadata Payload (if present) -->
                @if(!empty($transaction->meta_data))
                    <div class="card border rounded-3 bg-light p-3 mb-4">
                        <span class="fs-11 text-uppercase text-muted fw-bold mb-2 d-block">
                            <i class="feather-code me-1"></i>Transaction Payload Metadata
                        </span>
                        <pre class="mb-0 fs-11 text-muted" style="white-space: pre-wrap; font-family: monospace; max-height: 120px; overflow-y: auto;">{{ json_encode($transaction->meta_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                @endif

                <!-- -------------------------------------------------------------
                     5. OFFICIAL VERIFICATION WATERMARK & BARCODE
                     ------------------------------------------------------------- -->
                <div class="p-3 bg-light rounded-4 border text-center position-relative mb-2">
                    <div class="row align-items-center g-3">
                        <div class="col-sm-4 text-center">
                            <div class="verified-seal mb-2">
                                <i class="feather-check-circle me-1"></i>VERIFIED
                            </div>
                            <div class="fs-10 text-muted font-monospace">
                                ADMIN LEDGER AUTHENTICATED
                            </div>
                        </div>

                        <div class="col-sm-8 text-center text-sm-end">
                            <div class="barcode-lines mb-1 ms-auto" style="max-width: 280px;"></div>
                            <div class="fs-11 text-dark font-monospace fw-bold tracking-wider">
                                {{ $transaction->transaction_number }}
                            </div>
                            <div class="fs-10 text-muted">
                                Digital Cryptographic Record • {{ now()->format('d-M-Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Printable Footer Note -->
                <div class="text-center mt-3">
                    <p class="fs-11 text-muted mb-0">
                        Official system generated wallet ledger voucher. Recorded in Zomato Central Database.
                    </p>
                </div>
            </div>

            <!-- Bottom Thermal Sawtooth Cut -->
            <div class="voucher-zigzag-bottom"></div>
        </div>

        <!-- -------------------------------------------------------------
             6. ACTION TOOLBAR (Print, Share, Back)
             ------------------------------------------------------------- -->
        <div class="d-flex flex-wrap gap-2 justify-content-center mt-4 no-print">
            <button type="button" onclick="window.print()" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm fw-semibold">
                <i class="feather-printer me-2"></i>Print Slip Voucher
            </button>
            <a href="{{ route('admin.wallet-transactions.index') }}" class="btn btn-light border px-4 py-2 rounded-pill text-muted fw-semibold">
                <i class="feather-arrow-left me-1"></i>Back to Ledger
            </a>
        </div>
    </div>
</div>
@endsection

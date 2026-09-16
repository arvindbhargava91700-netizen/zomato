@extends('layouts.admin.main')

@section('title', getPageTitle('Configure ' . $gateway->name . ' - Payment Gateway'))

@php
    $gwIcons = [
        'razorpay' => 'feather-zap',
        'phonepe' => 'feather-smartphone',
        'paytm' => 'feather-credit-card',
        'payu' => 'feather-shield',
        'paypal' => 'feather-globe',
        'stripe' => 'feather-credit-card',
        'cashfree' => 'feather-dollar-sign',
    ];
    $iconClass = $gwIcons[$gateway->gateway_key] ?? 'feather-credit-card';

    $keyLabels = [
        'razorpay' => 'Razorpay Key ID',
        'phonepe' => 'PhonePe Merchant ID / App ID',
        'paytm' => 'Paytm Merchant ID (MID)',
        'payu' => 'PayU Merchant Key',
        'paypal' => 'PayPal Client ID',
        'stripe' => 'Stripe Publishable Key',
        'cashfree' => 'Cashfree App ID',
    ];
    $keyLabel = $keyLabels[$gateway->gateway_key] ?? ($gateway->name . ' Key ID / Public Key');

    $secretLabels = [
        'razorpay' => 'Razorpay Key Secret',
        'phonepe' => 'PhonePe Salt Key / API Secret',
        'paytm' => 'Paytm Merchant Key (Secret)',
        'payu' => 'PayU Merchant Salt',
        'paypal' => 'PayPal Secret Key',
        'stripe' => 'Stripe Secret Key',
        'cashfree' => 'Cashfree Secret Key',
    ];
    $secretLabel = $secretLabels[$gateway->gateway_key] ?? ($gateway->name . ' Key Secret / Private Key');

    $placeholders = [
        'razorpay' => ['key' => 'E.g. rzp_test_TWIAoYszpcVthe', 'secret' => 'E.g. EyaGehL4Ok8UttyrPDipNTcO'],
        'phonepe' => ['key' => 'E.g. PGTESTPAYUAT', 'secret' => 'E.g. 099eb0cd-02cf-4e2a-8aca-3e6c6aff0399'],
        'paytm' => ['key' => 'E.g. YOUR_MID_HERE', 'secret' => 'E.g. YOUR_MERCHANT_KEY'],
        'payu' => ['key' => 'E.g. YOUR_PAYU_KEY', 'secret' => 'E.g. YOUR_PAYU_SALT'],
        'paypal' => ['key' => 'E.g. A21AAFe_...', 'secret' => 'E.g. EP...'],
        'stripe' => ['key' => 'E.g. pk_test_...', 'secret' => 'E.g. sk_test_...'],
    ];
    $sampleKey = $placeholders[$gateway->gateway_key]['key'] ?? 'Enter public / API key';
    $sampleSecret = $placeholders[$gateway->gateway_key]['secret'] ?? 'Enter secret key';
@endphp

@section('content')
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10"><i class="{{ $iconClass }} me-2 text-primary"></i> Configure {{ $gateway->name }} Payment Gateway</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.payment-gateways.index') }}">Payment Gateways</a></li>
                <li class="breadcrumb-item active">{{ $gateway->name }} Details</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to Gateways
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="feather-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <strong>Please fix the errors below:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8 col-12">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header p-4 border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                 style="width: 44px; height: 44px; background-color: {{ $gateway->badge_color ?: '#072654' }}; font-size: 20px;">
                                <i class="{{ $iconClass }}"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $gateway->name }} API Credentials</h6>
                                <span class="text-muted small">Configure keys, mode, and parameters for {{ $gateway->name }}.</span>
                            </div>
                        </div>
                        <span class="badge {{ $gateway->is_active ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary' }} fs-11">
                            {{ $gateway->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </div>

                    <form action="{{ route('admin.payment-gateways.update', $gateway->gateway_key) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body p-4">
                            <!-- Gateway Name / Display Label -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Display Label (Shown on Checkout) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="feather-type text-muted"></i></span>
                                    <input type="text"
                                           name="display_name"
                                           class="form-control"
                                           value="{{ old('display_name', $gateway->display_name ?: $gateway->name) }}"
                                           placeholder="E.g. {{ $gateway->name }} (UPI, Cards & NetBanking)"
                                           required>
                                </div>
                                <small class="text-muted">The name shown to customers during table booking and checkout.</small>
                            </div>

                            <!-- Environment Mode (Sandbox vs Live) -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Environment Mode <span class="text-danger">*</span></label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="p-3 border rounded-3 d-flex align-items-center gap-3 w-100 cursor-pointer {{ old('mode', $gateway->mode) === 'sandbox' ? 'bg-soft-warning border-warning' : 'bg-white' }}" style="cursor: pointer;">
                                            <input type="radio" name="mode" value="sandbox" {{ old('mode', $gateway->mode) === 'sandbox' ? 'checked' : '' }} class="form-check-input mt-0">
                                            <div>
                                                <div class="fw-bold text-dark"><i class="feather-tool text-warning me-1"></i> Sandbox / Test Mode</div>
                                                <div class="fs-12 text-muted">Use for development & test transactions</div>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="p-3 border rounded-3 d-flex align-items-center gap-3 w-100 cursor-pointer {{ old('mode', $gateway->mode) === 'live' ? 'bg-soft-success border-success' : 'bg-white' }}" style="cursor: pointer;">
                                            <input type="radio" name="mode" value="live" {{ old('mode', $gateway->mode) === 'live' ? 'checked' : '' }} class="form-check-input mt-0">
                                            <div>
                                                <div class="fw-bold text-dark"><i class="feather-check-circle text-success me-1"></i> Live / Production Mode</div>
                                                <div class="fs-12 text-muted">Real money payments & live card processing</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Toggle -->
                            <div class="mb-4 p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold text-dark">Enable {{ $gateway->name }} on Customer Storefront</div>
                                    <div class="fs-12 text-muted">When enabled, customers can choose {{ $gateway->name }} for table reservation cover charges and food orders.</div>
                                </div>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="is_active"
                                           value="1"
                                           id="statusSwitch"
                                           {{ old('is_active', $gateway->is_active) ? 'checked' : '' }}
                                           style="cursor: pointer; width: 44px; height: 22px;">
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- API Key ID -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">{{ $keyLabel }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="feather-key text-muted"></i></span>
                                    <input type="text"
                                           name="key_id"
                                           id="gatewayKeyId"
                                           class="form-control font-monospace"
                                           value="{{ old('key_id', $gateway->key_id) }}"
                                           placeholder="{{ $sampleKey }}"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="copyInput('gatewayKeyId')" title="Copy Key ID">
                                        <i class="feather-copy"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Your public / client ID generated from the {{ $gateway->name }} dashboard.</small>
                            </div>

                            <!-- API Key Secret -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">{{ $secretLabel }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="feather-lock text-muted"></i></span>
                                    <input type="password"
                                           name="key_secret"
                                           id="gatewayKeySecret"
                                           class="form-control font-monospace"
                                           value="{{ old('key_secret', $gateway->key_secret) }}"
                                           placeholder="{{ $sampleSecret }}"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="toggleSecretVisibility('gatewayKeySecret', this)" title="Show/Hide Secret">
                                        <i class="feather-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Your secret API key. Keep this private and confidential.</small>
                            </div>

                            <div class="row g-3 mb-4">
                                <!-- Webhook Secret -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Webhook Secret / Salt (Optional)</label>
                                    <input type="text"
                                           name="webhook_secret"
                                           class="form-control font-monospace"
                                           value="{{ old('webhook_secret', $gateway->webhook_secret) }}"
                                           placeholder="E.g. whsec_... / Salt Index">
                                    <small class="text-muted">For server-to-server webhook verification.</small>
                                </div>

                                <!-- Currency -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Currency Code <span class="text-danger">*</span></label>
                                    <input type="text"
                                           name="currency"
                                           class="form-control text-uppercase font-monospace fw-bold"
                                           value="{{ old('currency', $gateway->currency ?: 'INR') }}"
                                           placeholder="INR"
                                           required>
                                    <small class="text-muted">Standard 3-letter currency code (e.g. INR, USD).</small>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <!-- Theme Color -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Brand Theme Color</label>
                                    <div class="input-group">
                                        <input type="color"
                                               class="form-control form-control-color"
                                               id="themeColorPicker"
                                               value="{{ old('theme_color', $gateway->theme_color ?: $gateway->badge_color ?: '#072654') }}"
                                               onchange="document.getElementById('themeColorText').value = this.value">
                                        <input type="text"
                                               name="theme_color"
                                               id="themeColorText"
                                               class="form-control font-monospace"
                                               value="{{ old('theme_color', $gateway->theme_color ?: $gateway->badge_color ?: '#072654') }}">
                                    </div>
                                    <small class="text-muted">Accent color used for {{ $gateway->name }} badges and modal.</small>
                                </div>

                                <!-- Merchant Account ID -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Merchant Account ID / Sub-merchant (Optional)</label>
                                    <input type="text"
                                           name="merchant_id"
                                           class="form-control"
                                           value="{{ old('merchant_id', $gateway->merchant_id) }}"
                                           placeholder="E.g. acc_... / MID">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Gateway Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Describe the payment methods supported...">{{ old('description', $gateway->description) }}</textarea>
                            </div>
                        </div>

                        <div class="card-footer p-4 bg-light border-top d-flex align-items-center justify-content-between">
                            <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-light border text-secondary fw-semibold">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary fw-bold px-4">
                                <i class="feather-save me-1"></i> Save {{ $gateway->name }} Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help & Guidance Column -->
            <div class="col-lg-4 col-12">
                <!-- Gateway Specific Guide Card -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header p-4 border-bottom bg-white">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="feather-help-circle me-1 text-primary"></i> How to Get {{ $gateway->name }} Keys
                        </h6>
                    </div>
                    <div class="card-body p-4 fs-13">
                        @if($gateway->gateway_key === 'razorpay')
                            <ol class="ps-3 mb-3">
                                <li class="mb-2">Log in to your <b><a href="https://dashboard.razorpay.com" target="_blank" class="text-primary fw-semibold">Razorpay Dashboard</a></b>.</li>
                                <li class="mb-2">Go to <b>Settings</b> &rarr; <b>API Keys</b> tab.</li>
                                <li class="mb-2">Click <b>Generate Key</b> to get your <b>Key ID</b> and <b>Key Secret</b>.</li>
                                <li class="mb-2">Paste both keys into the form on the left.</li>
                                <li class="mb-2">Ensure <b>Sandbox Mode</b> is chosen for testing, or <b>Live Mode</b> for production.</li>
                            </ol>
                        @elseif($gateway->gateway_key === 'phonepe')
                            <ol class="ps-3 mb-3">
                                <li class="mb-2">Log in to the <b><a href="https://business.phonepe.com" target="_blank" class="text-primary fw-semibold">PhonePe Merchant Dashboard</a></b>.</li>
                                <li class="mb-2">Navigate to <b>Developer Settings</b> &rarr; <b>API Credentials</b>.</li>
                                <li class="mb-2">Copy your <b>Merchant ID (MID)</b> and <b>Salt Key</b>.</li>
                                <li class="mb-2">Enter your <b>Salt Index</b> into the Webhook Secret field.</li>
                            </ol>
                        @elseif($gateway->gateway_key === 'paytm')
                            <ol class="ps-3 mb-3">
                                <li class="mb-2">Log in to the <b><a href="https://dashboard.paytm.com" target="_blank" class="text-primary fw-semibold">Paytm Dashboard</a></b>.</li>
                                <li class="mb-2">Go to <b>Developer Settings</b> &rarr; <b>API Keys</b>.</li>
                                <li class="mb-2">Copy your <b>Merchant ID (MID)</b> and <b>Merchant Key</b>.</li>
                                <li class="mb-2">Choose between Test and Production credentials.</li>
                            </ol>
                        @elseif($gateway->gateway_key === 'payu')
                            <ol class="ps-3 mb-3">
                                <li class="mb-2">Log in to your <b><a href="https://merchants.payu.in" target="_blank" class="text-primary fw-semibold">PayU Merchant Portal</a></b>.</li>
                                <li class="mb-2">Go to <b>Manage Account</b> &rarr; <b>System Integration</b>.</li>
                                <li class="mb-2">Copy your <b>Merchant Key</b> and <b>Merchant Salt</b>.</li>
                            </ol>
                        @elseif($gateway->gateway_key === 'paypal')
                            <ol class="ps-3 mb-3">
                                <li class="mb-2">Log in to the <b><a href="https://developer.paypal.com" target="_blank" class="text-primary fw-semibold">PayPal Developer Dashboard</a></b>.</li>
                                <li class="mb-2">Go to <b>Apps & Credentials</b> &rarr; <b>REST API Apps</b>.</li>
                                <li class="mb-2">Copy your <b>Client ID</b> and <b>Secret Key</b>.</li>
                                <li class="mb-2">Set Currency to <b>USD</b> (or supported international currency).</li>
                            </ol>
                        @else
                            <ol class="ps-3 mb-3">
                                <li class="mb-2">Log in to your <b>{{ $gateway->name }} Merchant / Developer Portal</b>.</li>
                                <li class="mb-2">Navigate to API Keys or Integration Settings.</li>
                                <li class="mb-2">Copy your Public / Client Key and Secret API Key.</li>
                                <li class="mb-2">Paste both keys into the form fields.</li>
                            </ol>
                        @endif

                        <div class="p-3 bg-soft-info text-info rounded-3 border border-info border-opacity-25 fs-12">
                            <i class="feather-info me-1"></i> Changes take effect immediately across customer checkout and table reservation cover charge payments.
                        </div>
                    </div>
                </div>

                <!-- Webhook URL Card -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header p-4 border-bottom bg-white">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="feather-link me-1 text-primary"></i> Webhook Endpoint URL
                        </h6>
                    </div>
                    <div class="card-body p-4 fs-13">
                        <p class="text-muted mb-2">Set this webhook URL in your {{ $gateway->name }} Dashboard for real-time payment capture notifications:</p>
                        <div class="input-group">
                            <input type="text"
                                   id="webhookUrlField"
                                   class="form-control form-control-sm font-monospace"
                                   value="{{ url('/api/' . $gateway->gateway_key . '/webhook') }}"
                                   readonly>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyInput('webhookUrlField')">
                                <i class="feather-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@push('scripts')
<script>
    function toggleSecretVisibility(inputId, btn) {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('feather-eye');
            icon.classList.add('feather-eye-off');
        } else {
            input.type = 'password';
            icon.classList.remove('feather-eye-off');
            icon.classList.add('feather-eye');
        }
    }

    function copyInput(inputId) {
        var input = document.getElementById(inputId);
        input.select();
        document.execCommand('copy');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Copied to clipboard!',
                showConfirmButton: false,
                timer: 1500
            });
        }
    }
</script>
@endpush

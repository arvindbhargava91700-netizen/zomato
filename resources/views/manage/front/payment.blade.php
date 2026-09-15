@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Payment</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Payment</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- account section starts -->
    <section class="account-section section-b-space pt-0">
        <div class="container">
            <div class="layout-sec">
                <div class="row g-lg-4 g-4">
                    <div class="col-lg-8">
                        @include('manage.front.partials.checkout-stepper', ['step' => 'payment'])

                        <div class="payment-section">
                            <div class="title mb-0">
                                <div class="loader-line"></div>
                                <h3>Choose Payment Method</h3>
                                <h6>There are many Types of Payment Method</h6>
                            </div>
                            <div class="alert alert-danger d-none mt-3" id="payment-error" role="alert"></div>
                            <div class="accordion payment-accordion" id="accordionExample">
                                <!-- 1. Credit / Debit Card -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            Credit / Debit Card (Visa, MasterCard, RuPay, Amex)
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            @php
                                                $savedCards = $userCards ?? (auth()->check() ? auth()->user()->cards()->latest()->get() : collect());
                                            @endphp

                                            @if($savedCards->count() > 0)
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-dark fs-13 mb-2">
                                                        <i class="ri-bank-card-2-line text-primary me-1"></i> Your Saved Cards (Click to Auto-Fill &amp; Pay):
                                                    </label>
                                                    <div class="d-flex flex-column gap-2">
                                                        @foreach($savedCards as $c)
                                                            @php
                                                                $expDisplay = $c->exp_date;
                                                                $expMonth = '12';
                                                                $expYear = '26';
                                                                if (strpos($c->exp_date, '-') !== false) {
                                                                    $parts = explode('-', $c->exp_date);
                                                                    $expYear = substr($parts[0] ?? '', -2);
                                                                    $expMonth = str_pad($parts[1] ?? '01', 2, '0', STR_PAD_LEFT);
                                                                    $expDisplay = $expMonth . '/' . $expYear;
                                                                } elseif (strpos($c->exp_date, '/') !== false) {
                                                                    $parts = explode('/', $c->exp_date);
                                                                    $expMonth = str_pad($parts[0] ?? '01', 2, '0', STR_PAD_LEFT);
                                                                    $expYear = substr($parts[1] ?? '', -2);
                                                                    $expDisplay = $expMonth . '/' . $expYear;
                                                                }
                                                                $cleanNum = preg_replace('/\D/', '', $c->card_number);
                                                                $last4 = substr($cleanNum, -4) ?: '4586';
                                                                $cardType = strtoupper($c->type ?: 'Debit / Credit Card');
                                                            @endphp
                                                            <label class="p-3 rounded-3 border d-flex align-items-center justify-content-between cursor-pointer saved-card-tile position-relative" style="cursor: pointer; background: #ffffff; transition: all 0.2s ease;">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <input class="form-check-input mt-0 saved-card-radio" type="radio" name="payment_method" value="card" id="savedCard_{{ $c->id }}"
                                                                           data-card-id="{{ $c->id }}"
                                                                           data-card-number="{{ $cleanNum }}"
                                                                           data-card-holder="{{ $c->holder_name }}"
                                                                           data-card-exp="{{ $expDisplay }}"
                                                                           data-card-month="{{ $expMonth }}"
                                                                           data-card-year="{{ $expYear }}"
                                                                           data-card-cvv="{{ $c->cvv }}">
                                                                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 40px; height: 40px; background: linear-gradient(135deg, #072654 0%, #1e40af 100%);">
                                                                        <i class="ri-bank-card-fill text-warning fs-20"></i>
                                                                    </div>
                                                                    <div>
                                                                        <div class="fw-bold text-dark fs-13 d-flex align-items-center gap-2">
                                                                            {{ $c->holder_name ?: 'Saved Card' }}
                                                                            <span class="badge bg-light text-primary border fs-10">{{ $cardType }}</span>
                                                                        </div>
                                                                        <div class="text-muted fs-12 font-monospace">
                                                                            &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; <span class="fw-bold text-dark">{{ $last4 }}</span>
                                                                            <span class="ms-2 text-secondary font-sans-serif">Exp: <span class="text-dark fw-semibold">{{ $expDisplay }}</span></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <span class="badge bg-soft-success text-success fs-10 rounded-pill"><i class="ri-flashlight-line me-1"></i>Auto-Fill</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Pay with New Card Option -->
                                            <ul class="card-list mb-0">
                                                <li>
                                                    <div class="form-check form-check-reverse">
                                                        <label class="form-check-label d-flex align-items-center justify-content-between flex-wrap gap-2 cursor-pointer" for="cardRadioOption">
                                                            <div class="d-flex align-items-center">
                                                                <i class="ri-secure-payment-line img fs-22 text-primary me-2"></i>
                                                                <span class="card-name dark-text">
                                                                    Pay with Another Debit / Credit Card
                                                                    <span class="d-block text-muted fs-11">Instant 3D Secure OTP &bull; Visa, MasterCard, RuPay, Amex</span>
                                                                </span>
                                                            </div>
                                                            <div class="d-flex align-items-center gap-1">
                                                                <span class="badge bg-light text-dark border fs-11">Visa</span>
                                                                <span class="badge bg-light text-dark border fs-11">MasterCard</span>
                                                                <span class="badge bg-light text-dark border fs-11">RuPay</span>
                                                                <span class="badge bg-light text-dark border fs-11">Amex</span>
                                                            </div>
                                                        </label>
                                                        <input class="form-check-input" type="radio" name="payment_method" value="card" id="cardRadioOption">
                                                    </div>
                                                </li>
                                            </ul>

                                            <div class="p-2 px-3 bg-light rounded-2 mt-3 border d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="ri-shield-check-fill text-success fs-16"></i>
                                                    <small class="text-muted fs-12">100% Secure &amp; encrypted 3D-Secure payment gateway.</small>
                                                </div>
                                                <span class="badge bg-soft-success text-success fs-10 rounded-pill">PCI-DSS Safe</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $activeGateways = \App\Models\PaymentGateway::where('is_active', true)->orderBy('sort_order')->get();
                                @endphp

                                @if($activeGateways->count() > 0)
                                <!-- 2. Online Payment Gateways (Active in Admin) -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGateways" aria-expanded="false" aria-controls="collapseGateways">
                                            Online Payment Gateways (UPI, NetBanking &amp; Wallets)
                                        </button>
                                    </h2>
                                    <div id="collapseGateways" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <ul class="card-list">
                                                @foreach($activeGateways as $gw)
                                                    @php
                                                        $gwKey = $gw->gateway_key;
                                                        $gwId = 'gateway_' . $gwKey;
                                                        $gwIcon = 'ri-secure-payment-line';
                                                        $gwColor = $gw->badge_color ?? '#fc8019';
                                                        $gwSub = $gw->description;

                                                        if ($gwKey === 'razorpay') {
                                                            $gwIcon = 'ri-flashlight-fill';
                                                            $gwColor = '#072654';
                                                            $gwSub = $gwSub ?: 'UPI, Google Pay, PhonePe, Cards & NetBanking';
                                                        } elseif ($gwKey === 'phonepe') {
                                                            $gwIcon = 'ri-smartphone-line';
                                                            $gwColor = '#5f259f';
                                                            $gwSub = $gwSub ?: 'Direct UPI & PhonePe Wallet';
                                                        } elseif ($gwKey === 'paytm') {
                                                            $gwIcon = 'ri-wallet-3-line';
                                                            $gwColor = '#00b9f5';
                                                            $gwSub = $gwSub ?: 'Paytm Wallet, UPI & NetBanking';
                                                        } elseif ($gwKey === 'payu' || $gwKey === 'payumoney') {
                                                            $gwIcon = 'ri-bank-card-line';
                                                            $gwColor = '#84cc16';
                                                            $gwSub = $gwSub ?: 'PayU UPI, Cards & NetBanking';
                                                        } elseif ($gwKey === 'paypal') {
                                                            $gwIcon = 'ri-paypal-fill';
                                                            $gwColor = '#003087';
                                                            $gwSub = $gwSub ?: 'PayPal Balance, Cards & Credit';
                                                        }
                                                    @endphp
                                                    <li>
                                                        <div class="form-check form-check-reverse">
                                                            <label class="form-check-label d-flex align-items-center" for="{{ $gwId }}">
                                                                <i class="{{ $gwIcon }} img fs-20 me-2" style="color: {{ $gwColor }};"></i>
                                                                <span class="card-name dark-text">
                                                                    {{ $gw->display_name ?: ucfirst($gwKey) }} <span>({{ $gwSub }})</span>
                                                                </span>
                                                            </label>
                                                            <input class="form-check-input" type="radio" name="payment_method" value="{{ $gwKey }}" id="{{ $gwId }}">
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- 3. My Wallet -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            My Wallet
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <ul class="card-list">
                                                <li>
                                                    <div class="form-check form-check-reverse">
                                                        <label class="form-check-label" for="flexRadioDefault1">
                                                            <i class="ri-wallet-line img fs-20 text-success me-2"></i>
                                                            <span class="card-name dark-text">
                                                                FoodExpress Wallet
                                                                @auth
                                                                    <span class="badge bg-success ms-2">{{ $currencySymbol }}{{ number_format(auth()->user()->wallet_balance, 2) }}</span>
                                                                @endauth
                                                            </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio" name="payment_method" value="wallet" id="flexRadioDefault1">
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- 4. Cash on Delivery -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCash" aria-expanded="false" aria-controls="collapseCash">
                                            Cash on Delivery
                                        </button>
                                    </h2>
                                    <div id="collapseCash" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <ul class="card-list">
                                                <li>
                                                    <div class="form-check form-check-reverse">
                                                        <label class="form-check-label" for="cashRadio">
                                                            <i class="ri-money-dollar-circle-line img fs-20 text-warning me-2"></i>
                                                            <span class="card-name dark-text">
                                                                Cash on Delivery
                                                            </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio" name="payment_method" value="cash_on_delivery" id="cashRadio">
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- 5. Delivery Option -->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Delivery Option
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <ul class="card-list">
                                                <li>
                                                    <div class="form-check form-check-reverse">
                                                        <label class="form-check-label" for="standardRadio">
                                                            <i class="ri-bike-line img fs-20 me-2"></i>
                                                            <span class="card-name dark-text">
                                                                Standard Delivery <span> (30-45 min) </span>
                                                            </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio" name="delivery_option" value="standard" id="standardRadio" checked>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="form-check form-check-reverse">
                                                        <label class="form-check-label" for="expressRadio">
                                                            <i class="ri-flashlight-line img fs-20 me-2"></i>
                                                            <span class="card-name dark-text">
                                                                Express Delivery <span> (10-15 min) </span>
                                                            </span>
                                                        </label>
                                                        <input class="form-check-input" type="radio" name="delivery_option" value="express" id="expressRadio">
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cancellation-note">
                                <div class="note-icon">
                                    <i class="ri-arrow-left-right-line"></i>
                                </div>
                                <div class="note-content">
                                    <h5 class="note-title">
                                        <i class="ri-information-line"></i>
                                        Order Cancellation &amp; Refund Note
                                    </h5>
                                    <p>
                                        Before placing the order, please review your order carefully. Once the
                                        order is confirmed and food preparation has started, cancellation and
                                        refund may not be available. Refunds, if applicable, will be processed
                                        according to the cancellation policy.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="order-summery-section sticky-top">
                            <div class="checkout-detail">
                                <div class="cart-address-box">
                                    <div class="add-img">
                                        <img class="img-fluid img"
                                            src="{{ asset('front//assets/images/home.png') }}"
                                            alt="location">
                                    </div>
                                    <div class="add-content">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h5 class="dark-text deliver-place">
                                                Deliver to : <span id="pay-address-label">Address</span>
                                            </h5>
                                            <a href="{{ route('address') }}" class="change-add">Change</a>
                                        </div>
                                        <h6 class="address mt-2 content-color" id="pay-address-text">
                                            No address selected.
                                        </h6>
                                    </div>
                                </div>
                                <h3 class="fw-semibold dark-text checkout-title">
                                    Order Summery
                                </h3>
                                <ul id="checkout-items"></ul>
                                <div class="promo-code position-relative">
                                    <input type="text" id="promo-input" class="form-control code-form-control"
                                        placeholder="Enter promo code" autocomplete="off">
                                    <a href="#" id="apply-promo" class="btn theme-btn apply-btn mt-0">APPLY</a>
                                </div>
                                <div class="promo-feedback mt-2 small"></div>
                                <h5 class="fw-semibold dark-text pt-3 pb-3">Bill Details</h5>
                                <div class="sub-total">
                                    <h6 class="content-color fw-normal">Sub Total</h6>
                                    <h6 class="fw-semibold" id="co-subtotal">{{ $currencySymbol }}0.00</h6>
                                </div>
                                <div class="sub-total">
                                    <h6 class="content-color fw-normal">
                                        Delivery Charge (2 kms)
                                    </h6>
                                    <div class="text-end">
                                        @php
                                            $deliveryCharge = 2 * ($deliveryChargePerKm ?? 20);
                                        @endphp
                                        <h6 class="fw-semibold success-color" id="co-delivery">{{ $currencySymbol }}{{ number_format($deliveryCharge, 2) }}</h6>
                                        <small class="content-color d-block" id="co-delivery-meta"></small>
                                    </div>
                                </div>

                                <div class="sub-total" id="co-promo-wrap" style="display:none">
                                    <h6 class="content-color fw-normal">Promo Discount</h6>
                                    <h6 class="fw-semibold success-color" id="co-promo">-{{ $currencySymbol }}0.00</h6>
                                </div>
                                <div class="sub-total">
                                    <h6 class="content-color fw-normal">Tax ({{ $taxGst ?: $taxPercentage . '%' }})</h6>
                                    <h6 class="fw-semibold" id="co-tax">{{ $currencySymbol }}0.00</h6>
                                </div>
                                <div class="grand-total">
                                    <h6 class="fw-semibold dark-text">Total</h6>
                                    <h6 class="fw-semibold amount" id="co-total">{{ $currencySymbol }}0.00</h6>
                                </div>
                                <button type="button" id="pay-now"
                                    class="btn theme-btn restaurant-btn w-100 rounded-2">
                                    <span class="btn-text">PAY NOW</span>
                                    <span class="btn-spinner d-none text-white">
                                        <span class="spinner-border spinner-border-sm me-2 text-white" role="status" aria-hidden="true"></span>
                                        Processing...
                                    </span>
                                </button>
                                <img class="dots-design" src="{{ asset('front/assets/images/svg/dots-design.svg') }}"
                                    alt="dots">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- account section end -->

@push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        $(function () {
            var C = window.CheckoutCart;
            var SYMBOL = window.CURRENCY_SYMBOL || '$';
            var delivery = localStorage.getItem('checkout_delivery_option') || 'standard';
            // No payment method selected by default, clear any previous selection
            localStorage.removeItem('checkout_payment_method');
            var savedPaymentMethod = '';

            function selectPaymentMethod(methodValue) {
                $('input[name="payment_method"]').prop('checked', false);
                if (!methodValue) return;
                var $radio = $('input[name="payment_method"][value="' + methodValue + '"]');
                if ($radio.length) {
                    $radio.first().prop('checked', true);
                    localStorage.setItem('checkout_payment_method', methodValue);
                }
            }

            selectPaymentMethod(savedPaymentMethod);

            // Sync accordion state on load
            if (savedPaymentMethod) {
                var $activeCollapse = $('input[name="payment_method"]:checked').closest('.accordion-collapse');
                if ($activeCollapse.length) {
                    $('#accordionExample .accordion-collapse').not('#collapseThree').removeClass('show');
                    $('#accordionExample .accordion-button').not('[data-bs-target="#collapseThree"]').addClass('collapsed').attr('aria-expanded', 'false');
                    $activeCollapse.addClass('show');
                    $activeCollapse.prev('.accordion-header').find('.accordion-button').removeClass('collapsed').attr('aria-expanded', 'true');
                }
            } else {
                $('#accordionExample .accordion-collapse').not('#collapseThree').removeClass('show');
                $('#accordionExample .accordion-button').not('[data-bs-target="#collapseThree"]').addClass('collapsed').attr('aria-expanded', 'false');
            }

            $(document).on('change', 'input[name="payment_method"]', function () {
                var val = $(this).val();
                $('input[name="payment_method"]').not(this).prop('checked', false);
                $(this).prop('checked', true);
                localStorage.setItem('checkout_payment_method', val);

                if ($(this).hasClass('saved-card-radio')) {
                    $('.saved-card-tile').css({'border-color': '#dee2e6', 'background': '#ffffff'});
                    $(this).closest('.saved-card-tile').css({'border-color': '#fc8019', 'background': '#fffaf5'});
                } else {
                    $('.saved-card-tile').css({'border-color': '#dee2e6', 'background': '#ffffff'});
                }
            });

            $(document).on('click', '.saved-card-tile', function () {
                $('.saved-card-tile').css({'border-color': '#dee2e6', 'background': '#ffffff'});
                $(this).css({'border-color': '#fc8019', 'background': '#fffaf5'});
                $(this).find('input[type="radio"]').prop('checked', true).trigger('change');
            });

            // When user expands a payment accordion, we intentionally do NOT auto-select
            // the radio button anymore. The user must manually choose.

            $('input[name="delivery_option"][value="' + delivery + '"]').prop('checked', true);
            $('input[name="delivery_option"]').on('change', function () {
                localStorage.setItem('checkout_delivery_option', $(this).val());
                if (window.CheckoutCart) window.CheckoutCart.render();
                applyDeliveryCharge();
            });

            function getCartRestaurantId() {
                for (var i = 0; i < localStorage.length; i++) {
                    var k = localStorage.key(i);
                    if (k && k.indexOf('food_cart_') === 0) {
                        return k.replace('food_cart_', '');
                    }
                }
                return null;
            }

            function haversineKm(lat1, lng1, lat2, lng2) {
                var R = 6371;
                var toRad = Math.PI / 180;
                var dLat = (lat2 - lat1) * toRad;
                var dLng = (lng2 - lng1) * toRad;
                var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * toRad) * Math.cos(lat2 * toRad) *
                    Math.sin(dLng / 2) * Math.sin(dLng / 2);
                return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            }

            function applyDeliveryCharge() {
                var addressId = localStorage.getItem('checkout_address_id');
                var restaurantId = getCartRestaurantId();

                function setDelivery(val, meta) {
                    window.DELIVERY_CHARGE = val;
                    if (window.CheckoutCart) window.CheckoutCart.render();
                    if (meta) {
                        $('#co-delivery-meta').text(meta);
                    } else {
                        $('#co-delivery-meta').empty();
                    }
                }

                function setUnavailable(msg) {
                    window.DELIVERY_CHARGE = 0;
                    if (window.CheckoutCart) window.CheckoutCart.render();
                    $('#co-delivery-meta').text(msg).addClass('text-danger');
                    showError(msg);
                }

                if (!addressId || !restaurantId) {
                    setDelivery((parseFloat(window.DELIVERY_CHARGE_PER_KM) || 20) * 2, '');
                    return;
                }

                $.getJSON('{{ url('api/addresses') }}/' + addressId, function (addr) {
                    if (!addr || !addr.latitude || !addr.longitude) {
                        setDelivery((parseFloat(window.DELIVERY_CHARGE_PER_KM) || 20) * 2, '');
                        return;
                    }

                    $.getJSON('{{ url('api/restaurants') }}/' + restaurantId, function (rest) {
                        if (!rest || !rest.latitude || !rest.longitude) {
                            setDelivery((parseFloat(window.DELIVERY_CHARGE_PER_KM) || 20) * 2, '');
                            return;
                        }

                        var dist = haversineKm(
                            parseFloat(addr.latitude),
                            parseFloat(addr.longitude),
                            parseFloat(rest.latitude),
                            parseFloat(rest.longitude)
                        );

                        var radius = parseFloat(rest.delivery_radius) || 0;
                        var perKm = parseFloat(rest.delivery_charge_per_km) || parseFloat(window.DELIVERY_CHARGE_PER_KM) || 20;

                        if (radius > 0 && dist > radius) {
                            setUnavailable('Delivery not available — your address is ' + dist.toFixed(1) + ' km away (max ' + radius.toFixed(1) + ' km).');
                            return;
                        }

                        setDelivery(dist * perKm, dist.toFixed(1) + ' km x ' + SYMBOL + perKm.toFixed(2) + '/km');
                    }).fail(function () {
                        setDelivery((parseFloat(window.DELIVERY_CHARGE_PER_KM) || 20) * 2, '');
                    });
                }).fail(function () {
                    setDelivery((parseFloat(window.DELIVERY_CHARGE_PER_KM) || 20) * 2, '');
                });
            }

            function sendPlaceOrder(extraData) {
                var $btn = $('#pay-now');
                function resetBtn() {
                    $btn.prop('disabled', false);
                    $btn.find('.btn-spinner').addClass('d-none');
                    $btn.find('.btn-text').removeClass('d-none');
                }

                var addressId = localStorage.getItem('checkout_address_id');
                if (!addressId) {
                    showError('Please select a delivery address first.');
                    resetBtn();
                    return;
                }
                if (!C) {
                    showError('Cart is unavailable. Please try again.');
                    resetBtn();
                    return;
                }

                var items = C.getItems().map(function (it) {
                    return { id: it.id, name: it.name, price: it.price, qty: it.qty };
                });

                if (items.length === 0) {
                    showError('Your cart is empty.');
                    resetBtn();
                    return;
                }

                var selectedPaymentMethod = $('input[name="payment_method"]:checked').val();
                var selectedDeliveryOption = $('input[name="delivery_option"]:checked').val() || localStorage.getItem('checkout_delivery_option') || 'standard';

                var postData = $.extend({
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    address_id: addressId,
                    delivery_option: selectedDeliveryOption,
                    payment_method: selectedPaymentMethod,
                    promo_code: localStorage.getItem('checkout_promo_code') || '',
                    items: items
                }, extraData || {});

                $btn.prop('disabled', true);
                $btn.find('.btn-text').addClass('d-none');
                $btn.find('.btn-spinner').removeClass('d-none');

                $.ajax({
                    url: '{{ route('place.order') }}',
                    method: 'POST',
                    data: postData,
                    success: function (res) {
                        if (res.success) {
                            if (C.clearCart) C.clearCart();
                            clearPromo();
                            window.location.href = res.redirect;
                        } else {
                            resetBtn();
                            showError('Could not place order. Please try again.');
                        }
                    },
                    error: function (xhr) {
                        resetBtn();
                        var msg = 'Could not place order.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        showError(msg);
                    }
                });
            }

            $('#pay-now').on('click', function (e) {
                e.preventDefault();
                var $btn = $(this);
                function resetBtn() {
                    $btn.prop('disabled', false);
                    $btn.find('.btn-spinner').addClass('d-none');
                    $btn.find('.btn-text').removeClass('d-none');
                }

                $btn.prop('disabled', true);
                $btn.find('.btn-text').addClass('d-none');
                $btn.find('.btn-spinner').removeClass('d-none');

                var addressId = localStorage.getItem('checkout_address_id');
                if (!addressId) {
                    showError('Please select a delivery address first.');
                    resetBtn();
                    return;
                }
                if (!C) {
                    showError('Cart is unavailable. Please try again.');
                    resetBtn();
                    return;
                }

                var items = C.getItems();
                if (!items || items.length === 0) {
                    showError('Your cart is empty.');
                    resetBtn();
                    return;
                }

                var selectedPaymentMethod = $('input[name="payment_method"]:checked').val();
                if (!selectedPaymentMethod) {
                    showError('Please choose a payment method.');
                    resetBtn();
                    return;
                }
                var grandTotal = parseFloat($('#co-total').text().replace(/[^0-9.]/g, '')) || (C.getTotals ? C.getTotals().total : 0);

                // 1. Wallet Payment
                if (selectedPaymentMethod === 'wallet') {
                    var userWalletBalance = {{ (float) (auth()->user()?->wallet_balance ?? 0) }};
                    if (userWalletBalance < grandTotal) {
                        showError('Insufficient wallet balance ({{ $currencySymbol }}' + userWalletBalance.toFixed(2) + '). Order total is {{ $currencySymbol }}' + grandTotal.toFixed(2) + '. Please top up your wallet or select another payment method.');
                        return;
                    }
                    sendPlaceOrder({ payment_method: 'wallet' });
                    return;
                }

                // 2. Card Payment (Credit / Debit Card)
                if (selectedPaymentMethod === 'card') {
                    if (typeof Razorpay === 'undefined') {
                        showError('Card payment gateway is initializing. Please try again.');
                        return;
                    }

                    var $checkedCardRadio = $('input[name="payment_method"]:checked');
                    var cardPrefillNum = $checkedCardRadio.attr('data-card-number') || '';
                    var cardPrefillName = $checkedCardRadio.attr('data-card-holder') || "{{ auth()->user()?->name }}";
                    var cardPrefillExp = $checkedCardRadio.attr('data-card-exp') || '';

                    var cardPrefillData = {
                        name: cardPrefillName || "{{ auth()->user()?->name }}",
                        email: "{{ auth()->user()?->email }}",
                        contact: "{{ auth()->user()?->phone }}"
                    };

                    // Auto-fill saved card details directly into payment gateway
                    if (cardPrefillNum) {
                        cardPrefillData['card[number]'] = cardPrefillNum;
                        cardPrefillData['card[name]'] = cardPrefillName;
                        if (cardPrefillExp) {
                            cardPrefillData['card[expiry]'] = cardPrefillExp;
                        }
                    }

                    var cardOptions = {
                        key: "{{ $razorpayKey ?? 'rzp_test_TWIAoYszpcVthe' }}",
                        amount: Math.round(grandTotal * 100),
                        currency: "INR",
                        name: "{{ $companyName ?? 'FoodExpress' }}",
                        description: "Credit / Debit Card Payment",
                        image: "{{ asset($companyLogo ?? 'front/assets/images/logo/logo.png') }}",
                        config: {
                            display: {
                                blocks: {
                                    card: {
                                        name: "Pay via Debit / Credit Card",
                                        instruments: [
                                            {
                                                method: "card"
                                            }
                                        ]
                                    }
                                },
                                sequence: ["block.card"],
                                preferences: {
                                    show_default_blocks: true
                                }
                            }
                        },
                        prefill: cardPrefillData,
                        handler: function (response) {
                            sendPlaceOrder({
                                payment_method: 'card',
                                payment_id: response.razorpay_payment_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id || '',
                                razorpay_signature: response.razorpay_signature || ''
                            });
                        },
                        theme: {
                            color: "#fc8019"
                        },
                        modal: {
                            ondismiss: function() {
                                resetBtn();
                            }
                        }
                    };

                    var cardRzp = new Razorpay(cardOptions);
                    cardRzp.on('payment.failed', function (resp) {
                        resetBtn();
                        showError('Card payment failed: ' + (resp.error.description || 'Transaction declined.'));
                    });
                    cardRzp.open();
                    return;
                }

                // 3. Razorpay Gateway Payment
                if (selectedPaymentMethod === 'razorpay') {
                    if (typeof Razorpay === 'undefined') {
                        showError('Razorpay gateway is initializing. Please try again.');
                        return;
                    }

                    var rzpOptions = {
                        key: "{{ $razorpayKey ?? 'rzp_test_TWIAoYszpcVthe' }}",
                        amount: Math.round(grandTotal * 100),
                        currency: "INR",
                        name: "{{ $companyName ?? 'FoodExpress' }}",
                        description: "Food Order Payment",
                        image: "{{ asset($companyLogo ?? 'front/assets/images/logo/logo.png') }}",
                        handler: function (response) {
                            sendPlaceOrder({
                                payment_method: 'razorpay',
                                payment_id: response.razorpay_payment_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id || '',
                                razorpay_signature: response.razorpay_signature || ''
                            });
                        },
                        prefill: {
                            name: "{{ auth()->user()?->name }}",
                            email: "{{ auth()->user()?->email }}",
                            contact: "{{ auth()->user()?->phone }}"
                        },
                        theme: {
                            color: "#fc8019"
                        },
                        modal: {
                            ondismiss: function() {
                                resetBtn();
                            }
                        }
                    };

                    var rzp = new Razorpay(rzpOptions);
                    rzp.on('payment.failed', function (resp) {
                        resetBtn();
                        showError('Payment failed: ' + (resp.error.description || 'Transaction declined.'));
                    });
                    rzp.open();
                    return;
                }

                // 4. COD, or other online payment gateways
                sendPlaceOrder({ payment_method: selectedPaymentMethod });
            });

            function showError(msg) {
                var $e = $('#payment-error');
                $e.removeClass('d-none').text(msg);
                $('html, body').animate({ scrollTop: $e.offset().top - 80 }, 400);
            }

            var csrf = $('meta[name="csrf-token"]').attr('content');

            function clearPromo() {
                localStorage.removeItem('checkout_promo_code');
                localStorage.removeItem('checkout_promo_code_id');
                localStorage.removeItem('checkout_promo_discount');
            }

            // Restore previously applied promo on page load
            var savedPromo = localStorage.getItem('checkout_promo_code');
            if (savedPromo) {
                $('#promo-input').val(savedPromo);
                if (C) C.render();
            }

            $('#apply-promo').on('click', function (e) {
                e.preventDefault();
                var code = $('#promo-input').val().trim();
                var $feedback = $('.promo-feedback');
                if (!code) {
                    $feedback.removeClass('text-success').addClass('text-danger').text('Please enter a promo code.');
                    return;
                }
                var subtotal = C ? C.getTotals().subtotal : 0;
                var $btn = $(this).prop('disabled', true).text('APPLYING...');

                $.ajax({
                    url: '{{ route('promo.validate') }}',
                    method: 'POST',
                    data: { _token: csrf, code: code, subtotal: subtotal },
                    success: function (res) {
                        $btn.prop('disabled', false).text('APPLY');
                        if (res.success) {
                            localStorage.setItem('checkout_promo_code', res.code);
                            localStorage.setItem('checkout_promo_code_id', res.promo_code_id);
                            localStorage.setItem('checkout_promo_discount', res.discount);
                            $('#promo-input').val(res.code);
                            $feedback.removeClass('text-danger').addClass('text-success').text(res.message);
                            if (C) C.render();
                            applyDeliveryCharge();
                        } else {
                            clearPromo();
                            if (C) C.render();
                            applyDeliveryCharge();
                            $feedback.removeClass('text-success').addClass('text-danger').text(res.message);
                        }
                    },
                    error: function (xhr) {
                        $btn.prop('disabled', false).text('APPLY');
                        clearPromo();
                        if (C) C.render();
                        applyDeliveryCharge();
                        var msg = 'Could not validate promo code.';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        $feedback.removeClass('text-success').addClass('text-danger').text(msg);
                    }
                });
            });

            var addressId = localStorage.getItem('checkout_address_id');
            if (addressId) {
                $.get('{{ url('/address-api') }}/' + addressId, function (addr) {
                    $('#pay-address-label').text(addr.label ? addr.label : 'Home');
                    $('#pay-address-text').text(
                        addr.address + ', ' + addr.city + ', ' + addr.country + ' ' + addr.zip
                    );
                }).fail(function () {
                    $('#pay-address-text').text('No address selected.');
                });
            }

            applyDeliveryCharge();
        });
    </script>
@endpush
@endsection

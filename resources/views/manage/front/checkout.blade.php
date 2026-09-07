@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Checkout</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="index.html"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!--  account section starts -->
    <section class="account-section section-b-space pt-0">
        <div class="container">
            <div class="layout-sec">
                <div class="row g-lg-4 g-4">
                    <div class="col-lg-8">
                        @include('manage.front.partials.checkout-stepper', ['step' => 'account'])
                        <div class="account-part">
                            @auth
                                <img class="img-fluid account-img" src="front/assets/images/logged-in.svg" alt="account" style="height: 300px;">
                                <div class="title mb-0">
                                    <div class="loader-line"></div>
                                    <h3>Account</h3>
                                    <div class="text-start">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <img class="img-fluid rounded-circle"
                                                src="{{ auth()->user()->profile_image ? asset(auth()->user()->profile_image) : asset('front/assets/images/icons/p5.png') }}"
                                                alt="profile"
                                                style="width:48px;height:48px;object-fit:cover;">
                                            <div>
                                                <h6 class="fw-semibold mb-0">{{ auth()->user()->name }}</h6>
                                                <small class="content-color">{{ auth()->user()->email }}</small>
                                            </div>
                                        </div>
                                        @if (auth()->user()->phone)
                                            <p class="content-color mb-1">
                                                <i class="ri-phone-line me-1"></i> {{ auth()->user()->phone }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <img class="img-fluid account-img" src="front/assets/images/account.svg" alt="account">
                                <div class="title mb-0">
                                    <div class="loader-line"></div>
                                    <h3>Account</h3>
                                    <p>
                                        To place your order now, log in to in your existing account
                                        or sign up
                                    </p>
                                    <div class="account-btn d-flex justify-content-center gap-2">
                                        <a href="{{ route('login') }}" class="btn theme-outline mt-0">Login</a>
                                        <a href="{{ route('register') }}" class="btn theme-outline mt-0">SIGN UP</a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="order-summery-section sticky-top">
                            <div class="checkout-detail">
                                <ul id="checkout-items"></ul>
                                <h5 class="bill-details-title fw-semibold dark-text">
                                    Bill Details
                                </h5>
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
                                <div class="sub-total">
                                    <h6 class="content-color fw-normal">Tax ({{ $taxGst ?: $taxPercentage . '%' }})</h6>
                                    <h6 class="fw-semibold" id="co-tax">{{ $currencySymbol }}0.00</h6>
                                </div>
                                <div class="grand-total">
                                    <h6 class="fw-semibold dark-text">To Pay</h6>
                                    <h6 class="fw-semibold amount" id="co-total">{{ $currencySymbol }}0.00</h6>
                                </div>
                                <a href="{{ route('address') }}"
                                    class="btn theme-btn restaurant-btn w-100 rounded-2">CHECKOUT</a>
                                <img class="dots-design" src="{{ asset('front/assets/images/svg/dots-design.svg') }}"
                                    alt="dots">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- account section end --

@endsection
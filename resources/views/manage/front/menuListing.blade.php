@extends('layouts.front.main')

@section('content')

    <!-- banner section starts -->
    <section class="product-banner-section">
        <div class="container">
            <div class="restaurant-box">
                <div class="restaurant-image">
                    <img class="img-fluid img"
                        src="{{ $restaurant && $restaurant->logo ? asset($restaurant->logo) : ($restaurant && $restaurant->brand && $restaurant->brand->logo ? asset($restaurant->brand->logo) : asset('front/assets/images/icons/brand13.png')) }}"
                        alt="brand">
                </div>
                <div class="restaurant-details">
                    @php
                        $now = \Carbon\Carbon::now();
                        $isOpen = $restaurant && $restaurant->opening_time && $restaurant->closing_time
                            ? $now->between($restaurant->opening_time, $restaurant->closing_time)
                            : false;
                        $openFmt = $restaurant && $restaurant->opening_time ? $restaurant->opening_time->format('h:i A') : '09:00 AM';
                        $closeFmt = $restaurant && $restaurant->closing_time ? $restaurant->closing_time->format('h:i A') : '11:00 PM';
                        $costForTwo = $restaurant && $restaurant->minimum_order_amount ? $restaurant->minimum_order_amount * 2 : null;
                        $mapsUrl = $restaurant && $restaurant->latitude && $restaurant->longitude
                            ? 'https://www.google.com/maps/dir/?api=1&destination=' . $restaurant->latitude . ',' . $restaurant->longitude
                            : '#!';
                        $phone = $restaurant && $restaurant->mobile ? $restaurant->mobile : '';
                    @endphp
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <h2 class="restaurant-name mb-0">
                                    {{ $restaurant ? $restaurant->restaurant_name : 'Select a Restaurant' }}
                                </h2>
                                @if ($restaurant && $restaurant->brand)
                                    <span class="badge bg-white text-dark border shadow-sm px-2 py-1 rounded-pill d-inline-flex align-items-center gap-1" style="font-size: 13px;">
                                        @if ($restaurant->brand->logo)
                                            <img src="{{ asset($restaurant->brand->logo) }}" alt="{{ $restaurant->brand->name }}" class="rounded-circle border" style="width: 18px; height: 18px; object-fit: cover;">
                                        @else
                                            <i class="ri-award-line text-warning"></i>
                                        @endif
                                        <span>Brand: <strong>{{ $restaurant->brand->name }}</strong></span>
                                    </span>
                                @endif
                            </div>
                            <h4 class="restaurant-place">
                                {{ $restaurant && $restaurant->city ? $restaurant->city->name : '' }}
                                @if ($restaurant && $restaurant->address)
                                    , {{ $restaurant->address }}
                                @endif
                            </h4>
                        </div>
                        <div class="restaurant-description">
                            <div class="categories-icon">
                                <a href="#!" id="liveToastBtn">
                                    <i class="ri-share-line icon text-white"></i>
                                </a>
                                <a href="#!" class="like-btn animate inactive" data-restaurant-id="{{ $restaurant ? $restaurant->id : '' }}">
                                    <i class="ri-heart-3-fill fill-icon"></i>
                                    <i class="ri-heart-3-line text-white outline-icon"></i>
                                    <div class="effect-group">
                                        <span class="effect"></span>
                                        <span class="effect"></span>
                                        <span class="effect"></span>
                                        <span class="effect"></span>
                                        <span class="effect"></span>
                                    </div>
                                </a>
                            </div>
                            <div class="distance d-flex align-items-center">
                                <h4 class="text-white shop-time">4.0km</h4>
                                @php
                                    $reviewCount = $restaurant && $restaurant->reviews ? $restaurant->reviews->count() : 0;
                                    
                                    $avgRating = 0;
                                    if ($reviewCount > 0) {
                                        $avgRating = $restaurant->reviews->avg(function($r) {
                                            return $r->restaurant_rating ?: ($r->food_rating ?: 0);
                                        });
                                    }
                                    
                                    $avgRatingDisplay = $avgRating > 0 ? number_format($avgRating, 1) : 'New';
                                    
                                    if ($reviewCount >= 1000) {
                                        $reviewCountDisplay = floor($reviewCount / 1000) . 'k+ Reviews';
                                    } else {
                                        $reviewCountDisplay = $reviewCount . ' Review' . ($reviewCount !== 1 ? 's' : '');
                                    }
                                @endphp
                                <h4 class="rating-star">
                                    <span class="star"><i class="ri-star-s-fill"></i></span> {{ $avgRatingDisplay }}
                                    ({{ $reviewCountDisplay }})
                                </h4>
                            </div>
                        </div>
                    </div>

                    <!-- restaurant meta (open status, cost, call) -->
                    <div class="restaurant-meta">
                        <div class="meta-info-row">
                            <span class="meta-badge {{ $isOpen ? 'open' : 'closed' }}">
                                <i class="ri-time-line"></i>
                                {{ $isOpen ? 'Open now' : 'Closed' }}
                                <span class="meta-hours">{{ $openFmt }} – {{ $closeFmt }} (Today)</span>
                            </span>
                            @if ($costForTwo)
                                <span class="meta-cost">
                                    <i class="ri-money-rupee-circle-line"></i>
                                    {{ $currencySymbol }}{{ number_format($costForTwo) }} for two
                                </span>
                            @endif
                            @if ($phone)
                                <a href="tel:{{ $phone }}" class="meta-call">
                                    <i class="ri-phone-line"></i> {{ $phone }}
                                </a>
                            @endif
                        </div>

                        <!-- quick actions -->
                        <div class="meta-actions">
                            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="meta-action">
                                <i class="ri-direction-line"></i> Direction
                            </a>
                            <a href="#!" class="meta-action js-share">
                                <i class="ri-share-line"></i> Share
                            </a>
                            <a href="#review" class="meta-action" data-tab="review">
                                <i class="ri-chat-1-line"></i> Reviews
                            </a>
                            @if(!$restaurant || $restaurant->restaurant_type !== 'cloud_kitchen')
                            <a href="#book" class="meta-action" data-tab="book">
                                <i class="ri-calendar-check-line"></i> Book a table
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner section end -->

    <!-- tab section starts -->
    <section class="tab-details-section section-b-space">
        <div class="container">
            <div class="category-detail-tab">
                
                {{-- Full-width Navigation Tabs --}}
                <div class="menu-button d-inline-block d-lg-none mb-2">
                    <a href="#!"><i class="ri-book-open-line"></i> Menu</a>
                </div>
                <ul class="nav nav-tabs tab-style1 mb-4" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="order-tab" data-bs-toggle="tab"
                            data-bs-target="#online" type="button" role="tab">
                            Order Online
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="overview-tab" data-bs-toggle="tab"
                            data-bs-target="#overview" type="button" role="tab">
                            Overview
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="items-tab" data-bs-toggle="tab" data-bs-target="#photo"
                            type="button" role="tab">
                            Photos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#review"
                            type="button" role="tab">
                            Reviews
                        </button>
                    </li>
                    @if(!$restaurant || $restaurant->restaurant_type !== 'cloud_kitchen')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="book-tab" data-bs-toggle="tab" data-bs-target="#book"
                            type="button" role="tab">
                            Book a table
                        </button>
                    </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="menu-tab" data-bs-toggle="tab" data-bs-target="#menu-list"
                            type="button" role="tab">
                            Menu
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    
                    {{-- 1. ORDER ONLINE TAB --}}
                    <div class="tab-pane fade show active" id="online" role="tabpanel" tabindex="0">
                        <div class="row g-4">
                            <div class="col-lg-9">
                                <div class="row g-lg-3 g-2">
                                    <div class="col-lg-4">
                                        <div class="product-sidebar sticky-top">
                                            <div class="sidebar-search">
                                                <input type="text" placeholder="Search Dishes..">
                                                <i class="ri-search-line"></i>
                                            </div>
                                            <nav id="navbar" class="product-items pb-0">
                                                <ul class="nav nav-pills">
                                                    @forelse (optional($restaurant)->categories ?? collect() as $cat)
                                                        @foreach($cat->foods as $food)
                                                            <li>
                                                                <a class="nav-link" href="#food-{{ $food->id }}">{{ $food->name }}</a>
                                                            </li>
                                                            @if($food->variants && $food->variants->count() > 0)
                                                            <li>
                                                                <nav class="nav nav-pills sub-nav-pills">
                                                                    <ul>
                                                                        @foreach($food->variants as $variant)
                                                                            <li>
                                                                                <a class="nav-link" href="#variant-{{ $variant->id }}">{{ $variant->variant_name }}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </nav>
                                                            </li>
                                                            @endif
                                                        @endforeach
                                                    @empty
                                                        <li><a class="nav-link" href="#!">No items</a></li>
                                                    @endforelse
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="product-box-section section-b-space">
                                            <div data-bs-spy="scroll" data-bs-target="#navbar"
                                                data-bs-smooth-scroll="true" class="scrollspy-example-2" tabindex="0">
                                                <div class="product-details-box-list">
                                                    @forelse (optional($restaurant)->categories ?? collect() as $cat)
                                                        @foreach ($cat->foods as $food)
                                                            <div id="food-{{ $food->id }}">
                                                                @include('manage.front.partials.menu-food', ['food' => $food])
                                                            </div>
                                                        @endforeach
                                                    @empty
                                                        <p class="content-color">No menu available for this restaurant.</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 product-details-content">
                                <div class="order-summery-section sticky-top">
                                    <div class="checkout-detail">
                                        <h3 class="fw-semibold dark-text checkout-title mb-3">Cart Items</h3>
                                        <ul id="cart-items" class="cart-items-list p-0 mb-3" style="list-style: none;">
                                            <li>
                                                <div class="text-center py-4">
                                                    <p class="content-color mb-0">Your cart is empty.</p>
                                                </div>
                                            </li>
                                        </ul>
                                        <h5 class="bill-details-title fw-semibold dark-text">Bill Details</h5>
                                        <div class="sub-total">
                                            <h6 class="content-color fw-normal">Sub Total</h6>
                                            <h6 class="fw-semibold" id="cart-subtotal">{{ $currencySymbol }}0.00</h6>
                                        </div>
                                        <div class="sub-total">
                                            <h6 class="content-color fw-normal">Delivery Charge (2 kms)</h6>
                                            @php
                                                $perKm = $restaurant->delivery_charge_per_km ?? $deliveryChargePerKm ?? 20;
                                                $deliveryCharge = 2 * $perKm;
                                            @endphp
                                            <h6 class="fw-semibold" id="cart-delivery">{{ $currencySymbol }}{{ number_format($deliveryCharge, 2) }}</h6>
                                        </div>
                                        <div class="sub-total">
                                            <h6 class="content-color fw-normal">Tax ({{ $taxPercentage ?? 0 }}%)</h6>
                                            <h6 class="fw-semibold" id="cart-tax">{{ $currencySymbol }}0.00</h6>
                                        </div>

                                        <div class="grand-total">
                                            <h6 class="fw-semibold dark-text">To Pay</h6>
                                            <h6 class="fw-semibold amount" id="cart-total">{{ $currencySymbol }}0.00</h6>
                                        </div>
                                        <button type="button" id="proceed-to-checkout"
                                            class="btn theme-btn restaurant-btn w-100 rounded-2">
                                            <span class="btn-text">Proceed to payment</span>
                                            <span class="btn-spinner d-none text-white">
                                                <span class="spinner-border spinner-border-sm me-2 text-white" role="status" aria-hidden="true"></span>
                                                Processing...
                                            </span>
                                        </button>
                                        <img class="dots-design"
                                            src="{{ asset('front/assets/images/svg/dots-design.svg') }}"
                                            alt="dots">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. OVERVIEW TAB --}}
                    <div class="tab-pane fade" id="overview" role="tabpanel" tabindex="0">
                        <div class="row g-4">
                            {{-- LEFT COLUMN: DINING OFFERS & ABOUT --}}
                            <div class="col-lg-8 col-12">
                                @php
                                    $liveOffers = $restaurant ? $restaurant->diningOffers : collect();
                                @endphp

                                {{-- Dining Offers (Only if restaurant has active offers in table) --}}
                                @if ($liveOffers->isNotEmpty())
                                    <div class="dining-offers-main-card mb-4">
                                        <div class="dining-offers-header">
                                            <h3 class="dining-offers-head-title">Dining Offers</h3>
                                            <p class="dining-offers-sub">Tap on any offer to know more</p>
                                        </div>

                                        <div class="row g-3">
                                            @foreach ($liveOffers as $idx => $offer)
                                                @php
                                                    $discountDisplay = $offer->discount_type === 'percentage'
                                                        ? 'Flat ' . rtrim(rtrim(number_format((float)$offer->discount_value, 2), '0'), '.') . '% OFF'
                                                        : 'FLAT ' . $currencySymbol . number_format((float)$offer->discount_value, 0) . ' OFF';
                                                    
                                                    $tag = $offer->coupon_code ?: ($offer->discount_type === 'percentage' ? 'PRE-BOOK OFFER' : 'EXCLUSIVE OFFER');
                                                    $cardClass = $loop->first ? 'is-primary active' : 'is-secondary';

                                                    $validityText = 'Valid today';
                                                    if ($offer->start_date && $offer->end_date) {
                                                        $validityText = 'Valid: ' . $offer->start_date->format('d M') . ' – ' . $offer->end_date->format('d M Y');
                                                    } elseif ($offer->end_date) {
                                                        $validityText = 'Valid till ' . $offer->end_date->format('d M Y');
                                                    }
                                                @endphp
                                                <div class="col-md-6 col-12">
                                                    <div class="dining-offer-box {{ $cardClass }}"
                                                         data-offer-id="{{ $offer->id }}"
                                                         data-discount="{{ $discountDisplay }}"
                                                         data-title="{{ $offer->title }}">
                                                        <div class="offer-tag-badge">{{ $tag }}</div>
                                                        <div class="offer-discount-title">{{ $discountDisplay }}</div>
                                                        <div class="offer-desc-text">
                                                            <strong>{{ $offer->title }}</strong><br>
                                                            <span class="sub-muted">{{ $validityText }}</span>
                                                            @if ($offer->min_bill_amount)
                                                                <br><span class="sub-muted">Min bill: {{ $currencySymbol }}{{ number_format($offer->min_bill_amount, 0) }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="offer-bg-icon"><i class="ri-coupon-3-line"></i></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- About Restaurant Card --}}
                                <div class="dining-offers-main-card">
                                    <h4 class="fw-bold mb-2">About {{ $restaurant ? $restaurant->restaurant_name : '' }}</h4>
                                    <p class="text-secondary lh-lg mb-0">{{ $restaurant && $restaurant->description ? $restaurant->description : 'No description available for this restaurant.' }}</p>
                                    
                                    @if ($restaurant && $restaurant->cuisines && $restaurant->cuisines->isNotEmpty())
                                        <div class="mt-3 pt-3 border-top">
                                            <h6 class="fw-bold mb-2 text-dark">Cuisines</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($restaurant->cuisines as $cuisine)
                                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-medium">
                                                        {{ $cuisine->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- RIGHT COLUMN: TABLE RESERVATION & DIRECTION --}}
                            <div class="col-lg-4 col-12">
                                <div class="d-flex flex-column gap-3">
                                    
                                    {{-- 1. Table reservation widget --}}
                                    <div class="table-reservation-widget-card">
                                        <h4 class="widget-heading mb-2">Table reservation</h4>
                                        @if($restaurant && $restaurant->restaurant_type === 'cloud_kitchen')
                                            <div class="p-3 text-center bg-light rounded border border-warning" style="color: #856404; background-color: #fff3cd !important;">
                                                <i class="ri-information-line fs-5 d-block mb-1"></i>
                                                Table reservation is not available for Cloud Kitchens.
                                            </div>
                                        @else
                                        
                                        <div class="reservation-offer-badge mb-3">
                                            @if($restaurant && $restaurant->diningOffers->isNotEmpty())
                                                <i class="ri-discount-percent-fill icon"></i>
                                                <span id="reservationPillText">
                                                    @php
                                                        $firstOffer = $restaurant->diningOffers->first();
                                                        $firstDisc = $firstOffer->discount_type === 'percentage'
                                                            ? 'Flat ' . rtrim(rtrim(number_format((float)$firstOffer->discount_value, 2), '0'), '.') . '% OFF'
                                                            : 'FLAT ' . $currencySymbol . number_format((float)$firstOffer->discount_value, 0) . ' OFF';
                                                        $extraCount = $restaurant->diningOffers->count() - 1;
                                                    @endphp
                                                    {{ $firstDisc }}@if($extraCount > 0) + {{ $extraCount }} more {{ $extraCount == 1 ? 'offer' : 'offers' }}@endif
                                                </span>
                                            @else
                                                <i class="ri-calendar-check-fill icon"></i>
                                                <span id="reservationPillText">Instant Table Reservation</span>
                                            @endif
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <div class="quick-select-wrap">
                                                    <select class="form-select quick-select" id="quickReservationDate">
                                                        <option value="{{ now()->toDateString() }}">Today</option>
                                                        <option value="{{ now()->addDay()->toDateString() }}">Tomorrow</option>
                                                        <option value="{{ now()->addDays(2)->toDateString() }}">{{ now()->addDays(2)->format('d M') }}</option>
                                                        <option value="{{ now()->addDays(3)->toDateString() }}">{{ now()->addDays(3)->format('d M') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="quick-select-wrap">
                                                    <select class="form-select quick-select" id="quickReservationGuests">
                                                        <option value="1">1 guest</option>
                                                        <option value="2" selected>2 guests</option>
                                                        <option value="3">3 guests</option>
                                                        <option value="4">4 guests</option>
                                                        <option value="5">5 guests</option>
                                                        <option value="6">6+ guests</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-zomato-red w-100" id="quickBookTableBtn">
                                            Book a table
                                        </button>
                                        @endif
                                    </div>

                                    {{-- 2. Direction widget --}}
                                    <div class="direction-widget-card">
                                        <h4 class="widget-heading mb-2">Direction</h4>
                                        <p class="direction-address-text text-secondary mb-3" id="restaurantAddressText">
                                            {{ $restaurant->address ?: 'Kaiserbagh Officer\'s Colony, Rani Lakshmibai Marg, Hazratganj, Lucknow' }}
                                            @if($restaurant->city && !str_contains($restaurant->address, $restaurant->city->name)), {{ $restaurant->city->name }}@endif
                                        </p>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-outline-secondary btn-sm direction-btn" id="copyAddressBtn">
                                                <i class="ri-file-copy-line me-1"></i> Copy
                                            </button>
                                            <a href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger btn-sm direction-btn direction-red">
                                                <i class="ri-direction-fill me-1"></i> Direction
                                            </a>
                                        </div>
                                    </div>

                                    {{-- 3. Opening Hours & Timings --}}
                                    @if ($restaurant)
                                        <div class="direction-widget-card">
                                            <h5 class="widget-heading fs-6 mb-2"><i class="ri-time-line text-theme me-1"></i> Timings</h5>
                                            <p class="small text-muted mb-0">
                                                {{ $restaurant->opening_time ? $restaurant->opening_time->format('h:i A') : '09:00 AM' }} –
                                                {{ $restaurant->closing_time ? $restaurant->closing_time->format('h:i A') : '11:00 PM' }}
                                                @if ($isOpen)
                                                    <span class="badge bg-success-subtle text-success ms-2">Open Now</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning ms-2">Closed</span>
                                                @endif
                                            </p>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. PHOTOS TAB --}}
                    <div class="tab-pane fade" id="photo" role="tabpanel" tabindex="0">
                        <div class="row g-3">
                            @if ($restaurant && $restaurant->banner)
                                <div class="col-lg-6">
                                    <img class="img-fluid rounded"
                                        src="{{ asset($restaurant->banner) }}" alt="banner">
                                </div>
                            @endif
                            @foreach (optional($restaurant)->categories ?? collect() as $cat)
                                @foreach ($cat->foods as $food)
                                    @if ($food->image)
                                        <div class="col-lg-3 col-6">
                                            <img class="img-fluid rounded"
                                                src="{{ asset($food->image) }}" alt="{{ $food->name }}">
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                            @if (!optional($restaurant)->banner && optional($restaurant)->categories->isEmpty())
                                <div class="col-12">
                                    <p class="content-color">No photos available.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 4. REVIEWS TAB --}}
                    <div class="tab-pane fade" id="review" role="tabpanel" tabindex="0" aria-labelledby="activity-tab">
                        <div class="review-section">
                            <ul class="review-box-list">
                                @if ($restaurant && $restaurant->reviews && $restaurant->reviews->isNotEmpty())
                                    @foreach ($restaurant->reviews as $review)
                                        @php
                                            $avatarIndex = ($loop->index % 5) + 1;
                                            $avatarSrc = ($review->user && $review->user->profile_image)
                                                ? asset($review->user->profile_image)
                                                : asset('front/assets/images/icons/p' . $avatarIndex . '.png');
                                            $rating = (int) ($review->restaurant_rating ?: ($review->food_rating ?: 5));
                                            $timeAgo = $review->created_at ? $review->created_at->diffForHumans() : '1 month ago';
                                            $headline = $rating >= 5 ? 'Wonderful Experience...!!' : ($rating >= 4 ? 'Great Food & Good Service' : ($rating >= 3 ? 'Good Experience' : 'Fair Experience'));
                                        @endphp
                                        <li>
                                            <div class="review-box">
                                                <div class="review-head">
                                                    <div class="review-image">
                                                        <img class="img-fluid img" src="{{ $avatarSrc }}" alt="{{ $review->user?->name ?? 'Reviewer' }}">
                                                    </div>
                                                    <div class="d-flex align-sm-items-center justify-content-between w-100">
                                                        <div>
                                                            <h6 class="reviewer-name">{{ $review->user?->name ?? 'Gunjan Puri' }}</h6>
                                                            <ul class="rating-star">
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <li>
                                                                        <i class="{{ $i <= $rating ? 'ri-star-fill star' : 'ri-star-line text-muted' }}"></i>
                                                                    </li>
                                                                @endfor
                                                            </ul>
                                                        </div>
                                                        <div>
                                                            <h6>{{ $timeAgo }}</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="review-content">
                                                    <h6>{{ $headline }}</h6>
                                                    <p>
                                                        {{ $review->comment ?: 'Serve a healthy and good food. Having a parking facility and there are also waiting area available. These place is perfect for Celebration of birthday.' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li>
                                        <div class="review-box">
                                            <div class="review-head">
                                                <div class="review-image">
                                                    <img class="img-fluid img" src="{{ asset('front/assets/images/icons/p1.png') }}" alt="p1">
                                                </div>
                                                <div class="d-flex align-sm-items-center justify-content-between w-100">
                                                    <div>
                                                        <h6 class="reviewer-name">Gunjan Puri</h6>
                                                        <ul class="rating-star">
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <h6>1 month ago</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content">
                                                <h6>Wonderful Experience...!!</h6>
                                                <p>
                                                    Serve a healthy and good food. Having a parking
                                                    facility and there are also waiting area
                                                    available. These place is perfect for Celebration
                                                    of birthday. Serve a healthy and good food. Having
                                                    a parking facility and there are also waiting area
                                                    available.
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="review-box">
                                            <div class="review-head">
                                                <div class="review-image">
                                                    <img class="img-fluid img" src="{{ asset('front/assets/images/icons/p2.png') }}" alt="p2">
                                                </div>
                                                <div class="d-flex align-sm-items-center justify-content-between w-100">
                                                    <div>
                                                        <h6 class="reviewer-name">Gunjan Puri</h6>
                                                        <ul class="rating-star">
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <h6>1 month ago</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content">
                                                <h6>Wonderful Experience...!!</h6>
                                                <p>
                                                    Serve a healthy and good food. Having a parking
                                                    facility and there are also waiting area
                                                    available. These place is perfect for Celebration
                                                    of birthday. Serve a healthy and good food. Having
                                                    a parking facility and there are also waiting area
                                                    available.
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="review-box">
                                            <div class="review-head">
                                                <div class="review-image">
                                                    <img class="img-fluid img" src="{{ asset('front/assets/images/icons/p5.png') }}" alt="p5">
                                                </div>
                                                <div class="d-flex align-sm-items-center justify-content-between w-100">
                                                    <div>
                                                        <h6 class="reviewer-name">Gunjan Puri</h6>
                                                        <ul class="rating-star">
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <h6>1 month ago</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content">
                                                <h6>Wonderful Experience...!!</h6>
                                                <p>
                                                    Serve a healthy and good food. Having a parking
                                                    facility and there are also waiting area
                                                    available. These place is perfect for Celebration
                                                    of birthday. Serve a healthy and good food. Having
                                                    a parking facility and there are also waiting area
                                                    available.
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="review-box">
                                            <div class="review-head">
                                                <div class="review-image">
                                                    <img class="img-fluid img" src="{{ asset('front/assets/images/icons/p4.png') }}" alt="p4">
                                                </div>
                                                <div class="d-flex align-sm-items-center justify-content-between w-100">
                                                    <div>
                                                        <h6 class="reviewer-name">Gunjan Puri</h6>
                                                        <ul class="rating-star">
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                            <li><i class="ri-star-fill star"></i></li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <h6>1 month ago</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="review-content">
                                                <h6>Wonderful Experience...!!</h6>
                                                <p>
                                                    Serve a healthy and good food. Having a parking
                                                    facility and there are also waiting area
                                                    available. These place is perfect for Celebration
                                                    of birthday. Serve a healthy and good food. Having
                                                    a parking facility and there are also waiting area
                                                    available.
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    {{-- 5. BOOK A TABLE TAB --}}
                    @if(!$restaurant || $restaurant->restaurant_type !== 'cloud_kitchen')
                    <div class="tab-pane fade" id="book" role="tabpanel" tabindex="0">
                        @php
                            $allFrontSlots = $restaurant ? $restaurant->generateTimeSlots() : [];
                            $lunchSlots = array_values(array_filter($allFrontSlots, fn($s) => ($s['meal'] ?? 'dinner') === 'lunch'));
                            $dinnerSlots = array_values(array_filter($allFrontSlots, fn($s) => ($s['meal'] ?? 'dinner') === 'dinner'));

                            $hasLunch = !empty($lunchSlots);
                            $hasDinner = !empty($dinnerSlots);

                            $hasTables = $restaurant && $restaurant->tables()->where('status', \App\Models\RestaurantTable::STATUS_AVAILABLE)->count() > 0;

                            $defaultTime = '19:00';
                            $defaultMeal = 'dinner';
                            if ($hasDinner) {
                                $has19 = in_array('19:00', array_column($dinnerSlots, 'time'));
                                $defaultTime = $has19 ? '19:00' : $dinnerSlots[0]['time'];
                                $defaultMeal = 'dinner';
                            } elseif ($hasLunch) {
                                $defaultTime = $lunchSlots[0]['time'];
                                $defaultMeal = 'lunch';
                            }
                        @endphp

                        @if (($hasLunch || $hasDinner) && $hasTables)
                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-md-10 col-12">
                                <div class="product-details-box">
                                    <div class="product-content">
                                        <div class="book-table-card">
                                            <div class="booking-heading">
                                                <h4>Book Your Table</h4>
                                                <p>Reserve your table and enjoy a great dining experience.</p>
                                            </div>

                                            <form id="bookTableForm" class="book-table-form">
                                                @csrf
                                                <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">

                                                <div id="bookingFeedback" class="booking-feedback" style="display:none;"></div>

                                                <!-- Zomato 3-Dropdown Selector Bar (Date, Guests, Time/Meal) -->
                                                <div class="zomato-booking-bar">
                                                    <!-- 1. Select Date -->
                                                    <div class="zomato-dropdown-item" id="zomatoDateBox" title="Click to change date">
                                                        <div class="zomato-dropdown-left">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#4F4F4F" width="16" height="16" viewBox="0 0 20 20" role="img" class="zomato-bar-svg"><title>table-booking</title><path d="M17.14 1.66h-0.94v1.92h0.94c0 0 0 0 0 0 0.523 0 0.949 0.418 0.96 0.939l0 0.001v11.2c0 1.049-0.851 1.9-1.9 1.9v0h-12.38c-0.006 0-0.013 0-0.020 0-1.049 0-1.9-0.851-1.9-1.9 0-0 0-0 0-0v0-11.2c0.011-0.522 0.437-0.94 0.96-0.94 0 0 0 0 0 0h0.96v-1.92h-0.96c-1.58 0-2.86 1.28-2.86 2.86v0 11.2c0.011 2.101 1.717 3.8 3.82 3.8 0 0 0 0 0 0h12.38c2.094-0.011 3.789-1.706 3.8-3.799v-11.201c0-1.58-1.28-2.86-2.86-2.86v0zM14.28 4.76c0.53 0 0.96-0.43 0.96-0.96v0-2.38c-0.070-0.467-0.469-0.822-0.95-0.822s-0.88 0.354-0.949 0.817l-0.001 0.005v2.38c0 0 0 0 0 0 0 0.523 0.418 0.949 0.939 0.96l0.001 0zM10 4.76c0.53 0 0.96-0.43 0.96-0.96v0-2.38c0-0.53-0.43-0.96-0.96-0.96s-0.96 0.43-0.96 0.96v0 2.38c0 0.53 0.43 0.96 0.96 0.96v0zM5.72 4.76c0.522-0.011 0.94-0.437 0.94-0.96 0-0 0-0 0-0v0-2.38c-0.070-0.467-0.469-0.822-0.95-0.822s-0.88 0.354-0.949 0.817l-0.001 0.005v2.38c0 0.53 0.43 0.96 0.96 0.96v0zM5.16 9.32c-0.174 0.174-0.282 0.414-0.282 0.68s0.108 0.506 0.282 0.68l2.86 2.86c0.173 0.169 0.409 0.272 0.67 0.272s0.497-0.104 0.67-0.273l5.48-5.48c0.174-0.174 0.282-0.414 0.282-0.68s-0.108-0.506-0.282-0.68l-0-0c-0.173-0.169-0.409-0.272-0.67-0.272s-0.497 0.104-0.67 0.273l-4.82 4.82-2.18-2.2c-0.173-0.169-0.409-0.272-0.67-0.272s-0.497 0.104-0.67 0.273l0-0z"></path></svg>
                                                            <span class="zomato-bar-label" id="zomatoDateLabel">Today</span>
                                                        </div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#B5B5B5" width="12" height="12" viewBox="0 0 20 20" role="img" class="zomato-arrow-svg"><title>down-triangle</title><path d="M20 5.42l-10 10-10-10h20z"></path></svg>
                                                        <input type="date"
                                                               name="book_date"
                                                               id="bookDateInput"
                                                               class="zomato-hidden-input"
                                                               min="{{ now()->toDateString() }}"
                                                               max="{{ now()->addDays($restaurant->advance_booking_days ?: 7)->toDateString() }}"
                                                               value="{{ now()->toDateString() }}"
                                                               required>
                                                    </div>

                                                    <!-- 2. Select Guests -->
                                                    <div class="zomato-dropdown-item" id="zomatoGuestsBox" title="Click to change guests">
                                                        <div class="zomato-dropdown-left">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#4F4F4F" width="16" height="16" viewBox="0 0 20 20" role="img" class="zomato-bar-svg"><title>commuities-2-face</title><path d="M7.86 9.22c2.24 0 4.040-1.8 4.040-4.040s-1.8-4.060-4.040-4.060c-2.24 0-4.040 1.82-4.040 4.060v0c0 2.24 1.8 4.040 4.040 4.040v0zM7.86 2.56c1.44 0 2.62 1.18 2.62 2.62s-1.18 2.62-2.62 2.62c-1.44 0-2.62-1.18-2.62-2.62v0c0-1.44 1.18-2.62 2.62-2.62v0zM18.58 13.14c-1.060-0.94-2.46-1.54-4-1.54-0.060 0-0.12 0-0.16 0v0h-0.14c-0.020 0-0.060 0-0.1 0-0.7 0-1.36 0.1-2 0.3l0.040-0.020c-1.28-0.92-2.88-1.46-4.6-1.46-0.080 0-0.16 0-0.24 0h0.020c-0.1 0-0.22-0.020-0.32-0.020-2 0-3.82 0.76-5.18 2.020v-0.020c-1.18 1.22-1.9 2.88-1.9 4.72 0 0.1 0 0.18 0 0.28v-0.020c0 0 0 0 0 0 0 1.68 1.34 3.020 3 3.040h8.76c0.96-0.020 1.82-0.48 2.36-1.18l0.020-0.020h3.42c0 0 0.020 0 0.020 0 1.32 0 2.4-1.060 2.42-2.38v0c0-0.060 0-0.12 0-0.18 0-1.36-0.54-2.6-1.42-3.52v0zM11.76 18.98h-8.74c-0.88 0-1.6-0.7-1.6-1.58 0 0 0 0 0-0.020v0c0-0.080 0-0.18 0-0.26 0-1.44 0.56-2.74 1.48-3.7v0c1.1-0.98 2.54-1.58 4.14-1.58 0.080 0 0.16 0 0.22 0v0h0.14c0.1 0 0.22 0 0.36 0 1.58 0 3.020 0.6 4.1 1.58v0c0.92 0.96 1.48 2.26 1.48 3.7 0 0.080 0 0.18 0 0.28v-0.020c0 0.020 0 0.020 0 0.020 0 0.88-0.72 1.58-1.58 1.58v0zM17.56 17.8h-2.86c0.020-0.12 0.040-0.26 0.060-0.4v-0.020c0-0.040 0-0.12 0-0.18 0-1.54-0.5-2.96-1.34-4.12l0.020 0.020c0.22-0.040 0.5-0.060 0.76-0.060 0.020 0 0.060 0 0.1 0h-0.020c0.080 0 0.18-0.020 0.26-0.020 1.16 0 2.22 0.44 3.020 1.16v-0.020c0.62 0.66 1 1.52 1 2.5 0 0.060 0 0.12 0 0.18v0c0 0 0 0 0 0 0 0.54-0.42 0.96-0.94 0.96-0.020 0-0.040 0-0.060 0v0zM14.4 10.9c0 0 0 0 0.020 0 1.5 0 2.74-1.24 2.74-2.76s-1.24-2.74-2.74-2.74c-1.52 0-2.76 1.24-2.76 2.74v0c0 0 0 0 0 0 0 1.52 1.24 2.74 2.74 2.76v0zM14.4 6.84c0 0 0 0 0.020 0 0.72 0 1.3 0.6 1.3 1.32s-0.58 1.32-1.3 1.32c-0.74 0-1.32-0.6-1.32-1.32 0 0 0 0 0-0.020v0c0-0.7 0.58-1.3 1.3-1.3v0z"></path></svg>
                                                            <span class="zomato-bar-label" id="zomatoGuestsLabel">2 guests</span>
                                                        </div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#B5B5B5" width="12" height="12" viewBox="0 0 20 20" role="img" class="zomato-arrow-svg"><title>down-triangle</title><path d="M20 5.42l-10 10-10-10h20z"></path></svg>
                                                        <select name="guests" id="bookGuestsSelect" class="zomato-hidden-input" required>
                                                            @for ($i = 1; $i <= 8; $i++)
                                                                <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>
                                                                    {{ $i }} {{ $i == 1 ? 'guest' : 'guests' }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <!-- 3. Select Time / Meal -->
                                                    <div class="zomato-dropdown-item" id="zomatoTimeBox" title="Click to change meal/time">
                                                        <div class="zomato-dropdown-left">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#4F4F4F" width="16" height="16" viewBox="0 0 20 20" role="img" class="zomato-bar-svg"><title>time</title><path d="M14.76 9.040h-4.22l-2.58-4.28c-0.147-0.354-0.489-0.598-0.889-0.598-0.53 0-0.96 0.43-0.96 0.96 0 0.227 0.079 0.436 0.211 0.6l-0.001-0.002 2.86 4.76c0.172 0.278 0.474 0.46 0.82 0.46 0 0 0 0 0.001 0h4.76c0.467-0.070 0.822-0.469 0.822-0.95s-0.354-0.88-0.817-0.949l-0.005-0.001zM10 0c-5.523 0-10 4.477-10 10s4.477 10 10 10c5.523 0 10-4.477 10-10v0c0-5.523-4.477-10-10-10v0zM10 18.58c-4.739 0-8.58-3.841-8.58-8.58s3.841-8.58 8.58-8.58c4.739 0 8.58 3.841 8.58 8.58v0c0 4.739-3.841 8.58-8.58 8.58v0z"></path></svg>
                                                            <span class="zomato-bar-label" id="zomatoTimeLabel">
                                                                {{ ucfirst($defaultMeal) }} ({{ date('g:i A', strtotime($defaultTime)) }})
                                                            </span>
                                                        </div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#B5B5B5" width="12" height="12" viewBox="0 0 20 20" role="img" class="zomato-arrow-svg"><title>down-triangle</title><path d="M20 5.42l-10 10-10-10h20z"></path></svg>
                                                        <select id="zomatoMealSelect" class="zomato-hidden-input">
                                                            @if ($hasLunch)
                                                                <option value="lunch" {{ $defaultMeal === 'lunch' ? 'selected' : '' }}>Lunch</option>
                                                            @endif
                                                            @if ($hasDinner)
                                                                <option value="dinner" {{ $defaultMeal === 'dinner' ? 'selected' : '' }}>Dinner</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <!-- Available Time Slots with Lunch / Dinner Switcher -->
                                                    <div class="col-12">
                                                        <div class="booking-field">
                                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                                                <div>
                                                                    <label class="form-label mb-1 fw-bold d-block">Select Dining Time Slot</label>
                                                                    <!-- Lunch / Dinner Switcher Tabs -->
                                                                    <div class="meal-tabs-nav" id="mealTabsNav">
                                                                        @if ($hasLunch)
                                                                            <button type="button"
                                                                                    class="meal-tab-btn {{ $defaultMeal === 'lunch' ? 'active' : '' }}"
                                                                                    data-meal-type="lunch"
                                                                                    id="lunchTabBtn">
                                                                                <i class="ri-sun-fill me-1"></i> Lunch
                                                                            </button>
                                                                        @endif
                                                                        @if ($hasDinner)
                                                                            <button type="button"
                                                                                    class="meal-tab-btn {{ $defaultMeal === 'dinner' ? 'active' : '' }}"
                                                                                    data-meal-type="dinner"
                                                                                    id="dinnerTabBtn">
                                                                                <i class="ri-moon-clear-fill me-1"></i> Dinner
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <small class="text-muted" id="slotDurationHint"><i class="ri-timer-line me-1"></i>{{ $restaurant->slot_duration_minutes ?: 60 }} mins slot</small>
                                                            </div>
                                                            <input type="hidden" name="book_time" id="bookTimeInput" value="{{ $defaultTime }}" required>

                                                            <div id="mealSlotsWrapper">
                                                                <!-- Lunch Section (Visible only when Lunch tab is active) -->
                                                                <div class="meal-group mb-2 {{ ($hasLunch && $defaultMeal === 'lunch') ? '' : 'd-none' }}" id="lunchSlotGroup">
                                                                    <div class="slot-pill-grid" id="lunchPillsContainer">
                                                                        @foreach($lunchSlots as $s)
                                                                            <button type="button"
                                                                                    class="slot-pill-btn {{ $s['time'] === $defaultTime ? 'active' : '' }}"
                                                                                    data-time="{{ $s['time'] }}"
                                                                                    data-meal="lunch">
                                                                                <i class="ri-time-line"></i> {{ $s['display'] }}
                                                                            </button>
                                                                        @endforeach
                                                                    </div>
                                                                </div>

                                                                <!-- Dinner Section (Visible only when Dinner tab is active) -->
                                                                <div class="meal-group mb-2 {{ ($hasDinner && $defaultMeal === 'dinner') ? '' : 'd-none' }}" id="dinnerSlotGroup">
                                                                    <div class="slot-pill-grid" id="dinnerPillsContainer">
                                                                        @foreach($dinnerSlots as $s)
                                                                            <button type="button"
                                                                                    class="slot-pill-btn {{ $s['time'] === $defaultTime ? 'active' : '' }}"
                                                                                    data-time="{{ $s['time'] }}"
                                                                                    data-meal="dinner">
                                                                                <i class="ri-time-line"></i> {{ $s['display'] }}
                                                                            </button>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>




                                                    <!-- Choose Offer -->
                                                    @if ($restaurant && $restaurant->diningOffers->isNotEmpty())
                                                        <div class="col-12">
                                                            <div class="booking-offers mb-2">
                                                                <div class="booking-heading mb-2">
                                                                    <h5 class="mb-1 fs-6 fw-bold">Choose an Offer (Optional)</h5>
                                                                    <p class="small text-muted mb-0">Select an offer for dining discount or book without offer.</p>
                                                                </div>

                                                                <div class="row g-3">
                                                                    @foreach ($restaurant->diningOffers as $offer)
                                                                        @php
                                                                            $offerCoverCharge = (float)($offer->cover_charge ?? 0);
                                                                            $discountDisplay = $offer->discount_type === 'percentage'
                                                                                ? rtrim(rtrim(number_format((float)$offer->discount_value, 2), '0'), '.') . '% OFF'
                                                                                : $currencySymbol . number_format((float)$offer->discount_value, 0) . ' OFF';
                                                                        @endphp
                                                                        <div class="col-sm-6 col-12">
                                                                            <label class="offer-card w-100">
                                                                                <input type="radio"
                                                                                       name="dining_offer_id"
                                                                                       value="{{ $offer->id }}"
                                                                                       data-cover-charge="{{ $offerCoverCharge }}"
                                                                                       data-title="{{ $offer->title }}"
                                                                                       class="offer-radio"
                                                                                       {{ $loop->first ? 'checked' : '' }}>

                                                                                <div class="offer-content">
                                                                                    <div class="offer-left">
                                                                                        <div class="d-flex align-items-center gap-1 mb-1">
                                                                                            <span class="offer-badge">{{ $offer->discount_type === 'percentage' ? 'PERCENT' : 'FLAT' }}</span>
                                                                                            @if ($offer->coupon_code)
                                                                                                <span class="badge bg-white text-primary border border-primary font-monospace fw-bold fs-11" style="border-radius: 5px;">
                                                                                                    <i class="ri-coupon-line me-1"></i>{{ $offer->coupon_code }}
                                                                                                </span>
                                                                                            @endif
                                                                                        </div>
                                                                                        <h6 class="offer-title">{{ $offer->title }}</h6>
                                                                                        <h5>{{ $discountDisplay }}</h5>
                                                                                        <p class="mb-0">
                                                                                            @if ($offerCoverCharge > 0)
                                                                                                <span class="text-danger fw-semibold"><i class="ri-money-rupee-circle-fill"></i> {{ $currencySymbol }}{{ number_format($offerCoverCharge, 0) }} cover charge required</span>
                                                                                            @else
                                                                                                <span class="text-success fw-semibold"><i class="ri-checkbox-circle-line"></i> No cover charge required</span>
                                                                                            @endif
                                                                                        </p>
                                                                                    </div>

                                                                                    <div class="offer-check">
                                                                                        <i class="ri-check-line"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    @endforeach

                                                                    {{-- Option for Regular Booking without Offer --}}
                                                                    <div class="col-sm-6 col-12">
                                                                        <label class="offer-card w-100">
                                                                            <input type="radio"
                                                                                   name="dining_offer_id"
                                                                                   value=""
                                                                                   data-cover-charge="0"
                                                                                   data-title="Regular Table Booking"
                                                                                   class="offer-radio">

                                                                            <div class="offer-content">
                                                                                <div class="offer-left">
                                                                                    <div class="d-flex align-items-center gap-1 mb-1">
                                                                                        <span class="offer-badge bg-secondary">STANDARD</span>
                                                                                    </div>
                                                                                    <h6 class="offer-title">Regular Booking</h6>
                                                                                    <h5>Free Reservation</h5>
                                                                                    <p class="mb-0 text-success fw-semibold">
                                                                                        <i class="ri-checkbox-circle-line"></i> No cover charge required
                                                                                    </p>
                                                                                </div>

                                                                                <div class="offer-check">
                                                                                    <i class="ri-check-line"></i>
                                                                                </div>
                                                                            </div>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <!-- Name -->
                                                    <div class="col-sm-6 col-12">
                                                        <div class="booking-field">
                                                            <label class="form-label">Customer Name</label>
                                                            <i class="ri-user-line booking-icon"></i>
                                                            <input type="text"
                                                                   name="customer_name"
                                                                   class="form-control booking-input"
                                                                   placeholder="Enter your name"
                                                                   value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <!-- Phone -->
                                                    <div class="col-sm-6 col-12">
                                                        <div class="booking-field">
                                                            <label class="form-label">Phone Number</label>
                                                            <i class="ri-phone-line booking-icon"></i>
                                                            <input type="tel"
                                                                   name="phone"
                                                                   class="form-control booking-input"
                                                                   placeholder="Enter contact number"
                                                                   value="{{ auth()->check() ? auth()->user()->phone : '' }}"
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <!-- Cover Charge & Payment Section -->
                                                    <div class="col-12" id="coverChargeSection">
                                                        <div class="cover-charge-card p-3 rounded-3 border" id="coverChargeCard">
                                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="cover-icon-wrap" id="coverChargeIcon">
                                                                        <i class="ri-shield-flash-line"></i>
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="fw-bold mb-0 text-dark" id="coverChargeTitle">Cover Charge Required</h6>
                                                                        <small class="text-muted" id="coverChargeSub">Adjustable against your final food bill at restaurant</small>
                                                                    </div>
                                                                </div>
                                                                <div class="cover-amount-badge" id="coverChargeBadge">
                                                                    <span class="fs-5 fw-bold" id="coverChargeAmountDisplay">{{ $currencySymbol }}0.00</span>
                                                                </div>
                                                            </div>

                                                            <div id="coverChargePaymentOptions" style="display: none;">
                                                                <div class="pt-3 border-top mt-3">
                                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                                        <label class="form-label small fw-bold text-dark mb-0">Select Payment Method to Pay Cover Charge:</label>
                                                                        <span class="badge bg-light text-muted border font-monospace fs-11"><i class="ri-shield-check-line me-1 text-success"></i>100% Secure</span>
                                                                    </div>
                                                                    <div class="row g-2">
                                                                        @php
                                                                            $activeGateways = \App\Models\PaymentGateway::where('is_active', true)->orderBy('sort_order')->get();
                                                                        @endphp

                                                                        @foreach($activeGateways as $index => $gw)
                                                                            @php
                                                                                $gwKey = $gw->gateway_key;
                                                                                $gwIcon = 'ri-secure-payment-line';
                                                                                $gwBg = $gw->badge_color ?? '#fc8019';
                                                                                $gwTextColor = '#ffffff';
                                                                                $gwSub = $gw->description;
                                                                                $gwName = $gw->display_name ?: ucfirst($gwKey);

                                                                                if ($gwKey === 'razorpay') {
                                                                                    $gwIcon = 'ri-flashlight-fill';
                                                                                    $gwBg = '#072654';
                                                                                    $gwTextColor = '#3395ff';
                                                                                    $gwName = 'Razorpay';
                                                                                    $gwSub = $gwSub ?: 'UPI, GPay, Cards & NetBanking';
                                                                                } elseif ($gwKey === 'phonepe') {
                                                                                    $gwIcon = 'ri-smartphone-line';
                                                                                    $gwBg = '#5f259f';
                                                                                    $gwTextColor = '#ffffff';
                                                                                    $gwName = 'PhonePe';
                                                                                    $gwSub = $gwSub ?: 'Direct UPI, QR & Wallet';
                                                                                } elseif ($gwKey === 'paytm') {
                                                                                    $gwIcon = 'ri-wallet-3-line';
                                                                                    $gwBg = '#00b9f5';
                                                                                    $gwTextColor = '#002e6e';
                                                                                    $gwName = 'Paytm';
                                                                                    $gwSub = $gwSub ?: 'Paytm Wallet, UPI & Postpaid';
                                                                                } elseif ($gwKey === 'payu' || $gwKey === 'payumoney') {
                                                                                    $gwIcon = 'ri-bank-card-line';
                                                                                    $gwBg = '#a5c83b';
                                                                                    $gwTextColor = '#ffffff';
                                                                                    $gwName = 'PayU Money';
                                                                                    $gwSub = $gwSub ?: 'Instant Checkout & Netbanking';
                                                                                } elseif ($gwKey === 'paypal') {
                                                                                    $gwIcon = 'ri-paypal-fill';
                                                                                    $gwBg = '#003087';
                                                                                    $gwTextColor = '#0079c1';
                                                                                    $gwName = 'PayPal';
                                                                                    $gwSub = $gwSub ?: 'Cards & PayPal Wallet';
                                                                                }
                                                                            @endphp
                                                                            <div class="col-md-6 col-12">
                                                                                <label class="gateway-card-tile w-100" data-gateway-name="{{ $gwName }}">
                                                                                    <div class="d-flex align-items-center gap-2">
                                                                                        <input type="radio" name="payment_method" value="{{ $gwKey }}" {{ $loop->first ? 'checked' : '' }} class="gateway-radio">
                                                                                        <div class="gateway-brand-badge" style="background: {{ $gwBg }}; color: {{ $gwTextColor }};">
                                                                                            <i class="{{ $gwIcon }}"></i>
                                                                                        </div>
                                                                                        <div>
                                                                                            <div class="gateway-name fw-bold text-dark">{{ $gwName }}</div>
                                                                                            <div class="gateway-sub text-muted">{{ $gwSub }}</div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="gateway-check-icon">
                                                                                        <i class="ri-checkbox-circle-fill"></i>
                                                                                    </div>
                                                                                </label>
                                                                            </div>
                                                                        @endforeach

                                                                        <!-- My Wallet Option -->
                                                                        <div class="col-md-6 col-12">
                                                                            <label class="gateway-card-tile w-100" data-gateway-name="Wallet">
                                                                                <div class="d-flex align-items-center gap-2">
                                                                                    <input type="radio" name="payment_method" value="wallet" {{ $activeGateways->isEmpty() ? 'checked' : '' }} class="gateway-radio">
                                                                                    <div class="gateway-brand-badge" style="background: #ea580c; color: #ffffff;">
                                                                                        <i class="ri-wallet-3-fill"></i>
                                                                                    </div>
                                                                                    <div>
                                                                                        <div class="gateway-name fw-bold text-dark">My Wallet</div>
                                                                                        <div class="gateway-sub text-muted">Instant pay from App Wallet</div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="gateway-check-icon">
                                                                                    <i class="ri-checkbox-circle-fill"></i>
                                                                                </div>
                                                                            </label>
                                                                        </div></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Submit -->
                                                    <div class="col-12 pt-1">
                                                        <button type="submit"
                                                                class="btn booking-submit w-100"
                                                                id="bookSubmitBtn">
                                                            <i class="ri-calendar-check-line me-2"></i>
                                                            <span id="bookSubmitBtnText">Request Booking</span>
                                                        </button>
                                                        <div class="booking-note">
                                                            <i class="ri-shield-check-line me-1"></i>
                                                            Your booking request is safe, secure & instant
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="booking-unavailable-msg">
                            <div class="booking-unavailable-icon">
                                <i class="ri-calendar-close-line"></i>
                            </div>
                            <h4>Booking Not Available</h4>
                            <p>Table booking is not available at this time. Please try again later or contact the restaurant directly.</p>
                        </div>
                    @endif
                    </div>
                    @endif

                    {{-- 6. MENU TAB --}}
                    <div class="tab-pane fade" id="menu-list" role="tabpanel" tabindex="0">
                        <div class="row g-3">
                            @if ($restaurant && $restaurant->menus && $restaurant->menus->isNotEmpty())
                                @foreach ($restaurant->menus as $menu)
                                    <div class="col-lg-3 col-6">
                                        <div class="menu-image-box">
                                            <img class="img-fluid rounded menu-thumb"
                                                src="{{ asset($menu->image) }}"
                                                alt="{{ $menu->name }}"
                                                data-full="{{ asset($menu->image) }}"
                                                data-name="{{ $menu->name }}">
                                            @if ($menu->name)
                                                <span class="menu-thumb-label">{{ $menu->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <p class="content-color">No menu available.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- tab section end -->

    {{-- Menu Image Zoom Modal --}}
    <div class="modal fade" id="menuZoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content menu-zoom-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="menuZoomModalTitle">Menu</h5>
                    <div class="menu-zoom-controls">
                        <button type="button" class="btn menu-zoom-btn" id="menuZoomIn" title="Zoom in"><i class="ri-zoom-in-line"></i></button>
                        <button type="button" class="btn menu-zoom-btn" id="menuZoomOut" title="Zoom out"><i class="ri-zoom-out-line"></i></button>
                        <button type="button" class="btn menu-zoom-btn" id="menuZoomReset" title="Reset zoom"><i class="ri-restart-line"></i></button>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="menu-zoom-viewport" id="menuZoomViewport">
                        <img src="" alt="Menu" class="menu-zoom-image" id="menuZoomImage">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* =======================================
           Zomato-style Dining Offers & Overview UI
        ======================================= */

        /* Menu Images Grid */
        .menu-image-box {
            position: relative;
            overflow: hidden;
            border-radius: 14px;
            border: 1px solid #f1f1f1;
            cursor: zoom-in;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.04);
            transition: transform .22s ease, box-shadow .22s ease;
        }
        .menu-image-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.12);
        }
        .menu-thumb {
            width: 100%;
            height: 210px;
            object-fit: cover;
            display: block;
            transition: transform .3s ease;
        }
        .menu-image-box:hover .menu-thumb {
            transform: scale(1.05);
        }
        .menu-thumb-label {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 22px 12px 8px;
            font-size: 12.5px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(to top, rgba(0,0,0,0.65), transparent);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Menu Zoom Modal */
        .menu-zoom-modal .modal-header {
            border-bottom: 1px solid #f1f1f1;
            padding: 12px 18px;
        }
        .menu-zoom-controls {
            display: inline-flex;
            gap: 6px;
        }
        .menu-zoom-btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #334155;
            border-radius: 8px;
            font-size: 17px;
            transition: all .2s ease;
        }
        .menu-zoom-btn:hover {
            background: #fc8019;
            border-color: #fc8019;
            color: #ffffff;
        }
        .menu-zoom-viewport {
            height: 72vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f7f5 url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="%23ececec" d="M0 0h12v12H0zM12 12h12v12H12z"/></svg>');
            background-size: 24px 24px;
            position: relative;
            border-radius: 10px;
            cursor: grab;
        }
        .menu-zoom-viewport:active {
            cursor: grabbing;
        }
        .menu-zoom-image {
            max-width: 100%;
            max-height: 100%;
            transform-origin: center center;
            transition: transform .12s ease-out;
            user-select: none;
            -webkit-user-drag: none;
        }
        .menu-zoom-modal .modal-body {
            padding: 14px;
            background: #f8f7f5;
        }
        .menu-zoom-modal .btn-close {
            font-size: 12px;
        }

        .dining-offers-main-card {
            background: #ffffff;
            border: 1px solid #eef2f6;
            border-radius: 16px;
            padding: 24px 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }
        .dining-offers-head-title {
            font-size: 22px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 2px;
        }
        .dining-offers-sub {
            font-size: 13.5px;
            color: #64748b;
            margin-bottom: 18px;
        }

        /* Prevent background layout shift when modal opens */
        body.modal-open {
            overflow: visible !important;
            padding-right: 0 !important;
        }

        /* Prevent the dark background color change when modal opens */
        .modal-backdrop {
            background-color: transparent !important;
        }

        /* Offer Cards */
        .dining-offer-box {
            border-radius: 12px;
            padding: 16px 18px;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.25s ease;
            min-height: 125px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .dining-offer-box:hover {
            transform: translateY(-2px);
        }

        /* Primary Active Card (Royal Blue) */
        .dining-offer-box.is-primary {
            background: linear-gradient(135deg, #1b68e3 0%, #1557c0 100%);
            border: 1px solid #1557c0;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(27, 104, 227, 0.28);
        }
        .dining-offer-box.is-primary .offer-tag-badge {
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .dining-offer-box.is-primary .offer-discount-title {
            color: #ffffff;
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .dining-offer-box.is-primary .offer-desc-text {
            color: rgba(255, 255, 255, 0.88);
            font-size: 11.5px;
            line-height: 1.35;
        }
        .dining-offer-box.is-primary .sub-muted {
            color: rgba(255, 255, 255, 0.75);
        }
        .dining-offer-box.is-primary .offer-bg-icon {
            position: absolute;
            right: 8px;
            bottom: 4px;
            font-size: 60px;
            color: rgba(255, 255, 255, 0.12);
            pointer-events: none;
        }

        /* Secondary White Card */
        .dining-offer-box.is-secondary {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }
        .dining-offer-box.is-secondary:hover {
            border-color: #1b68e3;
            box-shadow: 0 6px 18px rgba(27, 104, 227, 0.12);
        }
        .dining-offer-box.is-secondary .offer-tag-badge {
            color: #1b68e3;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .dining-offer-box.is-secondary .offer-discount-title {
            color: #1e293b;
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 4px;
            line-height: 1.3;
        }
        .dining-offer-box.is-secondary .offer-desc-text {
            color: #64748b;
            font-size: 11.5px;
            line-height: 1.35;
        }
        .dining-offer-box.is-secondary .offer-bg-icon {
            position: absolute;
            right: 8px;
            bottom: 4px;
            font-size: 55px;
            color: rgba(27, 104, 227, 0.06);
            pointer-events: none;
        }

        /* Right Column Widgets */
        .table-reservation-widget-card,
        .direction-widget-card {
            background: #ffffff;
            border: 1px solid #eef2f6;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        }
        .widget-heading {
            font-size: 19px;
            font-weight: 700;
            color: #1a202c;
        }
        .reservation-offer-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f4f5fd;
            border: 1px solid #e6e8fa;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 12.5px;
            font-weight: 700;
            color: #1c68e3;
        }
        .reservation-offer-badge .icon {
            font-size: 15px;
            color: #1c68e3;
        }
        .quick-select-wrap .quick-select {
            height: 44px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            color: #334155;
            background-color: #fff;
        }
        .btn-zomato-red {
            height: 46px;
            background: #ef4f5f;
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            margin-top: 4px;
            transition: all 0.25s ease;
        }
        .btn-zomato-red:hover {
            background: #e23744;
            color: #fff;
            box-shadow: 0 6px 18px rgba(226, 55, 68, 0.35);
            transform: translateY(-1px);
        }
        .direction-address-text {
            font-size: 13px;
            line-height: 1.5;
            color: #64748b;
        }
        .direction-btn {
            padding: 6px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border-color: #d1d5db;
            color: #374151;
            background: #fff;
        }
        .direction-btn:hover {
            background: #f3f4f6;
            color: #111827;
        }
        .direction-red {
            color: #ef4f5f;
            border-color: #fecdd3;
            background: #fff5f5;
        }
        .direction-red:hover {
            background: #ef4f5f;
            color: #fff;
            border-color: #ef4f5f;
        }

        /* Book Table Form Card */
        .book-table-card {
            background: #fff;
            border: 1px solid #f1f1f1;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.07);
        }
        .booking-heading {
            margin-bottom: 24px;
        }
        .booking-heading h4 {
            font-size: 22px;
            font-weight: 700;
            color: #222;
            margin-bottom: 6px;
        }
        .booking-heading p {
            color: #888;
            font-size: 14px;
            margin: 0;
        }
        .booking-field {
            position: relative;
        }
        .booking-field .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }
        .booking-input {
            height: 52px;
            border: 1px solid #e8e8e8;
            border-radius: 10px;
            background: #fafafa;
            padding: 10px 14px 10px 43px;
            font-size: 14px;
            color: #333;
            transition: all 0.25s ease;
        }
        .booking-input:focus {
            border-color: #fc8019;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(252, 128, 25, 0.10);
        }
        .booking-icon {
            position: absolute;
            left: 15px;
            bottom: 16px;
            color: #fc8019;
            font-size: 18px;
            z-index: 2;
        }
        .booking-submit {
            height: 54px;
            border: 0;
            border-radius: 11px;
            background: #fc8019;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .2px;
            transition: all 0.25s ease;
            box-shadow: 0 7px 18px rgba(252, 128, 25, 0.22);
        }
        .booking-submit:hover {
            background: #e96f0b;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(252, 128, 25, 0.28);
        }
        .booking-feedback {
            display: none;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .booking-feedback.is-success {
            background: #eafaf1;
            color: #1a7f43;
            border: 1px solid #bfe9cd;
        }
        .booking-feedback.is-danger {
            background: #fdecea;
            color: #b42318;
            border: 1px solid #f5c2bb;
        }
        .booking-note {
            text-align: center;
            margin-top: 14px;
            color: #999;
            font-size: 12px;
        }

        /* Booking Unavailable Message */
        .booking-unavailable-msg {
            text-align: center;
            padding: 48px 24px;
            background: #fff;
            border: 1px solid #f1f1f1;
            border-radius: 16px;
        }
        .booking-unavailable-icon {
            width: 62px;
            height: 62px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff1ee;
            color: #ef4f5f;
            font-size: 30px;
        }
        .booking-unavailable-msg h4 {
            font-size: 20px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
        }
        .booking-unavailable-msg p {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 0;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Booking Offer Cards */
        .offer-card {
            position: relative;
            display: block;
            cursor: pointer;
            margin: 0;
        }
        .offer-radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        .offer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 115px;
            padding: 18px;
            border: 1.5px solid #e8e8e8;
            border-radius: 14px;
            background: #fff;
            transition: all 0.25s ease;
        }
        .offer-card:hover .offer-content {
            border-color: #fc8019;
            box-shadow: 0 6px 20px rgba(252, 128, 25, 0.10);
        }
        .offer-radio:checked + .offer-content {
            border-color: #fc8019;
            background: #fff8f3;
            box-shadow: 0 6px 20px rgba(252, 128, 25, 0.14);
        }
        .offer-badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 5px;
            background: #fc8019;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
            margin-bottom: 7px;
        }
        .offer-check {
            width: 25px;
            height: 25px;
            border: 1.5px solid #ddd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: transparent;
            transition: all 0.25s ease;
        }
        .offer-radio:checked + .offer-content .offer-check {
            background: #fc8019;
            border-color: #fc8019;
            color: #fff;
        }
        .offer-check i {
            font-size: 15px;
        }

        /* Cover Charge & Payment UI */
        .cover-charge-card {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            transition: all 0.25s ease;
        }
        .cover-charge-card.has-charge {
            background: #f0f7ff;
            border-color: #93c5fd;
        }
        .cover-icon-wrap {
            width: 40px;
            height: 40px;
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .cover-icon-wrap.free {
            background: #dcfce7;
            color: #15803d;
        }
        .cover-amount-badge {
            background: #1e40af;
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 8px;
        }
        .cover-amount-badge.free {
            background: #16a34a;
        }

        /* Gateway Cards UI */
        .gateway-card-tile {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            cursor: pointer;
            margin-bottom: 0;
            transition: all 0.2s ease;
        }
        .gateway-card-tile:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
            transform: translateY(-1px);
        }
        .gateway-card-tile.active,
        .gateway-card-tile:has(input:checked) {
            border-color: #2563eb;
            background: #f0f7ff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.14);
        }
        .gateway-card-tile .gateway-radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        .gateway-brand-badge {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .gateway-name {
            font-size: 13.5px;
            line-height: 1.2;
        }
        .gateway-sub {
            font-size: 11px;
            line-height: 1.2;
        }
        .gateway-check-icon {
            color: #cbd5e1;
            font-size: 19px;
            transition: all 0.2s ease;
        }
        .gateway-card-tile.active .gateway-check-icon,
        .gateway-card-tile:has(input:checked) .gateway-check-icon {
            color: #2563eb;
            transform: scale(1.1);
        }

        /* Zomato 3-Dropdown Table Booking Bar */
        .zomato-booking-bar {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            background: #ffffff;
            border: 1.5px solid #eaeaea;
            border-radius: 12px;
            padding: 8px;
            margin-bottom: 24px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        }
        .zomato-dropdown-item {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            background: #fafafa;
            border: 1.5px solid #e8e8e8;
            border-radius: 9px;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }
        .zomato-dropdown-item:hover,
        .zomato-dropdown-item:focus-within {
            background: #ffffff;
            border-color: #fc8019;
            box-shadow: 0 0 0 3px rgba(252, 128, 25, 0.12);
        }
        .zomato-dropdown-left {
            display: flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }
        .zomato-bar-svg {
            flex-shrink: 0;
        }
        .zomato-bar-label {
            font-size: 14px;
            font-weight: 600;
            color: #2b2b2b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .zomato-arrow-svg {
            flex-shrink: 0;
            margin-left: 6px;
        }
        .zomato-hidden-input {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 5;
        }
        @media (max-width: 768px) {
            .zomato-booking-bar {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 8px;
            }
        }

        /* Meal Tab Switcher (Lunch / Dinner) */
        .meal-tabs-nav {
            display: inline-flex;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 4px;
            gap: 4px;
        }
        .meal-tab-btn {
            border: 0;
            background: transparent;
            padding: 7px 18px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .meal-tab-btn:hover {
            color: #0f172a;
        }
        .meal-tab-btn.active {
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .meal-tab-btn.active[data-meal-type="dinner"] {
            color: #2563eb;
        }
        .meal-tab-btn.active[data-meal-type="lunch"] {
            color: #d97706;
        }

        /* Dining Time Slot Pills */
        .slot-pill-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 8px;
            margin-top: 6px;
            max-height: 270px;
            overflow-y: auto;
            padding: 3px 2px;
            scrollbar-width: thin;
        }

        .slot-pill-btn {
            border: 1.5px solid #e2e8f0;
            background: #ffffff;
            border-radius: 8px;
            padding: 8px 6px;
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            width: 100%;
            text-align: center;
            white-space: nowrap;
        }
        .slot-pill-btn:hover {
            border-color: #fc8019;
            color: #fc8019;
            background: #fff8f3;
        }
        .slot-pill-btn.active {
            border-color: #fc8019;
            background: #fc8019;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(252, 128, 25, 0.22);
        }
        .slot-pill-btn.disabled,
        .slot-pill-btn:disabled {
            opacity: 0.5;
            background: #f1f5f9;
            border-color: #cbd5e1;
            cursor: not-allowed;
            color: #94a3b8;
        }


        @media (max-width: 767px) {
            .dining-offers-main-card {
                padding: 18px 16px;
            }
            .table-reservation-widget-card,
            .direction-widget-card {
                padding: 18px 16px;
            }
        }
    </style>


@push('scripts')
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/css/sweetalert2.min.css') }}">
    <script src="{{ asset('admin/assets/vendors/js/sweetalert2.min.js') }}"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        /* ===== Menu Image Zoom Lightbox ===== */
        $(function () {
            var zoomScale = 1;
            var zoomMin = 1;
            var zoomMax = 4;
            var zoomStep = 0.25;
            var zoomImage = $('#menuZoomImage');
            var zoomViewport = $('#menuZoomViewport');
            var zoomModal = $('#menuZoomModal');

            function zoomApply() {
                zoomImage.css('transform', 'scale(' + zoomScale + ')');
            }

            function zoomReset() {
                zoomScale = 1;
                zoomApply();
            }

            function zoomSet(scale) {
                zoomScale = Math.min(zoomMax, Math.max(zoomMin, scale));
                zoomApply();
            }

            zoomModal.on('show.bs.modal', function (event) {
                var img = $(event.relatedTarget);
                zoomImage.attr('src', img.data('full'));
                zoomImage.attr('alt', img.data('name') || 'Menu');
                $('#menuZoomModalTitle').text(img.data('name') || 'Menu');
                zoomReset();
                zoomImage.css('left', '0').css('top', '0');
            });

            zoomModal.on('hidden.bs.modal', function () {
                zoomImage.attr('src', '');
                zoomReset();
            });

            $('#menuZoomIn').on('click', function () { zoomSet(zoomScale + zoomStep); });
            $('#menuZoomOut').on('click', function () { zoomSet(zoomScale - zoomStep); });
            $('#menuZoomReset').on('click', zoomReset);

            zoomViewport.on('wheel', function (e) {
                e.preventDefault();
                zoomSet(zoomScale + (e.originalEvent.deltaY < 0 ? zoomStep : -zoomStep));
            });

            var dragging = false;
            var startX = 0, startY = 0, imgOffsetX = 0, imgOffsetY = 0;

            zoomViewport.on('mousedown', function (e) {
                if (zoomScale <= 1) return;
                dragging = true;
                startX = e.clientX;
                startY = e.clientY;
                var pos = zoomImage.position();
                imgOffsetX = pos.left;
                imgOffsetY = pos.top;
            });

            zoomViewport.on('mousemove', function (e) {
                if (!dragging) return;
                e.preventDefault();
                zoomImage.css('left', (imgOffsetX + (e.clientX - startX)) + 'px');
                zoomImage.css('top', (imgOffsetY + (e.clientY - startY)) + 'px');
            });

            $(window).on('mouseup', function () { dragging = false; });

            $(document).on('click', '.menu-thumb', function () {
                zoomModal.modal('show', this);
            });
        });

        $(function () {

            var SYMBOL = window.CURRENCY_SYMBOL || '₹';
            var restaurantId = {{ $restaurant ? $restaurant->id : 'null' }};
            var storageKey = 'food_cart_' + restaurantId;

            function loadCart() {
                try {
                    var raw = localStorage.getItem(storageKey);
                    return raw ? JSON.parse(raw) : {};
                } catch (e) {
                    return {};
                }
            }

            function saveCart() {
                try {
                    localStorage.setItem(storageKey, JSON.stringify(cart));
                } catch (e) {}
            }

            var cart = loadCart();

            function esc(str) {
                return $('<div>').text(str == null ? '' : str).html();
            }

            function renderCart() {
                var $list = $('#cart-items');
                $list.empty();
                var ids = Object.keys(cart);
                if (ids.length === 0) {
                    $list.append('<li><div class="text-center py-4"><p class="content-color mb-0">Your cart is empty.</p></div></li>');
                    $('#cart-subtotal').text(SYMBOL + '0.00');
                    $('#cart-delivery').text(SYMBOL + '0.00');
                    $('#cart-tax').text(SYMBOL + '0.00');
                    $('#cart-total').text(SYMBOL + '0.00');
                    return;
                }
                var subtotal = 0;
                $.each(ids, function (i, id) {
                    var item = cart[id];
                    var lineTotal = (item.price || 0) * (item.qty || 1);
                    subtotal += lineTotal;
                    var html = ''
                        + '<div class="py-2 border-bottom">'
                        +   '<div class="d-flex align-items-start justify-content-between gap-2 mb-1">'
                        +     '<h6 class="fw-semibold text-dark mb-0 fs-14 text-truncate" style="max-width: 80%;" title="' + esc(item.name) + '">' + esc(item.name) + '</h6>'
                        +     '<a href="#!" class="cart-remove text-danger opacity-75 fs-15 lh-1 text-decoration-none" data-id="' + esc(id) + '" title="Remove item"><i class="ri-delete-bin-line"></i></a>'
                        +   '</div>'
                        +   '<div class="d-flex align-items-center justify-content-between mt-2">'
                        +     '<div class="d-flex align-items-center bg-light border rounded px-1" style="height: 28px;">'
                        +       '<button class="btn btn-link text-dark p-0 px-2 cart-minus text-decoration-none border-0" type="button" data-id="' + esc(id) + '" style="font-size: 14px; line-height: 1;"><i class="ri-subtract-line"></i></button>'
                        +       '<span class="fw-bold px-1 fs-12 text-dark">' + item.qty + '</span>'
                        +       '<button class="btn btn-link text-dark p-0 px-2 cart-plus text-decoration-none border-0" type="button" data-id="' + esc(id) + '" style="font-size: 14px; line-height: 1;"><i class="ri-add-line"></i></button>'
                        +     '</div>'
                        +     '<div class="text-end">'
                        +       '<span class="fw-bold text-dark fs-14">' + SYMBOL + lineTotal.toFixed(2) + '</span>'
                        +     '</div>'
                        +   '</div>'
                        + '</div>';
                    $list.append($('<li></li>').html(html));
                });
                var perKm = parseFloat('{{ $restaurant->delivery_charge_per_km ?? $deliveryChargePerKm ?? 20 }}');
                var deliveryCharge = 2 * perKm;
                var taxPercent = parseFloat('{{ $taxPercentage ?? 0 }}') || 0;
                var tax = subtotal * (taxPercent / 100);
                var total = subtotal + deliveryCharge + tax;
                $('#cart-subtotal').text(SYMBOL + subtotal.toFixed(2));
                $('#cart-delivery').text(SYMBOL + deliveryCharge.toFixed(2));
                $('#cart-tax').text(SYMBOL + tax.toFixed(2));
                $('#cart-total').text(SYMBOL + total.toFixed(2));
                saveCart();
            }

            function addToCart(id, name, price, img, desc) {
                id = String(id);
                if (cart[id]) {
                    cart[id].qty += 1;
                } else {
                    cart[id] = { id: id, name: name, price: price, img: img, desc: desc || '', qty: 1 };
                }
                renderCart();
                showToast(name);
            }

            function changeQty(id, delta) {
                id = String(id);
                if (!cart[id]) return;
                cart[id].qty += delta;
                if (cart[id].qty <= 0) {
                    delete cart[id];
                }
                renderCart();
            }

            function showToast(name) {
                if (typeof Swal === 'undefined') return;
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: name + ' added to cart',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: function (toast) {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
            }

            var customModalCurrentFood = null;

            $(document).on('click', '.add-btn[data-id]', function (e) {
                e.preventDefault();
                var $b = $(this);
                var hasVariants = $b.data('has-variants') == '1' || $b.data('has-variants') == 1;
                var variants = $b.data('variants');

                if (typeof variants === 'string') {
                    try { variants = JSON.parse(variants); } catch (err) { variants = []; }
                }

                if (hasVariants && Array.isArray(variants) && variants.length > 0) {
                    customModalCurrentFood = {
                        id: $b.data('id'),
                        name: $b.data('name'),
                        price: parseFloat($b.data('price') || 0),
                        img: $b.data('img'),
                        desc: $b.data('desc'),
                        variants: variants
                    };

                    $('#customized-food-title').text(customModalCurrentFood.name);

                    var $vList = $('#customized-variant-list');
                    $vList.empty();

                    variants.forEach(function(v, idx) {
                        var vPrice = parseFloat(v.sale_price || v.price || 0);
                        var isChecked = idx === 0 ? 'checked' : '';
                        var rowHtml = ''
                            + '<li>'
                            +   '<h6 class="product-size">' + esc(v.variant_name) + '</h6>'
                            +   '<div class="form-check product-price">'
                            +     '<label class="form-check-label" for="custom_v_' + v.id + '">' + SYMBOL + vPrice.toFixed(2) + '</label>'
                            +     '<input class="form-check-input" type="radio" name="customized_variant_choice" value="' + v.id + '" id="custom_v_' + v.id + '" data-name="' + esc(v.variant_name) + '" data-price="' + vPrice + '" ' + isChecked + '>'
                            +   '</div>'
                            + '</li>';
                        $vList.append(rowHtml);
                    });

                    var modalEl = document.getElementById('customized');
                    if (modalEl && window.bootstrap && bootstrap.Modal) {
                        var modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modalInstance.show();
                    } else {
                        $('#customized').modal('show');
                    }
                } else {
                    addToCart($b.data('id'), $b.data('name'), $b.data('price'), $b.data('img'), $b.data('desc'));
                }
            });

            // Handle apply button inside customized modal
            $('#customized-apply-btn').on('click', function(e) {
                e.preventDefault();
                if (!customModalCurrentFood) return;

                var $checked = $('input[name="customized_variant_choice"]:checked');
                if ($checked.length === 0) {
                    $checked = $('input[name="customized_variant_choice"]').first();
                }

                var vId = $checked.val();
                var vName = $checked.data('name');
                var vPrice = parseFloat($checked.data('price') || 0);

                var cartItemId = customModalCurrentFood.id + '-v-' + vId;
                var cartItemName = customModalCurrentFood.name + ' (' + vName + ')';

                addToCart(cartItemId, cartItemName, vPrice, customModalCurrentFood.img, customModalCurrentFood.desc);

                var modalEl = document.getElementById('customized');
                if (modalEl && window.bootstrap && bootstrap.Modal) {
                    var modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    } else {
                        $('#customized').modal('hide');
                    }
                } else {
                    $('#customized').modal('hide');
                }
            });

            $(document).on('click', '.cart-plus', function () {
                changeQty($(this).data('id'), 1);
            });

            $(document).on('click', '.cart-minus', function () {
                changeQty($(this).data('id'), -1);
            });

            $(document).on('click', '.cart-remove', function () {
                delete cart[String($(this).data('id'))];
                renderCart();
            });

            $('#proceed-to-checkout').on('click', function (e) {
                e.preventDefault();
                var $btn = $(this);
                
                // If cart is empty, do not proceed
                if (Object.keys(cart).length === 0) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Your cart is empty',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                    return;
                }

                $btn.prop('disabled', true);
                $btn.find('.btn-text').addClass('d-none');
                $btn.find('.btn-spinner').removeClass('d-none');
                window.location.href = "{{ route('checkout') }}";
            });

            renderCart();
        });
    </script>

    <script>
        $(function () {
            var SYMBOL = '₹';

            // Auto activate tab from URL query (?tab=overview) or hash (#overview)
            var urlParams = new URLSearchParams(window.location.search);
            var activeTabKey = urlParams.get('tab') || window.location.hash.replace('#', '');
            if (activeTabKey) {
                var $targetTabBtn = $('#myTab button[data-bs-target="#' + activeTabKey + '"], #' + activeTabKey + '-tab');
                if ($targetTabBtn.length && window.bootstrap && bootstrap.Tab) {
                    var tabInstance = new bootstrap.Tab($targetTabBtn[0]);
                    tabInstance.show();
                }
            }

            var selectedOfferId = null;

            function updateCoverChargeUI() {
                var $selectedOffer = $('input[name="dining_offer_id"]:checked');
                var coverCharge = 0;
                if ($selectedOffer.length) {
                    coverCharge = parseFloat($selectedOffer.data('cover-charge')) || 0;
                }
                var selectedGateway = $('input[name="payment_method"]:checked').closest('.gateway-card-tile').data('gateway-name') || 'Razorpay';

                if (coverCharge > 0) {
                    $('#coverChargeCard').addClass('has-charge');
                    $('#coverChargeIcon').removeClass('free').html('<i class="ri-shield-flash-fill"></i>');
                    $('#coverChargeTitle').text('Cover Charge Payment Required');
                    $('#coverChargeSub').text('Pay cover charge now to confirm booking. Adjustable against food bill.');
                    $('#coverChargeBadge').removeClass('free');
                    $('#coverChargeAmountDisplay').text(SYMBOL + coverCharge.toFixed(2));
                    $('#coverChargePaymentOptions').slideDown(200);
                    $('#bookSubmitBtnText').text('Pay ' + SYMBOL + coverCharge.toFixed(2) + ' via ' + selectedGateway + ' & Confirm Table');
                    $('#bookSubmitBtn i').attr('class', 'ri-secure-payment-fill me-2');
                } else {
                    $('#coverChargeCard').removeClass('has-charge');
                    $('#coverChargeIcon').addClass('free').html('<i class="ri-checkbox-circle-fill"></i>');
                    $('#coverChargeTitle').text('Cover Charge: Free');
                    $('#coverChargeSub').text('No advance cover charge required for this booking');
                    $('#coverChargeBadge').addClass('free');
                    $('#coverChargeAmountDisplay').text('Free');
                    $('#coverChargePaymentOptions').slideUp(200);
                    $('#bookSubmitBtnText').text('Confirm Table Booking (Free)');
                    $('#bookSubmitBtn i').attr('class', 'ri-calendar-check-line me-2');
                }
            }

            // Update Zomato 3-Dropdown Bar Labels
            function updateZomatoBarLabels() {
                // 1. Date label
                var dateVal = $('#bookDateInput').val();
                if (dateVal) {
                    var today = new Date().toISOString().split('T')[0];
                    var tomorrowDate = new Date();
                    tomorrowDate.setDate(tomorrowDate.getDate() + 1);
                    var tomorrow = tomorrowDate.toISOString().split('T')[0];

                    if (dateVal === today) {
                        $('#zomatoDateLabel').text('Today');
                    } else if (dateVal === tomorrow) {
                        $('#zomatoDateLabel').text('Tomorrow');
                    } else {
                        var d = new Date(dateVal + 'T00:00:00');
                        var options = { weekday: 'short', month: 'short', day: 'numeric' };
                        $('#zomatoDateLabel').text(d.toLocaleDateString('en-US', options));
                    }
                }

                // 2. Guests label
                var guestsVal = parseInt($('#bookGuestsSelect').val()) || 2;
                $('#zomatoGuestsLabel').text(guestsVal + (guestsVal === 1 ? ' guest' : ' guests'));

                // 3. Time / Meal label
                var timeVal = $('#bookTimeInput').val() || '19:00';
                var parts = timeVal.split(':');
                var hour = parseInt(parts[0]) || 19;
                var min = parts[1] || '00';
                var h12 = (hour % 12) || 12;
                var ampm = hour < 12 ? 'AM' : 'PM';
                var formattedTime = h12 + ':' + min + ' ' + ampm;
                var mealType = hour < 16 ? 'Lunch' : 'Dinner';

                $('#zomatoTimeLabel').text(mealType + ' (' + formattedTime + ')');
                $('#zomatoMealSelect').val(mealType.toLowerCase());
            }


            // Function to switch between Lunch & Dinner views
            function switchMeal(mealType) {
                if (!mealType) mealType = 'dinner';

                // 1. Update Meal Switcher Tabs UI
                $('.meal-tab-btn').removeClass('active');
                $('.meal-tab-btn[data-meal-type="' + mealType + '"]').addClass('active');

                // 2. Show only the active meal group and hide the other
                if (mealType === 'lunch') {
                    $('#lunchSlotGroup').removeClass('d-none');
                    $('#dinnerSlotGroup').addClass('d-none');

                    // If active slot is not in lunch, select first available lunch slot
                    var $activeInLunch = $('#lunchPillsContainer .slot-pill-btn.active');
                    if (!$activeInLunch.length || $activeInLunch.hasClass('disabled')) {
                        var $firstLunch = $('#lunchPillsContainer .slot-pill-btn:not(.disabled)').first();
                        if ($firstLunch.length) {
                            $('.slot-pill-btn').removeClass('active');
                            $firstLunch.addClass('active');
                            $('#bookTimeInput').val($firstLunch.data('time'));
                        }
                    }
                } else {
                    $('#dinnerSlotGroup').removeClass('d-none');
                    $('#lunchSlotGroup').addClass('d-none');

                    // If active slot is not in dinner, select first available dinner slot
                    var $activeInDinner = $('#dinnerPillsContainer .slot-pill-btn.active');
                    if (!$activeInDinner.length || $activeInDinner.hasClass('disabled')) {
                        var $firstDinner = $('#dinnerPillsContainer .slot-pill-btn:not(.disabled)').first();
                        if ($firstDinner.length) {
                            $('.slot-pill-btn').removeClass('active');
                            $firstDinner.addClass('active');
                            $('#bookTimeInput').val($firstDinner.data('time'));
                        }
                    }
                }

                // 3. Update top Zomato dropdown
                $('#zomatoMealSelect').val(mealType);

                // 4. Update top Zomato labels
                updateZomatoBarLabels();
            }

            // Clicking on a Meal Switcher Tab (Lunch / Dinner)
            $(document).on('click', '.meal-tab-btn', function () {
                var mealType = $(this).data('meal-type');
                switchMeal(mealType);
            });

            // Selecting meal from top Zomato dropdown
            $(document).on('change', '#zomatoMealSelect', function () {
                var mealType = $(this).val();
                switchMeal(mealType);
            });

            // Clicking a slot pill selects the time
            $(document).on('click', '.slot-pill-btn:not(.disabled)', function () {
                $('.slot-pill-btn').removeClass('active');
                $(this).addClass('active');
                var timeVal = $(this).data('time');
                $('#bookTimeInput').val(timeVal);
                updateZomatoBarLabels();
            });

            // Dynamically refresh slot availability when Date or Guests change
            function refreshSlotAvailability() {
                var dateVal = $('#bookDateInput').val();
                var guestsVal = $('#bookGuestsSelect').val() || 2;
                var restId = {{ $restaurant ? $restaurant->id : 'null' }};
                if (!restId || !dateVal) return;

                updateZomatoBarLabels();

                $.ajax({
                    url: '/dining-slots/' + restId,
                    type: 'GET',
                    data: { date: dateVal, guests: guestsVal },
                    success: function (res) {
                        if (res.success && res.slots && res.slots.length > 0) {
                            var currSelectedTime = $('#bookTimeInput').val();
                            var $lunchContainer = $('#lunchPillsContainer');
                            var $dinnerContainer = $('#dinnerPillsContainer');
                            $lunchContainer.empty();
                            $dinnerContainer.empty();

                            var lunchCount = 0;
                            var dinnerCount = 0;
                            var matchedCurrent = false;

                            $.each(res.slots, function (i, slot) {
                                var hour = parseInt(slot.time.split(':')[0]) || 0;
                                var isLunch = (slot.meal === 'lunch' || hour < 16);
                                var isFull = slot.status === 'full';
                                var isSelected = (slot.time === currSelectedTime && !isFull);
                                if (isSelected) matchedCurrent = true;

                                var badge = '';
                                if (isFull) {
                                    badge = '<span class="badge bg-danger ms-1 fs-10" style="font-size:10px;">Full</span>';
                                } else if (slot.status === 'limited') {
                                    badge = '<span class="badge bg-warning text-dark ms-1 fs-10" style="font-size:10px;">' + slot.available_count + ' left</span>';
                                }

                                var $btn = $('<button type="button" class="slot-pill-btn"></button>')
                                    .attr('data-time', slot.time)
                                    .attr('data-meal', isLunch ? 'lunch' : 'dinner')
                                    .html('<i class="ri-time-line"></i> ' + slot.display + badge);

                                if (isFull) {
                                    $btn.addClass('disabled').prop('disabled', true);
                                }

                                if (isLunch) {
                                    lunchCount++;
                                    $lunchContainer.append($btn);
                                } else {
                                    dinnerCount++;
                                    $dinnerContainer.append($btn);
                                }
                            });

                            // Rebuild Meal Tabs Nav dynamically
                            var $tabsNav = $('#mealTabsNav');
                            $tabsNav.empty();
                            if (lunchCount > 0) {
                                $tabsNav.append('<button type="button" class="meal-tab-btn" data-meal-type="lunch" id="lunchTabBtn"><i class="ri-sun-fill me-1"></i> Lunch</button>');
                            }
                            if (dinnerCount > 0) {
                                $tabsNav.append('<button type="button" class="meal-tab-btn" data-meal-type="dinner" id="dinnerTabBtn"><i class="ri-moon-clear-fill me-1"></i> Dinner</button>');
                            }

                            // Update Zomato Meal Dropdown dynamically
                            var $mealSelect = $('#zomatoMealSelect');
                            var prevMeal = $mealSelect.val() || (dinnerCount > 0 ? 'dinner' : 'lunch');
                            $mealSelect.empty();

                            if (lunchCount > 0) {
                                $mealSelect.append('<option value="lunch">Lunch</option>');
                            }
                            if (dinnerCount > 0) {
                                $mealSelect.append('<option value="dinner">Dinner</option>');
                            }

                            // Choose active meal
                            var targetMeal = prevMeal;
                            if (targetMeal === 'lunch' && lunchCount === 0 && dinnerCount > 0) targetMeal = 'dinner';
                            if (targetMeal === 'dinner' && dinnerCount === 0 && lunchCount > 0) targetMeal = 'lunch';

                            if (matchedCurrent) {
                                $('.slot-pill-btn[data-time="' + currSelectedTime + '"]').addClass('active');
                                var hour = parseInt(currSelectedTime.split(':')[0]) || 0;
                                targetMeal = (hour < 16) ? 'lunch' : 'dinner';
                            }

                            switchMeal(targetMeal);
                        }
                    }
                });
            }



            $(document).on('change', '#bookDateInput, #bookGuestsSelect', function () {
                refreshSlotAvailability();
            });

            // Initial bar sync
            updateZomatoBarLabels();


            // Trigger when selecting an offer in booking form
            $(document).on('change', 'input[name="dining_offer_id"]', function () {
                updateCoverChargeUI();
            });

            // Trigger when selecting gateway tile
            $(document).on('click', '.gateway-card-tile', function () {
                $('.gateway-card-tile').removeClass('active');
                $(this).addClass('active');
                $(this).find('input[type="radio"]').prop('checked', true);
                updateCoverChargeUI();
            });
            $(document).on('change', 'input[name="payment_method"]', function () {
                $('.gateway-card-tile').removeClass('active');
                $(this).closest('.gateway-card-tile').addClass('active');
                updateCoverChargeUI();
            });


            // Tap on any offer box in Overview tab to select it
            $(document).on('click', '.dining-offer-box', function () {
                $('.dining-offer-box').removeClass('is-primary active').addClass('is-secondary');
                $(this).removeClass('is-secondary').addClass('is-primary active');

                var disc = $(this).data('discount') || 'Offer Selected';
                selectedOfferId = $(this).data('offer-id');

                $('#reservationPillText').text(disc + ' selected for booking');

                if (selectedOfferId) {
                    $('input[name="dining_offer_id"][value="' + selectedOfferId + '"]').prop('checked', true);
                    updateCoverChargeUI();
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: disc + ' applied!',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                }
            });

            // Quick Book a table button from overview
            $(document).on('click', '#quickBookTableBtn', function (e) {
                e.preventDefault();
                var dateVal = $('#quickReservationDate').val();
                var guestsVal = $('#quickReservationGuests').val();

                var $bookTabBtn = $('#book-tab, #myTab button[data-bs-target="#book"]');
                if ($bookTabBtn.length && window.bootstrap && bootstrap.Tab) {
                    var tab = new bootstrap.Tab($bookTabBtn[0]);
                    tab.show();
                }

                if (dateVal) {
                    $('input[name="book_date"]').val(dateVal);
                }
                if (guestsVal) {
                    $('select[name="guests"]').val(guestsVal);
                }
                if (selectedOfferId) {
                    $('input[name="dining_offer_id"][value="' + selectedOfferId + '"]').prop('checked', true);
                }
                updateCoverChargeUI();

                $('html, body').animate({
                    scrollTop: $('#book').offset().top - 40
                }, 400);
            });

            // Copy Address Button
            $(document).on('click', '#copyAddressBtn', function (e) {
                e.preventDefault();
                var addr = $('#restaurantAddressText').text().trim();
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(addr);
                }
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Address copied to clipboard!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                }
            });

            // Activate the matching tab when a meta-action button is clicked
            $(document).on('click', '.meta-action[data-tab]', function (e) {
                e.preventDefault();
                var target = $(this).data('tab');
                var $btn = $('#myTab button[data-bs-target="#' + target + '"]');
                if ($btn.length && window.bootstrap && bootstrap.Tab) {
                    var tab = new bootstrap.Tab($btn[0]);
                    tab.show();
                    $('html, body').animate({
                        scrollTop: $('#myTab').offset().top - 20
                    }, 400);
                }
            });

            // Share: use native share if available, otherwise copy the link
            $(document).on('click', '.js-share', function (e) {
                e.preventDefault();
                var url = window.location.href;
                var shareData = {
                    title: document.title,
                    text: 'Check out this restaurant',
                    url: url
                };
                if (navigator.share) {
                    navigator.share(shareData).catch(function () {});
                    return;
                }
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(url).then(showShareToast).catch(showShareToast);
                } else {
                    showShareToast();
                }
                function showShareToast() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Link copied to clipboard',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                }
            });

            // Wishlist: toggle the restaurant in the customer's saved list
            $(document).on('click', '.like-btn[data-restaurant-id]', function (e) {
                e.preventDefault();
                var $btn = $(this);
                var restaurantId = $btn.data('restaurant-id');
                if (!restaurantId) return;

                if (typeof Swal === 'undefined') return;
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Please wait...',
                    showConfirmButton: false,
                    timer: 1000,
                    timerProgressBar: true,
                    didOpen: function (toast) { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                });

                $.ajax({
                    url: "{{ route('wishlist.store') }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        restaurant_id: restaurantId
                    },
                    success: function (res) {
                        if (res.success) {
                            $btn.toggleClass('inactive active');
                            if (res.added) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: res.message,
                                    showConfirmButton: false,
                                    timer: 1200,
                                    timerProgressBar: true,
                                    didOpen: function (toast) { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                                });
                                setTimeout(function () {
                                    window.location.href = "{{ route('wish.list') }}";
                                }, 1200);
                            } else {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'info',
                                    title: res.message,
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    didOpen: function (toast) { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                                });
                            }
                        }
                    },
                    error: function () {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Could not update wishlist. Please login.',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });
                    }
                });
            });

            // Book a table (stored in database with Razorpay cover charge payment)
            $(document).on('submit', '#bookTableForm', function (e) {
                e.preventDefault();

                const $form = $(this);
                const $feedback = $('#bookingFeedback');
                const $submit = $('#bookSubmitBtn');
                var $selectedOffer = $('input[name="dining_offer_id"]:checked');
                var coverCharge = $selectedOffer.length ? (parseFloat($selectedOffer.data('cover-charge')) || 0) : 0;
                var $checkedGateway = $('input[name="payment_method"]:checked');
                var paymentMethod = $checkedGateway.val() || 'razorpay';
                var gatewayName = $checkedGateway.closest('.gateway-card-tile').data('gateway-name') || 'Razorpay';
                var restId = $('input[name="restaurant_id"]').val() || restaurantId;
                var customerName = $('input[name="customer_name"]').val();
                var customerPhone = $('input[name="phone"]').val();

                // Cover charge payment via Razorpay
                if (coverCharge > 0) {
                    $submit.prop('disabled', true);

                    Swal.fire({
                        title: 'Connecting to ' + gatewayName + '...',
                        html: 'Initializing secure cover charge payment of <b>' + SYMBOL + coverCharge.toFixed(2) + '</b>...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: function () {
                            Swal.showLoading();
                        }
                    });

                    // Step 1: Create Razorpay Order
                    $.ajax({
                        url: '{{ route('razorpay.create-order') }}',
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            restaurant_id: restId,
                            amount: coverCharge
                        },
                        success: function (rzpData) {
                            Swal.close();

                            var options = {
                                "key": rzpData.key || "rzp_test_TWIAoYszpcVthe",
                                "amount": rzpData.amount,
                                "currency": rzpData.currency || "INR",
                                "name": rzpData.restaurant_name || "Food Management",
                                "description": "Cover Charge - Table Booking",
                                "image": "{{ $restaurant && $restaurant->logo ? asset($restaurant->logo) : asset('front/assets/images/logo/logo.png') }}",
                                "order_id": rzpData.order_id || undefined,
                                "prefill": {
                                    "name": customerName,
                                    "contact": customerPhone,
                                    "email": "{{ auth()->user()?->email ?? '' }}"
                                },
                                "theme": {
                                    "color": rzpData.theme_color || "#072654"
                                },

                                "handler": function (response) {
                                    // Step 2: Payment Received -> Save Booking with Payment ID
                                    Swal.fire({
                                        title: 'Confirming Reservation...',
                                        html: 'Verifying payment <b>' + response.razorpay_payment_id + '</b>...',
                                        allowOutsideClick: false,
                                        showConfirmButton: false,
                                        didOpen: function () {
                                            Swal.showLoading();
                                        }
                                    });

                                    var formData = $form.serializeArray();
                                    formData.push({ name: 'razorpay_payment_id', value: response.razorpay_payment_id });
                                    if (response.razorpay_order_id) {
                                        formData.push({ name: 'razorpay_order_id', value: response.razorpay_order_id });
                                    }
                                    if (response.razorpay_signature) {
                                        formData.push({ name: 'razorpay_signature', value: response.razorpay_signature });
                                    }

                                    $.ajax({
                                        url: '{{ route('booking.store') }}',
                                        method: 'POST',
                                        data: $.param(formData),
                                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                        success: function (res) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Table Booked & Paid!',
                                                html: '<div class="text-center">'
                                                    + '<div class="badge bg-success mb-2 px-3 py-2 fs-14">✓ Payment Verified</div>'
                                                    + '<p class="text-success fw-bold fs-6 mb-2">Cover Charge of ' + SYMBOL + coverCharge.toFixed(2) + ' Received via ' + gatewayName + '!</p>'
                                                    + '<p class="text-muted small mb-3">' + res.message + '</p>'
                                                    + '<div class="p-3 bg-light rounded text-start fs-13 border">'
                                                    + '<div class="mb-1"><b>Booking ID:</b> #' + res.booking_id + '</div>'
                                                    + '<div class="mb-1"><b>Payment ID:</b> <span class="badge bg-dark font-monospace">' + response.razorpay_payment_id + '</span></div>'
                                                    + '<div class="mb-1"><b>Status:</b> <span class="badge bg-success">Confirmed</span></div>'
                                                    + '<div><b>Cover Charge:</b> ' + SYMBOL + parseFloat(res.cover_charge).toFixed(2) + ' <span class="text-muted">(Adjustable in final bill)</span></div>'
                                                    + '</div>'
                                                    + '</div>',
                                                confirmButtonColor: '#fc8019'
                                            });
                                            $form[0].reset();
                                            updateCoverChargeUI();
                                            updateZomatoBarLabels();
                                            refreshSlotAvailability();
                                        },
                                        error: function (xhr) {
                                            let msg = 'Payment was successful (' + response.razorpay_payment_id + '), but saving the booking failed. Please contact restaurant.';
                                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                                msg = xhr.responseJSON.message;
                                            }
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Booking Error',
                                                text: msg,
                                                confirmButtonColor: '#cb202d'
                                            });
                                        },
                                        complete: function () {
                                            $submit.prop('disabled', false).html('<i class="ri-calendar-check-line me-2"></i><span id="bookSubmitBtnText">Request Booking</span>');
                                            updateCoverChargeUI();
                                        }
                                    });
                                },
                                "modal": {
                                    "ondismiss": function () {
                                        $submit.prop('disabled', false);
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Payment Incomplete',
                                            text: 'You closed the ' + gatewayName + ' payment window before completing payment.',
                                            confirmButtonColor: '#fc8019'
                                        });
                                    }
                                }
                            };

                            var rzp = new Razorpay(options);
                            rzp.on('payment.failed', function (resp) {
                                $submit.prop('disabled', false);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Payment Failed',
                                    text: resp.error.description || 'Payment was declined or cancelled.',
                                    confirmButtonColor: '#cb202d'
                                });
                            });
                            rzp.open();
                        },
                        error: function (xhr) {
                            $submit.prop('disabled', false);
                            Swal.fire({
                                icon: 'error',
                                title: 'Could not connect to payment gateway',
                                text: 'Please check your internet connection or try again.',
                                confirmButtonColor: '#cb202d'
                            });
                        }
                    });

                } else {
                    // Free Table Reservation (No Cover Charge)
                    $submit.prop('disabled', true).html('<i class="ri-loader-line me-2 spin"></i>Submitting...');

                    $.ajax({
                        url: '{{ route('booking.store') }}',
                        method: 'POST',
                        data: $form.serialize(),
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Table Booking Requested!',
                                text: res.message,
                                confirmButtonColor: '#fc8019'
                            });
                            $form[0].reset();
                            updateCoverChargeUI();
                            updateZomatoBarLabels();
                            refreshSlotAvailability();
                        },
                        error: function (xhr) {
                            let msg = 'Something went wrong. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                if (xhr.responseJSON.errors) {
                                    const first = Object.values(xhr.responseJSON.errors)[0];
                                    msg = Array.isArray(first) ? first[0] : first;
                                } else {
                                    msg = xhr.responseJSON.message;
                                }
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Booking Failed',
                                text: msg
                            });
                        },
                        complete: function () {
                            $submit.prop('disabled', false).html('<i class="ri-calendar-check-line me-2"></i><span id="bookSubmitBtnText">Request Booking</span>');
                            updateCoverChargeUI();
                        }
                    });
                }

                function showBookingFeedback(text, type) {
                    $feedback.removeClass('is-success is-danger')
                        .addClass(type === 'success' ? 'is-success' : 'is-danger')
                        .html('<i class="ri-' + (type === 'success' ? 'check' : 'close') + '-circle-line me-1"></i>' + text)
                        .stop(true, true)
                        .slideDown();
                    $('html, body').animate({ scrollTop: $feedback.offset().top - 120 }, 300);
                    if (type === 'success') {
                        setTimeout(function () { $feedback.slideUp(); }, 6000);
                    }
                }
            });


            // Initial UI update on page ready
            updateCoverChargeUI();
            updateZomatoBarLabels();
            refreshSlotAvailability();
        });
    </script>
@endpush

<!-- Customized Modal (Theme Native) -->
<div class="modal customized-modal fade" id="customized" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="container">
                <div class="filter-header">
                    <h5 class="title" id="customized-food-title">Custom Food</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="filter-body">
                    <div class="filter-title">
                        <h5 class="fw-medium dark-text">Choose Size</h5>
                    </div>
                    <ul class="filter-list border-0" id="customized-variant-list">
                        <!-- Dynamic Variant Rows injected here -->
                    </ul>
                </div>
                <div class="filter-footer">
                    <button type="button" class="btn theme-btn add-btn w-100 mt-0" id="customized-apply-btn">Apply</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection



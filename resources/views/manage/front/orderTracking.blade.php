@extends('layouts.front.main')
<style>
    /* =========================================
   ORDER TRACKING
========================================= */

.tracking-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
}

/* Header */

.tracking-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding-bottom: 22px;
    border-bottom: 1px solid #f0f0f0;
}

.tracking-header h4 {
    font-size: 20px;
    font-weight: 700;
    color: #222;
}

.tracking-header p {
    font-size: 13px;
}

/* Status Badge */

.tracking-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 13px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

.tracking-badge.completed {
    background: #e8f8ef;
    color: #1a9b55;
}

.tracking-badge.processing {
    background: #fff5df;
    color: #d58a00;
}

.tracking-badge.rejected {
    background: #ffe8e8;
    color: #dc3545;
}

/* Progress */

.tracking-progress {
    padding: 22px 0 28px;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 9px;
    font-size: 13px;
    color: #777;
}

.progress-info strong {
    color: #222;
}

.tracking-progress .progress {
    height: 7px;
    background: #f0f1f3;
    border-radius: 20px;
    overflow: hidden;
}

.tracking-progress .progress-bar {
    border-radius: 20px;
    background: linear-gradient(90deg, #ff6b35, #ff9f43);
    transition: width .5s ease;
}

/* Timeline */

.order-timeline {
    position: relative;
    padding-top: 5px;
}

.timeline-item {
    position: relative;
    display: flex;
    gap: 18px;
    min-height: 95px;
}

.timeline-icon {
    width: 44px;
    height: 44px;
    min-width: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    z-index: 2;
    font-size: 19px;
    border: 4px solid #fff;
}

/* Timeline line */

.timeline-line {
    position: absolute;
    left: 21px;
    top: 44px;
    width: 2px;
    height: calc(100% - 25px);
    background: #e8e8e8;
    z-index: 1;
}

/* Completed */

.timeline-item.is-done .timeline-icon {
    background: #e9f8ef;
    color: #18a558;
    box-shadow: 0 0 0 4px #f4fcf7;
}

.timeline-item.is-done .timeline-line {
    background: #20b15a;
}

.timeline-item.is-done h5 {
    color: #222;
}

/* Active */

.timeline-item.is-active .timeline-icon {
    background: #fff1e9;
    color: #ff6b35;
    box-shadow: 0 0 0 5px #fff7f3;
    animation: trackingPulse 1.8s infinite;
}

.timeline-item.is-active .timeline-line {
    background: linear-gradient(
        to bottom,
        #ff6b35 0%,
        #e8e8e8 100%
    );
}

.timeline-item.is-active h5 {
    color: #ff6b35;
}

/* Pending */

.timeline-item.is-pending .timeline-icon {
    background: #f5f6f7;
    color: #aaa;
}

.timeline-item.is-pending h5 {
    color: #999;
}

/* Content */

.timeline-content {
    flex: 1;
    padding: 2px 0 28px;
}

.timeline-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 15px;
}

.timeline-content h5 {
    margin: 3px 0 8px;
    font-size: 15px;
    font-weight: 650;
}

.step-number {
    display: block;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .7px;
    color: #999;
}

/* Step Status */

.step-status {
    font-size: 11px;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 20px;
    white-space: nowrap;
}

.step-status.done {
    background: #eaf8ef;
    color: #159447;
}

.step-status.active {
    background: #fff0e8;
    color: #ff6b35;
}

.step-status.pending {
    background: #f3f3f3;
    color: #999;
}

/* Time */

.timeline-time {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #888;
}

.timeline-time i {
    font-size: 14px;
}

.time-divider {
    color: #ccc;
    margin: 0 2px;
}

/* Last Item */

.timeline-item.is-last {
    min-height: auto;
}

.timeline-item.is-last .timeline-content {
    padding-bottom: 0;
}

/* Animation */

@keyframes trackingPulse {
    0% {
        box-shadow: 0 0 0 4px rgba(255, 107, 53, .15);
    }

    50% {
        box-shadow: 0 0 0 9px rgba(255, 107, 53, .05);
    }

    100% {
        box-shadow: 0 0 0 4px rgba(255, 107, 53, .15);
    }
}

/* Empty State */

.empty-tracking {
    text-align: center;
    padding: 60px 20px;
    background: #fff;
    border: 1px dashed #ddd;
    border-radius: 18px;
}

.empty-tracking-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #fff3ed;
    color: #ff6b35;
    font-size: 32px;
}

.empty-tracking h5 {
    font-size: 17px;
    font-weight: 650;
    margin-bottom: 7px;
}

.empty-tracking p {
    margin: 0;
    color: #999;
    font-size: 13px;
}

/* Mobile */

@media (max-width: 575px) {

    .tracking-card {
        padding: 18px;
        border-radius: 14px;
    }

    .tracking-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .timeline-top {
        flex-direction: column;
        gap: 6px;
    }

    .step-status {
        align-self: flex-start;
    }

    .timeline-item {
        gap: 12px;
    }

    .timeline-icon {
        width: 38px;
        height: 38px;
        min-width: 38px;
        font-size: 16px;
    }

    .timeline-line {
        left: 18px;
        top: 38px;
    }

    .timeline-content h5 {
        font-size: 14px;
    }

    .timeline-time {
        flex-wrap: wrap;
    }
}
</style>
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Order Tracking</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Order Tracking
                    </li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    @php
        $order = $order ?? null;
        $doneMap = [
            \App\Models\Order::STATUS_PENDING => 1,
            \App\Models\Order::STATUS_ACCEPTED => 2,
            \App\Models\Order::STATUS_PREPARING => 3,
            \App\Models\Order::STATUS_READY => 4,
            \App\Models\Order::STATUS_ASSIGNED => 4,
            \App\Models\Order::STATUS_PICKED_UP => 5,
            \App\Models\Order::STATUS_OUT_FOR_DELIVERY => 6,
            \App\Models\Order::STATUS_DELIVERED => 7,
            \App\Models\Order::STATUS_COMPLETED => 7,
        ];
        $steps = [
            ['label' => 'Order Placed', 'time' => $order?->created_at],
            ['label' => 'Order Accepted', 'time' => $order?->accepted_at],
            ['label' => 'Preparing Food', 'time' => $order?->preparing_at],
            ['label' => 'Food is Ready', 'time' => $order?->ready_at],
            ['label' => 'Picked Up', 'time' => $order?->picked_up_at],
            ['label' => 'Out for Delivery', 'time' => $order?->out_for_delivery_at],
            ['label' => 'Delivered', 'time' => $order?->delivered_at],
        ];
        $doneCount = $order ? ($doneMap[$order->status] ?? 0) : 0;
        $isRejected = $order && in_array($order->status, ['rejected', 'cancelled']);
    @endphp

    <!-- order tracking section starts -->
    <section class="section-b-space">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-xl-7">
                    <div class="delivery-root">
                        <div class="map" id="map"></div>
                    </div>
                </div>
                <div class="col-xl-5">
                    <div class="order-tracking-content">
                        <ul class="nav nav-tabs tab-style3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="status-tab" data-bs-toggle="tab"
                                    data-bs-target="#status-tab-pane" type="button" role="tab">
                                    Order Status
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="details" data-bs-toggle="tab"
                                    data-bs-target="#details-pane" type="button" role="tab">
                                    Order Details
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tracking-tab" data-bs-toggle="tab"
                                    data-bs-target="#tracking-pane" type="button" role="tab">
                                    Order Tracking
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="status-tab-pane" role="tabpanel" tabindex="0">
                                <div class="order-status-content">
                                    @if ($order)
                                        <div class="driver-details">
                                            <h4>Drivers Information</h4>
                                            <div class="driver-details-box">
                                                <div class="driver-image">
                                                    <img class="img-fluid img"
                                                        src="{{ asset('front/assets/images/icons/p1.png') }}" alt="driver">
                                                </div>
                                                <div class="driver-content">
                                                    <div class="driver-info">
                                                        <h6>Driver Name :</h6>
                                                        <h5>{{ $order->deliveryPartner?->name ?? 'Not assigned yet' }}</h5>
                                                    </div>
                                                    <div class="driver-info">
                                                        <h6>Phone :</h6>
                                                        <h5>{{ $order->deliveryPartner?->phone ?? '-' }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($isRejected)
                                            <div class="alert alert-danger mt-3 mb-0">
                                                <i class="ri-close-circle-line me-1"></i>
                                                {{ $order->cancel_reason ?: 'Your order has been cancelled.' }}
                                            </div>
                                        @endif

                                        <div class="shipping-details">
                                            <h4>Shipping Details</h4>
                                            <ul class="delivery-list">
                                                <li>
                                                    <div class="order-address">
                                                        <img class="img-fluid place-icon"
                                                            src="{{ asset('front/assets/images/svg/driver.svg') }}"
                                                            alt="delivery">
                                                        <div>
                                                            <h5>Driver position</h5>
                                                            <h6 class="delivery-place">{{ \App\Models\Order::statusLabel($order->status) }}</h6>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="order-address">
                                                        <img class="img-fluid place-icon"
                                                            src="{{ asset('front/assets/images/svg/placed.svg') }}"
                                                            alt="restaurant">
                                                        <div>
                                                            <h5>Restaurant Address</h5>
                                                            <h6 class="delivery-place">
                                                                {{ $order->restaurant?->restaurant_name ?? '-' }}{{ $order->restaurant?->address ? ', ' . $order->restaurant->address : '' }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="order-address">
                                                        <img class="img-fluid place-icon"
                                                            src="{{ asset('front/assets/images/svg/user-map.svg') }}"
                                                            alt="delivery">
                                                        <div>
                                                            <h5>Delivery Address</h5>
                                                            <h6 class="delivery-place">
                                                                {{ $order->address ? $order->address->address . ', ' . $order->address->city . ', ' . $order->address->country . ' ' . $order->address->zip : 'Address not available.' }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>

                                        @if ($order->status === \App\Models\Order::STATUS_DELIVERED && !$order->review)
                                            <div class="shipping-details review-box">
                                                <h4>Rate Your Experience</h4>
                                                <form method="POST" action="{{ route('review.store') }}">
                                                    @csrf
                                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label">Restaurant</label>
                                                            <select name="restaurant_rating" class="form-select" required>
                                                                <option value="">Select</option>
                                                                @for ($r = 5; $r >= 1; $r--)
                                                                    <option value="{{ $r }}">{{ $r }} star{{ $r > 1 ? 's' : '' }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Food</label>
                                                            <select name="food_rating" class="form-select" required>
                                                                <option value="">Select</option>
                                                                @for ($r = 5; $r >= 1; $r--)
                                                                    <option value="{{ $r }}">{{ $r }} star{{ $r > 1 ? 's' : '' }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label">Delivery</label>
                                                            <select name="delivery_rating" class="form-select" required>
                                                                <option value="">Select</option>
                                                                @for ($r = 5; $r >= 1; $r--)
                                                                    <option value="{{ $r }}">{{ $r }} star{{ $r > 1 ? 's' : '' }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label">Comment</label>
                                                            <textarea name="comment" class="form-control" rows="3"
                                                                placeholder="Good food and fast delivery..."></textarea>
                                                        </div>
                                                        <div class="col-12 text-end">
                                                            <button type="submit" class="btn theme-btn">Submit Review</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif

                                        @if ($order->review)
                                            <div class="shipping-details review-box">
                                                <h4>Your Review</h4>
                                                <div class="d-flex flex-wrap gap-3 mb-2">
                                                    <span><i class="ri-star-fill theme-color"></i> Restaurant: <strong>{{ $order->review->restaurant_rating ?? '-' }}/5</strong></span>
                                                    <span><i class="ri-star-fill theme-color"></i> Food: <strong>{{ $order->review->food_rating ?? '-' }}/5</strong></span>
                                                    <span><i class="ri-star-fill theme-color"></i> Delivery: <strong>{{ $order->review->delivery_rating ?? '-' }}/5</strong></span>
                                                </div>
                                                @if ($order->review->comment)
                                                    <p class="content-color mb-0">"{{ $order->review->comment }}"</p>
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center py-5">
                                            <i class="ri-shopping-bag-3-line display-4 d-block mb-2 content-color"></i>
                                            <h4 class="mb-2">No orders yet</h4>
                                            <p class="content-color">You haven't placed any orders yet.</p>
                                            <a href="{{ route('index') }}" class="btn theme-btn mt-0">START ORDERING</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade" id="details-pane" role="tabpanel" tabindex="0">
                                <div class="order-details-content">
                                    @if ($order)
                                        <div class="layout-sec">
                                            <div class="order-summery-section sticky-top">
                                                <div class="checkout-detail">
                                                    <div class="cart-address-box">
                                                        <div class="add-img">
                                                            <img class="img-fluid img sm-size"
                                                                src="{{ asset('front/assets/images/svg/location.svg') }}"
                                                                alt="location">
                                                        </div>
                                                        <div class="add-content">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h5 class="dark-text deliver-place">
                                                                    Deliver to : {{ $order->address ? ucfirst($order->address->label ?: 'Home') : 'Address' }}
                                                                    <i class="ri-check-line"></i>
                                                                </h5>
                                                            </div>
                                                            <h6 class="address mt-sm-2 mt-1 content-color">
                                                                {{ $order->address ? $order->address->address . ', ' . $order->address->city . ', ' . $order->address->country . ' ' . $order->address->zip : 'Address not available.' }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                                    <div class="cart-address-box mt-3">
                                                        <div class="add-img">
                                                            <img class="img-fluid img sm-size"
                                                                src="{{ asset('front/assets/images/svg/wallet-add.svg') }}"
                                                                alt="payment">
                                                        </div>
                                                        <div class="add-content">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <h5 class="dark-text deliver-place">
                                                                    Payment Method: <i class="ri-check-line"></i>
                                                                </h5>
                                                            </div>
                                                            <h6 class="address mt-sm-2 mt-1 content-color">
                                                                {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                                                                ({{ ucfirst($order->payment_status) }})
                                                            </h6>
                                                        </div>
                                                    </div>
                                                    <ul>
                                                        @foreach ($order->items as $item)
                                                            <li>
                                                                <div class="horizontal-product-box">
                                                                    <div class="product-content">
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <h5>{{ $item->name }} <span class="content-color">× {{ $item->qty }}</span></h5>
                                                                            <h6 class="product-price">{{ $currencySymbol }}{{ number_format($item->price, 2) }}</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    <h5 class="bill-details-title fw-semibold dark-text">Bill Details</h5>
                                                    <div class="sub-total">
                                                        <h6 class="content-color fw-normal">Sub Total</h6>
                                                        <h6 class="fw-semibold">{{ $currencySymbol }}{{ number_format($order->subtotal, 2) }}</h6>
                                                    </div>
                                                    <div class="sub-total">
                                                        <h6 class="content-color fw-normal">Delivery Charge</h6>
                                                        <h6 class="fw-semibold success-color">
                                                            {{ $order->delivery_charge > 0 ? $currencySymbol . number_format($order->delivery_charge, 2) : 'Free' }}
                                                        </h6>
                                                    </div>
                                                    <div class="sub-total">
                                                        <h6 class="content-color fw-normal">Discount (10%)</h6>
                                                        <h6 class="fw-semibold">{{ $currencySymbol }}{{ number_format($order->discount, 2) }}</h6>
                                                    </div>
                                                    @if($order->promo_discount > 0)
                                                        <div class="sub-total">
                                                            <h6 class="content-color fw-normal">
                                                                Promo Discount
                                                                @if($order->promo_code)
                                                                    <span class="badge ms-1" style="background:#e8f8ee;color:#1f9d4d;font-family:monospace;">{{ $order->promo_code }}</span>
                                                                @endif
                                                            </h6>
                                                            <h6 class="fw-semibold success-color">-{{ $currencySymbol }}{{ number_format($order->promo_discount, 2) }}</h6>
                                                        </div>
                                                    @endif
                                                    <div class="sub-total">
                                                        <h6 class="content-color fw-normal">Tax ({{ $taxGst ?: $taxPercentage . '%' }})</h6>
                                                        <h6 class="fw-semibold">{{ $currencySymbol }}{{ number_format($order->tax, 2) }}</h6>
                                                    </div>
                                                    <div class="grand-total">
                                                        <h6 class="fw-semibold dark-text">Total</h6>
                                                        <h6 class="fw-semibold amount">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</h6>
                                                    </div>
                                                    <img class="dots-design"
                                                        src="{{ asset('front/assets/images/svg/dots-design.svg') }}"
                                                        alt="dots">
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <p class="content-color mb-0">No order details available.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
<div class="tab-pane fade" id="tracking-pane" role="tabpanel" tabindex="0">
    <div class="order-status-content">

        @if ($order)

            <div class="tracking-card">

                {{-- Header --}}
                <div class="tracking-header">
                    <div>
                        <h4 class="mb-1">Order Tracking</h4>
                        <p class="mb-0 text-muted">
                            Track your order status in real time
                        </p>
                    </div>

                    @if ($isRejected)
                        <span class="tracking-badge rejected">
                            <i class="ri-close-circle-line"></i>
                            Rejected
                        </span>
                    @elseif ($doneCount >= count($steps))
                        <span class="tracking-badge completed">
                            <i class="ri-checkbox-circle-line"></i>
                            Completed
                        </span>
                    @else
                        <span class="tracking-badge processing">
                            <i class="ri-loader-4-line"></i>
                            Processing
                        </span>
                    @endif
                </div>

                {{-- Progress --}}
                @php
                    $totalSteps = count($steps);
                    $progress = $totalSteps > 0
                        ? min(100, ($doneCount / $totalSteps) * 100)
                        : 0;
                @endphp

                <div class="tracking-progress">
                    <div class="progress-info">
                        <span>Order Progress</span>
                        <strong>{{ round($progress) }}%</strong>
                    </div>

                    <div class="progress">
                        <div
                            class="progress-bar"
                            role="progressbar"
                            style="width: {{ $progress }}%">
                        </div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="order-timeline">

                    @foreach ($steps as $i => $step)

                        @php
                            $stepNumber = $i + 1;

                            $isDone = $stepNumber <= $doneCount;

                            $isActive =
                                $stepNumber === $doneCount + 1 &&
                                !$isRejected;

                            $isPending =
                                !$isDone &&
                                !$isActive;

                            $isLast = $i === count($steps) - 1;
                        @endphp

                        <div class="timeline-item
                            {{ $isDone ? 'is-done' : '' }}
                            {{ $isActive ? 'is-active' : '' }}
                            {{ $isPending ? 'is-pending' : '' }}
                            {{ $isLast ? 'is-last' : '' }}">

                            {{-- Timeline Line --}}
                            @if (!$isLast)
                                <div class="timeline-line"></div>
                            @endif

                            {{-- Icon --}}
                            <div class="timeline-icon">

                                @if ($isDone)
                                    <i class="ri-check-line"></i>
                                @elseif ($isActive)
                                    <i class="ri-loader-4-line"></i>
                                @else
                                    <i class="ri-time-line"></i>
                                @endif

                            </div>

                            {{-- Content --}}
                            <div class="timeline-content">

                                <div class="timeline-top">

                                    <div>
                                        <span class="step-number">
                                            Step {{ $stepNumber }}
                                        </span>

                                        <h5>{{ $step['label'] }}</h5>
                                    </div>

                                    @if ($isDone)
                                        <span class="step-status done">
                                            Completed
                                        </span>
                                    @elseif ($isActive)
                                        <span class="step-status active">
                                            In Progress
                                        </span>
                                    @else
                                        <span class="step-status pending">
                                            Pending
                                        </span>
                                    @endif

                                </div>

                                <div class="timeline-time">

                                    @if ($step['time'])

                                        <i class="ri-calendar-check-line"></i>

                                        <span>
                                            {{ $step['time']->format('d M Y') }}
                                        </span>

                                        <span class="time-divider">•</span>

                                        <i class="ri-time-line"></i>

                                        <span>
                                            {{ $step['time']->format('h:i A') }}
                                        </span>

                                    @else

                                        <i class="ri-time-line"></i>
                                        <span>Waiting for update</span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @else

            <div class="empty-tracking">

                <div class="empty-tracking-icon">
                    <i class="ri-map-pin-time-line"></i>
                </div>

                <h5>No Order Tracking Available</h5>

                <p>
                    Tracking information will appear here once your order
                    has been placed.
                </p>

            </div>

        @endif

    </div>
</div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- order tracking section end -->

@push('styles')
    <style>
        #map {
            background-color: #eef1f4;
            background-image: url('{{ asset('front/assets/images/svg/driver.svg') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 48px;
        }
        .status-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .status-list li {
            position: relative;
            padding: 0 0 26px 56px;
        }
        .status-list li::before {
            content: '';
            position: absolute;
            left: 21px;
            top: 38px;
            bottom: -8px;
            width: 3px;
            border-radius: 3px;
            background: #e9edf1;
        }
        .status-list li.done::before {
            background: #34c759;
        }
        .status-list li:last-child::before {
            display: none;
        }
        .status-list .order-address {
            display: block;
        }
        .status-list .status-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            border: 2px solid #e2e5e9;
            color: #9aa3ad;
            font-size: 18px;
            transition: all 0.3s ease;
        }
        .status-list li.done .status-icon {
            background: linear-gradient(135deg, #34c759, #2ba842);
            border-color: #2ba842;
            color: #fff;
            box-shadow: 0 4px 12px rgba(43, 168, 66, 0.35);
        }
        .status-list li.active-step .status-icon {
            background: linear-gradient(135deg, #ff5a5f, #cb202d);
            border-color: #cb202d;
            color: #fff;
            box-shadow: 0 4px 12px rgba(203, 32, 45, 0.35);
            animation: status-pulse 1.8s infinite;
        }
        @keyframes status-pulse {
            0% { box-shadow: 0 0 0 0 rgba(203, 32, 45, 0.45); }
            70% { box-shadow: 0 0 0 12px rgba(203, 32, 45, 0); }
            100% { box-shadow: 0 0 0 0 rgba(203, 32, 45, 0); }
        }
        .status-list h5 {
            font-size: 15px;
            font-weight: 600;
            color: #232323;
            margin: 0 0 4px;
            line-height: 1.3;
        }
        .status-list li.done h5 {
            color: #1f7a2d;
        }
        .status-list li.active-step h5 {
            color: #cb202d;
        }
        .status-list .delivery-place {
            font-size: 13px;
            color: #7a7a7a;
            font-weight: 400;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .status-list li.done .delivery-place {
            color: #3f9d4a;
        }
        .status-list li.pending-step .delivery-place {
            color: #9aa3ad;
        }
    </style>
@endpush
@endsection
@extends('layouts.restaurant.main')

@section('title', 'Order #' . $order->id . ' - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Order #{{ $order->id }}</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.orders.index') }}">Orders</a></li>
                <li class="breadcrumb-item">#{{ $order->id }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <a href="{{ route('restaurant.orders.index') }}" class="btn btn-light border">
                <i class="feather-arrow-left me-1"></i> Back to Orders
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Status Banner -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-text avatar-lg bg-light text-secondary rounded-circle">
                        <i class="feather-shopping-cart fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">Order #{{ $order->id }}</h4>
                        <span class="{{ \App\Models\Order::statusBadge($order->status) }} fs-12">
                            {{ \App\Models\Order::statusLabel($order->status) }}
                        </span>
                    </div>
                </div>

                @if ($order->isActive())
                    <div class="d-flex flex-wrap gap-2">
                        @if ($order->status === 'pending')
                            <form method="POST" action="{{ route('restaurant.orders.reject', $order->id) }}" class="action-form" data-action="Rejecting...">
                                @csrf
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Reject this order?')">Reject</button>
                            </form>
                            <form method="POST" action="{{ route('restaurant.orders.accept', $order->id) }}" class="action-form" data-action="Accepting...">
                                @csrf
                                <button type="submit" class="btn btn-success">Accept</button>
                            </form>
                        @elseif ($order->status === 'accepted')
                            <form method="POST" action="{{ route('restaurant.orders.preparing', $order->id) }}" class="action-form" data-action="Updating...">
                                @csrf
                                <button type="submit" class="btn btn-primary">Start Preparing</button>
                            </form>
                        @elseif ($order->status === 'preparing')
                            <form method="POST" action="{{ route('restaurant.orders.ready', $order->id) }}" class="action-form" data-action="Updating...">
                                @csrf
                                <button type="submit" class="btn btn-success">Mark Ready</button>
                            </form>
                        @elseif ($order->status === 'ready' && !$order->delivery_partner_id)
                            <form method="POST" action="{{ route('restaurant.orders.send-request', $order->id) }}" class="action-form" data-action="Assigning...">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="feather-user-check me-1"></i>Auto Assign Partner
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="row g-4">
            <!-- Customer & Delivery Info -->
            <div class="col-xl-4">
                <div class="card stretch stretch-full mb-4">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="feather-map-pin me-2"></i>Delivery Details</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-4 pb-3">
                            <h6 class="card-title mb-3 fw-bold"><i class="feather-user me-2"></i>Customer</h6>
                            <div class="fw-bold text-dark fs-6">{{ $order->user?->name ?? '-' }}</div>
                            <div class="text-muted fs-13">{{ $order->user?->email ?? '' }}</div>
                            <div class="text-muted fs-13">{{ $order->user?->phone ?? '' }}</div>
                        </div>

                        <hr class="my-0">

                        <div class="p-4 py-3">
                            <h6 class="card-title mb-3 fw-bold"><i class="feather-home me-2"></i>Delivery Address</h6>
                            @if ($order->address)
                                <div class="fw-semibold text-dark">{{ $order->address->first_name }} {{ $order->address->last_name }}</div>
                                <div class="text-muted fs-13">{{ $order->address->address }}, {{ $order->address->city }}</div>
                                <div class="text-muted fs-13">{{ $order->address->country }} {{ $order->address->zip }}</div>
                                <div class="text-muted fs-13">{{ $order->address->phone }}</div>
                            @else
                                <span class="text-muted">Not available.</span>
                            @endif
                        </div>

                        <hr class="my-0">

                        <div class="p-4 pt-3">
                            <h6 class="card-title mb-3 fw-bold"><i class="feather-truck me-2"></i>Delivery Partner</h6>
                            @if ($order->deliveryPartner)
                                <div class="fw-bold text-dark">{{ $order->deliveryPartner->name }}</div>
                                <div class="text-muted fs-13">{{ $order->deliveryPartner->phone ?? '' }}</div>
                            @elseif ($order->status === 'ready')
                                <form method="POST" action="{{ route('restaurant.orders.assign-partner', $order->id) }}" class="d-flex gap-2 action-form" data-action="Assigning...">
                                    @csrf
                                    <select name="delivery_partner_id" class="form-select form-select-sm" required>
                                        <option value="">Select Delivery Partner</option>
                                        @foreach ($deliveryPartners as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->name }} ({{ $partner->phone ?? 'no phone' }})</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary text-nowrap">
                                        <i class="feather-user-check me-1"></i>Assign
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <hr class="my-0">

                        <div class="p-4 pt-3">
                            <h6 class="card-title mb-3 fw-bold d-flex align-items-center">
                                <i class="feather-users text-primary me-2"></i>Active Partners (Nearest First)
                            </h6>
                            @if($deliveryPartners->count() > 0)
                                <style>
                                    .pulse-dot {
                                        height: 8px; width: 8px; background-color: #10b981; border-radius: 50%; display: inline-block;
                                        animation: pulse-green 1.5s infinite;
                                    }
                                    @keyframes pulse-green {
                                        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
                                        70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
                                        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
                                    }
                                </style>
                                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                                    @foreach ($deliveryPartners as $p)
                                        <li class="d-flex align-items-center justify-content-between p-3 border rounded bg-white shadow-sm" style="transition: all 0.2s ease; cursor: default;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.05)';">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-secondary" style="width: 42px; height: 42px;">
                                                    <i class="feather-user fs-5"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark fs-14 d-block mb-1">{{ $p->name }}</span>
                                                    <span class="fs-12 d-flex align-items-center">
                                                        @if(isset($p->location_type) && $p->location_type === 'Live GPS')
                                                            <span class="pulse-dot me-2"></span>
                                                            <span class="text-success fw-semibold">Live GPS Active</span>
                                                        @else
                                                            <i class="feather-map-pin text-muted me-1"></i>
                                                            <span class="text-muted">{{ $p->location_type ?? 'Unknown' }}</span>
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if(isset($p->distance) && $p->distance != 999999)
                                                    <div class="badge rounded-pill" style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; font-size: 12px; padding: 6px 12px; font-weight: 600;">
                                                        <i class="feather-navigation me-1"></i>{{ number_format($p->distance, 2) }} km
                                                    </div>
                                                @else
                                                    <div class="badge rounded-pill bg-light text-muted border" style="font-size: 12px; padding: 6px 12px;">
                                                        N/A
                                                    </div>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-muted text-center p-4 border rounded bg-light border-dashed">
                                    <i class="feather-user-x fs-3 d-block mb-2 text-secondary"></i>
                                    No delivery partners found.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items & Billing -->
            <div class="col-xl-8">
                <div class="card stretch stretch-full">
                    <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold"><i class="feather-shopping-cart me-2"></i>Order Items</h5>
                        <button type="button" onclick="window.print()" class="btn btn-sm btn-primary d-print-none shadow-sm">
                            <i class="feather-printer me-1"></i> Print PDF
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Item</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-dark">{{ $item->name }}</td>
                                        <td>{{ $currencySymbol }}{{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td class="text-end pe-4 fw-semibold">{{ $currencySymbol }}{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-end">
                            <div style="min-width: 260px;">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Sub Total</span>
                                    <span class="fw-semibold">{{ $currencySymbol }}{{ number_format($order->subtotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Delivery Charge</span>
                                    <span class="fw-semibold">{{ $order->delivery_charge > 0 ? $currencySymbol . number_format($order->delivery_charge, 2) : 'Free' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Discount (10%)</span>
                                    <span class="fw-semibold">- {{ $currencySymbol }}{{ number_format($order->discount, 2) }}</span>
                                </div>
                                @if($order->promo_discount > 0)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">
                                            Promo Discount
                                            @if($order->promo_code)
                                                <span class="badge ms-1" style="background:#e8f8ee;color:#1f9d4d;font-family:monospace;">{{ $order->promo_code }}</span>
                                            @endif
                                        </span>
                                        <span class="fw-semibold text-success">- {{ $currencySymbol }}{{ number_format($order->promo_discount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Tax ({{ $taxGst ?: $taxPercentage . '%' }})</span>
                                    <span class="fw-semibold">{{ $currencySymbol }}{{ number_format($order->tax, 2) }}</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold text-dark">Total</span>
                                    <span class="fw-bold text-success">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($order->review)
            <div class="card stretch stretch-full mt-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="feather-star me-2"></i>Customer Review</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3 mb-2">
                        <span>Restaurant: <strong>{{ $order->review->restaurant_rating ?? '-' }}/5</strong></span>
                        <span>Food: <strong>{{ $order->review->food_rating ?? '-' }}/5</strong></span>
                        <span>Delivery: <strong>{{ $order->review->delivery_rating ?? '-' }}/5</strong></span>
                    </div>
                    @if ($order->review->comment)
                        <p class="text-muted mb-0">"{{ $order->review->comment }}"</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.action-form').on('submit', function() {
            var $btn = $(this).find('button[type="submit"]');
            var actionText = $(this).data('action') || 'Processing...';
            if ($btn.length) {
                $btn.prop('disabled', true);
                $btn.html('<span class="spinner-border spinner-border-sm me-1 text-white" role="status" aria-hidden="true"></span> ' + actionText);
            }
        });
    });
</script>
@endpush
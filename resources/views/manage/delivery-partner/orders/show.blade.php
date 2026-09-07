@extends('layouts.delivery-partner.main')

@section('title', 'Delivery #' . $order->id . ' - Zomato Delivery')


@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Delivery #{{ $order->id }}</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('delivery-partner.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('delivery-partner.orders.deliveries') }}">My Deliveries</a></li>
                <li class="breadcrumb-item">#{{ $order->id }}</li>
            </ul>
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

        @php
            $stepKeys = ['assigned', 'picked_up', 'out_for_delivery', 'delivered'];
            $stepIcons = [
                'assigned' => 'feather-check-circle',
                'picked_up' => 'feather-shopping-bag',
                'out_for_delivery' => 'feather-truck',
                'delivered' => 'feather-check-square',
            ];
            $stepIdx = array_search($order->status, $stepKeys);
            if ($stepIdx === false) {
                $stepIdx = in_array($order->status, ['completed']) ? 3 : -1;
            }
        @endphp

 <!-- Status Hero Banner -->
<div class="card mb-4 border-0 overflow-hidden"
    style="border-radius:20px; box-shadow:0 12px 35px rgba(0,0,0,.10);">

    <div style="
        position:relative;
        padding:28px;
        color:#fff;
        overflow:hidden;
        background:linear-gradient(135deg,#ff6b35 0%,#ff7848 50%,#f4511e 100%);
    ">

        <!-- Decorative Background -->
        <div style="
            position:absolute;
            width:220px;
            height:220px;
            right:-80px;
            top:-120px;
            border-radius:50%;
            background:rgba(255,255,255,.08);
        "></div>

        <div style="
            position:absolute;
            width:140px;
            height:140px;
            left:-70px;
            bottom:-100px;
            border-radius:50%;
            background:rgba(255,255,255,.06);
        "></div>


        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-4"
            style="position:relative; z-index:2;">

            <!-- Order Information -->
            <div class="d-flex align-items-center gap-3">

                <!-- Truck Icon -->
                <div style="
                    width:64px;
                    height:64px;
                    min-width:64px;
                    border-radius:18px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    background:rgba(255,255,255,.17);
                    border:1px solid rgba(255,255,255,.25);
                    box-shadow:0 8px 20px rgba(0,0,0,.08);
                ">
                    <i class="feather-truck" style="font-size:30px;"></i>
                </div>

                <!-- Details -->
                <div>

                    <div style="
                        font-size:10px;
                        font-weight:700;
                        letter-spacing:1.5px;
                        opacity:.75;
                        margin-bottom:3px;
                    ">
                        DELIVERY ORDER
                    </div>

                    <h4 style="
                        margin:0 0 8px 0;
                        font-size:24px;
                        font-weight:700;
                        letter-spacing:-.3px;
                    ">
                        #{{ $order->id }}
                    </h4>

                    <!-- Status -->
                    <span style="
                        display:inline-flex;
                        align-items:center;
                        gap:7px;
                        padding:6px 13px;
                        border-radius:30px;
                        background:rgba(255,255,255,.18);
                        border:1px solid rgba(255,255,255,.25);
                        font-size:12px;
                        font-weight:600;
                        backdrop-filter:blur(8px);
                    ">
                        <span style="
                            width:7px;
                            height:7px;
                            border-radius:50%;
                            background:#fff;
                            box-shadow:0 0 0 4px rgba(255,255,255,.10);
                        "></span>

                        {{ \App\Models\Order::statusLabel($order->status) }}
                    </span>

                </div>
            </div>


            <!-- Action Buttons -->
            <div class="d-flex flex-wrap gap-2"
                style="position:relative; z-index:3;">

                @if ($order->status === 'assigned')

                    <form method="POST"
                        action="{{ route('delivery-partner.orders.picked-up', $order->id) }}">
                        @csrf

                        <button type="submit"
                            class="btn"
                            style="
                                background:#26bd0e;
                                color:#fff;
                                border:0;
                                border-radius:10px;
                                padding:10px 17px;
                                font-size:13px;
                                font-weight:600;
                                box-shadow:0 5px 15px rgba(0,0,0,.10);
                            ">

                            <i class="feather-shopping-bag me-1"></i>
                            Collect Order

                        </button>
                    </form>

                @elseif ($order->status === 'picked_up')

                    <form method="POST"
                        action="{{ route('delivery-partner.orders.out-for-delivery', $order->id) }}">
                        @csrf

                        <button type="submit"
                            class="btn"
                            style="
                                background:#26bd0e;
                                color:#fff;
                                border:0;
                                border-radius:10px;
                                padding:10px 17px;
                                font-size:13px;
                                font-weight:600;
                                box-shadow:0 5px 15px rgba(0,0,0,.10);
                            ">

                            <i class="feather-truck me-1"></i>
                            Start Delivery

                        </button>
                    </form>

                @elseif ($order->status === 'out_for_delivery')

                    <form method="POST"
                        action="{{ route('delivery-partner.orders.delivered', $order->id) }}">

                        @csrf

                        <button type="submit"
                            class="btn"
                            style="
                                background:#20a557;
                                color:#fff;
                                border:0;
                                border-radius:10px;
                                padding:10px 17px;
                                font-size:13px;
                                font-weight:600;
                                box-shadow:0 5px 15px rgba(32,165,87,.25);
                            "
                            onclick="return confirm('Confirm this order has been delivered?')">

                            <i class="feather-check-circle me-1"></i>
                            Mark Delivered

                        </button>

                    </form>

                @endif


                @if (in_array($order->status, ['assigned', 'picked_up']))

                    <form method="POST"
                        action="{{ route('delivery-partner.orders.reject', $order->id) }}"
                        class="d-inline">

                        @csrf

                        <button type="submit"
                            class="btn"
                            style="
                                color:#fff;
                                background:rgba(255,255,255,.10);
                                border:1px solid rgba(255,255,255,.40);
                                border-radius:10px;
                                padding:10px 17px;
                                font-size:13px;
                                font-weight:600;
                            "
                            onclick="return confirm('Reject this delivery? Another partner will be notified.')">

                            <i class="feather-x me-1"></i>
                            Reject Delivery

                        </button>

                    </form>

                @endif

            </div>
        </div>


        <!-- Delivery Stepper -->
        @if ($stepIdx >= 0)

            <div style="
                position:relative;
                z-index:2;
                margin-top:28px;
                padding:25px 22px;
                border-radius:16px;
                background:#fff;
                color:#222;
                box-shadow:0 10px 30px rgba(0,0,0,.10);
            ">

                <!-- Stepper Header -->
                <div class="d-flex align-items-center justify-content-between mb-4">

                    <div>
                        <h5 style="
                            margin:0 0 4px;
                            font-size:16px;
                            font-weight:700;
                            color:#222;
                        ">
                            Delivery Progress
                        </h5>

                        <p style="
                            margin:0;
                            font-size:12px;
                            color:#999;
                        ">
                            Track your order through each stage
                        </p>
                    </div>

                    <!-- Progress Counter -->
                    <span style="
                        display:inline-flex;
                        align-items:center;
                        justify-content:center;
                        min-width:50px;
                        height:30px;
                        padding:0 10px;
                        border-radius:30px;
                        background:#fff1eb;
                        color:#ff6b35;
                        font-size:12px;
                        font-weight:700;
                    ">
                        {{ $stepIdx + 1 }} / {{ count($stepKeys) }}
                    </span>

                </div>


                <!-- Steps -->
                <div class="row g-0 text-center"
                    style="position:relative;">

                    @foreach ($stepKeys as $i => $stepKey)

                        @php
                            $isDone = $i < $stepIdx;
                            $isActive = $i === $stepIdx;
                            $isLast = $i === count($stepKeys) - 1;
                        @endphp

                        <div class="col-{{ 12 / count($stepKeys) }}"
                            style="position:relative;">

                            <!-- Connecting Line -->
                            @if (!$isLast)

                                <div style="
                                    position:absolute;
                                    top:22px;
                                    left:50%;
                                    width:100%;
                                    height:3px;
                                    z-index:0;
                                    border-radius:10px;
                                    background:{{ $i < $stepIdx ? '#20a557' : '#e9ecef' }};
                                "></div>

                            @endif


                            <!-- Step Icon -->
                            <div style="
                                position:relative;
                                z-index:2;
                                width:46px;
                                height:46px;
                                margin:0 auto 12px;
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                border-radius:50%;
                                border:4px solid #fff;

                                @if ($isDone)
                                    background:#20a557;
                                    color:#fff;
                                    box-shadow:0 5px 15px rgba(32,165,87,.25);
                                @elseif ($isActive)
                                    background:#ff6b35;
                                    color:#fff;
                                    box-shadow:0 5px 18px rgba(255,107,53,.30);
                                @else
                                    background:#f3f4f6;
                                    color:#a5a9ae;
                                    box-shadow:0 3px 10px rgba(0,0,0,.06);
                                @endif

                            ">

                                @if ($isDone)

                                    <i class="feather-check" style="font-size:18px;"></i>

                                @else

                                    <i class="{{ $stepIcons[$stepKey] }}"
                                        style="font-size:18px;"></i>

                                @endif

                            </div>


                            <!-- Step Number -->
                            <div style="
                                font-size:9px;
                                font-weight:700;
                                text-transform:uppercase;
                                letter-spacing:.7px;
                                color:#aaa;
                                margin-bottom:3px;
                            ">
                                Step {{ $i + 1 }}
                            </div>


                            <!-- Step Label -->
                            <div style="
                                font-size:12px;
                                font-weight:650;
                                line-height:1.3;

                                @if ($isDone)
                                    color:#222;
                                @elseif ($isActive)
                                    color:#ff6b35;
                                @else
                                    color:#999;
                                @endif
                            ">
                                {{ \App\Models\Order::statusLabel($stepKey) }}
                            </div>


                            <!-- Step State -->
                            <div style="
                                margin-top:5px;
                                font-size:9px;
                                font-weight:600;

                                @if ($isDone)
                                    color:#20a557;
                                @elseif ($isActive)
                                    color:#ff6b35;
                                @else
                                    color:#aaa;
                                @endif
                            ">

                                @if ($isDone)

                                    Completed

                                @elseif ($isActive)

                                    Current Status

                                @else

                                    Upcoming

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

    </div>
</div>
        <div class="row g-4">
            <!-- Left: Delivery Details -->
            <div class="col-xl-4 d-flex flex-column">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 flex-fill">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="feather-truck me-2 text-danger"></i>Delivery Details</h5>
                    </div>
                    <div class="card-body p-0">

                        <div class="px-4 pt-3 pb-2">
                            <h6 class="fw-bold text-uppercase fs-11 text-muted mb-0"><i class="feather-map-pin me-1 text-danger"></i>Pickup Restaurant</h6>
                        </div>
                        <div class="px-4 pb-3">
                            <div class="info-item">
                                <div class="info-label">Restaurant</div>
                                <div class="info-value">{{ $order->restaurant?->restaurant_name ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Address</div>
                                <div class="info-value">{{ $order->restaurant?->address ?? '-' }}</div>
                            </div>
                            @if ($order->restaurant?->phone)
                                <div class="info-item">
                                    <div class="info-label">Phone</div>
                                    <div class="info-value">{{ $order->restaurant->phone }}</div>
                                </div>
                            @endif
                        </div>

                        <div class="border-top px-4 py-3">
                            <h6 class="fw-bold text-uppercase fs-11 text-muted mb-0"><i class="feather-user me-1 text-danger"></i>Customer</h6>
                        </div>
                        <div class="px-4 pb-3">
                            <div class="info-item">
                                <div class="info-label">Name</div>
                                <div class="info-value">{{ $order->user?->name ?? '-' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Phone</div>
                                <div class="info-value">{{ $order->user?->phone ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="border-top px-4 py-3">
                            <h6 class="fw-bold text-uppercase fs-11 text-muted mb-0"><i class="feather-home me-1 text-danger"></i>Delivery Address</h6>
                        </div>
                        <div class="px-4 pb-4">
                            @if ($order->address)
                                <div class="info-item">
                                    <div class="info-label">Recipient</div>
                                    <div class="info-value">{{ $order->address->first_name }} {{ $order->address->last_name }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Address</div>
                                    <div class="info-value">{{ $order->address->address }}, {{ $order->address->city }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Region</div>
                                    <div class="info-value">{{ $order->address->country }} {{ $order->address->zip }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Phone</div>
                                    <div class="info-value">{{ $order->address->phone }}</div>
                                </div>
                            @else
                                <span class="text-muted">Not available.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Items & Billing -->
            <div class="col-xl-8 d-flex flex-column">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="feather-shopping-cart me-2 text-danger"></i>Order Items</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
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
                                            <td><span class="badge bg-soft-primary text-primary">{{ $item->qty }}</span></td>
                                            <td class="text-end pe-4 fw-semibold">{{ $currencySymbol }}{{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-end">
                            <div style="min-width: 280px;">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Payment Method</span>
                                    <span class="fw-semibold">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">Payment Status</span>
                                    <span class="fw-semibold text-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark">Total</span>
                                    <span class="fw-bold fs-5 text-success">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
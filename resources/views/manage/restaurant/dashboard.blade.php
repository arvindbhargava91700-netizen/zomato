@extends('layouts.restaurant.main')

@section('title', 'Partner Dashboard - Zomato')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Restaurant Dashboard</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
            </ul>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Restaurant Profile Banner Card -->
        @if($restaurant)
            <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
                <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        @if($restaurant->logo)
                            <img src="{{ asset($restaurant->logo) }}" alt="Logo" class="rounded-circle border" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px; background-color: #cb202d;">
                                <i class="feather-shopping-bag"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">{{ $restaurant->restaurant_name }}</h4>
                            <p class="text-muted mb-0 fs-12"><i class="feather-map-pin me-1"></i>{{ $restaurant->address }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-soft-success text-success px-3 py-2 rounded-pill fs-12 fw-semibold">
                            Commission Rate: {{ $restaurant->commission_percentage }}%
                        </span>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning mb-4 rounded-3" role="alert">
                <i class="feather-alert-triangle me-2"></i> No restaurant is currently assigned to your account. Please contact the administrator.
            </div>
        @endif

        <!-- Stats Grid: 8 Key Cards -->
        <div class="row g-3 mb-4">
            <!-- Today's Orders -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold uppercase">Today's Orders</span>
                                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $stats['todays_orders'] }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-circle">
                                <i class="feather-shopping-cart fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Orders -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold uppercase">Pending Orders</span>
                                <h3 class="fw-bold text-warning mb-0 mt-1">{{ $stats['pending_orders'] }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-circle">
                                <i class="feather-clock fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Orders -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold uppercase">Completed Orders</span>
                                <h3 class="fw-bold text-success mb-0 mt-1">{{ $stats['completed_orders'] }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-success text-success rounded-circle">
                                <i class="feather-check-circle fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancelled Orders -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold uppercase">Cancelled Orders</span>
                                <h3 class="fw-bold text-danger mb-0 mt-1">{{ $stats['cancelled_orders'] }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-danger text-danger rounded-circle">
                                <i class="feather-x-circle fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Foods -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold uppercase">Total Foods</span>
                                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $stats['total_foods'] }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-info text-info rounded-circle">
                                <i class="feather-grid fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Categories -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold uppercase">Total Categories</span>
                                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $stats['total_categories'] }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-secondary text-secondary rounded-circle">
                                <i class="feather-folder fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Earnings -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold uppercase">Today's Earnings</span>
                                <h3 class="fw-bold text-success mb-0 mt-1">{{ $currencySymbol }}{{ number_format($stats['todays_earnings'], 2) }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-success text-success rounded-circle">
                                <i class="feather-dollar-sign fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Earnings -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold uppercase">Monthly Earnings</span>
                                <h3 class="fw-bold text-primary mb-0 mt-1">{{ $currencySymbol }}{{ number_format($stats['monthly_earnings'], 2) }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-circle">
                                <i class="feather-trending-up fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Earnings -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold uppercase">Total Earnings</span>
                                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $currencySymbol }}{{ number_format($stats['total_earnings'], 2) }}</h3>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-circle">
                                <i class="feather-credit-card fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Widget -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold">Recent Orders</h5>
                <span class="badge bg-light text-muted">Latest Live Activity</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Order ID</th>
                                <th>Customer Name</th>
                                <th>Items</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">
                                        <a href="{{ route('restaurant.orders.show', $order->id) }}" class="text-decoration-none">#{{ $order->id }}</a>
                                    </td>
                                    <td>{{ $order->user?->name ?? '-' }}</td>
                                    <td>
                                        @foreach ($order->items->take(2) as $item)
                                            <span class="fs-12">{{ $item->name }} ×{{ $item->qty }};</span>
                                        @endforeach
                                        @if ($order->items->count() > 2)
                                            <span class="fs-12 text-muted">+{{ $order->items->count() - 2 }} more</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="{{ \App\Models\Order::statusBadge($order->status) }} fs-12">
                                            {{ \App\Models\Order::statusLabel($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4 text-muted fs-12">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="feather-shopping-bag display-6 d-block mb-2 text-secondary"></i>
                                        No recent orders recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

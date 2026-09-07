@extends('layouts.restaurant.main')

@section('title', 'Orders - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Orders</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Orders</li>
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

        <!-- Status Filter Tabs -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('restaurant.orders.index') }}"
                class="btn btn-sm {{ !$status ? 'btn-danger text-white' : 'btn-light border' }}"
                style="{{ !$status ? 'background-color:#cb202d;border:none;' : '' }}">All</a>
            @foreach (\App\Models\Order::STATUSES as $s)
                <a href="{{ route('restaurant.orders.index', ['status' => $s]) }}"
                    class="btn btn-sm {{ $status === $s ? 'btn-danger text-white' : 'btn-light border' }}"
                    style="{{ $status === $s ? 'background-color:#cb202d;border:none;' : '' }}">
                    {{ \App\Models\Order::statusLabel($s) }}
                </a>
            @endforeach
        </div>

        <!-- Orders Table -->
        <div class="card stretch stretch-full">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">{{ $restaurant->restaurant_name }} - Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="restaurantOrdersTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Order</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Placed At</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">#{{ $order->id }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $order->user?->name ?? '-' }}</div>
                                        <span class="text-muted fs-12 d-block">{{ $order->user?->phone ?? '' }}</span>
                                    </td>
                                    <td>
                                        @foreach ($order->items as $item)
                                            <div class="fs-12">{{ $item->name }} <span class="text-muted">× {{ $item->qty }}</span></div>
                                        @endforeach
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</span>
                                        <span class="text-muted fs-11 d-block">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }} text-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="{{ \App\Models\Order::statusBadge($order->status) }} fs-12">
                                            {{ \App\Models\Order::statusLabel($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-muted fs-12">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('restaurant.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-light border text-info p-2 rounded-2"
                                            data-bs-toggle="tooltip" title="View Order">
                                            <i class="feather-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="feather-shopping-bag fs-1 text-muted mb-2 opacity-50"></i>
                                            <h6 class="fw-semibold text-dark mb-1">No orders found</h6>
                                            <span class="fs-12 text-muted">There are no orders matching the selected filter.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($orders->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $orders->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
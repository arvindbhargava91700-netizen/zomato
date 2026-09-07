@extends('layouts.delivery-partner.main')

@section('title', 'My Deliveries - Zomato Delivery')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">My Deliveries</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('delivery-partner.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">My Deliveries</li>
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

        <div class="card stretch stretch-full">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">My Deliveries</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="customerList">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Order</th>
                                <th>Restaurant</th>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">#{{ $order->id }}</td>
                                    <td>{{ $order->restaurant?->restaurant_name ?? '-' }}</td>
                                    <td>{{ $order->user?->name ?? '-' }}</td>
                                    <td class="text-muted fs-12">
                                        {{ Str::limit($order->address?->address ?? 'No address', 25) }}
                                    </td>
                                    <td class="fw-bold text-dark">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="{{ \App\Models\Order::statusBadge($order->status) }} fs-12">
                                            {{ \App\Models\Order::statusLabel($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $order->isCodOrder() ? 'bg-soft-warning text-warning' : 'bg-soft-success text-success' }} fs-12">
                                            {{ $order->isCodOrder() ? 'Cash On Delivery' : ucwords(str_replace('_', ' ', $order->payment_method ?? 'Prepaid')) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('delivery-partner.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-light border text-info p-2 rounded-2"
                                            data-bs-toggle="tooltip" title="View">
                                            <i class="feather-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                               
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
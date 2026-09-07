@extends('layouts.restaurant.main')

@section('title', 'Table Bookings - Zomato Partner')

@php
    $statusLabels = [
        'pending' => 'Pending',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];
    $statusClasses = [
        'pending' => 'bg-warning text-dark',
        'accepted' => 'bg-success text-white',
        'rejected' => 'bg-danger text-white',
        'completed' => 'bg-primary text-white',
        'cancelled' => 'bg-secondary text-white',
    ];
    $billStatusLabels = [
        'pending' => 'Bill Pending',
        'paid' => 'Bill Paid',
    ];
    $billStatusClasses = [
        'pending' => 'bg-warning text-dark',
        'paid' => 'bg-success text-white',
    ];
@endphp

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Table Bookings</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Table Bookings</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <span class="badge bg-light border text-secondary fw-semibold">
                {{ $bookings->total() }} Booking(s)
            </span>
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

        <!-- Filter Card -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('restaurant.bookings.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by customer name or phone..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                    {{ $statusLabels[$status] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">Search</button>
                        <a href="{{ route('restaurant.bookings.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Bookings for {{ $restaurant->restaurant_name ?? 'Your Restaurant' }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Customer</th>
                                <th>Date &amp; Time</th>
                                <th>Guests</th>
                                <th>Offer</th>
                                <th>Status</th>
                                <th>Bill</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark fs-6">{{ $booking->customer_name }}</div>
                                        <div class="fs-12 text-muted">{{ $booking->phone }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $booking->book_date->format('d M Y') }}</div>
                                        <div class="fs-12 text-muted">{{ $booking->book_time->format('h:i A') }}</div>
                                    </td>
                                    <td>{{ $booking->guests }}</td>
                                    <td>
                                        @if($booking->diningOffer)
                                            <span class="badge bg-light border text-danger fw-semibold">
                                                {{ $booking->diningOffer->discount_type === 'percentage'
                                                    ? $booking->diningOffer->discount_value . '% OFF'
                                                    : $currencySymbol . number_format($booking->diningOffer->discount_value, 2) . ' OFF' }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusClasses[$booking->status] }} px-3 py-2 rounded-pill fw-semibold">
                                            {{ $statusLabels[$booking->status] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($booking->bill_items && !empty($booking->bill_items))
                                            <span class="badge {{ $billStatusClasses[$booking->bill_status] }} px-3 py-2 rounded-pill fw-semibold">
                                                <i class="feather-{{ $booking->isBillPaid() ? 'check-circle' : 'clock' }} me-1"></i>
                                                {{ $billStatusLabels[$booking->bill_status] }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-1 flex-wrap">
                                            @if($booking->isPending())
                                                <form action="{{ route('restaurant.bookings.update-status', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ App\Models\Booking::STATUS_ACCEPTED }}">
                                                    <button type="submit" class="btn btn-sm btn-success border-0 px-3 rounded-pill fw-semibold">Accept</button>
                                                </form>
                                                <form action="{{ route('restaurant.bookings.update-status', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ App\Models\Booking::STATUS_REJECTED }}">
                                                    <button type="submit" class="btn btn-sm btn-danger border-0 px-3 rounded-pill fw-semibold">Reject</button>
                                                </form>
                                            @elseif($booking->status === App\Models\Booking::STATUS_ACCEPTED)
                                                <form action="{{ route('restaurant.bookings.update-status', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ App\Models\Booking::STATUS_COMPLETED }}">
                                                    <button type="submit" class="btn btn-sm btn-primary border-0 px-3 rounded-pill fw-semibold">Complete</button>
                                                </form>
                                                <form action="{{ route('restaurant.bookings.update-status', $booking->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ App\Models\Booking::STATUS_CANCELLED }}">
                                                    <button type="submit" class="btn btn-sm btn-secondary border-0 px-3 rounded-pill fw-semibold">Cancel</button>
                                                </form>
                                            @else
                                            @endif

                                            @if(in_array($booking->status, [App\Models\Booking::STATUS_ACCEPTED, App\Models\Booking::STATUS_COMPLETED]))
                                                <a href="{{ route('restaurant.bookings.bill.show', $booking->id) }}" class="btn btn-sm btn-info border-0 px-3 rounded-pill fw-semibold">
                                                    <i class="feather-file-text me-1"></i> Bill
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No table bookings found for your restaurant.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($bookings->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $bookings->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

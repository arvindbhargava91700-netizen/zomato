@extends('layouts.app')

@section('title', $restaurant->restaurant_name . ' - Order Food Online')

@section('content')
<div class="container py-5">
    <a href="{{ route('public.restaurants.index') }}" class="btn btn-light border text-secondary fw-semibold mb-4">
        <i class="feather-arrow-left me-1"></i> Back to Restaurants
    </a>

    <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
        @if($restaurant->banner)
            <img src="{{ asset($restaurant->banner) }}" alt="Banner" style="height: 220px; width: 100%; object-fit: cover;">
        @else
            <div class="bg-danger d-flex align-items-center justify-content-center text-white" style="height: 220px; background-color: #cb202d;">
                <i class="feather-shopping-bag display-3"></i>
            </div>
        @endif
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                @if($restaurant->logo)
                    <img src="{{ asset($restaurant->logo) }}" alt="Logo" class="rounded-circle border border-4 border-white shadow" style="width: 80px; height: 80px; object-fit: cover;">
                @endif
                <div class="flex-grow-1">
                    <h3 class="fw-bold mb-0 text-dark">{{ $restaurant->restaurant_name }}</h3>
                    <p class="text-muted mb-0"><i class="feather-map-pin me-1"></i>{{ $restaurant->address }}</p>
                </div>
                <div class="text-end">
                    @if($restaurant->is_pure_veg)
                        <span class="badge bg-success fs-10">Pure Veg</span>
                    @else
                        <span class="badge bg-danger fs-10">Veg / Non-Veg</span>
                    @endif
                    <div class="mt-1">
                        <span class="badge bg-light text-secondary fs-10"><i class="feather-clock me-1"></i>{{ $restaurant->opening_time ? $restaurant->opening_time->format('h:i A') : '—' }} - {{ $restaurant->closing_time ? $restaurant->closing_time->format('h:i A') : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="card-title mb-0 fw-bold text-dark"><i class="feather-star me-2 text-danger"></i>Features</h5>
        </div>
        <div class="card-body p-4">
            @php $features = $restaurant->featureLabels(); @endphp
            @if(count($features) > 0)
                <div class="d-flex flex-wrap gap-2">
                    @foreach($features as $feature)
                        <span class="badge bg-light text-dark border px-3 py-2 fs-12">
                            <i class="feather-check-circle text-success me-1"></i>{{ $feature }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">No features added yet.</p>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="feather-file-text me-2 text-danger"></i>About</h5>
                </div>
                <div class="card-body p-4">
                    <p class="mb-0 text-secondary">{{ $restaurant->description ?? 'No description provided.' }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="feather-info me-2 text-danger"></i>Info</h5>
                </div>
                <div class="card-body p-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Min Order</span>
                            <strong>₹{{ number_format($restaurant->minimum_order_amount, 2) }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Delivery Radius</span>
                            <strong>{{ $restaurant->delivery_radius }} km</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Delivery Time</span>
                            <strong>{{ $restaurant->estimated_delivery_time }} mins</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

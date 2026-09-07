@extends('layouts.app')

@section('title', 'Restaurants - Order Food Online')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Explore Restaurants</h1>
        <p class="text-muted">Order delicious food from the best approved restaurants near you</p>

        <form action="{{ route('public.restaurants.index') }}" method="GET" class="row g-2 justify-content-center mt-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search restaurant or cuisine..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">Search</button>
            </div>
        </form>
    </div>

    <div class="row g-4">
        @forelse($restaurants as $restaurant)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('public.restaurants.show', $restaurant->restaurant_slug) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden">
                        @if($restaurant->banner)
                            <img src="{{ asset($restaurant->banner) }}" alt="Banner" class="card-img-top" style="height: 140px; object-fit: cover;">
                        @else
                            <div class="bg-danger d-flex align-items-center justify-content-center text-white" style="height: 140px; background-color: #cb202d;">
                                <i class="feather-shopping-bag display-5"></i>
                            </div>
                        @endif
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-2">
                                @if($restaurant->logo)
                                    <img src="{{ asset($restaurant->logo) }}" alt="Logo" class="rounded-circle border" style="width: 45px; height: 45px; object-fit: cover;">
                                @endif
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">{{ $restaurant->restaurant_name }}</h6>
                                    <span class="text-muted fs-12"><i class="feather-map-pin me-1"></i>{{ Str::limit($restaurant->address, 40) }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-3">
                                @if($restaurant->is_pure_veg)
                                    <span class="badge bg-success fs-10">Pure Veg</span>
                                @endif
                                <span class="badge bg-light text-secondary fs-10">
                                    <i class="feather-clock me-1"></i>{{ $restaurant->estimated_delivery_time }} mins
                                </span>
                                <span class="badge bg-light text-secondary fs-10">
                                    <i class="feather-currency-inr me-1"></i>Min {{ number_format($restaurant->minimum_order_amount, 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="feather-inbox display-5 d-block mb-2 text-secondary"></i>
                No restaurants available right now. Please check back later.
            </div>
        @endforelse
    </div>

    @if($restaurants->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $restaurants->links() }}
        </div>
    @endif
</div>
@endsection

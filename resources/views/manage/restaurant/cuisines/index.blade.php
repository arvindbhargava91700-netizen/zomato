@extends('layouts.restaurant.main')

@section('title', 'Select Cuisines - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Restaurant Cuisines</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Cuisines</li>
            </ul>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Select Cuisines for {{ $restaurant->restaurant_name }}</h5>
                <p class="text-muted small mb-0 mt-1">Check the cuisines that your restaurant serves to help customers find your food easily.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('restaurant.cuisines.update') }}" method="POST">
                    @csrf

                    <div class="row g-3 mb-4">
                        @forelse($activeCuisines as $cuisine)
                            <div class="col-md-4 col-lg-3">
                                <div class="card border rounded-3 p-3 h-100 position-relative">
                                    <div class="form-check d-flex align-items-center gap-3">
                                        <input class="form-check-input flex-shrink-0" type="checkbox" name="cuisine_ids[]" value="{{ $cuisine->id }}" id="cuis_{{ $cuisine->id }}" {{ in_array($cuisine->id, $assignedCuisineIds) ? 'checked' : '' }} style="width: 20px; height: 20px;">
                                        <label class="form-check-label w-100 cursor-pointer" for="cuis_{{ $cuisine->id }}">
                                            <div class="d-flex align-items-center gap-2">
                                                @if($cuisine->icon)
                                                    <img src="{{ asset($cuisine->icon) }}" alt="{{ $cuisine->name }}" class="rounded" style="width: 30px; height: 30px; object-fit: cover;">
                                                @elseif($cuisine->image)
                                                    <img src="{{ asset($cuisine->image) }}" alt="{{ $cuisine->name }}" class="rounded" style="width: 30px; height: 30px; object-fit: cover;">
                                                @else
                                                    <i class="feather-coffee text-muted fs-4"></i>
                                                @endif
                                                <span class="fw-bold text-dark fs-6">{{ $cuisine->name }}</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="feather-coffee display-6 d-block mb-2 text-secondary"></i>
                                No active cuisines available. Please contact system administrator.
                            </div>
                        @endforelse
                    </div>

                    @if($activeCuisines->count() > 0)
                        <div class="d-flex justify-content-end border-top pt-3">
                            <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">Save Cuisine Selection</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

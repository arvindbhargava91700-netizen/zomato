@extends('layouts.restaurant.main')

@section('title', 'Food Item Details - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Food Item Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.foods.index') }}">Food Items</a></li>
                <li class="breadcrumb-item">{{ $food->name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.foods.edit', $food->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit me-1"></i> Edit
            </a>
            <a href="{{ route('restaurant.foods.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-auto">
                        @if($food->image)
                            <img src="{{ asset($food->image) }}" alt="{{ $food->name }}" class="rounded-3 border" style="width: 110px; height: 110px; object-fit: cover;">
                        @else
                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border" style="width: 110px; height: 110px;">
                                <i class="feather-grid text-muted display-5"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h3 class="fw-bold text-dark mb-0">{{ $food->name }}</h3>
                            @if($food->food_type === 'veg')
                                <span class="badge bg-success"><i class="feather-disc me-1"></i> Veg</span>
                            @elseif($food->food_type === 'non_veg')
                                <span class="badge bg-danger"><i class="feather-disc me-1"></i> Non-Veg</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="feather-disc me-1"></i> Egg</span>
                            @endif
                        </div>
                        <p class="text-muted mb-2">Slug: <code>{{ $food->slug }}</code> | SKU: <code>{{ $food->sku ?? 'N/A' }}</code></p>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="fs-4 fw-bold text-success">₹{{ number_format($food->base_price, 2) }}</span>
                            @if($food->discount_price)
                                <span class="text-muted text-decoration-line-through">₹{{ number_format($food->discount_price, 2) }}</span>
                            @endif
                            @if($food->status === 'active')
                                <span class="badge bg-success px-3 py-1 rounded-pill ms-2">Active</span>
                            @else
                                <span class="badge bg-secondary px-3 py-1 rounded-pill ms-2">Inactive</span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-3"><i class="feather-home me-2 text-danger"></i>Restaurant & Category</h6>
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted d-block fs-12">Restaurant</span>
                            <strong class="text-dark fs-6">{{ $restaurant->restaurant_name }}</strong>
                            <span class="text-muted d-block fs-12 mt-2">Food Category</span>
                            <strong class="text-dark fs-6">{{ $food->category->name ?? 'N/A' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-3"><i class="feather-info me-2 text-danger"></i>Specifications</h6>
                        <div class="p-3 bg-light rounded-3">
                            <div class="row g-2">
                                <div class="col-6">
                                    <span class="text-muted fs-12 d-block">Preparation Time</span>
                                    <strong class="text-dark">{{ $food->preparation_time ?? 'N/A' }} mins</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted fs-12 d-block">Tax Percentage</span>
                                    <strong class="text-dark">{{ number_format($food->tax_percentage, 2) }}%</strong>
                                </div>
                                <div class="col-12 mt-2">
                                    <span class="text-muted fs-12 d-block mb-1">Associated Cuisines</span>
                                    @forelse($food->cuisines as $cuisine)
                                        <span class="badge bg-soft-info text-info me-1">{{ $cuisine->name }}</span>
                                    @empty
                                        <span class="text-muted fs-12">None</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <h6 class="fw-bold text-dark mb-2"><i class="feather-file-text me-2 text-danger"></i>Short Description</h6>
                        <p class="text-secondary bg-light p-3 rounded-3 mb-0">{{ $food->short_description ?? 'No short description provided.' }}</p>
                    </div>

                    <div class="col-12">
                        <h6 class="fw-bold text-dark mb-2"><i class="feather-align-left me-2 text-danger"></i>Full Description</h6>
                        <p class="text-secondary bg-light p-3 rounded-3 mb-0">{{ $food->description ?? 'No detailed description provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

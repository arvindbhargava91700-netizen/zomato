@extends('layouts.admin.main')

@section('title', 'Food Variant Details - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Food Variant Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.food-variants.index') }}">Food Variants</a></li>
                <li class="breadcrumb-item">{{ $foodVariant->variant_name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.food-variants.edit', $foodVariant->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit me-1"></i> Edit
            </a>
            <a href="{{ route('admin.food-variants.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">{{ $foodVariant->variant_name }}</h3>
                        <p class="text-muted mb-0">Food Item: <strong class="text-dark">{{ $foodVariant->food->name ?? 'N/A' }}</strong> | SKU: <code>{{ $foodVariant->sku ?? 'N/A' }}</code></p>
                    </div>
                    <div>
                        @if($foodVariant->status === 'active')
                            <span class="badge bg-success px-3 py-2 fs-6 rounded-pill">Active</span>
                        @else
                            <span class="badge bg-secondary px-3 py-2 fs-6 rounded-pill">Inactive</span>
                        @endif
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-3"><i class="feather-dollar-sign me-2 text-danger"></i>Pricing Structure</h6>
                        <div class="p-3 bg-light rounded-3">
                            <div class="row g-3">
                                <div class="col-4">
                                    <span class="text-muted fs-12 d-block">Selling Price</span>
                                    <strong class="text-dark fs-5">₹{{ number_format($foodVariant->price, 2) }}</strong>
                                </div>
                                <div class="col-4">
                                    <span class="text-muted fs-12 d-block">Sale Price</span>
                                    <strong class="text-success fs-5">{{ $foodVariant->sale_price ? '₹' . number_format($foodVariant->sale_price, 2) : 'N/A' }}</strong>
                                </div>
                                <div class="col-4">
                                    <span class="text-muted fs-12 d-block">Cost Price</span>
                                    <strong class="text-secondary fs-5">{{ $foodVariant->cost_price ? '₹' . number_format($foodVariant->cost_price, 2) : 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-3"><i class="feather-package me-2 text-danger"></i>Portion & Prep Specifications</h6>
                        <div class="p-3 bg-light rounded-3">
                            <div class="row g-3">
                                <div class="col-4">
                                    <span class="text-muted fs-12 d-block">Weight / Volume</span>
                                    <strong class="text-dark">{{ $foodVariant->weight ? $foodVariant->weight . ' ' . $foodVariant->weight_unit : 'N/A' }}</strong>
                                </div>
                                <div class="col-4">
                                    <span class="text-muted fs-12 d-block">Serving Size</span>
                                    <strong class="text-dark">{{ $foodVariant->serving_size ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-4">
                                    <span class="text-muted fs-12 d-block">Preparation Time</span>
                                    <strong class="text-dark">{{ $foodVariant->preparation_time ? $foodVariant->preparation_time . ' mins' : 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <h6 class="fw-bold text-dark mb-2"><i class="feather-home me-2 text-danger"></i>Restaurant Info</h6>
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted fs-12 d-block">Belongs To Restaurant</span>
                            <strong class="text-dark fs-6">{{ $foodVariant->food->restaurant->restaurant_name ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

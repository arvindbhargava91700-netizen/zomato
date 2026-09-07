@extends('layouts.admin.main')

@section('title', 'Food Category Details - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Food Category Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.food-categories.index') }}">Food Categories</a></li>
                <li class="breadcrumb-item">{{ $foodCategory->name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.food-categories.edit', $foodCategory->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit me-1"></i> Edit
            </a>
            <a href="{{ route('admin.food-categories.index') }}" class="btn btn-light border text-secondary fw-semibold">
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
                        @if($foodCategory->image)
                            <img src="{{ asset($foodCategory->image) }}" alt="{{ $foodCategory->name }}" class="rounded-3 border" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border" style="width: 100px; height: 100px;">
                                <i class="feather-folder text-muted display-5"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <h3 class="fw-bold text-dark mb-1">{{ $foodCategory->name }}</h3>
                        <p class="text-muted mb-2">Slug: <code>{{ $foodCategory->slug }}</code></p>
                        <div>
                            @if($foodCategory->status === 'active')
                                <span class="badge bg-success px-3 py-2 rounded-pill">Active</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">Inactive</span>
                            @endif
                            <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill ms-2">Sort Order: {{ $foodCategory->sort_order }}</span>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-3"><i class="feather-home me-2 text-danger"></i>Category Scope / Restaurant</h6>
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted d-block fs-12">Assigned Restaurant</span>
                            <strong class="text-dark fs-6">{{ $foodCategory->restaurant->restaurant_name ?? 'Global / Platform Category' }}</strong>
                            @if($foodCategory->restaurant)
                                <span class="text-muted d-block fs-12 mt-2">Owner: {{ $foodCategory->restaurant->owner_name ?? 'N/A' }}</span>
                                <span class="text-muted d-block fs-12">Email: {{ $foodCategory->restaurant->email ?? 'N/A' }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-dark mb-3"><i class="feather-clock me-2 text-danger"></i>Audit Information</h6>
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted d-block fs-12">Created At</span>
                            <strong class="text-dark">{{ $foodCategory->created_at ? $foodCategory->created_at->format('M d, Y h:i A') : 'N/A' }}</strong>
                            <span class="text-muted d-block fs-12 mt-2">Last Updated</span>
                            <strong class="text-dark">{{ $foodCategory->updated_at ? $foodCategory->updated_at->format('M d, Y h:i A') : 'N/A' }}</strong>
                        </div>
                    </div>
                    <div class="col-12">
                        <h6 class="fw-bold text-dark mb-2"><i class="feather-file-text me-2 text-danger"></i>Description</h6>
                        <p class="text-secondary bg-light p-3 rounded-3 mb-0">{{ $foodCategory->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

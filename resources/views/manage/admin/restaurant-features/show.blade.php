@extends('layouts.admin.main')

@section('title', 'Feature Details: ' . $restaurantFeature->name . ' - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Feature Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurant-features.index') }}">Restaurant Features</a></li>
                <li class="breadcrumb-item">{{ $restaurantFeature->name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.restaurant-features.edit', $restaurantFeature->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit-2 me-1"></i> Edit Feature
            </a>
            <a href="{{ route('admin.restaurant-features.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold">Feature Profile</h5>
                <span class="badge {{ $restaurantFeature->status === 'active' ? 'bg-success' : 'bg-danger' }} fs-12 px-3 py-2 text-uppercase fw-bold shadow-sm">
                    <i class="feather-power me-1"></i> {{ ucfirst($restaurantFeature->status) }}
                </span>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3 border">
                    <div class="bg-white p-2 rounded border shadow-sm" style="width: 60px; height: 60px; flex-shrink: 0;">
                        <div class="w-100 h-100 bg-soft-secondary d-flex align-items-center justify-content-center">
                            <i class="{{ $restaurantFeature->icon ?: 'feather-star' }} fs-4 text-danger"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h3 class="fw-bold mb-1 text-dark">{{ $restaurantFeature->name }}</h3>
                        <div>
                            <span class="text-muted fs-12 me-1">Slug:</span>
                            <code class="text-primary bg-soft-primary px-2 py-1 rounded fs-13">{{ $restaurantFeature->slug }}</code>
                        </div>
                    </div>
                </div>

                <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                    <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                        <span class="text-muted fs-13"><i class="feather-hash me-1"></i> ID</span>
                        <span class="fw-bold text-dark fs-13">#{{ $restaurantFeature->id }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                        <span class="text-muted fs-13"><i class="feather-list me-1"></i> Sort Order</span>
                        <span class="fw-bold text-dark fs-13">{{ $restaurantFeature->sort_order }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                        <span class="text-muted fs-13"><i class="feather-feather me-1"></i> Icon</span>
                        <code class="text-secondary fs-13">{{ $restaurantFeature->icon ?: '-' }}</code>
                    </li>
                    <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                        <span class="text-muted fs-13"><i class="feather-user me-1"></i> Created By</span>
                        <span class="fw-medium text-dark fs-13">{{ $restaurantFeature->creator->name ?? 'Admin' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                        <span class="text-muted fs-13"><i class="feather-calendar me-1"></i> Created At</span>
                        <span class="text-dark fs-13">{{ $restaurantFeature->created_at ? $restaurantFeature->created_at->format('M d, Y h:i A') : '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                        <span class="text-muted fs-13"><i class="feather-user-check me-1"></i> Updated By</span>
                        <span class="fw-medium text-dark fs-13">{{ $restaurantFeature->updater->name ?? '-' }}</span>
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <span class="text-muted fs-13"><i class="feather-clock me-1"></i> Last Updated</span>
                        <span class="text-dark fs-13">{{ $restaurantFeature->updated_at ? $restaurantFeature->updated_at->format('M d, Y h:i A') : '-' }}</span>
                    </li>
                </ul>

                <div class="mt-4 pt-3 border-top d-grid gap-2">
                    <a href="{{ route('admin.restaurant-features.edit', $restaurantFeature->id) }}" class="btn btn-primary fw-semibold">
                        <i class="feather-edit-2 me-1"></i> Edit Details
                    </a>
                    <form action="{{ route('admin.restaurant-features.destroy', $restaurantFeature->id) }}" method="POST" onsubmit="return confirm('Move this feature to trash?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 fw-semibold">
                            <i class="feather-trash-2 me-1"></i> Move to Trash
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
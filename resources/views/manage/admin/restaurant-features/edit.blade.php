@extends('layouts.admin.main')

@section('title', $title)

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Feature: {{ $restaurantFeature->name }}</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurant-features.index') }}">Restaurant Features</a></li>
                <li class="breadcrumb-item">Edit</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.restaurant-features.show', $restaurantFeature->id) }}" class="btn btn-light border text-primary fw-semibold">
                <i class="feather-eye me-1"></i> View Feature
            </a>
            <a href="{{ route('admin.restaurant-features.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Alerts -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <div class="fw-bold mb-1"><i class="feather-alert-triangle me-1"></i> Please fix the following errors:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Update Feature Information</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.restaurant-features.update', $restaurantFeature->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Feature Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Feature Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $restaurantFeature->name) }}" required placeholder="e.g. Credit card, Wifi, Outdoor seating">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Slug -->
                        <div class="col-md-6">
                            <label for="slug" class="form-label fw-semibold">Slug <small class="text-muted">(Optional, unique URL identifier)</small></label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $restaurantFeature->slug) }}" placeholder="e.g. credit-card, wifi">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $restaurantFeature->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $restaurantFeature->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Sort Order -->
                        <div class="col-md-6">
                            <label for="sort_order" class="form-label fw-semibold">Sort Order <small class="text-muted">(Lower appears first)</small></label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $restaurantFeature->sort_order) }}" min="0">
                            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Icon -->
                        <div class="col-md-6">
                            <label for="icon" class="form-label fw-semibold">Icon Class <small class="text-muted">(Feather icon, e.g. feather-wifi)</small></label>
                            <input type="text" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" value="{{ old('icon', $restaurantFeature->icon) }}" placeholder="e.g. feather-wifi, feather-credit-card">
                            @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
                        <a href="{{ route('admin.restaurant-features.index') }}" class="btn btn-light border text-secondary fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-primary fw-semibold px-4">
                            <i class="feather-save me-1"></i> Update Feature
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
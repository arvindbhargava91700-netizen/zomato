@extends('layouts.admin.main')

@section('title', 'Cuisine Details - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Cuisine Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.cuisines.index') }}">Cuisines</a></li>
                <li class="breadcrumb-item">{{ $cuisine->name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.cuisines.edit', $cuisine->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit me-1"></i> Edit
            </a>
            <a href="{{ route('admin.cuisines.index') }}" class="btn btn-light border text-secondary fw-semibold">
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
                        @if($cuisine->image)
                            <img src="{{ asset($cuisine->image) }}" alt="{{ $cuisine->name }}" class="rounded-3 border" style="width: 100px; height: 100px; object-fit: cover;">
                        @elseif($cuisine->icon)
                            <img src="{{ asset($cuisine->icon) }}" alt="{{ $cuisine->name }}" class="rounded-3 border" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border" style="width: 100px; height: 100px;">
                                <i class="feather-coffee text-muted display-5"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <h3 class="fw-bold text-dark mb-1">{{ $cuisine->name }}</h3>
                        <p class="text-muted mb-2">Slug: <code>{{ $cuisine->slug }}</code></p>
                        <div>
                            @if($cuisine->status === 'active')
                                <span class="badge bg-success px-3 py-2 rounded-pill">Active</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">Inactive</span>
                            @endif
                            <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill ms-2">Sort Order: {{ $cuisine->sort_order }}</span>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row g-4">
                    <div class="col-12">
                        <h6 class="fw-bold text-dark mb-2"><i class="feather-file-text me-2 text-danger"></i>Description</h6>
                        <p class="text-secondary bg-light p-3 rounded-3 mb-0">{{ $cuisine->description ?? 'No description provided.' }}</p>
                    </div>

                    <div class="col-12">
                        <h6 class="fw-bold text-dark mb-3"><i class="feather-shopping-bag me-2 text-danger"></i>Assigned Restaurants ({{ $cuisine->restaurants->count() }})</h6>
                        <div class="row g-3">
                            @forelse($cuisine->restaurants as $restaurant)
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
                                        @if($restaurant->logo)
                                            <img src="{{ asset($restaurant->logo) }}" alt="Logo" class="rounded-circle border" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; background-color: #cb202d;">
                                                {{ strtoupper(substr($restaurant->restaurant_name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 text-dark fw-bold">{{ $restaurant->restaurant_name }}</h6>
                                            <span class="fs-12 text-muted">{{ $restaurant->owner_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-muted fs-12">No restaurants assigned to this cuisine yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

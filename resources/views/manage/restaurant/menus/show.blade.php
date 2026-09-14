@extends('layouts.restaurant.main')

@section('title', 'Menu Details - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Menu Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.menus.index') }}">Menus</a></li>
                <li class="breadcrumb-item">{{ $menu->name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.menus.edit', $menu->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit me-1"></i> Edit
            </a>
            <a href="{{ route('restaurant.menus.index') }}" class="btn btn-light border text-secondary fw-semibold">
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
                        @if($menu->image)
                            <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}" class="rounded-3 border" style="width: 140px; height: 140px; object-fit: cover;">
                        @else
                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border" style="width: 140px; height: 140px;">
                                <i class="feather-book-open text-muted display-5"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <h3 class="fw-bold text-dark mb-1">{{ $menu->name }}</h3>
                        <p class="text-muted mb-2">Slug: <code>{{ $menu->slug }}</code></p>
                        <div class="d-flex gap-2 align-items-center">
                            @if($menu->status === 'active')
                                <span class="badge bg-success px-3 py-1 rounded-pill">Active</span>
                            @else
                                <span class="badge bg-secondary px-3 py-1 rounded-pill">Inactive</span>
                            @endif
                            <span class="badge bg-soft-info text-info px-3 py-1 rounded-pill">Sort: {{ $menu->sort_order }}</span>
                            <span class="badge bg-soft-warning text-warning px-3 py-1 rounded-pill">{{ $restaurant->restaurant_name }}</span>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold text-dark mb-3"><i class="feather-image me-2 text-danger"></i>Full Menu Image</h6>
                <div class="text-center bg-light rounded-3 p-4">
                    @if($menu->image)
                        <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}" class="rounded-2 border shadow-sm"
                            style="max-width: 100%; max-height: 480px; object-fit: contain;">
                    @else
                        <div class="rounded-3 bg-white d-flex align-items-center justify-content-center border mx-auto"
                            style="width: 200px; height: 200px;">
                            <i class="feather-image text-muted fs-1"></i>
                        </div>
                        <p class="text-muted mt-3 mb-0">No image uploaded.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
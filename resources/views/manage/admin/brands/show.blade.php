@extends('layouts.admin.main')

@section('title', 'Brand Details: ' . $brand->name . ' - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Brand Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.brands.index') }}">Brands</a></li>
                <li class="breadcrumb-item">{{ $brand->name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit-2 me-1"></i> Edit Brand
            </a>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="row">
            <!-- Brand Overview Card -->
            <div class="col-lg-8">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold">Brand Profile</h5>
                        <span class="badge {{ $brand->status === 'active' ? 'bg-success' : 'bg-danger' }} fs-12 px-3 py-2 text-uppercase fw-bold shadow-sm">
                            <i class="feather-power me-1"></i> {{ ucfirst($brand->status) }}
                        </span>
                    </div>

                    <div class="card-body p-4">
                        <!-- Logo & Brand Header -->
                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3 border">
                            <div class="bg-white p-2 rounded border shadow-sm" style="width: 85px; height: 85px; flex-shrink: 0;">
                                @if($brand->logo)
                                    <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="w-100 h-100" style="object-fit: contain;">
                                @else
                                    <div class="w-100 h-100 bg-soft-secondary d-flex align-items-center justify-content-center">
                                        <i class="feather-award fs-2 text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ms-3">
                                <h3 class="fw-bold mb-1 text-dark">{{ $brand->name }}</h3>
                                <div>
                                    <span class="text-muted fs-12 me-1">Slug:</span>
                                    <code class="text-primary bg-soft-primary px-2 py-1 rounded fs-13">{{ $brand->slug }}</code>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <h6 class="fw-bold text-dark mb-2">About Brand</h6>
                        <div class="p-3 bg-light rounded-3 text-secondary border">
                            @if($brand->description)
                                <p class="mb-0 fs-14 leading-relaxed" style="white-space: pre-line;">{{ $brand->description }}</p>
                            @else
                                <span class="text-muted fst-italic fs-13">No description provided.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit & Metadata Sidebar -->
            <div class="col-lg-4">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">Brand Meta Info</h5>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                            <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                <span class="text-muted fs-13"><i class="feather-hash me-1"></i> ID</span>
                                <span class="fw-bold text-dark fs-13">#{{ $brand->id }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                <span class="text-muted fs-13"><i class="feather-check-circle me-1"></i> Status</span>
                                <span class="badge {{ $brand->status === 'active' ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }} fs-12 px-2 py-1">
                                    {{ ucfirst($brand->status) }}
                                </span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                <span class="text-muted fs-13"><i class="feather-user me-1"></i> Created By</span>
                                <span class="fw-medium text-dark fs-13">{{ $brand->creator->name ?? 'Admin' }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                <span class="text-muted fs-13"><i class="feather-calendar me-1"></i> Created At</span>
                                <span class="text-dark fs-13">{{ $brand->created_at ? $brand->created_at->format('M d, Y h:i A') : '-' }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                <span class="text-muted fs-13"><i class="feather-user-check me-1"></i> Updated By</span>
                                <span class="fw-medium text-dark fs-13">{{ $brand->updater->name ?? '-' }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13"><i class="feather-clock me-1"></i> Last Updated</span>
                                <span class="text-dark fs-13">{{ $brand->updated_at ? $brand->updated_at->format('M d, Y h:i A') : '-' }}</span>
                            </li>
                        </ul>

                        <div class="mt-4 pt-3 border-top d-grid gap-2">
                            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-primary fw-semibold">
                                <i class="feather-edit-2 me-1"></i> Edit Details
                            </a>
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Move this brand to trash?');">
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
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

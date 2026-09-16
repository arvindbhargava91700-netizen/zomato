@extends('layouts.admin.main')

@section('title', getPageTitle('Nightlife Banner Details: ' . $nightlifeBanner->title . ''))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Nightlife Banner Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.nightlife-banners.index') }}">Nightlife Banners</a></li>
                <li class="breadcrumb-item">{{ $nightlifeBanner->title }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.nightlife-banners.edit', $nightlifeBanner->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit-2 me-1"></i> Edit Banner
            </a>
            <a href="{{ route('admin.nightlife-banners.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="row">
            <!-- Banner Overview Card -->
            <div class="col-lg-8">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                    <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold">Nightlife Banner Profile</h5>
                        <span class="badge {{ $nightlifeBanner->status === 'active' ? 'bg-success' : 'bg-danger' }} fs-12 px-3 py-2 text-uppercase fw-bold shadow-sm">
                            <i class="feather-power me-1"></i> {{ ucfirst($nightlifeBanner->status) }}
                        </span>
                    </div>

                    <div class="card-body p-4">
                        <!-- Banner Image -->
                        @if($nightlifeBanner->banner)
                            <div class="mb-4 rounded-3 border overflow-hidden shadow-sm">
                                <img src="{{ asset($nightlifeBanner->banner) }}" alt="{{ $nightlifeBanner->title }}" class="w-100" style="max-height: 320px; object-fit: cover;">
                            </div>
                        @else
                            <div class="mb-4 rounded-3 border bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <div class="text-center text-muted">
                                    <i class="feather-image fs-3 d-block mb-2"></i>
                                    <span class="fs-13">No banner image uploaded</span>
                                </div>
                            </div>
                        @endif

                        <!-- Title & Header -->
                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3 border">
                            <div class="bg-white p-3 rounded border shadow-sm d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; flex-shrink: 0;">
                                <i class="feather-moon fs-3" style="color: #8b5cf6;"></i>
                            </div>
                            <div class="ms-3">
                                <h3 class="fw-bold mb-1 text-dark">{{ $nightlifeBanner->title }}</h3>
                                <div>
                                    <span class="text-muted fs-12 me-1">Slug:</span>
                                    <code class="text-primary bg-soft-primary px-2 py-1 rounded fs-13">{{ $nightlifeBanner->slug }}</code>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <h6 class="fw-bold text-dark mb-2">Description</h6>
                        <div class="p-3 bg-light rounded-3 text-secondary border">
                            @if($nightlifeBanner->description)
                                <p class="mb-0 fs-14 leading-relaxed" style="white-space: pre-line;">{{ $nightlifeBanner->description }}</p>
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
                        <h5 class="card-title mb-0 fw-bold">Banner Meta Info</h5>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-unstyled mb-0 d-flex flex-column gap-3">
                            <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                <span class="text-muted fs-13"><i class="feather-hash me-1"></i> ID</span>
                                <span class="fw-bold text-dark fs-13">#{{ $nightlifeBanner->id }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                <span class="text-muted fs-13"><i class="feather-check-circle me-1"></i> Status</span>
                                <span class="badge {{ $nightlifeBanner->status === 'active' ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }} fs-12 px-2 py-1">
                                    {{ ucfirst($nightlifeBanner->status) }}
                                </span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                <span class="text-muted fs-13"><i class="feather-user me-1"></i> Created By</span>
                                <span class="fw-medium text-dark fs-13">{{ $nightlifeBanner->creator->name ?? 'Admin' }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                <span class="text-muted fs-13"><i class="feather-calendar me-1"></i> Created At</span>
                                <span class="text-dark fs-13">{{ $nightlifeBanner->created_at ? $nightlifeBanner->created_at->format('M d, Y h:i A') : '-' }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center pb-2 border-bottom">
                                <span class="text-muted fs-13"><i class="feather-user-check me-1"></i> Updated By</span>
                                <span class="fw-medium text-dark fs-13">{{ $nightlifeBanner->updater->name ?? '-' }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <span class="text-muted fs-13"><i class="feather-clock me-1"></i> Last Updated</span>
                                <span class="text-dark fs-13">{{ $nightlifeBanner->updated_at ? $nightlifeBanner->updated_at->format('M d, Y h:i A') : '-' }}</span>
                            </li>
                        </ul>

                        <div class="mt-4 pt-3 border-top d-grid gap-2">
                            <a href="{{ route('admin.nightlife-banners.edit', $nightlifeBanner->id) }}" class="btn btn-primary fw-semibold">
                                <i class="feather-edit-2 me-1"></i> Edit Details
                            </a>
                            <form action="{{ route('admin.nightlife-banners.destroy', $nightlifeBanner->id) }}" method="POST" onsubmit="return confirm('Move this nightlife banner to trash?');">
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
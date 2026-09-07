@extends('layouts.restaurant.main')

@section('title', 'Preview Blog - ' . $blog->title)

@section('content')
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10"><i class="feather-file-text me-2 text-primary"></i> Blog Article Preview</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.blogs.index') }}">Blogs</a></li>
                <li class="breadcrumb-item active">Preview</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            @if($blog->isLive())
                <a href="{{ route('blog.show', ['slug' => $blog->slug]) }}" target="_blank" class="btn btn-success fw-semibold">
                    <i class="feather-external-link me-1"></i> View Live on Website
                </a>
            @endif


            <a href="{{ route('restaurant.blogs.edit', $blog->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit-2 me-1"></i> Edit Article
            </a>
            <a href="{{ route('restaurant.blogs.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="row g-4">
            <!-- Left Column: Article Body -->
            <div class="col-lg-8 col-12">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                    @if($blog->featured_image)
                        <img src="{{ asset($blog->featured_image) }}"
                             alt="{{ $blog->title }}"
                             class="w-100 object-fit-cover border-bottom"
                             style="max-height: 380px;">
                    @endif

                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center gap-3 fs-13 text-muted mb-3 flex-wrap">
                            <span><i class="feather-calendar text-primary me-1"></i> {{ $blog->created_at->format('F d, Y') }}</span>
                            <span><i class="feather-clock text-warning me-1"></i> {{ $blog->reading_time }} min read</span>
                            <span><i class="feather-eye text-info me-1"></i> {{ number_format($blog->views_count) }} views</span>
                        </div>

                        <h2 class="fw-bold text-dark mb-3">{{ $blog->title }}</h2>

                        @if($blog->short_description)
                            <div class="p-3 bg-light rounded-3 border-start border-4 border-primary fs-15 text-muted fst-italic mb-4">
                                {{ $blog->short_description }}
                            </div>
                        @endif

                        <div class="blog-article-content fs-15 text-dark lh-lg" style="line-height: 1.8;">
                            {!! nl2br(e($blog->content)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Meta, Status & Admin Remarks -->
            <div class="col-lg-4 col-12">
                <!-- Status & Workflow Card -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header p-4 border-bottom bg-white">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="feather-activity me-2 text-primary"></i> Status & Approval Details
                        </h6>
                    </div>
                    <div class="card-body p-4 fs-13">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Publishing Status:</span>
                            @if($blog->status === 'published')
                                <span class="badge bg-soft-primary text-primary fs-11 text-uppercase">Published</span>
                            @elseif($blog->status === 'draft')
                                <span class="badge bg-soft-secondary text-secondary fs-11 text-uppercase">Draft</span>
                            @else
                                <span class="badge bg-soft-danger text-danger fs-11 text-uppercase">Inactive</span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Admin Approval:</span>
                            @if($blog->approval_status === 'approved')
                                <span class="badge bg-soft-success text-success fs-11">
                                    <i class="feather-check-circle me-1"></i> Approved
                                </span>
                            @elseif($blog->approval_status === 'pending')
                                <span class="badge bg-soft-warning text-warning fs-11">
                                    <i class="feather-clock me-1"></i> Under Review
                                </span>
                            @elseif($blog->approval_status === 'rejected')
                                <span class="badge bg-soft-danger text-danger fs-11">
                                    <i class="feather-x-circle me-1"></i> Rejected
                                </span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Public Visibility:</span>
                            @if($blog->isLive())
                                <span class="badge bg-success text-white fs-11">Live on Website</span>
                            @else
                                <span class="badge bg-light text-muted border fs-11">Hidden from Public</span>
                            @endif
                        </div>

                        @if($blog->approved_at)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Approved On:</span>
                                <span class="fw-semibold text-dark">{{ $blog->approved_at->format('M d, Y h:i A') }}</span>
                            </div>
                        @endif

                        @if($blog->approval_status === 'rejected' && $blog->admin_remarks)
                            <div class="p-3 bg-soft-danger rounded-3 border border-danger border-opacity-25 mt-3">
                                <div class="fw-bold text-danger mb-1"><i class="feather-alert-triangle me-1"></i> Rejection Reason:</div>
                                <div class="text-dark fs-12">{{ $blog->admin_remarks }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Author / Restaurant Card -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header p-4 border-bottom bg-white">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="feather-user me-2 text-primary"></i> Restaurant & Author
                        </h6>
                    </div>
                    <div class="card-body p-4 fs-13">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $restaurant->logo ? asset($restaurant->logo) : asset('front/assets/images/logo/logo.png') }}"
                                 alt="{{ $restaurant->restaurant_name }}"
                                 class="rounded-circle border"
                                 style="width: 48px; height: 48px; object-fit: cover;">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $restaurant->restaurant_name }}</h6>
                                <span class="text-muted fs-12">{{ $restaurant->address }}</span>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 border fs-12">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Slug:</span>
                                <span class="font-monospace text-dark">?slug={{ $blog->slug }}</span>
                            </div>
                            <div class="d-flex justify-content-between">

                                <span class="text-muted">Author:</span>
                                <span class="fw-semibold text-dark">{{ $blog->author?->name ?? 'Restaurant Owner' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

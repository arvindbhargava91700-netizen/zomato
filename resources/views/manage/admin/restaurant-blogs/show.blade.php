@extends('layouts.admin.main')

@section('title', $title)

@section('content')
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10"><i class="feather-check-square me-2 text-primary"></i> Review Blog Article</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurant-blogs.index') }}">Restaurant Blogs</a></li>
                <li class="breadcrumb-item active">Review</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.restaurant-blogs.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to Listing
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="feather-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left Column: Article Body -->
            <div class="col-lg-8 col-12">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                    @if($restaurant_blog->featured_image)
                        <img src="{{ asset($restaurant_blog->featured_image) }}"
                             alt="{{ $restaurant_blog->title }}"
                             class="w-100 object-fit-cover border-bottom"
                             style="max-height: 380px;">
                    @endif

                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center gap-3 fs-13 text-muted mb-3 flex-wrap">
                            <span><i class="feather-calendar text-primary me-1"></i> Submitted: {{ $restaurant_blog->created_at->format('F d, Y h:i A') }}</span>
                            <span><i class="feather-clock text-warning me-1"></i> {{ $restaurant_blog->reading_time }} min read</span>
                            <span><i class="feather-eye text-info me-1"></i> {{ number_format($restaurant_blog->views_count) }} views</span>
                        </div>

                        <h2 class="fw-bold text-dark mb-3">{{ $restaurant_blog->title }}</h2>

                        @if($restaurant_blog->short_description)
                            <div class="p-3 bg-light rounded-3 border-start border-4 border-primary fs-15 text-muted fst-italic mb-4">
                                {{ $restaurant_blog->short_description }}
                            </div>
                        @endif

                        <div class="blog-article-content fs-15 text-dark lh-lg" style="line-height: 1.8;">
                            {!! nl2br(e($restaurant_blog->content)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Admin Actions & Restaurant Meta -->
            <div class="col-lg-4 col-12">
                <!-- Action / Decision Card -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header p-4 border-bottom bg-white">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="feather-shield me-2 text-primary"></i> Approval Decision
                        </h6>
                    </div>
                    <div class="card-body p-4 fs-13">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Current Status:</span>
                            @if($restaurant_blog->approval_status === 'approved')
                                <span class="badge bg-soft-success text-success fs-12 px-3 py-2">
                                    <i class="feather-check-circle me-1"></i> Approved
                                </span>
                            @elseif($restaurant_blog->approval_status === 'pending')
                                <span class="badge bg-soft-warning text-warning fs-12 px-3 py-2">
                                    <i class="feather-clock me-1"></i> Pending Review
                                </span>
                            @elseif($restaurant_blog->approval_status === 'rejected')
                                <span class="badge bg-soft-danger text-danger fs-12 px-3 py-2">
                                    <i class="feather-x-circle me-1"></i> Rejected
                                </span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Publishing State:</span>
                            <span class="badge {{ $restaurant_blog->status === 'published' ? 'bg-soft-primary text-primary' : 'bg-soft-secondary text-secondary' }} fs-11 text-uppercase">
                                {{ ucfirst($restaurant_blog->status) }}
                            </span>
                        </div>

                        @if($restaurant_blog->approved_at)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Decision Date:</span>
                                <span class="fw-semibold text-dark">{{ $restaurant_blog->approved_at->format('M d, Y h:i A') }}</span>
                            </div>
                        @endif

                        @if($restaurant_blog->approval_status === 'rejected' && $restaurant_blog->admin_remarks)
                            <div class="p-3 bg-soft-danger rounded-3 border border-danger border-opacity-25 mb-3">
                                <div class="fw-bold text-danger mb-1"><i class="feather-alert-triangle me-1"></i> Rejection Reason:</div>
                                <div class="text-dark fs-12">{{ $restaurant_blog->admin_remarks }}</div>
                            </div>
                        @endif

                        <hr class="my-3">

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            @if($restaurant_blog->approval_status !== 'approved')
                                <form action="{{ route('admin.restaurant-blogs.approve', $restaurant_blog->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 fw-bold py-2" onclick="return confirm('Are you sure you want to APPROVE this blog? It will be published live immediately.');">
                                        <i class="feather-check-circle me-1"></i> Approve & Publish Live
                                    </button>
                                </form>
                            @endif

                            @if($restaurant_blog->approval_status !== 'rejected')
                                <button type="button" class="btn btn-outline-danger w-100 fw-semibold py-2" data-bs-toggle="modal" data-bs-target="#rejectBlogModal">
                                    <i class="feather-x-circle me-1"></i> Reject Article
                                </button>
                            @endif

                            @if($restaurant_blog->isLive())
                                <a href="{{ route('blog.show', ['slug' => $restaurant_blog->slug]) }}" target="_blank" class="btn btn-light border text-primary w-100 fw-semibold">
                                    <i class="feather-external-link me-1"></i> View Live Article
                                </a>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- Restaurant / Author Info -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header p-4 border-bottom bg-white">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="feather-shopping-bag me-2 text-primary"></i> Restaurant & Author
                        </h6>
                    </div>
                    <div class="card-body p-4 fs-13">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $restaurant_blog->restaurant?->logo ? asset($restaurant_blog->restaurant->logo) : asset('front/assets/images/logo/logo.png') }}"
                                 alt="{{ $restaurant_blog->restaurant?->restaurant_name }}"
                                 class="rounded-circle border"
                                 style="width: 48px; height: 48px; object-fit: cover;">
                            <div>
                                <h6 class="fw-bold mb-0 text-dark">{{ $restaurant_blog->restaurant?->restaurant_name ?? '—' }}</h6>
                                <span class="text-muted fs-12">{{ $restaurant_blog->restaurant?->address ?? '' }}</span>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 border fs-12">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Slug:</span>
                                <span class="font-monospace text-dark">?slug={{ $restaurant_blog->slug }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Author / Owner:</span>
                                <span class="fw-semibold text-dark">{{ $restaurant_blog->author?->name ?? 'Restaurant Owner' }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectBlogModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form action="{{ route('admin.restaurant-blogs.reject', $restaurant_blog->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-bold text-danger">
                            <i class="feather-alert-triangle me-2"></i> Reject Blog Article
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted fs-13 mb-3">Please provide a clear reason why this blog article is being rejected. This feedback will be sent to the restaurant owner so they can revise and re-submit.</p>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Rejection Reason / Admin Remarks <span class="text-danger">*</span></label>
                            <textarea name="admin_remarks"
                                      class="form-control"
                                      rows="4"
                                      placeholder="E.g. Inappropriate content, low resolution image, misleading recipe details, etc..."
                                      required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger fw-bold px-4">
                            Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

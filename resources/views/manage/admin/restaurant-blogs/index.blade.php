@extends('layouts.admin.main')

@section('title', 'Restaurant Blogs Management - Super Admin')

@section('content')
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10"><i class="feather-edit-3 me-2 text-primary"></i> Restaurant Blogs & Stories Review</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Restaurants Management</li>
                <li class="breadcrumb-item active">Blogs</li>
            </ul>
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

        <!-- Summary Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xxl-3 col-md-6 col-12">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="avatar-lg bg-soft-primary text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 22px;">
                            <i class="feather-file-text"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Total Articles</div>
                            <div class="fs-4 fw-bold text-dark">{{ $counts['all'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6 col-12">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="avatar-lg bg-soft-warning text-warning rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 22px;">
                            <i class="feather-clock"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Pending Review</div>
                            <div class="fs-4 fw-bold text-warning">{{ $counts['pending'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6 col-12">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="avatar-lg bg-soft-success text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 22px;">
                            <i class="feather-check-circle"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Approved & Live</div>
                            <div class="fs-4 fw-bold text-success">{{ $counts['approved'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6 col-12">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="avatar-lg bg-soft-danger text-danger rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 22px;">
                            <i class="feather-x-circle"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Rejected</div>
                            <div class="fs-4 fw-bold text-dark">{{ $counts['rejected'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.restaurant-blogs.index') }}" method="GET" class="row g-3 align-items-center">
                    <div class="col-lg-7 col-12">
                        <div class="btn-group flex-wrap" role="group">
                            <a href="{{ route('admin.restaurant-blogs.index') }}"
                               class="btn btn-sm {{ !request('approval_status') ? 'btn-primary' : 'btn-light' }}">
                                All ({{ $counts['all'] }})
                            </a>
                            <a href="{{ route('admin.restaurant-blogs.index', ['approval_status' => 'pending']) }}"
                               class="btn btn-sm {{ request('approval_status') === 'pending' ? 'btn-warning text-dark' : 'btn-light' }}">
                                <i class="feather-clock me-1"></i> Pending ({{ $counts['pending'] }})
                            </a>
                            <a href="{{ route('admin.restaurant-blogs.index', ['approval_status' => 'approved']) }}"
                               class="btn btn-sm {{ request('approval_status') === 'approved' ? 'btn-success' : 'btn-light' }}">
                                <i class="feather-check-circle me-1"></i> Approved ({{ $counts['approved'] }})
                            </a>
                            <a href="{{ route('admin.restaurant-blogs.index', ['approval_status' => 'rejected']) }}"
                               class="btn btn-sm {{ request('approval_status') === 'rejected' ? 'btn-danger' : 'btn-light' }}">
                                <i class="feather-x-circle me-1"></i> Rejected ({{ $counts['rejected'] }})
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-5 col-12">
                        <div class="input-group">
                            <input type="text"
                                   name="search"
                                   class="form-control form-control-sm"
                                   placeholder="Search by article title or restaurant..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary btn-sm" type="submit">
                                <i class="feather-search"></i>
                            </button>
                            @if(request('search') || request('approval_status'))
                                <a href="{{ route('admin.restaurant-blogs.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear">
                                    <i class="feather-x"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Listing -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                @if($blogs->isEmpty())
                    <div class="p-5 text-center">
                        <div class="avatar-lg bg-soft-primary text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 26px;">
                            <i class="feather-file-text"></i>
                        </div>
                        <h5 class="fw-bold mb-1">No Restaurant Blogs Found</h5>
                        <p class="text-muted fs-13 mb-0">No blog submissions matching your filter.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4" style="min-width: 260px;">Article</th>
                                    <th>Restaurant</th>
                                    <th>Status</th>
                                    <th>Approval Status</th>
                                    <th>Views</th>
                                    <th>Submitted Date</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($blogs as $b)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $b->featured_image ? asset($b->featured_image) : asset('front/assets/images/blog/blog-grid/1.png') }}"
                                                     alt="{{ $b->title }}"
                                                     class="rounded-3 border object-fit-cover shadow-sm"
                                                     style="width: 54px; height: 44px;">
                                                <div>
                                                    <a href="{{ route('admin.restaurant-blogs.show', $b->id) }}" class="fw-bold text-dark text-decoration-none d-block hover-primary">
                                                        {{ Str::limit($b->title, 45) }}
                                                    </a>
                                                    <span class="fs-12 text-muted font-monospace">?slug={{ $b->slug }}</span>
                                                </div>

                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $b->restaurant?->restaurant_name ?? '—' }}</div>
                                            <span class="fs-12 text-muted">{{ $b->author?->name ?? 'Owner' }}</span>
                                        </td>
                                        <td>
                                            @if($b->status === 'published')
                                                <span class="badge bg-soft-primary text-primary fs-11 text-uppercase">Published</span>
                                            @elseif($b->status === 'draft')
                                                <span class="badge bg-soft-secondary text-secondary fs-11 text-uppercase">Draft</span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger fs-11 text-uppercase">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($b->approval_status === 'approved')
                                                <span class="badge bg-soft-success text-success fs-11">
                                                    <i class="feather-check-circle me-1"></i> Approved
                                                </span>
                                            @elseif($b->approval_status === 'pending')
                                                <span class="badge bg-soft-warning text-warning fs-11">
                                                    <i class="feather-clock me-1"></i> Pending Review
                                                </span>
                                            @elseif($b->approval_status === 'rejected')
                                                <span class="badge bg-soft-danger text-danger fs-11" title="{{ $b->admin_remarks }}">
                                                    <i class="feather-x-circle me-1"></i> Rejected
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fs-13 text-muted"><i class="feather-eye me-1"></i> {{ number_format($b->views_count) }}</span>
                                        </td>
                                        <td>
                                            <span class="fs-12 text-muted">{{ $b->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.restaurant-blogs.show', $b->id) }}" class="btn btn-sm btn-primary fw-semibold px-3">
                                                <i class="feather-check-square me-1"></i> Review
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($blogs->hasPages())
                        <div class="p-4 border-top">
                            {{ $blogs->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

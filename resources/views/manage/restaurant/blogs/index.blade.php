@extends('layouts.restaurant.main')

@section('title', 'My Restaurant Blogs & Stories')

@section('content')
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10"><i class="feather-edit-3 me-2 text-primary"></i> Restaurant Blogs & Stories</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Blogs</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.blogs.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add New Blog
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="feather-alert-triangle me-2"></i> {{ session('error') }}
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
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Total Blogs</div>
                            <div class="fs-4 fw-bold text-dark">{{ $counts['all'] }} <span class="fs-13 text-muted fw-normal">Articles</span></div>
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
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Live on Website</div>
                            <div class="fs-4 fw-bold text-success">{{ $counts['approved'] }} <span class="fs-13 text-muted fw-normal">Approved</span></div>
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
                            <div class="fs-12 text-muted fw-semibold text-uppercase">In Review</div>
                            <div class="fs-4 fw-bold text-warning">{{ $counts['pending'] }} <span class="fs-13 text-muted fw-normal">Pending Admin</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6 col-12">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="avatar-lg bg-soft-secondary text-secondary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 22px;">
                            <i class="feather-edit-2"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted fw-semibold text-uppercase">Drafts</div>
                            <div class="fs-4 fw-bold text-dark">{{ $counts['draft'] }} <span class="fs-13 text-muted fw-normal">Saved</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('restaurant.blogs.index') }}" method="GET" class="row g-3 align-items-center">
                    <!-- Status Filter Tabs / Select -->
                    <div class="col-lg-7 col-12">
                        <div class="btn-group flex-wrap" role="group">
                            <a href="{{ route('restaurant.blogs.index') }}"
                               class="btn btn-sm {{ !request('approval_status') && !request('status') ? 'btn-primary' : 'btn-light' }}">
                                All ({{ $counts['all'] }})
                            </a>
                            <a href="{{ route('restaurant.blogs.index', ['approval_status' => 'approved']) }}"
                               class="btn btn-sm {{ request('approval_status') === 'approved' ? 'btn-success' : 'btn-light' }}">
                                <i class="feather-check-circle me-1"></i> Live ({{ $counts['approved'] }})
                            </a>
                            <a href="{{ route('restaurant.blogs.index', ['approval_status' => 'pending']) }}"
                               class="btn btn-sm {{ request('approval_status') === 'pending' ? 'btn-warning text-dark' : 'btn-light' }}">
                                <i class="feather-clock me-1"></i> Pending ({{ $counts['pending'] }})
                            </a>
                            <a href="{{ route('restaurant.blogs.index', ['status' => 'draft']) }}"
                               class="btn btn-sm {{ request('status') === 'draft' ? 'btn-secondary' : 'btn-light' }}">
                                <i class="feather-edit me-1"></i> Drafts ({{ $counts['draft'] }})
                            </a>
                            @if($counts['rejected'] > 0)
                                <a href="{{ route('restaurant.blogs.index', ['approval_status' => 'rejected']) }}"
                                   class="btn btn-sm {{ request('approval_status') === 'rejected' ? 'btn-danger' : 'btn-light' }}">
                                    <i class="feather-x-circle me-1"></i> Rejected ({{ $counts['rejected'] }})
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="col-lg-5 col-12">
                        <div class="input-group">
                            <input type="text"
                                   name="search"
                                   class="form-control form-control-sm"
                                   placeholder="Search by blog title or keyword..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-primary btn-sm" type="submit">
                                <i class="feather-search"></i>
                            </button>
                            @if(request('search') || request('status') || request('approval_status'))
                                <a href="{{ route('restaurant.blogs.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear Filters">
                                    <i class="feather-x"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Blogs List Table -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                @if($blogs->isEmpty())
                    <div class="text-center py-5 border rounded-3 bg-light m-4">
                        <div class="avatar-lg bg-soft-primary text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 26px;">
                            <i class="feather-edit-3"></i>
                        </div>
                        <h5 class="fw-bold mb-1">No Blogs Found</h5>
                        <p class="text-muted small max-w-400 mx-auto mb-3">Share your special recipes, restaurant stories, chef secrets, and food guides with customers.</p>
                        <a href="{{ route('restaurant.blogs.create') }}" class="btn btn-primary fw-semibold">
                            <i class="feather-plus me-1"></i> Create First Blog
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border mb-0" id="customerList">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th style="min-width: 280px;">Blog Article</th>
                                    <th>Status</th>
                                    <th>Admin Approval</th>
                                    <th>Views</th>
                                    <th>Created Date</th>
                                    <th class="text-end" style="width: 170px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($blogs as $idx => $blog)
                                    <tr>
                                        <td class="text-muted fw-semibold">
                                            {{ $blogs->firstItem() ? $blogs->firstItem() + $idx : $idx + 1 }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $blog->featured_image ? asset($blog->featured_image) : asset('front/assets/images/blog/blog-grid/1.png') }}"
                                                     alt="{{ $blog->title }}"
                                                     class="rounded-3 border object-fit-cover shadow-sm"
                                                     style="width: 55px; height: 45px;">
                                                <div>
                                                    <a href="{{ route('restaurant.blogs.show', $blog->id) }}" class="fw-bold text-dark text-decoration-none d-block hover-primary">
                                                        {{ Str::limit($blog->title, 50) }}
                                                    </a>
                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                        @if($blog->category)
                                                            <span class="badge bg-soft-info text-info fs-11">{{ $blog->category->name }}</span>
                                                        @endif
                                                        <span class="fs-12 text-muted font-monospace">?slug={{ $blog->slug }}</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </td>
                                        <td>
                                            @if($blog->status === 'published')
                                                <span class="badge bg-soft-success text-success px-2 py-1">
                                                    <i class="feather-check-circle me-1"></i> Published
                                                </span>
                                            @elseif($blog->status === 'draft')
                                                <span class="badge bg-soft-secondary text-secondary px-2 py-1">
                                                    <i class="feather-file-text me-1"></i> Draft
                                                </span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger px-2 py-1">
                                                    <i class="feather-slash me-1"></i> Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($blog->approval_status === 'approved')
                                                <span class="badge bg-soft-success text-success px-2 py-1">
                                                    <i class="feather-check-circle me-1"></i> Approved (Live)
                                                </span>
                                            @elseif($blog->approval_status === 'pending')
                                                <span class="badge bg-soft-warning text-warning px-2 py-1">
                                                    <i class="feather-clock me-1"></i> Pending Review
                                                </span>
                                            @elseif($blog->approval_status === 'rejected')
                                                <div>
                                                    <span class="badge bg-soft-danger text-danger px-2 py-1">
                                                        <i class="feather-x-circle me-1"></i> Rejected
                                                    </span>
                                                    @if($blog->admin_remarks)
                                                        <button type="button" class="btn btn-link btn-sm text-danger p-0 d-block fs-11 text-decoration-underline"
                                                                data-bs-toggle="modal" data-bs-target="#reasonModal{{ $blog->id }}">
                                                            View Reason
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fs-13 text-muted"><i class="feather-eye me-1"></i> {{ number_format($blog->views_count) }}</span>
                                        </td>
                                        <td>
                                            <span class="fs-12 text-muted">{{ $blog->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">
                                                <!-- Toggle Status (Published / Draft) -->
                                                <form action="{{ route('restaurant.blogs.toggle-status', $blog->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-light border p-1 px-2"
                                                            title="{{ $blog->status === 'published' ? 'Switch to Draft' : 'Publish Blog' }}">
                                                        <i class="feather-power text-{{ $blog->status === 'published' ? 'success' : 'secondary' }}"></i>
                                                    </button>
                                                </form>

                                                <!-- Preview Blog Button -->
                                                <a href="{{ route('restaurant.blogs.show', $blog->id) }}"
                                                   class="btn btn-sm btn-light border p-1 px-2 text-info"
                                                   title="Preview Blog">
                                                    <i class="feather-eye"></i>
                                                </a>

                                                <!-- Live Storefront Link (if Live) -->
                                                @if($blog->isLive())
                                                    <a href="{{ route('blog.show', ['slug' => $blog->slug]) }}"
                                                       target="_blank"
                                                       class="btn btn-sm btn-light border p-1 px-2 text-success"
                                                       title="View Live on Website">
                                                        <i class="feather-external-link"></i>
                                                    </a>
                                                @endif



                                                <!-- Edit Blog Button -->
                                                <a href="{{ route('restaurant.blogs.edit', $blog->id) }}"
                                                   class="btn btn-sm btn-light border p-1 px-2 text-primary"
                                                   title="Edit Blog">
                                                    <i class="feather-edit-2"></i>
                                                </a>

                                                <!-- Delete Button -->
                                                <form action="{{ route('restaurant.blogs.destroy', $blog->id) }}"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this blog?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-light border p-1 px-2 text-danger"
                                                            title="Delete Blog">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    @if($blog->approval_status === 'rejected' && $blog->admin_remarks)
                                        <!-- Rejection Reason Modal -->
                                        <div class="modal fade" id="reasonModal{{ $blog->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-3">
                                                    <div class="modal-header border-bottom">
                                                        <h5 class="modal-title fw-bold text-danger">
                                                            <i class="feather-alert-triangle me-2"></i> Rejection Reason
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <p class="fw-semibold text-dark mb-1">Blog: {{ $blog->title }}</p>
                                                        <div class="p-3 bg-soft-danger text-danger rounded-3 border border-danger border-opacity-25 mt-2 fs-13">
                                                            {{ $blog->admin_remarks }}
                                                        </div>
                                                        <p class="text-muted small mt-3 mb-0">
                                                            Please edit your blog to address the feedback and update it to re-submit for admin approval.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-top bg-light">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                        <a href="{{ route('restaurant.blogs.edit', $blog->id) }}" class="btn btn-primary btn-sm">
                                                            <i class="feather-edit-2 me-1"></i> Edit Blog Now
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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

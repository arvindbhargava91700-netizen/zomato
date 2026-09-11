@extends('layouts.admin.main')

@section('title', 'Nightlife Banners Management - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Nightlife Banners Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Nightlife Banners</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.nightlife-banners.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add New Banner
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="feather-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="feather-alert-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('admin.nightlife-banners.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by title, slug, description..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">Search</button>
                        <a href="{{ route('admin.nightlife-banners.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                        @if(request()->boolean('trashed'))
                            <a href="{{ route('admin.nightlife-banners.index') }}" class="btn btn-outline-secondary fw-semibold">
                                <i class="feather-list me-1"></i> All Banners
                            </a>
                        @else
                            <a href="{{ route('admin.nightlife-banners.index', ['trashed' => 1]) }}" class="btn btn-outline-danger fw-semibold" title="View Trashed Banners">
                                <i class="feather-trash-2 me-1"></i> Trashed
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold">
                    {{ request()->boolean('trashed') ? 'Trashed Nightlife Banners' : 'Nightlife Banners List' }}
                </h5>
                <span class="badge bg-soft-primary text-primary fs-12">{{ $nightlifeBanners->total() }} Total</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 100px;">Banner</th>
                                <th>Title / Heading</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nightlifeBanners as $banner)
                                <tr>
                                    <!-- Banner -->
                                    <td class="ps-4">
                                        @if($banner->banner)
                                            <img src="{{ asset($banner->banner) }}" alt="{{ $banner->title }}" class="rounded border p-1 bg-white shadow-sm" style="width: 70px; height: 48px; object-fit: cover;">
                                        @else
                                            <div class="rounded bg-soft-secondary d-flex align-items-center justify-content-center border" style="width: 70px; height: 48px;">
                                                <i class="feather-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Title & Description snippet -->
                                    <td>
                                        <a href="{{ route('admin.nightlife-banners.show', $banner->id) }}" class="fw-bold text-dark text-decoration-none fs-6 d-block">
                                            {{ $banner->title }}
                                        </a>
                                        @if($banner->description)
                                            <span class="text-muted fs-12 d-inline-block text-truncate" style="max-width: 300px;">
                                                {{ Str::limit($banner->description, 60) }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Slug -->
                                    <td>
                                        <code class="text-primary bg-soft-primary px-2 py-1 rounded fs-12">{{ $banner->slug }}</code>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if(!$banner->trashed())
                                            <form action="{{ route('admin.nightlife-banners.toggle-status', $banner->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="badge border-0 rounded-pill {{ $banner->status === 'active' ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }} fs-11 px-3 py-1 text-uppercase fw-bold cursor-pointer" title="Click to toggle status">
                                                    <i class="feather-power me-1"></i> {{ ucfirst($banner->status) }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-soft-warning text-warning fs-11">Trashed</span>
                                        @endif
                                    </td>

                                    <!-- Created Date -->
                                    <td>
                                        <span class="fs-12 text-muted">{{ $banner->created_at ? $banner->created_at->format('M d, Y') : '-' }}</span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end pe-4">
                                        @if(!$banner->trashed())
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <a href="{{ route('admin.nightlife-banners.show', $banner->id) }}" class="btn btn-sm btn-icon btn-light text-primary" title="View Banner">
                                                    <i class="feather-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.nightlife-banners.edit', $banner->id) }}" class="btn btn-sm btn-icon btn-light text-secondary" title="Edit Banner">
                                                    <i class="feather-edit-2"></i>
                                                </a>
                                                <form action="{{ route('admin.nightlife-banners.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Move this nightlife banner to trash?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-icon btn-light text-danger" title="Move to Trash">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <form action="{{ route('admin.nightlife-banners.restore', $banner->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success fw-semibold">
                                                        <i class="feather-rotate-ccw me-1"></i> Restore
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.nightlife-banners.force-delete', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this nightlife banner? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger fw-semibold">
                                                        <i class="feather-trash-2 me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="feather-moon fs-1 d-block mb-2 text-secondary"></i>
                                            <h6 class="fw-bold">No Nightlife Banners Found</h6>
                                            <p class="fs-12 mb-3">No nightlife banner records match your search criteria.</p>
                                            <a href="{{ route('admin.nightlife-banners.create') }}" class="btn btn-sm btn-danger text-white">
                                                <i class="feather-plus me-1"></i> Create First Banner
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($nightlifeBanners->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $nightlifeBanners->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
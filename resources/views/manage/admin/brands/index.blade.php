@extends('layouts.admin.main')

@section('title', $title)

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Brands Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Brands</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.brands.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add New Brand
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
                <form action="{{ route('admin.brands.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by brand name, slug, description..." value="{{ request('search') }}">
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
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                        @if(request()->boolean('trashed'))
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary fw-semibold">
                                <i class="feather-list me-1"></i> All Brands
                            </a>
                        @else
                            <a href="{{ route('admin.brands.index', ['trashed' => 1]) }}" class="btn btn-outline-danger fw-semibold" title="View Trashed Brands">
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
                    {{ request()->boolean('trashed') ? 'Trashed Brands' : 'Master Brands List' }}
                </h5>
                <span class="badge bg-soft-primary text-primary fs-12">{{ $brands->total() }} Total</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="width: 80px;">Logo</th>
                                <th>Brand Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                                <tr>
                                    <!-- Logo -->
                                    <td class="ps-4">
                                        @if($brand->logo)
                                            <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="rounded border p-1 bg-white shadow-sm" style="width: 48px; height: 48px; object-fit: contain;">
                                        @else
                                            <div class="rounded bg-soft-secondary d-flex align-items-center justify-content-center border" style="width: 48px; height: 48px;">
                                                <i class="feather-award text-muted fs-4"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Brand Name & Description snippet -->
                                    <td>
                                        <a href="{{ route('admin.brands.show', $brand->id) }}" class="fw-bold text-dark text-decoration-none fs-6 d-block">
                                            {{ $brand->name }}
                                        </a>
                                        @if($brand->description)
                                            <span class="text-muted fs-12 d-inline-block text-truncate" style="max-width: 300px;">
                                                {{ Str::limit($brand->description, 60) }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Slug -->
                                    <td>
                                        <code class="text-primary bg-soft-primary px-2 py-1 rounded fs-12">{{ $brand->slug }}</code>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if(!$brand->trashed())
                                            <form action="{{ route('admin.brands.toggle-status', $brand->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="badge border-0 rounded-pill {{ $brand->status === 'active' ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }} fs-11 px-3 py-1 text-uppercase fw-bold cursor-pointer" title="Click to toggle status">
                                                    <i class="feather-power me-1"></i> {{ ucfirst($brand->status) }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-soft-warning text-warning fs-11">Trashed</span>
                                        @endif
                                    </td>

                                    <!-- Created Date -->
                                    <td>
                                        <span class="fs-12 text-muted">{{ $brand->created_at ? $brand->created_at->format('M d, Y') : '-' }}</span>
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-end pe-4">
                                        @if(!$brand->trashed())
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <a href="{{ route('admin.brands.show', $brand->id) }}" class="btn btn-sm btn-icon btn-light text-primary" title="View Brand">
                                                    <i class="feather-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-sm btn-icon btn-light text-secondary" title="Edit Brand">
                                                    <i class="feather-edit-2"></i>
                                                </a>
                                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Move this brand to trash?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-icon btn-light text-danger" title="Move to Trash">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <form action="{{ route('admin.brands.restore', $brand->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success fw-semibold">
                                                        <i class="feather-rotate-ccw me-1"></i> Restore
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.brands.force-delete', $brand->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this brand? This action cannot be undone.');">
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
                                            <i class="feather-award fs-1 d-block mb-2 text-secondary"></i>
                                            <h6 class="fw-bold">No Brands Found</h6>
                                            <p class="fs-12 mb-3">No brand records match your search criteria.</p>
                                            <a href="{{ route('admin.brands.create') }}" class="btn btn-sm btn-danger text-white">
                                                <i class="feather-plus me-1"></i> Create First Brand
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($brands->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $brands->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

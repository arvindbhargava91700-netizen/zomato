@extends('layouts.admin.main')

@section('title', 'Cuisines Management - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Cuisine Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Cuisines</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.cuisines.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add New Cuisine
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('admin.cuisines.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by cuisine name..." value="{{ request('search') }}">
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
                        <a href="{{ route('admin.cuisines.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                        <a href="{{ route('admin.cuisines.index', ['trashed' => 1]) }}" class="btn btn-outline-danger fw-semibold" title="View Trashed Cuisines">
                            <i class="feather-trash-2 me-1"></i> Trashed
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Master Cuisines List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Image/Icon</th>
                                <th>Cuisine Name</th>
                                <th>Assigned Restaurants</th>
                                <th>Sort Order</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cuisines as $cuisine)
                                <tr>
                                    <td class="ps-4">
                                        @if($cuisine->image)
                                            <img src="{{ asset($cuisine->image) }}" alt="Cuisine" class="rounded border" style="width: 45px; height: 45px; object-fit: cover;">
                                        @elseif($cuisine->icon)
                                            <img src="{{ asset($cuisine->icon) }}" alt="Icon" class="rounded border" style="width: 45px; height: 45px; object-fit: cover;">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                                                <i class="feather-coffee text-muted fs-4"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-6">{{ $cuisine->name }}</div>
                                        <span class="text-muted fs-12">Slug: {{ $cuisine->slug }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-info text-info fw-semibold fs-12">{{ $cuisine->restaurants_count }} Restaurants</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $cuisine->sort_order }}</span>
                                    </td>
                                    <td>
                                        @if(!$cuisine->trashed())
                                            <form action="{{ route('admin.cuisines.toggle-status', $cuisine->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                @if($cuisine->status === 'active')
                                                    <button type="submit" class="btn btn-sm btn-success border-0 px-3 rounded-pill fw-semibold" title="Click to Deactivate">Active</button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-secondary border-0 px-3 rounded-pill fw-semibold" title="Click to Activate">Inactive</button>
                                                @endif
                                            </form>
                                        @else
                                            <span class="badge bg-danger">Soft Deleted</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            @if(!$cuisine->trashed())
                                                <a href="{{ route('admin.cuisines.show', $cuisine->id) }}" class="btn btn-sm btn-light border text-info p-2 rounded-2" data-bs-toggle="tooltip" title="View Details">
                                                    <i class="feather-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.cuisines.edit', $cuisine->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.cuisines.destroy', $cuisine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to soft delete this cuisine?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light border text-danger p-2 rounded-2" data-bs-toggle="tooltip" title="Soft Delete">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.cuisines.restore', $cuisine->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success p-2 rounded-2" title="Restore">
                                                        <i class="feather-rotate-ccw"></i> Restore
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.cuisines.force-delete', $cuisine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this cuisine?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger p-2 rounded-2" title="Permanently Delete">
                                                        <i class="feather-x-circle"></i> Permanent Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No cuisines found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($cuisines->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $cuisines->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

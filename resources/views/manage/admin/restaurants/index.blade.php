@extends('layouts.admin.main')

@section('title', 'Restaurants - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Restaurant Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Restaurants</li>
            </ul>
        </div>
       {{-- <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.restaurants.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add New Restaurant
            </a>
        </div>--}}
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search & Filter Card -->
        <div class="card stretch stretch-full mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.restaurants.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, owner, email, mobile..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="approval_status" class="form-select">
                            <option value="">All Approval</option>
                            <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">Search</button>
                        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Approval Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <a href="{{ route('admin.restaurants.index') }}" class="text-decoration-none">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="feather-layers"></i>
                            </div>
                            <div>
                                <div class="fs-14 fw-bold text-dark">{{ $pendingCount + $approvedCount + $rejectedCount }}</div>
                                <div class="fs-12 text-muted">Total Restaurants</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.restaurants.index', ['approval_status' => 'pending']) }}" class="text-decoration-none">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-soft-warning text-warning d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="feather-clock"></i>
                            </div>
                            <div>
                                <div class="fs-14 fw-bold text-dark">{{ $pendingCount }}</div>
                                <div class="fs-12 text-muted">Pending</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.restaurants.index', ['approval_status' => 'approved']) }}" class="text-decoration-none">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-soft-success text-success d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="feather-check-circle"></i>
                            </div>
                            <div>
                                <div class="fs-14 fw-bold text-dark">{{ $approvedCount }}</div>
                                <div class="fs-12 text-muted">Approved</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.restaurants.index', ['approval_status' => 'rejected']) }}" class="text-decoration-none">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-soft-danger text-danger d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="feather-x-circle"></i>
                            </div>
                            <div>
                                <div class="fs-14 fw-bold text-dark">{{ $rejectedCount }}</div>
                                <div class="fs-12 text-muted">Rejected</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Restaurant Table Card -->
        <div class="card stretch stretch-full">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title mb-0 fw-bold">
                    @if(request('approval_status') === 'pending')
                        Pending Restaurants
                    @elseif(request('approval_status') === 'approved')
                        Approved Restaurants
                    @elseif(request('approval_status') === 'rejected')
                        Rejected Restaurants
                    @else
                        All Restaurants
                    @endif
                </h5>
                <!-- <a href="{{ route('admin.restaurants.create') }}" class="btn btn-danger text-white fw-semibold btn-sm" style="background-color: #cb202d; border: none;">
                    <i class="feather-plus me-1"></i> Add New Restaurant
                </a> -->
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Logo</th>
                                <th>Restaurant Details</th>
                                <th>Owner Info</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Approval</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($restaurants as $restaurant)
                                <tr>
                                    <td class="ps-4">
                                        @if($restaurant->logo)
                                            <img src="{{ asset($restaurant->logo) }}" alt="Logo" class="rounded-circle border" style="width: 45px; height: 45px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                                                <i class="feather-shopping-bag text-muted fs-4"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-6 d-flex align-items-center flex-wrap gap-1">
                                            {{ $restaurant->restaurant_name }}
                                            @if($restaurant->brand)
                                                <span class="badge bg-soft-primary text-primary fs-11 fw-semibold d-inline-flex align-items-center gap-1">
                                                    @if($restaurant->brand->logo)
                                                        <img src="{{ asset($restaurant->brand->logo) }}" alt="{{ $restaurant->brand->name }}" class="rounded-circle border" style="width: 16px; height: 16px; object-fit: cover;">
                                                    @else
                                                        <i class="feather-award"></i>
                                                    @endif
                                                    {{ $restaurant->brand->name }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-muted fs-12"><i class="feather-map-pin me-1"></i>{{ Str::limit($restaurant->address, 35) }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $restaurant->owner_name }}</div>
                                        <span class="text-muted fs-12 d-block">{{ $restaurant->email }}</span>
                                        <span class="text-muted fs-12">{{ $restaurant->mobile }}</span>
                                    </td>
                                    <td>
                                        @if($restaurant->is_pure_veg)
                                            <span class="badge bg-soft-success text-success border border-success">Pure Veg</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger border border-danger">Veg / Non-Veg</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.restaurants.toggle-status', $restaurant->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($restaurant->status === 'active')
                                                <button type="submit" class="btn btn-sm btn-success border-0 px-3 rounded-pill fw-semibold" title="Click to Deactivate">Active</button>
                                            @elseif($restaurant->status === 'inactive')
                                                <button type="submit" class="btn btn-sm btn-secondary border-0 px-3 rounded-pill fw-semibold" title="Click to Activate">Inactive</button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-warning border-0 px-3 rounded-pill fw-semibold text-dark" title="Click to Activate">Pending</button>
                                            @endif
                                        </form>
                                    </td>
                                    <td>
                                        @if($restaurant->approval_status === 'approved')
                                            <span class="badge bg-success fs-12" title="Approved"><i class="feather-check-circle me-1"></i>Approved</span>
                                        @elseif($restaurant->approval_status === 'rejected')
                                            <span class="badge bg-danger fs-12" title="Rejected"><i class="feather-x-circle me-1"></i>Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark fs-12" title="Pending Approval"><i class="feather-clock me-1"></i>Pending</span>
                                        @endif
                                        @if($restaurant->admin_remarks)
                                            <span class="text-muted fs-11 d-block mt-1" title="Admin Remarks">{{ Str::limit($restaurant->admin_remarks, 30) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-sm btn-light border text-info p-2 rounded-2" data-bs-toggle="tooltip" title="View / Approve">
                                                <i class="feather-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" data-bs-toggle="tooltip" title="Edit Details">
                                                <i class="feather-edit"></i>
                                            </a>
                                            @if($restaurant->approval_status !== 'approved')
                                                <form action="{{ route('admin.restaurants.approval', $restaurant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Approve {{ addslashes($restaurant->restaurant_name) }}?');">
                                                    @csrf
                                                    <input type="hidden" name="approval_status" value="approved">
                                                    <button type="submit" class="btn btn-sm btn-success p-2 rounded-2" data-bs-toggle="tooltip" title="Approve">
                                                        <i class="feather-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($restaurant->approval_status !== 'rejected')
                                                <form action="{{ route('admin.restaurants.approval', $restaurant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject {{ addslashes($restaurant->restaurant_name) }}?');">
                                                    @csrf
                                                    <input type="hidden" name="approval_status" value="rejected">
                                                    <button type="submit" class="btn btn-sm btn-danger p-2 rounded-2" data-bs-toggle="tooltip" title="Reject">
                                                        <i class="feather-x"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to soft delete {{ addslashes($restaurant->restaurant_name) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger p-2 rounded-2" data-bs-toggle="tooltip" title="Soft Delete">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No restaurants found matching your criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($restaurants->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $restaurants->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

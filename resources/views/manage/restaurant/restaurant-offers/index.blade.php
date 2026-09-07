@extends('layouts.restaurant.main')

@section('title', 'Restaurant Offers - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Restaurant Offers</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Restaurant Offers</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.restaurant-offers.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add Offer
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Card -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('restaurant.restaurant-offers.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by offer title..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="approval_status" class="form-select">
                            <option value="">All Approvals</option>
                            <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-3">Go</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Offers for {{ $restaurant->restaurant_name }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Banner</th>
                                <th>Title</th>
                                <th>Discount</th>
                                <th>Validity</th>
                                <th>Approval</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($offers as $offer)
                                <tr>
                                    <td class="ps-4">
                                        @if($offer->banner_url)
                                            <img src="{{ $offer->banner_url }}" alt="{{ $offer->title }}" class="rounded-2" style="width:90px;height:52px;object-fit:cover;">
                                        @else
                                            <span class="text-muted fs-12">No banner</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-6">{{ $offer->title }}</div>
                                        @if($offer->approval_status === 'rejected' && $offer->admin_remarks)
                                            <div class="text-danger fs-12">{{ Str::limit($offer->admin_remarks, 60) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($offer->discount_type === 'percentage')
                                            <span class="fw-semibold text-success">{{ $offer->discount_value }}% OFF</span>
                                            @if($offer->maximum_discount_amount)
                                                <div class="text-muted fs-12">Max {{ $currencySymbol }}{{ number_format($offer->maximum_discount_amount, 2) }}</div>
                                            @endif
                                        @else
                                            <span class="fw-semibold text-success">{{ $currencySymbol }}{{ number_format($offer->discount_value, 2) }} OFF</span>
                                        @endif
                                        @if($offer->minimum_order_amount)
                                            <div class="text-muted fs-12">Min {{ $currencySymbol }}{{ number_format($offer->minimum_order_amount, 2) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($offer->valid_from || $offer->valid_until)
                                            <span class="fs-12 text-muted">
                                                {{ $offer->valid_from ? $offer->valid_from->format('d M Y') : 'Any' }}
                                                –
                                                {{ $offer->valid_until ? $offer->valid_until->format('d M Y') : 'Any' }}
                                            </span>
                                        @else
                                            <span class="fs-12 text-muted">Always</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($offer->approval_status === 'approved')
                                            <span class="badge bg-soft-success text-success fw-semibold fs-12">Approved</span>
                                        @elseif($offer->approval_status === 'rejected')
                                            <span class="badge bg-soft-danger text-danger fw-semibold fs-12">Rejected</span>
                                        @else
                                            <span class="badge bg-soft-warning text-warning fw-semibold fs-12">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('restaurant.restaurant-offers.toggle-status', $offer->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($offer->status === 'active')
                                                <button type="submit" class="btn btn-sm btn-success border-0 px-3 rounded-pill fw-semibold" title="Click to Deactivate">Active</button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-secondary border-0 px-3 rounded-pill fw-semibold" title="Click to Activate">Inactive</button>
                                            @endif
                                        </form>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            <a href="{{ route('restaurant.restaurant-offers.edit', $offer->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" title="Edit">
                                                <i class="feather-edit"></i>
                                            </a>
                                            <form action="{{ route('restaurant.restaurant-offers.destroy', $offer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this offer?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger p-2 rounded-2" title="Delete">
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
                                        No restaurant offers found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($offers->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $offers->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

@extends('layouts.restaurant.main')

@section('title', 'My Restaurants - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Restaurant Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Restaurants</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            @if($restaurants->count() === 0)
                <a href="{{ route('restaurant.restaurants.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                    <i class="feather-plus me-1"></i> Add New Restaurant
                </a>
            @else
                <span class="badge bg-soft-secondary text-secondary border px-3 py-2">
                    <i class="feather-check-circle me-1 text-success"></i> 1 Restaurant Registered (Limit: 1)
                </span>
            @endif
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

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Restaurant Table Card -->
        <div class="card stretch stretch-full">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">My Restaurants</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="customerList">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Logo</th>
                                <th>Restaurant Details</th>
                                <th>Owner Info</th>
                                <th>Type</th>
                                <th>Commission</th>
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
                                        @php $rtype = $restaurant->restaurant_type ?? 'restaurant'; @endphp
                                        @if($rtype === 'brand')
                                            <span class="badge bg-soft-warning text-warning border border-warning"><i class="feather-award me-1"></i>Brand</span>
                                        @elseif($rtype === 'nightlife')
                                            <span class="badge bg-dark text-white"><i class="feather-moon me-1"></i>Nightlife</span>
                                            @if($restaurant->nightlifeBanner)
                                                <span class="badge bg-secondary text-white fs-11 fw-semibold d-inline-flex align-items-center gap-1 mt-1">
                                                    @if($restaurant->nightlifeBanner->banner)
                                                        <img src="{{ asset($restaurant->nightlifeBanner->banner) }}" alt="{{ $restaurant->nightlifeBanner->title }}" class="rounded border" style="width: 18px; height: 18px; object-fit: cover;">
                                                    @endif
                                                    {{ $restaurant->nightlifeBanner->title }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-soft-primary text-primary border border-primary"><i class="feather-flag me-1"></i>Restaurant</span>
                                        @endif
                                        <span class="d-block mt-1">
                                            @if($restaurant->is_pure_veg)
                                                <span class="badge bg-soft-success text-success border border-success fs-11">Pure Veg</span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger border border-danger fs-11">Veg / Non-Veg</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $restaurant->commission_percentage }}%</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-{{ $restaurant->status === 'active' ? 'success' : ($restaurant->status === 'inactive' ? 'secondary' : 'warning') }} text-{{ $restaurant->status === 'active' ? 'success' : ($restaurant->status === 'inactive' ? 'secondary' : 'warning') }}">
                                            {{ ucfirst($restaurant->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($restaurant->approval_status === 'approved')
                                            <span class="badge bg-success fs-12"><i class="feather-check-circle me-1"></i>Approved</span>
                                        @elseif($restaurant->approval_status === 'rejected')
                                            <span class="badge bg-danger fs-12"><i class="feather-x-circle me-1"></i>Rejected</span>
                                            @if($restaurant->admin_remarks)
                                                <span class="text-danger fs-11 d-block mt-1" data-bs-toggle="tooltip" title="{{ $restaurant->admin_remarks }}">
                                                    <i class="feather-message-square me-1"></i>{{ Str::limit($restaurant->admin_remarks, 28) }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-warning text-dark fs-12"><i class="feather-clock me-1"></i>Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            <a href="{{ route('restaurant.restaurants.show', $restaurant->id) }}" class="btn btn-sm btn-light border text-info p-2 rounded-2" data-bs-toggle="tooltip" title="View Profile">
                                                <i class="feather-eye"></i>
                                            </a>
                                            <a href="{{ route('restaurant.restaurants.edit', $restaurant->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" data-bs-toggle="tooltip" title="Edit Details">
                                                <i class="feather-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
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

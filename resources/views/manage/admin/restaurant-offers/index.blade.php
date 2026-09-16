@extends('layouts.admin.main')

@section('title', getPageTitle('Restaurant Offers'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Restaurant Offers (Today's Deal)</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Restaurant Offers</li>
            </ul>
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

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-auto">
                <a href="{{ route('admin.restaurant-offers.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 py-3 px-4 {{ !request('approval_status') ? 'border border-primary' : '' }}">
                        <div class="fs-12 text-muted">All</div>
                        <div class="fs-4 fw-bold">{{ $counts['all'] }}</div>
                    </div>
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.restaurant-offers.index', ['approval_status' => 'pending']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 py-3 px-4 {{ request('approval_status') === 'pending' ? 'border border-warning' : '' }}">
                        <div class="fs-12 text-muted">Pending</div>
                        <div class="fs-4 fw-bold text-warning">{{ $counts['pending'] }}</div>
                    </div>
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.restaurant-offers.index', ['approval_status' => 'approved']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 py-3 px-4 {{ request('approval_status') === 'approved' ? 'border border-success' : '' }}">
                        <div class="fs-12 text-muted">Approved</div>
                        <div class="fs-4 fw-bold text-success">{{ $counts['approved'] }}</div>
                    </div>
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.restaurant-offers.index', ['approval_status' => 'rejected']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 py-3 px-4 {{ request('approval_status') === 'rejected' ? 'border border-danger' : '' }}">
                        <div class="fs-12 text-muted">Rejected</div>
                        <div class="fs-4 fw-bold text-danger">{{ $counts['rejected'] }}</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('admin.restaurant-offers.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by offer title..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">Search</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.restaurant-offers.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Banner</th>
                                <th>Offer</th>
                                <th>Restaurant</th>
                                <th>Discount</th>
                                <th>Validity</th>
                                <th>Approval</th>
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
                                        <span class="badge {{ $offer->status === 'active' ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary' }} fs-12">{{ ucfirst($offer->status) }}</span>
                                    </td>
                                    <td>{{ $offer->restaurant?->restaurant_name ?? '—' }}</td>
                                    <td>
                                        @if($offer->discount_type === 'percentage')
                                            <span class="fw-semibold text-success">{{ $offer->discount_value }}% OFF</span>
                                        @else
                                            <span class="fw-semibold text-success">{{ $currencySymbol }}{{ number_format($offer->discount_value, 2) }} OFF</span>
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
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.restaurant-offers.show', $offer->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" title="Review">
                                            <i class="feather-eye"></i>
                                        </a>
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

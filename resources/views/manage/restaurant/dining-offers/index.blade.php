@extends('layouts.restaurant.main')

@section('title', 'Dining Offers - Zomato Partner')

@section('content')
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Dining Offers</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Dining Offers</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.dining-offers.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
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
                <form action="{{ route('restaurant.dining-offers.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by title or coupon code..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="approval_status" class="form-select">
                            <option value="">All Approvals</option>
                            <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold w-100">Filter</button>
                        <a href="{{ route('restaurant.dining-offers.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold">Offers for {{ $restaurant->restaurant_name }}</h5>
                <span class="badge bg-light text-secondary">{{ $offers->total() }} Total Offers</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Offer Title</th>
                                <th>Coupon Code</th>
                                <th>Discount</th>
                                <th>Min Bill</th>
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
                                        <div class="fw-bold text-dark fs-6">{{ $offer->title }}</div>
                                        @if($offer->cover_charge)
                                            <span class="fs-12 text-muted">Cover Charge: {{ $currencySymbol }}{{ number_format($offer->cover_charge, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($offer->coupon_code)
                                            <span class="badge bg-soft-primary text-primary font-monospace fw-bold px-2 py-1">
                                                <i class="feather-tag me-1"></i>{{ $offer->coupon_code }}
                                            </span>
                                        @else
                                            <span class="text-muted fs-12">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($offer->discount_type === 'percentage')
                                            <span class="fw-semibold text-success">{{ $offer->discount_value }}% OFF</span>
                                            @if($offer->max_discount_amount)
                                                <div class="text-muted fs-12">Max {{ $currencySymbol }}{{ number_format($offer->max_discount_amount, 2) }}</div>
                                            @endif
                                        @else
                                            <span class="fw-semibold text-success">{{ $currencySymbol }}{{ number_format($offer->discount_value, 2) }} OFF</span>
                                        @endif
                                    </td>
                                    <td>{{ $offer->min_bill_amount ? $currencySymbol . number_format($offer->min_bill_amount, 2) : 'No Min' }}</td>
                                    <td>
                                        @if($offer->start_date || $offer->end_date)
                                            <span class="fs-12 text-muted">
                                                {{ $offer->start_date ? $offer->start_date->format('d M Y') : 'Any' }}
                                                –
                                                {{ $offer->end_date ? $offer->end_date->format('d M Y') : 'Any' }}
                                            </span>
                                        @else
                                            <span class="fs-12 text-muted">Always</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($offer->approval_status === 'approved')
                                            <span class="badge bg-soft-success text-success fw-semibold px-2 py-1">
                                                <i class="feather-check-circle me-1"></i>Approved
                                            </span>
                                        @elseif($offer->approval_status === 'rejected')
                                            <div>
                                                <span class="badge bg-soft-danger text-danger fw-semibold px-2 py-1">
                                                    <i class="feather-x-circle me-1"></i>Rejected
                                                </span>
                                                @if($offer->admin_remarks)
                                                    <button type="button" class="btn btn-link btn-sm text-danger p-0 d-block fs-11 text-decoration-underline" 
                                                            data-bs-toggle="modal" data-bs-target="#reasonModal{{ $offer->id }}">
                                                        View Reason
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge bg-soft-warning text-warning fw-semibold px-2 py-1">
                                                <i class="feather-clock me-1"></i>Pending Review
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('restaurant.dining-offers.toggle-status', $offer->id) }}" method="POST" class="d-inline">
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
                                            <a href="{{ route('restaurant.dining-offers.edit', $offer->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" title="Edit Offer">
                                                <i class="feather-edit"></i>
                                            </a>
                                            <form action="{{ route('restaurant.dining-offers.destroy', $offer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this offer?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger p-2 rounded-2" title="Delete Offer">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                @if($offer->approval_status === 'rejected' && $offer->admin_remarks)
                                    <!-- Rejection Reason Modal -->
                                    <div class="modal fade" id="reasonModal{{ $offer->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header border-bottom bg-light">
                                                    <h6 class="modal-title fw-bold text-danger">
                                                        <i class="feather-alert-circle me-1"></i> Rejection Reason
                                                    </h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <h6 class="fw-bold mb-2">{{ $offer->title }}</h6>
                                                    <div class="p-3 bg-light rounded-3 text-muted">
                                                        {{ $offer->admin_remarks }}
                                                    </div>
                                                    <p class="text-muted fs-12 mt-3 mb-0">
                                                        You can edit this offer and resubmit it for administrator review.
                                                    </p>
                                                </div>
                                                <div class="modal-footer border-top">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <a href="{{ route('restaurant.dining-offers.edit', $offer->id) }}" class="btn btn-primary">Edit & Resubmit</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No dining offers found matching your filters.
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
@endsection

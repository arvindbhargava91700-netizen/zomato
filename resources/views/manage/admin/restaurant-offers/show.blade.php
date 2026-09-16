@extends('layouts.admin.main')

@section('title', getPageTitle('Review Restaurant Offer'))

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Review Restaurant Offer</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurant-offers.index') }}">Restaurant Offers</a></li>
                <li class="breadcrumb-item">Review</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.restaurant-offers.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-0">
                        @if($restaurantOffer->banner_url)
                            <img src="{{ $restaurantOffer->banner_url }}" alt="{{ $restaurantOffer->title }}" class="img-fluid w-100 rounded-top-3" style="max-height:320px;object-fit:cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center text-muted bg-light rounded-top-3" style="height:200px;">No banner uploaded</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">{{ $restaurantOffer->title }}</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-3">
                            <tr>
                                <th class="ps-0" style="width:40%">Restaurant</th>
                                <td>{{ $restaurantOffer->restaurant?->restaurant_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Discount</th>
                                <td>
                                    @if($restaurantOffer->discount_type === 'percentage')
                                        {{ $restaurantOffer->discount_value }}% OFF
                                    @else
                                        {{ $currencySymbol }}{{ number_format($restaurantOffer->discount_value, 2) }} OFF
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0">Minimum Order</th>
                                <td>{{ $restaurantOffer->minimum_order_amount ? $currencySymbol . number_format($restaurantOffer->minimum_order_amount, 2) : '—' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Maximum Discount</th>
                                <td>{{ $restaurantOffer->maximum_discount_amount ? $currencySymbol . number_format($restaurantOffer->maximum_discount_amount, 2) : '—' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Validity</th>
                                <td>
                                    {{ $restaurantOffer->valid_from ? $restaurantOffer->valid_from->format('d M Y') : 'Any' }}
                                    –
                                    {{ $restaurantOffer->valid_until ? $restaurantOffer->valid_until->format('d M Y') : 'Any' }}
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0">Owner Status</th>
                                <td>{{ ucfirst($restaurantOffer->status) }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Approval</th>
                                <td>
                                    @if($restaurantOffer->approval_status === 'approved')
                                        <span class="badge bg-soft-success text-success fw-semibold">Approved</span>
                                        @if($restaurantOffer->approved_at) <span class="fs-12 text-muted">on {{ $restaurantOffer->approved_at->format('d M Y H:i') }}</span> @endif
                                    @elseif($restaurantOffer->approval_status === 'rejected')
                                        <span class="badge bg-soft-danger text-danger fw-semibold">Rejected</span>
                                    @else
                                        <span class="badge bg-soft-warning text-warning fw-semibold">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            @if($restaurantOffer->admin_remarks)
                                <tr>
                                    <th class="ps-0 align-top">Admin Remarks</th>
                                    <td>{{ $restaurantOffer->admin_remarks }}</td>
                                </tr>
                            @endif
                        </table>

                        @if($restaurantOffer->approval_status !== 'approved')
                            <form action="{{ route('admin.restaurant-offers.approve', $restaurantOffer->id) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Remarks (optional)</label>
                                    <input type="text" name="admin_remarks" class="form-control" placeholder="Add a note for the restaurant owner">
                                </div>
                                <button type="submit" class="btn btn-success fw-semibold px-4">
                                    <i class="feather-check me-1"></i> Approve Offer
                                </button>
                            </form>
                        @endif

                        @if($restaurantOffer->approval_status !== 'rejected')
                            <form action="{{ route('admin.restaurant-offers.reject', $restaurantOffer->id) }}" method="POST" onsubmit="return confirm('Reject this offer?');">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                                    <textarea name="admin_remarks" class="form-control" rows="2" placeholder="Explain why this offer is being rejected" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger fw-semibold px-4">
                                    <i class="feather-x me-1"></i> Reject Offer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

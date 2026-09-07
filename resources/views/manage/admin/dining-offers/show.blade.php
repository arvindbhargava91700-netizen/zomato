@extends('layouts.admin.main')

@section('title', 'Review Dining Offer - Admin Dashboard')

@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Review Dining Offer</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.dining-offers.index') }}">Dining Offers</a></li>
                <li class="breadcrumb-item">Offer #{{ $diningOffer->id }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.dining-offers.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="feather-check-circle fs-4 me-2 text-success"></i>
                    <div class="fw-semibold">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Status Summary Top Banner -->
        @if($diningOffer->approval_status === 'approved')
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center justify-content-between p-3" role="alert">
                <div class="d-flex align-items-center">
                    <div class="avatar-text avatar-md bg-success text-white rounded-circle me-3">
                        <i class="feather-check-circle"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-success">Offer Status: Approved & Live</h6>
                        <small class="text-muted">Customers can view and select this discount when reserving tables.</small>
                    </div>
                </div>
                <span class="badge bg-success fs-13 px-3 py-2 fw-bold">Approved</span>
            </div>
        @elseif($diningOffer->approval_status === 'rejected')
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center justify-content-between p-3" role="alert">
                <div class="d-flex align-items-center">
                    <div class="avatar-text avatar-md bg-danger text-white rounded-circle me-3">
                        <i class="feather-x-circle"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-danger">Offer Status: Rejected</h6>
                        <small class="text-muted">This offer is rejected and hidden from customers.</small>
                    </div>
                </div>
                <span class="badge bg-danger fs-13 px-3 py-2 fw-bold">Rejected</span>
            </div>
        @else
            <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center justify-content-between p-3" role="alert">
                <div class="d-flex align-items-center">
                    <div class="avatar-text avatar-md bg-warning text-white rounded-circle me-3">
                        <i class="feather-clock"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-warning">Pending Administrator Decision</h6>
                        <small class="text-muted">Review the terms and approve or reject this offer below.</small>
                    </div>
                </div>
                <span class="badge bg-warning text-dark fs-13 px-3 py-2 fw-bold">Pending Review</span>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left Column: Summary Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-text avatar-xl {{ $diningOffer->approval_status === 'approved' ? 'bg-soft-success text-success' : ($diningOffer->approval_status === 'rejected' ? 'bg-soft-danger text-danger' : 'bg-soft-warning text-warning') }} mx-auto mb-3 rounded-circle" style="width: 80px; height: 80px; font-size: 32px;">
                            <i class="feather-tag"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">{{ $diningOffer->title }}</h5>
                        <p class="text-muted fs-13 mb-3">{{ $diningOffer->restaurant?->restaurant_name ?? '—' }}</p>

                        @if($diningOffer->coupon_code)
                            <div class="p-2 bg-light border border-dashed rounded-3 mb-3 font-monospace fw-bold text-primary fs-5">
                                <i class="feather-gift me-1"></i>{{ $diningOffer->coupon_code }}
                            </div>
                        @endif

                        <div class="d-flex justify-content-center gap-2 mb-3">
                            @if($diningOffer->approval_status === 'approved')
                                <span class="badge bg-success text-white fs-13 px-3 py-2 fw-bold">
                                    <i class="feather-check-circle me-1"></i> Approved
                                </span>
                            @elseif($diningOffer->approval_status === 'rejected')
                                <span class="badge bg-danger text-white fs-13 px-3 py-2 fw-bold">
                                    <i class="feather-x-circle me-1"></i> Rejected
                                </span>
                            @else
                                <span class="badge bg-warning text-dark fs-13 px-3 py-2 fw-bold">
                                    <i class="feather-clock me-1"></i> Pending Review
                                </span>
                            @endif
                        </div>

                        <div class="p-3 bg-light rounded-3 text-start fs-12">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Approval Status:</span>
                                <span class="fw-bold {{ $diningOffer->approval_status === 'approved' ? 'text-success' : ($diningOffer->approval_status === 'rejected' ? 'text-danger' : 'text-warning') }}">
                                    {{ ucfirst($diningOffer->approval_status) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Restaurant Toggle:</span>
                                <span class="fw-semibold text-dark">
                                    {{ ucfirst($diningOffer->status) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Live for Customers:</span>
                                <span class="fw-semibold {{ $diningOffer->approval_status === 'approved' && $diningOffer->status === 'active' ? 'text-success' : 'text-danger' }}">
                                    {{ $diningOffer->approval_status === 'approved' && $diningOffer->status === 'active' ? 'Yes (Live)' : 'No' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Restaurant Info Card -->
                @if($diningOffer->restaurant)
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header border-bottom py-3">
                            <h6 class="card-title mb-0 fw-bold">Restaurant Information</h6>
                        </div>
                        <div class="card-body p-3 fs-13">
                            <div class="mb-2">
                                <span class="text-muted fs-12 d-block">Restaurant Name</span>
                                <span class="fw-semibold">{{ $diningOffer->restaurant->restaurant_name }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted fs-12 d-block">Owner Name</span>
                                <span>{{ $diningOffer->restaurant->owner_name ?? '—' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted fs-12 d-block">Contact</span>
                                <span>{{ $diningOffer->restaurant->mobile ?? $diningOffer->restaurant->email ?? '—' }}</span>
                            </div>
                            <div>
                                <span class="text-muted fs-12 d-block">City</span>
                                <span>{{ $diningOffer->restaurant->city?->name ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Specs & Status/Decision Panel -->
            <div class="col-lg-8">
                <!-- Specifications Card -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">Offer Specifications</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle mb-0">
                                <tr>
                                    <th class="ps-0 text-muted" style="width:35%">Discount Structure:</th>
                                    <td>
                                        <span class="fw-bold text-success fs-6">
                                            @if($diningOffer->discount_type === 'percentage')
                                                {{ $diningOffer->discount_value }}% OFF
                                            @else
                                                {{ $currencySymbol }}{{ number_format($diningOffer->discount_value, 2) }} OFF
                                            @endif
                                        </span>
                                        <span class="text-muted fs-12">({{ ucfirst($diningOffer->discount_type) }})</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Coupon Code:</th>
                                    <td>
                                        @if($diningOffer->coupon_code)
                                            <span class="badge bg-soft-primary text-primary font-monospace fw-bold px-2 py-1 fs-12">
                                                <i class="feather-tag me-1"></i>{{ $diningOffer->coupon_code }}
                                            </span>
                                        @else
                                            <span class="text-muted">None (Direct table booking discount)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Minimum Bill Amount:</th>
                                    <td>{{ $diningOffer->min_bill_amount ? $currencySymbol . number_format($diningOffer->min_bill_amount, 2) : 'No minimum bill required' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Max Discount Cap:</th>
                                    <td>{{ $diningOffer->max_discount_amount ? $currencySymbol . number_format($diningOffer->max_discount_amount, 2) : 'No limit' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Cover Charge:</th>
                                    <td>{{ $diningOffer->cover_charge ? $currencySymbol . number_format($diningOffer->cover_charge, 2) : 'No cover charge' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Validity Window:</th>
                                    <td>
                                        @if($diningOffer->start_date || $diningOffer->end_date)
                                            {{ $diningOffer->start_date ? $diningOffer->start_date->format('d M Y') : 'Start Immediately' }}
                                            to
                                            {{ $diningOffer->end_date ? $diningOffer->end_date->format('d M Y') : 'No Expiry' }}
                                        @else
                                            Always Active
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Approval Status:</th>
                                    <td>
                                        @if($diningOffer->approval_status === 'approved')
                                            <span class="badge bg-success text-white fw-bold px-2 py-1"><i class="feather-check me-1"></i>Approved</span>
                                        @elseif($diningOffer->approval_status === 'rejected')
                                            <span class="badge bg-danger text-white fw-bold px-2 py-1"><i class="feather-x me-1"></i>Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark fw-bold px-2 py-1"><i class="feather-clock me-1"></i>Pending Review</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0 text-muted">Submission Date:</th>
                                    <td>{{ $diningOffer->created_at ? $diningOffer->created_at->format('d M Y, h:i A') : '—' }}</td>
                                </tr>
                                @if($diningOffer->approved_at)
                                    <tr>
                                        <th class="ps-0 text-muted">Reviewed Date:</th>
                                        <td>
                                            {{ $diningOffer->approved_at->format('d M Y, h:i A') }}
                                            @if($diningOffer->approver)
                                                <span class="text-muted">(by {{ $diningOffer->approver->name }})</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>

                        @if($diningOffer->admin_remarks)
                            <div class="mt-3 p-3 rounded-3 {{ $diningOffer->approval_status === 'rejected' ? 'bg-soft-danger border border-danger-subtle' : 'bg-light border' }}">
                                <div class="fw-bold {{ $diningOffer->approval_status === 'rejected' ? 'text-danger' : 'text-dark' }} mb-1">
                                    <i class="feather-message-square me-1"></i> {{ $diningOffer->approval_status === 'rejected' ? 'Rejection Reason:' : 'Admin Remarks:' }}
                                </div>
                                <div class="text-dark fs-13">{{ $diningOffer->admin_remarks }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Admin Decision & Status Actions Card -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h6 class="card-title mb-0 fw-bold">Admin Decision & Status</h6>
                        <span class="badge {{ $diningOffer->approval_status === 'approved' ? 'bg-success text-white' : ($diningOffer->approval_status === 'rejected' ? 'bg-danger text-white' : 'bg-warning text-dark') }} fw-bold px-3 py-1">
                            {{ ucfirst($diningOffer->approval_status) }}
                        </span>
                    </div>
                    <div class="card-body p-4">
                        @if($diningOffer->approval_status === 'rejected')
                            <!-- REJECTED STATE: Clean Status Display -->
                            <div class="p-4 rounded-3 border border-danger-subtle bg-soft-danger">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-text avatar-lg bg-danger text-white rounded-circle me-3 flex-shrink-0" style="width: 50px; height: 50px; font-size: 22px;">
                                        <i class="feather-x-circle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <h5 class="fw-bold text-danger mb-0">Decision: Offer Rejected</h5>
                                            <span class="badge bg-danger text-white fs-12 px-3 py-1">Rejected</span>
                                        </div>
                                        <p class="text-muted fs-13 mb-3">
                                            This offer is rejected and hidden from customer apps and website.
                                        </p>
                                        <div class="p-3 bg-white rounded-3 border border-danger-subtle mb-3 shadow-sm">
                                            <div class="text-muted fs-12 fw-semibold text-uppercase mb-1">
                                                <i class="feather-alert-circle text-danger me-1"></i> Rejection Reason Recorded:
                                            </div>
                                            <div class="text-dark fw-bold fs-14">
                                                {{ $diningOffer->admin_remarks ?: 'No specific reason given.' }}
                                            </div>
                                        </div>
                                        <div class="fs-12 text-muted d-flex flex-wrap gap-3">
                                            @if($diningOffer->approved_at)
                                                <span><i class="feather-calendar me-1"></i><strong>Rejected on:</strong> {{ $diningOffer->approved_at->format('d M Y, h:i A') }}</span>
                                            @endif
                                            @if($diningOffer->approver)
                                                <span><i class="feather-user me-1"></i><strong>Reviewed by:</strong> {{ $diningOffer->approver->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($diningOffer->approval_status === 'approved')
                            <!-- APPROVED STATE: Clean Status Display -->
                            <div class="p-4 rounded-3 border border-success-subtle bg-soft-success">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-text avatar-lg bg-success text-white rounded-circle me-3 flex-shrink-0" style="width: 50px; height: 50px; font-size: 22px;">
                                        <i class="feather-check-circle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <h5 class="fw-bold text-success mb-0">Decision: Offer Approved</h5>
                                            <span class="badge bg-success text-white fs-12 px-3 py-1">Approved & Live</span>
                                        </div>
                                        <p class="text-muted fs-13 mb-3">
                                            This dining offer has been approved by the administrator and is live for customers reserving tables.
                                        </p>
                                        @if($diningOffer->admin_remarks)
                                            <div class="p-3 bg-white rounded-3 border border-success-subtle mb-3 shadow-sm">
                                                <div class="text-muted fs-12 fw-semibold text-uppercase mb-1">
                                                    <i class="feather-message-square text-success me-1"></i> Approval Remarks:
                                                </div>
                                                <div class="text-dark fw-bold fs-14">
                                                    {{ $diningOffer->admin_remarks }}
                                                </div>
                                            </div>
                                        @endif
                                        <div class="fs-12 text-muted d-flex flex-wrap gap-3">
                                            @if($diningOffer->approved_at)
                                                <span><i class="feather-calendar me-1"></i><strong>Approved on:</strong> {{ $diningOffer->approved_at->format('d M Y, h:i A') }}</span>
                                            @endif
                                            @if($diningOffer->approver)
                                                <span><i class="feather-user me-1"></i><strong>Reviewed by:</strong> {{ $diningOffer->approver->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- PENDING STATE: Action Forms to Make a Decision -->
                            <div class="row g-4">
                                <!-- Approve Form -->
                                <div class="col-md-6 border-end">
                                    <div class="p-3 bg-light rounded-3 h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-success mb-2">
                                                <i class="feather-check-circle me-1"></i> Approve Offer
                                            </h6>
                                            <p class="text-muted fs-12 mb-3">
                                                Approving makes this dining offer active and visible for customer table bookings.
                                            </p>
                                        </div>
                                        <form action="{{ route('admin.dining-offers.approve', $diningOffer->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label fs-12 fw-semibold">Remarks (Optional)</label>
                                                <input type="text" name="admin_remarks" class="form-control form-control-sm" placeholder="e.g. Terms verified">
                                            </div>
                                            <button type="submit" class="btn btn-success fw-semibold px-4 w-100">
                                                <i class="feather-check me-1"></i> Accept & Approve
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Reject Form -->
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3 h-100 d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="fw-bold text-danger mb-2">
                                                <i class="feather-x-circle me-1"></i> Reject Offer
                                            </h6>
                                            <p class="text-muted fs-12 mb-3">
                                                Provide the reason for rejection so the restaurant owner is notified.
                                            </p>
                                        </div>
                                        <form action="{{ route('admin.dining-offers.reject', $diningOffer->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this offer?');">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label fs-12 fw-semibold">Rejection Reason <span class="text-danger">*</span></label>
                                                <textarea name="admin_remarks" class="form-control form-control-sm" rows="3" required placeholder="Specify reason why this offer is rejected..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-danger fw-semibold px-4 w-100">
                                                <i class="feather-x me-1"></i> Reject Offer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
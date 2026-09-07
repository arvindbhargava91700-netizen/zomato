@extends('layouts.admin.main')

@section('title', 'Dining Offers - Admin Dashboard')

@push('styles')
<style>
    .modal {
        z-index: 1060 !important;
    }
    .modal-backdrop {
        z-index: 1050 !important;
    }
    .modal-dialog {
        z-index: 1061 !important;
    }
</style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Dining Offers Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Dining Offers</li>
            </ul>
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

        <!-- Stats KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.dining-offers.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 {{ !request('approval_status') ? 'border-bottom border-primary border-3' : '' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold text-uppercase">All Offers</span>
                                <div class="fs-4 fw-bold text-dark mt-1">{{ $counts['all'] }}</div>
                            </div>
                            <div class="avatar-text avatar-md bg-soft-primary text-primary rounded-3">
                                <i class="feather-tag"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.dining-offers.index', ['approval_status' => 'pending']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 {{ request('approval_status') === 'pending' ? 'border-bottom border-warning border-3' : '' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold text-uppercase">Pending Approval</span>
                                <div class="fs-4 fw-bold text-warning mt-1">{{ $counts['pending'] }}</div>
                            </div>
                            <div class="avatar-text avatar-md bg-soft-warning text-warning rounded-3">
                                <i class="feather-clock"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.dining-offers.index', ['approval_status' => 'approved']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 {{ request('approval_status') === 'approved' ? 'border-bottom border-success border-3' : '' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold text-uppercase">Approved</span>
                                <div class="fs-4 fw-bold text-success mt-1">{{ $counts['approved'] }}</div>
                            </div>
                            <div class="avatar-text avatar-md bg-soft-success text-success rounded-3">
                                <i class="feather-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.dining-offers.index', ['approval_status' => 'rejected']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded-3 p-3 {{ request('approval_status') === 'rejected' ? 'border-bottom border-danger border-3' : '' }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold text-uppercase">Rejected</span>
                                <div class="fs-4 fw-bold text-danger mt-1">{{ $counts['rejected'] }}</div>
                            </div>
                            <div class="avatar-text avatar-md bg-soft-danger text-danger rounded-3">
                                <i class="feather-x-circle"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('admin.dining-offers.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search offer title, coupon code, restaurant..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="approval_status" class="form-select">
                            <option value="">All Approval Statuses</option>
                            <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                            <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold w-100">Filter</button>
                        <a href="{{ route('admin.dining-offers.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dining Offers Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold">Dining Offers List</h5>
                <span class="badge bg-light text-secondary">{{ $offers->total() }} Offers Found</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Offer Title</th>
                                <th>Restaurant</th>
                                <th>Coupon Code</th>
                                <th>Discount</th>
                                <th>Min Bill</th>
                                <th>Validity</th>
                                <th>Approval Status</th>
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
                                        <div class="fw-semibold text-dark">{{ $offer->restaurant?->restaurant_name ?? '—' }}</div>
                                        @if($offer->restaurant?->city)
                                            <span class="fs-12 text-muted">{{ $offer->restaurant->city->name }}</span>
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
                                            <span class="badge bg-success text-white fw-bold px-2 py-1">
                                                <i class="feather-check me-1"></i>Approved
                                            </span>
                                        @elseif($offer->approval_status === 'rejected')
                                            <div>
                                                <span class="badge bg-danger text-white fw-bold px-2 py-1">
                                                    <i class="feather-x me-1"></i>Rejected
                                                </span>
                                                @if($offer->admin_remarks)
                                                    <button type="button" class="btn btn-link btn-sm text-danger p-0 d-block fs-11 text-decoration-underline mt-1" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#viewReasonModal"
                                                            data-title="{{ $offer->title }}"
                                                            data-restaurant="{{ $offer->restaurant?->restaurant_name }}"
                                                            data-reason="{{ $offer->admin_remarks }}">
                                                        View Reason
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge bg-warning text-dark fw-bold px-2 py-1">
                                                <i class="feather-clock me-1"></i>Pending Review
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            <a href="{{ route('admin.dining-offers.show', $offer->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" title="View Details">
                                                <i class="feather-eye"></i>
                                            </a>

                                            @if($offer->approval_status === 'pending')
                                                <button type="button" class="btn btn-sm btn-soft-success text-success p-2 rounded-2" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#approveOfferModal" 
                                                        data-url="{{ route('admin.dining-offers.approve', $offer->id) }}"
                                                        data-title="{{ $offer->title }}"
                                                        data-restaurant="{{ $offer->restaurant?->restaurant_name }}"
                                                        data-discount="{{ $offer->discount_type === 'percentage' ? $offer->discount_value . '% OFF' : $currencySymbol . number_format($offer->discount_value, 2) . ' OFF' }}"
                                                        data-coupon="{{ $offer->coupon_code }}"
                                                        title="Accept / Approve Offer">
                                                    <i class="feather-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-soft-danger text-danger p-2 rounded-2" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectOfferModal" 
                                                        data-url="{{ route('admin.dining-offers.reject', $offer->id) }}"
                                                        data-title="{{ $offer->title }}"
                                                        data-restaurant="{{ $offer->restaurant?->restaurant_name }}"
                                                        title="Reject Offer">
                                                    <i class="feather-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No dining offers found.
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

@endsection

@push('modals')
    <!-- ========================================================================= -->
    <!-- GLOBAL MODALS (Rendered as direct children of <body> via @stack('modals'))-->
    <!-- ========================================================================= -->

    <!-- 1. Global Approve Modal -->
    <div class="modal fade" id="approveOfferModal" tabindex="-1" aria-labelledby="approveOfferModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden; background: #fff;">
                <form id="approveOfferForm" method="POST" action="">
                    @csrf
                    <div class="modal-header border-bottom bg-light py-3">
                        <h6 class="modal-title fw-bold text-success" id="approveOfferModalLabel">
                            <i class="feather-check-circle me-1"></i> Approve Dining Offer
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-3 text-muted">Are you sure you want to approve this dining offer? Once approved, it will be active for customers.</p>
                        <div class="p-3 bg-light rounded-3 mb-3 border">
                            <div class="fw-bold fs-6 text-dark" id="approveOfferTitle">Offer Title</div>
                            <div class="text-muted fs-12 mt-1" id="approveOfferRestaurant">Restaurant Info</div>
                            <div class="mt-2 d-none">
                                <span class="badge bg-primary font-monospace" id="approveOfferCoupon"></span>
                            </div>
                        </div>
                        <div>
                            <label class="form-label fw-semibold fs-13">Approval Remarks (Optional)</label>
                            <input type="text" name="admin_remarks" class="form-control" placeholder="Optional note for the restaurant">
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light py-2">
                        <button type="button" class="btn btn-light border fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success fw-semibold px-4">
                            <i class="feather-check me-1"></i> Approve Offer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Global Reject Modal -->
    <div class="modal fade" id="rejectOfferModal" tabindex="-1" aria-labelledby="rejectOfferModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden; background: #fff;">
                <form id="rejectOfferForm" method="POST" action="">
                    @csrf
                    <div class="modal-header border-bottom bg-light py-3">
                        <h6 class="modal-title fw-bold text-danger" id="rejectOfferModalLabel">
                            <i class="feather-alert-octagon me-1"></i> Reject Dining Offer
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="p-3 bg-light rounded-3 mb-3 border">
                            <div class="fw-bold fs-6 text-dark" id="rejectOfferTitle">Offer Title</div>
                            <div class="text-muted fs-12 mt-1" id="rejectOfferRestaurant">Restaurant Info</div>
                        </div>
                        <div>
                            <label class="form-label fw-semibold fs-13">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="admin_remarks" id="rejectOfferRemarks" class="form-control" rows="3" required placeholder="Specify why this offer cannot be approved (e.g. invalid discount terms, incorrect validity date, etc.)"></textarea>
                            <small class="text-muted d-block mt-1">This reason will be visible to the restaurant owner so they can fix and resubmit.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-top bg-light py-2">
                        <button type="button" class="btn btn-light border fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger fw-semibold px-4">
                            <i class="feather-x me-1"></i> Reject Offer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. View Reason Modal (Read-Only) -->
    <div class="modal fade" id="viewReasonModal" tabindex="-1" aria-labelledby="viewReasonModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden; background: #fff;">
                <div class="modal-header border-bottom bg-light py-3">
                    <h6 class="modal-title fw-bold text-danger" id="viewReasonModalLabel">
                        <i class="feather-alert-circle me-1"></i> Recorded Rejection Reason
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <div class="fw-bold text-dark fs-6" id="viewReasonTitle">Offer Title</div>
                        <div class="text-muted fs-12" id="viewReasonRestaurant">Restaurant</div>
                    </div>
                    <div class="p-3 bg-soft-danger border border-danger-subtle rounded-3">
                        <div class="text-muted fs-12 fw-semibold text-uppercase mb-1">Reason:</div>
                        <div class="text-dark fw-bold fs-14" id="viewReasonText">Reason description</div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2">
                    <button type="button" class="btn btn-light border fw-semibold" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Approve Modal handler
    const approveModal = document.getElementById('approveOfferModal');
    if (approveModal) {
        approveModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;
            
            const url = button.getAttribute('data-url');
            const title = button.getAttribute('data-title');
            const restaurant = button.getAttribute('data-restaurant');
            const discount = button.getAttribute('data-discount');
            const coupon = button.getAttribute('data-coupon');

            const form = document.getElementById('approveOfferForm');
            if (form) form.action = url;

            const titleEl = document.getElementById('approveOfferTitle');
            if (titleEl) titleEl.textContent = title;

            const restEl = document.getElementById('approveOfferRestaurant');
            if (restEl) restEl.textContent = (restaurant || '') + (discount ? ' • ' + discount : '');

            const couponEl = document.getElementById('approveOfferCoupon');
            if (couponEl) {
                if (coupon && coupon.trim() !== '') {
                    couponEl.textContent = coupon;
                    couponEl.parentElement.classList.remove('d-none');
                } else {
                    couponEl.parentElement.classList.add('d-none');
                }
            }
        });
    }

    // Reject Modal handler
    const rejectModal = document.getElementById('rejectOfferModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const url = button.getAttribute('data-url');
            const title = button.getAttribute('data-title');
            const restaurant = button.getAttribute('data-restaurant');

            const form = document.getElementById('rejectOfferForm');
            if (form) form.action = url;

            const titleEl = document.getElementById('rejectOfferTitle');
            if (titleEl) titleEl.textContent = title;

            const restEl = document.getElementById('rejectOfferRestaurant');
            if (restEl) restEl.textContent = restaurant || '';

            const remarksEl = document.getElementById('rejectOfferRemarks');
            if (remarksEl) remarksEl.value = '';
        });
    }

    // View Reason Modal handler
    const reasonModal = document.getElementById('viewReasonModal');
    if (reasonModal) {
        reasonModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const title = button.getAttribute('data-title');
            const restaurant = button.getAttribute('data-restaurant');
            const reason = button.getAttribute('data-reason');

            const titleEl = document.getElementById('viewReasonTitle');
            if (titleEl) titleEl.textContent = title;

            const restEl = document.getElementById('viewReasonRestaurant');
            if (restEl) restEl.textContent = restaurant || '';

            const textEl = document.getElementById('viewReasonText');
            if (textEl) textEl.textContent = reason || 'No specific reason given.';
        });
    }
});
</script>
@endpush
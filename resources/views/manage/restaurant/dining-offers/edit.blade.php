@extends('layouts.restaurant.main')

@section('title', 'Edit Dining Offer - Zomato Partner')

@section('content')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Dining Offer</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dining-offers.index') }}">Dining Offers</a></li>
                <li class="breadcrumb-item">Edit</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.dining-offers.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="main-content">
        @if($diningOffer->approval_status === 'rejected')
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-start">
                    <i class="feather-alert-octagon fs-4 me-3 mt-1 text-danger"></i>
                    <div>
                        <h6 class="fw-bold text-danger mb-1">Offer Rejected by Admin</h6>
                        <p class="mb-1"><strong>Rejection Reason:</strong> {{ $diningOffer->admin_remarks ?? 'Please review offer details and guidelines.' }}</p>
                        <small class="text-muted">Modifying this offer and clicking "Update & Resubmit" will submit it again for administrator review.</small>
                    </div>
                </div>
            </div>
        @elseif($diningOffer->approval_status === 'pending')
            <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center" role="alert">
                <i class="feather-clock fs-4 me-3 text-warning"></i>
                <div>
                    <span class="fw-semibold">Pending Admin Approval:</span> This offer is currently under review by the platform administrator.
                </div>
            </div>
        @endif

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('restaurant.dining-offers.update', $diningOffer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label for="title" class="form-label fw-semibold">Offer Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $diningOffer->title) }}" required>
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="coupon_code" class="form-label fw-semibold">Coupon / Promo Code <span class="text-muted fw-normal">(Optional)</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="feather-tag text-muted"></i></span>
                                <input type="text" name="coupon_code" id="coupon_code" class="form-control text-uppercase @error('coupon_code') is-invalid @enderror" value="{{ old('coupon_code', $diningOffer->coupon_code) }}" placeholder="e.g. DINE20">
                            </div>
                            <small class="text-muted">Customers can apply or see this coupon code for the offer.</small>
                            @error('coupon_code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="discount_type" class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
                            <select name="discount_type" id="discount_type" class="form-select @error('discount_type') is-invalid @enderror" required>
                                <option value="percentage" {{ old('discount_type', $diningOffer->discount_type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('discount_type', $diningOffer->discount_type) === 'fixed' ? 'selected' : '' }}>Fixed Amount ({{ $currencySymbol }})</option>
                            </select>
                            @error('discount_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="discount_value" class="form-label fw-semibold">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="discount_value" id="discount_value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value', $diningOffer->discount_value) }}" required min="0">
                            @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="min_bill_amount" class="form-label fw-semibold">Min Bill Amount</label>
                            <input type="number" step="0.01" name="min_bill_amount" id="min_bill_amount" class="form-control @error('min_bill_amount') is-invalid @enderror" value="{{ old('min_bill_amount', $diningOffer->min_bill_amount) }}" min="0">
                            @error('min_bill_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="max_discount_amount" class="form-label fw-semibold">Max Discount Amount</label>
                            <input type="number" step="0.01" name="max_discount_amount" id="max_discount_amount" class="form-control @error('max_discount_amount') is-invalid @enderror" value="{{ old('max_discount_amount', $diningOffer->max_discount_amount) }}" min="0">
                            @error('max_discount_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="cover_charge" class="form-label fw-semibold">Cover Charge</label>
                            <input type="number" step="0.01" name="cover_charge" id="cover_charge" class="form-control @error('cover_charge') is-invalid @enderror" value="{{ old('cover_charge', $diningOffer->cover_charge) }}" min="0">
                            @error('cover_charge') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label fw-semibold">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $diningOffer->start_date?->format('Y-m-d')) }}">
                            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label fw-semibold">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $diningOffer->end_date?->format('Y-m-d')) }}">
                            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $diningOffer->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $diningOffer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('restaurant.dining-offers.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">
                            {{ $diningOffer->approval_status === 'rejected' ? 'Update & Resubmit' : 'Update Offer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

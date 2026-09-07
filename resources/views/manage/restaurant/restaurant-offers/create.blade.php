@extends('layouts.restaurant.main')

@section('title', 'Add Restaurant Offer - Zomato Partner')

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Add Restaurant Offer</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.restaurant-offers.index') }}">Restaurant Offers</a></li>
                <li class="breadcrumb-item">Add New</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.restaurant-offers.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('restaurant.restaurant-offers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label for="title" class="form-label fw-semibold">Offer Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="e.g. Weekend 20% Off">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="discount_type" class="form-label fw-semibold">Discount Type <span class="text-danger">*</span></label>
                            <select name="discount_type" id="discount_type" class="form-select @error('discount_type') is-invalid @enderror" required>
                                <option value="percentage" {{ old('discount_type', 'percentage') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount ({{ $currencySymbol }})</option>
                            </select>
                            @error('discount_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="discount_value" class="form-label fw-semibold">Discount Value <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="discount_value" id="discount_value" class="form-control @error('discount_value') is-invalid @enderror" value="{{ old('discount_value') }}" required min="0">
                            @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="minimum_order_amount" class="form-label fw-semibold">Minimum Order Amount</label>
                            <input type="number" step="0.01" name="minimum_order_amount" id="minimum_order_amount" class="form-control @error('minimum_order_amount') is-invalid @enderror" value="{{ old('minimum_order_amount') }}" min="0" placeholder="0">
                            @error('minimum_order_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="maximum_discount_amount" class="form-label fw-semibold">Maximum Discount Amount</label>
                            <input type="number" step="0.01" name="maximum_discount_amount" id="maximum_discount_amount" class="form-control @error('maximum_discount_amount') is-invalid @enderror" value="{{ old('maximum_discount_amount') }}" min="0" placeholder="No limit">
                            @error('maximum_discount_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="valid_from" class="form-label fw-semibold">Valid From</label>
                            <input type="date" name="valid_from" id="valid_from" class="form-control @error('valid_from') is-invalid @enderror" value="{{ old('valid_from') }}">
                            @error('valid_from') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="valid_until" class="form-label fw-semibold">Valid Until</label>
                            <input type="date" name="valid_until" id="valid_until" class="form-control @error('valid_until') is-invalid @enderror" value="{{ old('valid_until') }}">
                            @error('valid_until') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="banner" class="form-label fw-semibold">Banner Image</label>
                            <input type="file" name="banner" id="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/*">
                            <div class="form-text">Shown in the public "Today's Deal" section. JPG/PNG/WEBP, max 4MB.</div>
                            @error('banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">The offer also needs admin approval before appearing publicly.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('restaurant.restaurant-offers.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">Submit for Approval</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

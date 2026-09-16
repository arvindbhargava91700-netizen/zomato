@extends('layouts.admin.main')

@section('title', getPageTitle('Add New Restaurant'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Add New Restaurant</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurants.index') }}">Restaurants</a></li>
                <li class="breadcrumb-item">Add New</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Basic Information -->
                    <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">1. Basic Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="restaurant_name" class="form-label fw-semibold">Restaurant Name <span class="text-danger">*</span></label>
                            <input type="text" name="restaurant_name" id="restaurant_name" class="form-control @error('restaurant_name') is-invalid @enderror" value="{{ old('restaurant_name') }}" required placeholder="e.g. Spice Hub">
                            @error('restaurant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="brand_id" class="form-label fw-semibold">Brand <small class="text-muted">(Optional, active brands)</small></label>
                            <div class="d-flex align-items-center gap-2">
                                <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror" onchange="updateBrandPreview(this)">
                                    <option value="" data-logo="">-- Select Brand (Independent / None) --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" data-logo="{{ $brand->logo ? asset($brand->logo) : '' }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="brand_preview_box" class="flex-shrink-0" style="display: none;">
                                    <img id="brand_preview_img" src="" alt="Brand Logo" class="rounded-circle border shadow-sm" style="width: 38px; height: 38px; object-fit: cover;">
                                </div>
                            </div>
                            @error('brand_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="restaurant_slug" class="form-label fw-semibold">Slug <small class="text-muted">(Optional, auto-generated)</small></label>
                            <input type="text" name="restaurant_slug" id="restaurant_slug" class="form-control @error('restaurant_slug') is-invalid @enderror" value="{{ old('restaurant_slug') }}" placeholder="spice-hub">
                            @error('restaurant_slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="owner_name" class="form-label fw-semibold">Owner Name <span class="text-danger">*</span></label>
                            <input type="text" name="owner_name" id="owner_name" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name') }}" required placeholder="e.g. John Doe">
                            @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Section 2: Contact Details -->
                    <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">2. Contact & Address</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="restaurant@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="mobile" class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" required placeholder="+91 9876543210">
                            @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold">Full Address <span class="text-danger">*</span></label>
                            <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror" required placeholder="Street address, landmark...">{{ old('address') }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="postal_code" class="form-label fw-semibold">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code') }}" placeholder="110001">
                            @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="latitude" class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude') }}" placeholder="28.6139">
                            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="longitude" class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude') }}" placeholder="77.2090">
                            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Section 3: Operations & Financials -->
                    <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">3. Operations & Financials</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="opening_time" class="form-label fw-semibold">Opening Time</label>
                            <input type="time" name="opening_time" id="opening_time" class="form-control @error('opening_time') is-invalid @enderror" value="{{ old('opening_time', '09:00') }}">
                            @error('opening_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="closing_time" class="form-label fw-semibold">Closing Time</label>
                            <input type="time" name="closing_time" id="closing_time" class="form-control @error('closing_time') is-invalid @enderror" value="{{ old('closing_time', '23:00') }}">
                            @error('closing_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="minimum_order_amount" class="form-label fw-semibold">Min Order Amount (₹)</label>
                            <input type="number" step="0.01" name="minimum_order_amount" id="minimum_order_amount" class="form-control @error('minimum_order_amount') is-invalid @enderror" value="{{ old('minimum_order_amount', 0) }}">
                            @error('minimum_order_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="delivery_radius" class="form-label fw-semibold">Delivery Radius (km)</label>
                            <input type="number" step="0.1" name="delivery_radius" id="delivery_radius" class="form-control @error('delivery_radius') is-invalid @enderror" value="{{ old('delivery_radius', 5) }}">
                            @error('delivery_radius') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="estimated_delivery_time" class="form-label fw-semibold">Est. Delivery Time (mins)</label>
                            <input type="number" name="estimated_delivery_time" id="estimated_delivery_time" class="form-control @error('estimated_delivery_time') is-invalid @enderror" value="{{ old('estimated_delivery_time', 30) }}">
                            @error('estimated_delivery_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="commission_percentage" class="form-label fw-semibold">Commission (%)</label>
                            <input type="number" step="0.01" name="commission_percentage" id="commission_percentage" class="form-control @error('commission_percentage') is-invalid @enderror" value="{{ old('commission_percentage', 10.00) }}">
                            @error('commission_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="is_pure_veg" id="is_pure_veg" class="form-check-input" value="1" {{ old('is_pure_veg') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-success" for="is_pure_veg">Is Pure Veg Restaurant</label>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3.1: Restaurant Features & Amenities -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="pet_friendly" id="pet_friendly" class="form-check-input" value="1" {{ old('pet_friendly') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="pet_friendly"><i class="feather-heart me-1 text-danger"></i> Pet Friendly</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        @include('manage.partials.restaurant-features', ['selected' => old('features', [])])
                    </div>

                    <!-- Section 4: Licenses & Branding -->
                    <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">4. Licenses & Branding</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="gst_number" class="form-label fw-semibold">GST Number</label>
                            <input type="text" name="gst_number" id="gst_number" class="form-control @error('gst_number') is-invalid @enderror" value="{{ old('gst_number') }}" placeholder="22AAAAA0000A1Z5">
                            @error('gst_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fssai_number" class="form-label fw-semibold">FSSAI License Number</label>
                            <input type="text" name="fssai_number" id="fssai_number" class="form-control @error('fssai_number') is-invalid @enderror" value="{{ old('fssai_number') }}" placeholder="10000000000000">
                            @error('fssai_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="logo" class="form-label fw-semibold">Logo Image <small class="text-muted">(JPG, PNG, WEBP, Max 2MB)</small></label>
                            <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="banner" class="form-label fw-semibold">Banner Image <small class="text-muted">(JPG, PNG, WEBP, Max 4MB)</small></label>
                            <input type="file" name="banner" id="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/*">
                            @error('banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="qr_code" class="form-label fw-semibold">QR Code Image <small class="text-muted">(JPG, PNG, WEBP, Max 2MB)</small></label>
                            <input type="file" name="qr_code" id="qr_code" class="form-control @error('qr_code') is-invalid @enderror" accept="image/*">
                            @error('qr_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="About the restaurant, specialties...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">Save Restaurant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

@push('scripts')
<script>
    function updateBrandPreview(select) {
        var opt = select.options[select.selectedIndex];
        var logo = opt ? opt.getAttribute('data-logo') : '';
        var box = document.getElementById('brand_preview_box');
        var img = document.getElementById('brand_preview_img');
        if (logo) {
            img.src = logo;
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        var select = document.getElementById('brand_id');
        if (select) updateBrandPreview(select);

        var nameInput = document.getElementById('restaurant_name');
        var slugInput = document.getElementById('restaurant_slug');
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                var slug = this.value.toString().toLowerCase().trim()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
                slugInput.value = slug;
            });
        }
    });
</script>
@endpush

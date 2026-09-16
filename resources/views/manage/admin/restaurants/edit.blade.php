@extends('layouts.admin.main')

@section('title', getPageTitle('Edit Restaurant'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Restaurant</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurants.index') }}">Restaurants</a></li>
                <li class="breadcrumb-item">Edit: {{ $restaurant->restaurant_name }}</li>
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
                <form action="{{ route('admin.restaurants.update', $restaurant->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Section 1: Basic Information -->
                    <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">1. Basic Information</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="restaurant_name" class="form-label fw-semibold">Restaurant Name <span class="text-danger">*</span></label>
                            <input type="text" name="restaurant_name" id="restaurant_name" class="form-control @error('restaurant_name') is-invalid @enderror" value="{{ old('restaurant_name', $restaurant->restaurant_name) }}" required>
                            @error('restaurant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="brand_id" class="form-label fw-semibold">Brand <small class="text-muted">(Optional, active brands)</small></label>
                            <div class="d-flex align-items-center gap-2">
                                <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror" onchange="updateBrandPreview(this)">
                                    <option value="" data-logo="">-- Select Brand (Independent / None) --</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" data-logo="{{ $brand->logo ? asset($brand->logo) : '' }}" {{ old('brand_id', $restaurant->brand_id) == $brand->id ? 'selected' : '' }}>
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
                            <label for="restaurant_slug" class="form-label fw-semibold">Slug</label>
                            <input type="text" name="restaurant_slug" id="restaurant_slug" class="form-control @error('restaurant_slug') is-invalid @enderror" value="{{ old('restaurant_slug', $restaurant->restaurant_slug) }}">
                            @error('restaurant_slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="owner_name" class="form-label fw-semibold">Owner Name <span class="text-danger">*</span></label>
                            <input type="text" name="owner_name" id="owner_name" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name', $restaurant->owner_name) }}" required>
                            @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $restaurant->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $restaurant->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="pending" {{ old('status', $restaurant->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Section 2: Contact Details -->
                    <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">2. Contact & Address</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $restaurant->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="mobile" class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $restaurant->mobile) }}" required>
                            @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold">Full Address <span class="text-danger">*</span></label>
                            <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $restaurant->address) }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="postal_code" class="form-label fw-semibold">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" value="{{ old('postal_code', $restaurant->postal_code) }}">
                            @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="latitude" class="form-label fw-semibold">Latitude</label>
                            <input type="number" step="any" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $restaurant->latitude) }}">
                            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="longitude" class="form-label fw-semibold">Longitude</label>
                            <input type="number" step="any" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $restaurant->longitude) }}">
                            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Section 3: Operations & Financials -->
                    <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">3. Operations & Financials</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="opening_time" class="form-label fw-semibold">Opening Time</label>
                            <input type="time" name="opening_time" id="opening_time" class="form-control @error('opening_time') is-invalid @enderror" value="{{ old('opening_time', $restaurant->opening_time ? $restaurant->opening_time->format('H:i') : '') }}">
                            @error('opening_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="closing_time" class="form-label fw-semibold">Closing Time</label>
                            <input type="time" name="closing_time" id="closing_time" class="form-control @error('closing_time') is-invalid @enderror" value="{{ old('closing_time', $restaurant->closing_time ? $restaurant->closing_time->format('H:i') : '') }}">
                            @error('closing_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="minimum_order_amount" class="form-label fw-semibold">Min Order Amount (₹)</label>
                            <input type="number" step="0.01" name="minimum_order_amount" id="minimum_order_amount" class="form-control @error('minimum_order_amount') is-invalid @enderror" value="{{ old('minimum_order_amount', $restaurant->minimum_order_amount) }}">
                            @error('minimum_order_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="delivery_radius" class="form-label fw-semibold">Delivery Radius (km)</label>
                            <input type="number" step="0.1" name="delivery_radius" id="delivery_radius" class="form-control @error('delivery_radius') is-invalid @enderror" value="{{ old('delivery_radius', $restaurant->delivery_radius) }}">
                            @error('delivery_radius') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="estimated_delivery_time" class="form-label fw-semibold">Est. Delivery Time (mins)</label>
                            <input type="number" name="estimated_delivery_time" id="estimated_delivery_time" class="form-control @error('estimated_delivery_time') is-invalid @enderror" value="{{ old('estimated_delivery_time', $restaurant->estimated_delivery_time) }}">
                            @error('estimated_delivery_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="commission_percentage" class="form-label fw-semibold">Commission (%)</label>
                            <input type="number" step="0.01" name="commission_percentage" id="commission_percentage" class="form-control @error('commission_percentage') is-invalid @enderror" value="{{ old('commission_percentage', $restaurant->commission_percentage) }}">
                            @error('commission_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="dining_commission_percentage" class="form-label fw-semibold">Dining Commission (%)</label>
                            <input type="number" step="0.01" name="dining_commission_percentage" id="dining_commission_percentage" class="form-control @error('dining_commission_percentage') is-invalid @enderror" value="{{ old('dining_commission_percentage', $restaurant->dining_commission_percentage) }}">
                            @error('dining_commission_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="is_pure_veg" id="is_pure_veg" class="form-check-input" value="1" {{ old('is_pure_veg', $restaurant->is_pure_veg) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-success" for="is_pure_veg">Is Pure Veg Restaurant</label>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3.1: Restaurant Features & Amenities -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="pet_friendly" id="pet_friendly" class="form-check-input" value="1" {{ old('pet_friendly', $restaurant->pet_friendly) ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="pet_friendly"><i class="feather-heart me-1 text-danger"></i> Pet Friendly</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        @include('manage.partials.restaurant-features', ['selected' => old('features', $restaurant->features ?? [])])
                    </div>

                    <!-- Section 4: Licenses & Branding -->
                    <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">4. Licenses & Branding</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="gst_number" class="form-label fw-semibold">GST Number</label>
                            <input type="text" name="gst_number" id="gst_number" class="form-control @error('gst_number') is-invalid @enderror" value="{{ old('gst_number', $restaurant->gst_number) }}">
                            @error('gst_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fssai_number" class="form-label fw-semibold">FSSAI License Number</label>
                            <input type="text" name="fssai_number" id="fssai_number" class="form-control @error('fssai_number') is-invalid @enderror" value="{{ old('fssai_number', $restaurant->fssai_number) }}">
                            @error('fssai_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="logo" class="form-label fw-semibold">Logo Image</label>
                            @if($restaurant->logo)
                                <div class="mb-2">
                                    <img src="{{ asset($restaurant->logo) }}" alt="Current Logo" class="rounded border" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="banner" class="form-label fw-semibold">Banner Image</label>
                            @if($restaurant->banner)
                                <div class="mb-2">
                                    <img src="{{ asset($restaurant->banner) }}" alt="Current Banner" class="rounded border" style="height: 60px; width: 120px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="banner" id="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/*">
                            @error('banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="qr_code" class="form-label fw-semibold">QR Code Image</label>
                            @if($restaurant->qr_code)
                                <div class="mb-2">
                                    <img src="{{ asset($restaurant->qr_code) }}" alt="Current QR Code" class="rounded border" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="qr_code" id="qr_code" class="form-control @error('qr_code') is-invalid @enderror" accept="image/*">
                            @error('qr_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $restaurant->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">Update Restaurant</button>
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
    });
</script>
@endpush

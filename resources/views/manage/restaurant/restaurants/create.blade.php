@extends('layouts.restaurant.main')

@section('title', 'Add Restaurant - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Add Restaurant</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.restaurants.index') }}">Restaurants</a></li>
                <li class="breadcrumb-item">Add New</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.restaurants.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('restaurant.restaurants.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Section 1: Basic Information -->
                    <div class="section-title-wrap">
                        <span class="section-badge">1</span>
                        <h5 class="section-title mb-0">Basic Information</h5>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            @php $rtype = old('restaurant_type', 'restaurant'); @endphp
                            <label class="form-label fw-semibold">Restaurant Type <span class="text-danger">*</span></label>
                            <div class="type-picker">
                                <input type="radio" name="restaurant_type" id="type_restaurant" value="restaurant" {{ $rtype === 'restaurant' ? 'checked' : '' }}>
                                <label for="type_restaurant"><i class="feather-flag"></i><span>Restaurant</span></label>
                                <input type="radio" name="restaurant_type" id="type_brand" value="brand" {{ $rtype === 'brand' ? 'checked' : '' }}>
                                <label for="type_brand"><i class="feather-award"></i><span>Brand</span></label>
                                <input type="radio" name="restaurant_type" id="type_nightlife" value="nightlife" {{ $rtype === 'nightlife' ? 'checked' : '' }}>
                                <label for="type_nightlife"><i class="feather-moon"></i><span>Nightlife</span></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="restaurant_name" class="form-label fw-semibold">Restaurant Name <span class="text-danger">*</span></label>
                            <input type="text" name="restaurant_name" id="restaurant_name" class="form-control @error('restaurant_name') is-invalid @enderror" value="{{ old('restaurant_name') }}" required placeholder="e.g. Spice Hub">
                            @error('restaurant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6" id="brand_field_wrapper" @if(old('restaurant_type', 'restaurant') !== 'brand') style="display: none;" @endif>
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
                        <div class="col-md-6" id="nightlife_field_wrapper" @if(old('restaurant_type', 'restaurant') !== 'nightlife') style="display: none;" @endif>
                            <label for="nightlife_banner_id" class="form-label fw-semibold">Nightlife Banner <small class="text-muted">(Optional, active banners)</small></label>
                            <div class="d-flex align-items-center gap-2">
                                <select name="nightlife_banner_id" id="nightlife_banner_id" class="form-select @error('nightlife_banner_id') is-invalid @enderror" onchange="updateNightlifePreview(this)">
                                    <option value="" data-banner="">-- Select Nightlife Banner --</option>
                                    @foreach($nightlifeBanners as $banner)
                                        <option value="{{ $banner->id }}" data-banner="{{ $banner->banner ? asset($banner->banner) : '' }}" {{ old('nightlife_banner_id') == $banner->id ? 'selected' : '' }}>
                                            {{ $banner->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="nightlife_preview_box" class="flex-shrink-0" style="display: none;">
                                    <img id="nightlife_preview_img" src="" alt="Nightlife Banner" class="rounded border shadow-sm" style="width: 70px; height: 40px; object-fit: cover;">
                                </div>
                            </div>
                            @error('nightlife_banner_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="restaurant_slug" class="form-label fw-semibold">Slug <small class="text-muted">(Optional, auto-generated)</small></label>
                            <input type="text" name="restaurant_slug" id="restaurant_slug" class="form-control @error('restaurant_slug') is-invalid @enderror" value="{{ old('restaurant_slug') }}" placeholder="spice-hub">
                            @error('restaurant_slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="owner_name" class="form-label fw-semibold">Owner Name <span class="text-danger">*</span></label>
                            <input type="text" name="owner_name" id="owner_name" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name', auth()->user()->name ?? '') }}" required placeholder="e.g. John Doe">
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
                            <label for="country_id" class="form-label fw-semibold">Country</label>
                            <select name="country_id" id="country_id" class="form-select @error('country_id') is-invalid @enderror">
                                <option value="">Select Country</option>
                                @foreach ($countries as $id => $name)
                                    <option value="{{ $id }}" {{ old('country_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="country_id_error"></div>
                            @error('country_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="state_id" class="form-label fw-semibold">State</label>
                            <select name="state_id" id="state_id" class="form-select @error('state_id') is-invalid @enderror">
                                <option value="">Select State</option>
                            </select>
                            <div class="invalid-feedback" id="state_id_error"></div>
                            @error('state_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="city_id" class="form-label fw-semibold">City</label>
                            <select name="city_id" id="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                <option value="">Select City</option>
                            </select>
                            <div class="invalid-feedback" id="city_id_error"></div>
                            @error('city_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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

                        <div class="col-12">
                            <button type="button" id="use_location_btn" class="btn btn-primary">
                                <i class="feather-map-pin me-1"></i> Use My Location
                            </button>
                            <button type="button" id="fetch_details_btn" class="btn btn-outline-secondary">
                                <i class="feather-search me-1"></i> Fetch Details from Coordinates
                            </button>
                            <span id="geo_status" class="ms-2 text-muted small"></span>
                        </div>

                        <!-- Map (appears after coordinates are available) -->
                        <div class="col-12" id="map_wrapper" style="display: none;">
                            <label class="form-label fw-semibold">Location Map</label>
                            <div id="location_map" style="height: 320px; width: 100%; border-radius: 8px; overflow: hidden;"></div>
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

                    <!-- Section 4: Restaurant Features & Amenities -->
                    <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">4. Restaurant Features & Amenities</h5>
                    <div class="mb-4">
                        @include('manage.partials.restaurant-features', ['selected' => old('features', [])])
                    </div>

                    <!-- Section 5: Licenses & Branding -->
                    <h5 class="fw-bold text-danger border-bottom pb-2 mb-3">5. Licenses & Branding</h5>
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
                        <div class="col-md-6">
                            <label for="pan_number" class="form-label fw-semibold">PAN Number</label>
                            <input type="text" name="pan_number" id="pan_number" class="form-control @error('pan_number') is-invalid @enderror" value="{{ old('pan_number') }}" placeholder="ABCDE1234F">
                            @error('pan_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="bank_name" class="form-label fw-semibold">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}" placeholder="State Bank of India">
                            @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="account_number" class="form-label fw-semibold">Account Number</label>
                            <input type="text" name="account_number" id="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}" placeholder="1234567890">
                            @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ifsc_code" class="form-label fw-semibold">IFSC Code</label>
                            <input type="text" name="ifsc_code" id="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror" value="{{ old('ifsc_code') }}" placeholder="SBIN0001234">
                            @error('ifsc_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    <div class="d-flex justify-content-end align-items-center gap-2 mt-4 pt-3 border-top action-bar">
                        <a href="{{ route('restaurant.restaurants.index') }}" class="btn btn-cancel px-4 fw-semibold">
                            <i class="feather-x me-1"></i>
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-zomato px-4 fw-semibold" id="saveRestaurantBtn">
                            <span class="btn-icons">
                                <i class="feather-save me-1"></i>
                                Save Restaurant
                            </span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        /* Action Bar */
        .action-bar {
            border-color: #e9ecef !important;
        }
        .action-bar .btn {
            height: 46px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 150px;
        }

        /* Cancel Button */
        .btn-cancel {
            background: #ffffff !important;
            border: 1.5px solid #d9d9d9 !important;
            color: #555555 !important;
            transition: all 0.2s ease;
        }
        .btn-cancel:hover {
            border-color: #cb202d !important;
            color: #cb202d !important;
            background: #ffffff !important;
        }

        /* Save Button - Zomato Red */
        .btn-zomato {
            background-color: #cb202d !important;
            border: none !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px rgba(203, 32, 45, .25) !important;
            transition: all .2s ease;
            letter-spacing: .3px;
        }
        .btn-zomato:hover,
        .btn-zomato:focus,
        .btn-zomato:active {
            background-color: #a81a25 !important;
            border: none !important;
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(203, 32, 45, .35) !important;
            transform: translateY(-1px);
        }
        .btn-zomato:disabled {
            background-color: #a81a25 !important;
            color: #ffffff !important;
            opacity: .85;
            cursor: not-allowed;
            transform: none !important;
        }

        /* Loading Spinner */
        .btn-loading {
            display: inline-flex;
            align-items: center;
        }
        .btn-loading.d-none {
            display: none !important;
        }
        .section-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 12px;
            margin-bottom: 24px;
            border-bottom: 2px solid #f1f1f1;
        }
        .section-badge {
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #cb202d;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
        }
        .section-title {
            font-weight: 700;
            color: #1c1c1c;
            font-size: 1.05rem;
        }
        .type-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding-top: 4px;
        }
        .type-picker input {
            display: none;
        }
        .type-picker label {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            color: #6b7280;
            background: #fff;
            transition: all .2s ease;
        }
        .type-picker label i {
            font-size: 16px;
        }
        .type-picker label:hover {
            border-color: #cb202d;
            color: #cb202d;
        }
        .type-picker input:checked + label {
            border-color: #cb202d;
            background: #cb202d0d;
            color: #cb202d;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('admin/assets/vendors/js/jquery.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        $(function () {
            var $csrf = $('meta[name="csrf-token"]').attr('content');
            var map = null, marker = null;

            function clearGeoErrors() {
                ['country_id', 'state_id', 'city_id'].forEach(function (f) {
                    $('#' + f).removeClass('is-invalid');
                    $('#' + f + '_error').text('');
                });
            }

            function showGeoError(field, msg) {
                $('#' + field).addClass('is-invalid');
                $('#' + field + '_error').text(msg);
            }

            function showMap(lat, lng) {
                $('#map_wrapper').show();
                if (!map) {
                    map = L.map('location_map').setView([lat, lng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19, attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);
                    marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                    marker.on('dragend', function (e) {
                        var p = e.target.getLatLng();
                        $('#latitude').val(p.lat.toFixed(6));
                        $('#longitude').val(p.lng.toFixed(6));
                    });
                } else {
                    map.setView([lat, lng], 15);
                    marker.setLatLng([lat, lng]);
                }
                setTimeout(function () { map.invalidateSize(); }, 200);
            }

            function loadStates(countryId, selectedStateId, callback) {
                var $state = $('#state_id');
                $state.html('<option value="">Select State</option>');
                $('#city_id').html('<option value="">Select City</option>');
                if (!countryId) { if (callback) callback(); return; }
                $.get('{{ route('restaurant.geo.states') }}', { country_id: countryId }, function (data) {
                    $.each(data, function (i, s) {
                        $state.append('<option value="' + s.id + '">' + s.name + '</option>');
                    });
                    if (selectedStateId) { $state.val(selectedStateId); }
                    if (callback) callback();
                });
            }

            function loadCities(stateId, selectedCityId) {
                var $city = $('#city_id');
                $city.html('<option value="">Select City</option>');
                if (!stateId) { return; }
                $.get('{{ route('restaurant.geo.cities') }}', { state_id: stateId }, function (data) {
                    $.each(data, function (i, c) {
                        $city.append('<option value="' + c.id + '">' + c.name + '</option>');
                    });
                    if (selectedCityId) { $city.val(selectedCityId); }
                });
            }

            // Dependent dropdowns (manual selection)
            $('#country_id').on('change', function () {
                loadStates($(this).val(), null);
            });
            $('#state_id').on('change', function () {
                loadCities($(this).val(), null);
            });

            // Show brand/nightlife lists only when the matching Restaurant Type is selected
            $('input[name="restaurant_type"]').on('change', function () {
                $('#brand_field_wrapper').toggle($(this).val() === 'brand');
                $('#nightlife_field_wrapper').toggle($(this).val() === 'nightlife');
            });

            // Submit loading state
            $('form').on('submit', function () {
                var $btn = $(this).find('button[type="submit"]');
                if ($btn.hasClass('is-loading')) {
                    return false;
                }
                $btn.addClass('is-loading').prop('disabled', true);
                $btn.find('.btn-icons').addClass('d-none');
                $btn.find('.btn-loading').removeClass('d-none');
            });

            function resolveAndApply(lat, lng, addressData) {
                clearGeoErrors();
                $('#geo_status').text('Fetching location details...');

                // Reverse geocode via OpenStreetMap Nominatim (no API key needed)
                var url = 'https://nominatim.openstreetmap.org/reverse?format=json&zoom=18&addressdetails=1&lat=' + lat + '&lon=' + lng;
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function (res) {
                        var addr = (res && res.address) ? res.address : {};
                        if (res && res.display_name) {
                            $('#address').val(res.display_name);
                        }
                        if (addr.postcode) {
                            $('#postal_code').val(addr.postcode);
                        }

                        var countryName = addr.country || '';
                        var stateName = addr.state || addr.region || '';
                        var cityName = addr.city || addr.town || addr.village || addr.county || '';

                        $.ajax({
                            url: '{{ route('restaurant.geo.resolve') }}',
                            type: 'POST',
                            data: {
                                _token: $csrf,
                                country: countryName,
                                state: stateName,
                                city: cityName
                            },
                            dataType: 'json',
                            success: function (r) {
                                if (!r.success) {
                                    $.each(r.errors, function (field, msg) {
                                        showGeoError(field, msg);
                                    });
                                    $('#geo_status').html('<span class="text-warning">Some location details were not found in our system.</span>');
                                } else {
                                    $('#geo_status').html('<span class="text-success">Location details fetched.</span>');
                                }

                                var d = r.data || {};
                                if (d.country_id) {
                                    $('#country_id').val(d.country_id);
                                    loadStates(d.country_id, d.state_id, function () {
                                        if (d.state_id) { loadCities(d.state_id, d.city_id); }
                                    });
                                }
                            },
                            error: function () {
                                $('#geo_status').html('<span class="text-danger">Could not resolve location. Please select manually.</span>');
                            }
                        });

                        showMap(lat, lng);
                    },
                    error: function () {
                        $('#geo_status').html('<span class="text-danger">Reverse geocoding failed. Enter coordinates manually if needed.</span>');
                        showMap(lat, lng);
                    }
                });
            }

            $('#use_location_btn').on('click', function () {
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser.');
                    return;
                }
                $('#geo_status').text('Locating...');
                navigator.geolocation.getCurrentPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    $('#latitude').val(lat.toFixed(6));
                    $('#longitude').val(lng.toFixed(6));
                    resolveAndApply(lat, lng);
                }, function () {
                    $('#geo_status').html('<span class="text-danger">Unable to retrieve your location.</span>');
                });
            });

            $('#fetch_details_btn').on('click', function () {
                var lat = parseFloat($('#latitude').val());
                var lng = parseFloat($('#longitude').val());
                if (isNaN(lat) || isNaN(lng)) {
                    alert('Please enter both latitude and longitude first.');
                    return;
                }
                resolveAndApply(lat, lng);
            });

            // Automatic Slug Generation
            $('#restaurant_name').on('input', function() {
                var name = $(this).val();
                var slug = name.toString().toLowerCase().trim()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
                $('#restaurant_slug').val(slug);
            });

            var select = document.getElementById('brand_id');
            if (select) updateBrandPreview(select);
        });

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

        function updateNightlifePreview(select) {
            var opt = select.options[select.selectedIndex];
            var banner = opt ? opt.getAttribute('data-banner') : '';
            var box = document.getElementById('nightlife_preview_box');
            var img = document.getElementById('nightlife_preview_img');
            if (banner) {
                img.src = banner;
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        }
    </script>
@endpush
@endsection

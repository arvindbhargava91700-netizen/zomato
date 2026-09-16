@extends('layouts.admin.main')

@section('title', getPageTitle('General Settings'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Commission / General Settings</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Settings</li>
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

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Regional & Tax Format</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="currency" class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                            <input type="text" name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror" value="{{ old('currency', $setting->currency) }}" required placeholder="INR">
                            @error('currency') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="timezone" class="form-label fw-semibold">Timezone <span class="text-danger">*</span></label>
                            <input type="text" name="timezone" id="timezone" class="form-control @error('timezone') is-invalid @enderror" value="{{ old('timezone', $setting->timezone) }}" required placeholder="Asia/Kolkata">
                            @error('timezone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="date_format" class="form-label fw-semibold">Date Format <span class="text-danger">*</span></label>
                            <input type="text" name="date_format" id="date_format" class="form-control @error('date_format') is-invalid @enderror" value="{{ old('date_format', $setting->date_format) }}" required placeholder="d/m/Y">
                            @error('date_format') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="tax_gst" class="form-label fw-semibold">Tax / GST</label>
                            <input type="text" name="tax_gst" id="tax_gst" class="form-control @error('tax_gst') is-invalid @enderror" value="{{ old('tax_gst', $setting->tax_gst) }}" placeholder="e.g. 5% GST">
                            @error('tax_gst') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="invoice_prefix" class="form-label fw-semibold">Invoice Prefix</label>
                            <input type="text" name="invoice_prefix" id="invoice_prefix" class="form-control @error('invoice_prefix') is-invalid @enderror" value="{{ old('invoice_prefix', $setting->invoice_prefix) }}" placeholder="e.g. INV-">
                            @error('invoice_prefix') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="receipt_prefix" class="form-label fw-semibold">Receipt Prefix</label>
                            <input type="text" name="receipt_prefix" id="receipt_prefix" class="form-control @error('receipt_prefix') is-invalid @enderror" value="{{ old('receipt_prefix', $setting->receipt_prefix) }}" placeholder="e.g. RCP-">
                            @error('receipt_prefix') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>


            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Revenue Split &amp; Delivery Partner Rules</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="platform_share" class="form-label fw-semibold">Admin Share / Commission (%)</label>
                            <input type="number" name="platform_share" id="platform_share" step="0.01" min="0" max="100" class="form-control @error('platform_share') is-invalid @enderror" value="{{ old('platform_share', $setting->platform_share ?? 15.00) }}" placeholder="15.00">
                            @error('platform_share') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted d-block mt-1">Platform commission on deliveries.</small>
                        </div>
                        <div class="col-md-4">
                            <label for="delivery_partner_share" class="form-label fw-semibold">Delivery Partner Share (%)</label>
                            <input type="number" name="delivery_partner_share" id="delivery_partner_share" step="0.01" min="0" max="100" class="form-control @error('delivery_partner_share') is-invalid @enderror" value="{{ old('delivery_partner_share', $setting->delivery_partner_share ?? 85.00) }}" placeholder="85.00">
                            @error('delivery_partner_share') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted d-block mt-1">Delivery partner payout share.</small>
                        </div>
                        <div class="col-md-4">
                            <label for="delivery_partner_cod_limit" class="form-label fw-semibold">Delivery Partner COD Limit ({{ $setting->currencySymbol() }})</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light fw-bold">{{ $setting->currencySymbol() }}</span>
                                <input type="number" name="delivery_partner_cod_limit" id="delivery_partner_cod_limit" step="0.01" min="0" class="form-control @error('delivery_partner_cod_limit') is-invalid @enderror" value="{{ old('delivery_partner_cod_limit', $setting->delivery_partner_cod_limit ?? 5000.00) }}" placeholder="5000.00">
                            </div>
                            @error('delivery_partner_cod_limit') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            <small class="text-muted d-block mt-1">Maximum cash-on-delivery holding limit allowed before settlement is required.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Dining / Table Booking Commission</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="dining_com_per" class="form-label fw-semibold">Admin Share / Commission (%)</label>
                            <input type="number" name="dining_com_per" id="dining_com_per" step="0.01" min="0" max="100" class="form-control @error('dining_com_per') is-invalid @enderror" value="{{ old('dining_com_per', $setting->dining_com_per ?? 10.00) }}" placeholder="10.00">
                            @error('dining_com_per') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="dining_restaurant_share" class="form-label fw-semibold">Restaurant Share (%)</label>
                            <input type="number" name="dining_restaurant_share" id="dining_restaurant_share" step="0.01" min="0" max="100" class="form-control @error('dining_restaurant_share') is-invalid @enderror" value="{{ old('dining_restaurant_share', $setting->dining_restaurant_share ?? 90.00) }}" placeholder="90.00">
                            @error('dining_restaurant_share') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <small class="text-muted">Platform commission split on dining / table booking bills: 10% for Admin and 90% for Restaurant. Total should be 100%.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Cover Charges Split (Dining &amp; Table Bookings)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="cover_charge_admin_share" class="form-label fw-semibold">Admin Share (%)</label>
                            <input type="number" name="cover_charge_admin_share" id="cover_charge_admin_share" step="0.01" min="0" max="100" class="form-control @error('cover_charge_admin_share') is-invalid @enderror" value="{{ old('cover_charge_admin_share', $setting->cover_charge_admin_share ?? 20.00) }}" placeholder="20.00">
                            @error('cover_charge_admin_share') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="cover_charge_restaurant_share" class="form-label fw-semibold">Restaurant Share (%)</label>
                            <input type="number" name="cover_charge_restaurant_share" id="cover_charge_restaurant_share" step="0.01" min="0" max="100" class="form-control @error('cover_charge_restaurant_share') is-invalid @enderror" value="{{ old('cover_charge_restaurant_share', $setting->cover_charge_restaurant_share ?? 80.00) }}" placeholder="80.00">
                            @error('cover_charge_restaurant_share') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <small class="text-muted">Percentage split on pre-paid cover charges for dining &amp; table reservations (e.g. 20% for Admin, 80% for Restaurant). Total should be 100%.</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">Save Settings</button>
            </div>
        </form>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
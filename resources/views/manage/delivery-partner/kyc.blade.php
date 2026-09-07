@extends('layouts.delivery-partner.main')

@section('title', 'KYC Verification - Delivery Partner Dashboard')

@push('styles')
<style>
    .wizard-stepper {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 6px;
    }
    .wizard-step-item {
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0.55;
        transition: opacity 0.25s ease;
    }
    .wizard-step-item.active,
    .wizard-step-item.done {
        opacity: 1;
    }
    .wizard-step-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #fff;
        background: #cbd5e1;
        flex-shrink: 0;
    }
    .wizard-step-item.active .wizard-step-circle {
        background: linear-gradient(90deg, #cb202d 0%, #e0555f 100%);
        box-shadow: 0 4px 12px rgba(203,32,45,0.35);
    }
    .wizard-step-item.done .wizard-step-circle {
        background: #2ba842;
    }
    .wizard-step-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }
    .wizard-step-line {
        width: 40px;
        height: 2px;
        background: #cbd5e1;
        border-radius: 2px;
        flex-shrink: 0;
    }
    .wizard-step-line.done { background: #2ba842; }

    .stepper-line {
        width: 48px;
        height: 2px;
        background: rgba(255,255,255,0.35);
        border-radius: 2px;
        flex-shrink: 0;
    }
    .text-muted-uppercase {
        font-size: 13px;
        letter-spacing: 0.3px;
    }
    .upload-zone {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 92px;
        padding: 14px;
        border: 2px dashed #d0d5dd;
        border-radius: 12px;
        background: #fafbfc;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        width: 100%;
    }
    .upload-zone:hover {
        border-color: #cb202d;
        background: #fff5f5;
        color: #cb202d;
    }
    .upload-zone i {
        font-size: 26px;
        color: #cb202d;
    }
    .upload-zone.uploaded {
        border-color: #2ba842;
        background: #f2fbf4;
        color: #1e7e34;
    }
    .upload-zone.uploaded i {
        color: #2ba842;
    }
    .form-label {
        font-size: 13px;
        color: #475569;
        margin-bottom: 6px;
    }
    .input-group-text {
        border-radius: 10px 0 0 10px;
    }
    .form-select, .form-control {
        border-radius: 10px;
    }
    .input-group > .form-control,
    .input-group > .form-select {
        border-radius: 0 10px 10px 0;
    }
</style>
@endpush

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">KYC Verification</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('delivery-partner.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">KYC</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            @if($partner->kyc_status === 'approved')
                <span class="badge bg-soft-success text-success rounded-pill px-3 py-2 fs-12 fw-semibold">
                    <i class="feather-check-circle me-1"></i>Approved
                </span>
            @elseif($partner->kyc_status === 'rejected')
                <span class="badge bg-soft-danger text-danger rounded-pill px-3 py-2 fs-12 fw-semibold">
                    <i class="feather-x-circle me-1"></i>Rejected
                </span>
            @elseif($partner->kyc_status === 'pending')
                <span class="badge bg-soft-warning text-warning rounded-pill px-3 py-2 fs-12 fw-semibold">
                    <i class="feather-clock me-1"></i>Pending Approval
                </span>
            @else
                <span class="badge bg-soft-secondary text-secondary rounded-pill px-3 py-2 fs-12 fw-semibold">
                    <i class="feather-edit me-1"></i>Not Submitted
                </span>
            @endif
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Admin KYC Decision Banner -->
        @if($partner->kyc_status === 'approved')
            <div class="alert alert-success alert-dismissible fade show mb-4 d-flex align-items-start gap-2" role="alert">
                <i class="feather-check-circle fs-5 mt-1"></i>
                <div>
                    <div class="fw-bold">Your KYC has been approved by the admin.</div>
                    @if($partner->kyc_remark)
                        <div class="mt-1"><strong>Admin Remark:</strong> {{ $partner->kyc_remark }}</div>
                    @endif
                    @if($partner->kyc_reviewed_at)
                        <div class="text-muted mt-1 fs-12">Reviewed on {{ $partner->kyc_reviewed_at->format('d M Y h:i A') }}</div>
                    @endif
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif($partner->kyc_status === 'rejected')
            <div class="alert alert-danger alert-dismissible fade show mb-4 d-flex align-items-start gap-2" role="alert">
                <i class="feather-x-circle fs-5 mt-1"></i>
                <div>
                    <div class="fw-bold">Your KYC has been rejected by the admin.</div>
                    @if($partner->kyc_rejected_reason)
                        <div class="mt-1"><strong>Admin Remark:</strong> {{ $partner->kyc_rejected_reason }}</div>
                    @endif
                    @if($partner->kyc_reviewed_at)
                        <div class="text-muted mt-1 fs-12">Reviewed on {{ $partner->kyc_reviewed_at->format('d M Y h:i A') }}</div>
                    @endif
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif($partner->kyc_status === 'pending')
            <div class="alert alert-info alert-dismissible fade show mb-4 d-flex align-items-start gap-2" role="alert">
                <i class="feather-clock fs-5 mt-1"></i>
                <div>
                    <div class="fw-bold">Your KYC is under review.</div>
                    <div class="mt-1">Your submitted documents have been sent to the admin. You will be notified once a decision is made.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($partner)
        @php $kycLocked = in_array($partner->kyc_status, ['approved', 'pending']); @endphp
        @if(!$kycLocked)
        <!-- Wizard Stepper -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body py-3">
                    <div class="wizard-stepper">
                        <div class="wizard-step-item active" data-nav="1">
                            <div class="wizard-step-circle">1</div>
                            <div class="wizard-step-label">Personal</div>
                        </div>
                        <div class="wizard-step-line" data-line="1"></div>
                        <div class="wizard-step-item" data-nav="2">
                            <div class="wizard-step-circle">2</div>
                            <div class="wizard-step-label">Vehicle</div>
                        </div>
                        <div class="wizard-step-line" data-line="2"></div>
                        <div class="wizard-step-item" data-nav="3">
                            <div class="wizard-step-circle">3</div>
                            <div class="wizard-step-label">Documents</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Single Wizard Form -->
            <form action="{{ route('delivery-partner.kyc.submit') }}" method="POST" enctype="multipart/form-data" id="kycWizardForm">
                @csrf

                <!-- Step 1 - Personal Details -->
                <div class="wizard-step" data-step="1">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
                        <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-3" style="background: linear-gradient(90deg, #cb202d 0%, #e0555f 100%);">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center fw-bold text-danger" style="width: 34px; height: 34px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">1</div>
                            <div>
                                <h5 class="card-title mb-0 text-white fw-bold">Personal Details</h5>
                                <span class="text-white-50 fs-12">Your identity, address & photos</span>
                            </div>
                            <span class="ms-auto badge bg-white text-danger rounded-pill px-3 py-2 fs-12 fw-semibold">
                                <i class="feather-user me-1"></i>Identity
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="name" class="form-label fw-semibold text-muted-uppercase">Full Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-user text-danger"></i></span>
                                        <input type="text" name="name" id="name" class="form-control border-start-0 bg-light" value="{{ old('name', $partner->name ?? '') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="age" class="form-label fw-semibold">Age <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-gift text-danger"></i></span>
                                        <input type="number" name="age" id="age" class="form-control border-start-0 @error('age') is-invalid @enderror"
                                               value="{{ old('age', $partner->age ?? '') }}" placeholder="e.g. 25" required min="18" max="100">
                                    </div>
                                    @error('age') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="phone" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-phone text-danger"></i></span>
                                        <input type="text" name="phone" id="phone" class="form-control border-start-0 @error('phone') is-invalid @enderror"
                                               value="{{ old('phone', $partner->phone ?? '') }}" placeholder="e.g. 98765 43210" required maxlength="15">
                                    </div>
                                    @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="country_id" class="form-label fw-semibold">Country <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-globe text-danger"></i></span>
                                        <select name="country_id" id="country_id" class="form-select border-start-0 @error('country_id') is-invalid @enderror" required>
                                            <option value="">Select Country</option>
                                            @foreach (\App\Models\Country::orderBy('name')->pluck('name', 'id') as $id => $name)
                                                <option value="{{ $id }}" {{ (old('country_id') ?: $partner->country_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('country_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="state_id" class="form-label fw-semibold">State</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-map text-danger"></i></span>
                                        <select name="state_id" id="state_id" class="form-select border-start-0 @error('state_id') is-invalid @enderror">
                                            <option value="">Select State</option>
                                        </select>
                                    </div>
                                    @error('state_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="city_id" class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-map-pin text-danger"></i></span>
                                        <select name="city_id" id="city_id" class="form-select border-start-0 @error('city_id') is-invalid @enderror" required>
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                    @error('city_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="passport_photo" class="form-label fw-semibold">Passport Size Photo <span class="text-danger">*</span></label>
                                    <label for="passport_photo" class="upload-zone {{ $partner->passport_photo ? 'uploaded' : '' }}">
                                        <i class="feather-camera"></i>
                                        <span>{{ $partner->passport_photo ? 'Photo uploaded - Click to change' : 'Click to upload passport photo' }}</span>
                                        <input type="file" name="passport_photo" id="passport_photo" class="d-none" accept="image/*" {{ $partner->passport_photo ? '' : 'required' }}>
                                    </label>
                                    @error('passport_photo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="aadhar_front" class="form-label fw-semibold">Aadhar Card (Front) <span class="text-danger">*</span></label>
                                            <label for="aadhar_front" class="upload-zone {{ $partner->aadhar_front ? 'uploaded' : '' }}">
                                                <i class="feather-file-text"></i>
                                                <span>{{ $partner->aadhar_front ? 'Aadhar front uploaded - Click to change' : 'Click to upload Aadhar front' }}</span>
                                                <input type="file" name="aadhar_front" id="aadhar_front" class="d-none" accept="image/*" {{ $partner->aadhar_front ? '' : 'required' }}>
                                            </label>
                                            @error('aadhar_front') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="aadhar_back" class="form-label fw-semibold">Aadhar Card (Back) <span class="text-danger">*</span></label>
                                            <label for="aadhar_back" class="upload-zone {{ $partner->aadhar_back ? 'uploaded' : '' }}">
                                                <i class="feather-file-text"></i>
                                                <span>{{ $partner->aadhar_back ? 'Aadhar back uploaded - Click to change' : 'Click to upload Aadhar back' }}</span>
                                                <input type="file" name="aadhar_back" id="aadhar_back" class="d-none" accept="image/*" {{ $partner->aadhar_back ? '' : 'required' }}>
                                            </label>
                                            @error('aadhar_back') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2 - Vehicle Details -->
                <div class="wizard-step" data-step="2" style="display:none;">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
                        <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-3" style="background: linear-gradient(90deg, #e87c0f 0%, #f5a623 100%);">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center fw-bold text-warning" style="width: 34px; height: 34px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">2</div>
                            <div>
                                <h5 class="card-title mb-0 text-white fw-bold">Vehicle Details</h5>
                                <span class="text-white-50 fs-12">Your delivery vehicle information</span>
                            </div>
                            <span class="ms-auto badge bg-white text-warning rounded-pill px-3 py-2 fs-12 fw-semibold">
                                <i class="feather-truck me-1"></i>Vehicle
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-muted mb-4 d-flex align-items-center gap-2">
                                <i class="feather-info text-warning"></i>
                                Select your vehicle type. If you use a bike, you must provide the RC (Registration Certificate) and vehicle number.
                            </p>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="vehicle_type" class="form-label fw-semibold">Vehicle Type <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-truck text-warning"></i></span>
                                        <select name="vehicle_type" id="vehicle_type" class="form-select border-start-0 @error('vehicle_type') is-invalid @enderror" required>
                                            <option value="">Select Vehicle Type</option>
                                            <option value="bike" {{ (old('vehicle_type') ?: $partner->vehicle_type) == 'bike' ? 'selected' : '' }}>Bike</option>
                                            <option value="ev" {{ (old('vehicle_type') ?: $partner->vehicle_type) == 'ev' ? 'selected' : '' }}>EV</option>
                                            <option value="cycle" {{ (old('vehicle_type') ?: $partner->vehicle_type) == 'cycle' ? 'selected' : '' }}>Cycle</option>
                                        </select>
                                    </div>
                                    @error('vehicle_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 vehicle-bike-field" style="{{ (old('vehicle_type') ?: $partner->vehicle_type) == 'bike' ? '' : 'display:none;' }}">
                                    <label for="vehicle_number" class="form-label fw-semibold">Vehicle Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-hash text-warning"></i></span>
                                        <input type="text" name="vehicle_number" id="vehicle_number" class="form-control border-start-0 @error('vehicle_number') is-invalid @enderror"
                                               value="{{ old('vehicle_number', $partner->vehicle_number ?? '') }}" placeholder="e.g. UP 32 AB 1234">
                                    </div>
                                    @error('vehicle_number') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 vehicle-bike-field" style="{{ (old('vehicle_type') ?: $partner->vehicle_type) == 'bike' ? '' : 'display:none;' }}">
                                    <label for="rc_image" class="form-label fw-semibold">RC (Registration Certificate) <span class="text-danger">*</span></label>
                                    <label for="rc_image" class="upload-zone {{ $partner->rc_image ? 'uploaded' : '' }}">
                                        <i class="feather-file"></i>
                                        <span>{{ $partner->rc_image ? 'RC uploaded - Click to change' : 'Click to upload RC certificate' }}</span>
                                        <input type="file" name="rc_image" id="rc_image" class="d-none" accept="image/*">
                                    </label>
                                    @error('rc_image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 - Documents Upload -->
                <div class="wizard-step" data-step="3" style="display:none;">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
                        <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-3" style="background: linear-gradient(90deg, #1e7e34 0%, #2ba842 100%);">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center fw-bold text-success" style="width: 34px; height: 34px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">3</div>
                            <div>
                                <h5 class="card-title mb-0 text-white fw-bold">Documents Upload</h5>
                                <span class="text-white-50 fs-12">Financial & identity documents</span>
                            </div>
                            <span class="ms-auto badge bg-white text-success rounded-pill px-3 py-2 fs-12 fw-semibold">
                                <i class="feather-upload me-1"></i>Documents
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="aadhar_card" class="form-label fw-semibold">Aadhar Card Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-credit-card text-success"></i></span>
                                        <input type="text" name="aadhar_card" id="aadhar_card" class="form-control border-start-0 @error('aadhar_card') is-invalid @enderror"
                                               value="{{ old('aadhar_card', $partner->aadhar_card ?? '') }}" placeholder="1234 5678 9012" required>
                                    </div>
                                    @error('aadhar_card') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="pan_card" class="form-label fw-semibold">PAN Card Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-file-text text-success"></i></span>
                                        <input type="text" name="pan_card" id="pan_card" class="form-control border-start-0 @error('pan_card') is-invalid @enderror"
                                               value="{{ old('pan_card', $partner->pan_card ?? '') }}" placeholder="ABCDE1234F" required>
                                    </div>
                                    @error('pan_card') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="bank_account" class="form-label fw-semibold">Bank Account Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-briefcase text-success"></i></span>
                                        <input type="text" name="bank_account" id="bank_account" class="form-control border-start-0 @error('bank_account') is-invalid @enderror"
                                               value="{{ old('bank_account', $partner->bank_account ?? '') }}" placeholder="1234567890" required>
                                    </div>
                                    @error('bank_account') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="ifsc_code" class="form-label fw-semibold">IFSC Code</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-codesandbox text-success"></i></span>
                                        <input type="text" name="ifsc_code" id="ifsc_code" class="form-control border-start-0 @error('ifsc_code') is-invalid @enderror"
                                               value="{{ old('ifsc_code', $partner->ifsc_code ?? '') }}" placeholder="SBIN0001234" required>
                                    </div>
                                    @error('ifsc_code') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="bank_name" class="form-label fw-semibold">Bank Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="feather-bank text-success"></i></span>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control border-start-0 @error('bank_name') is-invalid @enderror"
                                               value="{{ old('bank_name', $partner->bank_name ?? '') }}" placeholder="State Bank of India" required>
                                    </div>
                                    @error('bank_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wizard Navigation -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body d-flex align-items-center justify-content-between gap-2">
                        <button type="button" class="btn btn-light border px-4 fw-semibold" id="btnBack" style="display:none;">
                            <i class="feather-arrow-left me-1"></i> Back
                        </button>
                        <div class="ms-auto d-flex gap-2">
                            <a href="{{ route('delivery-partner.dashboard') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                            <button type="button" class="btn text-white px-4 fw-semibold shadow-sm" id="btnNext" style="background: linear-gradient(90deg, #e87c0f 0%, #f5a623 100%); border: none; border-radius: 10px;">
                                Next <i class="feather-arrow-right ms-1"></i>
                            </button>
                            <button type="submit" class="btn text-white px-4 py-2 fw-semibold shadow-sm" id="btnSubmit" style="display:none; background: linear-gradient(90deg, #1e7e34 0%, #2ba842 100%); border: none; border-radius: 10px;">
                                <i class="feather-upload me-1"></i> Submit KYC Details
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="alert alert-secondary mb-4 d-flex align-items-start gap-2" role="alert">
                <i class="feather-eye fs-5 mt-1"></i>
                <div>
                    <div class="fw-bold">KYC is in view-only mode.</div>
                    <div class="mt-1">
                        Your KYC details have been submitted and are
                        {{ $partner->kyc_status === 'approved' ? 'approved' : 'under review' }}.
                        Editing is disabled — you can review your submitted information below.
                    </div>
                </div>
            </div>
        @endif

            <!-- Profile Summary & Uploaded Documents (View Only) -->
            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
                <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-3" style="background: linear-gradient(90deg, #3b82f6 0%, #10b981 100%);">
                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                        <i class="feather-eye text-primary"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 text-white fw-bold">My Profile & Uploaded Documents</h5>
                        <span class="text-white-50 fs-12">Your submitted KYC details for admin review</span>
                    </div>
                    <span class="ms-auto badge bg-white text-primary rounded-pill px-3 py-2 fs-12 fw-semibold">
                        <i class="feather-shield me-1"></i>Under Review
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Profile Summary -->
                        <div class="col-lg-5">
                            <div class="bg-light rounded-3 p-4 h-100">
                                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                    <i class="feather-user text-primary"></i>Profile Details
                                </h6>
                                <div class="d-flex align-items-center gap-3 mb-4">
                                    @if($partner->passport_photo)
                                        <img src="{{ asset($partner->passport_photo) }}" alt="Passport" class="rounded-circle border" style="width: 64px; height: 64px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 64px; height: 64px; background: linear-gradient(135deg, #cb202d, #e0555f);">
                                            {{ strtoupper(substr($partner->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $partner->name }}</h6>
                                        <small class="text-muted">{{ $partner->email }}</small>
                                    </div>
                                </div>
                                <ul class="list-unstyled mb-0 d-grid gap-2">
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="feather-gift text-muted fs-13"></i>
                                        <span class="text-muted fs-13">Age:</span>
                                        <span class="fw-semibold text-dark fs-13">{{ $partner->age ?? '—' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="feather-phone text-muted fs-13"></i>
                                        <span class="text-muted fs-13">Phone:</span>
                                        <span class="fw-semibold text-dark fs-13">{{ $partner->phone ?? '—' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="feather-map-pin text-muted fs-13"></i>
                                        <span class="text-muted fs-13">Location:</span>
                                        <span class="fw-semibold text-dark fs-13">
                                            {{ trim(implode(', ', array_filter([$partner->city ?? '', $partner->state?->name ?? '', $partner->country?->name ?? '']))) ?: '—' }}
                                        </span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="feather-truck text-muted fs-13"></i>
                                        <span class="text-muted fs-13">Vehicle:</span>
                                        <span class="fw-semibold text-dark fs-13">{{ ucfirst($partner->vehicle_type ?? '—') }}</span>
                                    </li>
                                    @if($partner->vehicle_number)
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="feather-hash text-muted fs-13"></i>
                                        <span class="text-muted fs-13">Vehicle No:</span>
                                        <span class="fw-semibold text-dark fs-13">{{ $partner->vehicle_number }}</span>
                                    </li>
                                    @endif
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="feather-credit-card text-muted fs-13"></i>
                                        <span class="text-muted fs-13">Aadhar:</span>
                                        <span class="fw-semibold text-dark fs-13">{{ $partner->aadhar_card ? '•••• ' . substr($partner->aadhar_card, -4) : '—' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="feather-file-text text-muted fs-13"></i>
                                        <span class="text-muted fs-13">PAN:</span>
                                        <span class="fw-semibold text-dark fs-13">{{ $partner->pan_card ?? '—' }}</span>
                                    </li>
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="feather-bank text-muted fs-13"></i>
                                        <span class="text-muted fs-13">Bank:</span>
                                        <span class="fw-semibold text-dark fs-13">{{ $partner->bank_name ? $partner->bank_name . ' •• ' . substr($partner->bank_account ?? '', -4) : '—' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Uploaded Documents -->
                        <div class="col-lg-7">
                            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i class="feather-upload text-primary"></i>Uploaded Documents
                            </h6>
                            <div class="row g-3">
                                @php
                                    $docs = [
                                        ['label' => 'Passport Size Photo', 'file' => $partner->passport_photo, 'icon' => 'feather-camera'],
                                        ['label' => 'Aadhar Card (Front)', 'file' => $partner->aadhar_front, 'icon' => 'feather-file-text'],
                                        ['label' => 'Aadhar Card (Back)', 'file' => $partner->aadhar_back, 'icon' => 'feather-file-text'],
                                        ['label' => 'RC Certificate', 'file' => $partner->rc_image, 'icon' => 'feather-file'],
                                    ];
                                @endphp
                                @foreach($docs as $doc)
                                    <div class="col-md-6">
                                        <div class="border rounded-3 overflow-hidden bg-white h-100 d-flex flex-column shadow-sm">
                                            @if($doc['file'])
                                                <div class="position-relative" style="height: 130px; overflow: hidden; background: #f1f5f9;">
                                                    <img src="{{ asset($doc['file']) }}" alt="{{ $doc['label'] }}" class="w-100 h-100" style="object-fit: cover;">
                                                    <span class="position-absolute top-0 end-0 m-2 badge bg-success text-white rounded-circle p-2" title="Uploaded">
                                                        <i class="feather-check fs-12"></i>
                                                    </span>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center justify-content-center" style="height: 130px; background: #f8fafc;">
                                                    <span class="badge bg-light text-muted rounded-pill px-3 py-2"><i class="feather-x me-1"></i>Not Uploaded</span>
                                                </div>
                                            @endif
                                            <div class="p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                <div>
                                                    <div class="fw-semibold text-dark fs-13">{{ $doc['label'] }}</div>
                                                    @if($doc['file'])
                                                        <small class="text-success fw-semibold">Uploaded</small>
                                                    @else
                                                        <small class="text-muted">Pending</small>
                                                    @endif
                                                </div>
                                                @if($doc['file'])
                                                    <a href="{{ asset($doc['file']) }}" target="_blank" class="btn btn-sm btn-light border fw-semibold">View</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <p class="text-danger">Partner data not available. Please contact support.</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            var initialCountry = {{ $partner->country_id ?? 'null' }};
            var initialState = {{ $partner->state_id ?? 'null' }};
            var initialCity = {{ $partner->city_id ?? 'null' }};

            function loadStates(countryId, selectedStateId, callback) {
                var $state = $('#state_id');
                $state.html('<option value="">Select State</option>');
                $('#city_id').html('<option value="">Select City</option>');
                if (!countryId) { if (callback) callback(); return; }
                $.get('{{ route('delivery-partner.geo.states') }}', { country_id: countryId }, function (data) {
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
                $.get('{{ route('delivery-partner.geo.cities') }}', { state_id: stateId }, function (data) {
                    $.each(data, function (i, c) {
                        $city.append('<option value="' + c.id + '">' + c.name + '</option>');
                    });
                    if (selectedCityId) { $city.val(selectedCityId); }
                });
            }

            // Dependent dropdowns
            $('#country_id').on('change', function () {
                loadStates($(this).val(), null);
            });
            $('#state_id').on('change', function () {
                loadCities($(this).val(), null);
            });

            // Pre-load dependent dropdowns for existing record
            if (initialCountry) {
                loadStates(initialCountry, initialState, function () {
                    if (initialState) { loadCities(initialState, initialCity); }
                });
            }

            // Toggle bike-specific fields (RC + vehicle number) and their required state
            function toggleBikeFields() {
                var isBike = $('#vehicle_type').val() === 'bike';
                $('.vehicle-bike-field').toggle(isBike);
                $('#vehicle_number').prop('required', isBike);
                $('#rc_image').prop('required', isBike && !$('#rc_image').closest('.upload-zone').hasClass('uploaded'));
            }
            $('#vehicle_type').on('change', toggleBikeFields);
            toggleBikeFields();

            // Show selected file name inside upload zones
            $('.upload-zone input[type="file"]').on('change', function () {
                var zone = $(this).closest('.upload-zone');
                var label = zone.find('span');
                if (this.files && this.files[0]) {
                    zone.addClass('uploaded');
                    label.text(this.files[0].name);
                } else {
                    zone.removeClass('uploaded');
                }
            });

            // ===== Wizard Navigation =====
            var currentStep = 1;
            var totalSteps = 3;

            function updateStepper() {
                $('.wizard-step-item').each(function () {
                    var nav = parseInt($(this).data('nav'));
                    $(this).removeClass('active done');
                    if (nav < currentStep) { $(this).addClass('done'); }
                    else if (nav === currentStep) { $(this).addClass('active'); }
                });
                $('.wizard-step-line').each(function () {
                    var line = parseInt($(this).data('line'));
                    $(this).toggleClass('done', line < currentStep);
                });
            }

            function showStep(step) {
                $('.wizard-step').hide();
                $('.wizard-step[data-step="' + step + '"]').show();

                $('#btnBack').toggle(step > 1);
                $('#btnNext').toggle(step < totalSteps);
                $('#btnSubmit').toggle(step === totalSteps);

                updateStepper();
                $('html, body').animate({ scrollTop: 0 }, 200);
            }

            function validateStep(step) {
                var $step = $('.wizard-step[data-step="' + step + '"]');
                var valid = true;
                var firstInvalid = null;

                $step.find('[required]').each(function () {
                    var $el = $(this);
                    var val = $el.val();
                    var isFile = $el.attr('type') === 'file';

                    if (isFile) {
                        if (!this.files || !this.files.length) {
                            valid = false;
                            if (!firstInvalid) firstInvalid = this;
                        }
                    } else if (!val || !val.toString().trim()) {
                        valid = false;
                        if (!firstInvalid) firstInvalid = this;
                    }
                });

                if (!valid && firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    $(firstInvalid).focus();
                    toastrError('Please fill in all required fields before continuing.');
                }
                return valid;
            }

            function toastrError(msg) {
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            }

            $('#btnNext').on('click', function () {
                if (validateStep(currentStep)) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            $('#btnBack').on('click', function () {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            showStep(1);
        });
    </script>
@endpush

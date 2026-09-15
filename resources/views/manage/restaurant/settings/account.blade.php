@extends('layouts.restaurant.main')

@section('title', 'Account & Bank Settings - Restaurant Partner')

@push('styles')
<style>
    .settings-card {
        border: 1px solid rgba(0,0,0,0.06);
        border-radius: 16px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.03);
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    .bank-badge {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 1px solid #bfdbfe;
        border-radius: 12px;
    }
</style>
@endpush

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header d-flex align-items-center justify-content-between mb-4">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10 fw-bold">Account & Settlement Settings</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Settings</li>
                <li class="breadcrumb-item">Account & Bank</li>
            </ul>
        </div>
        <div class="page-header-right">
            <a href="{{ route('restaurant.withdrawals.index') }}" class="btn btn-sm btn-outline-primary rounded-3 px-3">
                <i class="feather-arrow-up-circle me-1"></i>View Payouts
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="feather-check-circle fs-18 me-2"></i>
                    <div>{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="feather-alert-triangle fs-18 me-2"></i>
                    <div>{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="feather-alert-circle fs-18 me-2"></i>
                    <div>
                        <strong>Please resolve the following errors:</strong>
                        <ul class="mb-0 mt-1 ps-3 fs-13">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="restaurantAccountSettingsForm" action="{{ route('restaurant.account.settings.update') }}" method="POST">
            @csrf

            <div class="row g-4">
                <!-- Left / Top Column: Profile & Bank Information -->
                <div class="col-lg-8">
                    <!-- 1. Profile Information Card -->
                    <div class="card settings-card bg-white mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="feather-user fs-16"></i>
                                </div>
                                <h6 class="card-title mb-0 fw-bold fs-15 text-dark">Profile Information</h6>
                            </div>
                            <span class="badge bg-light text-muted border">Partner Details</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Full Name / Owner Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" placeholder="e.g. Rahul Sharma" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" placeholder="partner@restaurant.com" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Mobile / Phone Number</label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone ?? $restaurant?->mobile) }}" placeholder="e.g. 9876543210">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Registered Restaurant</label>
                                    <input type="text" class="form-control bg-light text-muted" value="{{ $restaurant?->restaurant_name ?? 'Not Assigned' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Bank & Settlement Information Card -->
                    <div class="card settings-card bg-white mb-4">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-soft-success text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="feather-credit-card fs-16"></i>
                                </div>
                                <h6 class="card-title mb-0 fw-bold fs-15 text-dark">Bank & Payout Settlement Details</h6>
                            </div>
                            <span class="badge bg-soft-success text-success fw-bold">Settlement Account</span>
                        </div>
                        <div class="card-body p-4">
                            <!-- Info Alert Banner -->
                            <div class="p-3 bank-badge mb-4 d-flex align-items-start gap-3">
                                <i class="feather-shield text-primary fs-20 mt-1"></i>
                                <div class="fs-12 text-dark">
                                    <strong>Official Payout Account:</strong> All restaurant withdrawal requests and daily order settlements will be disbursed directly to these bank / UPI details. Please ensure your IFSC code and Account Number are exact.
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Beneficiary / Account Holder Name -->
                                <div class="col-md-6">
                                    <label for="holder_name" class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Account Holder Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="feather-user fs-14 text-muted"></i></span>
                                        <input type="text" name="holder_name" id="holder_name" class="form-control @error('holder_name') is-invalid @enderror" 
                                               value="{{ old('holder_name', $user->holder_name ?? $restaurant?->holder_name ?? $restaurant?->restaurant_name ?? $user->name) }}" 
                                               placeholder="Name as registered with bank">
                                    </div>
                                    @error('holder_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Bank Name -->
                                <div class="col-md-6">
                                    <label for="bank_name" class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Bank Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="feather-home fs-14 text-muted"></i></span>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" 
                                               value="{{ old('bank_name', $user->bank_name ?? $restaurant?->bank_name) }}" 
                                               placeholder="e.g. HDFC Bank / State Bank of India">
                                    </div>
                                    @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Bank Account Number -->
                                <div class="col-md-6">
                                    <label for="bank_account" class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Bank Account Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="feather-hash fs-14 text-muted"></i></span>
                                        <input type="text" name="bank_account" id="bank_account" class="form-control @error('bank_account') is-invalid @enderror" 
                                               value="{{ old('bank_account', $user->bank_account ?? $restaurant?->account_number) }}" 
                                               placeholder="Enter 9-18 digit account number">
                                    </div>
                                    @error('bank_account') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- IFSC Code -->
                                <div class="col-md-6">
                                    <label for="ifsc_code" class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Bank IFSC Code</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="feather-shield fs-14 text-muted"></i></span>
                                        <input type="text" name="ifsc_code" id="ifsc_code" class="form-control text-uppercase font-monospace @error('ifsc_code') is-invalid @enderror" 
                                               value="{{ old('ifsc_code', $user->ifsc_code ?? $restaurant?->ifsc_code) }}" 
                                               placeholder="e.g. HDFC0001234">
                                    </div>
                                    @error('ifsc_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Branch Name (Optional) -->
                                <div class="col-md-6">
                                    <label for="branch_name" class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Branch Name <small class="text-muted">(Optional)</small></label>
                                    <input type="text" name="branch_name" id="branch_name" class="form-control @error('branch_name') is-invalid @enderror" 
                                           value="{{ old('branch_name', $user->branch_name ?? $restaurant?->branch_name) }}" 
                                           placeholder="e.g. Connaught Place Branch">
                                    @error('branch_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- UPI ID / VPA -->
                                <div class="col-md-6">
                                    <label for="upi_id" class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">UPI ID / VPA <small class="text-muted">(Instant Transfer)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="feather-smartphone fs-14 text-muted"></i></span>
                                        <input type="text" name="upi_id" id="upi_id" class="form-control @error('upi_id') is-invalid @enderror" 
                                               value="{{ old('upi_id', $user->upi_id ?? $restaurant?->upi_id) }}" 
                                               placeholder="e.g. yourname@okhdfcbank / 9876543210@paytm">
                                    </div>
                                    @error('upi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mb-5">
                        <a href="{{ route('restaurant.dashboard') }}" class="btn btn-light border px-4 py-2 fw-semibold">
                            Cancel
                        </a>
                        <button type="submit" id="btnSaveAccountSettings" class="btn btn-primary px-5 py-2 fw-semibold shadow-sm position-relative">
                            <span class="btn-text d-inline-flex align-items-center">
                                <i class="feather-save me-2"></i>Save Account & Bank Settings
                            </span>
                            <span class="btn-loader d-none align-items-center">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Saving Changes...
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Right Column: Quick Guidance & KYC Summary -->
                <div class="col-lg-4">
                    <!-- Guidelines Card -->
                    <div class="card settings-card bg-white p-4 mb-4">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="feather-info text-primary"></i>Bank Details Guidelines
                        </h6>
                        <ul class="list-unstyled mb-0 fs-12 text-muted d-flex flex-column gap-2">
                            <li class="d-flex align-items-start gap-2">
                                <i class="feather-check text-success mt-1"></i>
                                <span>Ensure the <strong>Account Holder Name</strong> matches your official bank records.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="feather-check text-success mt-1"></i>
                                <span>Provide a valid 11-character <strong>IFSC code</strong> (e.g. SBIN0001234, HDFC0005678).</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="feather-check text-success mt-1"></i>
                                <span>Adding a <strong>UPI ID</strong> enables faster automated settlements.</span>
                            </li>
                            <li class="d-flex align-items-start gap-2">
                                <i class="feather-check text-success mt-1"></i>
                                <span>Your details are encrypted and securely stored for verified payouts only.</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Wallet Payout Shortcut -->
                    <div class="card settings-card bg-soft-primary border-primary p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold text-dark mb-0">Ready to Withdraw?</h6>
                            <i class="feather-dollar-sign text-primary fs-20"></i>
                        </div>
                        <p class="fs-12 text-muted mb-3">Once you have updated and saved your bank details, you can request earnings payouts directly to your bank account anytime.</p>
                        <a href="{{ route('restaurant.withdrawals.create') }}" class="btn btn-primary w-100 fw-semibold rounded-3">
                            <i class="feather-arrow-up-circle me-1"></i>Request Payout Now
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- [ Main Content ] end -->
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('restaurantAccountSettingsForm');
        const btn = document.getElementById('btnSaveAccountSettings');

        if (form && btn) {
            form.addEventListener('submit', function() {
                if (!form.checkValidity()) {
                    return;
                }
                btn.disabled = true;
                const btnText = btn.querySelector('.btn-text');
                const btnLoader = btn.querySelector('.btn-loader');
                if (btnText && btnLoader) {
                    btnText.classList.add('d-none');
                    btnText.classList.remove('d-inline-flex');
                    btnLoader.classList.remove('d-none');
                    btnLoader.classList.add('d-inline-flex');
                }
            });
        }
    });
</script>
@endpush
@endsection
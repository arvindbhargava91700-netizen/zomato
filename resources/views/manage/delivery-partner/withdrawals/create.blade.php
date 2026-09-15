@extends('layouts.delivery-partner.main')

@section('title', 'Request Withdrawal - Delivery Partner')

@push('styles')
<style>
    .quick-chip {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }
    .quick-chip:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }
    .method-card-label {
        cursor: pointer;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        transition: all 0.2s ease;
        background: #ffffff;
    }
    .btn-check:checked + .method-card-label {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.12);
    }
    .summary-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 18px;
    }
</style>
@endpush

@section('content')
<!-- [ page-header ] start -->
<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10 fw-bold">Request Withdrawal</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('delivery-partner.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('delivery-partner.withdrawals.index') }}">Withdrawals</a></li>
            <li class="breadcrumb-item">New Request</li>
        </ul>
    </div>
    <div class="page-header-right">
        <a href="{{ route('delivery-partner.withdrawals.index') }}" class="btn btn-sm btn-light border shadow-sm px-3">
            <i class="feather-arrow-left me-1"></i>Back to Withdrawals
        </a>
    </div>
</div>
<!-- [ page-header ] end -->

@php
    $currencySym = $currencySymbol ?? ($setting ? $setting->currencySymbol() : '₹');
@endphp

<div class="main-content">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
            <i class="feather-alert-triangle me-2 fs-16"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 justify-content-center">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h5 class="card-title mb-0 fw-bold fs-16 text-dark d-flex align-items-center gap-2">
                        <i class="feather-arrow-up-circle text-primary"></i>Withdraw Funds to Bank or UPI
                    </h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <!-- Balance Hero Banner -->
                    <div class="p-4 rounded-4 bg-soft-primary text-primary mb-4 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fs-12 text-uppercase fw-bold d-block text-muted">Available Wallet Balance</span>
                            <h2 class="display-6 fw-bold text-dark mb-0">{{ $currencySym }}{{ number_format($wallet->balance, 2) }}</h2>
                        </div>
                        <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="feather-credit-card fs-24 text-primary"></i>
                        </div>
                    </div>

                    <form id="dpWithdrawalForm" method="POST" action="{{ route('delivery-partner.withdrawals.store') }}">
                        @csrf

                        <!-- 1. Amount Section -->
                        <div class="mb-4">
                            <label class="form-label fs-13 fw-bold text-dark text-uppercase mb-2">
                                1. Enter Withdrawal Amount <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 fw-bold fs-18 text-muted">{{ $currencySym }}</span>
                                <input type="number" step="0.01" min="10" max="{{ max(0, (float)$wallet->balance) }}" 
                                       id="withdrawAmount" name="amount" value="{{ old('amount') }}" 
                                       class="form-control form-control-lg fw-bold fs-20 border-start-0" 
                                       placeholder="0.00" oninput="updateSummary(this.value)" required>
                            </div>
                            @error('amount')
                                <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                            @enderror

                            <!-- Fast Chips -->
                            <div class="d-flex gap-2 mt-3 flex-wrap align-items-center">
                                <span class="text-muted fs-12 fw-semibold me-1">Quick Select:</span>
                                <span class="quick-chip" onclick="setAmount(100)">₹100</span>
                                <span class="quick-chip" onclick="setAmount(500)">₹500</span>
                                <span class="quick-chip" onclick="setAmount(1000)">₹1,000</span>
                                <span class="quick-chip" onclick="setAmount(2000)">₹2,000</span>
                                @if($wallet->balance > 0)
                                    <span class="quick-chip" style="border-color: #2563eb; color: #2563eb;" onclick="setAmount({{ (float)$wallet->balance }})">All Available ({{ $currencySym }}{{ number_format($wallet->balance, 2) }})</span>
                                @endif
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-25">

                        <!-- 2. Transfer Channel -->
                        <div class="mb-4">
                            <label class="form-label fs-13 fw-bold text-dark text-uppercase mb-2">
                                2. Select Payout Method <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <input type="radio" class="btn-check" name="payout_method" id="method_bank" value="bank_transfer" 
                                           {{ old('payout_method', 'bank_transfer') === 'bank_transfer' ? 'checked' : '' }} onchange="toggleMethod('bank')">
                                    <label class="method-card-label w-100 d-flex align-items-center gap-3" for="method_bank">
                                        <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                            <i class="feather-home fs-18"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 fs-14">Bank Account Transfer</h6>
                                            <small class="text-muted fs-11">NEFT / RTGS / IMPS Direct</small>
                                        </div>
                                    </label>
                                </div>

                                <div class="col-sm-6">
                                    <input type="radio" class="btn-check" name="payout_method" id="method_upi" value="upi" 
                                           {{ old('payout_method') === 'upi' ? 'checked' : '' }} onchange="toggleMethod('upi')">
                                    <label class="method-card-label w-100 d-flex align-items-center gap-3" for="method_upi">
                                        <div class="rounded-circle bg-soft-info text-info d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                            <i class="feather-smartphone fs-18"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 fs-14">Instant UPI Transfer</h6>
                                            <small class="text-muted fs-11">VPA / GPay / PhonePe / Paytm</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Account Details Section -->
                        <div id="bankSection" style="{{ old('payout_method', 'bank_transfer') === 'bank_transfer' ? 'display:block;' : 'display:none;' }}">
                            <label class="form-label fs-13 fw-bold text-dark text-uppercase mb-2">
                                3. Bank Account Information
                            </label>
                            <div class="p-3 p-md-4 rounded-4 bg-light border mb-4">
                                <div class="mb-3">
                                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Account Holder / Beneficiary Name</label>
                                    <input type="text" name="holder_name" value="{{ old('holder_name', $partner->name) }}" class="form-control" placeholder="Full name as per bank records">
                                </div>
                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Bank Name</label>
                                        <input type="text" name="bank_name" value="{{ old('bank_name', $partner->bank_name) }}" class="form-control" placeholder="e.g. State Bank of India">
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">IFSC Code</label>
                                        <input type="text" name="ifsc_code" value="{{ old('ifsc_code', $partner->ifsc_code) }}" class="form-control text-uppercase" placeholder="SBIN0001234">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Bank Account Number</label>
                                    <input type="text" name="account_number" value="{{ old('account_number', $partner->bank_account) }}" class="form-control" placeholder="Enter bank account number">
                                </div>
                            </div>
                        </div>

                        <div id="upiSection" style="{{ old('payout_method') === 'upi' ? 'display:block;' : 'display:none;' }}">
                            <label class="form-label fs-13 fw-bold text-dark text-uppercase mb-2">
                                3. UPI Virtual Payment Address
                            </label>
                            <div class="p-3 p-md-4 rounded-4 bg-light border mb-4">
                                <div>
                                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">UPI ID / VPA <span class="text-danger">*</span></label>
                                    <input type="text" name="upi_id" value="{{ old('upi_id') }}" class="form-control" placeholder="e.g. 9876543210@paytm / name@oksbi">
                                    <small class="text-muted fs-11 mt-1 d-block">Make sure your UPI ID is active and linked to your bank account.</small>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Notes -->
                        <div class="mb-4">
                            <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Notes / Remarks (Optional)</label>
                            <input type="text" name="notes" value="{{ old('notes') }}" class="form-control" placeholder="e.g. Weekly courier payout request">
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                            <a href="{{ route('delivery-partner.withdrawals.index') }}" class="btn btn-light border px-4 py-2 fw-semibold">
                                Cancel
                            </a>
                            <button type="submit" id="btnSubmitWithdrawal" class="btn btn-primary px-5 py-2 fw-semibold shadow-sm position-relative" {{ $wallet->balance <= 0 ? 'disabled' : '' }}>
                                <span class="btn-text d-inline-flex align-items-center">
                                    <i class="feather-send me-2"></i>Confirm & Submit Request
                                </span>
                                <span class="btn-loader d-none align-items-center">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Submitting Request...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payout Summary & Guidelines Column -->
        <div class="col-lg-4">
            <!-- Live Summary Box -->
            <div class="card border-0 shadow-sm rounded-4 summary-box p-4 mb-4">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="feather-file-text text-primary"></i>Payout Calculation
                </h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted fs-13">Requested Amount:</span>
                    <span class="fw-bold text-dark fs-14" id="summaryReqAmount">{{ $currencySym }}0.00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted fs-13">Processing Fee:</span>
                    <span class="fw-bold text-success fs-14">Free ({{ $currencySym }}0.00)</span>
                </div>
                <div class="d-flex justify-content-between pt-2 border-top mb-3">
                    <span class="fw-bold text-dark fs-14">Net Payout:</span>
                    <span class="fw-bold text-primary fs-18" id="summaryNetAmount">{{ $currencySym }}0.00</span>
                </div>
                <div class="fs-11 text-muted">
                    <i class="feather-info me-1"></i>Funds are held securely and deducted immediately upon submission. Admin processes payouts promptly.
                </div>
            </div>

            <!-- Guidelines Card -->
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="feather-shield text-success"></i>Payout Guidelines
                </h6>
                <ul class="list-unstyled mb-0 fs-12 text-muted d-flex flex-column gap-2">
                    <li class="d-flex align-items-start gap-2">
                        <i class="feather-check text-success mt-1"></i>
                        <span>Minimum withdrawal limit is <strong>{{ $currencySym }}10.00</strong>.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="feather-check text-success mt-1"></i>
                        <span>Double-check your Bank Account Number and IFSC Code before submitting.</span>
                    </li>
                    <li class="d-flex align-items-start gap-2">
                        <i class="feather-check text-success mt-1"></i>
                        <span>If a request is rejected by Admin, the amount is <strong>instantly refunded</strong> back to your wallet.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    function setAmount(val) {
        document.getElementById('withdrawAmount').value = val;
        updateSummary(val);
    }

    function updateSummary(val) {
        const num = parseFloat(val) || 0;
        const formatted = '{{ $currencySym }}' + num.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('summaryReqAmount').innerText = formatted;
        document.getElementById('summaryNetAmount').innerText = formatted;
    }

    function toggleMethod(type) {
        const bankSec = document.getElementById('bankSection');
        const upiSec = document.getElementById('upiSection');
        if (type === 'upi') {
            bankSec.style.display = 'none';
            upiSec.style.display = 'block';
        } else {
            bankSec.style.display = 'block';
            upiSec.style.display = 'none';
        }
    }

    // Form Submission with Loader in Button
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('dpWithdrawalForm');
        const btn = document.getElementById('btnSubmitWithdrawal');
        
        if (form && btn) {
            form.addEventListener('submit', function(e) {
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
@endsection

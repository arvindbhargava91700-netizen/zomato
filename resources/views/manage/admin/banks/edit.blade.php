@extends('layouts.admin.main')

@section('title', getPageTitle('Account Setting'))

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Account Setting (Bank Details)</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Account Setting</li>
            </ul>
        </div>
    </div>
    
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.account-setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">My Bank Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="bank_name" class="form-label fw-semibold">Bank Name</label>
                            <input type="text" name="bank_name" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name', $admin->bank_name ?? '') }}" placeholder="e.g. State Bank of India">
                            @error('bank_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="account_number" class="form-label fw-semibold">Account Number</label>
                            <input type="text" name="account_number" id="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number', $admin->account_number ?? '') }}" placeholder="Enter Account Number">
                            @error('account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ifsc_code" class="form-label fw-semibold">IFSC Code</label>
                            <input type="text" name="ifsc_code" id="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror" value="{{ old('ifsc_code', $admin->ifsc_code ?? '') }}" placeholder="Enter IFSC Code">
                            @error('ifsc_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="branch_name" class="form-label fw-semibold">Branch Name</label>
                            <input type="text" name="branch_name" id="branch_name" class="form-control @error('branch_name') is-invalid @enderror" value="{{ old('branch_name', $admin->branch_name ?? '') }}" placeholder="Enter Branch Name">
                            @error('branch_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="account_type" class="form-label fw-semibold">Account Type</label>
                            <select name="account_type" id="account_type" class="form-control @error('account_type') is-invalid @enderror">
                                <option value="">Select Account Type</option>
                                <option value="Savings" {{ old('account_type', $admin->account_type ?? '') == 'Savings' ? 'selected' : '' }}>Savings</option>
                                <option value="Current" {{ old('account_type', $admin->account_type ?? '') == 'Current' ? 'selected' : '' }}>Current</option>
                            </select>
                            @error('account_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="upi_id" class="form-label fw-semibold">UPI ID</label>
                            <input type="text" name="upi_id" id="upi_id" class="form-control @error('upi_id') is-invalid @enderror" value="{{ old('upi_id', $admin->upi_id ?? '') }}" placeholder="e.g. brand@upi">
                            @error('upi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="qr_code_image" class="form-label fw-semibold">Payment QR Code Image <small class="text-muted">(Max 2MB)</small></label>
                            <input type="file" name="qr_code_image" id="qr_code_image" class="form-control @error('qr_code_image') is-invalid @enderror" accept="image/*">
                            @if(!empty($admin->qr_code_image))
                                <img src="{{ asset($admin->qr_code_image) }}" alt="QR Code" class="mt-2 rounded border" style="max-height: 55px;">
                            @endif
                            @error('qr_code_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-5">
                <button type="submit" class="btn btn-primary">
                    <i class="feather-save me-2"></i> Update Bank Details
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

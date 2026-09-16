@extends('layouts.admin.main')

@section('title', getPageTitle('Company Setting'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Company Setting</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">CMS Management</li>
                <li class="breadcrumb-item">Company Setting</li>
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

        <form action="{{ route('admin.cms.company.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Company Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label fw-semibold">Company Name</label>
                            <input type="text" name="company_name" id="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $setting->company_name) }}" placeholder="e.g. Food Management Inc.">
                            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="website" class="form-label fw-semibold">Website</label>
                            <input type="text" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $setting->website) }}" placeholder="e.g. https://www.yourwebsite.com">
                            @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="logo_lg" class="form-label fw-semibold">Logo (Large) <small class="text-muted">(Max 2MB)</small></label>
                            <input type="file" name="logo_lg" id="logo_lg" class="form-control @error('logo_lg') is-invalid @enderror" accept="image/*">
                            @if($setting->logo_lg)
                                <img src="{{ asset($setting->logo_lg) }}" alt="Logo Large" class="mt-2 rounded border" style="max-height: 55px;">
                            @endif
                            @error('logo_lg') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="logo_sm" class="form-label fw-semibold">Logo (Small) <small class="text-muted">(Max 2MB)</small></label>
                            <input type="file" name="logo_sm" id="logo_sm" class="form-control @error('logo_sm') is-invalid @enderror" accept="image/*">
                            @if($setting->logo_sm)
                                <img src="{{ asset($setting->logo_sm) }}" alt="Logo Small" class="mt-2 rounded border" style="max-height: 40px;">
                            @endif
                            @error('logo_sm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="favicon" class="form-label fw-semibold">Favicon <small class="text-muted">(Max 1MB)</small></label>
                            <input type="file" name="favicon" id="favicon" class="form-control @error('favicon') is-invalid @enderror" accept="image/*">
                            @if($setting->favicon)
                                <img src="{{ asset($setting->favicon) }}" alt="Favicon" class="mt-2 rounded border" style="max-height: 40px;">
                            @endif
                            @error('favicon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Contact Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $setting->email) }}" placeholder="e.g. info@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $setting->phone) }}" placeholder="e.g. +91 9876543210">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold">Address</label>
                            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $setting->address) }}" placeholder="e.g. 123, Main Street, City, State, PIN">
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mb-5">
                <button type="submit" class="btn btn-primary">
                    <i class="feather-save me-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

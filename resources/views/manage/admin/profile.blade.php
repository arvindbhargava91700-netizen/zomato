@extends('layouts.admin.main')

@section('title', 'Admin Profile - Zomato Clone')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Admin Profile</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Profile</li>
            </ul>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="row">
            <div class="col-lg-8 col-xl-7">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 text-dark">Update Profile Details</h5>
                    </div>
                    <div class="card-body p-4">

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="mobile" class="form-label fw-semibold">Mobile Number</label>
                                    <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $admin->mobile) }}" placeholder="e.g. +91 9876543210">
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="role" class="form-label fw-semibold">Role</label>
                                    <input type="text" id="role" class="form-control text-capitalize" value="{{ str_replace('_', ' ', $admin->role) }}" disabled readonly>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h6 class="fw-semibold text-secondary mb-3">Change Password <small class="text-muted fw-normal">(Leave blank to keep current password)</small></h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-semibold">New Password</label>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold" style="background-color: #cb202d; border: none;">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

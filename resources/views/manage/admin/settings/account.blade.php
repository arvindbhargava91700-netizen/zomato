@extends('layouts.admin.main')

@section('title', 'Account Settings - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Account Settings</h5>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.account.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Login Security</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter current password" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="new_password" class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                            <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Enter new password" required>
                            @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Profile Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth('admin')->user()->name) }}" placeholder="e.g. John Doe">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth('admin')->user()->email) }}" placeholder="john@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Security</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">For your security, you can change your password. Make sure to remember your new password.</p>
                    <div class="d-flex justify-content-end gap-2 mb-4">
                        <button type="submit" class="btn btn-primary px-5 fw-semibold">Update Account</button>
                        <button type="button" class="btn btn-light px-5 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
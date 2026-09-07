@extends('layouts.restaurant.main')

@section('title', 'My Profile - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">My Profile</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Profile</li>
            </ul>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Alerts -->
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

        <div class="row g-4">
            <!-- Profile Info Form Column -->
            <div class="col-lg-7">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">Update Personal Profile</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('restaurant.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Profile Image Preview & Input -->
                            <div class="d-flex align-items-center gap-3 mb-4">
                                @if($owner && $owner->profile_image)
                                    <img src="{{ asset($owner->profile_image) }}" alt="Profile Image" class="rounded-circle border" style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 80px; height: 80px; background-color: #cb202d;">
                                        {{ strtoupper(substr($owner->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <label for="profile_image" class="form-label fw-semibold mb-1">Profile Photo</label>
                                    <input type="file" name="profile_image" id="profile_image" class="form-control form-control-sm" accept="image/*">
                                    <span class="fs-12 text-muted">JPG, PNG or WEBP (Max 2MB)</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $owner->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input type="email" id="email" class="form-control bg-light" value="{{ $owner->email }}" disabled readonly>
                                <span class="fs-12 text-muted">Email address cannot be changed. Please contact administrator.</span>
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label fw-semibold">Mobile Number</label>
                                <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $owner->mobile) }}" placeholder="+91 9876543210">
                                @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold" style="background-color: #cb202d; border: none;">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Password Change Form Column -->
            <div class="col-lg-5">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark">Change Password</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('restaurant.password.update') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-semibold">Current Password <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required placeholder="••••••••">
                                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">New Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="••••••••">
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-secondary px-4 py-2 fw-semibold">Change Password</button>
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

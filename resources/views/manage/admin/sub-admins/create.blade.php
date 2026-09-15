@extends('layouts.admin.main')

@section('title', 'Add Sub Admin - Admin Dashboard')

@push('styles')
<style>
    :root {
        --za-red: #cb202d;
        --za-red-dark: #a51523;
        --za-soft: rgb(203 32 45 / 0.08);
    }

    /* ---------- Hero banner ---------- */
    .role-hero {
        background: linear-gradient(135deg, #8b0f1a 0%, #cb202d 55%, #ef5a50 100%);
        border-radius: 18px;
        padding: 26px 30px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(203, 32, 45, 0.25);
    }
    .role-hero::before {
        content: '';
        position: absolute;
        right: -60px;
        top: -60px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }
    .role-hero::after {
        content: '';
        position: absolute;
        right: 60px;
        bottom: -90px;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }
    .role-hero-icon {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    /* ---------- Cards ---------- */
    .role-card {
        border: 1px solid #eceff3;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
    }
    .input-icon-wrap {
        position: relative;
    }
    .input-icon-wrap > i,
        .input-icon-wrap > svg {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        pointer-events: none;
    }
    .input-icon-wrap .form-control,
    .input-icon-wrap .form-select {
        padding-left: 40px;
    }

    /* ---------- Form focus ---------- */
    .role-form .form-control:focus,
    .role-form .form-select:focus {
        border-color: var(--za-red);
        box-shadow: 0 0 0 0.2rem var(--za-soft);
    }

    /* ---------- Submit loading ---------- */
    .spinner {
        animation: nxl-spin 0.9s linear infinite;
        display: inline-block;
    }
    @keyframes nxl-spin {
        to { transform: rotate(360deg); }
    }
    .btn.is-submitting {
        opacity: 0.85;
        pointer-events: none;
        cursor: wait;
    }
</style>
@endpush

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Add Sub Admin</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.sub-admins.index') }}">Sub Admins</a></li>
                <li class="breadcrumb-item">Add</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.sub-admins.index') }}" class="btn btn-light border fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <div class="main-content">
        <!-- Hero Banner -->
        <div class="role-hero mb-4 d-flex align-items-center gap-3">
            <div class="role-hero-icon"><i class="feather-user-plus"></i></div>
            <div>
                <h4 class="mb-1 fw-bold text-white">Create Sub Admin</h4>
                <p class="mb-0 opacity-75 fs-13">Add a new sub admin to assist with admin panel operations.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="feather-alert-triangle me-1"></i> Please fix the following errors:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('admin.sub-admins.store') }}" method="POST" class="role-form">
            @csrf

            <div class="card role-card stretch stretch-full mb-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="feather-user"></i>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Rohan Sharma">
                            </div>
                            @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold text-dark">Email Address <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="feather-mail"></i>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="rohan@yourcompany.com">
                            </div>
                            @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="mobile" class="form-label fw-semibold text-dark">Mobile Number</label>
                            <div class="input-icon-wrap">
                                <i class="feather-phone"></i>
                                <input type="text" name="mobile" id="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" placeholder="+91 98765 43210">
                            </div>
                            @error('mobile') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold text-dark">Password <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="feather-lock"></i>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Min 6 characters">
                            </div>
                            @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold text-dark">Confirm Password <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="feather-check-circle"></i>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Re-enter password">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="role_id" class="form-label fw-semibold text-dark">Role <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="feather-user-check"></i>
                                <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror">
                                    <option value="">— Select Role —</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->slug }}" {{ old('role_id') == $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted">Roles are managed in Roles &amp; Permissions.</small>
                            @error('role_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold text-dark">Status <span class="text-danger">*</span></label>
                            <div class="input-icon-wrap">
                                <i class="feather-toggle-right"></i>
                                <select name="status" id="status" class="form-select">
                                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky action bar -->
            <div class="sticky-bottom bg-white border-top p-3 d-flex align-items-center justify-content-between rounded rounded-top-0 shadow-sm">
                <div class="text-muted fs-13">
                    <i class="feather-info me-1"></i> Fields marked <span class="text-danger">*</span> are required.
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.sub-admins.index') }}" class="btn btn-light border fw-semibold px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary px-5 fw-semibold" style="background-color: #cb202d; border: none;">
                        <i class="feather-user-plus me-1"></i> Create Sub Admin
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        document.querySelectorAll('form.role-form').forEach(function (form) {
            form.addEventListener('submit', function () {
                const btn = form.querySelector('button[type="submit"]');
                if (btn && !btn.disabled) {
                    btn.disabled = true;
                    btn.classList.add('is-submitting');
                    btn.innerHTML = '<i class="feather-loader spinner me-1"></i> Creating...';
                }
            });
        });
    })();
</script>
@endpush
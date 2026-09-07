@extends('layouts.admin.main')

@section('title', 'Delivery Partner KYC - Admin Dashboard')

@php
    $kycBadge = function ($status) {
        return match ($status) {
            'pending' => '<span class="badge bg-soft-warning text-warning fw-semibold fs-12">Pending Review</span>',
            'approved' => '<span class="badge bg-soft-success text-success fw-semibold fs-12">Approved</span>',
            'rejected' => '<span class="badge bg-soft-danger text-danger fw-semibold fs-12">Rejected</span>',
            default => '<span class="badge bg-soft-secondary text-secondary fw-semibold fs-12">Not Submitted</span>',
        };
    };
    $statusBadge = function ($status) {
        return $status === 'active'
            ? '<span class="badge bg-soft-success text-success fw-semibold fs-12">Active</span>'
            : '<span class="badge bg-soft-danger text-danger fw-semibold fs-12">Inactive</span>';
    };
@endphp

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Delivery Partner KYC Review</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.delivery-partners.index') }}">Delivery Partners</a></li>
                <li class="breadcrumb-item">{{ $partner->name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <a href="{{ route('admin.delivery-partners.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left: Personal & Bank Details -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold">Partner Details</h5>
                        {!! $kycBadge($partner->kyc_status) !!}
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if($partner->profile_image)
                                <img src="{{ asset($partner->profile_image) }}" alt="profile" class="rounded-circle" style="width:64px;height:64px;object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-danger fw-bold" style="width:64px;height:64px;font-size:24px;">
                                    {{ strtoupper(substr($partner->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold fs-5">{{ $partner->name }}</div>
                                <div class="text-muted fs-12">{{ $partner->email }}</div>
                            </div>
                        </div>

                        <table class="table table-sm table-borderless mb-0">
                            <tr><th class="ps-0 text-muted" style="width:45%">Phone</th><td>{{ $partner->phone ?? '—' }}</td></tr>
                            <tr><th class="ps-0 text-muted">Age</th><td>{{ $partner->age ?? '—' }}</td></tr>
                            <tr><th class="ps-0 text-muted">City</th><td>{{ $partner->city ?? '—' }}</td></tr>
                            <tr><th class="ps-0 text-muted">Vehicle Type</th><td>{{ ucfirst($partner->vehicle_type ?? '—') }}</td></tr>
                            <tr><th class="ps-0 text-muted">Vehicle Number</th><td>{{ $partner->vehicle_number ?? '—' }}</td></tr>
                            <tr><th class="ps-0 text-muted">Account Status</th><td>{!! $statusBadge($partner->status) !!}</td></tr>
                            <tr><th class="ps-0 text-muted">Aadhar Number</th><td>{{ $partner->aadhar_card ?? '—' }}</td></tr>
                            <tr><th class="ps-0 text-muted">PAN Number</th><td>{{ $partner->pan_card ?? '—' }}</td></tr>
                            <tr><th class="ps-0 text-muted">Bank Name</th><td>{{ $partner->bank_name ?? '—' }}</td></tr>
                            <tr><th class="ps-0 text-muted">Bank Account</th><td>{{ $partner->bank_account ?? '—' }}</td></tr>
                            <tr><th class="ps-0 text-muted">IFSC Code</th><td>{{ $partner->ifsc_code ?? '—' }}</td></tr>
                            @if($partner->kyc_reviewed_at)
                                <tr><th class="ps-0 text-muted">Reviewed At</th><td>{{ $partner->kyc_reviewed_at->format('d M Y h:i A') }}</td></tr>
                            @endif
                        </table>

                        @if($partner->kyc_status === 'rejected' && $partner->kyc_rejected_reason)
                            <div class="alert alert-danger mt-3 mb-0">
                                <strong>Rejection Reason:</strong><br>{{ $partner->kyc_rejected_reason }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bike / Vehicle Details -->
                @if($partner->vehicle_type || $partner->vehicle_number || $partner->rc_image)
                    <div class="card border-0 shadow-sm rounded-3 mt-4">
                        <div class="card-header border-bottom py-3">
                            <h5 class="card-title mb-0 fw-bold"><i class="feather-bike me-2"></i>Bike / Vehicle Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless mb-3">
                                <tr><th class="ps-0 text-muted" style="width:45%">Vehicle Type</th><td>{{ ucfirst($partner->vehicle_type ?? '—') }}</td></tr>
                                <tr><th class="ps-0 text-muted">Vehicle Number</th><td>{{ $partner->vehicle_number ?? '—' }}</td></tr>
                            </table>

                            @if($partner->rc_image)
                                <div class="text-muted fs-12 mb-2">Registration Certificate (RC)</div>
                                <a href="{{ asset($partner->rc_image) }}" target="_blank">
                                    <img src="{{ asset($partner->rc_image) }}" alt="Vehicle RC" class="img-fluid rounded-2" style="max-height:240px;width:100%;object-fit:contain;background:#f8f9fa;">
                                </a>
                            @else
                                <div class="text-center text-muted py-3">RC image not uploaded</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: KYC Documents -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">KYC Documents</h5>
                    </div>
                    <div class="card-body">
                        @if($partner->kyc_status === 'not_submitted')
                            <div class="alert alert-warning mb-0">This partner has not submitted any KYC documents yet.</div>
                        @else
                            <div class="row g-3">
                                @foreach([
                                    'aadhar_front' => 'Aadhar Card (Front)',
                                    'aadhar_back' => 'Aadhar Card (Back)',
                                    'passport_photo' => 'Passport Size Photo',
                                    'rc_image' => 'Vehicle RC',
                                ] as $field => $label)
                                    <div class="col-md-6">
                                        <div class="border rounded-3 p-2 h-100">
                                            <div class="text-muted fs-12 mb-2">{{ $label }}</div>
                                            @if($partner->$field)
                                                <a href="{{ asset($partner->$field) }}" target="_blank">
                                                    <img src="{{ asset($partner->$field) }}" alt="{{ $label }}" class="img-fluid rounded-2" style="max-height:200px;width:100%;object-fit:contain;background:#f8f9fa;">
                                                </a>
                                            @else
                                                <div class="text-center text-muted py-4">Not uploaded</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Card -->
                @if($partner->kyc_status === 'pending')
                    <div class="card border-0 shadow-sm rounded-3 mt-4">
                        <div class="card-header border-bottom py-3">
                            <h5 class="card-title mb-0 fw-bold">KYC Decision &amp; Remarks</h5>
                        </div>
                        <div class="card-body">
                            <!-- Approve with remark -->
                            <form action="{{ route('admin.delivery-partners.approve', $partner->id) }}" method="POST" class="mb-4">
                                @csrf
                                <label for="kyc_remark" class="form-label">Approval Remark <span class="text-muted">(optional)</span></label>
                                <textarea name="kyc_remark" id="kyc_remark" class="form-control mb-2" rows="3" placeholder="Add a note about this approval...">{{ old('kyc_remark') }}</textarea>
                                <button type="submit" class="btn btn-success fw-semibold">
                                    <i class="feather-check-circle me-1"></i> Approve & Activate
                                </button>
                            </form>

                            <hr>

                            <!-- Reject with remark -->
                            <form action="{{ route('admin.delivery-partners.reject', $partner->id) }}" method="POST">
                                @csrf
                                <label for="kyc_rejected_reason" class="form-label">Rejection Remark <span class="text-danger">*</span></label>
                                <textarea name="kyc_rejected_reason" id="kyc_rejected_reason" class="form-control mb-2 @error('kyc_rejected_reason') is-invalid @enderror" rows="3" required placeholder="Explain why the KYC is being rejected...">{{ old('kyc_rejected_reason') }}</textarea>
                                @error('kyc_rejected_reason')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <button type="submit" class="btn btn-danger fw-semibold">
                                    <i class="feather-x-circle me-1"></i> Reject KYC
                                </button>
                            </form>
                        </div>
                    </div>
                @elseif($partner->kyc_status === 'approved')
                    <div class="alert alert-success mt-4 mb-0">
                        <i class="feather-check-circle me-1"></i> KYC approved. This partner's account is active and can accept deliveries.
                        @if($partner->kyc_remark)
                            <div class="mt-2"><strong>Remark:</strong> {{ $partner->kyc_remark }}</div>
                        @endif
                    </div>
                @elseif($partner->kyc_status === 'rejected')
                    <div class="alert alert-danger mt-4 mb-0">
                        <i class="feather-x-circle me-1"></i> KYC was rejected. The partner account remains inactive.
                        @if($partner->kyc_rejected_reason)
                            <div class="mt-2"><strong>Remark:</strong> {{ $partner->kyc_rejected_reason }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

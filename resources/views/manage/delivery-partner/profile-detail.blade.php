@extends('layouts.delivery-partner.main')

@section('title', 'Profile Details - Delivery Partner Dashboard')

@push('styles')
<style>
    .info-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 10px;
    }
    .info-row i {
        width: 20px;
        text-align: center;
        flex-shrink: 0;
    }
    .info-label {
        min-width: 110px;
        flex-shrink: 0;
        color: #64748b;
    }
    .info-value {
        flex: 1;
        min-width: 0;
        word-break: break-word;
    }
    .doc-card {
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    .doc-card:hover {
        box-shadow: 0 6px 16px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .doc-thumb {
        height: 150px;
        overflow: hidden;
        background: #f1f5f9;
        position: relative;
    }
    .doc-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }
</style>
@endpush

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Profile Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('delivery-partner.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Profile Details</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('delivery-partner.kyc') }}" class="btn text-white fw-semibold px-3 py-2 shadow-sm" style="background: linear-gradient(90deg, #cb202d 0%, #e0555f 100%); border: none; border-radius: 8px;">
                <i class="feather-edit me-1"></i> Edit KYC Details
            </a>
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

        <!-- Identity Card -->
        <div class="card stretch mb-4 border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0 position-relative" style="background: linear-gradient(135deg, #2d1b3a 0%, #4a2545 45%, #cb202d 100%);">
                <div class="p-4 p-md-5 position-relative" style="z-index: 2;">
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <div class="position-relative">
                            @if($partner->passport_photo)
                                <img src="{{ asset($partner->passport_photo) }}" alt="Passport" class="rounded-circle border border-3 border-white shadow"
                                     style="width: 90px; height: 90px; object-fit: cover;">
                            @else
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-2 border border-3 border-white shadow"
                                     style="width: 90px; height: 90px; background: linear-gradient(135deg, #cb202d, #e0555f);">
                                    {{ strtoupper(substr($partner->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="position-absolute bottom-0 end-0 badge bg-success text-white rounded-circle p-2 border border-2 border-white">
                                <i class="feather-check fs-12"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="fw-bold text-white mb-1">{{ $partner->name }}</h4>
                            <p class="text-white-50 mb-2 fs-14"><i class="feather-mail me-1"></i>{{ $partner->email }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-white text-danger rounded-pill px-3 py-2 fs-12 fw-semibold">
                                    <i class="feather-truck me-1"></i>Delivery Partner
                                </span>
                                @if($partner->status === 'inactive')
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fs-12 fw-semibold">
                                        <i class="feather-clock me-1"></i>KYC Pending
                                    </span>
                                @else
                                    <span class="badge bg-white text-success rounded-pill px-3 py-2 fs-12 fw-semibold">
                                        <i class="feather-check-circle me-1"></i>Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Personal Details -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2" style="background: linear-gradient(90deg, #cb202d 0%, #e0555f 100%);">
                        <i class="feather-user text-white"></i>
                        <h5 class="card-title mb-0 text-white fw-bold">Personal Details</h5>
                    </div>
                    <div class="card-body p-4 d-grid gap-3">
                        <div class="info-row">
                            <i class="feather-user text-danger"></i>
                            <span class="info-label">Full Name</span>
                            <span class="info-value fw-semibold text-dark">{{ $partner->name ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <i class="feather-mail text-danger"></i>
                            <span class="info-label">Email</span>
                            <span class="info-value fw-semibold text-dark">{{ $partner->email ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <i class="feather-phone text-danger"></i>
                            <span class="info-label">Phone</span>
                            <span class="info-value fw-semibold text-dark">{{ $partner->phone ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <i class="feather-gift text-danger"></i>
                            <span class="info-label">Age</span>
                            <span class="info-value fw-semibold text-dark">{{ $partner->age ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <i class="feather-map-pin text-danger"></i>
                            <span class="info-label">Location</span>
                            <span class="info-value fw-semibold text-dark">
                                {{ trim(implode(', ', array_filter([$partner->city ?? '', $partner->state?->name ?? '', $partner->country?->name ?? '']))) ?: '—' }}
                            </span>
                        </div>
                        <div class="info-row">
                            <i class="feather-credit-card text-danger"></i>
                            <span class="info-label">Aadhar</span>
                            <span class="info-value fw-semibold text-dark">{{ $partner->aadhar_card ? '•••• ' . substr($partner->aadhar_card, -4) : '—' }}</span>
                        </div>
                        <div class="info-row">
                            <i class="feather-file-text text-danger"></i>
                            <span class="info-label">PAN</span>
                            <span class="info-value fw-semibold text-dark">{{ $partner->pan_card ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Details -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2" style="background: linear-gradient(90deg, #e87c0f 0%, #f5a623 100%);">
                        <i class="feather-truck text-white"></i>
                        <h5 class="card-title mb-0 text-white fw-bold">Vehicle Details</h5>
                    </div>
                    <div class="card-body p-4 d-grid gap-3">
                        <div class="info-row">
                            <i class="feather-truck text-warning"></i>
                            <span class="info-label">Vehicle Type</span>
                            <span class="info-value fw-semibold text-dark">{{ ucfirst($partner->vehicle_type ?? '—') }}</span>
                        </div>
                        <div class="info-row">
                            <i class="feather-hash text-warning"></i>
                            <span class="info-label">Vehicle No</span>
                            <span class="info-value fw-semibold text-dark">{{ $partner->vehicle_number ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <i class="feather-file text-warning"></i>
                            <span class="info-label">RC Certificate</span>
                            <span class="info-value fw-semibold text-dark">
                                @if($partner->rc_image)
                                    <a href="{{ asset($partner->rc_image) }}" target="_blank" class="text-success text-decoration-none"><i class="feather-eye me-1"></i>View</a>
                                @else
                                    <span class="text-muted">Not Uploaded</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3 mt-4">
                    <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2" style="background: linear-gradient(90deg, #1e7e34 0%, #2ba842 100%);">
                        <i class="feather-briefcase text-white"></i>
                        <h5 class="card-title mb-0 text-white fw-bold">Bank Details</h5>
                    </div>
                    <div class="card-body p-4 d-grid gap-3">
                        <div class="info-row">
                            <i class="feather-briefcase text-success"></i>
                            <span class="info-label">Bank Name</span>
                            <span class="info-value fw-semibold text-dark">{{ $partner->bank_name ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <i class="feather-credit-card text-success"></i>
                            <span class="info-label">Account No</span>
                            <span class="info-value fw-semibold text-dark">{{ $partner->bank_account ? '•••• ' . substr($partner->bank_account, -4) : '—' }}</span>
                        </div>
                        <div class="info-row">
                            <i class="feather-codesandbox text-success"></i>
                            <span class="info-label">IFSC</span>
                            <span class="info-value fw-semibold text-dark">{{ $partner->ifsc_code ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Uploaded Documents -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mt-4">
            <div class="card-header border-0 py-3 px-4 d-flex align-items-center gap-2" style="background: linear-gradient(90deg, #3b82f6 0%, #10b981 100%);">
                <i class="feather-upload text-white"></i>
                <h5 class="card-title mb-0 text-white fw-bold">Uploaded Documents</h5>
            </div>
            <div class="card-body p-4">
                @php
                    $docs = [
                        ['label' => 'Passport Size Photo', 'file' => $partner->passport_photo, 'icon' => 'feather-camera'],
                        ['label' => 'Aadhar Card (Front)', 'file' => $partner->aadhar_front, 'icon' => 'feather-file-text'],
                        ['label' => 'Aadhar Card (Back)', 'file' => $partner->aadhar_back, 'icon' => 'feather-file-text'],
                        ['label' => 'RC Certificate', 'file' => $partner->rc_image, 'icon' => 'feather-file'],
                    ];
                @endphp
                <div class="row g-3">
                    @foreach($docs as $doc)
                        <div class="col-md-6 col-xxl-3">
                            <div class="doc-card">
                                <div class="doc-thumb">
                                    @if($doc['file'])
                                        <img src="{{ asset($doc['file']) }}" alt="{{ $doc['label'] }}">
                                        <span class="status-badge badge bg-success text-white rounded-circle p-2" title="Uploaded">
                                            <i class="feather-check fs-12"></i>
                                        </span>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <span class="badge bg-light text-muted rounded-pill px-3 py-2"><i class="feather-x me-1"></i>Not Uploaded</span>
                                        </div>
                                    @endif
                                </div>
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
@endsection
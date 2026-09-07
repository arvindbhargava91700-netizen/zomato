@extends('layouts.admin.main')

@section('title', 'Payment Gateways - Super Admin')

@section('content')
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10"><i class="feather-credit-card me-2 text-primary"></i> Payment Gateways Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Super Admin</li>
                <li class="breadcrumb-item active">Payment Gateways</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.payment-gateways.edit', 'razorpay') }}" class="btn btn-primary fw-semibold">
                <i class="feather-zap me-1"></i> Configure Razorpay
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="feather-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="feather-alert-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Overview banner -->
        <div class="card border-0 shadow-sm rounded-3 mb-4 text-white" style="background: linear-gradient(135deg, #072654 0%, #1e3a8a 100%) !important; color: #ffffff !important;">
            <div class="card-body p-4">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8 col-12">
                        <div class="d-inline-flex align-items-center gap-1 bg-white text-dark rounded-pill px-3 py-1 fw-bold mb-2 fs-12 shadow-sm">
                            <i class="feather-shield text-success"></i> <span>PCI-DSS Compliant & Encrypted</span>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: #ffffff !important;">Payment Gateway Settings & API Keys</h4>
                        <p class="mb-0 fs-13" style="color: rgba(255, 255, 255, 0.85) !important;">Manage your active payment processors for customer orders and dining table cover charges. Store and update your API Key and Secret Key securely.</p>
                    </div>
                    <div class="col-lg-4 col-12 text-lg-end">
                        <div class="d-inline-flex gap-2">
                            <div class="p-3 rounded-3 text-center" style="min-width: 110px; background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div class="fs-3 fw-bold" style="color: #ffffff !important; line-height: 1.2;">{{ $gateways->where('is_active', true)->count() }}</div>
                                <div class="fs-11 fw-semibold text-uppercase" style="color: rgba(255, 255, 255, 0.9) !important; letter-spacing: 0.5px;">Active</div>
                            </div>
                            <div class="p-3 rounded-3 text-center" style="min-width: 110px; background: rgba(255, 255, 255, 0.18); border: 1px solid rgba(255, 255, 255, 0.3);">
                                <div class="fs-3 fw-bold" style="color: #ffffff !important; line-height: 1.2;">{{ $gateways->count() }}</div>
                                <div class="fs-11 fw-semibold text-uppercase" style="color: rgba(255, 255, 255, 0.9) !important; letter-spacing: 0.5px;">Total Gateways</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Gateways Grid -->
        <div class="row g-4">
            @foreach($gateways as $gw)
                @php
                    $isRazorpay = ($gw->gateway_key === 'razorpay');
                @endphp
                <div class="col-xxl-4 col-lg-6 col-12">
                    <div class="card h-100 border-0 shadow-sm rounded-3 transition-all" style="border: 1.5px solid {{ $gw->is_active ? '#e2e8f0' : '#f1f5f9' }} !important;">
                        <div class="card-header p-4 border-bottom bg-white d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                     style="width: 46px; height: 46px; background-color: {{ $gw->badge_color ?: '#072654' }}; font-size: 20px;">
                                    @if($gw->gateway_key === 'razorpay')
                                        <i class="feather-zap"></i>
                                    @elseif($gw->gateway_key === 'phonepe')
                                        <i class="feather-smartphone"></i>
                                    @elseif($gw->gateway_key === 'paytm')
                                        <i class="feather-credit-card"></i>
                                    @elseif($gw->gateway_key === 'paypal')
                                        <i class="feather-globe"></i>
                                    @else
                                        <i class="feather-credit-card"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">{{ $gw->name }}</h6>
                                    <span class="badge {{ $gw->mode === 'live' ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }} fs-10 mt-1 text-uppercase">
                                        {{ $gw->mode === 'live' ? 'Live Mode' : 'Sandbox (Test)' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Toggle Status Form -->
                            <form action="{{ route('admin.payment-gateways.toggle-status', $gw->gateway_key) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch m-0" title="Toggle Active/Inactive">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           role="switch"
                                           onchange="this.form.submit()"
                                           {{ $gw->is_active ? 'checked' : '' }}
                                           style="cursor: pointer; width: 42px; height: 22px;">
                                </div>
                            </form>
                        </div>

                        <div class="card-body p-4">
                            <p class="text-muted fs-13 mb-3" style="min-height: 40px;">
                                {{ $gw->description ?? 'Secure online payment processing with instant confirmation.' }}
                            </p>

                            <!-- Key Details Summary -->
                            <div class="p-3 bg-light rounded-3 border mb-3 fs-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">API Key ID:</span>
                                    @if($gw->key_id)
                                        <span class="font-monospace fw-bold text-dark text-truncate" style="max-width: 180px;">
                                            {{ substr($gw->key_id, 0, 10) }}...{{ substr($gw->key_id, -4) }}
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">Not configured</span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">API Secret:</span>
                                    @if($gw->key_secret)
                                        <span class="font-monospace fw-bold text-success">
                                            ••••••••••••{{ substr($gw->key_secret, -4) }}
                                        </span>
                                    @else
                                        <span class="text-muted fst-italic">Not configured</span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Currency:</span>
                                    <span class="badge bg-soft-primary text-primary fw-bold">{{ $gw->currency ?? 'INR' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer p-3 bg-white border-top d-flex align-items-center justify-content-between">
                            <span class="fs-12 {{ $gw->is_active ? 'text-success fw-semibold' : 'text-muted' }}">
                                <i class="feather-circle {{ $gw->is_active ? 'fill-success' : 'fill-secondary' }} me-1 fs-10"></i>
                                {{ $gw->is_active ? 'Enabled on Storefront' : 'Disabled' }}
                            </span>
                            <a href="{{ route('admin.payment-gateways.edit', $gw->gateway_key) }}" class="btn btn-sm btn-primary px-3 fw-semibold">
                                <i class="feather-edit-2 me-1"></i> Edit Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

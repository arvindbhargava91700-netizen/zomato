@extends('layouts.admin.main')

@section('title', 'Withdrawal Request #' . $withdrawal->withdrawal_number . ' - Admin')

@push('styles')
<style>
    .fintech-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 20px;
        color: #ffffff !important;
        position: relative;
        overflow: hidden;
    }
    .fintech-hero-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.25) 0%, rgba(37, 99, 235, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .status-pill-pulse {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .pulse-dot-pending {
        background-color: #f59e0b;
        box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
        animation: pulseOrange 2s infinite;
    }
    .pulse-dot-approved {
        background-color: #10b981;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulseGreen 2s infinite;
    }
    .pulse-dot-rejected {
        background-color: #ef4444;
    }
    @keyframes pulseOrange {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(245, 158, 11, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }
    @keyframes pulseGreen {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Content Cards */
    .custom-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .custom-card-header {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 20px;
    }
    .info-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
        display: block;
    }
    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
    }
    .info-box-light {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
    }
    .bank-details-card {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    }
    .account-num-display {
        font-size: 22px;
        font-weight: 800;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        color: #0f172a;
        letter-spacing: 1px;
    }
    .copy-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-weight: 600;
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .copy-btn:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }
    .action-console-card {
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    }
    .tab-btn-pill {
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        padding: 10px 16px;
        transition: all 0.2s ease;
        border: 1px solid #cbd5e1;
        color: #475569;
        background: #f8fafc;
    }
    .tab-btn-pill.active.tab-approve {
        background: #16a34a !important;
        color: #ffffff !important;
        border-color: #16a34a !important;
        box-shadow: 0 4px 14px rgba(22, 163, 74, 0.3);
    }
    .tab-btn-pill.active.tab-reject {
        background: #dc2626 !important;
        color: #ffffff !important;
        border-color: #dc2626 !important;
        box-shadow: 0 4px 14px rgba(220, 38, 38, 0.3);
    }
    .reason-chip {
        cursor: pointer;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 16px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        transition: all 0.15s ease;
    }
    .reason-chip:hover {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
    }
    .timeline-wrapper {
        position: relative;
        padding-left: 28px;
    }
    .timeline-wrapper::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-node {
        position: relative;
        margin-bottom: 24px;
    }
    .timeline-node:last-child {
        margin-bottom: 0;
    }
    .timeline-bullet {
        position: absolute;
        left: -28px;
        top: 2px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }
</style>
@endpush

@section('content')
@php
    $currencySym = $currencySymbol ?? ($setting ? $setting->currencySymbol() : '₹');
    $acc = $withdrawal->account_details ?? [];
    $isPending = $withdrawal->status === 'pending';
    $isApproved = $withdrawal->status === 'approved';
    $isRejected = $withdrawal->status === 'rejected';
@endphp

<!-- [ page-header ] start -->
<div class="page-header d-flex align-items-center justify-content-between mb-4 no-print">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10 fw-bold">Withdrawal Request Details</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.withdrawals.index') }}">Withdrawals</a></li>
            <li class="breadcrumb-item">#{{ $withdrawal->withdrawal_number }}</li>
        </ul>
    </div>
    <div class="page-header-right d-flex gap-2">
        <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-sm btn-light border shadow-sm px-3 fw-semibold">
            <i class="feather-arrow-left me-1"></i>All Withdrawals
        </a>
        <button onclick="window.print()" class="btn btn-sm btn-dark shadow-sm px-3 fw-semibold">
            <i class="feather-printer me-1"></i>Print Slip
        </button>
    </div>
</div>
<!-- [ page-header ] end -->

<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm rounded-4 bg-soft-success" role="alert">
            <div class="d-flex align-items-center">
                <i class="feather-check-circle fs-20 me-3 text-success"></i>
                <div>
                    <h6 class="fw-bold mb-0 text-success fs-14">Action Completed</h6>
                    <div class="fs-13 text-dark">{{ session('success') }}</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-4 bg-soft-danger" role="alert">
            <div class="d-flex align-items-center">
                <i class="feather-alert-triangle fs-20 me-3 text-danger"></i>
                <div>
                    <h6 class="fw-bold mb-0 text-danger fs-14">Error</h6>
                    <div class="fs-13 text-dark">{{ session('error') }}</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-4 bg-soft-danger" role="alert">
            <div class="d-flex align-items-center">
                <i class="feather-alert-circle fs-20 me-3 text-danger"></i>
                <div>
                    <h6 class="fw-bold mb-1 text-danger fs-14">Please fix errors:</h6>
                    <ul class="mb-0 ps-3 fs-13 text-dark">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 1. HERO KPI BANNER (Dark Luxury Fintech Card) -->
    <div class="card fintech-hero-card border-0 shadow-lg mb-4 p-4 p-md-5">
        <div class="row align-items-center g-4 position-relative" style="z-index: 1;">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-3 mb-2 flex-wrap">
                    @if($isApproved)
                        <span class="status-pill-pulse bg-success text-white">
                            <span class="pulse-dot pulse-dot-approved"></span> Approved & Settled
                        </span>
                    @elseif($isRejected)
                        <span class="status-pill-pulse bg-danger text-white">
                            <span class="pulse-dot pulse-dot-rejected"></span> Rejected & Refunded
                        </span>
                    @else
                        <span class="status-pill-pulse bg-warning text-dark">
                            <span class="pulse-dot pulse-dot-pending"></span> Awaiting Admin Approval
                        </span>
                    @endif

                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 px-3 py-2 fs-12">
                        <i class="feather-credit-card me-1 text-info"></i>{{ ucwords(str_replace('_', ' ', $withdrawal->payout_method)) }}
                    </span>

                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 px-3 py-2 fs-12 font-monospace">
                        #{{ $withdrawal->withdrawal_number }}
                    </span>
                </div>

                <div class="fs-12 text-white text-opacity-75 text-uppercase fw-semibold tracking-wider">Total Settlement Payout Amount</div>
                <h1 class="display-4 fw-bold text-white mb-2" style="letter-spacing: -1.5px; color: #ffffff !important;">
                    {{ $currencySym }}{{ number_format($withdrawal->amount, 2) }}
                </h1>
                <div class="d-flex align-items-center gap-3 fs-13 text-white text-opacity-75">
                    <span><i class="feather-check-circle text-success me-1"></i>Processing Fee: <strong>Free ({{ $currencySym }}0.00)</strong></span>
                    <span>•</span>
                    <span>Net Transfer: <strong>{{ $currencySym }}{{ number_format($withdrawal->amount, 2) }}</strong></span>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-white text-dark d-flex align-items-center justify-content-center fw-bold fs-18 shadow" style="width: 50px; height: 50px; min-width: 50px;">
                            {{ strtoupper(substr($withdrawal->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="mb-0 text-white fw-bold fs-15 text-truncate" style="color: #ffffff !important;">{{ $withdrawal->user->name ?? 'Requester' }}</h6>
                                <span class="badge bg-primary text-white fs-10 px-2 py-0">{{ $withdrawal->user->role?->name ?? 'Partner' }}</span>
                            </div>
                            <div class="fs-12 text-white text-opacity-75 text-truncate">{{ $withdrawal->user->email ?? 'No email' }}</div>
                            @if($withdrawal->restaurant)
                                <div class="fs-12 text-warning fw-semibold mt-1 text-truncate">
                                    <i class="feather-home me-1"></i>{{ $withdrawal->restaurant->restaurant_name }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. TWO COLUMN DETAILS & ACTION GRID -->
    <div class="row g-4">
        <!-- LEFT COLUMN (7 COLS): Destination & Audit -->
        <div class="col-xl-7 col-lg-6">
            <!-- Bank & Payout Destination Details Card -->
            <div class="bank-details-card mb-4 p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-soft-success text-success d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="feather-shield fs-16"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0 fs-15">Destination Account Information</h6>
                    </div>
                    <span class="badge bg-soft-primary text-primary fw-bold text-uppercase fs-11">
                        {{ str_replace('_', ' ', $withdrawal->payout_method) }}
                    </span>
                </div>

                @if($withdrawal->payout_method === 'upi')
                    <!-- UPI Details -->
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <span class="info-label">Beneficiary UPI ID / Virtual Address</span>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span class="account-num-display text-primary">{{ $acc['upi_id'] ?? '-' }}</span>
                            @if(!empty($acc['upi_id']))
                                <button type="button" class="copy-btn" onclick="copyValue('{{ $acc['upi_id'] }}', this)">
                                    <i class="feather-copy me-1"></i>Copy UPI ID
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="info-box-light">
                                <span class="info-label">Account Holder Name</span>
                                <div class="info-value">{{ $acc['holder_name'] ?? ($withdrawal->user->name ?? '-') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box-light">
                                <span class="info-label">Transfer Channel</span>
                                <div class="info-value text-success">Instant VPA Settlement</div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Bank Account Details -->
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <span class="info-label">Bank Account Number</span>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span class="account-num-display">{{ $acc['account_number'] ?? '-' }}</span>
                            @if(!empty($acc['account_number']))
                                <button type="button" class="copy-btn" onclick="copyValue('{{ $acc['account_number'] }}', this)">
                                    <i class="feather-copy me-1"></i>Copy Account No
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <div class="info-box-light">
                                <span class="info-label">Beneficiary Name</span>
                                <div class="info-value">{{ $acc['holder_name'] ?? ($withdrawal->user->name ?? '-') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box-light">
                                <span class="info-label">Bank Name</span>
                                <div class="info-value">{{ $acc['bank_name'] ?? 'Not Specified' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box-light">
                                <span class="info-label">IFSC Code</span>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="info-value font-monospace fs-15 text-primary">{{ $acc['ifsc_code'] ?? '-' }}</span>
                                    @if(!empty($acc['ifsc_code']))
                                        <button type="button" class="copy-btn py-1 px-2" onclick="copyValue('{{ $acc['ifsc_code'] }}', this)">
                                            <i class="feather-copy"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box-light">
                                <span class="info-label">Payout Mode</span>
                                <div class="info-value text-success">NEFT / RTGS / IMPS</div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($acc['notes']))
                    <div class="p-3 bg-soft-warning rounded-3 border border-warning mt-3">
                        <span class="info-label text-dark">Requester Notes</span>
                        <div class="fs-13 text-dark fw-medium">{{ $acc['notes'] }}</div>
                    </div>
                @endif
            </div>

            <!-- Requester & Wallet Overview -->
            <div class="custom-card mb-4">
                <div class="custom-card-header d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold text-dark mb-0 fs-15 d-flex align-items-center gap-2">
                        <i class="feather-user text-primary"></i>Requester Details & Balance
                    </h6>
                    <span class="badge bg-light text-dark border">User Profile</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="info-box-light text-center">
                                <span class="info-label">Current Wallet Balance</span>
                                <div class="fs-18 fw-bold text-dark">{{ $currencySym }}{{ number_format($withdrawal->wallet?->balance ?? 0, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="info-box-light text-center">
                                <span class="info-label">User Role</span>
                                <div class="fs-14 fw-bold text-primary">{{ $withdrawal->user?->role?->name ?? 'Partner' }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="info-box-light text-center">
                                <span class="info-label">Phone Number</span>
                                <div class="fs-14 fw-bold text-dark">{{ $withdrawal->user?->phone ?? 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit Trail & Timeline -->
            <div class="custom-card mb-4">
                <div class="custom-card-header">
                    <h6 class="fw-bold text-dark mb-0 fs-15 d-flex align-items-center gap-2">
                        <i class="feather-activity text-primary"></i>Processing Timeline
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="timeline-wrapper">
                        <!-- Step 1: Requested -->
                        <div class="timeline-node">
                            <div class="timeline-bullet text-primary"><i class="feather-check"></i></div>
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold text-dark fs-14 mb-1">Request Submitted by User</h6>
                                    <p class="fs-12 text-muted mb-0">
                                        {{ $withdrawal->user?->name }} requested withdrawal of {{ $currencySym }}{{ number_format($withdrawal->amount, 2) }}.
                                    </p>
                                </div>
                                <span class="text-muted fs-11 fw-semibold">
                                    {{ $withdrawal->requested_at ? $withdrawal->requested_at->format('d M Y, h:i A') : $withdrawal->created_at->format('d M Y, h:i A') }}
                                </span>
                            </div>
                        </div>

                        <!-- Step 2: Final Resolution -->
                        @if($isApproved)
                            <div class="timeline-node">
                                <div class="timeline-bullet text-success" style="border-color: #16a34a;"><i class="feather-check"></i></div>
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-bold text-success fs-14 mb-1">Disbursed & Settled by Admin</h6>
                                        <p class="fs-12 text-dark mb-1">
                                            Processed by <strong>{{ $withdrawal->admin?->name ?? 'Admin' }}</strong>.
                                        </p>
                                        <div class="p-2 rounded-2 bg-soft-success border border-success d-inline-block fs-12 font-monospace fw-bold text-dark">
                                            Bank UTR: {{ $withdrawal->admin_transaction_id }}
                                        </div>
                                        @if($withdrawal->admin_remarks)
                                            <div class="fs-11 text-muted mt-1">Remark: {{ $withdrawal->admin_remarks }}</div>
                                        @endif
                                    </div>
                                    <span class="text-muted fs-11 fw-semibold">
                                        {{ $withdrawal->processed_at ? $withdrawal->processed_at->format('d M Y, h:i A') : '' }}
                                    </span>
                                </div>
                            </div>
                        @elseif($isRejected)
                            <div class="timeline-node">
                                <div class="timeline-bullet text-danger" style="border-color: #dc2626;"><i class="feather-x"></i></div>
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="fw-bold text-danger fs-14 mb-1">Rejected & Amount Refunded</h6>
                                        <p class="fs-12 text-dark mb-1">
                                            Rejected by <strong>{{ $withdrawal->admin?->name ?? 'Admin' }}</strong>. Full {{ $currencySym }}{{ number_format($withdrawal->amount, 2) }} refunded to wallet.
                                        </p>
                                        <div class="p-2 rounded-2 bg-soft-danger border border-danger fs-12 text-dark">
                                            <strong>Reason:</strong> {{ $withdrawal->admin_remarks }}
                                        </div>
                                    </div>
                                    <span class="text-muted fs-11 fw-semibold">
                                        {{ $withdrawal->processed_at ? $withdrawal->processed_at->format('d M Y, h:i A') : '' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="timeline-node">
                                <div class="timeline-bullet text-warning" style="border-color: #f59e0b;"><i class="feather-clock"></i></div>
                                <div>
                                    <h6 class="fw-bold text-dark fs-14 mb-1">Awaiting Admin Action</h6>
                                    <p class="fs-12 text-muted mb-0">Use the action console on the right to approve or reject this payout request.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN (5 COLS): Direct Action Console (NO POPUPS) -->
        <div class="col-xl-5 col-lg-6">
            @if($isPending)
                <!-- ACTION CONSOLE CARD -->
                <div class="action-console-card sticky-top" style="top: 80px; z-index: 10;">
                    <div class="custom-card-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                <i class="feather-zap fs-14"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0 fs-15">Take Action</h6>
                        </div>
                        <span class="badge bg-warning text-dark fw-bold">Pending Review</span>
                    </div>

                    <div class="p-4">
                        <!-- Navigation Pills for Action Selection -->
                        <ul class="nav nav-pills nav-justified mb-3 gap-2" id="adminActionTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active tab-btn-pill tab-approve d-flex align-items-center justify-content-center gap-2 w-100" 
                                        id="tab-approve-btn" data-bs-toggle="pill" data-bs-target="#tab-approve-pane" type="button" role="tab">
                                    <i class="feather-check-circle fs-15"></i>
                                    <span>Approve & Settle</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-btn-pill tab-reject d-flex align-items-center justify-content-center gap-2 w-100" 
                                        id="tab-reject-btn" data-bs-toggle="pill" data-bs-target="#tab-reject-pane" type="button" role="tab">
                                    <i class="feather-x-circle fs-15"></i>
                                    <span>Reject & Refund</span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="adminActionTabsContent">
                            <!-- TAB 1: APPROVE SETTLEMENT FORM -->
                            <div class="tab-pane fade show active" id="tab-approve-pane" role="tabpanel">
                                <div class="p-3 bg-soft-success rounded-3 border border-success mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fs-12 text-uppercase fw-bold text-success">Payout Amount</span>
                                        <span class="fs-18 fw-bold text-success">{{ $currencySym }}{{ number_format($withdrawal->amount, 2) }}</span>
                                    </div>
                                    <div class="fs-12 text-dark">
                                        Transfer funds to the destination account and enter the settlement reference below.
                                    </div>
                                </div>

                                <form id="pageApproveForm" method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label fs-12 fw-bold text-dark text-uppercase mb-1">
                                            Bank Transaction ID / UTR Number <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="feather-hash fs-14 text-muted"></i></span>
                                            <input type="text" name="admin_transaction_id" class="form-control fw-bold font-monospace fs-14" 
                                                   placeholder="e.g. UTR1234567890 / IMPS987654" required>
                                        </div>
                                        <small class="text-muted fs-11">Enter the UTR or reference generated from your banking application.</small>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fs-12 fw-bold text-dark text-uppercase mb-1">Admin Remarks (Optional)</label>
                                        <input type="text" name="admin_remarks" class="form-control" placeholder="e.g. Settled via Corporate Netbanking">
                                    </div>

                                    <button type="submit" id="btnPageApproveSubmit" class="btn btn-success w-100 py-2 fw-bold shadow-sm position-relative">
                                        <span class="btn-text d-inline-flex align-items-center justify-content-center">
                                            <i class="feather-check-circle me-2 fs-15"></i>Confirm & Mark Paid ({{ $currencySym }}{{ number_format($withdrawal->amount, 2) }})
                                        </span>
                                        <span class="btn-loader d-none align-items-center justify-content-center">
                                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                            Approving & Settling...
                                        </span>
                                    </button>
                                </form>
                            </div>

                            <!-- TAB 2: REJECT & REFUND FORM -->
                            <div class="tab-pane fade" id="tab-reject-pane" role="tabpanel">
                                <div class="p-3 bg-soft-danger rounded-3 border border-danger mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fs-12 text-uppercase fw-bold text-danger">Auto-Refund Amount</span>
                                        <span class="fs-18 fw-bold text-danger">{{ $currencySym }}{{ number_format($withdrawal->amount, 2) }}</span>
                                    </div>
                                    <div class="fs-12 text-dark">
                                        Rejecting will immediately credit the held funds back to the user's wallet.
                                    </div>
                                </div>

                                <form id="pageRejectForm" method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}">
                                    @csrf

                                    <!-- Quick Reason Chips -->
                                    <div class="mb-3">
                                        <label class="form-label fs-11 fw-bold text-muted text-uppercase mb-2">Quick Reasons:</label>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <span class="reason-chip" onclick="setRejectReason('Invalid IFSC Code provided')">Invalid IFSC</span>
                                            <span class="reason-chip" onclick="setRejectReason('Bank account number mismatch')">Account Mismatch</span>
                                            <span class="reason-chip" onclick="setRejectReason('Beneficiary name does not match bank records')">Name Mismatch</span>
                                            <span class="reason-chip" onclick="setRejectReason('Inactive or invalid UPI handle')">Invalid UPI</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fs-12 fw-bold text-dark text-uppercase mb-1">
                                            Rejection Reason <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="admin_remarks" id="rejectRemarksInput" class="form-control" rows="3" 
                                                  placeholder="Provide clear reason for rejection..." required></textarea>
                                        <small class="text-muted fs-11">This reason will be visible to the user in their withdrawal history.</small>
                                    </div>

                                    <button type="submit" id="btnPageRejectSubmit" class="btn btn-danger w-100 py-2 fw-bold shadow-sm position-relative">
                                        <span class="btn-text d-inline-flex align-items-center justify-content-center">
                                            <i class="feather-x-circle me-2 fs-15"></i>Confirm Rejection & Refund Wallet
                                        </span>
                                        <span class="btn-loader d-none align-items-center justify-content-center">
                                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                            Processing Rejection & Refund...
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($isApproved)
                <!-- SETTLED RECEIPT CARD -->
                <div class="custom-card mb-4 border-success">
                    <div class="custom-card-header bg-soft-success d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="feather-check-circle text-success fs-18"></i>
                            <h6 class="fw-bold text-dark mb-0 fs-15">Settlement Record</h6>
                        </div>
                        <span class="badge bg-success text-white">SETTLED</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center py-3 border-bottom mb-3">
                            <div class="fs-12 text-muted text-uppercase fw-semibold">Disbursed Amount</div>
                            <h2 class="display-6 fw-bold text-success mb-0">{{ $currencySym }}{{ number_format($withdrawal->amount, 2) }}</h2>
                        </div>

                        <div class="info-box-light mb-3">
                            <span class="info-label">Bank Settlement UTR / Reference ID</span>
                            <div class="fs-16 fw-bold text-dark font-monospace">{{ $withdrawal->admin_transaction_id }}</div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <span class="info-label">Settled Date</span>
                                <div class="fs-13 fw-semibold text-dark">{{ $withdrawal->processed_at ? $withdrawal->processed_at->format('d M Y, h:i A') : '-' }}</div>
                            </div>
                            <div class="col-6">
                                <span class="info-label">Settled By</span>
                                <div class="fs-13 fw-semibold text-dark">{{ $withdrawal->admin?->name ?? 'Admin' }}</div>
                            </div>
                        </div>

                        @if($withdrawal->admin_remarks)
                            <div class="p-3 bg-light rounded-3 border">
                                <span class="info-label">Admin Note</span>
                                <div class="fs-13 text-dark">{{ $withdrawal->admin_remarks }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- REJECTED NOTICE CARD -->
                <div class="custom-card mb-4 border-danger">
                    <div class="custom-card-header bg-soft-danger d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="feather-x-circle text-danger fs-18"></i>
                            <h6 class="fw-bold text-dark mb-0 fs-15">Rejection & Refund Record</h6>
                        </div>
                        <span class="badge bg-danger text-white">REFUNDED</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center py-3 border-bottom mb-3">
                            <div class="fs-12 text-muted text-uppercase fw-semibold">Refunded Back to Wallet</div>
                            <h2 class="display-6 fw-bold text-danger mb-0">{{ $currencySym }}{{ number_format($withdrawal->amount, 2) }}</h2>
                        </div>

                        <div class="p-3 bg-soft-danger rounded-3 border border-danger mb-3">
                            <span class="info-label text-danger">Rejection Reason</span>
                            <div class="fs-14 fw-bold text-danger">{{ $withdrawal->admin_remarks }}</div>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <span class="info-label">Processed Date</span>
                                <div class="fs-13 fw-semibold text-dark">{{ $withdrawal->processed_at ? $withdrawal->processed_at->format('d M Y, h:i A') : '-' }}</div>
                            </div>
                            <div class="col-6">
                                <span class="info-label">Processed By</span>
                                <div class="fs-13 fw-semibold text-dark">{{ $withdrawal->admin?->name ?? 'Admin' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Copy value helper
    function copyValue(text, btnElement) {
        if (!navigator.clipboard) {
            const temp = document.createElement('textarea');
            temp.value = text;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
        } else {
            navigator.clipboard.writeText(text);
        }

        const originalHTML = btnElement.innerHTML;
        btnElement.innerHTML = '<i class="feather-check text-success"></i> <span class="fs-11 text-success fw-bold">Copied!</span>';
        setTimeout(() => {
            btnElement.innerHTML = originalHTML;
        }, 1500);
    }

    // Quick Reason Setter
    function setRejectReason(reason) {
        const input = document.getElementById('rejectRemarksInput');
        if (input) {
            input.value = reason;
            input.focus();
        }
    }

    // Submit Loaders
    document.addEventListener('DOMContentLoaded', function() {
        const approveForm = document.getElementById('pageApproveForm');
        const approveBtn = document.getElementById('btnPageApproveSubmit');
        if (approveForm && approveBtn) {
            approveForm.addEventListener('submit', function() {
                if (!approveForm.checkValidity()) return;
                approveBtn.disabled = true;
                const text = approveBtn.querySelector('.btn-text');
                const loader = approveBtn.querySelector('.btn-loader');
                if (text && loader) {
                    text.classList.add('d-none');
                    text.classList.remove('d-inline-flex');
                    loader.classList.remove('d-none');
                    loader.classList.add('d-inline-flex');
                }
            });
        }

        const rejectForm = document.getElementById('pageRejectForm');
        const rejectBtn = document.getElementById('btnPageRejectSubmit');
        if (rejectForm && rejectBtn) {
            rejectForm.addEventListener('submit', function() {
                if (!rejectForm.checkValidity()) return;
                rejectBtn.disabled = true;
                const text = rejectBtn.querySelector('.btn-text');
                const loader = rejectBtn.querySelector('.btn-loader');
                if (text && loader) {
                    text.classList.add('d-none');
                    text.classList.remove('d-inline-flex');
                    loader.classList.remove('d-none');
                    loader.classList.add('d-inline-flex');
                }
            });
        }
    });
</script>
@endpush
@endsection

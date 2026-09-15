@extends('layouts.delivery-partner.main')

@section('title', 'Withdrawals & Payouts - Delivery Partner')

@push('styles')
<style>
    .metric-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(0,0,0,0.06);
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.06) !important;
    }
    .quick-chip {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
    }
    .quick-chip:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }
    .status-badge {
        padding: 6px 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')
<!-- [ page-header ] start -->
<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10 fw-bold">Withdrawals & Payouts</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('delivery-partner.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Withdrawals</li>
        </ul>
    </div>
    <div class="page-header-right d-flex gap-2">
        <a href="{{ route('delivery-partner.withdrawals.create') }}" class="btn btn-primary shadow-sm px-3">
            <i class="feather-plus-circle me-1"></i>Request Withdrawal
        </a>
    </div>
</div>
<!-- [ page-header ] end -->

@php
    $currencySym = $currencySymbol ?? ($setting ? $setting->currencySymbol() : '₹');
@endphp

<!-- [ Main Content ] start -->
<div class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
            <i class="feather-check-circle me-2 fs-16"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm rounded-3" role="alert">
            <i class="feather-alert-triangle me-2 fs-16"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- Available Wallet Balance -->
        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle {{ $stats['wallet_balance'] >= 0 ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }} d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-credit-card fs-20"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="fs-20 fw-bold {{ $stats['wallet_balance'] >= 0 ? 'text-dark' : 'text-danger' }}" style="letter-spacing: -0.5px;">
                            {{ $currencySym }}{{ number_format($stats['wallet_balance'], 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Available for Withdrawal</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Withdrawn (Approved) -->
        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-check-circle fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-primary" style="letter-spacing: -0.5px;">
                            {{ $currencySym }}{{ number_format($stats['total_withdrawn'], 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Total Paid Out</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Withdrawal Requests -->
        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-warning text-warning d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-clock fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-warning" style="letter-spacing: -0.5px;">
                            {{ $currencySym }}{{ number_format($stats['pending_withdrawal'], 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Pending Admin Review</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected / Refunded -->
        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-secondary text-secondary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-rotate-ccw fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-secondary" style="letter-spacing: -0.5px;">
                            {{ $currencySym }}{{ number_format($stats['rejected_withdrawal'], 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Rejected / Refunded</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Details Preview Banner -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-soft-info text-info d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                            <i class="feather-shield fs-18"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Bank & Payout Profile</h6>
                            <div class="fs-12 text-muted">
                                @if($partner->bank_account)
                                    Bank: <strong>{{ $partner->bank_name ?? 'Saved Bank' }}</strong> • 
                                    A/C: <strong>••••{{ substr($partner->bank_account, -4) }}</strong> • 
                                    IFSC: <strong>{{ $partner->ifsc_code ?? '-' }}</strong>
                                @else
                                    <span class="text-warning"><i class="feather-alert-circle me-1"></i>No default bank account saved in profile. You can enter it directly during withdrawal.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#requestWithdrawalModal">
                        <i class="feather-arrow-up-circle me-1"></i>Withdraw Funds
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal History Table -->
    <div class="card stretch stretch-full border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-bold fs-15 text-dark">
                <i class="feather-list me-2 text-primary"></i>Withdrawal Requests History
            </h5>
            <span class="badge bg-soft-primary text-primary px-3 py-1 rounded-pill fs-12 fw-semibold">
                {{ $withdrawals->total() }} Records
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Request #</th>
                            <th>Amount</th>
                            <th>Payout Method</th>
                            <th>Account Details</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th>Admin Reference / Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $w)
                            <tr>
                                <!-- Request # -->
                                <td class="ps-4">
                                    <span class="badge bg-soft-secondary text-dark font-monospace fs-12">
                                        {{ $w->withdrawal_number }}
                                    </span>
                                </td>

                                <!-- Amount -->
                                <td>
                                    <span class="fw-bold text-dark fs-14">
                                        {{ $currencySym }}{{ number_format($w->amount, 2) }}
                                    </span>
                                </td>

                                <!-- Payout Method -->
                                <td>
                                    @if($w->payout_method === 'upi')
                                        <span class="badge bg-soft-info text-info fs-11">
                                            <i class="feather-smartphone me-1"></i>UPI
                                        </span>
                                    @else
                                        <span class="badge bg-soft-primary text-primary fs-11">
                                            <i class="feather-home me-1"></i>Bank Transfer
                                        </span>
                                    @endif
                                </td>

                                <!-- Account Details Snapshot -->
                                <td>
                                    @php $acc = $w->account_details ?? []; @endphp
                                    @if($w->payout_method === 'upi')
                                        <div class="fs-12 fw-bold text-dark font-monospace">{{ $acc['upi_id'] ?? '-' }}</div>
                                    @else
                                        <div class="fs-12 fw-bold text-dark">{{ $acc['bank_name'] ?? '-' }}</div>
                                        <div class="fs-11 text-muted font-monospace">A/C: {{ $acc['account_number'] ?? '-' }} • IFSC: {{ $acc['ifsc_code'] ?? '-' }}</div>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td>
                                    @if($w->status === 'approved')
                                        <span class="status-badge bg-soft-success text-success">
                                            <i class="feather-check-circle me-1"></i>Paid / Approved
                                        </span>
                                    @elseif($w->status === 'rejected')
                                        <span class="status-badge bg-soft-danger text-danger">
                                            <i class="feather-x-circle me-1"></i>Rejected & Refunded
                                        </span>
                                    @else
                                        <span class="status-badge bg-soft-warning text-warning">
                                            <i class="feather-clock me-1"></i>Pending Review
                                        </span>
                                    @endif
                                </td>

                                <!-- Requested At -->
                                <td class="text-muted fs-12">
                                    {{ $w->requested_at ? $w->requested_at->format('d M Y, h:i A') : $w->created_at->format('d M Y, h:i A') }}
                                </td>

                                <!-- Admin Remarks & UTR -->
                                <td>
                                    @if($w->status === 'approved')
                                        <div class="fs-12 fw-bold text-success font-monospace">
                                            UTR: {{ $w->admin_transaction_id ?? 'Settled' }}
                                        </div>
                                        @if($w->admin_remarks)
                                            <div class="fs-11 text-muted">{{ $w->admin_remarks }}</div>
                                        @endif
                                    @elseif($w->status === 'rejected')
                                        <div class="fs-12 text-danger">
                                            <strong>Reason:</strong> {{ $w->admin_remarks ?? 'Request rejected' }}
                                        </div>
                                    @else
                                        <span class="text-muted fs-12"><i class="feather-loader me-1"></i>In Queue</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted py-4">
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                            <i class="feather-arrow-up-circle fs-24 text-secondary opacity-50"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No Withdrawal Requests</h6>
                                        <p class="fs-12 text-muted mb-0">When you request payout from your wallet, your requests will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($withdrawals->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
</div>

@endsection

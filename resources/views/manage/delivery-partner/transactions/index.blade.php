@extends('layouts.delivery-partner.main')

@section('title', 'Transactions - Delivery Partner Ledger')

@push('styles')
<style>
    .metric-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.06) !important;
    }
    .txn-table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #64748b;
    }
    .txn-row:hover {
        background-color: #f8fafc !important;
    }
</style>
@endpush

@section('content')
<!-- [ page-header ] start -->
<div class="page-header d-flex align-items-center justify-content-between mb-4">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10 fw-bold">Wallet Transactions</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('delivery-partner.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Transactions</li>
        </ul>
    </div>
    <div class="page-header-right d-flex gap-2">
        <a href="{{ route('delivery-partner.earnings.index') }}" class="btn btn-sm btn-outline-primary shadow-sm px-3">
            <i class="feather-trending-up me-1"></i>Order Earnings
        </a>
    </div>
</div>
<!-- [ page-header ] end -->

@php
    $setting = $setting ?? \App\Models\CompanySetting::firstSetting();
    $currencySymbol = $currencySymbol ?? ($setting ? $setting->currencySymbol() : '₹');
@endphp

<!-- [ Main Content ] start -->
<div class="main-content">
    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <!-- Current Wallet Balance -->
        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle {{ $stats['wallet_balance'] >= 0 ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }} d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-credit-card fs-20"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="fs-20 fw-bold {{ $stats['wallet_balance'] >= 0 ? 'text-dark' : 'text-danger' }}" style="letter-spacing: -0.5px;">
                            {{ $currencySymbol }}{{ number_format($stats['wallet_balance'], 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">
                            {{ $stats['wallet_balance'] >= 0 ? 'Available Balance' : 'COD Cash Holding' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Credits -->
        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-success text-success d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-arrow-down-left fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-success" style="letter-spacing: -0.5px;">
                            +{{ $currencySymbol }}{{ number_format($stats['total_credits'], 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Total Credited</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Debits -->
        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-danger text-danger d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-arrow-up-right fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-danger" style="letter-spacing: -0.5px;">
                            -{{ $currencySymbol }}{{ number_format($stats['total_debits'], 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Total Debited</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Transaction Count -->
        <div class="col-xl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-repeat fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-dark" style="letter-spacing: -0.5px;">
                            {{ $stats['total_count'] }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Total Transactions</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card stretch stretch-full border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('delivery-partner.transactions.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Search Keyword</label>
                    <div class="input-group input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="feather-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm border-start-0" placeholder="Search Txn # or description...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Type</label>
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit (+)</option>
                        <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit (-)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-2">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn  btn-primary flex-fill fw-semibold">
                        <i class="feather-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('delivery-partner.transactions.index') }}" class="btn  btn-light border" title="Reset Filters">
                        <i class="feather-rotate-ccw"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Transactions Table Card -->
    <div class="card stretch stretch-full border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-bold fs-15 text-dark">
                <i class="feather-list me-2 text-primary"></i>Wallet Transaction Ledger
            </h5>
            <span class="badge bg-soft-primary text-primary px-3 py-1 rounded-pill fs-12 fw-semibold">
                {{ $transactions->total() }} Records
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 txn-table">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Txn Number</th>
                            <th>Type</th>
                            <th>Description / Source</th>
                            <th>Reference</th>
                            <th>Amount</th>
                            <th>Balance Before</th>
                            <th>Balance After</th>
                            <th>Date & Time</th>
                            <th class="text-end pe-4">Receipt / Slip</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                            <tr class="txn-row">
                                <td class="ps-4">
                                    <span class="badge bg-soft-secondary text-dark font-monospace fs-12">
                                        {{ $tx->transaction_number }}
                                    </span>
                                </td>
                                <td>
                                    @if($tx->type === 'credit')
                                        <span class="badge bg-soft-success text-success px-2 py-1 rounded-pill fs-11 fw-semibold">
                                            <i class="feather-arrow-down-left me-1"></i>Credit
                                        </span>
                                    @else
                                        <span class="badge bg-soft-danger text-danger px-2 py-1 rounded-pill fs-11 fw-semibold">
                                            <i class="feather-arrow-up-right me-1"></i>Debit
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-13">{{ $tx->description ?? '-' }}</div>
                                    <div class="fs-11 text-muted">
                                        Source: <code class="text-primary">{{ $tx->source }}</code>
                                    </div>
                                </td>
                                <td>
                                    @if($tx->reference_id)
                                        <a href="{{ route('delivery-partner.orders.show', $tx->reference_id) }}" class="btn btn-xs btn-outline-primary rounded-pill py-0 px-2 fs-11">
                                            <i class="feather-shopping-bag me-1"></i>Order #{{ $tx->reference_id }}
                                        </a>
                                    @else
                                        <span class="text-muted fs-12">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bolder {{ $tx->type === 'credit' ? 'text-success' : 'text-danger' }} fs-14">
                                        {{ $tx->type === 'credit' ? '+' : '-' }}{{ $currencySymbol }}{{ number_format($tx->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted fs-12">
                                        {{ $currencySymbol }}{{ number_format($tx->balance_before, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-dark fw-bold fs-13">
                                        {{ $currencySymbol }}{{ number_format($tx->balance_after, 2) }}
                                    </span>
                                </td>
                                <td class="text-muted fs-12">
                                    <i class="feather-clock me-1 text-muted"></i>{{ $tx->created_at ? $tx->created_at->format('d M Y, h:i A') : '-' }}
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('delivery-partner.transactions.show', $tx->id) }}" class="btn btn-sm btn-primary rounded-pill py-1 px-3 fs-12 shadow-sm">
                                        <i class="feather-file-text me-1"></i>Slip
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted py-4">
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                            <i class="feather-credit-card fs-24 text-secondary opacity-50"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No Transactions Found</h6>
                                        <p class="fs-12 text-muted mb-0">Your wallet payout credits and cash collection debits will be listed here.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($transactions->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

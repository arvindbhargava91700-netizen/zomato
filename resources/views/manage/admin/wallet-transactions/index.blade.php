@extends('layouts.admin.main')

@section('title', 'Wallet Transactions - Admin Dashboard')

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
            <h5 class="m-b-10 fw-bold">System Wallet Transactions</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Wallet Transactions</li>
        </ul>
    </div>
    <div class="page-header-right d-flex gap-2">
        <a href="{{ route('admin.wallet-transactions.index') }}" class="btn btn-sm btn-light border shadow-sm px-3">
            <i class="feather-rotate-cw me-1"></i>Refresh
        </a>
    </div>
</div>
<!-- [ page-header ] end -->

@php
    $currencySym = $currencySymbol ?? ($setting ? $setting->currencySymbol() : '₹');
@endphp

<!-- [ Main Content ] start -->
<div class="main-content">
    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- Total System Wallets Balance -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-credit-card fs-20"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="fs-20 fw-bold text-dark" style="letter-spacing: -0.5px;">
                            {{ $currencySym }}{{ number_format($totalSystemWalletsBalance, 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">
                            Total System Wallets Balance
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total System Credits -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-success text-success d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-arrow-down-left fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-success" style="letter-spacing: -0.5px;">
                            +{{ $currencySym }}{{ number_format($totalSystemCredits, 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Total System Credits</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total System Debits -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-danger text-danger d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-arrow-up-right fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-danger" style="letter-spacing: -0.5px;">
                            -{{ $currencySym }}{{ number_format($totalSystemDebits, 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Total System Debits</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Transactions Count -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-info text-info d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-repeat fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-dark" style="letter-spacing: -0.5px;">
                            {{ number_format($totalTxnCount) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Total Wallet Transactions</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card stretch stretch-full border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('admin.wallet-transactions.index') }}" class="row g-3 align-items-end">
                <!-- Search Keyword -->
                <div class="col-md-3">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="feather-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Txn #, user name, email...">
                    </div>
                </div>

                <!-- User Role / Type -->
                <div class="col-md-2">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">User Role</label>
                    <select name="role_id" class="form-select">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Transaction Type (Credit / Debit) -->
                <div class="col-md-2">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Credit (+)</option>
                        <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Debit (-)</option>
                    </select>
                </div>

                <!-- Source Category -->
                <div class="col-md-2">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Source</label>
                    <select name="source" class="form-select">
                        <option value="">All Sources</option>
                        @foreach($availableSources as $src)
                            <option value="{{ $src }}" {{ request('source') === $src ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $src)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range: From -->
                <div class="col-md-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">From</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">To</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="col-12 d-flex justify-content-end gap-2 pt-2 border-top">
                    <a href="{{ route('admin.wallet-transactions.index') }}" class="btn btn-light border px-3 fw-semibold">
                        <i class="feather-rotate-ccw me-1"></i>Reset
                    </a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="feather-filter me-1"></i>Apply Filters
                    </button>
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
                            <th>User / Account</th>
                            <th>Type</th>
                            <th>Description / Source</th>
                            <th>Reference</th>
                            <th>Amount</th>
                            <th>Balance Flow</th>
                            <th>Date & Time</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                            <tr class="txn-row">
                                <!-- Transaction Number -->
                                <td class="ps-4">
                                    <span class="badge bg-soft-secondary text-dark font-monospace fs-12">
                                        {{ $tx->transaction_number }}
                                    </span>
                                </td>

                                <!-- User Details & Role -->
                                <td>
                                    @if($tx->user)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark fw-bold" style="width: 34px; height: 34px; font-size: 13px;">
                                                {{ strtoupper(substr($tx->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-13">{{ $tx->user->name }}</div>
                                                <div class="d-flex align-items-center gap-1">
                                                    @php
                                                        $roleSlug = $tx->user->role?->slug ?? '';
                                                        $roleBadge = match($roleSlug) {
                                                            'delivery_partner' => 'bg-soft-warning text-warning',
                                                            'restaurant_owner' => 'bg-soft-info text-info',
                                                            'admin', 'super_admin' => 'bg-soft-danger text-danger',
                                                            default => 'bg-soft-secondary text-secondary',
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $roleBadge }} fs-10 px-1 py-0">
                                                        {{ $tx->user->role?->name ?? 'User' }}
                                                    </span>
                                                    <span class="text-muted fs-11">• {{ $tx->user->phone ?? $tx->user->email ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted fs-12">User #{{ $tx->user_id }}</span>
                                    @endif
                                </td>

                                <!-- Type -->
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

                                <!-- Source / Description -->
                                <td>
                                    <div class="fw-bold text-dark fs-13">{{ $tx->description ?? '-' }}</div>
                                    <div class="fs-11 text-muted">
                                        Source: <code class="text-primary">{{ $tx->source }}</code>
                                    </div>
                                </td>

                                <!-- Reference -->
                                <td>
                                    @if($tx->reference_id)
                                        <span class="badge bg-light text-dark border font-monospace fs-11">
                                            Ref #{{ $tx->reference_id }}
                                        </span>
                                    @else
                                        <span class="text-muted fs-12">-</span>
                                    @endif
                                </td>

                                <!-- Amount -->
                                <td>
                                    <span class="fw-bolder {{ $tx->type === 'credit' ? 'text-success' : 'text-danger' }} fs-14">
                                        {{ $tx->type === 'credit' ? '+' : '-' }}{{ $currencySym }}{{ number_format($tx->amount, 2) }}
                                    </span>
                                </td>

                                <!-- Balance Before -> After -->
                                <td>
                                    <div class="fs-12 d-flex align-items-center gap-1">
                                        <span class="text-muted">{{ $currencySym }}{{ number_format($tx->balance_before, 2) }}</span>
                                        <i class="feather-arrow-right text-muted fs-10"></i>
                                        <span class="fw-bold text-dark">{{ $currencySym }}{{ number_format($tx->balance_after, 2) }}</span>
                                    </div>
                                </td>

                                <!-- Date & Time -->
                                <td class="text-muted fs-12">
                                    <i class="feather-clock me-1 text-muted"></i>{{ $tx->created_at ? $tx->created_at->format('d M Y, h:i A') : '-' }}
                                </td>

                                <!-- Action -->
                                <td class="text-end pe-4">
                                    <a href="{{ route('admin.wallet-transactions.show', $tx->id) }}" class="btn btn-sm btn-primary rounded-pill py-1 px-3 fs-12 shadow-sm">
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
                                        <h6 class="fw-bold text-dark mb-1">No Wallet Transactions Found</h6>
                                        <p class="fs-12 text-muted mb-0">System wallet payouts, earnings, COD deductions, and deposits will be recorded here.</p>
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

@extends('layouts.admin.main')

@section('title', 'Withdrawal Requests - Admin Dashboard')

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
            <h5 class="m-b-10 fw-bold">Withdrawal & Payout Requests</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Withdrawals</li>
        </ul>
    </div>
    <div class="page-header-right d-flex gap-2">
        <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-sm btn-light border shadow-sm px-3">
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
        <!-- Pending Payout Requests -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-warning text-warning d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-clock fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-warning" style="letter-spacing: -0.5px;">
                            {{ $currencySym }}{{ number_format($stats['total_pending_amount'], 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">
                            Pending Requests ({{ $stats['total_pending_count'] }})
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Approved / Settled -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-success text-success d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-check-circle fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-success" style="letter-spacing: -0.5px;">
                            {{ $currencySym }}{{ number_format($stats['total_approved_amount'], 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Total Approved / Settled</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Rejected / Refunded -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-danger text-danger d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-x-circle fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-danger" style="letter-spacing: -0.5px;">
                            {{ $currencySym }}{{ number_format($stats['total_rejected_amount'], 2) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">Total Rejected / Refunded</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Payout Requests -->
        <div class="col-xxl-3 col-md-6">
            <div class="card stretch stretch-full border-0 shadow-sm rounded-4 metric-card bg-white">
                <div class="card-body p-3 d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; min-width: 52px;">
                        <i class="feather-layers fs-20"></i>
                    </div>
                    <div>
                        <div class="fs-20 fw-bold text-dark" style="letter-spacing: -0.5px;">
                            {{ number_format($stats['total_requests_count']) }}
                        </div>
                        <div class="fs-12 text-muted fw-semibold">All Payout Requests</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card stretch stretch-full border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="row g-3 align-items-end">
                <!-- Search -->
                <div class="col-md-3">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="feather-search text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0" placeholder="Withdrawal #, user name, phone...">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-md-2">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved / Paid</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <!-- Role Filter -->
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

                <!-- Payout Method -->
                <div class="col-md-2">
                    <label class="form-label fs-12 fw-bold text-muted text-uppercase mb-1">Method</label>
                    <select name="payout_method" class="form-select">
                        <option value="">All Methods</option>
                        <option value="bank_transfer" {{ request('payout_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="upi" {{ request('payout_method') === 'upi' ? 'selected' : '' }}>UPI</option>
                    </select>
                </div>

                <!-- Date Range -->
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
                    <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-light border px-3 fw-semibold">
                        <i class="feather-rotate-ccw me-1"></i>Reset
                    </a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="feather-filter me-1"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdrawals Table Card -->
    <div class="card stretch stretch-full border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0 fw-bold fs-15 text-dark">
                <i class="feather-list me-2 text-primary"></i>All Payout Requests
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
                            <th>Requester / User</th>
                            <th>Amount</th>
                            <th>Payout Account Details</th>
                            <th>Status</th>
                            <th>Requested At</th>
                            <th>Settlement / Remarks</th>
                            <th class="text-end pe-4">Actions</th>
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

                                <!-- Requester -->
                                <td>
                                    @if($w->user)
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark fw-bold" style="width: 34px; height: 34px; font-size: 13px;">
                                                {{ strtoupper(substr($w->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-13">{{ $w->user->name }}</div>
                                                <div class="d-flex align-items-center gap-1">
                                                    @php
                                                        $roleSlug = $w->user->role?->slug ?? '';
                                                        $roleBadge = match($roleSlug) {
                                                            'delivery_partner' => 'bg-soft-warning text-warning',
                                                            'restaurant_owner' => 'bg-soft-info text-info',
                                                            default => 'bg-soft-secondary text-secondary',
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $roleBadge }} fs-10 px-1 py-0">
                                                        {{ $w->user->role?->name ?? 'User' }}
                                                    </span>
                                                    @if($w->restaurant)
                                                        <span class="text-muted fs-11">• {{ $w->restaurant->restaurant_name }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted fs-12">User #{{ $w->user_id }}</span>
                                    @endif
                                </td>

                                <!-- Amount -->
                                <td>
                                    <span class="fw-bold text-dark fs-14">
                                        {{ $currencySym }}{{ number_format($w->amount, 2) }}
                                    </span>
                                </td>

                                <!-- Payout Details -->
                                <td>
                                    @php $acc = $w->account_details ?? []; @endphp
                                    @if($w->payout_method === 'upi')
                                        <div class="fs-12 fw-bold text-dark font-monospace">
                                            <i class="feather-smartphone text-info me-1"></i>{{ $acc['upi_id'] ?? '-' }}
                                        </div>
                                    @else
                                        <div class="fs-12 fw-bold text-dark">{{ $acc['bank_name'] ?? '-' }}</div>
                                        <div class="fs-11 text-muted font-monospace">
                                            A/C: {{ $acc['account_number'] ?? '-' }} • IFSC: {{ $acc['ifsc_code'] ?? '-' }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td>
                                    @if($w->status === 'approved')
                                        <span class="status-badge bg-soft-success text-success">
                                            <i class="feather-check-circle me-1"></i>Approved / Paid
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

                                <!-- Settlement info -->
                                <td>
                                    @if($w->status === 'approved')
                                        <div class="fs-12 fw-bold text-success font-monospace">UTR: {{ $w->admin_transaction_id }}</div>
                                        @if($w->admin_remarks)
                                            <div class="fs-11 text-muted">{{ $w->admin_remarks }}</div>
                                        @endif
                                    @elseif($w->status === 'rejected')
                                        <div class="fs-12 text-danger">{{ $w->admin_remarks }}</div>
                                    @else
                                        <span class="text-muted fs-12">Awaiting Action</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="text-end pe-4">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        @if($w->status === 'pending')
                                            <!-- Review & Process Page Button -->
                                            <a href="{{ route('admin.withdrawals.show', $w->id) }}" class="btn btn-xs btn-primary rounded-pill px-3 py-1 fs-11 fw-semibold shadow-sm">
                                                <i class="feather-check-square me-1"></i>Review & Process
                                            </a>
                                        @else
                                            <!-- View Details Page Button -->
                                            <a href="{{ route('admin.withdrawals.show', $w->id) }}" class="btn btn-xs btn-light border rounded-pill px-3 py-1 fs-11 text-muted" title="View Details">
                                                <i class="feather-eye me-1"></i>View Details
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted py-4">
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                            <i class="feather-arrow-up-circle fs-24 text-secondary opacity-50"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">No Withdrawal Requests Found</h6>
                                        <p class="fs-12 text-muted mb-0">Delivery partner and restaurant withdrawal requests will appear here for approval.</p>
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

@extends('layouts.admin.main')

@section('title', 'Payment Transactions - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Payment Transactions</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Payment Transactions</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-light-brand">
                    <i class="feather-refresh-cw me-2"></i>Refresh
                </a>
            </div>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Summary KPI Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="feather-credit-card fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted text-uppercase fw-semibold">Total Transactions</div>
                            <div class="fs-18 fw-bold text-dark">{{ number_format($totalTransactions) }}</div>
                            <small class="text-muted fs-11">{{ $tableBookingsCount }} Table Reservations</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-soft-success text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="feather-dollar-sign fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted text-uppercase fw-semibold">Gross Volume</div>
                            <div class="fs-18 fw-bold text-dark">{{ $currencySymbol }}{{ number_format($totalVolume, 2) }}</div>
                            <small class="text-muted fs-11">Total paid payments</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-soft-danger text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="feather-pie-chart fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted text-uppercase fw-semibold">Admin Share (Revenue)</div>
                            <div class="fs-18 fw-bold text-danger">{{ $currencySymbol }}{{ number_format($totalAdminShare, 2) }}</div>
                            <small class="text-muted fs-11">Cover charge &amp; commissions</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-soft-warning text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="feather-user-check fs-4"></i>
                        </div>
                        <div>
                            <div class="fs-12 text-muted text-uppercase fw-semibold">Restaurant Share (Payouts)</div>
                            <div class="fs-18 fw-bold text-warning">{{ $currencySymbol }}{{ number_format($totalRestaurantShare, 2) }}</div>
                            <small class="text-muted fs-11">Restaurant distributed funds</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="feather-filter text-primary"></i> Filter &amp; Search Transactions
                </h6>
                @if(request()->anyFilled(['search', 'type', 'payment_status', 'restaurant_id', 'date_from', 'date_to']))
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-light text-danger">
                        <i class="feather-x me-1"></i> Clear Filters
                    </a>
                @endif
            </div>
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.transactions.index') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fs-12 fw-semibold text-muted mb-1">Search</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0"><i class="feather-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control form-control-sm border-start-0" placeholder="Txn #, Payment ID, Customer, Phone..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fs-12 fw-semibold text-muted mb-1">Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                <option value="table_booking" {{ request('type') === 'table_booking' ? 'selected' : '' }}>Table Booking</option>
                                <option value="order" {{ request('type') === 'order' ? 'selected' : '' }}>Food Order</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fs-12 fw-semibold text-muted mb-1">Status</label>
                            <select name="payment_status" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fs-12 fw-semibold text-muted mb-1">Restaurant</label>
                            <select name="restaurant_id" class="form-select form-select-sm">
                                <option value="">All Restaurants</option>
                                @foreach($restaurants as $r)
                                    <option value="{{ $r->id }}" {{ request('restaurant_id') == $r->id ? 'selected' : '' }}>{{ $r->restaurant_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-8">
                            <div class="row g-1">
                                <div class="col-6">
                                    <label class="form-label fs-12 fw-semibold text-muted mb-1">From Date</label>
                                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fs-12 fw-semibold text-muted mb-1">To Date</label>
                                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end mt-2">
                            <button type="submit" class="btn btn-sm btn-primary px-3">
                                <i class="feather-filter me-1"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Transactions List Table -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold">All Payment Transactions</h5>
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fs-12">
                    {{ $transactions->total() }} Total Records
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Transaction Details</th>
                                <th>Booking / Reference</th>
                                <th>Customer</th>
                                <th>Restaurant</th>
                                <th class="text-end">Total Amount</th>
                                <th class="text-center">Admin Share (%)</th>
                                <th class="text-center">Restaurant Share (%)</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $txn)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded bg-soft-primary text-primary p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                @if($txn->type === 'table_booking')
                                                    <i class="feather-calendar fs-14"></i>
                                                @else
                                                    <i class="feather-shopping-bag fs-14"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.transactions.show', $txn->id) }}" class="fw-bold text-dark text-decoration-none">
                                                    {{ $txn->transaction_number }}
                                                </a>
                                                <div class="fs-11 text-muted">
                                                    {{ $txn->created_at ? $txn->created_at->format('d M Y, h:i A') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @if($txn->type === 'table_booking')
                                            <div>
                                                <span class="badge bg-soft-info text-info fs-11">Booking #{{ $txn->booking_id }}</span>
                                                @if($txn->book_date)
                                                    <div class="fs-12 text-dark mt-1">
                                                        <i class="feather-clock text-muted me-1"></i>{{ \Carbon\Carbon::parse($txn->book_date)->format('d M Y') }} {{ $txn->book_time ? '(' . \Carbon\Carbon::parse($txn->book_time)->format('h:i A') . ')' : '' }}
                                                    </div>
                                                @endif
                                                @if($txn->guests)
                                                    <small class="text-muted">{{ $txn->guests }} {{ Str::plural('Guest', $txn->guests) }}</small>
                                                @endif
                                            </div>
                                        @elseif($txn->order_id)
                                            <span class="badge bg-soft-secondary text-dark fs-11">Order #{{ $txn->order_id }}</span>
                                        @else
                                            <span class="text-muted fs-12">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="fw-semibold text-dark">{{ $txn->customer_name ?: ($txn->customer?->name ?? 'Guest Customer') }}</div>
                                        <div class="fs-11 text-muted">{{ $txn->phone ?: ($txn->customer?->phone ?? '-') }}</div>
                                    </td>

                                    <td>
                                        <div class="fw-semibold text-dark">{{ $txn->restaurant?->restaurant_name ?? 'N/A' }}</div>
                                        <div class="fs-11 text-muted">{{ $txn->restaurant?->city?->name ?? $txn->restaurant?->address ?? '' }}</div>
                                    </td>

                                    <td class="text-end">
                                        <div class="fs-14 fw-bold text-dark">
                                            {{ $currencySymbol }}{{ number_format($txn->total_amount, 2) }}
                                        </div>
                                        <small class="text-muted fs-11">Cover Charge</small>
                                    </td>

                                    <td class="text-center">
                                        <div class="badge bg-soft-danger text-danger fs-12 fw-bold">
                                            {{ $currencySymbol }}{{ number_format($txn->admin_amount, 2) }}
                                        </div>
                                        <div class="fs-11 text-muted mt-1">({{ number_format($txn->admin_share_percent, 1) }}%)</div>
                                    </td>

                                    <td class="text-center">
                                        <div class="badge bg-soft-warning text-warning fs-12 fw-bold">
                                            {{ $currencySymbol }}{{ number_format($txn->restaurant_amount, 2) }}
                                        </div>
                                        <div class="fs-11 text-muted mt-1">({{ number_format($txn->restaurant_share_percent, 1) }}%)</div>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-soft-secondary text-uppercase fs-11 text-dark">
                                                {{ $txn->payment_gateway ?: $txn->payment_method }}
                                            </span>
                                        </div>
                                        @if($txn->payment_id)
                                            <div class="fs-11 text-muted font-monospace mt-1 text-truncate" style="max-width: 130px;" title="{{ $txn->payment_id }}">
                                                {{ $txn->payment_id }}
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @if($txn->payment_status === 'paid' || $txn->status === 'success')
                                            <span class="badge bg-soft-success text-success px-2 py-1 fs-11">
                                                <i class="feather-check-circle me-1"></i> Paid
                                            </span>
                                        @elseif($txn->payment_status === 'pending')
                                            <span class="badge bg-soft-warning text-warning px-2 py-1 fs-11">
                                                <i class="feather-clock me-1"></i> Pending
                                            </span>
                                        @elseif($txn->payment_status === 'failed')
                                            <span class="badge bg-soft-danger text-danger px-2 py-1 fs-11">
                                                <i class="feather-x-circle me-1"></i> Failed
                                            </span>
                                        @elseif($txn->payment_status === 'refunded')
                                            <span class="badge bg-soft-dark text-white px-2 py-1 fs-11">
                                                <i class="feather-rotate-ccw me-1"></i> Refunded
                                            </span>
                                        @else
                                            <span class="badge bg-soft-secondary text-dark px-2 py-1 fs-11">
                                                {{ ucfirst($txn->payment_status) }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.transactions.show', $txn->id) }}" class="btn btn-sm btn-icon btn-light" title="View Transaction Breakdown">
                                            <i class="feather-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="feather-inbox fs-1 text-muted mb-3 d-block"></i>
                                            <h6 class="fw-bold text-muted">No Payment Transactions Found</h6>
                                            <p class="text-muted fs-12 mb-0">No transaction records match your selected filter criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

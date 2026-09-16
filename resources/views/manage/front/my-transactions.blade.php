@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">My Transactions</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Transactions</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- profile section starts -->
    <section class="profile-section section-b-space">
        <div class="container">
            <div class="row g-3">
                @include('manage.front.partials.profile-sidebar')
                <div class="col-lg-9">
                    <div class="change-profile-content bg-white p-4 rounded-4 shadow-sm">
                        <div class="title mb-4 border-bottom pb-3">
                            <h3 class="mb-0 fw-bold">Transaction History</h3>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle custom-table">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Transaction Info</th>
                                        <th>Details</th>
                                        <th>Method</th>
                                        <th>Amount</th>
                                        <th class="text-end pe-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $txn)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark">{{ $txn->transaction_number }}</div>
                                            <div class="text-muted small">{{ $txn->paid_at ? $txn->paid_at->format('d M Y, h:i A') : $txn->created_at->format('d M Y, h:i A') }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold small text-truncate" style="max-width: 250px;" title="{{ $txn->note }}">{{ $txn->note ?: 'Transaction' }}</div>
                                            @if($txn->order_id)
                                                <div class="text-muted small mt-1">Order #{{ $txn->order_id }} @if($txn->restaurant) &bull; {{ $txn->restaurant->restaurant_name }} @endif</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark fw-medium border shadow-sm text-uppercase">
                                                <i class="ri-bank-card-line me-1"></i>{{ str_replace('_', ' ', $txn->payment_method ?: 'N/A') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-bold" style="color: var(--theme-color);">{{ $currencySymbol }}{{ number_format($txn->total_amount, 2) }}</div>
                                        </td>
                                        <td class="text-end pe-3">
                                            @php
                                                $badgeClass = 'bg-secondary';
                                                if ($txn->status === 'success' || $txn->status === 'paid') {
                                                    $badgeClass = 'bg-success';
                                                } elseif ($txn->status === 'failed') {
                                                    $badgeClass = 'bg-danger';
                                                } elseif ($txn->status === 'pending') {
                                                    $badgeClass = 'bg-warning text-dark';
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }} text-uppercase">{{ $txn->status ?: 'UNKNOWN' }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="ri-exchange-dollar-line" style="font-size: 40px; display: block; margin-bottom: 10px; color: #dee2e6;"></i>
                                                <h6 class="fw-normal text-secondary">You haven't made any transactions yet.</h6>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $transactions->links() }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

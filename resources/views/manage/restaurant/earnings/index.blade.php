@extends('layouts.restaurant.main')

@section('title', 'My Earnings - Restaurant Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">My Earnings</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">My Earnings</li>
            </ul>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Earnings Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="feather-clock"></i>
                        </div>
                        <div>
                            <div class="fs-14 fw-bold text-dark">{{ $currencySymbol }}{{ number_format($stats['todays'], 2) }}</div>
                            <div class="fs-12 text-muted">Today's Earnings</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-soft-info text-info d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="feather-calendar"></i>
                        </div>
                        <div>
                            <div class="fs-14 fw-bold text-dark">{{ $currencySymbol }}{{ number_format($stats['monthly'], 2) }}</div>
                            <div class="fs-12 text-muted">Monthly Earnings</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-soft-success text-success d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="feather-dollar-sign"></i>
                        </div>
                        <div>
                            <div class="fs-14 fw-bold text-dark">{{ $currencySymbol }}{{ number_format($stats['total'], 2) }}</div>
                            <div class="fs-12 text-muted">Total Earnings</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Table -->
        <div class="card stretch stretch-full">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold">
                    <i class="feather-dollar-sign me-2 text-success"></i>Earnings Listing
                </h5>
                <span class="fs-12 text-muted">From the incomes table</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="customerList">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Order</th>
                                <th>Restaurant</th>
                                <th>Food Total</th>
                                <th>Commission %</th>
                                <th>Commission Deducted</th>
                                <th>Net Payout</th>
                                <th>Paid On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $row)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">#{{ $row['order_id'] }}</td>
                                    <td class="fw-semibold text-dark">{{ $row['restaurant'] }}</td>
                                    <td>{{ $currencySymbol }}{{ number_format($row['base'], 2) }}</td>
                                    <td>{{ $row['percentage'] }}%</td>
                                    <td class="text-danger">-{{ $currencySymbol }}{{ number_format(round($row['base'] * $row['percentage'] / 100, 2), 2) }}</td>
                                    <td class="fw-bold text-success">{{ $currencySymbol }}{{ number_format($row['amount'], 2) }}</td>
                                    <td class="text-muted fs-12">{{ $row['paid_at']?->format('d M Y') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No earnings yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(count($rows) > 0)
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="ps-4 fw-bold text-dark">Total Earnings</td>
                                    <td class="fw-bold text-success">{{ $currencySymbol }}{{ number_format($stats['total'], 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
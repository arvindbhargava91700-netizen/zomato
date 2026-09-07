@extends('layouts.admin.main')

@section('title', 'Transaction Details - ' . $transaction->transaction_number)

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Transaction Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.transactions.index') }}">Transactions</a></li>
                <li class="breadcrumb-item">{{ $transaction->transaction_number }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.transactions.index') }}" class="btn btn-light-brand">
                    <i class="feather-arrow-left me-2"></i> Back to List
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="feather-printer me-2"></i> Print Receipt
                </button>
            </div>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="row g-4">
            <!-- Transaction Overview & Payment Details -->
            <div class="col-lg-8">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="card-title mb-1 fw-bold">Transaction #{{ $transaction->transaction_number }}</h5>
                            <small class="text-muted">Recorded on {{ $transaction->created_at ? $transaction->created_at->format('d F Y, h:i:s A') : '-' }}</small>
                        </div>
                        <div>
                            @if($transaction->payment_status === 'paid' || $transaction->status === 'success')
                                <span class="badge bg-soft-success text-success px-3 py-2 rounded-pill fs-12">
                                    <i class="feather-check-circle me-1"></i> Payment Successful
                                </span>
                            @else
                                <span class="badge bg-soft-warning text-warning px-3 py-2 rounded-pill fs-12">
                                    {{ ucfirst($transaction->payment_status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Revenue Split Banner -->
                        <div class="p-3 bg-light rounded-3 mb-4 border">
                            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
                                <i class="feather-pie-chart text-danger"></i> Cover Charge Distribution Summary
                            </h6>
                            <div class="row g-3 text-center">
                                <div class="col-md-4">
                                    <div class="p-3 bg-white rounded shadow-sm border">
                                        <div class="text-muted fs-12 text-uppercase fw-semibold">Gross Cover Charge</div>
                                        <div class="fs-20 fw-bold text-dark mt-1">{{ $currencySymbol }}{{ number_format($transaction->total_amount, 2) }}</div>
                                        <span class="badge bg-soft-primary text-primary fs-11 mt-1">100% Paid</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-white rounded shadow-sm border">
                                        <div class="text-muted fs-12 text-uppercase fw-semibold">Admin Share (Commission)</div>
                                        <div class="fs-20 fw-bold text-danger mt-1">{{ $currencySymbol }}{{ number_format($transaction->admin_amount, 2) }}</div>
                                        <span class="badge bg-soft-danger text-danger fs-11 mt-1">{{ number_format($transaction->admin_share_percent, 1) }}% Share</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-white rounded shadow-sm border">
                                        <div class="text-muted fs-12 text-uppercase fw-semibold">Restaurant Share (Payout)</div>
                                        <div class="fs-20 fw-bold text-warning mt-1">{{ $currencySymbol }}{{ number_format($transaction->restaurant_amount, 2) }}</div>
                                        <span class="badge bg-soft-warning text-warning fs-11 mt-1">{{ number_format($transaction->restaurant_share_percent, 1) }}% Share</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Payment Gateway Details -->
                        <h6 class="fw-bold mb-3 text-dark">Payment Gateway Details</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th class="bg-light text-muted" style="width: 30%;">Payment Method</th>
                                        <td class="fw-semibold">{{ strtoupper($transaction->payment_method) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted">Payment Gateway</th>
                                        <td class="fw-semibold text-uppercase">{{ $transaction->payment_gateway ?: 'Razorpay' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted">Gateway Payment ID</th>
                                        <td class="font-monospace text-primary fw-bold">{{ $transaction->payment_id ?: 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted">Order / Reference ID</th>
                                        <td class="font-monospace">{{ $transaction->order_reference_id ?: 'N/A' }}</td>
                                    </tr>
                                    @if($transaction->signature)
                                        <tr>
                                            <th class="bg-light text-muted">Signature Hash</th>
                                            <td class="font-monospace fs-11 text-muted text-break">{{ $transaction->signature }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th class="bg-light text-muted">Paid At</th>
                                        <td>{{ $transaction->paid_at ? $transaction->paid_at->format('d M Y, h:i:s A') : '-' }}</td>
                                    </tr>
                                    @if($transaction->note)
                                        <tr>
                                            <th class="bg-light text-muted">Payment Note</th>
                                            <td class="text-muted">{{ $transaction->note }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        @if($transaction->meta_data)
                            <h6 class="fw-bold mb-2 text-dark">Additional Meta Information</h6>
                            <div class="bg-light p-3 rounded font-monospace fs-12 text-muted border">
                                <pre class="mb-0">{{ json_encode($transaction->meta_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Booking / Related Entities Sidebar -->
            <div class="col-lg-4">
                <!-- Related Booking Card -->
                @if($transaction->booking)
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header border-bottom py-3">
                            <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                                <i class="feather-calendar text-primary"></i> Linked Table Reservation
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <ul class="list-group list-group-flush fs-13">
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span class="text-muted">Booking ID:</span>
                                    <span class="fw-bold">#{{ $transaction->booking->id }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span class="text-muted">Reservation Date:</span>
                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($transaction->booking->book_date)->format('d M Y') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span class="text-muted">Time Slot:</span>
                                    <span class="fw-semibold">{{ $transaction->booking->book_time ? \Carbon\Carbon::parse($transaction->booking->book_time)->format('h:i A') : '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span class="text-muted">Party Size:</span>
                                    <span class="fw-semibold">{{ $transaction->booking->guests }} Guests</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span class="text-muted">Assigned Table:</span>
                                    <span class="fw-semibold">{{ $transaction->booking->table?->table_number ?? 'Auto-assigned' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span class="text-muted">Applied Offer:</span>
                                    <span class="badge bg-soft-info text-info">{{ $transaction->booking->diningOffer?->title ?? 'None' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span class="text-muted">Booking Status:</span>
                                    <span class="badge bg-soft-success text-success text-uppercase">{{ $transaction->booking->status }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Restaurant & Customer Information -->
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header border-bottom py-3">
                        <h6 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
                            <i class="feather-map-pin text-primary"></i> Restaurant &amp; Customer
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="text-muted fs-11 text-uppercase fw-bold">Restaurant</label>
                            <div class="fw-bold text-dark fs-14">{{ $transaction->restaurant?->restaurant_name ?? 'N/A' }}</div>
                            <small class="text-muted d-block">{{ $transaction->restaurant?->phone }}</small>
                            <small class="text-muted d-block">{{ $transaction->restaurant?->address }}</small>
                        </div>
                        <hr class="my-2">
                        <div>
                            <label class="text-muted fs-11 text-uppercase fw-bold">Customer Details</label>
                            <div class="fw-bold text-dark fs-14">{{ $transaction->customer_name ?: ($transaction->customer?->name ?? 'Guest') }}</div>
                            <small class="text-muted d-block"><i class="feather-phone me-1"></i>{{ $transaction->phone ?: ($transaction->customer?->phone ?? '-') }}</small>
                            @if($transaction->customer?->email)
                                <small class="text-muted d-block"><i class="feather-mail me-1"></i>{{ $transaction->customer->email }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

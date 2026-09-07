@extends('layouts.delivery-partner.main')

@section('title', 'Delivery Partner Dashboard - Zomato')

@push('styles')
<style>
    .stepper-line {
        width: 48px;
        height: 2px;
        background: rgba(255,255,255,0.35);
        border-radius: 2px;
        flex-shrink: 0;
    }
    .text-muted-uppercase {
        font-size: 13px;
        letter-spacing: 0.3px;
    }
    .upload-zone {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 92px;
        padding: 14px;
        border: 2px dashed #d0d5dd;
        border-radius: 12px;
        background: #fafbfc;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
        width: 100%;
    }
    .upload-zone:hover {
        border-color: #cb202d;
        background: #fff5f5;
        color: #cb202d;
    }
    .upload-zone i {
        font-size: 26px;
        color: #cb202d;
    }
    .upload-zone.uploaded {
        border-color: #2ba842;
        background: #f2fbf4;
        color: #1e7e34;
    }
    .upload-zone.uploaded i {
        color: #2ba842;
    }
    .form-label {
        font-size: 13px;
        color: #475569;
        margin-bottom: 6px;
    }
    .input-group-text {
        border-radius: 10px 0 0 10px;
    }
    .form-select, .form-control {
        border-radius: 10px;
    }
    .input-group > .form-control,
    .input-group > .form-select {
        border-radius: 0 10px 10px 0;
    }
</style>
@endpush

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Delivery Partner Dashboard</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('delivery-partner.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
            </ul>
        </div>
    </div>

    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- KYC Status Card -->
        <div class="card stretch mb-4 border-0 overflow-hidden rounded-3 shadow-sm">
            <div class="card-body p-0 position-relative" style="background: linear-gradient(135deg, #2d1b3a 0%, #4a2545 45%, #cb202d 100%);">
                <div class="p-4 p-md-5 position-relative" style="z-index: 2;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center shadow"
                                 style="width: 64px; height: 64px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(4px);">
                                <i class="feather-shield text-white fs-3"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-white mb-1">KYC Verification</h4>
                                @if($partner->kyc_status === 'approved')
                                    <p class="text-white-50 mb-0 fs-14">
                                        Your KYC is approved. You can update your profile details anytime.
                                    </p>
                                @elseif($partner->kyc_status === 'rejected')
                                    <p class="text-white-50 mb-0 fs-14">
                                        Your KYC was rejected by the admin. Please review the remark and resubmit.
                                    </p>
                                @elseif($partner->kyc_status === 'pending')
                                    <p class="text-white-50 mb-0 fs-14">
                                        Your KYC is under review. You will be notified once the admin makes a decision.
                                    </p>
                                @else
                                    <p class="text-white-50 mb-0 fs-14">
                                        Complete the KYC verification to activate your account and start receiving delivery requests.
                                    </p>
                                @endif

                                @if($partner->kyc_status === 'approved' && $partner->kyc_remark)
                                    <p class="text-white mb-0 fs-13 mt-2">
                                        <strong>Admin Remark:</strong> {{ $partner->kyc_remark }}
                                    </p>
                                @elseif($partner->kyc_status === 'rejected' && $partner->kyc_rejected_reason)
                                    <p class="text-white mb-0 fs-13 mt-2">
                                        <strong>Admin Remark:</strong> {{ $partner->kyc_rejected_reason }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-2">
                            @if($partner->kyc_status === 'approved')
                                <span class="badge bg-white text-success rounded-pill px-3 py-2 fs-12 fw-semibold shadow-sm">
                                    <i class="feather-check-circle me-1"></i>Approved
                                </span>
                            @elseif($partner->kyc_status === 'rejected')
                                <span class="badge bg-white text-danger rounded-pill px-3 py-2 fs-12 fw-semibold shadow-sm">
                                    <i class="feather-x-circle me-1"></i>Rejected
                                </span>
                            @elseif($partner->kyc_status === 'pending')
                                <span class="badge bg-white text-warning rounded-pill px-3 py-2 fs-12 fw-semibold shadow-sm">
                                    <i class="feather-clock me-1"></i>Pending Approval
                                </span>
                            @else
                                <span class="badge bg-white text-secondary rounded-pill px-3 py-2 fs-12 fw-semibold shadow-sm">
                                    <i class="feather-edit me-1"></i>Not Submitted
                                </span>
                            @endif
                            <a href="{{ route('delivery-partner.kyc') }}" class="btn text-white fw-semibold px-4 py-2 shadow-sm" style="background: linear-gradient(90deg, #cb202d 0%, #e0555f 100%); border: none; border-radius: 8px;">
                                <i class="feather-edit me-1"></i> Manage KYC
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->status === 'active')
            <!-- Active Partner Dashboard Content -->
            <!-- Welcome Banner -->
            <div class="card stretch mb-4 border-0 shadow-sm rounded-3">
                <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 60px; height: 60px; background-color: #cb202d;">
                            {{ strtoupper(substr($partner->name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">Welcome, {{ $partner->name }}</h4>
                            <p class="text-muted mb-0 fs-12"><i class="feather-mail me-1"></i>{{ $partner->email }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-soft-success text-success px-3 py-2 rounded-pill fs-12 fw-semibold">
                            <i class="feather-truck me-1"></i>Active Delivery Partner
                        </span>
                    </div>
                </div>
            </div>

            <!-- New Delivery Requests -->
            @if($pendingRequests->isNotEmpty())
                <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 fw-bold">
                            <i class="feather-bell me-2 text-warning"></i>New Delivery Requests
                            <span class="badge bg-danger text-white ms-1">{{ $pendingRequests->count() }}</span>
                        </h5>
                        <a href="{{ route('delivery-partner.orders.available') }}" class="btn btn-sm btn-light border">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="customerList">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Order</th>
                                        <th>Restaurant</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Expires In</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingRequests as $request)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">#{{ $request->order->id }}</td>
                                            <td>{{ $request->order->restaurant?->restaurant_name ?? '-' }}</td>
                                            <td>
                                                @foreach ($request->order->items->take(2) as $item)
                                                    <span class="fs-12">{{ $item->name }} ×{{ $item->qty }};</span>
                                                @endforeach
                                            </td>
                                            <td class="fw-bold text-dark">{{ $currencySymbol }}{{ number_format($request->order->total, 2) }}</td>
                                            <td class="text-muted fs-12">
                                                <span class="countdown" data-expires="{{ $request->expires_at?->timestamp }}">
                                                    {{ $request->expires_at ? $request->expires_at->diffForHumans(now()) : '-' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <form method="POST" action="{{ route('delivery-partner.requests.accept', $request->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success text-white fw-semibold"
                                                        style="background-color:#2ba842;border:none;">Accept</button>
                                                </form>
                                                <form method="POST" action="{{ route('delivery-partner.requests.reject', $request->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light border text-danger fw-semibold">Reject</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Active Delivery -->
            @if($activeDelivery)
                <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-circle">
                                <i class="feather-truck fs-3"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark">Active Delivery #{{ $activeDelivery->id }}</h5>
                                <span class="{{ \App\Models\Order::statusBadge($activeDelivery->status) }} fs-12">
                                    {{ \App\Models\Order::statusLabel($activeDelivery->status) }}
                                </span>
                                <span class="text-muted fs-12 ms-2 d-block">
                                    {{ $activeDelivery->restaurant?->restaurant_name }} →
                                    {{ $activeDelivery->user?->name }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('delivery-partner.orders.show', $activeDelivery->id) }}"
                            class="btn btn-primary fw-semibold">Continue Delivery</a>
                    </div>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="row g-3 mb-4">
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fs-12 text-muted fw-semibold uppercase">Today's Deliveries</span>
                                    <h3 class="fw-bold text-dark mb-0 mt-1">{{ $stats['todays_deliveries'] }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-circle">
                                    <i class="feather-truck fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fs-12 text-muted fw-semibold uppercase">Pending Pickups</span>
                                    <h3 class="fw-bold text-warning mb-0 mt-1">{{ $stats['pending_pickups'] }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-circle">
                                    <i class="feather-clock fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fs-12 text-muted fw-semibold uppercase">Completed Deliveries</span>
                                    <h3 class="fw-bold text-success mb-0 mt-1">{{ $stats['completed_deliveries'] }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-success text-success rounded-circle">
                                    <i class="feather-check-circle fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fs-12 text-muted fw-semibold uppercase">Today's Earnings</span>
                                    <h3 class="fw-bold text-success mb-0 mt-1">{{ $currencySymbol }}{{ number_format($stats['todays_earnings'], 2) }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-success text-success rounded-circle">
                                    <i class="feather-dollar-sign fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fs-12 text-muted fw-semibold uppercase">Monthly Earnings</span>
                                    <h3 class="fw-bold text-primary mb-0 mt-1">{{ $currencySymbol }}{{ number_format($stats['monthly_earnings'], 2) }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-circle">
                                    <i class="feather-trending-up fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fs-12 text-muted fw-semibold uppercase">Total Earnings</span>
                                    <h3 class="fw-bold text-success mb-0 mt-1">{{ $currencySymbol }}{{ number_format($stats['total_earnings'], 2) }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-danger text-danger rounded-circle">
                                    <i class="feather-credit-card fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Deliveries Widget -->
            <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0 fw-bold">Recent Deliveries</h5>
                    <span class="badge bg-light text-muted">Latest Live Activity</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="customerList">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Order ID</th>
                                    <th>Customer Name</th>
                                    <th>Address</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Settlement</th>
                                    <th class="text-end pe-4">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentDeliveries as $order)
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark">
                                            <a href="{{ route('delivery-partner.orders.show', $order->id) }}" class="text-decoration-none">#{{ $order->id }}</a>
                                        </td>
                                        <td>{{ $order->user?->name ?? '-' }}</td>
                                        <td class="text-muted fs-12">{{ Str::limit($order->address?->address ?? 'No address', 25) }}</td>
                                        <td class="fw-bold">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</td>
                                        <td>
                                            <span class="{{ \App\Models\Order::statusBadge($order->status) }} fs-12">
                                                {{ \App\Models\Order::statusLabel($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->isCodOrder() && in_array($order->status, ['delivered', 'completed']))
                                                @php $settlement = $order->latestCodSettlement; @endphp
                                                @if ($settlement)
                                                    <span class="{{ \App\Models\CodSettlement::statusBadge($settlement->status) }} fs-12">
                                                        {{ \App\Models\CodSettlement::statusLabel($settlement->status) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-light text-muted fs-12">Not Submitted</span>
                                                @endif
                                            @else
                                                <span class="text-muted fs-12">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4 text-muted fs-12">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                @empty
                                   
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

@push('scripts')
    <script>
        (function () {
            var els = document.querySelectorAll('.countdown');
            function tick() {
                els.forEach(function (el) {
                    var exp = parseInt(el.getAttribute('data-expires'), 10) * 1000;
                    var diff = exp - Date.now();
                    if (diff <= 0) { el.textContent = 'Expired'; return; }
                    var m = Math.floor(diff / 60000);
                    var s = Math.floor((diff % 60000) / 1000);
                    el.textContent = m + 'm ' + s + 's';
                });
            }
            tick();
            setInterval(tick, 1000);
        })();
    </script>
@endpush
@extends('layouts.admin.main')

@section('title', 'Settlement #' . $settlement->id . ' - Admin Dashboard')

@section('styles')
<style>
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }

    /* Info Box Styling */
    .info-box {
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        cursor: default;
        animation: slideInUp 0.6s ease-out both;
    }

    .info-box[style*="animation-delay: 0.1s"] { animation-delay: 0.1s; }
    .info-box[style*="animation-delay: 0.2s"] { animation-delay: 0.2s; }
    .info-box[style*="animation-delay: 0.3s"] { animation-delay: 0.3s; }
    .info-box[style*="animation-delay: 0.4s"] { animation-delay: 0.4s; }
    .info-box[style*="animation-delay: 0.5s"] { animation-delay: 0.5s; }
    .info-box[style*="animation-delay: 0.6s"] { animation-delay: 0.6s; }

    .info-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
        transition: left 0.4s ease;
        pointer-events: none;
    }

    .info-box:hover {
        transform: translateX(6px) translateY(-2px) !important;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12) !important;
    }

    .info-box:hover::before {
        left: 100%;
    }

    /* Card Animations */
    .card {
        animation: fadeIn 0.5s ease-out;
        transition: all 0.3s ease;
    }

    /* Code Block Styling */
    code {
        font-family: 'Fira Code', 'Monaco', 'Menlo', 'Ubuntu Mono', monospace !important;
        font-weight: 500 !important;
        letter-spacing: 0.5px;
    }

    /* Icon Styling */
    .info-icon {
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }

    .info-box:hover .info-icon {
        transform: scale(1.1) rotate(5deg);
    }

    /* Payment Proof Card */
    .payment-proof-card {
        animation: slideInUp 0.6s ease-out;
    }

    /* Section Header */
    .section-header {
        letter-spacing: -0.3px;
        color: #1f2937;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">COD Settlement #{{ $settlement->id }}</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.settlements.index') }}">COD Settlements</a></li>
                <li class="breadcrumb-item">#{{ $settlement->id }}</li>
            </ul>
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

        <!-- Status Banner -->
        <div class="card mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 56px; height: 56px; background: rgba(203,32,45,0.1);">
                        <i class="feather-dollar-sign text-danger fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">Settlement #{{ $settlement->id }} &middot; Order #{{ $settlement->order_id }}</h4>
                        <span class="{{ \App\Models\CodSettlement::statusBadge($settlement->status) }} fs-12">
                            {{ \App\Models\CodSettlement::statusLabel($settlement->status) }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.settlements.index') }}" class="btn btn-light border fw-semibold">
                    <i class="feather-arrow-left me-1"></i>Back to Settlements
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left: Proof / Details -->
            <div class="col-xl-7 d-flex flex-column">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4" style="animation: slideInUp 0.6s ease-out;">
                    <div class="card-header border-bottom py-3" style="background: linear-gradient(135deg, #fff 0%, #f9fafb 100%);">
                        <h5 class="card-title mb-0 fw-bold" style="letter-spacing: -0.3px;"><i class="feather-file-text me-2" style="color: #cb202d;"></i>Payment Proof</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Amount Section -->
                            <div class="col-md-6">
                                <div class="info-box p-3 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-left: 4px solid #2ba842; animation-delay: 0.1s;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="info-icon feather-dollar-sign fw-bold" style="width: 28px; height: 28px; background: linear-gradient(135deg, #2ba842 0%, #1ea234 100%); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px;"></i>
                                        <span class="fs-12 text-muted fw-bold text-uppercase" style="letter-spacing: 0.8px;">Amount Collected</span>
                                    </div>
                                    <div class="fs-5 fw-bold" style="background: linear-gradient(135deg, #2ba842 0%, #1ea234 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                        {{ $currencySymbol }}{{ number_format($settlement->amount, 2) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Transaction ID Section -->
                            <div class="col-md-6">
                                <div class="info-box p-3 rounded-3" style="background: linear-gradient(135deg, #f9f5ff 0%, #ede9fe 100%); border-left: 4px solid #7c3aed; animation-delay: 0.2s;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="info-icon feather-hash fw-bold" style="width: 28px; height: 28px; background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px;"></i>
                                        <span class="fs-12 text-muted fw-bold text-uppercase" style="letter-spacing: 0.8px;">Transaction ID</span>
                                    </div>
                                    <div class="fs-6 fw-semibold text-dark" style="word-break: break-all;">
                                        <code style="background: rgba(124, 58, 237, 0.1); padding: 4px 8px; border-radius: 4px; color: #7c3aed;">{{ $settlement->transaction_id ?: '-' }}</code>
                                    </div>
                                </div>
                            </div>

                            <!-- Submitted By Section -->
                            <div class="col-md-6">
                                <div class="info-box p-3 rounded-3" style="background: linear-gradient(135deg, #fef3f2 0%, #fee2e0 100%); border-left: 4px solid #f43f5e; animation-delay: 0.3s;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="info-icon feather-user fw-bold" style="width: 28px; height: 28px; background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px;"></i>
                                        <span class="fs-12 text-muted fw-bold text-uppercase" style="letter-spacing: 0.8px;">Submitted By</span>
                                    </div>
                                    <div class="fs-6 fw-semibold text-dark">{{ $settlement->deliveryPartner?->name ?? '-' }}</div>
                                    <div class="fs-12 text-muted mt-1">{{ $settlement->deliveryPartner?->phone ?? 'No phone' }}</div>
                                </div>
                            </div>

                            <!-- Submitted At Section -->
                            <div class="col-md-6">
                                <div class="info-box p-3 rounded-3" style="background: linear-gradient(135deg, #fef5e7 0%, #fdeaa8 100%); border-left: 4px solid #f59e0b; animation-delay: 0.4s;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="info-icon feather-calendar fw-bold" style="width: 28px; height: 28px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px;"></i>
                                        <span class="fs-12 text-muted fw-bold text-uppercase" style="letter-spacing: 0.8px;">Submitted At</span>
                                    </div>
                                    <div class="fs-6 fw-semibold text-dark">{{ $settlement->submitted_at ? $settlement->submitted_at->format('d M Y') : '-' }}</div>
                                    <div class="fs-12 text-muted mt-1">{{ $settlement->submitted_at ? $settlement->submitted_at->format('h:i A') : '' }}</div>
                                </div>
                            </div>

                            <!-- Reviewed By Section -->
                            @if($settlement->reviewed_at)
                                <div class="col-md-6">
                                    <div class="info-box p-3 rounded-3" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 4px solid #22c55e; animation-delay: 0.5s;">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="info-icon feather-check-circle fw-bold" style="width: 28px; height: 28px; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px;"></i>
                                            <span class="fs-12 text-muted fw-bold text-uppercase" style="letter-spacing: 0.8px;">Reviewed By</span>
                                        </div>
                                        <div class="fs-6 fw-semibold text-dark">{{ $settlement->reviewer?->name ?? '-' }}</div>
                                        <div class="fs-12 text-muted mt-1">{{ $settlement->reviewed_at->format('d M Y, h:i A') }}</div>
                                    </div>
                                </div>
                            @endif

                            <!-- Remark Section -->
                            <div class="col-12">
                                <div class="info-box p-3 rounded-3" style="background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); border-left: 4px solid #6366f1; animation-delay: 0.6s;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="info-icon feather-message-square fw-bold" style="width: 28px; height: 28px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px;"></i>
                                        <span class="fs-12 text-muted fw-bold text-uppercase" style="letter-spacing: 0.8px;">Remark</span>
                                    </div>
                                    <p class="mb-0 text-dark" style="line-height: 1.6;">
                                        @if($settlement->remark)
                                            {{ $settlement->remark }}
                                        @else
                                            <span class="text-muted fst-italic">No remark provided</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 flex-fill">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="feather-camera me-2 text-danger"></i>Payment Screenshot</h5>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                        @if($settlement->screenshot_url)
                            <a href="{{ $settlement->screenshot_url }}" target="_blank">
                                <img src="{{ $settlement->screenshot_url }}" alt="Payment screenshot"
                                    class="rounded-3 border" style="max-width: 100%; height: auto; max-height: 420px; object-fit: contain;">
                            </a>
                            <div class="mt-3">
                                <a href="{{ $settlement->screenshot_url }}" target="_blank" class="btn btn-sm btn-light border text-info fw-semibold">
                                    <i class="feather-maximize me-1"></i>Open Full Image
                                </a>
                            </div>
                        @else
                            <div class="text-muted py-4">
                                <i class="feather-image display-5 d-block mb-2 text-secondary"></i>
                                No screenshot uploaded for this settlement.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right: Order info + Review Action -->
            <div class="col-xl-5 d-flex flex-column">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="feather-shopping-cart me-2 text-danger"></i>Order #{{ $settlement->order_id }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted fs-13">Restaurant</span>
                            <span class="fw-semibold text-dark">{{ $settlement->order?->restaurant?->restaurant_name ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted fs-13">Customer</span>
                            <span class="fw-semibold text-dark">{{ $settlement->order?->user?->name ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted fs-13">Payment Method</span>
                            <span class="fw-semibold text-dark">{{ ucwords(str_replace('_', ' ', $settlement->order?->payment_method ?? '')) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted fs-13">Payment Status</span>
                            <span class="fw-semibold text-{{ $settlement->order?->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($settlement->order?->payment_status ?? '-') }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted fs-13">Order Total</span>
                            <span class="fw-bold text-dark">{{ $currencySymbol }}{{ number_format($settlement->order?->total ?? 0, 2) }}</span>
                        </div>
                        @if(optional($settlement->order)->promo_code)
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted fs-13">Promo Code</span>
                                <span class="fw-semibold text-dark">
                                    <span class="badge" style="background:#e8f8ee;color:#1f9d4d;font-family:monospace;">{{ $settlement->order->promo_code }}</span>
                                    <span class="text-success ms-1">- {{ $currencySymbol }}{{ number_format($settlement->order->promo_discount ?? 0, 2) }}</span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold"><i class="feather-share-2 me-2 text-danger"></i>Payout Distribution</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-4 pb-2 border-bottom bg-light bg-opacity-50">
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted fs-13">Food Total (Subtotal - Discount)</span>
                                <span class="fw-semibold text-dark">{{ $currencySymbol }}{{ number_format($payout['food_total'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted fs-13">Restaurant Commission ({{ $payout['commission_pct'] }}%)</span>
                                <span class="fw-semibold text-dark">- {{ $currencySymbol }}{{ number_format($payout['commission'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted fs-13">Delivery Charge</span>
                                <span class="fw-semibold text-dark">{{ $currencySymbol }}{{ number_format($payout['delivery_charge'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="text-muted fs-13">Tax / GST</span>
                                <span class="fw-semibold text-dark">{{ $currencySymbol }}{{ number_format($payout['tax'], 2) }}</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="d-flex justify-content-between py-1">
                                <span class="fs-13 fw-semibold text-dark">Restaurant Payout</span>
                                <span class="fw-bold text-primary">{{ $currencySymbol }}{{ number_format($payout['restaurant_payout'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="fs-13 fw-semibold text-dark">Delivery Partner ({{ $payout['partner_share_pct'] }}% of delivery)</span>
                                <span class="fw-bold text-success">{{ $currencySymbol }}{{ number_format($payout['delivery_partner_payout'], 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span class="fs-13 fw-semibold text-dark">Platform / Admin ({{ $payout['platform_share_pct'] }}% + Tax + Commission)</span>
                                <span class="fw-bold text-warning">{{ $currencySymbol }}{{ number_format($payout['platform_payout'], 2) }}</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <span class="fs-13 fw-bold text-dark">Total Distributed</span>
                                <span class="fw-bold text-dark">{{ $currencySymbol }}{{ number_format($payout['restaurant_payout'] + $payout['delivery_partner_payout'] + $payout['platform_payout'], 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($settlement->isPending())
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3 flex-fill">
                        <div class="card-header border-bottom py-3">
                            <h5 class="card-title mb-0 fw-bold"><i class="feather-check-circle me-2 text-danger"></i>Review Settlement</h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <form method="POST" action="{{ route('admin.settlements.confirm', $settlement->id) }}" class="flex-fill d-flex flex-column">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Confirm Remark <span class="text-muted">(optional)</span></label>
                                    <textarea name="remark" rows="3" class="form-control" maxlength="500"
                                        placeholder="Add a remark visible to the delivery partner..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100 text-white fw-semibold mt-auto"
                                    onclick="return confirm('Approve & pay settlement #{{ $settlement->id }}? The payouts above will be distributed.')">
                                    <i class="feather-check me-1"></i>Approve &amp; Pay
                                </button>
                            </form>

                            <hr class="my-3">

                            <form method="POST" action="{{ route('admin.settlements.reject', $settlement->id) }}" id="rejectForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Reject Reason <span class="text-danger">*</span></label>
                                    <textarea name="remark" rows="2" class="form-control" maxlength="500"
                                        placeholder="Reason for rejection (visible to the partner)..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger w-100 text-white fw-semibold"
                                    onclick="return confirm('Reject settlement #{{ $settlement->id }}? The partner can resubmit.')">
                                    <i class="feather-x me-1"></i>Reject Settlement
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3 flex-fill">
                        <div class="card-body d-flex align-items-center">
                            <div class="alert alert-light border mb-0 fs-14 w-100 d-flex align-items-center gap-2">
                                <i class="feather-lock"></i>
                                <span>This settlement has already been reviewed
                                @if($settlement->reviewer?->name)
                                    by <strong>{{ $settlement->reviewer->name }}</strong>
                                @endif
                                on {{ $settlement->reviewed_at?->format('d M Y, h:i A') }}.</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
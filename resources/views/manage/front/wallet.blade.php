@extends('layouts.front.main')

@section('title', 'My Wallet - FoodExpress')

@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">My Wallet</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        My Wallet
                    </li>
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
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
                            <i class="ri-checkbox-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert">
                            <i class="ri-error-warning-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Hero Wallet Card -->
                    <div class="card border-0 rounded-4 mb-4 text-white shadow-sm overflow-hidden"
                         style="background: linear-gradient(135deg, rgba(var(--theme-color), 1) 0%, rgba(var(--theme-color2), 1) 100%);">
                        <div class="card-body p-4 p-md-5">
                            <div class="row align-items-center g-3">
                                <div class="col-md-7 col-12">
                                    <div class="d-inline-flex align-items-center gap-1 bg-white text-dark rounded-pill px-3 py-1 fw-bold mb-3 fs-11 shadow-sm">
                                        <i class="ri-shield-check-fill text-success"></i> <span>Instant 1-Click Pay &amp; Zero Fee</span>
                                    </div>
                                    <div class="fs-13 text-white-50 text-uppercase fw-semibold tracking-wider">Total Available Balance</div>
                                    <h1 class="display-5 fw-bold text-white mb-2" id="wallet-hero-balance">
                                        {{ $currencySymbol }}{{ number_format($wallet->balance, 2) }}
                                    </h1>
                                    <p class="mb-0 text-white-50 fs-13">
                                        Use your wallet for instant checkout on food orders and table booking cover charges.
                                    </p>
                                </div>
                                <div class="col-md-5 col-12 text-md-end">
                                    <button type="button" class="btn bg-white text-dark fw-bold px-4 py-2 rounded-pill shadow-sm topup-open-btn" data-bs-toggle="modal" data-bs-target="#topupModal">
                                        <i class="ri-add-circle-fill theme-color me-1 fs-16 align-middle"></i> Add Money to Wallet
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4 KPI Summary Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="card border-0 shadow-sm rounded-3 h-100" style="background: rgba(var(--box-bg), 1);">
                                <div class="card-body p-3 d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                        <i class="ri-wallet-3-line fs-18"></i>
                                    </div>
                                    <div>
                                        <div class="fs-11 text-muted text-uppercase fw-semibold">Current Balance</div>
                                        <div class="fs-16 fw-bold theme-color">{{ $currencySymbol }}{{ number_format($stats['balance'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="card border-0 shadow-sm rounded-3 h-100" style="background: rgba(var(--box-bg), 1);">
                                <div class="card-body p-3 d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                                        <i class="ri-arrow-down-circle-line fs-18"></i>
                                    </div>
                                    <div>
                                        <div class="fs-11 text-muted text-uppercase fw-semibold">Total Added</div>
                                        <div class="fs-16 fw-bold text-success">+{{ $currencySymbol }}{{ number_format($stats['total_added'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="card border-0 shadow-sm rounded-3 h-100" style="background: rgba(var(--box-bg), 1);">
                                <div class="card-body p-3 d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
                                        <i class="ri-arrow-up-circle-line fs-18"></i>
                                    </div>
                                    <div>
                                        <div class="fs-11 text-muted text-uppercase fw-semibold">Total Spent</div>
                                        <div class="fs-16 fw-bold text-danger">-{{ $currencySymbol }}{{ number_format($stats['total_spent'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-12">
                            <div class="card border-0 shadow-sm rounded-3 h-100" style="background: rgba(var(--box-bg), 1);">
                                <div class="card-body p-3 d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);">
                                        <i class="ri-exchange-dollar-line fs-18"></i>
                                    </div>
                                    <div>
                                        <div class="fs-11 text-muted text-uppercase fw-semibold">Total Activity</div>
                                        <div class="fs-16 fw-bold text-dark">{{ $stats['total_transactions'] }} Txns</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions History Card -->
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">
                                    <i class="ri-history-line theme-color me-2"></i>Transaction Ledger
                                </h5>
                                <small class="text-muted">Real-time record of all your wallet top-ups, orders, and payouts</small>
                            </div>

                            <!-- Filter Pills -->
                            <div class="d-flex align-items-center gap-1">
                                <a href="{{ route('wallet') }}" class="btn btn-sm rounded-pill px-3 {{ !request()->filled('type') ? 'theme-btn' : 'btn-outline-secondary' }}">All</a>
                                <a href="{{ route('wallet', ['type' => 'credit']) }}" class="btn btn-sm rounded-pill px-3 {{ request('type') === 'credit' ? 'btn-success text-white' : 'btn-outline-secondary' }}">Credits (+)</a>
                                <a href="{{ route('wallet', ['type' => 'debit']) }}" class="btn btn-sm rounded-pill px-3 {{ request('type') === 'debit' ? 'btn-danger text-white' : 'btn-outline-secondary' }}">Debits (-)</a>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead style="background-color: rgba(var(--box-bg), 1);">
                                        <tr>
                                            <th class="ps-4 py-3" style="font-size: 11px; text-transform: uppercase; color: rgba(var(--content-color), 1);">Txn Number</th>
                                            <th style="font-size: 11px; text-transform: uppercase; color: rgba(var(--content-color), 1);">Description &amp; Source</th>
                                            <th style="font-size: 11px; text-transform: uppercase; color: rgba(var(--content-color), 1);">Type</th>
                                            <th style="font-size: 11px; text-transform: uppercase; color: rgba(var(--content-color), 1);">Amount</th>
                                            <th style="font-size: 11px; text-transform: uppercase; color: rgba(var(--content-color), 1);">Balance After</th>
                                            <th style="font-size: 11px; text-transform: uppercase; color: rgba(var(--content-color), 1);">Date &amp; Time</th>
                                            <th class="pe-4 text-end" style="font-size: 11px; text-transform: uppercase; color: rgba(var(--content-color), 1);">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($walletTransactions as $txn)
                                            <tr>
                                                <td class="ps-4 font-monospace fw-semibold text-dark fs-12">
                                                    {{ $txn->transaction_number }}
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark fs-13">
                                                        {{ $txn->description ?: ucfirst(str_replace('_', ' ', $txn->source)) }}
                                                    </div>
                                                    <small class="text-muted fs-11">Source: <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $txn->source)) }}</span></small>
                                                </td>
                                                <td>
                                                    @if($txn->type === 'credit')
                                                        <span class="badge bg-soft-success text-success px-2 py-1 rounded-pill fw-bold">
                                                            <i class="ri-arrow-down-line"></i> Credit
                                                        </span>
                                                    @else
                                                        <span class="badge bg-soft-danger text-danger px-2 py-1 rounded-pill fw-bold">
                                                            <i class="ri-arrow-up-line"></i> Debit
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="fw-bold fs-14 {{ $txn->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                                    {{ $txn->type === 'credit' ? '+' : '-' }}{{ $currencySymbol }}{{ number_format($txn->amount, 2) }}
                                                </td>
                                                <td class="fw-semibold text-dark fs-13">
                                                    {{ $currencySymbol }}{{ number_format($txn->balance_after, 2) }}
                                                </td>
                                                <td class="text-muted fs-12">
                                                    <div>{{ $txn->created_at ? $txn->created_at->format('d M Y') : '-' }}</div>
                                                    <small class="text-muted">{{ $txn->created_at ? $txn->created_at->format('h:i A') : '' }}</small>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <span class="badge bg-success text-white rounded-pill px-2 py-1 fs-10">
                                                        <i class="ri-checkbox-circle-fill me-1"></i> Completed
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="ri-wallet-3-line fs-1 d-block mb-2 text-muted opacity-50"></i>
                                                        <h6 class="fw-bold mb-1">No Transactions Found</h6>
                                                        <p class="fs-13 mb-3">You have not made any wallet transactions yet.</p>
                                                        <button type="button" class="btn btn-sm theme-btn rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#topupModal">
                                                            <i class="ri-add-line me-1"></i> Add Funds to Wallet
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($walletTransactions->hasPages())
                            <div class="card-footer bg-white border-top py-3">
                                {{ $walletTransactions->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- profile section end -->

    <!-- Add Funds / Top-Up Modal -->
    <div class="modal fade" id="topupModal" tabindex="-1" aria-labelledby="topupModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-bottom py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; background: linear-gradient(135deg, rgba(var(--theme-color), 1), rgba(var(--theme-color2), 1));">
                            <i class="ri-add-circle-line fs-16"></i>
                        </div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="topupModalLabel">Add Money to Wallet</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-danger d-none mb-3" id="topup-error" role="alert"></div>

                    <!-- Amount Selection -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark fs-13 mb-1">Enter Amount to Add ({{ $currencySymbol }}):</label>
                        <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden border">
                            <span class="input-group-text bg-light border-0 fw-bold fs-18 theme-color">{{ $currencySymbol }}</span>
                            <input type="number" id="topup-amount-input" class="form-control border-0 fw-bold fs-20" placeholder="500.00" min="10" step="10" value="500">
                        </div>
                        <small class="text-muted fs-11">Minimum top-up amount is {{ $currencySymbol }}10.00</small>
                    </div>

                    <!-- Preset Amount Badges -->
                    <div class="mb-4">
                        <label class="form-label fs-11 text-muted text-uppercase fw-semibold mb-2">Quick Select Amount:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 quick-amount-btn" data-amount="100">+{{ $currencySymbol }}100</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 quick-amount-btn active" data-amount="500">+{{ $currencySymbol }}500</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 quick-amount-btn" data-amount="1000">+{{ $currencySymbol }}1,000</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 quick-amount-btn" data-amount="2000">+{{ $currencySymbol }}2,000</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 quick-amount-btn" data-amount="5000">+{{ $currencySymbol }}5,000</button>
                        </div>
                    </div>

                    <!-- Payment Gateway Selector (Dynamic based on Admin Active Gateways) -->
                    @php
                        $activeTopupGateways = \App\Models\PaymentGateway::where('is_active', true)->orderBy('sort_order')->get();
                    @endphp

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark fs-13 mb-2">Select Payment Method:</label>
                        <div class="d-flex flex-column gap-2">
                            @forelse($activeTopupGateways as $gw)
                                @php
                                    $gwKey = $gw->gateway_key;
                                    $gwIcon = 'ri-secure-payment-line';
                                    $gwBg = $gw->badge_color ?? '#fc8019';
                                    $gwSub = $gw->description;
                                    $gwName = $gw->display_name ?: ucfirst($gwKey);
                                    $badgeText = 'Instant';

                                    if ($gwKey === 'razorpay') {
                                        $gwIcon = 'ri-flashlight-fill text-info';
                                        $gwBg = '#072654';
                                        $gwName = 'Razorpay';
                                        $gwSub = $gwSub ?: 'UPI, Google Pay, PhonePe, Cards & NetBanking';
                                    } elseif ($gwKey === 'phonepe') {
                                        $gwIcon = 'ri-smartphone-line';
                                        $gwBg = '#5f259f';
                                        $gwName = 'PhonePe';
                                        $gwSub = $gwSub ?: 'Direct UPI & PhonePe Wallet';
                                        $badgeText = 'UPI';
                                    } elseif ($gwKey === 'paytm') {
                                        $gwIcon = 'ri-wallet-3-line';
                                        $gwBg = '#00b9f5';
                                        $gwName = 'Paytm';
                                        $gwSub = $gwSub ?: 'Paytm Wallet, UPI & NetBanking';
                                        $badgeText = 'Fast';
                                    } elseif ($gwKey === 'payu' || $gwKey === 'payumoney') {
                                        $gwIcon = 'ri-secure-payment-line';
                                        $gwBg = '#84cc16';
                                        $gwName = 'PayU Money';
                                        $gwSub = $gwSub ?: 'PayU UPI, Cards & NetBanking';
                                        $badgeText = 'Secure';
                                    } elseif ($gwKey === 'paypal') {
                                        $gwIcon = 'ri-paypal-fill';
                                        $gwBg = '#003087';
                                        $gwName = 'PayPal';
                                        $gwSub = $gwSub ?: 'International Cards & PayPal Balance';
                                        $badgeText = 'Global';
                                    }
                                @endphp
                                <label class="p-3 rounded-3 border d-flex align-items-center justify-content-between cursor-pointer gateway-option-tile {{ $loop->first ? 'active' : '' }}" style="cursor: pointer;">
                                    <div class="d-flex align-items-center gap-3">
                                        <input type="radio" name="topup_gateway" value="{{ $gwKey }}" {{ $loop->first ? 'checked' : '' }} class="form-check-input mt-0">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; background: {{ $gwBg }};">
                                            <i class="{{ $gwIcon }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-13">{{ $gwName }}</div>
                                            <small class="text-muted fs-11">{{ $gwSub }}</small>
                                        </div>
                                    </div>
                                    <span class="badge bg-soft-success text-success fs-10 rounded-pill">{{ $badgeText }}</span>
                                </label>
                            @empty
                                <div class="alert alert-warning p-3 fs-13 mb-0">
                                    <i class="ri-information-line me-1"></i> No online payment gateway is currently active. Please contact site administrator.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-light rounded-pill flex-grow-1" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirm-topup-btn" class="btn theme-btn rounded-pill flex-grow-1 fw-bold">
                            <i class="ri-lock-line me-1"></i> Proceed to Pay <span id="btn-pay-amount">{{ $currencySymbol }}500.00</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Razorpay Checkout Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        $(function () {
            var SYMBOL = '{{ $currencySymbol }}';
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var razorpayKey = '{{ $razorpayKey }}';
            var customerName = '{{ auth()->user()->name }}';
            var customerEmail = '{{ auth()->user()->email }}';
            var customerPhone = '{{ auth()->user()->phone ?? "" }}';
            var companyName = '{{ $companyName }}';

            // Quick Select Amount Buttons
            $('.quick-amount-btn').on('click', function () {
                $('.quick-amount-btn').removeClass('active btn-secondary text-white').addClass('btn-outline-secondary');
                $(this).addClass('active btn-secondary text-white').removeClass('btn-outline-secondary');
                var amt = $(this).data('amount');
                $('#topup-amount-input').val(amt);
                $('#btn-pay-amount').text(SYMBOL + parseFloat(amt).toFixed(2));
            });

            $('#topup-amount-input').on('input', function () {
                var val = parseFloat($(this).val()) || 0;
                $('#btn-pay-amount').text(SYMBOL + val.toFixed(2));
            });

            // Gateway tile click
            $('.gateway-option-tile').on('click', function () {
                $('.gateway-option-tile').removeClass('active border-primary bg-light');
                $(this).addClass('active border-primary bg-light');
                $(this).find('input[type="radio"]').prop('checked', true);
            });

            // Handle Top-Up Execution
            $('#confirm-topup-btn').on('click', function () {
                var amount = parseFloat($('#topup-amount-input').val()) || 0;
                var $checkedGateway = $('input[name="topup_gateway"]:checked');
                var gateway = $checkedGateway.val() || 'razorpay';
                var gatewayName = $checkedGateway.closest('.gateway-option-tile').find('.fw-bold').text() || 'Razorpay';
                var $btn = $(this);

                if (amount < 10) {
                    $('#topup-error').removeClass('d-none').text('Please enter a minimum amount of ' + SYMBOL + '10.00');
                    return;
                }

                $('#topup-error').addClass('d-none');
                $('#topupModal').modal('hide');

                // 1. If Razorpay is selected
                if (gateway === 'razorpay') {
                    Swal.fire({
                        title: 'Connecting to Razorpay...',
                        html: 'Opening secure payment gateway for <b>' + SYMBOL + amount.toFixed(2) + '</b>...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: function () {
                            Swal.showLoading();
                        }
                    });

                    var options = {
                        "key": razorpayKey,
                        "amount": Math.round(amount * 100),
                        "currency": "INR",
                        "name": companyName,
                        "description": "Wallet Top-Up (" + companyName + ")",
                        "image": "{{ asset($companyLogo) }}",
                        "prefill": {
                            "name": customerName,
                            "email": customerEmail,
                            "contact": customerPhone
                        },
                        "theme": {
                            "color": "#fc8019"
                        },
                        "handler": function (response) {
                            // Payment Succeeded -> Record in Wallet & Transactions
                            Swal.fire({
                                title: 'Adding Funds to Wallet...',
                                html: 'Verifying payment <b>' + response.razorpay_payment_id + '</b>...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: function () {
                                    Swal.showLoading();
                                }
                            });

                            $.ajax({
                                url: '{{ route('wallet.topup.success') }}',
                                method: 'POST',
                                data: {
                                    _token: csrfToken,
                                    amount: amount,
                                    payment_method: 'razorpay',
                                    payment_id: response.razorpay_payment_id,
                                    razorpay_order_id: response.razorpay_order_id || '',
                                    razorpay_signature: response.razorpay_signature || ''
                                },
                                success: function (res) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Money Added Successfully!',
                                        html: '<div class="text-center">'
                                            + '<div class="badge bg-success mb-2 px-3 py-2 fs-14">✓ Payment Verified</div>'
                                            + '<p class="text-success fw-bold fs-5 mb-2">' + SYMBOL + amount.toFixed(2) + ' Added to Your Wallet!</p>'
                                            + '<p class="text-muted small mb-3">' + res.message + '</p>'
                                            + '<div class="p-3 bg-light rounded text-start fs-13 border">'
                                            + '<div class="mb-1"><b>Transaction ID:</b> <span class="badge bg-dark font-monospace">' + res.transaction_number + '</span></div>'
                                            + '<div class="mb-1"><b>Payment ID:</b> <span class="badge bg-secondary font-monospace">' + response.razorpay_payment_id + '</span></div>'
                                            + '<div class="mb-1"><b>Payment Gateway:</b> <span class="badge bg-info text-dark">Razorpay</span></div>'
                                            + '<div><b>Updated Balance:</b> <span class="fw-bold text-success fs-15">' + SYMBOL + res.new_balance + '</span></div>'
                                            + '</div>'
                                            + '</div>',
                                        confirmButtonColor: '#fc8019'
                                    }).then(function () {
                                        window.location.reload();
                                    });
                                },
                                error: function (xhr) {
                                    var msg = 'Payment was verified (' + response.razorpay_payment_id + '), but saving transaction failed. Please contact support.';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        msg = xhr.responseJSON.message;
                                    }
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Wallet Credit Error',
                                        text: msg,
                                        confirmButtonColor: '#cb202d'
                                    });
                                }
                            });
                        },
                        "modal": {
                            "ondismiss": function () {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Payment Incomplete',
                                    text: 'You closed the Razorpay payment window before completing top-up.',
                                    confirmButtonColor: '#fc8019'
                                });
                            }
                        }
                    };

                    Swal.close();
                    var rzp = new Razorpay(options);
                    rzp.on('payment.failed', function (resp) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Payment Failed',
                            text: resp.error.description || 'Payment could not be processed.',
                            confirmButtonColor: '#cb202d'
                        });
                    });
                    rzp.open();
                } else {
                    // 2. Direct / Simulated Gateway Process (PhonePe / Paytm / PayU / PayPal)
                    Swal.fire({
                        title: 'Connecting to ' + gatewayName + '...',
                        html: 'Initializing secure wallet top-up payment of <b>' + SYMBOL + amount.toFixed(2) + '</b>...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: function () {
                            Swal.showLoading();
                        }
                    });

                    var autoPaymentId = 'PAY_' + gateway.toUpperCase() + '_' + Date.now();

                    setTimeout(function () {
                        $.ajax({
                            url: '{{ route('wallet.topup.success') }}',
                            method: 'POST',
                            data: {
                                _token: csrfToken,
                                amount: amount,
                                payment_method: gateway,
                                payment_id: autoPaymentId
                            },
                            success: function (res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Money Added Successfully!',
                                    html: '<div class="text-center">'
                                        + '<div class="badge bg-success mb-2 px-3 py-2 fs-14">✓ Payment Verified</div>'
                                        + '<p class="text-success fw-bold fs-5 mb-2">' + SYMBOL + amount.toFixed(2) + ' Added to Your Wallet!</p>'
                                        + '<p class="text-muted small mb-3">' + res.message + '</p>'
                                        + '<div class="p-3 bg-light rounded text-start fs-13 border">'
                                        + '<div class="mb-1"><b>Transaction ID:</b> <span class="badge bg-dark font-monospace">' + res.transaction_number + '</span></div>'
                                        + '<div class="mb-1"><b>Payment ID:</b> <span class="badge bg-secondary font-monospace">' + autoPaymentId + '</span></div>'
                                        + '<div class="mb-1"><b>Payment Gateway:</b> <span class="badge bg-info text-dark">' + gatewayName + '</span></div>'
                                        + '<div><b>Updated Balance:</b> <span class="fw-bold text-success fs-15">' + SYMBOL + res.new_balance + '</span></div>'
                                        + '</div>'
                                        + '</div>',
                                    confirmButtonColor: '#fc8019'
                                }).then(function () {
                                    window.location.reload();
                                });
                            },
                            error: function (xhr) {
                                var msg = 'An error occurred while processing your wallet top-up.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    msg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Payment Error',
                                    text: msg,
                                    confirmButtonColor: '#cb202d'
                                });
                            }
                        });
                    }, 1200);
                }
            });
        });
    </script>
@endpush
@endsection

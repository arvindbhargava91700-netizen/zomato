@extends('layouts.front.main')
@section('content')
   <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">My Order</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">My Order</li>
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
                    <div class="my-order-content">
                        <div class="title">
                            <div class="loader-line"></div>
                            <h3>My Order</h3>
                        </div>
                        <div class="cancel-toast alert alert-danger d-none" id="cancel-message" role="alert"></div>
                        <ul class="order-box-list">
                            @forelse ($orders as $order)
                                <li>
                                    <div class="order-box">
                                        <div class="order-box-content">
                                            <div class="brand-icon">
                                                @if ($order->restaurant && $order->restaurant->logo)
                                                    <img class="img-fluid icon" src="{{ asset($order->restaurant->logo) }}"
                                                        alt="restaurant">
                                                @else
                                                    <i class="ri-restaurant-line fs-3 content-color"></i>
                                                @endif
                                            </div>
                                            <div class="order-details">
                                                <div class="d-flex align-items-center justify-content-between w-100">
                                                    <h5 class="brand-name dark-text fw-medium">
                                                        {{ $order->restaurant?->restaurant_name ?? 'Restaurant' }}
                                                    </h5>
                                                    <h6 class="fw-medium content-color text-end">
                                                        {{ $order->created_at->format('d M Y, h:i A') }}
                                                    </h6>
                                                </div>
                                                <h6 class="fw-medium dark-text">
                                                    <span class="fw-normal content-color">Transaction Id :
                                                    </span>
                                                    #{{ $order->id }}
                                                </h6>
                                                <h6 class="fw-medium dark-text mt-1">
                                                    <span class="badge text-uppercase {{ in_array($order->status, ['rejected', 'cancelled']) ? 'bg-danger' : 'bg-theme' }}">
                                                        {{ \App\Models\Order::statusLabel($order->status) }}
                                                    </span>
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-sm-3 mt-2">
                                            <h6 class="fw-medium dark-text">
                                                <span class="fw-normal content-color">Total Amount :</span>
                                                {{ $currencySymbol }} {{ number_format($order->total, 2) }}
                                            </h6>
                                            <div class="d-flex align-items-center flex-wrap gap-2">
                                                <button type="button" class="btn theme-outline details-btn" data-bs-toggle="modal" data-bs-target="#billModal{{ $order->id }}">
                                                    <i class="ri-file-list-3-line me-1"></i>View Bill
                                                </button>
                                                <a href="{{ route('orderTracking', ['order' => $order->id]) }}"
                                                    class="btn theme-outline details-btn">Track Order</a>
                                                @if (in_array($order->status, [
                                                    \App\Models\Order::STATUS_DELIVERED,
                                                    \App\Models\Order::STATUS_COMPLETED,
                                                    \App\Models\Order::STATUS_CANCELLED,
                                                    \App\Models\Order::STATUS_REJECTED,
                                                ]))
                                                @elseif ($order->status === \App\Models\Order::STATUS_PENDING && $order->payment_status !== 'paid')
                                                    <button type="button"
                                                        class="btn cancel-btn details-btn cancel-order-btn"
                                                        data-bs-toggle="modal" data-bs-target="#cancelOrderModal"
                                                        data-id="{{ $order->id }}"
                                                        data-restaurant="{{ $order->restaurant?->restaurant_name ?? 'Restaurant' }}"
                                                        data-total="{{ $currencySymbol . ' ' . number_format($order->total, 2) }}">
                                                        <i class="ri-close-circle-line me-1"></i>Cancel Order
                                                    </button>
                                                @else
                                                    @php
                                                        $cancelDisabledReason = $order->payment_status === 'paid'
                                                            ? 'This order has already been paid, so it cannot be cancelled online. Please contact customer support for assistance.'
                                                            : ($order->status === \App\Models\Order::STATUS_ACCEPTED
                                                                ? 'Your order has already been accepted and food preparation has started. Cancellation and refund are not available at this stage.'
                                                                : 'This order cannot be cancelled at its current stage.');
                                                    @endphp
                                                    <button type="button"
                                                        class="btn cancel-btn details-btn disabled-cancel-btn"
                                                        data-reason="{{ $cancelDisabledReason }}">
                                                        <i class="ri-close-circle-line me-1"></i>Cancel Order
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li>
                                    <div class="text-center py-5">
                                        <i class="ri-shopping-bag-3-line display-4 d-block mb-2 content-color"></i>
                                        <h5 class="mb-2">No orders yet</h5>
                                        <p class="content-color">Start ordering your favourite food.</p>
                                        <a href="{{ route('index') }}" class="btn theme-btn mt-0">ORDER NOW</a>
                                    </div>
                                </li>
                            @endforelse
                        </ul>

                        @foreach($orders as $order)
                        <div class="modal fade" id="billModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-bottom-0 pb-0">
                                        <h1 class="modal-title fs-5 fw-bold"><i class="ri-bill-line me-2 text-primary"></i>Order Bill</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body py-4">
                                        <div class="bill-details-wrapper p-4 bg-light rounded-4">
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="content-color fw-medium">Items Subtotal</span>
                                                <span class="dark-text fw-bold">{{ $currencySymbol }}{{ number_format($order->subtotal, 2) }}</span>
                                            </div>
                                            @if($order->discount > 0)
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="text-success fw-medium">Restaurant Discount</span>
                                                <span class="text-success fw-bold">-{{ $currencySymbol }}{{ number_format($order->discount, 2) }}</span>
                                            </div>
                                            @endif
                                            @if($order->promo_discount > 0)
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="text-success fw-medium">Promo ({{ $order->promo_code }})</span>
                                                <span class="text-success fw-bold">-{{ $currencySymbol }}{{ number_format($order->promo_discount, 2) }}</span>
                                            </div>
                                            @endif
                                            <div class="d-flex justify-content-between mb-3">
                                                <span class="content-color fw-medium">Delivery Charge</span>
                                                <span class="dark-text fw-bold">{{ $currencySymbol }}{{ number_format($order->delivery_charge, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-4 pb-3 border-bottom" style="border-bottom-style: dashed !important; border-bottom-width: 2px !important;">
                                                <span class="content-color fw-medium">Taxes & Fees</span>
                                                <span class="dark-text fw-bold">{{ $currencySymbol }}{{ number_format($order->tax, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="dark-text fw-bold fs-5">Grand Total</span>
                                                <span class="fw-bold fs-4" style="color: var(--theme-color);">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if ($orders->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- profile section end -->

    <!-- cancel order modal starts -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="cancelOrderModalLabel">
                        <i class="ri-close-circle-line me-1"></i>Cancel Order
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="content-color mb-3">
                        Are you sure you want to cancel your order from
                        <strong class="dark-text" id="cancel-restaurant">this restaurant</strong>?
                        Order total: <strong class="dark-text" id="cancel-total"></strong>.
                    </p>
                    <div class="alert alert-warning mt-0 mb-0 cancel-warning">
                        <i class="ri-information-line me-1"></i>
                        Once cancelled, this action cannot be undone.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn gray-btn mt-0" data-bs-dismiss="modal">KEEP ORDER</button>
                    <button type="button" class="btn theme-btn mt-0" id="confirm-cancel">
                        <i class="ri-check-double-line me-1"></i>YES, CANCEL ORDER
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- cancel order modal ends -->

@push('scripts')
    <script>
        $(function () {
            var cancelUrl = '{{ url('/orders') }}';

            function showMessage(msg, isError) {
                var $m = $('#cancel-message');
                $m.removeClass('d-none alert-danger alert-success')
                    .addClass(isError ? 'alert-danger' : 'alert-success')
                    .html(msg);
                $('html, body').animate({ scrollTop: $m.offset().top - 90 }, 400);
                setTimeout(function () { $m.addClass('d-none'); }, 8000);
            }

            $('.cancel-order-btn').on('click', function () {
                var $btn = $(this);

                $('#cancel-restaurant').text($btn.data('restaurant'));
                $('#cancel-total').text($btn.data('total'));
                $('#confirm-cancel').data('order-id', $btn.data('id'));
            });

            $('.disabled-cancel-btn').on('click', function () {
                showMessage($(this).data('reason'), true);
            });

            $('#confirm-cancel').on('click', function () {
                var orderId = $(this).data('order-id');
                var $btn = $(this).prop('disabled', true).html('<i class="ri-loader-4-line me-1"></i>CANCELLING...');

                $.ajax({
                    url: cancelUrl + '/' + orderId + '/cancel',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.success) {
                            $('#cancelOrderModal').modal('hide');
                            showMessage(res.message, false);
                            setTimeout(function () { window.location.reload(); }, 1500);
                        } else {
                            $btn.prop('disabled', false).html('<i class="ri-check-double-line me-1"></i>YES, CANCEL ORDER');
                            showMessage(res.message || 'Could not cancel the order. Please try again.', true);
                        }
                    },
                    error: function (xhr) {
                        $btn.prop('disabled', false).html('<i class="ri-check-double-line me-1"></i>YES, CANCEL ORDER');
                        var msg = 'Could not cancel the order. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        showMessage(msg, true);
                    }
                });
            });
        });
    </script>
@endpush
@endsection
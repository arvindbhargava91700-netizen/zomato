@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">{{ $order ? 'Order Confirmed' : 'Checkout' }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Confirm</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!--  account section starts -->
    <section class="account-section section-b-space pt-0">
        <div class="container">
            <div class="layout-sec">
                <div class="row g-lg-4 g-4">
                    <div class="col-lg-8">
                        @include('manage.front.partials.checkout-stepper', ['step' => 'confirm'])

                        @if ($order)
                            <div class="account-part confirm-part">
                                <img class="img-fluid account-img w-25"
                                    src="{{ asset('front/assets/images/gif/confirm.gif') }}" alt="confirm">
                                <h3>Your order has been successfully placed</h3>
                                <p>
                                    Sit and relax while your order is being worked on. It’ll take
                                    5 min before you get it.
                                </p>
                                <div class="account-btn d-flex justify-content-center gap-2">
                                    <a href="{{ route('orderTracking', ['order' => $order->id]) }}" class="btn theme-btn mt-0">TRACK ORDER</a>
                                </div>
                            </div>
                        @else
                            <div class="account-part">
                                <img class="img-fluid account-img" src="front/assets/images/account.svg" alt="account">
                                <div class="title mb-0">
                                    <div class="loader-line"></div>
                                    <h3>Confirm Order</h3>
                                    <p>Review your order details before placing it.</p>
                                </div>

                                <div id="confirm-details" class="mt-3">
                                    <h5 class="fw-semibold mb-2">Delivery Address</h5>
                                    <div id="confirm-address" class="address-box white-bg p-3 mb-3"></div>

                                    <h5 class="fw-semibold mb-2">Delivery & Payment</h5>
                                    <div class="address-box white-bg p-3 mb-3">
                                        <p class="mb-1"><strong>Delivery Option:</strong> <span id="confirm-delivery"></span></p>
                                        <p class="mb-0"><strong>Payment Method:</strong> <span id="confirm-payment"></span></p>
                                    </div>
                                </div>

                                <div id="confirm-error" class="alert alert-danger d-none"></div>

                                <div class="account-btn d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('payment') }}" class="btn theme-outline mt-0">BACK</a>
                                    <button type="button" id="place-order" class="btn theme-btn mt-0">PLACE ORDER</button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        @if ($order)
                            <div class="order-summery-section sticky-top">
                                <div class="checkout-detail">
                                    <div class="cart-address-box">
                                        <div class="add-img">
                                            <img class="img-fluid img"
                                                src="{{ asset('front/assets/images/home.png') }}"
                                                alt="location">
                                        </div>
                                        <div class="add-content">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="dark-text deliver-place">
                                                    Deliver to : {{ $order->address ? ucfirst($order->address->label ?: 'Home') : 'Address' }}
                                                </h5>
                                            </div>
                                            <h6 class="address mt-2 content-color">
                                                {{ $order->address ? $order->address->address . ', ' . $order->address->city . ', ' . $order->address->country . ' ' . $order->address->zip : 'Address not available.' }}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="cart-address-box mt-3">
                                        <div class="add-img">
                                            <img class="img-fluid img sm-size"
                                                src="{{ asset('front/assets/images/svg/wallet-add.svg') }}"
                                                alt="payment">
                                        </div>
                                        <div class="add-content">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="dark-text deliver-place">Payment Method:</h5>
                                            </div>
                                            <h6 class="address mt-2 content-color">
                                                {{ ucwords(str_replace('_', ' ', $order->payment_method)) }}
                                            </h6>
                                        </div>
                                    </div>
                                    <ul>
                                        @foreach ($order->items as $item)
                                            <li>
                                                <div class="horizontal-product-box">
                                                    <div class="product-content">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h5>{{ $item->name }}</h5>
                                                            <h6 class="product-price">{{ $currencySymbol }}{{ number_format($item->price, 2) }}</h6>
                                                        </div>
                                                        @if ($item->food && $item->food->short_description)
                                                            <h6 class="ingredients-text">{{ $item->food->short_description }}</h6>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <h5 class="bill-details-title fw-semibold dark-text">
                                        Bill Details
                                    </h5>
                                    <div class="sub-total">
                                        <h6 class="content-color fw-normal">Sub Total</h6>
                                        <h6 class="fw-semibold">{{ $currencySymbol }}{{ number_format($order->subtotal, 2) }}</h6>
                                    </div>
                                    <div class="sub-total">
                                        <h6 class="content-color fw-normal">
                                            Delivery Charge (2 kms)
                                        </h6>
                                        <h6 class="fw-semibold success-color">
                                            {{ $order->delivery_charge > 0 ? $currencySymbol . number_format($order->delivery_charge, 2) : 'Free' }}
                                        </h6>
                                    </div>

                                    <div class="sub-total">
                                        <h6 class="content-color fw-normal">Tax ({{ $taxGst ?: $taxPercentage . '%' }})</h6>
                                        <h6 class="fw-semibold">{{ $currencySymbol }}{{ number_format($order->tax, 2) }}</h6>
                                    </div>
                                    <div class="grand-total">
                                        <h6 class="fw-semibold dark-text">Total</h6>
                                        <h6 class="fw-semibold amount">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</h6>
                                    </div>
                                    <img class="dots-design" src="{{ asset('front/assets/images/svg/dots-design.svg') }}"
                                        alt="dots">
                                </div>
                            </div>
                        @else
                            @include('manage.front.partials.cart-summary')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- account section end -->

@if (!$order)
@push('scripts')
    <script>
        $(function () {
            var C = window.CheckoutCart;
            if (!C) return;

            function label(map, key, fallback) {
                return map[key] ? map[key].label || map[key] : (fallback || key);
            }

            // Delivery address
            var addressId = localStorage.getItem('checkout_address_id');
            if (!addressId) {
                $('#confirm-address').html('<p class="content-color mb-0">No address selected. <a href="{{ route('address') }}">Select one</a>.</p>');
            } else {
                $.get('{{ url('/address-api') }}/' + addressId, function (addr) {
                    var html =
                        '<h6 class="fw-semibold mb-1">' +
                        (addr.label ? addr.label + ' - ' : '') + addr.first_name + ' ' + addr.last_name +
                        '</h6>' +
                        '<p class="content-color mb-1">' + addr.address + ', ' + addr.city + ', ' + addr.country + ' ' + addr.zip + '</p>' +
                        '<small class="content-color">' + addr.phone + '</small>';
                    $('#confirm-address').html(html);
                }).fail(function () {
                    $('#confirm-address').text('Unable to load address.');
                });
            }

            // Delivery & payment labels
            $('#confirm-delivery').text(label(C.deliveryOptions, C.deliveryOption(), C.deliveryOption()));
            $('#confirm-payment').text(C.paymentMethods[C.paymentMethod()] || C.paymentMethod());

            C.render();

            $('#place-order').on('click', function () {
                var items = C.getItems().map(function (it) {
                    return { id: it.id, name: it.name, price: it.price, qty: it.qty };
                });

                if (items.length === 0) {
                    showError('Your cart is empty.');
                    return;
                }
                if (!addressId) {
                    showError('Please select a delivery address.');
                    return;
                }
                if (!C.deliveryOption() || !C.paymentMethod()) {
                    showError('Please choose delivery and payment options.');
                    return;
                }

                var $btn = $(this).prop('disabled', true).text('PLACING...');

                $.ajax({
                    url: '{{ route('place.order') }}',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        address_id: addressId,
                        delivery_option: C.deliveryOption(),
                        payment_method: C.paymentMethod(),
                        items: items
                    },
                    success: function (res) {
                        if (res.success) {
                            C.clearCart();
                            window.location.href = res.redirect;
                        } else {
                            $btn.prop('disabled', false).text('PLACE ORDER');
                            showError('Could not place order. Please try again.');
                        }
                    },
                    error: function (xhr) {
                        $btn.prop('disabled', false).text('PLACE ORDER');
                        var msg = 'Could not place order.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        showError(msg);
                    }
                });
            });

            function showError(msg) {
                $('#confirm-error').removeClass('d-none').text(msg);
            }
        });
    </script>
@endpush
@endif
@endsection

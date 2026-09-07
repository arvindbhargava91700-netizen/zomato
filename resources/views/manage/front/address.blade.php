@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Address</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Address</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- account section starts -->
    <section class="account-section section-b-space pt-0">
        <div class="container">
            <div class="layout-sec">
                <div class="row g-lg-4 g-4">
                    <div class="col-lg-8">
                        @include('manage.front.partials.checkout-stepper', ['step' => 'address'])

                        <div class="address-section">
                            <div class="title">
                                <div class="loader-line"></div>
                                <h3>Select Saved Address</h3>
                                <h6>
                                    You've add some address before, You can select one of below.
                                </h6>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="alert alert-warning alert-dismissible fade show d-none" id="address-error"
                                role="alert">
                                <i class="ri-alarm-warning-line me-2"></i>
                                Please select an address first by clicking
                                <strong>"Deliver Here"</strong>.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>

                            <div class="row g-3">
                                @forelse (auth()->user()->addresses as $address)
                                    @php
                                        $label = $address->label ?: 'Home';
                                        if (strtolower($label) === 'office') {
                                            $icon = 'ri-briefcase-4-fill';
                                        } elseif (strtolower($label) === 'home') {
                                            $icon = 'ri-home-4-fill';
                                        } else {
                                            $icon = 'ri-account-circle-fill';
                                        }
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="address-box" data-address-id="{{ $address->id }}">
                                            <div class="address-title">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="{{ $icon }} icon"></i>
                                                    <h6>{{ $label }}</h6>
                                                </div>
                                                <a href="#edit-address-{{ $address->id }}" class="edit-btn"
                                                    data-bs-toggle="modal">Edit</a>
                                            </div>
                                            <div class="address-details">
                                                <h6>
                                                    {{ $address->address }}, {{ $address->city }},
                                                    {{ $address->country }} {{ $address->zip }}
                                                </h6>
                                                <h6 class="phone-number">{{ $address->phone }}</h6>
                                                <div class="option-section">
                                                    <a href="#" class="btn gray-btn rounded-2 mt-0 deliver-here"
                                                        data-id="{{ $address->id }}">Deliver
                                                        Here</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- edit address modal starts -->
                                    <div class="modal address-details-modal fade" id="edit-address-{{ $address->id }}"
                                        tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST"
                                                    action="{{ route('address.update', $address->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5">Address Details</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label">Label</label>
                                                                <input type="text" class="form-control" name="label"
                                                                    value="{{ old('label', $address->label) }}"
                                                                    placeholder="Home / Office">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">First Name</label>
                                                                <input type="text" class="form-control" name="first_name"
                                                                    value="{{ $address->first_name }}"
                                                                    placeholder="Enter your first name">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Last Name</label>
                                                                <input type="text" class="form-control" name="last_name"
                                                                    value="{{ $address->last_name }}"
                                                                    placeholder="Enter your last name">
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">Address</label>
                                                                <input type="text" class="form-control" name="address"
                                                                    value="{{ $address->address }}"
                                                                    placeholder="Enter your address" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">City</label>
                                                                <input type="text" class="form-control" name="city"
                                                                    value="{{ $address->city }}"
                                                                    placeholder="Enter your city">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Country</label>
                                                                <input type="text" class="form-control" name="country"
                                                                    value="{{ $address->country }}"
                                                                    placeholder="Enter your country">
                                                            </div>
                                                            <div class="col-md-8">
                                                                <label class="form-label">Phone Number</label>
                                                                <input type="tel" class="form-control" name="phone"
                                                                    value="{{ $address->phone }}"
                                                                    placeholder="Enter your number">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Zip</label>
                                                                <input type="text" class="form-control" name="zip"
                                                                    value="{{ $address->zip }}"
                                                                    placeholder="Enter your zip">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Latitude <small class="text-muted">(for delivery distance)</small></label>
                                                                <input type="number" step="any" class="form-control" name="latitude"
                                                                    value="{{ $address->latitude }}"
                                                                    placeholder="e.g. 28.6139">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">Longitude <small class="text-muted">(for delivery distance)</small></label>
                                                                <input type="number" step="any" class="form-control" name="longitude"
                                                                    value="{{ $address->longitude }}"
                                                                    placeholder="e.g. 77.2090">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn gray-btn mt-0"
                                                            data-bs-dismiss="modal">CANCEL</button>
                                                        <button type="submit" class="btn theme-btn mt-0">SUBMIT</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- edit address modal end -->
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            You have no saved addresses.
                                            <a href="{{ route('saved.address') }}">Add an address</a>.
                                        </div>
                                    </div>
                                @endforelse
                                <div class="col-md-6">
                                    <div class="address-box new-address-box">
                                        <a href="#address-details" class="btn theme-outline rounded-2"
                                            data-bs-toggle="modal">Add New Address</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="order-summery-section sticky-top">
                            <div class="checkout-detail">
                                <ul id="checkout-items"></ul>
                                <h5 class="bill-details-title fw-semibold dark-text">
                                    Bill Details
                                </h5>
                                <div class="sub-total">
                                    <h6 class="content-color fw-normal">Sub Total</h6>
                                    <h6 class="fw-semibold" id="co-subtotal">{{ $currencySymbol }}0.00</h6>
                                </div>
                                <div class="sub-total">
                                    <h6 class="content-color fw-normal">
                                        Delivery Charge (2 kms)
                                    </h6>
                                    <div class="text-end">
                                        @php
                                            $deliveryCharge = 2 * ($deliveryChargePerKm ?? 20);
                                        @endphp
                                        <h6 class="fw-semibold success-color" id="co-delivery">{{ $currencySymbol }}{{ number_format($deliveryCharge, 2) }}</h6>
                                        <small class="content-color d-block" id="co-delivery-meta"></small>
                                    </div>
                                </div>
                                <div class="sub-total">
                                    <h6 class="content-color fw-normal">Tax ({{ $taxGst ?: $taxPercentage . '%' }})</h6>
                                    <h6 class="fw-semibold" id="co-tax">{{ $currencySymbol }}0.00</h6>
                                </div>
                                <div class="grand-total">
                                    <h6 class="fw-semibold dark-text">To Pay</h6>
                                    <h6 class="fw-semibold amount" id="co-total">{{ $currencySymbol }}0.00</h6>
                                </div>
                                <a href="#" id="checkout-btn"
                                    class="btn theme-btn restaurant-btn rounded-2 w-100">CHECKOUT</a>
                                <img class="dots-design" src="{{ asset('front/assets/images/svg/dots-design.svg') }}"
                                    alt="dots">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- account section end -->

    <!-- address details modal starts -->
    <div class="modal address-details-modal fade" id="address-details" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('address.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                            Address Details
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Label</label>
                                <input type="text" class="form-control" name="label" placeholder="Home / Office">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name"
                                    placeholder="Enter your first name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name"
                                    placeholder="Enter your last name">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" name="address" placeholder="Enter your address"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" placeholder="Enter your city">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" name="country"
                                    placeholder="Enter your country">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" placeholder="Enter your number">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Zip</label>
                                <input type="text" class="form-control" name="zip" placeholder="Enter your zip">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Latitude <small class="text-muted">(for delivery distance)</small></label>
                                <input type="number" step="any" class="form-control" name="latitude"
                                    placeholder="e.g. 28.6139">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Longitude <small class="text-muted">(for delivery distance)</small></label>
                                <input type="number" step="any" class="form-control" name="longitude"
                                    placeholder="e.g. 77.2090">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn theme-btn mt-0">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- address details modal end -->

@push('scripts')
    <script>
        $(function () {
            var selectedId = localStorage.getItem('checkout_address_id');

            if (selectedId) {
                $('.address-box[data-address-id="' + selectedId + '"]').addClass('selected');
            }

            $('.deliver-here').on('click', function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                localStorage.setItem('checkout_address_id', id);
                $('.address-box').removeClass('selected');
                $(this).closest('.address-box').addClass('selected');
                $('#address-error').addClass('d-none');
            });

            $('#checkout-btn').on('click', function (e) {
                e.preventDefault();
                var id = localStorage.getItem('checkout_address_id');
                if (!id) {
                    $('#address-error').removeClass('d-none');
                    $('html, body').animate({ scrollTop: $('#address-error').offset().top - 80 }, 400);
                    return;
                }
                window.location.href = '{{ route('payment') }}';
            });
        });
    </script>
@endpush
@endsection

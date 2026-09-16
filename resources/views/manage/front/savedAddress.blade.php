@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Saved Address</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="index.html"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Saved Address
                    </li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- card section starts -->
    <section class="profile-section section-b-space">
        <div class="container">
            <div class="row g-3">
                @include('manage.front.partials.profile-sidebar')
                <div class="col-lg-9">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="address-section bg-color h-100 mt-0">
                        <div class="title">
                            <div class="loader-line"></div>
                            <h3>Saved Address</h3>
                        </div>
                        <div class="row g-3">
                            @foreach($addresses as $address)
                                @include('manage.front.partials.address')
                                @include('manage.front.partials.address-edit-modal')
                                @include('manage.front.partials.address-delete-modal')
                            @endforeach
                            <div class="col-md-6">
                                <div class="address-box white-bg new-address-box white-bg">
                                    <a href="#address-details" class="btn new-address-btn theme-outline rounded-2 mt-0"
                                        data-bs-toggle="modal">Add New Address</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- profile section end -->
         <!-- add-card modal starts -->
    <div class="modal address-details-modal fade" id="address-details" tabindex="-1" aria-labelledby="addModalAdress"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('address.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addModalAdress">
                            Address Details
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="inputFirstname" class="form-label">Label</label>
                                <input type="text" class="form-control" id="inputFirstname" name="label"
                                    placeholder="Home / Office">
                            </div>
                            <div class="col-md-6">
                                <label for="inputLastname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="inputLastname" name="first_name"
                                    placeholder="Enter your first name">
                            </div>
                            <div class="col-md-6">
                                <label for="inputmiddlename" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="inputmiddlename" name="last_name"
                                    placeholder="Enter your last name">
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="inputAddress" class="form-label mb-0">Address</label>
                                    <button type="button" class="btn btn-sm text-primary p-0 bg-transparent border-0 d-flex align-items-center gap-1 auto-location-btn">
                                        <i class="ri-map-pin-line"></i> Use my current location
                                    </button>
                                </div>
                                <input type="text" class="form-control" id="inputAddress" name="address"
                                    placeholder="Enter your address">
                            </div>
                            <div class="col-md-6">
                                <label for="inputCity" class="form-label">City</label>
                                <input type="text" class="form-control" id="inputCity" name="city"
                                    placeholder="Enter your city">
                            </div>
                            <div class="col-md-6">
                                <label for="inputCountry" class="form-label">Country</label>
                                <input type="text" class="form-control" id="inputCountry" name="country"
                                    placeholder="Enter your country">
                            </div>
                            <div class="col-md-8">
                                <label for="inputPhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="inputPhone" name="phone"
                                    placeholder="Enter your number">
                            </div>
                            <div class="col-md-4">
                                <label for="inputZip" class="form-label">Zip</label>
                                <input type="text" class="form-control" id="inputZip" name="zip"
                                    placeholder="Enter your zip">
                            </div>
                            <div class="col-md-6">
                                <label for="inputLat" class="form-label">Latitude <small class="text-muted">(for delivery distance)</small></label>
                                <input type="number" step="any" class="form-control" id="inputLat" name="latitude"
                                    placeholder="e.g. 28.6139">
                            </div>
                            <div class="col-md-6">
                                <label for="inputLng" class="form-label">Longitude <small class="text-muted">(for delivery distance)</small></label>
                                <input type="number" step="any" class="form-control" id="inputLng" name="longitude"
                                    placeholder="e.g. 77.2090">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</a>
                        <button type="submit" class="btn theme-btn mt-0">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- add-card modal end -->

    <!-- edit address modal starts -->
    <div class="modal address-details-modal fade" id="edit-address" tabindex="-1" aria-labelledby="exampleModalAdress"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalAdress">
                        Address Details
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3">
                        <div class="col-md-6">
                            <label for="editFirstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="editFirstname" value="Smith"
                                placeholder="Enter your fist name">
                        </div>
                        <div class="col-md-6">
                            <label for="editLastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="editLastname" value="Jones"
                                placeholder="Enter your last name">
                        </div>
                        <div class="col-12">
                            <label for="editAddress" class="form-label">Address</label>
                            <input type="text" class="form-control" id="editAddress"
                                value="93, Songbird Cir, Blackville," placeholder="Enter your address">
                        </div>
                        <div class="col-md-6">
                            <label for="editCity" class="form-label">City</label>
                            <input type="text" class="form-control" id="editCity" value="South Carolina"
                                placeholder="Enter your city">
                        </div>
                        <div class="col-md-6">
                            <label for="editCountry" class="form-label">Country</label>
                            <input type="text" class="form-control" id="editCountry" value="USA"
                                placeholder="Enter your country">
                        </div>
                        <div class="col-md-8">
                            <label for="editPhone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="editPhone" value="+33 (907) 555-0101"
                                placeholder="Enter your number">
                        </div>
                        <div class="col-md-4">
                            <label for="editZip" class="form-label">Zip</label>
                            <input type="text" class="form-control" id="editZip" value="29817"
                                placeholder="Enter your zip">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="address.html" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</a>
                    <a href="address.html" class="btn theme-btn mt-0">SUBMIT</a>
                </div>
            </div>
        </div>
    </div>
    <!-- edit address modal end -->

    <!-- logout modal starts -->
    <div class="modal address-details-modal fade" id="log-out" tabindex="-1" aria-labelledby="exampleModalLogout"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLogout">Logging Out</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you Sure, You are logging out</p>
                </div>
                <div class="modal-footer">
                    <a href="saved-card.html" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</a>
                    <a href="index.html" class="btn theme-btn mt-0">Log Out</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.auto-location-btn', function() {
            let $btn = $(this);
            let originalText = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Fetching...');
            $btn.prop('disabled', true);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;
                    
                    let $form = $btn.closest('form');
                    $form.find('input[name="latitude"]').val(lat);
                    $form.find('input[name="longitude"]').val(lng);

                    $.ajax({
                        url: `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`,
                        method: 'GET',
                        success: function(data) {
                            if (data && data.address) {
                                let addr = data.address;
                                let city = addr.city || addr.town || addr.village || addr.county || '';
                                let country = addr.country || '';
                                let zip = addr.postcode || '';
                                let fullAddress = data.display_name || '';
                                
                                $form.find('input[name="address"]').val(fullAddress);
                                $form.find('input[name="city"]').val(city);
                                $form.find('input[name="country"]').val(country);
                                $form.find('input[name="zip"]').val(zip);
                            }
                            $btn.html(originalText);
                            $btn.prop('disabled', false);
                        },
                        error: function() {
                            alert('Could not fetch address details. Please fill manually.');
                            $btn.html(originalText);
                            $btn.prop('disabled', false);
                        }
                    });
                }, function(error) {
                    alert('Location access denied or unavailable.');
                    $btn.html(originalText);
                    $btn.prop('disabled', false);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
                $btn.html(originalText);
                $btn.prop('disabled', false);
            }
        });

        $(function () {
            var selectedId = localStorage.getItem('checkout_address_id');
            if (selectedId) {
                $('.address-box[data-address-id="' + selectedId + '"]').addClass('selected');
            }

            // Add loading state to address modal forms
            $(document).on('submit', '.address-details-modal form', function() {
                let $btn = $(this).find('button[type="submit"]');
                if ($btn.length) {
                    $btn.prop('disabled', true);
                    $btn.html('<span class="spinner-border spinner-border-sm me-1 text-white" role="status" aria-hidden="true"></span> <span class="text-white">Submitting...</span>');
                }
            });

            // Add loading state to delete address forms
            $(document).on('submit', '.delete-address-form', function() {
                let $btn = $(this).find('button[type="submit"]');
                if ($btn.length) {
                    $btn.prop('disabled', true);
                    $btn.html('<span class="spinner-border spinner-border-sm me-1 text-white" role="status" aria-hidden="true"></span> <span class="text-white">Deleting...</span>');
                }
            });
        });
    </script>
@endpush
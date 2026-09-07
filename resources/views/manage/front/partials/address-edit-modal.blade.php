<div class="modal address-details-modal fade" id="edit-address-{{ $address->id }}" tabindex="-1"
    aria-labelledby="editAddress{{ $address->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('address.update', $address->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editAddress{{ $address->id }}">Address Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="label"
                                value="{{ old('label', $address->label) }}" placeholder="Home / Office">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first_name"
                                value="{{ old('first_name', $address->first_name) }}" placeholder="Enter your first name">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" name="last_name"
                                value="{{ old('last_name', $address->last_name) }}" placeholder="Enter your last name">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address"
                                value="{{ old('address', $address->address) }}" placeholder="Enter your address">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city"
                                value="{{ old('city', $address->city) }}" placeholder="Enter your city">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <input type="text" class="form-control" name="country"
                                value="{{ old('country', $address->country) }}" placeholder="Enter your country">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone"
                                value="{{ old('phone', $address->phone) }}" placeholder="Enter your number">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Zip</label>
                            <input type="text" class="form-control" name="zip"
                                value="{{ old('zip', $address->zip) }}" placeholder="Enter your zip">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Latitude <small class="text-muted">(for delivery distance)</small></label>
                            <input type="number" step="any" class="form-control" name="latitude"
                                value="{{ old('latitude', $address->latitude) }}" placeholder="e.g. 28.6139">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude <small class="text-muted">(for delivery distance)</small></label>
                            <input type="number" step="any" class="form-control" name="longitude"
                                value="{{ old('longitude', $address->longitude) }}" placeholder="e.g. 77.2090">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</a>
                    <button type="submit" class="btn theme-btn mt-0">SUBMIT</button>
                </div>
            </form>
            <form method="POST" action="{{ route('address.destroy', $address->id) }}" class="text-center pb-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn theme-outline mt-0">Delete Address</button>
            </form>
        </div>
    </div>
</div>

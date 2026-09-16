@extends('layouts.admin.main')

@section('title', getPageTitle('Restaurant Details'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Restaurant Profile</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.restaurants.index') }}">Restaurants</a></li>
                <li class="breadcrumb-item">{{ $restaurant->restaurant_name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit me-1"></i> Edit Details
            </a>
            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Approval Card -->
        <div class="card stretch stretch-full rounded-3 mb-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="card-title mb-0 fw-bold text-dark"><i class="feather-shield me-2 text-danger"></i>Restaurant Approval</h5>
                @if($restaurant->approval_status === 'approved')
                    <span class="badge bg-success fs-12 px-3 py-2 rounded-pill"><i class="feather-check-circle me-1"></i>Approved</span>
                @elseif($restaurant->approval_status === 'rejected')
                    <span class="badge bg-danger fs-12 px-3 py-2 rounded-pill"><i class="feather-x-circle me-1"></i>Rejected</span>
                @else
                    <span class="badge bg-warning text-dark fs-12 px-3 py-2 rounded-pill"><i class="feather-clock me-1"></i>Pending Approval</span>
                @endif
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Documents Checklist -->
                    <div class="col-lg-5">
                        <h6 class="fw-bold text-dark mb-3"><i class="feather-file-text me-1 text-danger"></i>Documents Checklist</h6>
                        <ul class="list-group list-group-flush border rounded-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                                <span class="fw-semibold"><i class="feather-image me-2 text-muted"></i>Logo</span>
                                @if($restaurant->logo)
                                    <a href="{{ asset($restaurant->logo) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">View <i class="feather-external-link ms-1"></i></a>
                                @else
                                    <span class="text-danger fs-12"><i class="feather-x me-1"></i>Missing</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                                <span class="fw-semibold"><i class="feather-image me-2 text-muted"></i>Banner</span>
                                @if($restaurant->banner)
                                    <a href="{{ asset($restaurant->banner) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">View <i class="feather-external-link ms-1"></i></a>
                                @else
                                    <span class="text-danger fs-12"><i class="feather-x me-1"></i>Missing</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                                <span class="fw-semibold"><i class="feather-award me-2 text-muted"></i>FSSAI License</span>
                                @if($restaurant->fssai_number)
                                    <span class="badge bg-success fs-12">{{ $restaurant->fssai_number }}</span>
                                @else
                                    <span class="text-danger fs-12"><i class="feather-x me-1"></i>Missing</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-3">
                                <span class="fw-semibold"><i class="feather-file-text me-2 text-muted"></i>GST Number</span>
                                @if($restaurant->gst_number)
                                    <span class="badge bg-success fs-12">{{ $restaurant->gst_number }}</span>
                                @else
                                    <span class="text-danger fs-12"><i class="feather-x me-1"></i>Missing</span>
                                @endif
                            </li>
                        </ul>

                        @if($restaurant->admin_remarks)
                            <div class="alert alert-warning border-0 rounded-3 mt-3 mb-0">
                                <div class="fw-bold text-dark mb-1"><i class="feather-message-square me-1"></i>Admin Remarks</div>
                                <p class="mb-0 text-secondary">{{ $restaurant->admin_remarks }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Approval Form -->
                    <div class="col-lg-7">
                        <h6 class="fw-bold text-dark mb-3"><i class="feather-edit-2 me-1 text-danger"></i>Update Approval Status</h6>
                        <form action="{{ route('admin.restaurants.approval', $restaurant->id) }}" method="POST" class="border rounded-3 p-3">
                            @csrf
                            <div class="mb-3">
                                <label for="admin_remarks" class="form-label fw-semibold">Admin Remarks <small class="text-muted">(Required when rejecting)</small></label>
                                <textarea name="admin_remarks" id="admin_remarks" rows="3" class="form-control" placeholder="e.g. FSSAI license missing, please upload it...">{{ old('admin_remarks', $restaurant->admin_remarks) }}</textarea>
                                @error('admin_remarks')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" name="approval_status" value="approved" class="btn btn-success px-4 fw-semibold">
                                    <i class="feather-check-circle me-1"></i> Approve
                                </button>
                                <button type="submit" name="approval_status" value="rejected" class="btn btn-danger px-4 fw-semibold" onclick="return rejectConfirm();">
                                    <i class="feather-x-circle me-1"></i> Reject
                                </button>
                                <button type="submit" name="approval_status" value="pending" class="btn btn-warning px-4 fw-semibold text-dark">
                                    <i class="feather-clock me-1"></i> Keep Pending
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner & Profile Header Card -->
        <div class="card stretch stretch-full rounded-3 mb-4 overflow-hidden">
            <div class="position-relative bg-dark" style="height: 200px;">
                @if($restaurant->banner)
                    <img src="{{ asset($restaurant->banner) }}" alt="Banner" class="w-100 h-100" style="object-fit: cover; opacity: 0.85;">
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary text-white">
                        <span class="fs-4 fw-bold"><i class="feather-image me-2"></i>No Banner Image</span>
                    </div>
                @endif
            </div>
            <div class="card-body p-4 position-relative" style="margin-top: -60px;">
                <div class="d-flex align-items-end gap-3 flex-wrap">
                    @if($restaurant->logo)
                        <img src="{{ asset($restaurant->logo) }}" alt="Logo" class="rounded-circle border border-4 border-white shadow" style="width: 110px; height: 110px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-white border border-4 border-white shadow d-flex align-items-center justify-content-center" style="width: 110px; height: 110px;">
                            <i class="feather-shopping-bag text-muted display-6"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="mb-1 fw-bold text-dark">{{ $restaurant->restaurant_name }}</h3>
                        <p class="text-muted mb-1"><i class="feather-user me-1"></i>Owner: <strong>{{ $restaurant->owner_name }}</strong></p>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            @if($restaurant->status === 'active')
                                <span class="badge bg-success px-3 py-2 rounded-pill">Active</span>
                            @elseif($restaurant->status === 'inactive')
                                <span class="badge bg-secondary px-3 py-2 rounded-pill">Inactive</span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Pending</span>
                            @endif

                            @if($restaurant->is_pure_veg)
                                <span class="badge bg-soft-success text-success border border-success px-3 py-2 rounded-pill">Pure Veg</span>
                            @else
                                <span class="badge bg-soft-danger text-danger border border-danger px-3 py-2 rounded-pill">Veg / Non-Veg</span>
                            @endif

                            @if($restaurant->brand)
                                <a href="{{ route('admin.brands.show', $restaurant->brand->id) }}" class="badge bg-soft-primary text-primary border border-primary px-3 py-2 rounded-pill text-decoration-none d-inline-flex align-items-center gap-2">
                                    @if($restaurant->brand->logo)
                                        <img src="{{ asset($restaurant->brand->logo) }}" alt="{{ $restaurant->brand->name }}" class="rounded-circle border" style="width: 20px; height: 20px; object-fit: cover;">
                                    @else
                                        <i class="feather-award"></i>
                                    @endif
                                    <span>Brand: <strong>{{ $restaurant->brand->name }}</strong></span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="row g-4">
            <!-- Left Column: Overview & Contact -->
            <div class="col-lg-7">
                <div class="card stretch stretch-full rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark"><i class="feather-info me-2 text-danger"></i>Contact & Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <span class="text-muted d-block fs-12">Email Address</span>
                                <strong class="text-dark">{{ $restaurant->email }}</strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block fs-12">Mobile Number</span>
                                <strong class="text-dark">{{ $restaurant->mobile }}</strong>
                            </div>
                            <div class="col-12">
                                <span class="text-muted d-block fs-12">Address</span>
                                <strong class="text-dark">{{ $restaurant->address }}</strong>
                            </div>
                            <div class="col-sm-4">
                                <span class="text-muted d-block fs-12">Postal Code</span>
                                <strong class="text-dark">{{ $restaurant->postal_code ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-sm-4">
                                <span class="text-muted d-block fs-12">Latitude</span>
                                <strong class="text-dark">{{ $restaurant->latitude ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-sm-4">
                                <span class="text-muted d-block fs-12">Longitude</span>
                                <strong class="text-dark">{{ $restaurant->longitude ?? 'N/A' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card stretch stretch-full rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark"><i class="feather-file-text me-2 text-danger"></i>Description & Licenses</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary">{{ $restaurant->description ?? 'No description provided.' }}</p>
                        <hr>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <span class="text-muted d-block fs-12">GST Number</span>
                                <strong class="text-dark">{{ $restaurant->gst_number ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block fs-12">FSSAI License Number</span>
                                <strong class="text-dark">{{ $restaurant->fssai_number ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        <hr>
                        <h6 class="fw-bold text-dark mb-3"><i class="feather-star me-1 text-danger"></i>Features</h6>
                        @php $features = $restaurant->featureLabels(); @endphp
                        @if(count($features) > 0)
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($features as $feature)
                                    <span class="badge bg-light text-dark border px-3 py-2 fs-12">{{ $feature }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0 fs-12">No features added yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Operations & Rates -->
            <div class="col-lg-5">
                <div class="card stretch stretch-full rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark"><i class="feather-clock me-2 text-danger"></i>Operational Details</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Opening Time</span>
                                <strong class="text-dark">{{ $restaurant->opening_time ? $restaurant->opening_time->format('h:i A') : 'N/A' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Closing Time</span>
                                <strong class="text-dark">{{ $restaurant->closing_time ? $restaurant->closing_time->format('h:i A') : 'N/A' }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Min. Order Amount</span>
                                <strong class="text-dark">₹{{ number_format($restaurant->minimum_order_amount, 2) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Delivery Radius</span>
                                <strong class="text-dark">{{ $restaurant->delivery_radius }} km</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Est. Delivery Time</span>
                                <strong class="text-dark">{{ $restaurant->estimated_delivery_time }} mins</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Admin Commission</span>
                                <strong class="text-danger fw-bold">{{ $restaurant->commission_percentage }}%</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card stretch stretch-full rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-dark"><i class="feather-calendar me-2 text-danger"></i>System Audit</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush fs-13">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Created At</span>
                                <span>{{ $restaurant->created_at ? $restaurant->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Last Updated</span>
                                <span>{{ $restaurant->updated_at ? $restaurant->updated_at->format('M d, Y h:i A') : 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
<script>
    function rejectConfirm() {
        const remarks = document.getElementById('admin_remarks');
        if (!remarks.value.trim()) {
            alert('Please provide a reason in Admin Remarks before rejecting.');
            remarks.focus();
            return false;
        }
        return confirm('Are you sure you want to reject this restaurant?');
    }
</script>
@endsection

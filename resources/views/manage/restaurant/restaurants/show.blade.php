@extends('layouts.restaurant.main')

@section('title', 'Restaurant Details - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Restaurant Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.restaurants.index') }}">Restaurants</a></li>
                <li class="breadcrumb-item">Details</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.restaurants.edit', $restaurant->id) }}" class="btn btn-primary fw-semibold">
                <i class="feather-edit me-1"></i> Edit
            </a>
            <a href="{{ route('restaurant.restaurants.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
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

        <!-- Approval Status Banner -->
        @if($restaurant->approval_status === 'rejected')
            <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <div class="fw-bold text-dark mb-1"><i class="feather-x-circle me-1"></i>Your Restaurant Was Rejected</div>
                    <p class="mb-0 text-secondary">Please fix the issues mentioned below and resubmit for admin approval.</p>
                </div>
                <form action="{{ route('restaurant.restaurants.resubmit', $restaurant->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;" onclick="return confirm('Resubmit this restaurant for admin review?');">
                        <i class="feather-rotate-cw me-1"></i> Resubmit for Approval
                    </button>
                </form>
            </div>
            @if($restaurant->admin_remarks)
                <div class="card border-danger mb-4 rounded-3">
                    <div class="card-body p-3">
                        <div class="fw-bold text-danger mb-1"><i class="feather-message-square me-1"></i>Admin Remarks</div>
                        <p class="mb-0">{{ $restaurant->admin_remarks }}</p>
                    </div>
                </div>
            @endif
        @elseif($restaurant->approval_status === 'approved')
            <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4">
                <div class="fw-bold text-success mb-1"><i class="feather-check-circle me-1"></i>Congratulations! Your Restaurant Is Approved</div>
                <p class="mb-0 text-secondary">Your restaurant is now live and visible to customers.</p>
            </div>
        @else
            <div class="alert alert-warning border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <div class="fw-bold text-dark mb-1"><i class="feather-clock me-1"></i>Your Restaurant Is Under Review</div>
                    <p class="mb-0 text-secondary">Our team is reviewing your restaurant details. You will be notified once it is approved.</p>
                </div>
                <form action="{{ route('restaurant.restaurants.resubmit', $restaurant->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning text-dark fw-semibold" onclick="return confirm('Resubmit this restaurant for admin review?');">
                        <i class="feather-rotate-cw me-1"></i> Resubmit
                    </button>
                </form>
            </div>
        @endif

        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4">
                    @if($restaurant->logo)
                        <img src="{{ asset($restaurant->logo) }}" alt="Logo" class="rounded-circle border" style="width: 90px; height: 90px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fs-2" style="width: 90px; height: 90px; background-color: #cb202d;">
                            <i class="feather-shopping-bag"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="fw-bold mb-1 text-dark">{{ $restaurant->restaurant_name }}</h4>
                        <span class="badge bg-soft-{{ $restaurant->status === 'active' ? 'success' : ($restaurant->status === 'inactive' ? 'secondary' : 'warning') }} text-{{ $restaurant->status === 'active' ? 'success' : ($restaurant->status === 'inactive' ? 'secondary' : 'warning') }} mb-2">
                            {{ ucfirst($restaurant->status) }}
                        </span>
                        @if($restaurant->is_pure_veg)
                            <span class="badge bg-soft-success text-success border border-success">Pure Veg</span>
                        @else
                            <span class="badge bg-soft-danger text-danger border border-danger">Veg / Non-Veg</span>
                        @endif
                        @if($restaurant->brand)
                            <span class="badge bg-soft-primary text-primary border border-primary d-inline-flex align-items-center gap-2">
                                @if($restaurant->brand->logo)
                                    <img src="{{ asset($restaurant->brand->logo) }}" alt="{{ $restaurant->brand->name }}" class="rounded-circle border" style="width: 18px; height: 18px; object-fit: cover;">
                                @else
                                    <i class="feather-award"></i>
                                @endif
                                <span>Brand: <strong>{{ $restaurant->brand->name }}</strong></span>
                            </span>
                        @endif
                        @if($restaurant->nightlifeBanner)
                            <span class="badge bg-dark text-white d-inline-flex align-items-center gap-2">
                                @if($restaurant->nightlifeBanner->banner)
                                    <img src="{{ asset($restaurant->nightlifeBanner->banner) }}" alt="{{ $restaurant->nightlifeBanner->title }}" class="rounded border" style="width: 22px; height: 22px; object-fit: cover;">
                                @else
                                    <i class="feather-moon"></i>
                                @endif
                                <span>Nightlife: <strong>{{ $restaurant->nightlifeBanner->title }}</strong></span>
                            </span>
                        @endif
                        <p class="text-muted mb-0 fs-12"><i class="feather-map-pin me-1"></i>{{ $restaurant->address }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold">Contact Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-muted">Owner Name</dt>
                            <dd class="col-sm-8">{{ $restaurant->owner_name }}</dd>
                            <dt class="col-sm-4 text-muted">Email</dt>
                            <dd class="col-sm-8">{{ $restaurant->email }}</dd>
                            <dt class="col-sm-4 text-muted">Mobile</dt>
                            <dd class="col-sm-8">{{ $restaurant->mobile }}</dd>
                            <dt class="col-sm-4 text-muted">GST Number</dt>
                            <dd class="col-sm-8">{{ $restaurant->gst_number ?: '-' }}</dd>
                            <dt class="col-sm-4 text-muted">FSSAI Number</dt>
                            <dd class="col-sm-8">{{ $restaurant->fssai_number ?: '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold">Operations & Financials</h5>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-5 text-muted">Opening Time</dt>
                            <dd class="col-sm-7">{{ $restaurant->opening_time ? $restaurant->opening_time->format('H:i') : '-' }}</dd>
                            <dt class="col-sm-5 text-muted">Closing Time</dt>
                            <dd class="col-sm-7">{{ $restaurant->closing_time ? $restaurant->closing_time->format('H:i') : '-' }}</dd>
                            <dt class="col-sm-5 text-muted">Min Order Amount</dt>
                            <dd class="col-sm-7">₹{{ number_format($restaurant->minimum_order_amount, 2) }}</dd>
                            <dt class="col-sm-5 text-muted">Delivery Radius</dt>
                            <dd class="col-sm-7">{{ $restaurant->delivery_radius }} km</dd>
                            <dt class="col-sm-5 text-muted">Est. Delivery Time</dt>
                            <dd class="col-sm-7">{{ $restaurant->estimated_delivery_time }} mins</dd>
                            <dt class="col-sm-5 text-muted">Commission</dt>
                            <dd class="col-sm-7">{{ $restaurant->commission_percentage }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
            @if($restaurant->description)
                <div class="col-12">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="card-title mb-0 fw-bold">Description</h5>
                        </div>
                        <div class="card-body p-4">
                            <p class="mb-0">{{ $restaurant->description }}</p>
                        </div>
                    </div>
                </div>
            @endif
            @php $features = $restaurant->featureLabels(); @endphp
            @if(!empty($restaurant->features))
                <div class="col-12">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h5 class="card-title mb-0 fw-bold">Features & Amenities</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($features as $feature)
                                    <span class="badge bg-light text-dark border px-3 py-2 fs-12">
                                        <i class="feather-check-circle text-success me-1"></i>{{ $feature }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

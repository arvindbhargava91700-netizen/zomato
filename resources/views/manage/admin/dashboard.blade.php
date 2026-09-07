@extends('layouts.admin.main')

@section('title', 'Admin Dashboard - Property Management Software')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Dashboard</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Dashboard</li>
            </ul>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="row g-3 mb-4">
            <div class="col-12">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                        <div>
                            <h5 class="fw-bold mb-1">Admin Dashboard</h5>
                            <p class="mb-0 text-muted">Overview of orders, revenue, riders and key admin activity.</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-soft-primary text-primary rounded-pill px-3 py-2">Live</span>
                            <span class="badge bg-soft-success text-success rounded-pill px-3 py-2">New updates</span>
                            <span class="badge bg-soft-info text-info rounded-pill px-3 py-2">Data refreshed</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- [Summary Cards] -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4" style="background: linear-gradient(180deg, #fff8f0 0%, #ffffff 100%);">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold text-uppercase">Today's Orders</span>
                                <h3 class="fw-bold text-dark mb-1">{{ $stats['todays_orders'] }}</h3>
                                <p class="mb-0 text-muted fs-12">Orders placed today</p>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-danger text-danger rounded-circle">
                                <i class="feather-shopping-cart fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4" style="background: linear-gradient(180deg, #eff9ff 0%, #ffffff 100%);">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold text-uppercase">Platform Earnings</span>
                                <h3 class="fw-bold text-dark mb-1">{{ $currencySymbol }}{{ number_format($stats['platform_earnings'], 2) }}</h3>
                                <p class="mb-0 text-muted fs-12">Your earnings from approved payouts</p>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-primary text-primary rounded-circle">
                                <i class="feather-trending-up fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4" style="background: linear-gradient(180deg, #effdf0 0%, #ffffff 100%);">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold text-uppercase">Delivered Orders</span>
                                <h3 class="fw-bold text-dark mb-1">{{ $stats['delivered_orders'] }}</h3>
                                <p class="mb-0 text-muted fs-12">Total orders successfully delivered</p>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-success text-success rounded-circle">
                                <i class="feather-check-circle fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-4" style="background: linear-gradient(180deg, #fff2f6 0%, #ffffff 100%);">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <span class="fs-12 text-muted fw-semibold text-uppercase">Active Orders</span>
                                <h3 class="fw-bold text-dark mb-1">{{ $stats['active_orders'] }}</h3>
                                <p class="mb-0 text-muted fs-12">Orders currently in progress</p>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-danger text-danger rounded-circle">
                                <i class="feather-activity fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <!-- [Overview Boxes] -->
            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-lg bg-soft-warning text-warning rounded-circle">
                                <i class="feather-shopping-bag fs-3"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">{{ $stats['restaurants'] }}</h5>
                                <p class="mb-0 text-muted fs-12">Restaurants</p>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center justify-content-between">
                            <span class="fs-12 text-muted">Total registered</span>
                            <span class="badge bg-light-warning text-warning fs-10">Business</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-lg bg-soft-info text-info rounded-circle">
                                <i class="feather-users fs-3"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">{{ $stats['delivery_partners'] }}</h5>
                                <p class="mb-0 text-muted fs-12">Delivery Partners</p>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center justify-content-between">
                            <span class="fs-12 text-muted">Total riders</span>
                            <span class="badge bg-light-info text-info fs-10">Fleet</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-lg bg-soft-success text-success rounded-circle">
                                <i class="feather-user fs-3"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">{{ $stats['customers'] }}</h5>
                                <p class="mb-0 text-muted fs-12">Customers</p>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center justify-content-between">
                            <span class="fs-12 text-muted">Total users</span>
                            <span class="badge bg-light-success text-success fs-10">Audience</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-3 col-md-6">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-text avatar-lg bg-soft-secondary text-secondary rounded-circle">
                                <i class="feather-grid fs-3"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">{{ $stats['total_foods'] }}</h5>
                                <p class="mb-0 text-muted fs-12">Food Items</p>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center justify-content-between">
                            <span class="fs-12 text-muted">Across all menus</span>
                            <span class="badge bg-light-secondary text-secondary fs-10">Catalog</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection


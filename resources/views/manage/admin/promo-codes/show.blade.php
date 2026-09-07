@extends('layouts.admin.main')

@section('title', 'Promo Code Details - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Promo Code Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.promo-codes.index') }}">Promo Codes</a></li>
                <li class="breadcrumb-item">Details</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="row g-3">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body text-center py-5">
                        <div class="text-muted fs-12 fw-semibold mb-2">PROMO CODE</div>
                        <div class="font-monospace fs-2 fw-bold" style="color:#cb202d;">{{ $promoCode->code }}</div>
                        <div class="mt-3">
                            @if($promoCode->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                            @if($promoCode->usage_status === 'used')
                                <span class="badge bg-success">Used</span>
                            @else
                                <span class="badge bg-secondary">Unused</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">Campaign Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-0" style="width:40%">Campaign Name</th>
                                    <td>{{ $promoCode->campaign_name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Code Type</th>
                                    <td>{{ $promoCode->code_type ? ucfirst($promoCode->code_type) : '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Discount Type</th>
                                    <td>{{ ucfirst($promoCode->discount_type) }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Discount Value</th>
                                    <td>
                                        @if($promoCode->discount_type === 'percentage')
                                            {{ $promoCode->discount_value }}%
                                        @else
                                            {{ $currencySymbol }}{{ number_format($promoCode->discount_value, 2) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Maximum Discount</th>
                                    <td>
                                        @if($promoCode->maximum_discount_amount)
                                            {{ $currencySymbol }}{{ number_format($promoCode->maximum_discount_amount, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Minimum Order</th>
                                    <td>{{ $currencySymbol }}{{ number_format($promoCode->minimum_order_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Per User Limit</th>
                                    <td>{{ $promoCode->per_user_limit }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Validity</th>
                                    <td>
                                        @if($promoCode->valid_from || $promoCode->valid_until)
                                            {{ $promoCode->valid_from?->format('d M Y') }} – {{ $promoCode->valid_until?->format('d M Y') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Assigned User</th>
                                    <td>
                                        @if($promoCode->assignedUser)
                                            <span class="fw-semibold">{{ $promoCode->assignedUser->name }}</span>
                                            @if($promoCode->used_at)
                                                <div class="text-muted fs-12">Used at {{ $promoCode->used_at->format('d M Y, h:i A') }}</div>
                                            @endif
                                        @else
                                            <span class="text-muted">Not assigned yet</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

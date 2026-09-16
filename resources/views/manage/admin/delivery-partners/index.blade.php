@extends('layouts.admin.main')

@section('title', getPageTitle('Delivery Partner Management'))

@php
    $kycBadge = function ($status) {
        return match ($status) {
            'pending' => '<span class="badge bg-soft-warning text-warning fw-semibold fs-12">Pending Review</span>',
            'approved' => '<span class="badge bg-soft-success text-success fw-semibold fs-12">Approved</span>',
            'rejected' => '<span class="badge bg-soft-danger text-danger fw-semibold fs-12">Rejected</span>',
            default => '<span class="badge bg-soft-secondary text-secondary fw-semibold fs-12">Not Submitted</span>',
        };
    };
    $statusBadge = function ($status) {
        return $status === 'active'
            ? '<span class="badge bg-soft-success text-success fw-semibold fs-12">Active</span>'
            : '<span class="badge bg-soft-danger text-danger fw-semibold fs-12">Inactive</span>';
    };
@endphp

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Delivery Partner Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Delivery Partners</li>
            </ul>
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

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="text-muted fs-12 mb-1">Total Partners</div>
                        <div class="fs-4 fw-bold">{{ $counts['all'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="text-muted fs-12 mb-1">Pending KYC</div>
                        <div class="fs-4 fw-bold text-warning">{{ $counts['pending'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="text-muted fs-12 mb-1">Approved</div>
                        <div class="fs-4 fw-bold text-success">{{ $counts['approved'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="text-muted fs-12 mb-1">Rejected</div>
                        <div class="fs-4 fw-bold text-danger">{{ $counts['rejected'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('admin.delivery-partners.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email or phone..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="kyc_status" class="form-select">
                            <option value="">All KYC Status</option>
                            <option value="not_submitted" {{ request('kyc_status') == 'not_submitted' ? 'selected' : '' }}>Not Submitted</option>
                            <option value="pending" {{ request('kyc_status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="approved" {{ request('kyc_status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('kyc_status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">Search</button>
                        <a href="{{ route('admin.delivery-partners.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Delivery Partners List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Partner</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>KYC Status</th>
                                <th>Account</th>
                                <th>Joined</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($partners as $partner)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-danger fw-bold" style="width: 38px; height: 38px;">
                                                {{ strtoupper(substr($partner->name, 0, 1)) }}
                                            </div>
                                            <div class="fw-bold text-dark fs-6">{{ $partner->name }}</div>
                                        </div>
                                    </td>
                                    <td><span class="text-muted fs-12">{{ $partner->email }}</span></td>
                                    <td><span class="text-muted fs-12">{{ $partner->phone ?? '—' }}</span></td>
                                    <td>{!! $kycBadge($partner->kyc_status) !!}</td>
                                    <td>{!! $statusBadge($partner->status) !!}</td>
                                    <td>
                                        <span class="text-muted fs-12">{{ $partner->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.delivery-partners.show', $partner->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" title="View KYC">
                                            <i class="feather-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No delivery partners found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($partners->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $partners->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

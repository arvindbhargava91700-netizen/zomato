@extends('layouts.admin.main')

@section('title', 'COD Settlements - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">COD Settlements</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">COD Settlements</li>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <a href="{{ route('admin.settlements.index', ['status' => 'pending']) }}" class="text-decoration-none">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-soft-warning text-warning d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="feather-clock"></i>
                            </div>
                            <div>
                                <div class="fs-14 fw-bold text-dark">{{ $pendingCount }}</div>
                                <div class="fs-12 text-muted">Pending</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.settlements.index', ['status' => 'paid']) }}" class="text-decoration-none">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-soft-success text-success d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="feather-check-circle"></i>
                            </div>
                            <div>
                                <div class="fs-14 fw-bold text-dark">{{ $paidCount }}</div>
                                <div class="fs-12 text-muted">Paid</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.settlements.index', ['status' => 'rejected']) }}" class="text-decoration-none">
                    <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                        <div class="card-body p-3 d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-soft-danger text-danger d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="feather-x-circle"></i>
                            </div>
                            <div>
                                <div class="fs-14 fw-bold text-dark">{{ $rejectedCount }}</div>
                                <div class="fs-12 text-muted">Rejected</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="feather-dollar-sign"></i>
                        </div>
                        <div>
                            <div class="fs-14 fw-bold text-dark">{{ $currencySymbol }}{{ number_format($totalAmount, 2) }}</div>
                            <div class="fs-12 text-muted">Total Collected</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="card stretch stretch-full mb-4">
            <div class="card-body p-3">
                <form action="{{ route('admin.settlements.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">Filter</button>
                        <a href="{{ route('admin.settlements.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Settlements Table -->
        <div class="card stretch stretch-full">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">
                    @if(request('status') === 'pending')
                        Pending Settlements
                    @elseif(request('status') === 'paid')
                        Paid Settlements
                    @elseif(request('status') === 'rejected')
                        Rejected Settlements
                    @else
                        All Settlements
                    @endif
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="customerList">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Settlement</th>
                                <th>Order</th>
                                <th>Delivery Partner</th>
                                <th>Amount</th>
                                <th>Transaction ID</th>
                                <th>Screenshot</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Submitted</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settlements as $settlement)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark fs-6">#{{ $settlement->id }}</div>
                                        <span class="text-muted fs-12">Reviewed by {{ $settlement->reviewer?->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">#{{ $settlement->order_id }}</div>
                                        <span class="text-muted fs-12">{{ $settlement->order?->payment_method ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $settlement->deliveryPartner?->name ?? '-' }}</div>
                                        <span class="text-muted fs-12">{{ $settlement->deliveryPartner?->phone ?? '' }}</span>
                                    </td>
                                    <td class="fw-bold text-dark">{{ $currencySymbol }}{{ number_format($settlement->amount, 2) }}</td>
                                    <td>
                                        <span class="text-dark fs-12 fw-semibold">{{ $settlement->transaction_id ?: '-' }}</span>
                                    </td>
                                    <td>
                                        @if($settlement->screenshot_url)
                                            <a href="{{ $settlement->screenshot_url }}" target="_blank" title="View payment screenshot">
                                                <img src="{{ $settlement->screenshot_url }}" alt="Payment screenshot"
                                                    class="rounded-2 border" style="width: 48px; height: 36px; object-fit: cover;">
                                            </a>
                                        @else
                                            <span class="text-muted fs-12">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="{{ \App\Models\CodSettlement::statusBadge($settlement->status) }} fs-12">
                                            {{ \App\Models\CodSettlement::statusLabel($settlement->status) }}
                                        </span>
                                        @if($settlement->reviewed_at)
                                            <span class="text-muted fs-11 d-block mt-1">{{ $settlement->reviewed_at->format('d M Y, h:i A') }}</span>
                                        @endif
                                    </td>
                                    <td style="max-width: 200px;">
                                        <span class="text-muted fs-12 d-block text-truncate" title="{{ $settlement->remark ?? '' }}">
                                            {{ $settlement->remark ?: '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($settlement->submitted_at)
                                            <span class="text-muted fs-12">{{ $settlement->submitted_at->format('d M Y, h:i A') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($settlement->isPending())
                                            <a href="{{ route('admin.settlements.show', $settlement->id) }}"
                                                class="btn btn-sm btn-success text-white fw-semibold rounded-2"
                                                data-bs-toggle="tooltip" title="Review &amp; Confirm">
                                                <i class="feather-check-circle me-1"></i>Review
                                            </a>
                                        @else
                                            <a href="{{ route('admin.settlements.show', $settlement->id) }}"
                                                class="btn btn-sm btn-light border text-info p-2 rounded-2"
                                                data-bs-toggle="tooltip" title="View">
                                                <i class="feather-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No settlements found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($settlements->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $settlements->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
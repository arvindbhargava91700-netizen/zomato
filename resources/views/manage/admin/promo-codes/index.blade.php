@extends('layouts.admin.main')

@section('title', getPageTitle('Promo Codes'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Promo Code / Campaign Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Promo Codes</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.promo-codes.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Create Campaign
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

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

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body py-3">
                        <div class="text-muted fs-12 fw-semibold">Total Codes</div>
                        <div class="fs-3 fw-bold">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body py-3">
                        <div class="text-muted fs-12 fw-semibold">Used</div>
                        <div class="fs-3 fw-bold text-success">{{ $stats['used'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body py-3">
                        <div class="text-muted fs-12 fw-semibold">Unused</div>
                        <div class="fs-3 fw-bold text-secondary">{{ $stats['unused'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body py-3">
                        <div class="text-muted fs-12 fw-semibold">Campaigns</div>
                        <div class="fs-3 fw-bold text-primary">{{ $stats['campaigns'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Campaign summary -->
        @if($campaigns->isNotEmpty())
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0 fw-bold">Campaigns</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($campaigns as $c)
                        <div class="col-md-6 col-lg-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="fw-bold fs-6">{{ $c->campaign_name }}</div>
                                    @if($c->code_type)
                                        <span class="badge bg-soft-info text-info fs-12">{{ ucfirst($c->code_type) }}</span>
                                    @endif
                                </div>
                                <div class="text-muted fs-13 mb-2">
                                    Discount:
                                    @if($c->discount_type === 'percentage')
                                        {{ $c->discount_value }}%
                                    @else
                                        {{ $currencySymbol }}{{ number_format($c->discount_value, 2) }}
                                    @endif
                                </div>
                                <div class="d-flex gap-4 fs-13">
                                    <span>Total: <b>{{ $c->total }}</b></span>
                                    <span class="text-success">Used: <b>{{ $c->used }}</b></span>
                                    <span class="text-secondary">Left: <b>{{ $c->total - $c->used }}</b></span>
                                </div>
                                <form action="{{ route('admin.promo-codes.delete-campaign') }}" method="POST" class="mt-2"
                                    onsubmit="return confirm('Delete campaign &quot;{{ $c->campaign_name }}&quot; and ALL its codes?');">
                                    @csrf
                                    <input type="hidden" name="campaign_name" value="{{ $c->campaign_name }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete Campaign</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Filter Card -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('admin.promo-codes.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by code or campaign..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="campaign_name" class="form-select">
                            <option value="">All Campaigns</option>
                            @foreach($campaignNames as $name)
                                <option value="{{ $name }}" {{ request('campaign_name') == $name ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="usage_status" class="form-select">
                            <option value="">All Usage</option>
                            <option value="unused" {{ request('usage_status') === 'unused' ? 'selected' : '' }}>Unused</option>
                            <option value="used" {{ request('usage_status') === 'used' ? 'selected' : '' }}>Used</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-4">Search</button>
                        <a href="{{ route('admin.promo-codes.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Promo Codes List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Code</th>
                                <th>Campaign</th>
                                <th>Type</th>
                                <th>Discount</th>
                                <th>Min Order</th>
                                <th>Max Disc.</th>
                                <th>Assigned User</th>
                                <th>Usage</th>
                                <th>Validity</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($codes as $code)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold font-monospace fs-6">{{ $code->code }}</span>
                                    </td>
                                    <td>{{ $code->campaign_name }}</td>
                                    <td>
                                        @if($code->code_type)
                                            <span class="badge bg-soft-info text-info fs-12">{{ ucfirst($code->code_type) }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($code->discount_type === 'percentage')
                                            <span class="fw-semibold">{{ $code->discount_value }}%</span>
                                        @else
                                            <span class="fw-semibold">{{ $currencySymbol }}{{ number_format($code->discount_value, 2) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $currencySymbol }}{{ number_format($code->minimum_order_amount, 2) }}</td>
                                    <td>
                                        @if($code->maximum_discount_amount)
                                            {{ $currencySymbol }}{{ number_format($code->maximum_discount_amount, 2) }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($code->assignedUser)
                                            <span class="fw-semibold">{{ $code->assignedUser->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($code->usage_status === 'used')
                                            <span class="badge bg-success">Used</span>
                                        @else
                                            <span class="badge bg-secondary">Unused</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($code->valid_from || $code->valid_until)
                                            <span class="fs-12">{{ $code->valid_from?->format('d M Y') }} – {{ $code->valid_until?->format('d M Y') }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.promo-codes.toggle-status', $code->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($code->status === 'active')
                                                <button type="submit" class="btn btn-sm btn-success border-0 px-3 rounded-pill fw-semibold" title="Click to Deactivate">Active</button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn-secondary border-0 px-3 rounded-pill fw-semibold" title="Click to Activate">Inactive</button>
                                            @endif
                                        </form>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            <a href="{{ route('admin.promo-codes.show', $code->id) }}" class="btn btn-sm btn-light border text-info p-2 rounded-2" title="View">
                                                <i class="feather-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.promo-codes.destroy', $code->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this promo code?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger p-2 rounded-2" title="Delete">
                                                    <i class="feather-trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No promo codes found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($codes->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $codes->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin.main')

@section('title', getPageTitle('Food Variants'))

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Food Variants Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Food Variants</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.food-variants.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add Food Variant
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

        <!-- Filter Card -->
        <div class="card stretch stretch-full mb-4 border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <form action="{{ route('admin.food-variants.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search variant name, food, or SKU..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="food_id" class="form-select">
                            <option value="">All Food Items</option>
                            @foreach($foods as $food)
                                <option value="{{ $food->id }}" {{ request('food_id') == $food->id ? 'selected' : '' }}>{{ $food->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-3">Filter</button>
                        <a href="{{ route('admin.food-variants.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                        <a href="{{ route('admin.food-variants.index', ['trashed' => 1]) }}" class="btn btn-outline-danger fw-semibold" title="View Trashed Variants">
                            <i class="feather-trash-2 me-1"></i> Trashed
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Food Variants List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Variant Name</th>
                                <th>Food Item</th>
                                <th>Restaurant</th>
                                <th>Price</th>
                                <th>Weight / Portion</th>
                                <th>Prep Time</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($variants as $variant)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark fs-6">{{ $variant->variant_name }}</div>
                                        <span class="text-muted fs-12">SKU: {{ $variant->sku ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-info text-info fw-semibold fs-12">{{ $variant->food->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-primary text-primary fw-semibold fs-12">{{ $variant->food->restaurant->restaurant_name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">₹{{ number_format($variant->price, 2) }}</div>
                                        @if($variant->sale_price)
                                            <small class="text-success text-decoration-line-through">₹{{ number_format($variant->sale_price, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($variant->weight)
                                            <span class="text-dark fw-medium">{{ $variant->weight }} {{ $variant->weight_unit }}</span>
                                        @else
                                            <span class="text-muted fs-12">{{ $variant->serving_size ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-secondary fs-12"><i class="feather-clock me-1"></i>{{ $variant->preparation_time ? $variant->preparation_time . ' mins' : 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if(!$variant->trashed())
                                            <form action="{{ route('admin.food-variants.toggle-status', $variant->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                @if($variant->status === 'active')
                                                    <button type="submit" class="btn btn-sm btn-success border-0 px-3 rounded-pill fw-semibold" title="Click to Deactivate">Active</button>
                                                @else
                                                    <button type="submit" class="btn btn-sm btn-secondary border-0 px-3 rounded-pill fw-semibold" title="Click to Activate">Inactive</button>
                                                @endif
                                            </form>
                                        @else
                                            <span class="badge bg-danger">Soft Deleted</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-1">
                                            @if(!$variant->trashed())
                                                <a href="{{ route('admin.food-variants.show', $variant->id) }}" class="btn btn-sm btn-light border text-info p-2 rounded-2" data-bs-toggle="tooltip" title="View Details">
                                                    <i class="feather-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.food-variants.edit', $variant->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.food-variants.destroy', $variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to soft delete this variant?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light border text-danger p-2 rounded-2" data-bs-toggle="tooltip" title="Soft Delete">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.food-variants.restore', $variant->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success p-2 rounded-2" title="Restore">
                                                        <i class="feather-rotate-ccw"></i> Restore
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.food-variants.force-delete', $variant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this variant?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger p-2 rounded-2" title="Permanently Delete">
                                                        <i class="feather-x-circle"></i> Permanent Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="feather-inbox display-6 d-block mb-2 text-secondary"></i>
                                        No food variants found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($variants->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $variants->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

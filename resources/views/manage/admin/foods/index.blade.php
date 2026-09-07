@extends('layouts.admin.main')

@section('title', 'Food Items - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Food Items Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Food Items</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.foods.create') }}" class="btn btn-danger text-white fw-semibold" style="background-color: #cb202d; border: none;">
                <i class="feather-plus me-1"></i> Add New Food Item
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
                <form action="{{ route('admin.foods.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="feather-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name or SKU..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="restaurant_id" class="form-select">
                            <option value="">All Restaurants</option>
                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->id }}" {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->restaurant_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="food_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="veg" {{ request('food_type') === 'veg' ? 'selected' : '' }}>Veg</option>
                            <option value="non_veg" {{ request('food_type') === 'non_veg' ? 'selected' : '' }}>Non-Veg</option>
                            <option value="egg" {{ request('food_type') === 'egg' ? 'selected' : '' }}>Egg</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold px-3">Filter</button>
                        <a href="{{ route('admin.foods.index') }}" class="btn btn-light border text-secondary fw-semibold">Reset</a>
                        <a href="{{ route('admin.foods.index', ['trashed' => 1]) }}" class="btn btn-outline-danger fw-semibold" title="View Trashed Foods">
                            <i class="feather-trash-2 me-1"></i> Trashed
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom py-3">
                <h5 class="card-title mb-0 fw-bold">Food Catalog</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Item</th>
                                <th>Restaurant</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Badges</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($foods as $food)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            @if($food->image)
                                                <img src="{{ asset($food->image) }}" alt="Food" class="rounded border" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px;">
                                                    <i class="feather-grid text-muted fs-4"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-dark fs-6">{{ $food->name }}</div>
                                                <span class="text-muted fs-12">SKU: {{ $food->sku ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-primary text-primary fw-semibold fs-12">{{ $food->restaurant->restaurant_name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-info text-info fw-semibold fs-12">{{ $food->category->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($food->food_type === 'veg')
                                            <span class="badge bg-success"><i class="feather-disc me-1"></i> Veg</span>
                                        @elseif($food->food_type === 'non_veg')
                                            <span class="badge bg-danger"><i class="feather-disc me-1"></i> Non-Veg</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="feather-disc me-1"></i> Egg</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">₹{{ number_format($food->base_price, 2) }}</div>
                                        @if($food->discount_price)
                                            <small class="text-success text-decoration-line-through">₹{{ number_format($food->discount_price, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            @if($food->is_featured)
                                                <span class="badge bg-warning text-dark fs-10" title="Featured Item">Featured</span>
                                            @endif
                                            @if($food->is_recommended)
                                                <span class="badge bg-info text-white fs-10" title="Chef Recommended">Recommended</span>
                                            @endif
                                            @if($food->is_spicy)
                                                <span class="badge bg-danger text-white fs-10" title="Spicy Dish">Spicy</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if(!$food->trashed())
                                            <form action="{{ route('admin.foods.toggle-status', $food->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                @if($food->status === 'active')
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
                                            @if(!$food->trashed())
                                                <a href="{{ route('admin.foods.show', $food->id) }}" class="btn btn-sm btn-light border text-info p-2 rounded-2" data-bs-toggle="tooltip" title="View Details">
                                                    <i class="feather-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.foods.edit', $food->id) }}" class="btn btn-sm btn-light border text-primary p-2 rounded-2" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.foods.destroy', $food->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to soft delete this food item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light border text-danger p-2 rounded-2" data-bs-toggle="tooltip" title="Soft Delete">
                                                        <i class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.foods.restore', $food->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success p-2 rounded-2" title="Restore">
                                                        <i class="feather-rotate-ccw"></i> Restore
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.foods.force-delete', $food->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this food item?');">
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
                                        No food items found in catalog.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($foods->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    <div class="d-flex justify-content-end">
                        {{ $foods->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

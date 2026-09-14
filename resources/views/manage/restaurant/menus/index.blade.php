@extends('layouts.restaurant.main')

@section('title', 'Menus - Zomato Partner')

@section('content')
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">My Menus</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Menus</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto d-flex align-items-center gap-2">
                <a href="{{ route('restaurant.menus.create') }}" class="btn btn-danger text-white fw-semibold"
                    style="background-color: #cb202d; border: none;">
                    <i class="feather-plus me-1"></i> Upload New Menu
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
                    <form action="{{ route('restaurant.menus.index') }}" method="GET" class="row g-2 align-items-center">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i
                                        class="feather-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0"
                                    placeholder="Search menu name..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-semibold px-3">Filter</button>
                            <a href="{{ route('restaurant.menus.index') }}"
                                class="btn btn-light border text-secondary fw-semibold">Reset</a>
                            <a href="{{ route('restaurant.menus.index', ['trashed' => 1]) }}"
                                class="btn btn-outline-danger fw-semibold" title="View Trashed Menus">
                                <i class="feather-trash-2 me-1"></i> Trashed
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                <div class="card-header border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold">Menus for {{ $restaurant->restaurant_name }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" >
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Menu</th>
                                    <th>Images</th>
                                    <th>Status</th>
                                    <th>Order</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                @if($menu->image)
                                                    <img src="{{ asset($menu->image) }}" alt="Menu" class="rounded border"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="rounded bg-light d-flex align-items-center justify-content-center border"
                                                        style="width: 50px; height: 50px;">
                                                        <i class="feather-book text-muted fs-4"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold text-dark fs-6">{{ $menu->name }}</div>
                                                    <span class="text-muted fs-12">Slug: {{ $menu->slug }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-info text-info fw-semibold fs-12">
                                                <i class="feather-image me-1"></i> {{ $menu->image ? 'Image Uploaded' : 'No Image' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(!$menu->trashed())
                                                <form action="{{ route('restaurant.menus.toggle-status', $menu->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($menu->status === 'active')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-success border-0 px-3 rounded-pill fw-semibold"
                                                            title="Click to Deactivate">Active</button>
                                                    @else
                                                        <button type="submit"
                                                            class="btn btn-sm btn-secondary border-0 px-3 rounded-pill fw-semibold"
                                                            title="Click to Activate">Inactive</button>
                                                    @endif
                                                </form>
                                            @else
                                                <span class="badge bg-danger">Soft Deleted</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $menu->sort_order }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                @if(!$menu->trashed())
                                                    <a href="{{ route('restaurant.menus.show', $menu->id) }}"
                                                        class="btn btn-sm btn-light border text-info p-2 rounded-2"
                                                        data-bs-toggle="tooltip" title="View Details">
                                                        <i class="feather-eye"></i>
                                                    </a>
                                                    <a href="{{ route('restaurant.menus.edit', $menu->id) }}"
                                                        class="btn btn-sm btn-light border text-primary p-2 rounded-2"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                    <form action="{{ route('restaurant.menus.destroy', $menu->id) }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to soft delete this menu?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-light border text-danger p-2 rounded-2"
                                                            data-bs-toggle="tooltip" title="Soft Delete">
                                                            <i class="feather-trash-2"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('restaurant.menus.restore', $menu->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success p-2 rounded-2"
                                                            title="Restore">
                                                            <i class="feather-rotate-ccw"></i> Restore
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('restaurant.menus.force-delete', $menu->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to permanently delete this menu?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger p-2 rounded-2"
                                                            title="Permanently Delete">
                                                            <i class="feather-x-circle"></i> Permanent Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-center py-5 text-muted">
                                                <i class="feather-book-open fs-1 text-secondary opacity-50 d-block mb-2"></i>
                                                <p class="mb-2 fw-medium">No menus uploaded yet.</p>
                                                <a href="{{ route('restaurant.menus.create') }}" class="btn btn-danger btn-sm text-white fw-semibold" style="background-color: #cb202d; border: none;">
                                                    <i class="feather-upload me-1"></i> Upload your first menu
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($menus->hasPages())
                    <div class="card-footer bg-white py-3 border-top">
                        <div class="d-flex justify-content-end">
                            {{ $menus->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection
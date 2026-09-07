@extends('layouts.admin.main')

@section('title', 'Edit Cuisine - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Cuisine</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.cuisines.index') }}">Cuisines</a></li>
                <li class="breadcrumb-item">Edit: {{ $cuisine->name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.cuisines.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('admin.cuisines.update', $cuisine->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Cuisine Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $cuisine->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="slug" class="form-label fw-semibold">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $cuisine->slug) }}">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="icon" class="form-label fw-semibold">Icon Image</label>
                            @if($cuisine->icon)
                                <div class="mb-2">
                                    <img src="{{ asset($cuisine->icon) }}" alt="Current Icon" class="rounded border" style="width: 45px; height: 45px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="icon" id="icon" class="form-control @error('icon') is-invalid @enderror" accept="image/*">
                            @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="image" class="form-label fw-semibold">Banner Image</label>
                            @if($cuisine->image)
                                <div class="mb-2">
                                    <img src="{{ asset($cuisine->image) }}" alt="Current Image" class="rounded border" style="width: 45px; height: 45px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sort_order" class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $cuisine->sort_order) }}" min="0">
                            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $cuisine->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $cuisine->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $cuisine->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Assign Restaurants -->
                        <div class="col-12 mt-4">
                            <label class="form-label fw-semibold">Assign to Restaurants</label>
                            <div class="row g-2 p-3 bg-light rounded-3 border" style="max-height: 200px; overflow-y: auto;">
                                @forelse($restaurants as $restaurant)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="restaurant_ids[]" value="{{ $restaurant->id }}" id="rest_{{ $restaurant->id }}" {{ in_array($restaurant->id, old('restaurant_ids', $assignedRestaurantIds)) ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark fw-medium" for="rest_{{ $restaurant->id }}">
                                                {{ $restaurant->restaurant_name }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted fs-12">No active restaurants found.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('admin.cuisines.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">Update Cuisine</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

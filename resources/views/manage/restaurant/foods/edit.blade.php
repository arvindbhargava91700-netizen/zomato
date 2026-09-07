@extends('layouts.restaurant.main')

@section('title', 'Edit Food Item - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Food Item</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.foods.index') }}">Food Items</a></li>
                <li class="breadcrumb-item">Edit: {{ $food->name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.foods.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('restaurant.foods.update', $food->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="food_category_id" class="form-label fw-semibold">Food Category <span class="text-danger">*</span></label>
                            <select name="food_category_id" id="food_category_id" class="form-select @error('food_category_id') is-invalid @enderror" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('food_category_id', $food->food_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('food_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Food Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $food->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="slug" class="form-label fw-semibold">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $food->slug) }}">
                            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="food_type" class="form-label fw-semibold">Food Type <span class="text-danger">*</span></label>
                            <select name="food_type" id="food_type" class="form-select @error('food_type') is-invalid @enderror" required>
                                <option value="veg" {{ old('food_type', $food->food_type) === 'veg' ? 'selected' : '' }}>Veg</option>
                                <option value="non_veg" {{ old('food_type', $food->food_type) === 'non_veg' ? 'selected' : '' }}>Non-Veg</option>
                                <option value="egg" {{ old('food_type', $food->food_type) === 'egg' ? 'selected' : '' }}>Egg</option>
                            </select>
                            @error('food_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="base_price" class="form-label fw-semibold">Base Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="base_price" id="base_price" class="form-control @error('base_price') is-invalid @enderror" value="{{ old('base_price', $food->base_price) }}" required>
                            @error('base_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="discount_price" class="form-label fw-semibold">Discounted Price (₹)</label>
                            <input type="number" step="0.01" name="discount_price" id="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price', $food->discount_price) }}">
                            @error('discount_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="tax_percentage" class="form-label fw-semibold">Tax Percentage (%)</label>
                            <input type="number" step="0.01" name="tax_percentage" id="tax_percentage" class="form-control @error('tax_percentage') is-invalid @enderror" value="{{ old('tax_percentage', $food->tax_percentage) }}" min="0" max="100">
                            @error('tax_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="preparation_time" class="form-label fw-semibold">Prep Time <small class="text-muted">(Mins)</small></label>
                            <input type="number" name="preparation_time" id="preparation_time" class="form-control @error('preparation_time') is-invalid @enderror" value="{{ old('preparation_time', $food->preparation_time) }}" min="1">
                            @error('preparation_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="sku" class="form-label fw-semibold">SKU Code</label>
                            <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $food->sku) }}">
                            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $food->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $food->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Flags / Checkboxes -->
                        <div class="col-12 my-2">
                            <label class="form-label fw-semibold d-block">Special Flags</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $food->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark fw-medium" for="is_featured">Featured Item</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_recommended" value="1" id="is_recommended" {{ old('is_recommended', $food->is_recommended) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark fw-medium" for="is_recommended">Chef Recommended</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_spicy" value="1" id="is_spicy" {{ old('is_spicy', $food->is_spicy) ? 'checked' : '' }}>
                                    <label class="form-check-label text-dark fw-medium" for="is_spicy">Spicy Dish</label>
                                </div>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="col-md-12">
                            <label for="image" class="form-label fw-semibold">Food Image</label>
                            @if($food->image)
                                <div class="mb-2">
                                    <img src="{{ asset($food->image) }}" alt="Current Food Image" class="rounded border" style="width: 70px; height: 70px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Cuisines Selection -->
                        <div class="col-12">
                            <label class="form-label fw-semibold">Associated Cuisines</label>
                            <div class="row g-2 p-3 bg-light rounded-3 border" style="max-height: 150px; overflow-y: auto;">
                                @forelse($cuisines as $cuisine)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="cuisine_ids[]" value="{{ $cuisine->id }}" id="cuis_{{ $cuisine->id }}" {{ in_array($cuisine->id, old('cuisine_ids', $assignedCuisineIds)) ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark" for="cuis_{{ $cuisine->id }}">{{ $cuisine->name }}</label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted fs-12">No cuisines assigned to your restaurant yet.</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="short_description" class="form-label fw-semibold">Short Description</label>
                            <textarea name="short_description" id="short_description" rows="2" class="form-control @error('short_description') is-invalid @enderror">{{ old('short_description', $food->short_description) }}</textarea>
                            @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Full Description</label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $food->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('restaurant.foods.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">Update Food Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

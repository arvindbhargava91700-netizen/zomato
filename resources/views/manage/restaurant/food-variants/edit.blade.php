@extends('layouts.restaurant.main')

@section('title', 'Edit Food Variant - Zomato Partner')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Food Variant</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('restaurant.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('restaurant.food-variants.index') }}">Food Variants</a></li>
                <li class="breadcrumb-item">Edit: {{ $foodVariant->variant_name }}</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('restaurant.food-variants.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <form action="{{ route('restaurant.food-variants.update', $foodVariant->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="food_id" class="form-label fw-semibold">Food Item <span class="text-danger">*</span></label>
                            <select name="food_id" id="food_id" class="form-select @error('food_id') is-invalid @enderror" required>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}" {{ old('food_id', $foodVariant->food_id) == $food->id ? 'selected' : '' }}>{{ $food->name }}</option>
                                @endforeach
                            </select>
                            @error('food_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="variant_name" class="form-label fw-semibold">Variant Name <span class="text-danger">*</span></label>
                            <input type="text" name="variant_name" id="variant_name" class="form-control @error('variant_name') is-invalid @enderror" value="{{ old('variant_name', $foodVariant->variant_name) }}" required>
                            @error('variant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="price" class="form-label fw-semibold">Selling Price (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $foodVariant->price) }}" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="sale_price" class="form-label fw-semibold">Sale / Promotional Price (₹)</label>
                            <input type="number" step="0.01" name="sale_price" id="sale_price" class="form-control @error('sale_price') is-invalid @enderror" value="{{ old('sale_price', $foodVariant->sale_price) }}">
                            @error('sale_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="cost_price" class="form-label fw-semibold">Cost Price (₹)</label>
                            <input type="number" step="0.01" name="cost_price" id="cost_price" class="form-control @error('cost_price') is-invalid @enderror" value="{{ old('cost_price', $foodVariant->cost_price) }}">
                            @error('cost_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="weight" class="form-label fw-semibold">Weight / Volume</label>
                            <input type="number" step="0.01" name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight', $foodVariant->weight) }}">
                            @error('weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="weight_unit" class="form-label fw-semibold">Weight Unit</label>
                            <select name="weight_unit" id="weight_unit" class="form-select @error('weight_unit') is-invalid @enderror">
                                <option value="">Select Unit</option>
                                <option value="g" {{ old('weight_unit', $foodVariant->weight_unit) === 'g' ? 'selected' : '' }}>g (Grams)</option>
                                <option value="kg" {{ old('weight_unit', $foodVariant->weight_unit) === 'kg' ? 'selected' : '' }}>kg (Kilograms)</option>
                                <option value="ml" {{ old('weight_unit', $foodVariant->weight_unit) === 'ml' ? 'selected' : '' }}>ml (Milliliters)</option>
                                <option value="l" {{ old('weight_unit', $foodVariant->weight_unit) === 'l' ? 'selected' : '' }}>l (Liters)</option>
                                <option value="pc" {{ old('weight_unit', $foodVariant->weight_unit) === 'pc' ? 'selected' : '' }}>pc (Pieces)</option>
                            </select>
                            @error('weight_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="serving_size" class="form-label fw-semibold">Serving Size</label>
                            <input type="text" name="serving_size" id="serving_size" class="form-control @error('serving_size') is-invalid @enderror" value="{{ old('serving_size', $foodVariant->serving_size) }}">
                            @error('serving_size') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="preparation_time" class="form-label fw-semibold">Prep Time <small class="text-muted">(Mins)</small></label>
                            <input type="number" name="preparation_time" id="preparation_time" class="form-control @error('preparation_time') is-invalid @enderror" value="{{ old('preparation_time', $foodVariant->preparation_time) }}" min="1">
                            @error('preparation_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="sku" class="form-label fw-semibold">SKU Code</label>
                            <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $foodVariant->sku) }}">
                            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $foodVariant->status) === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $foodVariant->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="sort_order" class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $foodVariant->sort_order) }}" min="0">
                            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('restaurant.food-variants.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                        <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">Update Variant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

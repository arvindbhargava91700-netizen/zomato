@extends('layouts.admin.main')

@section('title', 'Add Food Item - Admin Dashboard')

@section('content')
<div class="nxl-content">
    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Add Food Item</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.foods.index') }}">Food Items</a></li>
                <li class="breadcrumb-item">Add New</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <a href="{{ route('admin.foods.index') }}" class="btn btn-light border text-secondary fw-semibold">
                <i class="feather-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <!-- [ page-header ] end -->

    <!-- [ Main Content ] start -->
    <div class="main-content">
        <div class="row g-4">
            <!-- Left Column: Add Food Form -->
            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.foods.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="restaurant_id" class="form-label fw-semibold">Restaurant <span class="text-danger">*</span></label>
                                    <select name="restaurant_id" id="restaurant_id" class="form-select @error('restaurant_id') is-invalid @enderror" required>
                                        <option value="">Select Restaurant</option>
                                        @foreach($restaurants as $restaurant)
                                            <option value="{{ $restaurant->id }}" {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->restaurant_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('restaurant_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="food_category_id" class="form-label fw-semibold">Food Category <span class="text-danger">*</span></label>
                                    <select name="food_category_id" id="food_category_id" class="form-select @error('food_category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('food_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('food_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Food Item Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Paneer Butter Masala">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="slug" class="form-label fw-semibold">Slug <small class="text-muted">(Optional, auto-generated)</small></label>
                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="paneer-butter-masala">
                                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="food_type" class="form-label fw-semibold">Food Type <span class="text-danger">*</span></label>
                                    <select name="food_type" id="food_type" class="form-select @error('food_type') is-invalid @enderror" required>
                                        <option value="veg" {{ old('food_type') === 'veg' ? 'selected' : '' }}>Veg</option>
                                        <option value="non_veg" {{ old('food_type') === 'non_veg' ? 'selected' : '' }}>Non-Veg</option>
                                        <option value="egg" {{ old('food_type') === 'egg' ? 'selected' : '' }}>Egg</option>
                                    </select>
                                    @error('food_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="base_price" class="form-label fw-semibold">Base Price (₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="base_price" id="base_price" class="form-control @error('base_price') is-invalid @enderror" value="{{ old('base_price') }}" required placeholder="250.00">
                                    @error('base_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="discount_price" class="form-label fw-semibold">Discounted Price (₹)</label>
                                    <input type="number" step="0.01" name="discount_price" id="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price') }}" placeholder="220.00">
                                    @error('discount_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="tax_percentage" class="form-label fw-semibold">Tax Percentage (%)</label>
                                    <input type="number" step="0.01" name="tax_percentage" id="tax_percentage" class="form-control @error('tax_percentage') is-invalid @enderror" value="{{ old('tax_percentage', 5.00) }}" min="0" max="100">
                                    @error('tax_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="preparation_time" class="form-label fw-semibold">Prep Time <small class="text-muted">(Mins)</small></label>
                                    <input type="number" name="preparation_time" id="preparation_time" class="form-control @error('preparation_time') is-invalid @enderror" value="{{ old('preparation_time', 20) }}" min="1">
                                    @error('preparation_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="sku" class="form-label fw-semibold">SKU Code</label>
                                    <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="FOD-101">
                                    @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Flags / Checkboxes -->
                                <div class="col-12 my-2">
                                    <label class="form-label fw-semibold d-block">Special Flags</label>
                                    <div class="d-flex gap-4 flex-wrap">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark fw-medium" for="is_featured">Featured Item</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_recommended" value="1" id="is_recommended" {{ old('is_recommended') ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark fw-medium" for="is_recommended">Chef Recommended</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_spicy" value="1" id="is_spicy" {{ old('is_spicy') ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark fw-medium" for="is_spicy">Spicy Dish</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload -->
                                <div class="col-md-12">
                                    <label for="image" class="form-label fw-semibold">Food Image <small class="text-muted">(JPG, PNG, WEBP, Max 2MB)</small></label>
                                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Cuisines Selection -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Associated Cuisines</label>
                                    <div class="row g-2 p-3 bg-light rounded-3 border" style="max-height: 150px; overflow-y: auto;">
                                        @foreach($cuisines as $cuisine)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="cuisine_ids[]" value="{{ $cuisine->id }}" id="cuis_{{ $cuisine->id }}" {{ is_array(old('cuisine_ids')) && in_array($cuisine->id, old('cuisine_ids')) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="cuis_{{ $cuisine->id }}">{{ $cuisine->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="short_description" class="form-label fw-semibold">Short Description</label>
                                    <textarea name="short_description" id="short_description" rows="2" class="form-control @error('short_description') is-invalid @enderror" placeholder="Brief summary of dish ingredients...">{{ old('short_description') }}</textarea>
                                    @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold">Full Description</label>
                                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Detailed description of the food item...">{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                                <a href="{{ route('admin.foods.index') }}" class="btn btn-light border px-4 fw-semibold">Cancel</a>
                                <button type="submit" class="btn btn-danger text-white px-5 fw-semibold" style="background-color: #cb202d; border: none;">Save Food Item</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Restaurant Existing Food Items -->
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 shadow-sm rounded-3 sticky-top" style="top: 85px; z-index: 10;">
                    <div class="card-header bg-white border-bottom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="card-title fw-bold mb-0 text-dark">
                                    <i class="feather-coffee text-danger me-1"></i> Restaurant Menu
                                </h6>
                                <small class="text-muted" id="selected-restaurant-name">Select a restaurant</small>
                            </div>
                            <span class="badge bg-soft-danger text-danger border border-danger-subtle rounded-pill px-2 py-1 fs-12 fw-semibold" id="restaurant-food-count">0 Items</span>
                        </div>
                        <div class="mt-2" id="search-container" style="display: none;">
                            <input type="text" id="food-search-filter" class="form-control form-control-sm" placeholder="🔍 Search existing items...">
                        </div>
                    </div>

                    <div class="card-body p-3" id="restaurant-foods-container" style="max-height: 620px; overflow-y: auto;">
                        <!-- Placeholder State -->
                        <div class="text-center py-5 text-muted" id="empty-prompt">
                            <i class="feather-shopping-bag fs-1 text-secondary opacity-50 d-block mb-2"></i>
                            <p class="mb-0 fw-medium">Select a Restaurant to view its existing dishes & menu items.</p>
                        </div>

                        <!-- Loading State -->
                        <div class="text-center py-5" id="loading-spinner" style="display: none;">
                            <div class="spinner-border text-danger spinner-border-sm mb-2" role="status"></div>
                            <p class="text-muted mb-0 fs-13">Loading restaurant dishes...</p>
                        </div>

                        <!-- Foods List Container -->
                        <div class="d-flex flex-column gap-2" id="foods-list" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Real-time auto-slug generator
        var nameInput = document.getElementById('name');
        var slugInput = document.getElementById('slug');
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', function() {
                var slug = this.value.toString().toLowerCase().trim()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
                slugInput.value = slug;
            });
        }

        // 2. Fetch and render existing food items of selected restaurant
        var restaurantSelect = document.getElementById('restaurant_id');
        var emptyPrompt = document.getElementById('empty-prompt');
        var loadingSpinner = document.getElementById('loading-spinner');
        var foodsList = document.getElementById('foods-list');
        var foodCountBadge = document.getElementById('restaurant-food-count');
        var restaurantNameSub = document.getElementById('selected-restaurant-name');
        var searchContainer = document.getElementById('search-container');
        var searchInput = document.getElementById('food-search-filter');

        var currentFoods = [];

        function renderFoods(items) {
            foodsList.innerHTML = '';
            if (items.length === 0) {
                foodsList.innerHTML = '<div class="text-center py-4 text-muted fs-13">No matching food items found.</div>';
                return;
            }

            items.forEach(function(food) {
                var vegBadge = food.food_type === 'veg' 
                    ? '<span class="badge bg-success-subtle text-success border border-success-subtle px-1 fs-10">VEG</span>'
                    : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-1 fs-10">NON-VEG</span>';

                var variantsBadge = food.variants_count > 0 
                    ? '<span class="badge bg-info-subtle text-info border border-info-subtle fs-10 me-1">' + food.variants_count + ' Variants</span>' 
                    : '';

                var priceDisplay = food.discount_price 
                    ? '<strong class="text-danger">₹' + food.discount_price + '</strong> <del class="text-muted fs-11 ms-1">₹' + food.base_price + '</del>'
                    : '<strong class="text-dark">₹' + food.base_price + '</strong>';

                var variantsHtml = '';
                if (food.variants && food.variants.length > 0) {
                    variantsHtml = '<div class="mt-2 pt-2 border-top d-flex flex-wrap gap-1">'
                        + '<span class="fs-10 text-muted w-100 fw-semibold text-uppercase">Sizes / Variants:</span>';
                    food.variants.forEach(function(v) {
                        variantsHtml += '<span class="badge bg-white text-dark border fw-normal fs-11 px-2 py-1">'
                            + '<i class="feather-tag text-danger me-1"></i>' + v.name + ': <strong class="text-danger ms-1">₹' + v.display_price + '</strong>'
                            + '</span>';
                    });
                    variantsHtml += '</div>';
                }

                var itemHtml = ''
                    + '<div class="p-2 border rounded-2 bg-light bg-opacity-50 food-card-item" data-name="' + food.name.toLowerCase() + '" data-category="' + food.category_name.toLowerCase() + '">'
                    +   '<div class="d-flex align-items-center justify-content-between gap-2">'
                    +     '<div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden">'
                    +       '<img src="' + food.image + '" alt="' + food.name + '" class="rounded-2 object-fit-cover border flex-shrink-0" style="width: 44px; height: 44px;">'
                    +       '<div class="overflow-hidden">'
                    +         '<div class="d-flex align-items-center gap-1 mb-1">'
                    +           vegBadge
                    +           '<span class="badge bg-light text-secondary border fs-10 text-truncate">' + food.category_name + '</span>'
                    +         '</div>'
                    +         '<h6 class="fw-semibold text-dark mb-0 fs-13 text-truncate" title="' + food.name + '">' + food.name + '</h6>'
                    +         '<div class="fs-12 mt-1 d-flex align-items-center">' + variantsBadge + priceDisplay + '</div>'
                    +       '</div>'
                    +     '</div>'
                    +     '<div class="flex-shrink-0 text-end">'
                    +       '<a href="' + food.edit_url + '" target="_blank" class="btn btn-sm btn-light border px-2 py-1 fs-11" title="Edit in new tab"><i class="feather-edit text-secondary"></i></a>'
                    +     '</div>'
                    +   '</div>'
                    +   variantsHtml
                    + '</div>';

                foodsList.insertAdjacentHTML('beforeend', itemHtml);
            });
        }

        function fetchRestaurantFoods(restaurantId) {
            if (!restaurantId) {
                emptyPrompt.style.display = 'block';
                loadingSpinner.style.display = 'none';
                foodsList.style.display = 'none';
                searchContainer.style.display = 'none';
                foodCountBadge.innerText = '0 Items';
                restaurantNameSub.innerText = 'Select a restaurant';
                return;
            }

            emptyPrompt.style.display = 'none';
            loadingSpinner.style.display = 'block';
            foodsList.style.display = 'none';
            searchContainer.style.display = 'none';

            var selectedOption = restaurantSelect.options[restaurantSelect.selectedIndex];
            if (selectedOption) {
                restaurantNameSub.innerText = selectedOption.text;
            }

            var url = '{{ route("admin.restaurants.foods-ajax", ":id") }}'.replace(':id', restaurantId);

            fetch(url)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    loadingSpinner.style.display = 'none';
                    if (data.status && data.foods) {
                        currentFoods = data.foods;
                        foodCountBadge.innerText = currentFoods.length + ' Items';
                        if (currentFoods.length > 0) {
                            searchContainer.style.display = 'block';
                            foodsList.style.display = 'flex';
                            renderFoods(currentFoods);
                        } else {
                            foodsList.style.display = 'block';
                            foodsList.innerHTML = '<div class="text-center py-4 text-muted fs-13"><i class="feather-alert-circle d-block mb-1 fs-4 text-warning"></i>This restaurant does not have any dishes added yet.</div>';
                        }
                    }
                })
                .catch(function(err) {
                    loadingSpinner.style.display = 'none';
                    foodsList.style.display = 'block';
                    foodsList.innerHTML = '<div class="text-center py-3 text-danger fs-13">Failed to load restaurant dishes.</div>';
                });
        }

        if (restaurantSelect) {
            restaurantSelect.addEventListener('change', function() {
                fetchRestaurantFoods(this.value);
            });

            // Initial load if already selected
            if (restaurantSelect.value) {
                fetchRestaurantFoods(restaurantSelect.value);
            }
        }

        // Search filter listener
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                var query = this.value.toLowerCase().trim();
                if (!query) {
                    renderFoods(currentFoods);
                    return;
                }
                var filtered = currentFoods.filter(function(f) {
                    return f.name.toLowerCase().includes(query) || f.category_name.toLowerCase().includes(query);
                });
                renderFoods(filtered);
            });
        }
    });
</script>
@endpush

@php
    $featureCheckboxes = \App\Models\RestaurantFeature::active()->orderBy('sort_order')->get();
    $selectedFeatures = $selected ?? [];
@endphp
@if($featureCheckboxes->count() > 0)
    <div class="row g-3">
        @foreach ($featureCheckboxes as $feature)
            <div class="col-md-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input feature-check"
                        name="features[]" value="{{ $feature->id }}"
                        id="feature_{{ $feature->id }}"
                        {{ in_array($feature->id, $selectedFeatures) ? 'checked' : '' }}>
                    <label class="form-check-label" for="feature_{{ $feature->id }}">
                        @if($feature->icon)
                            <i class="{{ $feature->icon }} me-1 text-danger"></i>
                        @endif
                        {{ $feature->name }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info py-3 mb-0">
        <i class="feather-info me-2"></i> No restaurant features available yet. Please ask the admin to add features.
    </div>
@endif
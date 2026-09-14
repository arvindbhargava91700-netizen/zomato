<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRestaurantFeatureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $feature = $this->route('restaurantFeature');
        $featureId = is_object($feature) ? $feature->id : $feature;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('restaurant_features', 'name')->ignore($featureId)->whereNull('deleted_at')],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('restaurant_features', 'slug')->ignore($featureId)->whereNull('deleted_at')],
            'icon' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}

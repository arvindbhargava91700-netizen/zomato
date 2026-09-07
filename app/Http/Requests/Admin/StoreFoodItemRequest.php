<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFoodItemRequest extends FormRequest
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
        $restaurantId = $this->input('restaurant_id');

        return [
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'food_category_id' => ['required', 'exists:food_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('foods', 'slug')->where('restaurant_id', $restaurantId)->whereNull('deleted_at'),
            ],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'sku' => ['nullable', 'string', 'max:100'],
            'food_type' => ['required', 'in:veg,non_veg,egg'],
            'is_veg' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'is_recommended' => ['nullable', 'boolean'],
            'is_spicy' => ['nullable', 'boolean'],
            'preparation_time' => ['nullable', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:base_price'],
            'tax_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'cuisine_ids' => ['nullable', 'array'],
            'cuisine_ids.*' => ['exists:cuisines,id'],
        ];
    }
}

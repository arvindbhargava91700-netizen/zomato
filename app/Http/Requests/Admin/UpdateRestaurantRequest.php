<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantRequest extends FormRequest
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
        $restaurantId = $this->route('restaurant') ? $this->route('restaurant')->id : $this->id;

        return [
            'brand_id' => ['nullable', 'exists:brands,id'],
            'restaurant_name' => ['required', 'string', 'max:255'],
            'restaurant_slug' => ['nullable', 'string', 'max:255', 'unique:restaurants,restaurant_slug,' . $restaurantId],
            'owner_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:restaurants,email,' . $restaurantId],
            'mobile' => ['required', 'string', 'max:20', 'unique:restaurants,mobile,' . $restaurantId],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
            'qr_code' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'description' => ['nullable', 'string'],
            'gst_number' => ['nullable', 'string', 'max:50'],
            'fssai_number' => ['nullable', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'opening_time' => ['nullable'],
            'closing_time' => ['nullable'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'delivery_radius' => ['nullable', 'numeric', 'min:0'],
            'estimated_delivery_time' => ['nullable', 'integer', 'min:0'],
            'commission_percentage' => ['nullable', 'numeric', 'between:0,100'],
            'dining_commission_percentage' => ['nullable', 'numeric', 'between:0,100'],
            'is_pure_veg' => ['nullable', 'boolean'],
            'pet_friendly' => ['nullable', 'boolean'],
            'outdoor_seating' => ['nullable', 'boolean'],
            'serves_alcohol' => ['nullable', 'boolean'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive,pending'],
        ];
    }
}

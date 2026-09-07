<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCuisineRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('cuisines', 'name')->whereNull('deleted_at')],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('cuisines', 'slug')->whereNull('deleted_at')],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:1024'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'in:active,inactive'],
            'restaurant_ids' => ['nullable', 'array'],
            'restaurant_ids.*' => ['exists:restaurants,id'],
        ];
    }
}

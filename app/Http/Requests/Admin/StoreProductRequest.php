<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug'],
            'product_type' => ['required', 'in:physical,digital'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:3072'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,webp,svg', 'max:3072'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'array'],
            'features.*' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:active,inactive,draft'],
            'is_featured' => ['nullable', 'boolean'],

            // Physical product fields
            'sku' => ['nullable', 'string', 'max:100'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'shipping_charge' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'string', 'max:100'],
            'dimensions' => ['nullable', 'string', 'max:100'],
            'color' => ['nullable', 'string', 'max:100'],
            'warranty_period' => ['nullable', 'string', 'max:255'],
            'package_contents' => ['nullable', 'string', 'max:500'],

            // Digital product fields
            'digital_delivery_type' => ['nullable', 'in:credentials,license_key,direct_activation,file_download'],
            'access_instructions' => ['nullable', 'string'],
            'download_link' => ['nullable', 'url', 'max:500'],
            'download_file' => ['nullable', 'file', 'max:51200'],
            'default_validity' => ['nullable', 'string', 'max:100'],
            'has_plans' => ['nullable', 'boolean'],

            // Subscription plans / duration variations
            'plans' => ['nullable', 'array'],
            'plans.*.name' => ['required_with:plans', 'string', 'max:100'],
            'plans.*.validity' => ['nullable', 'string', 'max:100'],
            'plans.*.price' => ['required_with:plans', 'numeric', 'min:0'],
            'plans.*.sale_price' => ['nullable', 'numeric', 'min:0'],
            'plans.*.stock_quantity' => ['nullable', 'integer', 'min:0'],
            'plans.*.sku' => ['nullable', 'string', 'max:100'],
            'plans.*.is_default' => ['nullable', 'boolean'],
        ];
    }
}

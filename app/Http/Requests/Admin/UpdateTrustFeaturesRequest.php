<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTrustFeaturesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trust_features_enabled' => ['nullable', 'boolean'],
            'trust_features_speed' => ['required', 'integer', 'min:5', 'max:120'],
            'items' => ['nullable', 'array'],
            'items.*.icon' => ['nullable', 'string', 'max:255'],
            'items.*.text' => ['nullable', 'string', 'max:255'],
            'items.*.link' => ['nullable', 'string', 'max:500'],
        ];
    }
}

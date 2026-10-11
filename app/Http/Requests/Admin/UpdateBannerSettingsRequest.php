<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerSettingsRequest extends FormRequest
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
            'banner_autoplay' => ['nullable', 'boolean'],
            'banner_delay' => ['required', 'integer', 'min:1000', 'max:30000'],
            'banner_arrows' => ['nullable', 'boolean'],
            'banner_dots' => ['nullable', 'boolean'],
        ];
    }
}

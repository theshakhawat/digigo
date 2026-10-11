<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHeaderSettingsRequest extends FormRequest
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
            // Branding & Logo
            'site_name' => ['nullable', 'string', 'max:100'],
            'site_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:3072'],
            'remove_site_logo' => ['nullable', 'boolean'],
            'site_favicon' => ['nullable', 'file', 'mimes:ico,png,svg,jpg,jpeg', 'max:1024'],
            'remove_site_favicon' => ['nullable', 'boolean'],
            'header_search_placeholder' => ['nullable', 'string', 'max:255'],

            // Top Bar Information
            'topbar_announcement_enabled' => ['nullable', 'boolean'],
            'topbar_announcement_text' => ['nullable', 'string', 'max:255'],
            'topbar_announcement_link' => ['nullable', 'string', 'max:255'],
            'topbar_support_label' => ['nullable', 'string', 'max:50'],
            'topbar_support_phone' => ['nullable', 'string', 'max:50'],
            'topbar_whatsapp_label' => ['nullable', 'string', 'max:50'],
            'topbar_whatsapp_phone' => ['nullable', 'string', 'max:50'],

            // SEO & Meta Data
            'meta_title' => ['nullable', 'string', 'max:200'],
            'meta_title_separator' => ['nullable', 'string', 'max:10'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'meta_author' => ['nullable', 'string', 'max:100'],
            'meta_robots' => ['nullable', 'string', 'max:50'],
            'og_title' => ['nullable', 'string', 'max:200'],
            'og_description' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'remove_og_image' => ['nullable', 'boolean'],
            'twitter_card' => ['nullable', 'string', 'in:summary,summary_large_image'],
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFooterSettingsRequest extends FormRequest
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
            // Brand & About
            'footer_about_text' => ['nullable', 'string', 'max:500'],
            'footer_custom_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:3072'],
            'remove_footer_custom_logo' => ['nullable', 'boolean'],

            // Column 2
            'footer_col2_title' => ['required', 'string', 'max:100'],
            'footer_col2_link1_text' => ['nullable', 'string', 'max:100'],
            'footer_col2_link1_url' => ['nullable', 'string', 'max:255'],
            'footer_col2_link2_text' => ['nullable', 'string', 'max:100'],
            'footer_col2_link2_url' => ['nullable', 'string', 'max:255'],
            'footer_col2_link3_text' => ['nullable', 'string', 'max:100'],
            'footer_col2_link3_url' => ['nullable', 'string', 'max:255'],
            'footer_col2_link4_text' => ['nullable', 'string', 'max:100'],
            'footer_col2_link4_url' => ['nullable', 'string', 'max:255'],

            // Column 3
            'footer_col3_title' => ['required', 'string', 'max:100'],
            'footer_col3_link1_text' => ['nullable', 'string', 'max:100'],
            'footer_col3_link1_url' => ['nullable', 'string', 'max:255'],
            'footer_col3_link2_text' => ['nullable', 'string', 'max:100'],
            'footer_col3_link2_url' => ['nullable', 'string', 'max:255'],
            'footer_col3_link3_text' => ['nullable', 'string', 'max:100'],
            'footer_col3_link3_url' => ['nullable', 'string', 'max:255'],
            'footer_col3_link4_text' => ['nullable', 'string', 'max:100'],
            'footer_col3_link4_url' => ['nullable', 'string', 'max:255'],

            // Column 4 (Apps & Social)
            'footer_app_title' => ['nullable', 'string', 'max:100'],
            'footer_play_store_enabled' => ['nullable', 'boolean'],
            'footer_play_store_url' => ['nullable', 'string', 'max:255'],
            'footer_app_store_enabled' => ['nullable', 'boolean'],
            'footer_app_store_url' => ['nullable', 'string', 'max:255'],
            'footer_social_title' => ['nullable', 'string', 'max:100'],
            'footer_facebook_url' => ['nullable', 'string', 'max:255'],
            'footer_instagram_url' => ['nullable', 'string', 'max:255'],
            'footer_whatsapp_url' => ['nullable', 'string', 'max:255'],
            'footer_youtube_url' => ['nullable', 'string', 'max:255'],
            'footer_twitter_url' => ['nullable', 'string', 'max:255'],

            // Bottom Bar & Copyright
            'footer_copyright_text' => ['required', 'string', 'max:255'],
            'footer_payment_methods_enabled' => ['nullable', 'boolean'],
            'footer_payment_bkash' => ['nullable', 'boolean'],
            'footer_payment_nagad' => ['nullable', 'boolean'],
            'footer_payment_rocket' => ['nullable', 'boolean'],
            'footer_payment_upay' => ['nullable', 'boolean'],
            'footer_payment_cards' => ['nullable', 'boolean'],

            // Floating Widget
            'footer_floating_chat_enabled' => ['nullable', 'boolean'],
            'footer_floating_chat_link' => ['nullable', 'string', 'max:255'],
        ];
    }
}

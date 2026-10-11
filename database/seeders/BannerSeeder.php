<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Elevate your audio 🎧 experience',
                'subtitle' => 'Immerse yourself in crystal clear sound with our premium wireless headphones and high-fidelity earbuds at best prices.',
                'badge_text' => '🎧',
                'button_text' => 'SHOP NOW',
                'button_link' => '#audio-sound',
                'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=1600&q=80',
                'order' => 1,
                'status' => 'active',
            ],
            [
                'title' => 'Premium AI Tools 🚀 & Software Keys',
                'subtitle' => 'Supercharge your workflow with genuine licenses for top AI tools, creative software, and productivity suites with instant delivery.',
                'badge_text' => '🚀',
                'button_text' => 'EXPLORE DEALS',
                'button_link' => '#ai-tools',
                'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1600&q=80',
                'order' => 2,
                'status' => 'active',
            ],
            [
                'title' => 'Smart Devices ⌚ & Wearable Tech',
                'subtitle' => 'Discover the latest smartwatches, fitness trackers, and modern smart accessories engineered for your everyday lifestyle.',
                'badge_text' => '⌚',
                'button_text' => 'ORDER NOW',
                'button_link' => '#smartwatches',
                'image' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?auto=format&fit=crop&w=1600&q=80',
                'order' => 3,
                'status' => 'active',
            ],
            [
                'title' => 'Fast Charging ⚡ & Power Solutions',
                'subtitle' => 'Ultra-compact, lightning-fast 20W to 100W GaN chargers and power banks built to keep all your gadgets charged anywhere.',
                'badge_text' => '⚡',
                'button_text' => 'SHOP NOW',
                'button_link' => '#power-charging',
                'image' => 'https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=1600&q=80',
                'order' => 4,
                'status' => 'active',
            ],
        ];

        foreach ($banners as $bannerData) {
            Banner::updateOrCreate(
                ['title' => $bannerData['title']],
                $bannerData
            );
        }
    }
}

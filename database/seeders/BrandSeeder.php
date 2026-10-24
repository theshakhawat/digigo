<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            'OpenAI',
            'Canva',
            'Netflix',
            'Microsoft',
            'Google',
            'Spotify',
            'Adobe',
            'Steam',
            'Apple',
            'Samsung',
            'Anker',
            'Sony',
            'Xiaomi',
            'Logitech',
            'TP-Link',
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['slug' => Str::slug($brand)],
                [
                    'name' => $brand,
                    'image' => null,
                    'status' => 'active',
                ]
            );
        }
    }
}

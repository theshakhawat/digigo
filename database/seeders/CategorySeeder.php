<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Audio & Sound',
                'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=200&h=200&q=80',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Smartwatches & Accessories',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=200&h=200&q=80',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Power & Charging Solutions',
                'image' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=200&h=200&q=80',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Computer Peripherals',
                'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=200&h=200&q=80',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Networking & Smart Security',
                'image' => 'https://images.unsplash.com/photo-1557597774-9d273605dfa9?auto=format&fit=crop&w=200&h=200&q=80',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Lighting & Studio Gear',
                'image' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=200&h=200&q=80',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Home & Kitchen',
                'image' => 'https://images.unsplash.com/photo-1584269600464-37b1b58a9fe7?auto=format&fit=crop&w=200&h=200&q=80',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Mobile Accessories & Lifestyle',
                'image' => 'https://images.unsplash.com/photo-1601784551446-20c9e07cdbdb?auto=format&fit=crop&w=200&h=200&q=80',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Lifestyle',
                'image' => 'https://images.unsplash.com/photo-1621607512214-68297480165e?auto=format&fit=crop&w=200&h=200&q=80',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Digital Goods',
                'image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=200&h=200&q=80',
                'status' => 'active',
                'is_featured' => true,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'image' => $cat['image'],
                    'status' => $cat['status'],
                    'is_featured' => $cat['is_featured'],
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@digigo.com.bd'],
            [
                'name' => 'Admin User',
                'phone' => '01700000000',
                'role' => 'admin',
                'status' => 'active',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@digigo.com.bd'],
            [
                'name' => 'Regular Customer',
                'phone' => '01800000000',
                'role' => 'user',
                'status' => 'active',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        User::updateOrCreate(
            ['email' => 'shakhawat9083@gmail.com'],
            [
                'name' => 'Shakhawat Hossain',
                'phone' => '01646362559',
                'role' => 'user',
                'status' => 'active',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            CategorySeeder::class,
            BrandSeeder::class,
            BannerSeeder::class,
            SettingSeeder::class,
        ]);
    }
}

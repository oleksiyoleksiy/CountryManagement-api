<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Country;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => 'password',
        ]);

        Building::create([
            'name' => 'shop 24/7',
            'image' => env('APP_URL') . '/api/image/shop.png',
            'cooldown' => 0.5,
            'resources_income' => [
                'money' => 50
            ],
            'resources_price' => [
                'money' => 500,
                'energy' => 10
            ]
        ]);
        Building::create([
            'name' => 'restaurant',
            'image' => env('APP_URL') . '/api/image/restaurant.png',
            'cooldown' => 1,
            'resources_income' => [
                'money' => 200
            ],
            'resources_price' => [
                'money' => 2000,
                'energy' => 20
            ]
        ]);
        Building::create([
            'name' => 'building company',
            'image' => env('APP_URL') . '/api/image/constructing.png',
            'cooldown' => 2.5,
            'resources_income' => [
                'money' => 700
            ],
            'resources_price' => [
                'money' => 7000,
                'energy' => 35
            ]
        ]);
        Building::create([
            'name' => 'TPP',
            'image' => env('APP_URL') . '/api/image/tpp.png',
            'resources_income' => [
                'energy' => 60
            ],
            'resources_price' => [
                'money' => 5000,
                'coal' => 30
            ]
        ]);
        Building::create([
            'name' => 'NPP',
            'image' => env('APP_URL') . '/api/image/npp.png',
            'resources_income' => [
                'energy' => 1000
            ],
            'resources_price' => [
                'money' => 50000,
                'uranium' => 20
            ]
        ]);
    }
}

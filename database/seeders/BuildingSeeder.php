<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Building::create([
            'name' => 'Shop 24/7',
            'image' => 'shop.png',
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
            'name' => 'Restaurant',
            'image' => 'restaurant.png',
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
            'name' => 'Building company',
            'image' => 'constructing.png',
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
            'image' => 'tpp.png',
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
            'image' => 'npp.png',
            'resources_income' => [
                'energy' => 1000
            ],
            'resources_price' => [
                'money' => 50000,
                'uranium' => 20
            ]
        ]);
        Building::create([
            'name' => 'Mining Industry',
            'image' => 'mining_industry.png',
            'cooldown' => 10,
            'resources_income' => [
                'coal' => 10,
                'iron' => 10,
                'copper' => 10
            ],
            'resources_price' => [
                'money' => 20000,
                'energy' => 120
            ]
        ]);

        Building::create([
            'name' => 'Oil Well',
            'image' => 'oil_well.png',
            'cooldown' => 8,
            'resources_income' => [
                'oil' => 15,
            ],
            'resources_price' => [
                'money' => 15000,
                'energy' => 90
            ]
        ]);
        Building::create([
            'name' => 'Uranium enrichment laboratory',
            'image' => 'uranium_laboratory.png',
            'cooldown' => 10,
            'resources_income' => [
                'uranium' => 5,
            ],
            'resources_price' => [
                'money' => 25000,
                'energy' => 130
            ]
        ]);
    }
}

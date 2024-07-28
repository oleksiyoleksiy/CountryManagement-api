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

        $user = User::create([
            'email' => 'test@gmail.com',
            'name' => 'test',
            'password' => 'password',
        ]);

        $user->countries()->create([
            'name' => 'test',
            'resources' => [
                'money' => 999999,
                'energy' => 999999,
                'oil' => 999999,
                'coal' => 999999,
                'copper' => 999999,
                'uranium' => 999999,
                'iron' => 999999,
            ],
            'available_resources' => [
                'oil',
                'uranium'
            ]
        ]);

        $this->call(BuildingSeeder::class);
    }
}

<?php

namespace App\Services\Building;

use App\Models\Building;
use Illuminate\Support\Facades\Cache;

class BuildingService
{
    public function index()
    {
        if (!Cache::has('buildings')) {
            Cache::put('buildings', Building::all(), now()->addHour());
        }

        $buildings = Cache::get('buildings');

        return $buildings;
    }
}

<?php

namespace App\Services\CountryBuilding;

use App\Models\Country;
use App\Models\Building;
use App\Models\CountryBuilding;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CountryBuildingService
{
    public function index(Country $country)
    {
        return $country->buildings;
    }

    public function store(Country $country, array $data)
    {
        $buildingId = $data['building_id'];

        $selectedBuilding = Building::find($buildingId);

        $existingBuilding = $country->buildings()->where('building_id', $buildingId)->first();

        $country->withdrawResources($selectedBuilding);

        if ($existingBuilding) {
            $country->buildings()->updateExistingPivot($buildingId, [
                'count' => $existingBuilding->pivot->count + 1,
                'income_at' => now()->addMinutes($existingBuilding->cooldown)
            ]);
        } else {
            $country->buildings()->attach($buildingId, [
                'count' => $data['count'] ?? 1,
                'income_at' => now()->addMinutes($selectedBuilding->cooldown)
            ]);
        }


        return $country;
    }


    public function collectIncome(Country $country, array $data)
    {
        $buildingId = $data['building_id'];

        $building = $country->buildings()->where('building_id', $buildingId)->first();

        $incomeTime = $building->pivot->income_at;


        $this->validateIncome($incomeTime);

        $buildingCount = $building->pivot->count;

        if ($building->resources_income) {
            $this->updateResourcesIncome($country, $building, $buildingCount);
        }

        $country->buildings()->updateExistingPivot($buildingId, [
            'income_at' => now()->addMinutes($building->cooldown)
        ]);

        return $country;
    }

    public function validateIncome($incomeTime)
    {
        if (now()->lte($incomeTime)) {
            $timeLeft = $this->getTimeUntil($incomeTime);

            throw ValidationException::withMessages([
                'message' => "until the next collect $timeLeft.",
            ]);
        }
    }


    public function getTimeUntil($targetDate)
    {
        $now = Carbon::now();
        $target = Carbon::parse($targetDate);
        $diff = $target->diffForHumans($now, ['parts' => 3, 'join' => ', ', 'syntax' => Carbon::DIFF_ABSOLUTE]);

        return $diff;
    }

    private function updateResourcesIncome($country, $building, $buildingCount)
    {
        $updatedResources = collect($country->resources)->mapWithKeys(function ($value, $resource) use ($building, $buildingCount) {
            $resourceIncome = array_key_exists($resource, $building->resources_income)
                ? $building->resources_income[$resource] * $buildingCount
                : 0;

            return [$resource => $value + $resourceIncome];
        });

        $country->update([
            'resources' => $updatedResources
        ]);
    }
}

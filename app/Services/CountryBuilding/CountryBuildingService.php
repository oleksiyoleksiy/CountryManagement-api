<?php

namespace App\Services\CountryBuilding;

use App\Enums\ResourceEnum;
use App\Models\Country;
use App\Models\Building;
use Illuminate\Support\Carbon;
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
        $selectedBuilding = Building::findOrFail($buildingId);

        $existingBuilding = $country->buildings()->where('building_id', $buildingId)->first();

        $this->withdrawResources($country, $selectedBuilding);

        if ($existingBuilding) {
            $this->updateExistingBuilding($country, $existingBuilding, $buildingId);
        } else {
            $this->attachNewBuilding($country, $selectedBuilding, $data);
        }

        return $country;
    }

    public function collectIncome(Country $country, array $data)
    {
        $buildingId = $data['building_id'];
        $building = $country->buildings()->where('building_id', $buildingId)->firstOrFail();

        $this->validateIncome($building->pivot->income_at);

        $this->updateResourcesIncome($country, $building, $building->pivot->count);

        $country->buildings()->updateExistingPivot($buildingId, [
            'income_at' => now()->addMinutes($building->cooldown)
        ]);

        return $country;
    }

    private function withdrawResources(Country $country, Building $building)
    {
        $country->withdrawResources($building);

        if (array_key_exists(ResourceEnum::ENERGY->value, $building->resources_income)) {
            $country->addResource(ResourceEnum::ENERGY, $building->resources_income[ResourceEnum::ENERGY->value]);
        }
    }

    private function updateExistingBuilding(Country $country, $existingBuilding, $buildingId)
    {
        $country->buildings()->updateExistingPivot($buildingId, [
            'count' => $existingBuilding->pivot->count + 1,
            'income_at' => now()->addMinutes($existingBuilding->cooldown)
        ]);
    }

    private function attachNewBuilding(Country $country, Building $building, array $data)
    {
        $country->buildings()->attach($building->id, [
            'count' => $data['count'] ?? 1,
            'income_at' => now()->addMinutes($building->cooldown)
        ]);
    }

    private function validateIncome($incomeTime)
    {
        if (now()->lte($incomeTime)) {
            throw ValidationException::withMessages([
                'message' => "until the next collect " . $this->getTimeUntil($incomeTime),
            ]);
        }
    }

    private function getTimeUntil($targetDate)
    {
        return Carbon::now()->diffForHumans(Carbon::parse($targetDate), ['parts' => 3, 'join' => ', ', 'syntax' => Carbon::DIFF_ABSOLUTE]);
    }

    private function updateResourcesIncome(Country $country, $building, $buildingCount)
    {
        $updatedResources = collect($country->resources)->mapWithKeys(function ($value, $resource) use ($building, $buildingCount) {
            $resourceIncome = array_key_exists($resource, $building->resources_income) ? $building->resources_income[$resource] * $buildingCount : 0;
            return [$resource => $value + $resourceIncome];
        });

        $country->update(['resources' => $updatedResources]);
    }
}

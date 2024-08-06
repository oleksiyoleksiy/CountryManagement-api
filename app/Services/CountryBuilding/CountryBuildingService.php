<?php

namespace App\Services\CountryBuilding;

use App\DTO\CountryBuildingDTO;
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

    public function store(Country $country, CountryBuildingDTO $dto)
    {
        $buildingId = $dto->building_id;

        $building = Building::findOrFail($buildingId);

        $this->validateCountryBuildingStore($country, $building);

        $this->withdrawResources($country, $building);

        $country->addBuilding($building);
    }

    public function collectIncome(Country $country, CountryBuildingDTO $dto)
    {
        $buildingId = $dto->building_id;

        $building = $country->buildings()->where('building_id', $buildingId)->firstOrFail();

        $this->validateIncome($building->pivot->income_at);

        $this->updateResourcesIncome($country, $building, $building->pivot->count);

        $country->buildings()->updateExistingPivot($buildingId, [
            'income_at' => now()->addMinutes($building->cooldown)
        ]);
    }

    private function withdrawResources(Country $country, Building $building)
    {
        $country->withdrawResources($building);

        if (array_key_exists(ResourceEnum::ENERGY->value, $building->resources_income)) {
            $country->addResource(ResourceEnum::ENERGY, $building->resources_income[ResourceEnum::ENERGY->value]);
        }
    }

    private function validateIncome($incomeTime)
    {
        if (now()->lte($incomeTime)) {
            throw ValidationException::withMessages([
                'message' => "until the next collect " . $this->getTimeUntil($incomeTime),
            ]);
        }
    }

    private function validateCountryBuildingStore(Country $country, Building $building)
    {
        $buildingHasResourcesIncome = collect($building->resources_income)->keys()->intersect(ResourceEnum::fossils());

        if ($buildingHasResourcesIncome->isNotEmpty() && !$country->hasAnyRequiredResources($building->resources_income)) {
            throw ValidationException::withMessages([
                'message' => "Your country doesn't have available any of the resources that building provides",
            ]);
        }
    }


    private function getTimeUntil($targetDate)
    {
        return Carbon::now()->diffForHumans(Carbon::parse($targetDate), ['parts' => 3, 'join' => ', ', 'syntax' => Carbon::DIFF_ABSOLUTE]);
    }

    private function updateResourcesIncome(Country $country, $building, $buildingCount)
    {

        $updatedResources = collect($country->resources)->mapWithKeys(function ($value, $resource) use ($country, $building, $buildingCount) {
            $isFossils = in_array($resource, ResourceEnum::fossils());
            $isAvailable = in_array($resource, $country->available_resources);
            $isExists = array_key_exists($resource, $building->resources_income);

            if ((($isFossils && $isAvailable) || !$isFossils) && $isExists) {
                $resourceIncome = $building->resources_income[$resource] * $buildingCount;
                return [$resource => $value + $resourceIncome];
            }
            return [$resource => $value];
        });

        $country->update(['resources' => $updatedResources]);
    }
}

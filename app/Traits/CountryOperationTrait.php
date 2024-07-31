<?php

namespace App\Traits;

use App\Constants;
use App\Enums\ProductTypeEnum;
use App\Enums\ResourceEnum;
use App\Models\Building;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

trait CountryOperationTrait
{
    public function addResource(ResourceEnum $resource, int $value)
    {
        $updatedValue = $this->resources[$resource->value] + $value;
        $this->updateResource($resource->value, $updatedValue);
    }

    public function subtractResource(ResourceEnum $resource, int $value)
    {
        $updatedValue = $this->resources[$resource->value] - $value;
        $this->updateResource($resource->value, $updatedValue);
    }

    private function updateResource(string $resource, int $value)
    {
        $updatedResources = $this->resources;
        $updatedResources[$resource] = $value;
        $this->update([
            'resources' => $updatedResources
        ]);
    }

    public function withdrawResources(Model $model)
    {
        $updatedResources = collect($this->resources)->mapWithKeys(function ($value, $resource) use ($model) {
            return [$resource => $value - ($model->resources_price[$resource] ?? 0)];
        });

        $this->validateWithdraw($updatedResources);

        $this->update([
            'resources' => $updatedResources,
        ]);
    }

    private function validateWithdraw(Collection $updatedResources)
    {
        $insufficientResources = [];

        foreach ($updatedResources as $resource => $value) {
            if ($value < 0) {
                $insufficientResources[] = $resource;
            }
        }

        if (!empty($insufficientResources)) {
            $resourcesStr = implode(', ', $insufficientResources);

            throw ValidationException::withMessages([
                'message' => "Insufficient amount of {$resourcesStr}.",
            ]);
        }
    }
    public function hasAnyRequiredResources($resources): bool
    {
        $requiredFossils = collect($resources)->keys()->intersect(ResourceEnum::fossils());

        return $requiredFossils->isNotEmpty() && $requiredFossils->some(function ($fossil) {
            return collect($this->available_resources)->contains($fossil);
        });
    }

    public function formattedResources()
    {
        return collect($this->resources)->mapWithKeys(function ($value, $resource) {
            return [$resource => $this->shortenNumber($value ?? 0)];
        })->all();
    }


    private function shortenNumber(int $number)
    {
        if ($number >= 1000000000) {
            return $this->formatNumber($number, 1000000000, 'B');
        }
        if ($number >= 1000000) {
            return $this->formatNumber($number, 1000000, 'M');
        }
        if ($number >= 1000) {
            return $this->formatNumber($number, 1000, 'K');
        }

        return (string)$number;
    }

    private function formatNumber($number, $divisor, $suffix)
    {
        $shortNumber = $number / $divisor;
        $formattedNumber = floor($shortNumber * 100) / 100; // Обрізати до однієї десяткової частки без округлення
        return "$formattedNumber$suffix";
    }

    private function transfer(Country $country, Product $product)
    {
        $this->validateResource(ResourceEnum::MONEY, $product->price);

        $this->subtractResource(ResourceEnum::MONEY, $product->price);

        $country->addResource(ResourceEnum::MONEY, $product->price);

        if ($product->isResource()) {
            $this->addResource(ResourceEnum::from($product->resource), $this->count);
            $country->subtractResource(ResourceEnum::from($product->resource), $this->count);
        }

        if ($product->isBuilding()) {
            $building = Building::find($product->model_id);

            $this->addBuilding($building, $product->count);
            $country->subtractBuilding($building, $product->count);
        }
    }

    public function addBuilding(Building $building, int $count = 1)
    {
        $buildingRelation = $this->buildings()->where('building_id', $building->id)->first();

        if ($buildingRelation) {
            $updatedCount = $buildingRelation->pivot->count + $count;
            $this->buildings()->updateExistingPivot($building->id, [
                'count' => $updatedCount,
                'income_at' => now()->addMinutes($building->cooldown)
            ]);
        } else {
            $this->attachNewBuilding($building, $count);
        }
    }

    public function subtractBuilding(Building $building, int $count = 1)
    {
        $buildingRelation = $this->buildings()->where('building_id', $building->id)->first();
        $updatedCount = $buildingRelation->pivot->count - $count;

        if ($updatedCount > 0) {
            $this->buildings()->updateExistingPivot($building->id, [
                'count' => $updatedCount,
                'income_at' => now()->addMinutes($building->cooldown)
            ]);
        } else {
            $this->buildings()->detach($building->id);
        }
    }

    private function attachNewBuilding(Building $building, int $count = 1)
    {
        $this->buildings()->attach($building->id, [
            'count' => $count,
            'income_at' => now()->addMinutes($building->cooldown)
        ]);
    }

    public function withdrawMarketplaceFee(int $price)
    {
        $fee = floor(($price * Constants::MARKETPLACE_FEE) / 100);

        $this->validateResource(ResourceEnum::MONEY, $fee);

        $this->subtractResource(ResourceEnum::MONEY, $fee);
    }

    private function validateResource(ResourceEnum $resource, int $value)
    {
        if ($this->resources[$resource->value] < $value) {
            throw ValidationException::withMessages([
                'message' => "Insufficient amount of $resource->value.",
            ]);
        }
    }

    public function transferFrom(Product $product)
    {
        if ($product->isResource()) {
            $resource = ResourceEnum::from($product->resource);
            $this->subtractResource($resource, $product->count);
        }

        if ($product->isBuilding()) {
            $building = Building::find($product->model_id);
            $this->subtractBuilding($building, $product->count);
        }
    }

    public function transferTo(Product $product)
    {
        if ($product->isResource()) {
            $resource = ResourceEnum::from($product->resource);
            $this->addResource($resource, $product->count);
        }

        if ($product->isBuilding()) {
            $building = Building::find($product->model_id);
            $this->addBuilding($building, $product->count);
        }
    }
}

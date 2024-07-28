<?php

namespace App\Traits;

use App\Enums\ResourceEnum;
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
}

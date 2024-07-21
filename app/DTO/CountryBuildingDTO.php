<?php

namespace App\DTO;

class CountryBuildingDTO
{
    public function __construct(public int $buildingId)
    {
    }

    public function toArray(): array
    {
        return [
            'building_id' => $this->buildingId
        ];
    }
}

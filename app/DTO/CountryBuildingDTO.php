<?php

namespace App\DTO;

class CountryBuildingDTO
{
    public function __construct(public int $building_id)
    {
    }

    public function toArray(): array
    {
        return [
            'building_id' => $this->building_id
        ];
    }
}

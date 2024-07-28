<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;

class BuildingDTO
{
    public function __construct(
        public string $name,
        public string|null $description,
        public UploadedFile $image,
        public array $resources_income,
        public array $resources_price,
        public float|null $cooldown,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'resources_income' => $this->resources_income,
            'resources_price' => $this->resources_price,
            'cooldown' => $this->cooldown,
        ];
    }
}

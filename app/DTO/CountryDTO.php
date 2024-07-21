<?php

namespace App\DTO;

class CountryDTO
{
    public function __construct(
        public string $name
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name
        ];
    }
}

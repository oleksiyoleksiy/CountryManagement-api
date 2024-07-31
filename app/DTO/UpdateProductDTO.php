<?php

namespace App\DTO;

class UpdateProductDTO
{
    public function __construct(
        public int $count,
        public int $price,
    ) {
    }

    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'price' => $this->price,
        ];
    }
}

<?php

namespace App\DTO;

class PurchaseProductDTO
{
    public function __construct(
        public int $count,
    ) {
    }

    public function toArray(): array
    {
        return [
            'count' => $this->count,
        ];
    }
}

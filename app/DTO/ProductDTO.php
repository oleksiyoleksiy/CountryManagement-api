<?php

namespace App\DTO;

class ProductDTO
{
    public function __construct(
        public int $type,
        public int $count,
        public int $price,
        public int|null $model_id = null,
        public string|null $fossil = null
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'count' => $this->count,
            'price' => $this->price,
            'model_id' => $this->model_id,
            'fossil' => $this->fossil
        ];
    }
}

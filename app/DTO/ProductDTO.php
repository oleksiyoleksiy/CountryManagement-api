<?php

namespace App\DTO;

class ProductDTO
{
    public function __construct(
        public int $type,
        public int $count,
        public int $price,
        public int $model_id,
        public string $resource
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'count' => $this->count,
            'price' => $this->price,
            'model_id' => $this->model_id,
            'resource' => $this->resource
        ];
    }
}

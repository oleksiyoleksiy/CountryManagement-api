<?php

namespace App\Http\Resources;

use App\Enums\ProductTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->isFossil() ? $this->fossil : null,
            'image' => $this->isFossil() ? "/$this->fossil.webp" : null,
            'count' => $this->count,
            'price' => $this->price,
            'seller' => $this->country->name
        ];
    }
}

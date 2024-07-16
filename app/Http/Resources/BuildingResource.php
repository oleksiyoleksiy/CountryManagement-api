<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildingResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'resources_income' => $this->resources_income,
            'resources_price' => $this->resources_price,
            'cooldown' => $this->cooldown,
            'count' => $this->pivot?->count,
            'income_at' => $this->pivot ? now()->diffInSeconds($this->pivot->income_at) : null
        ];
    }
}

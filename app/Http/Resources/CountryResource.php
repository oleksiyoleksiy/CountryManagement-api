<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'country' => [
                'id' => $this->id,
                'name' => $this->name,
                'available_resources' => $this->available_resources,
                'resources' => $this->resources,
            ],
            'buildings' => BuildingResource::collection($this->buildings)
        ];
    }
}

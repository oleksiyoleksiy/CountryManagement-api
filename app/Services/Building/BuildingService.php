<?php

namespace App\Services\Building;

use App\DTO\BuildingDTO;
use App\Models\Building;
use App\Services\Image\ImageService;
use Illuminate\Support\Facades\Cache;

class BuildingService
{
    public function __construct(private ImageService $imageService)
    {
    }

    public function index()
    {
        // if (!Cache::has('buildings')) {
        //     Cache::put('buildings', Building::all(), now()->addHour());
        // }

        // $buildings = Cache::get('buildings');

        return Building::all();
    }

    public function store(BuildingDTO $dto)
    {
        $image = $this->imageService->storeImage($dto->image);

        $data = $dto->toArray();

        $data['image'] = $image;

        return Building::create($data);
    }
}

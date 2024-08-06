<?php

namespace App\Http\Controllers\CountryBuilding;

use App\DTO\CountryBuildingDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CountryBuilding\CountryBuildingRequest;
use App\Http\Resources\BuildingResource;
use App\Http\Resources\CountryBuildingResource;
use App\Http\Resources\CountryResource;
use App\Models\Building;
use App\Models\Country;
use App\Services\CountryBuilding\CountryBuildingService;
use Illuminate\Http\Request;

class CountryBuildingController extends Controller
{
    public function __construct(private CountryBuildingService $service)
    {
    }

    public function index(Country $country)
    {
        return CountryBuildingResource::collection($this->service->index($country));
    }

    public function store(Country $country, CountryBuildingRequest $request)
    {
        $data = $request->validated();

        $dto = new CountryBuildingDTO(...$data);

        $this->service->store($country, $dto);

        return CountryBuildingResource::make($country);
    }

    public function collectIncome(Country $country, CountryBuildingRequest $request)
    {

        $data = $request->validated();

        $dto = new CountryBuildingDTO(...$data);

        $this->service->collectIncome($country, $dto);

        return CountryBuildingResource::make($country);
    }
}

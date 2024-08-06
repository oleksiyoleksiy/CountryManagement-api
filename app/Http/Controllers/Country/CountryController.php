<?php

namespace App\Http\Controllers\Country;

use App\DTO\CountryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Country\CountryRequest;
use App\Http\Requests\Country\StoreRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\BuildingResource;
use App\Http\Resources\CountryBuildingResource;
use App\Http\Resources\CountryCollection;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Services\Country\CountryService;

class CountryController extends Controller
{
    public function __construct(private CountryService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CountryBuildingResource::collection($this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $dto = new CountryDTO(...$data);

        $country = $this->service->store($dto);

        return CountryBuildingResource::collection($country);
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        return CountryResource::make($country);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();

        return response()->noContent();
    }
}

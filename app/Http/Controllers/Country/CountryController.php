<?php

namespace App\Http\Controllers\Country;

use App\Http\Controllers\Controller;
use App\Http\Requests\Country\CountryRequest;
use App\Http\Requests\Country\StoreRequest;
use App\Http\Requests\UpdateCountryRequest;
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
        return CountryResource::collection($this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return CountryResource::make($this->service->store($request));
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
        //
    }
}

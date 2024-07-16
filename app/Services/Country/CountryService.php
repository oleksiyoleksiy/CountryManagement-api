<?php

namespace App\Services\Country;

use App\Constants;
use App\Http\Requests\Country\CountryRequest;
use App\Http\Requests\Country\StoreRequest;
use App\Models\Country;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class CountryService
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        // $data['image'] = $request->file('image')->store('image');

        $randomResources = collect(Constants::AVAILABLE_RESOURCES)->random(2);

        $data['available_resources'] = $randomResources->all();

        $data['user_id'] = auth()->id();

        $data['resources'] = collect(Constants::AVAILABLE_RESOURCES)->mapWithKeys(function ($resource) {
            return [$resource => 0];
        })->all();

        $data['resources'] = [
            'money' => 1000,
            'energy' => 100
        ];

        return Country::create($data);
    }



    public function index()
    {
        return auth()->user()->countries;
    }
}

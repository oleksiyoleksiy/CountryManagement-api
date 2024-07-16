<?php

namespace App\Http\Controllers\Building;

use App\Http\Controllers\Controller;
use App\Http\Requests\BuildingRequest;
use App\Http\Requests\StoreBuildingsRequest;
use App\Http\Requests\UpdateBuildingsRequest;
use App\Http\Resources\BuildingResource;
use App\Models\Building;
use App\Services\Building\BuildingService;

class BuildingController extends Controller
{
    public function __construct(private BuildingService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BuildingResource::collection($this->service->index());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BuildingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Building $buildings)
    {
        //
    }


    public function update(BuildingRequest $request, Building $building)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Building $building)
    {
        //
    }
}

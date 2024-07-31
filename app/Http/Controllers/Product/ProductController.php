<?php

namespace App\Http\Controllers\Product;

use App\DTO\ProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Country;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Country $country)
    {
        return ProductResource::collection($this->service->index($country));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Country $country, ProductRequest $request)
    {
        $data = $request->validated();

        $dto = new ProductDTO(...$data);

        return ProductResource::make($this->service->store($country, $dto));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

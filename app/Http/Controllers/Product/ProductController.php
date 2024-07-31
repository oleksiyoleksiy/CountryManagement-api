<?php

namespace App\Http\Controllers\Product;

use App\DTO\ProductDTO;
use App\DTO\UpdateProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Country;
use App\Models\Product;
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
    public function store(Country $country, StoreRequest $request)
    {
        $data = $request->validated();

        $dto = new ProductDTO(...$data);

        return ProductResource::make($this->service->store($country, $dto));
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country, Product $product)
    {
        return ProductResource::make($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Country $country, UpdateRequest $request, Product $product)
    {
        $data = $request->validated();

        $dto = new UpdateProductDTO(...$data);

        return ProductResource::make($this->service->update($country, $product, $dto));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country, Product $product)
    {
        $this->service->delete($country, $product);

        return response()->noContent();
    }
}

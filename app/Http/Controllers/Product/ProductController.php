<?php

namespace App\Http\Controllers\Product;

use App\DTO\ProductDTO;
use App\DTO\PurchaseProductDTO;
use App\DTO\UpdateProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Requests\Product\PurchaseRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\ProductResource;
use App\Models\Country;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function __construct(private ProductService $service) {}

  /**
   * Display a listing of the resource.
   */
  public function index(Country $country)
  {
    return ProductResource::collection($this->service->index($country));
  }

  public function countryIndex(Country $country)
  {
    return ProductResource::collection($this->service->countryIndex($country));
  }
  /**
   * Store a newly created resource in storage.
   */
  public function store(Country $country, StoreRequest $request)
  {
    $data = $request->validated();

    $dto = new ProductDTO(...$data);

    return [
      'product' => ProductResource::make($this->service->store($country, $dto)),
      'country' => CountryResource::make($country),
    ];
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

    return [
      'product' => ProductResource::make($this->service->update($country, $product, $dto)),
      'country' => CountryResource::make($country)
    ];
  }

  public function purchase(PurchaseRequest $request, Country $country, Product $product)
  {
    $data = $request->validated();

    $dto = new PurchaseProductDTO(...$data);

    $this->service->purchase($dto, $country, $product);

    return [
      'product' => ProductResource::make($product),
      'country' => CountryResource::make($country)
    ];
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Country $country, Product $product)
  {
    $this->service->delete($country, $product);

    return CountryResource::make($country);
  }
}

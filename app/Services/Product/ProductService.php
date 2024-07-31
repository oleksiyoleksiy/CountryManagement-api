<?php

namespace App\Services\Product;

use App\DTO\ProductDTO;
use App\Enums\ProductTypeEnum;
use App\Enums\ResourceEnum;
use App\Http\Resources\ProductResource;
use App\Models\Building;
use App\Models\Country;
use App\Models\Product;
use App\Services\Image\ImageService;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function index(Country $country)
    {
        $products = Product::where('country_id', $country->id)->get();

        return ProductResource::collection($products);
    }

    public function store(Country $country, ProductDTO $dto)
    {
        $data = $dto->toArray();

        $data['country_id'] = $country->id;

        $country->withdrawMarketplaceFee($dto->price);

        $product = Product::create($data);

        $this->withdrawFromCountry($product, $country);

        return $product;
    }

    private function withdrawFromCountry(Product $product, Country $country)
    {
        if ($product->isResource()) {
            $resource = ResourceEnum::from($product->resource);
            $country->subtractResource($resource, $product->count);
        }

        if ($product->isBuilding()) {
            $building = Building::find($product->model_id);
            $country->subtractBuilding($building, $product->count);
        }
    }
}

<?php

namespace App\Services\Product;

use App\DTO\ProductDTO;
use App\DTO\UpdateProductDTO;
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

        $country->transferFrom($product);

        return $product;
    }

    public function update(Country $country, Product $product, UpdateProductDTO $dto)
    {
        $data = $dto->toArray();

        if ($dto->count !== $product->count) {
            $country->transferTo($product);
            $country->transferFrom($product);
        }

        $product->update($data);

        return $product;
    }

    public function delete(Country $country, Product $product)
    {
        $country->transferTo($product);

        $product->delete();
    }
}

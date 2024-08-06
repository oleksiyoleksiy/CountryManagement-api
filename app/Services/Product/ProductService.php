<?php

namespace App\Services\Product;

use App\DTO\ProductDTO;
use App\DTO\PurchaseProductDTO;
use App\DTO\UpdateProductDTO;
use App\Enums\ProductTypeEnum;
use App\Enums\ResourceEnum;
use App\Http\Requests\Product\PurchaseRequest;
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
        return Product::where('country_id', '!=', $country->id)->get();
    }

    public function countryIndex(Country $country)
    {
        return $country->products;
    }

    public function store(Country $country, ProductDTO $dto)
    {
        $data = $dto->toArray();

        $data['country_id'] = $country->id;

        $country->withdrawMarketplaceFee($dto->price);

        $product = Product::create($data);

        $country->transferFrom($product, $product->count);

        return $product;
    }

    public function update(Country $country, Product $product, UpdateProductDTO $dto)
    {
        $data = $dto->toArray();

        if ($dto->count !== $product->count) {
            $country->transferTo($product, $product->count);
            $country->transferFrom($product, $dto->count);
        }

        $product->update($data);

        return $product;
    }

    public function delete(Country $country, Product $product)
    {
        $country->transferTo($product, $product->count);

        $product->delete();
    }

    public function purchase(PurchaseProductDTO $dto, Country $country, Product $product)
    {
        $country->subtractResource(ResourceEnum::MONEY, $dto->count * $product->price);
        $product->country->addResource(ResourceEnum::MONEY, $dto->count * $product->price);
        $country->transferTo($product, $dto->count);

        $difference = $product->count - $dto->count;

        $difference === 0
            ? $product->delete()
            : $product->update(['count' => $difference]);
    }
}

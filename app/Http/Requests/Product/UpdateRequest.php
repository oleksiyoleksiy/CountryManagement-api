<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductTypeEnum;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'count' => ['required', 'min:1', 'integer'],
            'price' => ['required', 'min:1000', 'integer']
        ];
    }


    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $countryId = $this->route('country');
            $productId = $this->route('product');
            $country = Country::find($countryId)->first();
            $product = Product::find($productId)->first();
            $resources = $country->resources;

            if (ProductTypeEnum::from($product->type)->isFossil()) {
                if ($resources[$product->fossil] < $this->count) {
                    $validator->errors()->add('count', 'The quantity indicated exceeds the available stock.');
                }
            }

            // if (ProductTypeEnum::from($product->type)->isBuilding()) {
            //     $building = $country->buildings()->where('building_id', $product->model_id)->first();
            //     $isBuildingMissingOrInsufficient = !$building || $building->pivot->count < $this->count;

            //     if ($isBuildingMissingOrInsufficient) {
            //         $validator->errors()->add('count', 'The quantity indicated exceeds the available stock.');
            //     }
            // }
        });
    }
}

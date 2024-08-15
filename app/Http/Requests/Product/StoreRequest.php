<?php

namespace App\Http\Requests\Product;

use App\Constants;
use App\Enums\ProductTypeEnum;
use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreRequest extends FormRequest
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
            'type' => ['required', 'integer', Rule::enum(ProductTypeEnum::class)],
            'count' => ['required', 'min:1', 'integer'],
            'fossil' => ['required_if:type,' . ProductTypeEnum::FOSSIL->value],
            'model_id' => ['required_unless:type,' . ProductTypeEnum::FOSSIL->value],
            'price' => ['required', 'min:1000', 'integer']
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $country = $this->route('country');
            $resources = $country->resources;

            if (ProductTypeEnum::from($this->type)->isFossil()) {
                if ($resources[$this->fossil] < $this->count) {
                    $validator->errors()->add('count', 'The quantity indicated exceeds the available stock.');
                }
            }

            if ($resources['money'] < Constants::MARKETPLACE_FEE) {
                    $validator->errors()->add('fee', 'Not enough money to pay the marketplace fee.');
            }

            // if (ProductTypeEnum::from($this->type)->isBuilding()) {
            //     $building = $country->buildings()->where('building_id', $this->model_id)->first();
            //     $isBuildingMissingOrInsufficient = !$building || $building->pivot->count < $this->count;

            //     if ($isBuildingMissingOrInsufficient) {
            //         $validator->errors()->add('count', 'The quantity indicated exceeds the available stock.');
            //     }
            // }
        });
    }
}

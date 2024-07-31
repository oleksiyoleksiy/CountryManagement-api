<?php

namespace App\Http\Requests\Product;

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
            'resource' => ['required_if:type,' . ProductTypeEnum::RESOURCE->value],
            'model_id' => ['required_unless:type,' . ProductTypeEnum::RESOURCE->value],
            'price' => ['required', 'min:1000', 'integer']
        ];
    }

    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $countryId = $this->route('country');
            $country = Country::find($countryId)->first();
            $resources = $country->resources;

            if (ProductTypeEnum::from($this->type)->isResource()) {
                if ($resources[$this->resource] < $this->count) {
                    $validator->errors()->add('count', 'The quantity indicated exceeds the available stock.');
                }
            }

            if (ProductTypeEnum::from($this->type)->isBuilding()) {
                $building = $country->buildings()->where('building_id', $this->model_id)->first();
                $isBuildingMissingOrInsufficient = !$building || $building->pivot->count < $this->count;

                if ($isBuildingMissingOrInsufficient) {
                    $validator->errors()->add('count', 'The quantity indicated exceeds the available stock.');
                }
            }
        });
    }
}

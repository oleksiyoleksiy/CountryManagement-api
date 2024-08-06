<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductTypeEnum;
use App\Enums\ResourceEnum;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PurchaseRequest extends FormRequest
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
            'count' => ['required', 'integer', 'min:1']
        ];
    }


    protected function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $country = $this->route('country');
            $product = $this->route('product');

            $country->validateResource(ResourceEnum::MONEY, $this->count * $product->price);

            if ($product->count < $this->count) {
                $validator->errors()->add('count', 'The quantity indicated exceeds the available stock.');
            }
        });
    }
}

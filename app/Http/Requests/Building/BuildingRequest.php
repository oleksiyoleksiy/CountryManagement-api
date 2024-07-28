<?php

namespace App\Http\Requests\Building;

use Illuminate\Foundation\Http\FormRequest;

class BuildingRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cooldown' => ['nullable', 'integer'],
            'resources_income' => ['array'],
            'resources_income.*' => ['required', 'integer'],
            'resources_price' => ['array'],
            'resources_price.*' => ['integer']
        ];
    }
}

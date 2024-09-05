<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:255',  // Ensure 'unit' is required and not null
            'measurement' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'delivery_charges' => 'required|numeric|min:0',
        ];
    }
}

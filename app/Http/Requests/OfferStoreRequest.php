<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferStoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'nullable|integer',
            'valid_date' => 'nullable|date',
            'datetime' => 'nullable|datetime',
            'max_users' => 'nullable|integer',
            'shop_id' => 'required|exists:shops,id',
            'avatar' => 'nullable|image',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressesStoreRequest extends FormRequest
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
            'id' => 'nullable|integer',
            'name' => 'nullable|string',
            'mobile' => 'nullable|string',
            'alternate_mobile' => 'nullable|string',
            'door_no' => 'nullable|string',
            'street_name' => 'nullable|string',
            'city' => 'nullable|string',
            'pincode' => 'nullable|string',
            'state' => 'nullable|string',
        ];
    }
}

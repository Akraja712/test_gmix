<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IntrestsStoreRequest extends FormRequest
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
            'intrest_user_id' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'datetime' => 'nullable|datetime',
        ];
    }
}

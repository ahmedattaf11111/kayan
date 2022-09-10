<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        //Contains all inputs with validations
        return [
            "first_name" => 'required',
            "last_name" => 'required',
            //Optional fields
            "phone" => 'nullable|regex:/^01[0125][0-9]{8}$/',
            "age" => "nullable|integer",
            "image" => "nullable|image",
            "address" => "nullable",
            "city" => "nullable",
            "age" => "nullable",
            "education" => "nullable",
            "job" => "nullable",
            "about_me" => "nullable",
        ];
    }
}

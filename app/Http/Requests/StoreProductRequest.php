<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            "name" => "required|unique:products",
            "category_id" => "required",
            "effective_material" => "required",
            "pharmacist_form_id" => "required",
            "public_price" => "required|numeric|min:1",
            "image" => "required|image",
            "company_id"=>"required"
        ];
    }
}

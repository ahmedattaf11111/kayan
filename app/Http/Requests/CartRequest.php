<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
        $rules = [
            "product_id" => "required|numeric",
        ];
        $this->supplier_id ?
            $rules["supplier_id"] = "required_without:company_id|numeric"
            : $rules["company_id"] = "required_without:company_id|numeric";
        return $rules;
    }
}

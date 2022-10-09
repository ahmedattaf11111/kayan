<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderFormRequest extends FormRequest
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
            "same_address_shipping" => "required|boolean",
        ];
        if (!$this->same_address_shipping) {
            $rules["shipping_address"] = "required";
            $rules["shipping_city_id"] = "required";
            $rules["shipping_area_id"] = "required";
        }
        return $rules;
    }
}

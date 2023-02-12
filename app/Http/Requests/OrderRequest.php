<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'payment_method'=>'required',
            'name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'address'=>'required',
            'zip'=>'required',
            'country_id'=>'required',
            'city_id'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'country_id.required'=>'The country name field is required.',
            'city_id.required'=>'The city name field is required.'
        ];
    }
}

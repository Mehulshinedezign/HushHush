<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingRequest extends FormRequest
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
        $emailRegex = "/^[a-zA-Z]+[a-zA-Z0-9_\.\-]*@[a-zA-Z]+(\.[a-zA-Z]+)*[\.]{1}[a-zA-Z]{2,10}$/";
        return [
            'name' => 'required',
            'email' => 'required|email|regex:'.$emailRegex,
            'phone_number' => 'required|regex:/^\([0-9]{3}\)-[0-9]{3}-[0-9]{4}$/',
            'address1' => 'required',
            'postcode' => 'required',
            'country' => 'required|exists:countries,id',
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
            'product_id' => 'required|exists:products,id',
            'product_location' => 'required|exists:product_locations,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Enter the name',
            'email.required' => 'Enter the email',
            'email.email' => 'Invalid email address',
            'email.regex' => 'Invalid email address',
            'phone_number.required' => 'Enter the phone number',
            'phone_number.regex' => 'Invalid number',
            'address1.required' => 'Enter the address1',
            'postcode.required' => 'Enter the zip code',
            'country.required' => 'Select the country',
            'country.exists' => 'Selected country not exist',
            'state.required' => 'Select the state',
            'state.exists' => 'Selected state not exist',
            'city.required' => 'Select the city',
            'city.exists' => 'Selected city not exist',
            'product_id.required' => 'Something went wrong with the product. Try again!',
            'product_id.exists' => 'Selected product does not exist anymore',
            'product_location.required' => 'Something went wrong with the selected location. Try again!',
            'product_location.exists' => 'Selected product not exist near to your location anymore. Try again!',
        ];
    }
}

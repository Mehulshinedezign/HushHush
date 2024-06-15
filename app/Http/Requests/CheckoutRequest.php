<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'option' => 'required|in:security,insurance',
            'product' => 'required|exists:products,id',
            'location_id' => 'required|exists:product_locations,id',
            'customer_location' => 'required',
            'reservation_date' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
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
            'option.required' => __('product.validations.optionRequired'),
            'option.in' => __('product.validations.optionArray'),
            'product.required' => __('checkout.validations.productRequired'),
            'product.exists' => __('checkout.validations.productExist'),
            'location_id.required' => __('checkout.validations.productLocationRequired'),
            'location_id.exists' => __('checkout.validations.productLocationExist'),
            'customer_location.required' => __('checkout.validations.customerLocationRequired'),
            'reservation_date.required' => __('checkout.validations.reservationRequired'),
            'latitude.required' => __('checkout.validations.latitudeRequired'),
            'longitude.required' => __('checkout.validations.longitudeRequired'),
        ];
    }
}

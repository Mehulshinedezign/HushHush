<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'location' => 'required',
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
            'location.required' => __('product.validations.locationRequired'),
            'reservation_date.required' => __('product.validations.reservationDateRequired'),
            'latitude.required' => __('product.validations.latitudeRequired'),
            'longitude.required' => __('product.validations.longitudeRequired'),
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_name' => 'required',
            'category' => 'required',
            'subcategory' => 'nullable',
            'size' => 'nullable',
            'brand' => 'nullable',
            'color' => 'nullable',
            'product_complete_location' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'non_available_dates' => 'required',
            'product_condition' => 'required',
            'description' => 'required',
            'product_market_value' => 'required',
            'product_link' => 'nullable',
            'min_rent_days' => 'required',
            'rent_price_day' => 'required',
            'rent_price_week' => 'required',
            'rent_price_month' => 'required',
            'images.*' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'This field is required.',
            'category.required' => 'This field is required.',
            'product_complete_location.required' => 'This field is required.',
            'address1.required' => 'This field is required.',
            'address2.required' => 'This field is required.',
            'country.required' => 'This field is required.',
            'state.required' => 'This field is required.',
            'city.required' => 'This field is required.',
            'non_available_dates.required' => 'This field is required.',
            'product_condition.required' => 'This field is required.',
            'description.required' => 'This field is required.',
            'product_market_value.required' => 'This field is required.',
            'min_rent_days.required' => 'This field is required.',
            'rent_price_day.required' => 'This field is required.',
            'rent_price_week.required' => 'This field is required.',
            'rent_price_month.required' => 'This field is required.',
            'images.*.required' => 'Each image is required.',
        ];
    }
}

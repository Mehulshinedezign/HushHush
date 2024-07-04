<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'product_name' => 'required',
            'description' => 'required|string',
            'category' => 'required',
             'product_condition' => 'required|string',
            'rent_price' => 'nullable',
            'rent_price_day' => 'required',
            'rent_price_week' => 'required',
            'rent_price_month' => 'required',
            'min_rent_days' => 'required',
            'product_market_value' => 'required',
            'product_link' => 'nullable',
            'size' => 'nullable',
            'brand' => 'nullable',
            'color' => 'nullable',
            'price' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'non_available_dates' => 'nullable',
            'pick_up_location' => 'required|',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_name.required' => 'The product name is required.',
            'description.required' => 'The product description is required.',
            'category.required' => 'Please select a category.',
            'product_condition.required' => 'Please specify the product condition.',
            'rent_price_day.required' => 'The daily rent price is required.',
            'rent_price_week.required' => 'The weekly rent price is required.',
            'rent_price_month.required' => 'The monthly rent price is required.',
            'min_rent_days.required' => 'Please specify the minimum rental period.',
            'product_market_value.required' => 'The product market value is required.',
            'city.required' => 'The city is required.',
            'state.required' => 'The state is required.',
            'pick_up_location.required' => 'The pick-up location is required.',
            'images.*.image' => 'The uploaded file must be an image.',
            'images.*.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'images.*.max' => 'The image may not be greater than 2MB.',
        ];
    }
}

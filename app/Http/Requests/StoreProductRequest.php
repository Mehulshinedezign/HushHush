<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image',
            'product_name' => 'required',
            'category' => 'required',
            'product_complete_location' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'country' => 'required',
            'state' => 'required',
            // 'city' => 'required',
            'product_link' => ['nullable'],
            'description' => 'required',
            'product_market_value' => 'required',
            'min_rent_days' => 'required|integer',
            'rent_price_day' => 'required|integer',
            'rent_price_week' => 'required|integer',
            'rent_price_month' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'images.required' => 'Please upload at least one image.',
            'images.min' => 'You must upload at least one image.',
            'images.max' => 'You can upload a maximum of 5 images.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Only jpeg, png, jpg, images are allowed.',
            'product_name.required' => 'This field is required.',
            'category.required' => 'This field is required.',
            'product_complete_location.required' => 'Please enter the complete address.',
            'address1.required' => 'This field is required.',
            'address2.required' => 'This field is required.',
            'country.required' => 'This field is required.',
            'state.required' => 'This field is required.',
            'city.required' => 'This field is required.',
            'product_link.regex' => 'Please enter a valid product link.',
            'product_condition.required' => 'This field is required.',
            'description.required' => 'This field is required.',
            'product_market_value.required' => 'This field is required.',
            'min_rent_days.required' => 'Please enter the number of days required to rent an item..',
            'rent_price_day.required' => 'This field is required.',
            'rent_price_week.required' => 'This field is required.',
            'rent_price_month.required' => 'This field is required.',
            'min_rent_days.integer' => 'Please enter a valid number.',
            'rent_price_day.integer' => 'Please enter a valid number.',
            'rent_price_week.integer' => 'Please enter a valid number.',

        ];

    }
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        // Add custom error data
        $errors->add('add_product_error', 'This is custom error info.');

        throw new HttpResponseException(
            redirect()->back()->withErrors($errors)->withInput()
        );
    }
}

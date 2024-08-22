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

        $rules = [
            'product_name' => 'required|string',
            'category' => 'required|string',
            'product_complete_location' => 'required|string',
            'address1' => 'required|string',
            'address2' => 'required|string',
            'country' => 'required|string',
            'state' => 'required|string',
            // 'city' => 'required|string',
            'product_link' => ['nullable','regex:/^((ftp|http|https):\/\/)?(www\.)?(?!.*(ftp|http|https|www\.))[a-zA-Z0-9_-]+(\.[a-zA-Z]+)+((\/)[\w#]+)*(\/\w+\?[a-zA-Z0-9_]+=\w+(&[a-zA-Z0-9_]+=\w+)*)?\/?$/'],
            'product_condition' => 'required|string',
            'description' => 'required|string',
            'product_market_value' => 'required|numeric',
            'min_rent_days' => 'required|integer|min:1',
            'rent_price_day' => 'required|numeric|min:0',
            'rent_price_week' => 'required|numeric|min:0',
            'rent_price_month' => 'required|numeric|min:0',
        ];


        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'new_images.required' => 'Please upload at least one image.',
            'new_images.min' => 'You must upload at least one image.',
            'new_images.max' => 'You can upload a maximum of 5 images.',
            'new_images.*.image' => 'Each file must be an image.',
            'new_images.*.mimes' => 'Only jpeg, png, jpg images are allowed.',
            'existing_images.array' => 'Existing images must be an array.',
            'existing_images.*.integer' => 'Each existing image ID must be a valid integer.',
            'existing_images.*.exists' => 'The provided existing image ID is invalid.',
            'product_name.required' => 'This field is required.',
            'category.required' => 'This field is required.',
            'product_complete_location.required' => 'This field is required.',
            'address1.required' => 'This field is required.',
            'address2.required' => 'This field is required.',
            'country.required' => 'This field is required.',
            'state.required' => 'This field is required.',
            'city.required' => 'This field is required.',
            'product_link.regex' => 'Please enter a valid product link.',
            'product_condition.required' => 'This field is required.',
            'description.required' => 'This field is required.',
            'product_market_value.required' => 'This field is required..',
            'min_rent_days.required' => 'This field is required.',
            'rent_price_day.required' => 'This field is required.',
            'rent_price_week.required' => 'This field is required.',
            'rent_price_month.required' => 'This field is required.',
            'min_rent_days.integer' => 'Minimum rental days must be a valid number.',
            'rent_price_day.numeric' => 'Daily rental price must be a valid number.',
            'rent_price_week.numeric' => 'Weekly rental price must be a valid number.',
            'rent_price_month.numeric' => 'Monthly rental price must be a valid number.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
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
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|max:1000',
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
            'rating.required' =>  'Please give rating',
            'rating.min' =>  'Rating can be between 1 to 5',
            'rating.max' =>  'Rating can be between 1 to 5',
            'review.required' =>  'Please write a review',
            'review.max' =>  'Review should not be more than 1000 characters',
        ];
    }
}

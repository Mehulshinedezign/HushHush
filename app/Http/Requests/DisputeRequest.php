<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisputeRequest extends FormRequest
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
        $counter = 0;
        $validate = [];

        $validate = [
            'subject' => 'required',
            'description' => 'required',
        ];
        if (!isset(request()->images)) {
            $validate['images'] = 'required';
            return $validate;
        }
        if ($counter < $this->minImageCount && count(request()->images) < $this->minImageCount) {
            $validate['images'] = 'required';
        }
        for ($i = 1; $i <= count(request()->images); $i++) {
            if (!is_null(request()->file('image' . $i))) {
                $counter++;
                $validate['images' . $i] =  'mimes:' . 'jpg,jpeg,png' . '|max:' . '4096';
            }
        }

        // if ($counter < request()->global_min_dispute_image_count) {
        //     $validate['error'] = 'required';
        // }

        return $validate;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [
            'subject.required' => 'Please select the reason of dispute',
            'description.required' => 'Please enter the description',
            'images.required' =>  'Please upload atleast 1 images',
        ];

        // for ($i = 1; $i <= request()->global_max_dispute_image_count; $i++) {
        //     if (!is_null(request()->file('dispute_image' . $i))) {
        //         $messages['dispute_image' . $i . '.mimes'] =  'Please upload only ' . request()->global_php_image_extension;
        //         $messages['dispute_image' . $i . '.max'] =  'File size should not be more than ' . (request()->global_php_file_size / 1000) . 'Mb';
        //     }
        // }

        return $messages;
    }
}

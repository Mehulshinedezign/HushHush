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
        $validate = [
            'subject' => 'required',
            'description' => 'required',
        ];
        for($i = 1; $i <= request()->global_max_dispute_image_count; $i++) {
            if (!is_null(request()->file('dispute_image'.$i))) {
                $counter++;
                $validate['dispute_image'.$i] =  'mimes:'.request()->global_php_image_extension.'|max:'.request()->global_php_file_size;
            }
        }
        
        if ($counter < request()->global_min_dispute_image_count) {
            $validate['error'] = 'required';
        }

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
            'error.required' =>  'Please upload atleast '.request()->global_min_dispute_image_count.' images',
        ];

        for($i = 1; $i <= request()->global_max_dispute_image_count; $i++) {
            if (!is_null(request()->file('dispute_image'.$i))) {
                $messages['dispute_image'.$i.'.mimes'] =  'Please upload only '.request()->global_php_image_extension;
                $messages['dispute_image'.$i.'.max'] =  'File size should not be more than '.(request()->global_php_file_size/1000).'Mb';
            }
        }

        return $messages;
    }
}

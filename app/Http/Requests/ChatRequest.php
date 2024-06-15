<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatRequest extends FormRequest
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
        $rules = [];
        if (is_null(request()->message) && is_null(request()->attachment)) {
            $validate['error'] = 'required';
        }

        if (!is_null(request()->message)) {
            $rules['message'] = 'string|max:1000';
        }

        if (!is_null(request()->attachment)) {
            $rules['attachment'] = 'mimes:'.request()->global_php_image_extension.'|max:'.request()->global_php_file_size;
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'error.required' => 'Please send either message or attachment',
            'message.string' => 'Invalid message text',
            'message.max' => 'Maximum 1000 characters are allowed',
            'attachment.mimes' => 'Please upload only '.request()->global_php_image_extension,
            'attachment.max' => 'File size should not be more than '.(request()->global_php_file_size/1000).'Mb',
        ];
    }
}

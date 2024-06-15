<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderPickUpReturnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    private $maxImageCount = 0;
    private $minImageCount = 0;

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
        $order = request()->route()->parameters();
        // dd($order);
        if ('Pending' == $order['order']->status) {
            $this->maxImageCount = request()->global_max_picked_up_image_count;
            $this->minImageCount = request()->global_min_picked_up_image_count;
        } else {
            $this->maxImageCount = request()->global_max_returned_image_count;
            $this->minImageCount = request()->global_min_returned_image_count;
        }

        $counter = 0;
        $validate = [];
        for ($i = 1; $i <= $this->maxImageCount; $i++) {
            if (!is_null(request()->file('image' . $i))) {
                $counter++;
                $validate['image' . $i] =  'mimes:' . request()->global_php_image_extension . '|max:' . request()->global_php_file_size;
            }
        }

        if ($counter < $this->minImageCount && request()->uploaded_image_count < $this->minImageCount) {
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
            'error.required' =>  'Please upload atleast ' . $this->minImageCount . ' images',
        ];

        for ($i = 1; $i <= $this->maxImageCount; $i++) {
            if (!is_null(request()->file('image' . $i))) {
                $messages['image' . $i . '.mimes'] =  'Please upload only ' . request()->global_php_image_extension;
                $messages['image' . $i . '.max'] =  'File size should not be more than ' . (request()->global_php_file_size / 1000) . 'MB';
            }
        }

        return $messages;
    }
}

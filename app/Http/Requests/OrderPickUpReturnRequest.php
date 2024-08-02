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
        if ('Waiting' == $order['order']->status) {
            $this->maxImageCount = 5;
            $this->minImageCount = 1;
        } else {
            $this->maxImageCount = 5;
            $this->minImageCount = 1;
        }

        $counter = 0;
        $validate = [];
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
                $validate['image' . $i] =  'mimes:' . 'jpg,jpeg,png' . '|max:' . '4096';
            }
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
            'images.required' =>  'Please upload atleast ' . $this->minImageCount . ' images',
        ];

        // for ($i = 1; $i <= $this->maxImageCount; $i++) {
        //     if (!is_null(request()->file('image' . $i))) {
        //         $messages['image' . $i . '.mimes'] =  'Please upload only ' . request()->global_php_image_extension;
        //         $messages['image' . $i . '.max'] =  'File size should not be more than ' . (request()->global_php_file_size / 1000) . 'MB';
        //     }
        // }

        return $messages;
    }
}

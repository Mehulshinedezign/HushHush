<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        if (isset(request()->product_pagination)) {
            return [
                'product_pagination' => 'required|numeric',
                'home_page_title' => 'max: 250',
                'footer_page_title' => 'max: 250',
            ];
        }

        return [
            
        ];
        
    }
}

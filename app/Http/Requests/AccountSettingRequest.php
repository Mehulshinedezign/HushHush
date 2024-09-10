<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'complete_address' => 'required',
            // 'addressline1' => 'required',
            // 'addressline2' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
        ];
    }

    public function messages(){
        return [

            'complete_address.required' => 'This field is required.',
            // 'addressline1.required' => 'This field is required.',
            // 'addressline2.required' => 'This field is required.',
            'country.required' => 'This field is required.',
            'state.required' => 'This field is required.',
            'city.required' => 'This field is required.',
           

        ];
    }
}

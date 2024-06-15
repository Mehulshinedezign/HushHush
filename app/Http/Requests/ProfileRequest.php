<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'current_password' => 'required',
            'new_password' => 'required|min:8|same:confirm_password',
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
            'current_password.required' =>  __('user.validations.currentPasswordRequired'),
            'new_password.required' =>  __('user.validations.newPasswordRequired'),
            'new_password.min' =>  __('user.validations.newPasswordMin'),
            'new_password.same' =>  __('user.validations.newPasswordMatch'),
        ];
    }
}

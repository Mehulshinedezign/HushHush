<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDetailRequest extends FormRequest
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
        $requestArray = [
            'name' => 'required',
            'phone_number' => 'required|digits:' . config('validation.phone_minlength'), 'min:' . config('validation.phone_minlength'), 'max:' . config('validation.phone_maxlength'),
        ];

        if (auth()->user()->role->name != 'admin') {
            $requestArray = array_merge($requestArray, [
                // 'address1' => 'required',
                'country' => 'required|exists:countries,id',
                'state' => 'required|exists:states,id',
                // 'city' => 'required|exists:cities,id',
                'postcode' => 'required',
                'profile_pic' => 'mimes:jpg,jpeg,png|max:2000',
                'proof' => 'mimes:pdf,jpg,jpeg,png|max:2000',
            ]);
        }
        //  else {
        //     $emailRegex = "/^[a-zA-Z]+[a-zA-Z0-9_\.\-]*@[a-zA-Z]+(\.[a-zA-Z]+)*[\.]{1}[a-zA-Z]{2,10}$/";
        //     $requestArray['email'] = 'required|email|regex:' . $emailRegex;
        // }

        return $requestArray;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' =>  __('user.validations.nameRequired'),
            // 'email.required' => __('user.validations.emailRequired'),
            // 'email.string' => __('user.validations.emailString'),
            // 'email.email' => __('user.validations.emailType'),
            // 'email.regex' => __('user.validations.emailType'),
            'phone_number.required' =>  __('user.validations.phoneRequired'),
            'phone_number.regex' =>  __('user.validations.phoneRegex'),
            // 'address1.required' =>  __('user.validations.address1Required'),
            'country.required' =>  __('user.validations.countryRequired'),
            'country.exists' =>  __('user.validations.countryExist'),
            'state.required' =>  __('user.validations.stateRequired'),
            'state.exists' =>  __('user.validations.stateExist'),
            // 'city.required' =>  __('user.validations.cityRequired'),
            'city.exists' =>  __('user.validations.cityExist'),
            'proof.mimes' =>  __('user.validations.proofExtenstion'),
            'proof.max' =>  __('user.validations.proofSize'),
            'profile_pic.mimes' =>  __('user.validations.profilePicExtenstion'),
            'profile_pic.max' =>  __('user.validations.profilePicSize'),
        ];
    }
}

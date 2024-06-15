<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends BaseFormRequest
{
    private $maxProductRentAmount = 99999.99;
    private $productDescriptionLimit = 10000;
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
        $rentRegex = "/^\d+(\.\d{1,2})?$/";
        $validate = [
            'name' => 'required',
            'category' => 'required',
            //'thumbnail_image' => 'required',
            'description' => ['required', 'max:' . $this->productDescriptionLimit],
            'rent' => ['required', 'min:1', 'lte:' . $this->maxProductRentAmount, 'regex: ' . $rentRegex],
            'price' => ['required', 'min:1', 'lte:' . $this->maxProductRentAmount, 'regex: ' . $rentRegex],
        ];

        $counter = 0;
        for ($i = 1; $i <= request()->global_max_product_image_count; $i++) {
            if (request()->hasFile('image' . $i)) {
                $counter++;
                $validate['image' . $i] = 'mimes:' . request()->global_php_image_extension . '|max:' . request()->global_php_file_size;
            }
        }

        $locationCount = (request()->location_count >= 1) ? request()->location_count : 1;

        // for($i = 0; $i < $locationCount; $i++) {
        //     $validate['locations.value.' . $i] = 'required';
        //     $validate['locations.custom.' . $i] = 'required';
        //     $validate['locations.latitude.' . $i] = 'required';
        //     $validate['locations.longitude.' . $i] = 'required';
        //     // $validate['locations.country.' . $i] = 'required';
        //     // $validate['locations.state.' . $i] = 'required';
        //     // $validate['locations.city.' . $i] = 'required';
        //     // $validate['locations.postal_code.' . $i] = 'required';
        // }

        if ($counter < request()->global_min_product_image_count && request()->uploaded_image_count < request()->global_min_product_image_count) {
            $validate['error'] = 'required';
        }

        // $nonAvailableDates = request()->non_availabile_dates;
        // $nonAvailableDatesArray = [];

        // if (count($nonAvailableDates) > 1) {
        //     foreach ($nonAvailableDates as $nonAvailableDate) {
        //         $fromAndToDate = array_map('trim', explode(request()->global_date_separator, $nonAvailableDate));
        //         $nonAvailableDatesArray = array_merge($nonAvailableDatesArray, $fromAndToDate);
        //     }

        //     if ('Day' == request()->rentaltype) {
        //         $validateFormat = request()->global_date_format;
        //     } else {
        //         $validateFormat = request()->global_product_date_time_format;
        //     }
        //     $unavailabilityLength = count($nonAvailableDatesArray);
        //     $j = 0;
        //     for ($i = 0; $i < $unavailabilityLength; $i += 2) {
        //         if (strtotime($nonAvailableDatesArray[$i]) == false) {
        //             $validate['non_availabile_dates.' . $j] = "date_format: " .  $validateFormat;
        //         }
        //         $j++;
        //     }
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
        $message = [
            'name.required' =>  'Please enter the product name',
            'category.required' =>  'Please select the product category',
            //'category.exists' =>  'Selected product category doesn\'t exist',
            'description.required' =>  'Please enter the product description',
            'description.max' =>  'Product description should not be more than ' . $this->productDescriptionLimit,
            'rent.required' => 'Please enter the rent amount',
            'rent.min' => 'Minimum rent amount should be 1',
            'rent.lte' => 'Maximum rent amount will be ' . $this->maxProductRentAmount,
            'rent.regex' =>  'Rent amount can be upto 2 digits from decimal',
            'price.required' =>  'Please enter the product estimated value',
            'price.min' => 'Minimum product estimated value should be 1',
            'price.lte' => 'Maximum product estimated value will be ' . $this->maxProductRentAmount,
            'price.regex' =>  'Price amount can be upto 2 digits from decimal',
            //'thumbnail_image.required' =>  'Please select the thumbnail image',
            'error.required' =>  'Please upload atleast ' . request()->global_min_product_image_count . ' images',
        ];

        for ($i = 1; $i <= request()->global_max_product_image_count; $i++) {
            if (!is_null(request()->file('image' . $i))) {
                $messages['image' . $i . '.mimes'] =  'Please upload only ' . request()->global_php_image_extension;
                $messages['image' . $i . '.max'] =  'File size should not be more than ' . (request()->global_php_file_size / 1000) . 'MB';
            }
        }

        $locationCount = (request()->location_count >= 1) ? request()->location_count : 1;
        // for($i = 0; $i < $locationCount; $i++) {
        //     $message['locations.value.' . $i . '.required'] = __('product.validations.locationRequired');
        //     $message['locations.custom.' . $i . '.required'] = __('product.validations.locationCustomRequired');
        //     $message['locations.latitude.' . $i . '.required'] = __('product.validations.latitudeRequired');
        //     $message['locations.longitude.' . $i . '.required'] = __('product.validations.longitudeRequired');
        //     // $message['locations.country.' . $i . '.required'] = __('product.validations.oops');
        //     // $message['locations.state.' . $i . '.required'] = __('product.validations.oops');
        //     // $message['locations.city.' . $i . '.required'] = __('product.validations.oops');
        //     // $message['locations.postal_code.' . $i . '.required'] = __('product.validations.oops');
        // }

        // $nonAvailableDates = request()->non_availabile_dates;
        // $nonAvailableDatesArray = [];
        // foreach ($nonAvailableDates as $nonAvailableDate) {
        //     $fromAndToDate = array_map('trim', explode(request()->global_date_separator, $nonAvailableDate));
        //     $nonAvailableDatesArray = array_merge($nonAvailableDatesArray, $fromAndToDate);
        // }

        // $unavailabilityLength = count($nonAvailableDatesArray);
        // $j = 0;

        // for ($i = 0; $i < $unavailabilityLength; $i += 2) {
        //     if (strtotime($nonAvailableDatesArray[$i]) == false) {
        //         $message['non_availabile_dates.' . $j . '.date_format'] = 'Invalid date format';
        //     }

        //     $j++;
        // }

        return $message;
    }
}

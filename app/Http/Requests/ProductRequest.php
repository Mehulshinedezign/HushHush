<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        // dd(request()->toArray());
        $rentRegex = "/^\d+(\.\d{1,2})?$/";
        $counter = 0;
        $validate = [
            'name' => 'required',
            'category' => 'required|exists:categories,id',
            'thumbnail_image' => 'required',
            'rentaltype' => 'required|in:Day,Hour',
            'description' => ['required', 'max:' . $this->productDescriptionLimit],
            'rent' => ['required', 'min:1', 'lte:' . $this->maxProductRentAmount, 'regex: ' . $rentRegex],
            'price' => ['required', 'min:1', 'lte:'. $this->maxProductRentAmount, 'regex: ' . $rentRegex],
        ];
        
        for($i = 1; $i <= request()->global_max_product_image_count; $i++) {
            if (request()->hasFile('image'.$i)) {
                $counter++;
                $validate['image' . $i] = 'mimes:'.request()->global_php_image_extension.'|max:'.request()->global_php_file_size;
            }
        }

        $locationCount = (request()->location_count >= 1) ? request()->location_count : 1;
        
        for($i = 0; $i < $locationCount; $i++) {
            $validate['locations.value.' . $i] = 'required';
            $validate['locations.custom.' . $i] = 'required';
            $validate['locations.latitude.' . $i] = 'required';
            $validate['locations.longitude.' . $i] = 'required';
            // $validate['locations.country.' . $i] = 'required';
            // $validate['locations.state.' . $i] = 'required';
            // $validate['locations.city.' . $i] = 'required';
            // $validate['locations.postal_code.' . $i] = 'required';
        }

        $nonAvailableDates = request()->non_availabile_dates;
        $nonAvailableDatesArray = [];
        foreach($nonAvailableDates as $nonAvailableDate) {
            if (!is_null($nonAvailableDate)) {
                $fromAndToDate = array_map('trim', explode(request()->global_date_separator, $nonAvailableDate));
                $nonAvailableDatesArray = array_merge($nonAvailableDatesArray, $fromAndToDate);
            }
        }
        if ('Day' == request()->rentaltype) {
            $today = date(request()->global_date_format);
            $validateFormat = request()->global_date_format;
        } else {
            $today = date(request()->global_product_date_time_format);
            $validateFormat = request()->global_product_date_time_format;
        }
        $unavailabilityLength = count($nonAvailableDatesArray);
        
        $j = 0;
        for($i = 0; $i < $unavailabilityLength;  $i += 2) {
            if (strtotime($nonAvailableDatesArray[$i]) && $nonAvailableDatesArray[$i] < $today) {
                $validate['non_availabile_dates.' . $j] = "date_format: ". $validateFormat."|after_or_equal: {$today}";
            } elseif (strtotime($nonAvailableDatesArray[$i]) == false) {
                $validate['non_availabile_dates.' . $j] = "date_format: ".  $validateFormat;
            }
            $j++;
        }

        // dd($validate, $today, $validateFormat, request()->all());

        if ($counter < request()->global_min_product_image_count) {
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
            'name.required' =>  'Please enter the product name',
            'category.required' =>  'Please select the product category',
            'category.exists' =>  'Selected product category doesn\'t exist',
            'description.required' =>  'Please enter the product description',
            'description.max' =>  'Product description should not be more than ' . $this->productDescriptionLimit,
            'rentaltype.required' => 'Please enter the rent amount',
            'rent.required' => 'Please enter the rent amount',
            'rent.min' => 'Minimum rent amount should be 1',
            'rent.lte' => 'Maximum rent amount will be '. $this->maxProductRentAmount,
            'rent.regex' =>  'Rent amount can be upto 2 digits from decimal',
            'price.required' =>  'Please enter the product estimated value',
            'price.min' => 'Minimum product estimated value should be 1',
            'price.lte' => 'Maximum product estimated value will be '. $this->maxProductRentAmount, 
            'price.regex' =>  'Price amount can be upto 2 digits from decimal',
            'thumbnail_image.required' =>  'Please select the thumbnail image',
            'error.required' =>  'Please upload atleast '.request()->global_min_product_image_count.' images',
        ];

        for($i = 1; $i <= request()->global_max_product_image_count; $i++) {
            if (!is_null(request()->file('image'.$i))) {
                $messages['image'.$i.'.mimes'] =  'Please upload only '.request()->global_php_image_extension;
                $messages['image'.$i.'.max'] =  'File size should not be more than '.(request()->global_php_file_size/1000).'MB';
            }
        }

        $locationCount = (request()->location_count >= 1) ? request()->location_count : 1;
        for($i = 0; $i < $locationCount; $i++) {
            $messages['locations.value.' . $i . '.required'] = __('product.validations.locationRequired');
            $messages['locations.custom.' . $i . '.required'] = __('product.validations.locationCustomRequired');
            $messages['locations.latitude.' . $i . '.required'] = __('product.validations.latitudeRequired');
            $messages['locations.longitude.' . $i . '.required'] = __('product.validations.longitudeRequired');
            // $messages['locations.country.' . $i . '.required'] = __('product.validations.oops');
            // $messages['locations.state.' . $i . '.required'] = __('product.validations.oops');
            // $messages['locations.city.' . $i . '.required'] = __('product.validations.oops');
            // $messages['locations.postal_code.' . $i . '.required'] = __('product.validations.oops');
        }

        $nonAvailableDates = request()->non_availabile_dates;
        $nonAvailableDatesArray = [];
        foreach($nonAvailableDates as $nonAvailableDate) {
            $fromAndToDate = array_map('trim', explode(request()->global_date_separator, $nonAvailableDate));
            $nonAvailableDatesArray = array_merge($nonAvailableDatesArray, $fromAndToDate);
        }

        $today = date(session()->get('date'));
        $unavailabilityLength = count($nonAvailableDatesArray);
        $j = 0;

        for($i = 0; $i < $unavailabilityLength;  $i += 2) {
            if (strtotime($nonAvailableDatesArray[$i]) && $nonAvailableDatesArray[$i] < $today) {
                $messages['non_availabile_dates.' . $j . '.date_format'] = "Invalid date format";
                $messages['non_availabile_dates.' . $j . '.after_or_equal'] = __('product.validations.nonAvailabileDatesAfterOrEqual');
            }  elseif (strtotime($nonAvailableDatesArray[$i]) == false) {
                $messages['non_availabile_dates.' . $j . '.date_format'] = "Invalid date format";
            }
            $j++;
        }

        return $messages;
    }
}
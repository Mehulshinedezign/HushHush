<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\Models\{AdminSetting, BillingToken, Order, Product, ProductLocation};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use DateTime;

trait ProductTrait
{
    protected $range = 10;
    protected $earthRadius = 6380;
    protected $maxTotalAmount = 999999.99;

    public function getProducts(Request $request, $retailerId = null)
    {
        $with = ['locations', 'thumbnailImage', 'ratings', 'nonAvailableDates', 'retailer' => function ($q) {
            $q->where('status', '1');
            $q->where('is_approved', '1');
        }, 'categories' => function ($q) {
            $q->where('status', '1');
            $q->whereNull('deleted_at');
        }];
        if (auth()->user()) {
            array_push($with, 'favorites');
        }

        $fromDate = $toDate = null;
        $rent_min = $request->min;
        $rent_max = $request->max;
        if (isset($request->global_date_separator) && isset($request->reservation_date)) {
            $fromAndToDate = array_map('trim', explode($request->global_date_separator, $request->reservation_date));

            $fromstartDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[0]));
            $fromendDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[1]));
            if (count($fromAndToDate) == 2) {
                $fromDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromstartDate)->format('Y-m-d');
                $toDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromendDate)->format('Y-m-d');
            }
        }

        $products = Product::withCount(['ratings as average_rating' => function ($query) {
            $query->select(DB::raw('coalesce(avg(rating),0)'));
        }])
            ->with($with)->when(!is_null($request->neighborhoodcity), function ($q) use ($request) {
                $q->where('neighborhood_city', $request->neighborhoodcity)
                    ->orWhere('city', $request->neighborhoodcity);
            })
            ->whereHas('categories', function ($q) use ($request) {
                $q->where('status', '1')->whereNull('deleted_at');
                $q->when(!empty($request->category), function ($query) use ($request) {
                    $query->whereIn('id', (array)$request->category);
                });
            })
            // ->when(!is_null($request->neighborhoodcity) && !is_null($request->longitude), function ($q) use ($request) {
            //     $q->whereHas('product', function ($q) use ($request) {
            //         // $q->whereRaw('ACOS( SIN( RADIANS( latitude ) ) * SIN( RADIANS( ' . $request->latitude . ' ) ) + COS( RADIANS( latitude ) ) * COS( RADIANS( ' . $request->latitude . ' )) * COS( RADIANS( longitude ) - RADIANS( ' . $request->longitude . ' )) ) * ' . $this->earthRadius . ' < ' . $this->range);
            //         $q->where('neighborhood_city', $request->neighborhood_city);
            //     });
            // })
            ->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
                $q->whereDoesntHave('nonAvailableDates', function ($q) use ($fromDate, $toDate) {
                    $q->whereDate('from_date', '>=', $fromDate);
                    $q->whereDate('to_date', '<=', $toDate);
                });
            })
            ->when(!empty($request->rating), function ($q) use ($request) {
                $q->whereHas('ratings', function ($q) use ($request) {
                    $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
                });
            })
            ->when(!empty($request->rent), function ($q) use ($request) {
                $q->where('rent', '<=', $request->rent);
            })
            ->when(!empty($request->product), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->product . '%');
            })
            ->when(!is_null($retailerId), function ($q) use ($retailerId) {
                $q->where('user_id', $retailerId);
            })
            ->whereHas('retailer', function ($q) {
                $q->where('status', '1');
                $q->where('is_approved', '1');
            })
            ->when(!empty($request->sort_by), function ($q) use ($request) {
                if ($request->sort_by == 'price_asc') {
                    $q->orderBy('rent', 'asc');
                } else if ($request->sort_by == 'price_desc') {
                    $q->orderBy('rent', 'desc');
                } else if ($request->sort_by == 'rating_desc') {
                    $q->orderBy('average_rating', 'desc');
                } else if ($request->sort_by == 'rating_asc') {
                    $q->orderBy('average_rating', 'asc');
                }
            })
            ->when(!empty($request->rental_type), function ($q) use ($request) {
                $q->where('rentaltype', ucwords(strtolower($request->rental_type)));
            })
            ->when(!empty($request->condition), function ($q) use ($request) {
                $q->whereIn('condition', (array)$request->condition);
            })->when(!is_null($rent_min) && !is_null($rent_max), function ($q) use ($rent_min, $rent_max) {
                $q->where('rent', '>=', $rent_min);
                $q->where('rent', '<=', $rent_max);
            })
            ->when(!empty($request->filtercolor), function ($q) use ($request) {
                $q->whereIn('color', (array)$request->filtercolor);
            })
            ->when(!empty($request->brand), function ($q) use ($request) {
                $q->whereIn('brand', (array)$request->brand);
            })
            ->when(!empty($request->size), function ($q) use ($request) {
                $q->whereIn('size', (array)$request->size);
            })
            ->where('status', '1')->orderBy('id', 'desc');
        //->where('user_id', '!=', auth()->user()->id)

        return $products;
    }

    // get single product
    // public function getProduct(Request $request, $id)
    // {
    //     $fromDate = $toDate = null;

    //     if (!empty($request->reservation_date) && !empty($request->global_date_separator)) {
    //         $fromAndToDate = array_map('trim', explode($request->global_date_separator, $request->reservation_date));
    //         if (count($fromAndToDate) == 2 && !empty($request->global_date_format_for_check)) {
    //             $fromDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromAndToDate[0]);
    //             $toDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromAndToDate[1]);

    //             if ($fromDate && $toDate) {
    //                 $fromDate = $fromDate->format('Y-m-d');
    //                 $toDate = $toDate->format('Y-m-d');
    //             }
    //         }
    //     }

    //     $product = Product::with(['get_brand', 'thumbnailImage', 'images', 'allImages', 'locations', 'nonAvailableDates', 'ratings.user', 'retailer' => function ($q) {
    //         $q->where('status', '1');
    //         $q->where('is_approved', '1');
    //     }, 'categories' => function ($q) {
    //         $q->where('status', '1');
    //         $q->whereNull('deleted_at');
    //     }])->withCount('ratings')
    //         ->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
    //             $q->whereDoesntHave('nonAvailableDates', function ($q) use ($fromDate, $toDate) {
    //                 $q->whereBetween('from_date', [$fromDate, $toDate]);
    //                 $q->orWhereBetween('to_date', [$fromDate, $toDate]);
    //             });
    //         })
    //         ->when(!empty($request->rating), function ($q) use ($request) {
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(ratings.rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when(!empty($request->rate), function ($q) use ($request) {
    //             $q->where('rate', '<=', $request->rate);
    //         })
    //         ->whereHas('retailer', function ($q) {
    //             $q->where('status', '1');
    //             $q->where('is_approved', '1');
    //         })
    //         ->whereHas('categories', function ($q) use ($request) {
    //             $q->where('status', '1')->whereNull('deleted_at');
    //             $q->when(!empty($request->category), function ($query) use ($request) {
    //                 $query->whereIn('id', $request->category);
    //             });
    //         })
    //         ->where('status', '1')
    //         ->where('id', $id)
    //         ->first();

    //     return $product;
    // }

    public function getProduct(Request $request, $id)
    {
        $fromDate = $toDate = null;

        if (!empty($request->reservation_date) && !empty($request->global_date_separator)) {
            $fromAndToDate = array_map('trim', explode($request->global_date_separator, $request->reservation_date));
            if (count($fromAndToDate) == 2 && !empty($request->global_date_format_for_check)) {
                $fromDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromAndToDate[0]);
                $toDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromAndToDate[1]);

                if ($fromDate && $toDate) {
                    $fromDate = $fromDate->format('Y-m-d');
                    $toDate = $toDate->format('Y-m-d');
                }
            }
        }

        $product = Product::with([
            'get_brand',
            'thumbnailImage',
            'images',
            'allImages',
            'locations',
            'nonAvailableDates',
            'ratings.user',
            'retailer' => function ($q) {
                $q->where('status', '1');
                $q->where('is_approved', '1');
            },
            'categories' => function ($q) {
                $q->where('status', '1');
                $q->whereNull('deleted_at');
            }
        ])
            ->withCount('ratings')
            ->when(!is_null($fromDate) && !is_null($toDate), function ($q) use ($fromDate, $toDate) {
                $q->whereDoesntHave('nonAvailableDates', function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('from_date', [$fromDate, $toDate])
                        ->orWhereBetween('to_date', [$fromDate, $toDate]);
                });
            })
            ->when(!empty($request->rating), function ($q) use ($request) {
                $q->whereHas('ratings', function ($q) use ($request) {
                    $q->havingRaw('AVG(ratings.rating) >= ?', [$request->rating]);
                });
            })
            ->when(!empty($request->rate), function ($q) use ($request) {
                $q->where('rate', '<=', $request->rate);
            })
            ->whereHas('retailer', function ($q) {
                $q->where('status', '1');
                $q->where('is_approved', '1');
            })
            ->whereHas('categories', function ($q) use ($request) {
                $q->where('status', '1')->whereNull('deleted_at');
                $q->when(!empty($request->category), function ($query) use ($request) {
                    $query->whereIn('id', $request->category);
                });
            })
            ->where('status', '1')
            ->where('id', $id)
            ->first();

        return $product;
    }


    // get the nearest product location
    public function productNearestLocation($latitude, $longitude, $productId)
    {
        return ProductLocation::where('product_id', $productId)->first();
        // ->selectRaw("id, latitude, longitude,( ACOS( SIN( RADIANS( latitude ) ) * SIN( RADIANS( {$latitude} ) ) + COS( RADIANS( latitude ) ) * COS( RADIANS( {$latitude} )) * COS( RADIANS( `longitude` ) - RADIANS( {$longitude} )) ) * {$this->earthRadius}) AS distance")
        // ->whereRaw(('ACOS( SIN( RADIANS( latitude ) ) * SIN( RADIANS( ' . $latitude . ' ) ) + COS( RADIANS( latitude ) ) * COS( RADIANS( ' . $latitude . ' )) * COS( RADIANS( `longitude` ) - RADIANS( ' . $longitude . ' )) ) * ' . $this->earthRadius . ' < ' . $this->range))
        // ->orderBy('distance', 'ASC')

    }

    public function validateReservationDate(Request $request, $product)
    {
        $fromAndToDate = array_map('trim', explode($request->global_date_separator, $request->reservation_date));
        $fromstartDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[0]));
        $fromendDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[1]));
        if (count($fromAndToDate) != 2) {
            return 'fail';
        }

        if ('Hour' == $product->rentaltype) {

            $today = date(request()->global_product_date_time_format);

            $fromDate = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[0])->format('Y-m-d');
            $toDate = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[1])->format('Y-m-d');
            $fromHour = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[0])->format('H');
            $fromMinute = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[0])->format('i');
            $toHour = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[1])->format('H');
            $toMinute = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[1])->format('i');
        } else {

            $today = date('Y-m-d');

            $fromDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromstartDate)->format('Y-m-d');
            $toDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromendDate)->format('Y-m-d');
        }

        if ($fromDate < $today || $toDate < $fromDate) {
            return 'fail';
        }

        $to = Carbon::createFromFormat('Y-m-d', $toDate);
        $from = Carbon::createFromFormat('Y-m-d', $fromDate);
        $rentalDays = $to->diffInDays($from) + 1;

        if ('Hour' == $product->rentaltype) {
            return [
                'from' => $fromDate,
                'to' => $toDate,
                'fromHour' => $fromHour,
                'fromMinute' => $fromMinute,
                'toHour' => $toHour,
                'toMinute' => $toMinute,
                'rental_days' => $rentalDays
            ];
        } else {
            return [
                'from' => $fromDate,
                'to' => $toDate,
                'rental_days' => $rentalDays
            ];
        }
    }

    public function checkProductAvailability(Request $request, $productId)
    {
        $product = Product::where('id', $productId)->first();

        if ('Hour' == $product->rentaltype) {
            return $this->checkTimeAvailablity($request, $productId);
        }

        $fromDate = $toDate = null;
        $fromAndToDate = array_map('trim', explode($request->global_date_separator, $request->reservation_date));
        $fromstartDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[0]));
        $fromendDate = date($request->global_date_format_for_check, strtotime($fromAndToDate[1]));
        if (count($fromAndToDate) == 2) {
            $fromDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromstartDate)->format('Y-m-d');
            $toDate = DateTime::createFromFormat($request->global_date_format_for_check, $fromendDate)->format('Y-m-d');
        }

        if ($request->has('product_location')) {
            $product = Product::with(['category', 'locations' => function ($q) use ($request) {
                $q->where('id', $request->product_location);
            }, 'nonAvailableDates'])
                ->whereDoesntHave('nonAvailableDates', function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('from_date', [$fromDate, $toDate]);
                    $q->orWhereBetween('to_date', [$fromDate, $toDate]);
                })
                ->whereHas('locations', function ($q) use ($request) {
                    $q->where('id', '=', $request->product_location);
                })
                ->whereHas('category', function ($q) {
                    $q->where('status', '1');
                })
                ->where('status', '1')
                ->where('available', '>', 0)
                ->where('id', $productId)
                ->first();
        } else {
            $product = Product::with('category', 'nonAvailableDates')
                ->whereDoesntHave('nonAvailableDates', function ($q) use ($fromDate, $toDate) {
                    $q->whereBetween('from_date', [$fromDate, $toDate]);
                    $q->orWhereBetween('to_date', [$fromDate, $toDate]);
                })
                ->whereHas('category', function ($q) {
                    $q->where('status', '1');
                })
                ->where('status', '1')
                ->where('available', '>', 0)
                ->where('id', $productId)
                ->first();
        }

        return $product;
    }

    public function checkTimeAvailablity(Request $request, $productId)
    {
        $product = Product::where('id', $productId)->first();

        $fromDate = $toDate = null;
        $fromAndToDate = array_map('trim', explode($request->global_date_separator, $request->reservation_date));

        $today = date(request()->global_product_date_time_format);
        $validateFormat = request()->global_product_date_time_format;

        if (count($fromAndToDate) == 2) {
            $fromDate = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[0])->format($validateFormat);
            $toDate = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[1])->format($validateFormat);
            $fromHour = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[0])->format('H');
            $fromMinute = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[0])->format('i');
            $toHour = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[1])->format('H');
            $toMinute = DateTime::createFromFormat($request->global_product_date_time_format, $fromAndToDate[1])->format('i');
        }

        if ($request->has('product_location')) {
            $product = Product::with(['category', 'locations' => function ($q) use ($request) {
                $q->where('id', $request->product_location);
            }, 'nonAvailableDates'])
                ->whereDoesntHave('nonAvailableDates', function ($q) use ($fromDate, $toDate, $fromHour, $fromMinute, $toHour, $toMinute) {
                    $q->whereBetween('from_date', [$fromDate, $toDate])->where('from_hour', '>=', $fromHour)->where('from_minute', '>=', $fromMinute)->where('to_hour', '<=', $toHour)->where('to_minute', '<=', $toMinute);
                    $q->orWhereBetween('to_date', [$fromDate, $toDate])->where('from_hour', '>=', $fromHour)->where('from_minute', '>=', $fromMinute)->where('to_hour', '<=', $toHour)->where('to_minute', '<=', $toMinute);
                })
                ->whereHas('locations', function ($q) use ($request) {
                    $q->where('id', '=', $request->product_location);
                })
                ->whereHas('category', function ($q) {
                    $q->where('status', 'Active');
                })
                ->where('status', 'Active')
                ->where('available', '>', 0)
                ->where('id', $productId)
                ->first();
        } else {
            $product = Product::with('category', 'nonAvailableDates')
                ->whereDoesntHave('nonAvailableDates', function ($q) use ($fromDate, $toDate, $fromHour, $fromMinute, $toHour, $toMinute) {
                    $q->whereBetween('from_date', [$fromDate, $toDate])->where('from_hour', '>=', $fromHour)->where('from_minute', '>=', $fromMinute)->where('to_hour', '<=', $toHour)->where('to_minute', '<=', $toMinute);
                    $q->orWhereBetween('to_date', [$fromDate, $toDate])->where('from_hour', '>=', $fromHour)->where('from_minute', '>=', $fromMinute)->where('to_hour', '<=', $toHour)->where('to_minute', '<=', $toMinute);
                })
                ->whereHas('category', function ($q) {
                    $q->where('status', 'Active');
                })
                ->where('status', 'Active')
                ->where('available', '>', 0)
                ->where('id', $productId)
                ->first();
        }
        return $product;
    }

    public function validateBillingToken($token)
    {
        return BillingToken::where('token', $token)->where('user_id', auth()->user()->id)->whereDate('created_at', date('Y-m-d'))->first();
    }

    public function getInsuranceAmount(Product $product)
    {
        // get the item price
        // dd($product);
        $insuranceSetting = AdminSetting::where('key', 'insurance_fee')->first();
        $insuranceAmount = $insuranceSetting->value;
        if ($insuranceSetting->type == 'Percentage') {
            $insuranceAmount = ($product->price * $insuranceSetting->value) / 100;
        }

        $insuranceAmount = number_format((float) $insuranceAmount, 2, '.', '');

        return $insuranceAmount;
    }

    public function getSecurityAmount(Product $product)
    {
        // get the item price
        $securitySetting = AdminSetting::where('key', 'security_fee')->first();
        $securityAmount = $securitySetting->value;
        if ($securitySetting->type == 'Percentage') {
            $securityAmount = ($product->price * $securitySetting->value) / 100;
        }
        $securityAmount = number_format((float) $securityAmount, 2, '.', '');

        return $securityAmount;
    }

    public function getTransactionAmount(Product $product, $rentalDays)
    {
        // get the item price
        $transactionSetting = AdminSetting::where('key', 'renter_transaction_fee')->first();
        $transactionAmount = $transactionSetting->value;
        if ($transactionSetting->type == 'Percentage') {
            $transactionAmount = (($product->rent * $rentalDays) * ($transactionSetting->value)) / 100;
        }
        $transactionAmount = number_format((float) $transactionAmount, 2, '.', '');

        return $transactionAmount;
    }

    public function getOrderCommisionAmount(Product $product)
    {
        // get the item price
        // dd($product);
        $ordercommsion = AdminSetting::where('key', 'order_commission')->first();
        $ordercommsionvalue = $ordercommsion->value;
        if ($ordercommsion->type == 'Percentage') {
            $ordercommsionvalue = ($product->rent * $ordercommsionvalue) / 100;
        }

        $ordercommsionvalue = number_format((float) $ordercommsionvalue, 2, '.', '');

        return $ordercommsionvalue;
    }
    public function getVendorReceivedAmount($securityOptionAmount, $orderCommisionAdmin, float $orderTotal)
    {
        // get the item price
        $orderSetting = AdminSetting::where('key', 'order_commission')->first();
        $orderSettingAmount = $orderSetting->value;

        $lenderReceived =  $orderTotal - $securityOptionAmount;
        $orderSettingAmount = $lenderReceived - $orderCommisionAdmin;

        $orderSettingAmount = number_format((float) $orderSettingAmount, 2, '.', '');
        // $vendorReceivedAmount = number_format((float) ($orderTotal - $orderSettingAmount), 2, '.', '');
        return ["vendor_received_amount" => $orderSettingAmount,  "order_commission_type" => $orderSetting->type, "order_commission_value" => $orderSetting->value, "order_commission_amount" => $orderCommisionAdmin];
    }

    public function getratingprogress(Product $product)
    {
        $onestar = $twostar = $threestar = $fourstar = $fivestar = 0;
        foreach ($product->ratings as $rating) {
            switch ($rating->rating) {
                case 1.0:
                    $onestar++;
                    break;
                case 2.0:
                    $twostar++;
                    break;
                case 3.0:
                    $threestar++;
                    break;
                case 4.0:
                    $fourstar++;
                    break;
                case 5.0:
                    $fivestar++;
                    break;
            }
        }
        return ['onestar' => $onestar, 'twostar' => $twostar, 'threestar' => $threestar, 'fourstar' => $fourstar, 'fivestar' => $fivestar];
    }

    public function getOrders($date)
    {

        return Order::with(['item:id,order_id,product_id,customer_id,retailer_id,total_rental_days', 'item.product:id,name', 'item.customer:id,name,email', 'item.retailer:id,name,email', 'location'])->select('id', 'from_date', 'to_date', 'location_id')->datefilter($date)->get();

        // dd($orders->toArray());
    }
}

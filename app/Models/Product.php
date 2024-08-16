<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'specification',
        'rentaltype',
        'category_id',
        'subcat_id',
        'user_id',
        'quantity',
        'rent',
        'price',
        'security',
        'available',
        'status',
        'size',
        'other_size',
        'color',
        'brand',
        'product_condition',
        'modified_user_type',
        'modified_by',
        'city',
        'neighborhood_city',
        'product_market_value',
        'product_link',
        'min_days_rent_item',
        'rent_price',
        'rent_day',
        'rent_week',
        'rent_month',
        'state',
        'country',
        'deleted_at'
    ];

    protected $appends = [
        'average_rating'
    ];

    protected static function boot()
    {
        parent::boot();

        // Cascading delete for children1 relationship
        static::deleting(function ($parent) {
            $parent->queries()->delete();
        });

        // Cascading delete for children2 relationship
        static::deleting(function ($parent) {
            $parent->locations()->delete();
        });

        static::deleting(function ($parent) {
            $parent->locations()->delete();
        });

        static::deleting(function ($parent) {
            $parent->disableDates()->delete();
        });

        static::deleting(function ($parent) {
            $parent->allImages()->delete();
        });


        // Add more event listeners for other relationships as needed
    }
    public function getRentAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getPriceAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getSecurityAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function getAverageRatingAttribute()
    {
        return number_format(round($this->ratings()->avg('rating'), 1), 1, '.', '');
    }


    /**
     * Product retailer
     *
     * @var object
     */
    public function retailer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Product category
     *
     * @var object
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->withTrashed();
    }

    /**
     * Product category
     *
     * @var object
     */
    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * single product location
     */
    public function productCompleteLocation()
    {
        return $this->hasOne(ProductLocation::class);
    }
    /**
     * Product locations
     *
     * @var object
     */
    public function locations()
    {
        return $this->hasMany(ProductLocation::class);
    }

    /**
     * Product thumbnail image
     *
     * @var object
     */
    public function thumbnailImage()
    {
        return $this->hasOne(ProductImage::class);
    }

    /**
     * Product images
     *
     * @var object
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Product images
     *
     * @var object
     */
    public function allImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Product non available dates
     *
     * @var object
     */
    public function nonAvailableDates()
    {
        return $this->hasMany(ProductUnavailability::class);
    }

    /**
     * Product ratings
     *
     * @var object
     */
    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    /**
     * Product favorites
     *
     * @var object
     */
    public function favorites()
    {
        return $this->hasOne(ProductFavorite::class)->where('user_id', @auth()->user()->id);
    }

    /**
     * Product brand
     *
     * @var object
     */
    public function get_brand()
    {
        return $this->belongsTo(Brand::class, 'brand');
    }

    /**
     * Product color
     *
     * @var object
     */
    public function get_color()
    {
        return $this->belongsTo(Color::class, 'color');
    }

    /**
     * Product size
     *
     * @var object
     */
    public function get_size()
    {
        return $this->belongsTo(Size::class, 'size');
    }


    //disable dates

    public function disableDates()
    {
        return $this->hasMany(ProductDisableDate::class);
    }

    public function querydata()
    {

        return $this->hasOne(Query::class);
    }
    public function queries()
    {
        return $this->hasMany(Query::class);
    }

    public function scopeFilterByCategories($query, $categories)
    {
        if (!empty($categories)) {
            return $query->whereIn('category_id', $categories);
        }
        return $query;
    }

    public function scopeFilterByBrands($query, $brands)
    {
        if (!empty($brands)) {
            return $query->whereIn('brand', $brands);
        }
        return $query;
    }

    public function scopeFilterBySizes($query, $sizes)
    {
        if (!empty($sizes)) {
            return $query->whereIn('size', $sizes);
        }
        return $query;
    }

    public function scopeFilterByColors($query, $colors)
    {
        if (!empty($colors)) {
            return $query->whereIn('color', $colors);
        }
        return $query;
    }

    public function scopeFilterByPriceRange($query, $priceRange)
    {
        if (in_array('1', $priceRange)) {
            $query->orWhere('price', '<', 1000);
        }
        if (in_array('2', $priceRange)) {
            $query->orWhereBetween('price', [1000, 2000]);
        }
        if (in_array('3', $priceRange)) {
            $query->orWhereBetween('price', [2000, 3000]);
        }
        if (in_array('4', $priceRange)) {
            $query->orWhere('price', '>', 3000);
        }
        return $query;
    }

    public function scopeFilterByCondition($query, $conditions)
    {
        if (!empty($conditions)) {
            return $query->whereIn('product_condition', $conditions);
        }
        return $query;
    }


    // public function scopeApplyFilters($query)
    // {
    //     $request = request();

    //     // dd($request->rating,$request->country,$request->state,$request->city);
    //         $query->when($request->Category, function ($q) use ($request) {
    //             $q->whereIn('category_id',  $request->Category);

    //         }) ->when($request->Brand, function ($q) use ($request) {
    //             $q->whereIn('brand',$request->Brand);
    //         })
    //         ->when($request->Size, function ($q) use ($request) {
    //             $q->whereIn('size',  $request->Size);
    //         })
    //         ->when($request->filled(['min_value', 'max_value']), function ($q) use ($request) {
    //             $q->whereBetween('rent_day', [$request->input('min_value'), $request->input('max_value')]);
    //         })
    //         ->when($request->filled(['country','state','city']),function ($q) use ($request){
    //             $q->where(['city'=>$request->city]);
    //         })
    //         ->when($request->filled(['country','state']) && !$request->filled(['city']),function ($q) use ($request){
    //             $q->where(['state'=>$request->state]);
    //         })
    //         ->when($request->country && !$request->filled(['state','city']),function ($q) use ($request){
    //             $q->where('country',$request->country);
    //         })
    //         ->when($request->rating, function ($q) use ($request) {
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Category', 'Brand', 'Size', 'min_value', 'max_value','Category','country','state','rating']), function ($q) use ($request) {
    //             return $q->whereIn('category_id',  $request->Category);
    //             $q->whereIn('brand',$request->Brand);
    //             $q->whereIn('size',  $request->Size);
    //             $q->whereBetween('rent_day', [$request->input('min_value'), $request->input('max_value')]);
    //             $q->where(['city'=>$request->city]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Category', 'Brand','Category','country','state','rating']) && !$request->filled(['Size', 'min_value', 'max_value']), function ($q) use ($request) {
    //             return $q->whereIn('category_id',  $request->Category);
    //             $q->whereIn('brand')->whereIn($request->Brand);
    //             $q->where(['city'=>$request->city]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Category', 'Size','country','state','city','rating']) && !$request->filled(['Brand', 'min_value', 'max_value']), function ($q) use ($request) {
    //             return $q->whereIn('category_id',  $request->Category);
    //             $q->whereIn('size',  $request->Size);
    //             $q->where(['city'=>$request->city]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Category', 'min_value', 'max_value','country','state','city','rating']) && !$request->filled(['Brand', 'Size']), function ($q) use ($request) {
    //             return $q->whereIn('category_id',  $request->Category);
    //             $q->whereBetween('rent_day', [$request->input('min_value'), $request->input('max_value')]);
    //             $q->where(['city'=>$request->city]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Category','country','state','city','rating']),function($q) use ($request){
    //             return $q->whereIn('category_id',$request->Category);
    //             $q->where(['city'=>$request->city]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Category','country','state','rating']) && !$request->filled(['city']),function($q) use ($request){
    //             return $q->whereIn('category_id',$request->Category);
    //             $q->where(['state'=>$request->state]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Category','country','rating']) && !$request->filled(['state','city']),function($q) use ($request){
    //             return $q->whereIn('category_id',$request->Category);
    //             $q->where(['country'=>$request->country]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Brand','country','state','city','rating']),function($q) use ($request){
    //             return $q->whereIn('brand',$request->Brand);
    //             $q->where(['city'=>$request->city]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Brand','country','state','rating']) && !$request->filled(['city']),function($q) use ($request){
    //             return $q->whereIn('brand',$request->Brand);
    //             $q->where(['state'=>$request->state]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Brand','country','rating']) && !$request->filled(['state','city']),function($q) use ($request){
    //             return $q->whereIn('brand',$request->Brand);
    //             $q->where(['country'=>$request->country]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Size','country','state','city','rating']),function($q) use ($request){
    //             return $q->whereIn('size',  $request->Size);
    //             $q->where(['city'=>$request->city]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['Size','country','state','rating']) && !$request->filled(['city']),function($q) use ($request){
    //             return $q->whereIn('size',  $request->Size);
    //             $q->where(['state'=>$request->state]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);;
    //             });
    //         })
    //         ->when($request->filled(['Size','country','rating']) && !$request->filled(['state','city']),function($q) use ($request){
    //             return $q->whereIn('size',  $request->Size);
    //             $q->where(['country'=>$request->country]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['min_value', 'max_value','country','state','city','rating']),function($q) use ($request){
    //             $q->whereBetween('rent_day', [$request->input('min_value'), $request->input('max_value')]);
    //             $q->where(['city'=>$request->city]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['min_value', 'max_value','country','state','rating']) && !$request->filled(['city']),function($q) use ($request){
    //             $q->whereBetween('rent_day', [$request->input('min_value'), $request->input('max_value')]);
    //             $q->where(['state'=>$request->state]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })
    //         ->when($request->filled(['min_value', 'max_value','country','rating']) && !$request->filled(['state','city']),function($q) use ($request){
    //             $q->whereBetween('rent_day', [$request->input('min_value'), $request->input('max_value')]);
    //             $q->where(['country'=>$request->country]);
    //             $q->whereHas('ratings', function ($q) use ($request) {
    //                 $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
    //             });
    //         })

    //         ->when($request->filled('filter_date'), function ($q) use ($request) {
    //             $dateRange = $request->filter_date;
    //             $dates = explode(' - ', $dateRange);
    //             $startDate = date('Y-m-d', strtotime($dates[0]));
    //             $endDate = date('Y-m-d', strtotime($dates[1]));
    //             return $q->filterByDateRange($startDate, $endDate);
    //         });

    // }

    public function scopeApplyFilters($query)
{
    $request = request();

    // Category filter
    $query->when($request->filled('category'), function ($q) use ($request) {
        $categories = is_array($request->category) ? $request->category : [$request->category];
        $q->whereIn('category_id', $categories);
    });

    // Subcategory filter
    $query->when($request->filled('Subcategory'), function ($q) use ($request) {
        $subcategories = is_array($request->Subcategory) ? $request->Subcategory : [$request->Subcategory];
        $q->whereIn('subcat_id', $subcategories);
    });

    // Brand filter
    $query->when($request->filled('Brand'), function ($q) use ($request) {
        $brands = is_array($request->Brand) ? $request->Brand : [$request->Brand];
        $q->whereIn('brand', $brands);
    });

    // Size filter
    $query->when($request->filled('Size'), function ($q) use ($request) {
        $sizes = is_array($request->Size) ? $request->Size : [$request->Size];
        $q->whereIn('size', $sizes);
    });

    // Price range filter
    $query->when($request->filled(['min_value', 'max_value']), function ($q) use ($request) {
        $q->whereBetween('rent_day', [$request->input('min_value'), $request->input('max_value')]);
    });

    // Location filters (Country, State, City)
    $query->when($request->filled('city'), function ($q) use ($request) {
        $q->where('city', $request->city);
    });

    $query->when($request->filled('state') && !$request->filled('city'), function ($q) use ($request) {
        $q->where('state', $request->state);
    });

    $query->when($request->filled('country') && !$request->filled(['state', 'city']), function ($q) use ($request) {
        $q->where('country', $request->country);
    });

    // Rating filter
    $query->when($request->filled('rating'), function ($q) use ($request) {
        $q->whereHas('ratings', function ($q) use ($request) {
            $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
        });
    });

    // Date range filter
    $query->when($request->filled('filter_date'), function ($q) use ($request) {
        $dateRange = $request->filter_date;
        $dates = explode(' - ', $dateRange);
        $startDate = date('Y-m-d', strtotime($dates[0]));
        $endDate = date('Y-m-d', strtotime($dates[1]));
        return $q->whereBetween('created_at', [$startDate, $endDate]);
    });

    return $query;
}


    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {

        if (!empty($startDate) && !empty($endDate)) {

            // dd($startDate,$endDate);
            return $query->whereDoesntHave('disableDates', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('disable_date', [$startDate, $endDate]);
            });
        }
        return $query;
    }



    public function getCalculatedPrice($dateRange)
    {
        [$startDateStr, $endDateStr] = explode(' - ', $dateRange);
        $startDate = Carbon::createFromFormat('Y-m-d', trim($startDateStr));
        $endDate = Carbon::createFromFormat('Y-m-d', trim($endDateStr));

        $days = $startDate->diffInDays($endDate) + 1;

        $rent_day = $this->rent_day ?? 0;
        $rent_week = $this->rent_week ?? 0;
        $rent_month = $this->rent_month ?? 0;

        if ($days > 30) {
            $months = intdiv($days, 30);
            $remainingDays = $days % 30;
            if ($remainingDays > 7) {
                $weeks = intdiv($remainingDays, 7);
                $remainingDays = $remainingDays % 7;
                $price = ($months * $rent_month) + ($weeks * $rent_week) + ($remainingDays * $rent_day);
            } else {
                $price = ($months * $rent_month) + ($remainingDays * $rent_day);
            }
        } elseif ($days > 7) {
            $weeks = intdiv($days, 7);
            $remainingDays = $days % 7;
            $price = ($weeks * $rent_week) + ($remainingDays * $rent_day);
        } else {
            $price = $days * $rent_day;
        }

        return $price;
    }

    public static function countUserProducts($userId)
    {
        return self::where('user_id', $userId)->count();
    }

    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcat_id');
    }
}

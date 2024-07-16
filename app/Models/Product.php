<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'specification', 'rentaltype', 'category_id', 'subcat_id', 'user_id', 'quantity', 'rent', 'price', 'security', 'available', 'status', 'size', 'other_size', 'color', 'brand', 'product_condition', 'modified_user_type', 'modified_by', 'city', 'neighborhood_city','product_market_value','product_link','min_days_rent_item','rent_price','rent_day','rent_week','rent_month','state','country'

    ];

    protected $appends = [
        'average_rating'
    ];

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

    public function querydat(){

        return $this->hasOne(Query::class);
    }

}

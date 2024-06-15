<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'category_image_name',
        'category_image_url',
        'parent_id',
        'status'
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Category products
     *
     * @var object
     */

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Category Size types
     *
     * @var object
     */

     public function size_type()
     {
        return $this->hasMany(CategorySizeRelation::class);
     }

    public function scopeSearch($query)
    {
        $request = request();
        $query->when(!empty($request->search), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        })->when(!is_null($request->dates), function ($q) use ($request) {
            $dates = explode(' - ', $request->dates);
            $dateFrom = $dates[0];
            $dateTo = $dates[1];
            $q->where(function ($query) use ($dateFrom, $dateTo) {
                $query->whereDate('created_at', '>=', $dateFrom);
                $query->whereDate('created_at', '<=', $dateTo);
            });
        })->when(!is_null($request->filter_by_status), function ($q) use ($request) {
            switch($request->filter_by_status){
                case('Active');
                    $q->where('status', '1');
                break;
                case('Inactive');
                    $q->where('status', '0');
                break;
                case('main');
                    $q->where('parent_id', null);
                break;
                case('sub');
                    $q->where('parent_id', '!=', '');
                break;
                default:
                    return;
            }
        });
    }
}

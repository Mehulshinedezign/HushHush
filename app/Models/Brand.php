<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name','status'];

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
                default:
                    return;
            }
        });
    }
}

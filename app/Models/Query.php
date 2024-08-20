<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Query extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'for_user',
        'query_message',
        'status',
        'date_range',
        'negotiate_price',
        'chat_enabled',
        'shipping_charges',
        'cleaning_charges'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function forUser()
    {
        return $this->belongsTo(User::class, 'for_user')->withTrashed();
    }

    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public function getCalculatedPrice($dateRange)
    {
        [$startDateStr, $endDateStr] = explode(' - ', $dateRange);
        $startDate = Carbon::createFromFormat('Y-m-d', trim($startDateStr));
        $endDate = Carbon::createFromFormat('Y-m-d', trim($endDateStr));

        $days = $startDate->diffInDays($endDate) + 1;
        // dd($days);

        $rent_day = $this->product->rent_day ?? 0;
        $rent_week = $this->product->rent_week ?? 0;
        $rent_month = $this->product->rent_month ?? 0;

        //   dd($rent_day,$rent_week,$rent_month);
        if ($days >= 30) {
            $months = intdiv($days, 30);
            $remainingDays = $days % 30;
            if ($remainingDays >= 7) {
                $weeks = intdiv($remainingDays, 7);
                $remainingDays = $remainingDays % 7;
                $price = ($months * $rent_month) + ($weeks * $rent_week) + ($remainingDays * $rent_day);
            } else {
                $price = ($months * $rent_month) + ($remainingDays * $rent_day);
            }
        } elseif ($days >= 7) {
            $weeks = intdiv($days, 7);
            $remainingDays = $days % 7;
            $price = ($weeks * $rent_week) + ($remainingDays * $rent_day);
        } else {
            $price = $days * $rent_day;
        }

        // dd("price is : ",$price);
        return $price;
    }

    public function getStartDateAttribute()
    {
        return explode(' - ', $this->date_range)[0] ?? null;
    }

    public function getEndDateAttribute()
    {
        return explode(' - ', $this->date_range)[1] ?? null;
    }
    public function totalBookPrice()
    {
        return ($this->negotiate_price + $this->shipping_charges + $this->cleaning_charges);
    }

    public static function countUserQueries($userId)
    {
        return self::where('for_user', $userId)->count();
    }
}

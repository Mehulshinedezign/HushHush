<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductDisableDate;
use App\Models\ProductDisableDates;
use Carbon\Carbon;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->clearExpiredDisableDates($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->clearExpiredDisableDates($product);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->clearExpiredDisableDates($product);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        $this->clearExpiredDisableDates($product);
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        $this->clearExpiredDisableDates($product);
    }

    /**
     * Clear expired disable dates for the given product.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    protected function clearExpiredDisableDates(Product $product)
    {
        ProductDisableDate::where('disable_date', '<', Carbon::today())->delete();
    }
}



<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use App\Mail\PickupTimeLocation;
use App\Http\Traits\ProductTrait;

class PickupTime extends Command
{
    use ProductTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pickup:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send reminder mail to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (count($this->getOrders('fromdate')) > 0) {
            foreach ($this->getOrders('fromdate') as $order) {
                $mailData = [
                    'name' => $order->item->customer->name,
                    'product_name' => $order->item->product->name,
                    'retailer' => $order->item->retailer->name,
                    'rental_date' => $order->from_date . ' To ' . $order->to_date,
                    'location' => $order->location->map_address,
                ];

                Mail::to($order->item->customer->email)->send(new PickupTimeLocation($mailData, '1'));
                Mail::to($order->item->retailer->email)->send(new PickupTimeLocation($mailData, '2'));
            }
        }
    }
}

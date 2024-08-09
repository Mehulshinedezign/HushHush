<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class transaction extends Component
{
    /**
     * Create a new component instance.
     */
    public $orders, $earnings;
    public function __construct($orders, $earnings)
    {
        $this->orders = $orders;
        $this->earnings = $earnings;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $orders = $this->orders;
        $earnings = $this->earnings;
        return view('components.transaction', compact('orders', 'earnings'));
    }
}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductQuery extends Component
{
    /**
     * Create a new component instance.
    */

    public $querydatas;

    public function __construct($querydatas)
    {
        $this->querydatas = $querydatas;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-query');
    }
}

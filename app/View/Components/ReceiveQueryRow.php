<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReceiveQueryRow extends Component
{
    /**
     * Create a new component instance.
     */

     public $query;

     public function __construct($query)
     {
         $this->query = $query;
     }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.receive-query-row');
    }
}

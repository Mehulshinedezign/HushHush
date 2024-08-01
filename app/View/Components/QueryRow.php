<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class QueryRow extends Component
{
    public $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function render(): View
    {
        return view('components.query-row');
    }
}
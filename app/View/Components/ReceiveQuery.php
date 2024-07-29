<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ReceiveQuery extends Component
{
    public $querydatas;
    public $accept;

    /**
     * Create a new component instance.
     *
     * @param $querydatas
     * @return void
     */
    public function __construct($querydatas,$accept)
    {
        $this->querydatas = $querydatas;
        $this->accept = $accept;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.receive-query');
    }
}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Slips extends Component
{
    /**
     * Create a new component instance.
     */

    public $attandees;
    public $componentKey;
    public function __construct(
        $componentKey,
        $slipData
    ) {
        $this->componentKey = $componentKey;
        $this->attandees = $slipData;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.slips');
    }
}

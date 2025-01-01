<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WorkingTimes extends Component
{
    public $workingTimes;
    /**
     * Create a new component instance.
     */
    public function __construct($workingTimes = null)
    {
        $this->workingTimes = $workingTimes;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.working-times');
    }
}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReviewModal extends Component
{
    public $title;
    public $route;
    public $profileId;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $route, $profileId)
    {
        $this->title = $title;
        $this->route = $route;
        $this->profileId = $profileId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.review-modal');
    }
}

<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FilterAccordionAndSearch extends Component
{
    public $genres;
    public $venuePromoterCount;
    public $promoterVenueCount;
    /**
     * Create a new component instance.
     */
    public function __construct($genres)
    {
        $this->genres = $genres;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.filter-accordion-and-search');
    }
}
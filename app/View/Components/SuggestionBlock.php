<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SuggestionBlock extends Component
{
    public $existingPromoters;
    public $promoterWithHighestRating;
    public $photographerWithHighestRating;
    public $videographerWithHighestRating;
    public $bandWithHighestRating;
    public $designerWithHighestRating;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $existingPromoters = null,
        $promoterWithHighestRating = null,
        $photographerWithHighestRating = null,
        $videographerWithHighestRating = null,
        $bandWithHighestRating = null,
        $designerWithHighestRating = null
    ) {
        $this->existingPromoters = $existingPromoters;
        $this->promoterWithHighestRating = $promoterWithHighestRating;
        $this->photographerWithHighestRating = $photographerWithHighestRating;
        $this->videographerWithHighestRating = $videographerWithHighestRating;
        $this->bandWithHighestRating = $bandWithHighestRating;
        $this->designerWithHighestRating = $designerWithHighestRating;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.suggestion-block');
    }
}

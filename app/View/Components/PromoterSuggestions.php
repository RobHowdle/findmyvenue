<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Venue;
use App\Models\Promoter;

class PromoterSuggestions extends Component
{
    /**
     * Create a new component instance.
     */
    public $suggestedPromoters;

    public function __construct($venueId, $filters = [])
    {
        $venue = Venue::findOrFail($venueId);

        // Check if there's already a promoter associated with the venue
        $associatedPromoter = $venue->promoters->first();

        // If there's no associated promoter, retrieve promoters in the same area
        if (!$associatedPromoter) {
            $promotersInArea = Promoter::where('postal_town', $venue->postal_town);

            // Apply user filters
            foreach ($filters as $filter) {
                $promotersInArea->where($filter, true); // Adjust this based on your actual filter logic
            }

            // Filter by rating
            // $promotersInArea->orderBy('rating', 'desc');

            $this->suggestedPromoters = $promotersInArea->get();
        } else {
            $this->suggestedPromoters = collect([$associatedPromoter]);
        }
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.promoter-suggestions');
    }
}

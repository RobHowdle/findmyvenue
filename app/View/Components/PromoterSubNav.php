<?php

namespace App\View\Components;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Finance;
use App\Models\PromoterReview;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class PromoterSubNav extends Component
{
    public $eventsCountYTD;
    public $totalProfits;
    public $promoterId;
    public $promoter;
    public $overallScore;
    public $overallReviews;

    /**
     * Helper function to render rating icons
     */
    public function renderRatingIcons($overallScore)
    {
        $output = '';
        $totalIcons = 5;
        $fullIcons = floor($overallScore);
        $fraction = $overallScore - $fullIcons;
        $emptyIcon = asset('storage/images/system/ratings/empty.png');
        $fullIcon = asset('storage/images/system/ratings/full.png');
        $hotIcon = asset('storage/images/system/ratings/hot.png');

        if ($overallScore == $totalIcons) {
            // Display 5 hot icons when the score is 5/5
            $output = str_repeat('<img src="' . $hotIcon . '" alt="Hot Icon" />', $totalIcons);
        } else {
            // Add full icons
            for ($i = 0; $i < $fullIcons; $i++) {
                $output .= '<img src="' . $fullIcon . '" alt="Full Icon" />';
            }

            // Handle the fractional icon using clip-path
            if ($fraction > 0) {
                $output .= '<img src="' . $fullIcon . '" alt="Partial Full Icon" style="clip-path: inset(0 ' . ((1 - $fraction) * 100) . '% 0 0);" />';
            }

            // Add empty icons to fill the rest
            $iconsDisplayed = $fullIcons + ($fraction > 0 ? 1 : 0);
            $remainingIcons = $totalIcons - $iconsDisplayed;

            for ($i = 0; $i < $remainingIcons; $i++) {
                $output .= '<img src="' . $emptyIcon . '" alt="Empty Icon" />';
            }
        }

        return $output;
    }

    /**
     * Create a new component instance.
     */
    public function __construct(int $promoterId)
    {
        // Overall Reviews
        $this->promoterId = $promoterId;
        $promoterUser = User::find($this->promoterId);
        if ($promoterUser) {
            $promoterCompany = $promoterUser->promoters()->first();

            if ($promoterCompany) {
                $this->promoterId = $promoterCompany->id;
                $this->overallScore = PromoterReview::calculateOverallScore($this->promoterId);
            }
        }
        $this->overallReviews = $this->renderRatingIcons($this->overallScore);

        // Total Profit Year To Date
        $this->totalProfits = $this->calculateTotalProfitsYTD($promoterUser);
        $this->eventsCountYTD = $this->calculateEventsYTD($promoterUser);
    }

    public function calculateTotalProfitsYTD($promoterUser)
    {
        if ($promoterUser) {
            $promoterCompany = $promoterUser->promoters()->first();

            if ($promoterCompany) {
                $startOfYear = Carbon::now()->startOfYear();
                $endOfYear = Carbon::now()->endOfYear();

                // Query the finances table for the current year's profits
                $totalProfitsYTD = Finance::where('serviceable_id', $promoterCompany->id)
                    ->where('serviceable_type', 'App\Models\Promoter')
                    ->whereBetween('date_to', [$startOfYear, $endOfYear])
                    ->sum('total_profit');

                return $totalProfitsYTD;
            }
        }

        // Return 0 if no promoter company or no profits found
        return 0;
    }

    public function calculateEventsYTD($promoterUser)
    {
        if ($promoterUser) {
            $promoterCompany = $promoterUser->promoters()->first();

            if ($promoterCompany) {
                $startOfYear = Carbon::now()->startOfYear();
                $endOfYear = Carbon::now()->endOfYear();

                // Query the finances table for the current year's profits
                $eventsCountYTD = DB::table('event_promoter')
                    ->join('events', 'event_promoter.event_id', '=', 'events.id')
                    ->where('promoter_id', $promoterCompany->id)
                    ->whereBetween('events.event_date', [$startOfYear, $endOfYear])
                    ->count();

                return $eventsCountYTD;
            }
        }

        // Return 0 if no promoter company or no profits found
        return 0;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.promoter-sub-nav');
    }
}

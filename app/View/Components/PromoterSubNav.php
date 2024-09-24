<?php

namespace App\View\Components;

use Closure;
use App\Models\User;
use App\Models\PromoterReview;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class PromoterSubNav extends Component
{
    public $eventsYtd;
    public $totalProfitYtd;
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
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.promoter-sub-nav');
    }
}

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

class SubNav extends Component
{
    // Global
    public $userType;
    public $role;
    public $promoter;
    public $overallScore;
    public $user;

    // Promoter
    public $promoterId;
    public $eventsCountPromoterYtd;
    public $overallRatingPromoter;
    public $totalProfitsPromoterYtd;

    // Bands
    public $bandId;
    public $gigsCountBandYtd;
    public $overallRatingBand;
    public $totalProfitsBandYtd;

    // Designer
    public $designerId;
    public $jobsDesignerYTD;
    public $overallRatingDesigner;
    public $totalProfitsDesignerYtd;

    // Venue
    public $venueId;
    public $eventsCountVenueYtd;
    public $overallRatingVenue;
    public $totalProfitsVenueYtd;

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
    public function __construct(int $userId)
    {
        $this->loadUserData($userId);
    }

    private function loadUserData(int $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $this->role = $user->getRoleNames()->first();
        $this->userType = $this->role ?? 'guest'; // Set a default user type if none

        switch ($this->userType) {
            case 'promoter':
                $this->loadPromoterData($user);
                break;

            case 'band':
                $this->loadBandData($user);
                break;

                // You can handle designer and venue similarly if needed
            default:
                // Handle other types or set defaults
                break;
        }
    }

    private function loadPromoterData($user)
    {
        $promoter = $user->promoters()->first();
        if ($promoter) {
            $this->promoterId = $promoter->id;
            $this->eventsCountPromoterYtd = $this->calculateEventsCountPromoterYtd($promoter);
            $this->totalProfitsPromoterYtd = $this->calculateTotalProfitsPromoterYtd($promoter);
            $this->overallRatingPromoter = $this->renderRatingIcons($this->promoterId);
        }
    }

    private function loadBandData($user)
    {
        $bands = $user->otherService()->where('other_service_id', 4)->get();
        if ($bands->isNotEmpty()) {
            $band = $bands->first();
            $this->bandId = $band->id;
            $this->gigsCountBandYtd = $this->calculateGigsCountBandYtd($band->id);
            $this->overallRatingBand = $this->calculateOverallRatingBand($band->id);
            $this->totalProfitsBandYtd = $this->calculateTotalProfitsBandYtd($band->id);
        }
    }

    private function loadDesignerData(int $bandId)
    {
        //
    }
    private function loadVenueData(int $bandId)
    {
        //
    }
    private function loadPhotographerData(int $bandId)
    {
        //
    }

    public function calculateTotalProfitsPromoterYtd($promoter)
    {
        if ($promoter) {
            $promoterCompany = $promoter->first();

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

    public function calculateEventsCountPromoterYtd($promoter)
    {
        if ($promoter) {
            $promoterCompany = $promoter->first();

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

    public function calculateGigsCountBandYtd($band)
    {
        if ($band) {
            $startOfYear = Carbon::now()->startOfYear();
            $endOfYear = Carbon::now()->endOfYear();

            $gigsCountBandYtd = DB::table('event_band')
                ->join('events', 'event_band.event_id', '=', 'events.id')
                ->where('event_band.band_id', $band)
                ->whereBetween('events.event_date', [$startOfYear, $endOfYear])
                ->count();

            return $gigsCountBandYtd;
        }

        return 0;
    }

    public function calculateOverallRatingBand($band)
    {
        //
    }

    public function calculateTotalProfitsBandYtd($band)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sub-nav', [
            'userType' => $this->userType,
            'overallRatingPromoter' => $this->overallRatingPromoter,
            'overallRatingBand' => $this->overallRatingBand,
            'gigsCountBandYtd' => $this->gigsCountBandYtd,
            'totalProfitsBandYtd' => $this->totalProfitsBandYtd,
            'eventsCountPromoterYtd' => $this->userType === 'promoter' ? $this->eventsCountPromoterYtd : null,
            'totalProfitsPromoterYtd' => $this->totalProfitsPromoterYtd,
        ]);
    }
}

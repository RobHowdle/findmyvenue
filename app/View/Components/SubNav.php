<?php

namespace App\View\Components;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Finance;
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

    // Photographer
    public $photographerId;
    public $jobsCountPhotographerYtd;
    public $totalProfitsPhotographerYtd;
    public $overallPhotographerRating;

    /**
     * Helper function to render rating icons
     */
    public function renderRatingIcons($overallScore)
    {
        $overallScore = 0;
        $output = '';
        $totalIcons = 5;
        $emptyIcon = asset('storage/images/system/ratings/empty.png');
        $fullIcon = asset('storage/images/system/ratings/full.png');
        $hotIcon = asset('storage/images/system/ratings/hot.png');

        // Display 5 empty icons if there is no rating
        if (is_null($overallScore) || $overallScore <= 0.1) {
            return str_repeat('<img src="' . $emptyIcon . '" alt="Empty Icon" />', $totalIcons);
        }

        $fullIcons = floor($overallScore);
        $fraction = $overallScore - $fullIcons;

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
        dd($output);
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

            case 'venue':
                $this->loadVenueData($user);
                break;

            case 'designer':
                $this->loadDesignerData($user);
                break;

            case 'photographer':
                $this->loadPhotographerData($user);
                break;

            case 'videographer':
                $this->loadVideographerData($user);
                break;
            default:
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
        $bands = $user->otherService("Band")->get();
        if ($bands->isNotEmpty()) {
            $band = $bands->first();
            $this->bandId = $band->id;
            $this->gigsCountBandYtd = $this->calculateGigsCountBandYtd($band->id);
            $this->overallRatingBand = $this->renderRatingIcons($band->overallRating);
            $this->totalProfitsBandYtd = $this->calculateTotalProfitsBandYtd($band->id);
        }
    }

    private function loadDesignerData(int $bandId)
    {
        //
    }
    private function loadVideographerData(int $bandId)
    {
        //
    }
    private function loadVenueData($user)
    {
        $venue = $user->venues()->first();
        if ($venue) {
            $this->venueId = $venue->id;
            $this->eventsCountVenueYtd = $this->calculateEventsCountPromoterYtd($venue);
            $this->totalProfitsVenueYtd = $this->calculateTotalProfitsPromoterYtd($venue);
            $this->overallRatingVenue = $this->renderRatingIcons($this->venueId);
        }
    }
    private function loadPhotographerData($user)
    {
        $photographers = $user->otherService("Photography")->get();
        if ($photographers->isNotEmpty()) {
            $photographer = $photographers->first();
            $this->photographerId = $photographer->id;
            $this->jobsCountPhotographerYtd = $this->calculateJobsCountPhotographerYtd($photographer);
            $this->totalProfitsPhotographerYtd = $this->calculateTotalProfitsPhotographerYtd($photographer);
            $this->overallPhotographerRating = $this->renderRatingIcons($this->photographerId);
        }
    }

    // Promoter Calculations
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

    // Photograher Calculations
    public function calculateJobsCountPhotographerYtd($photographer)
    {
        if ($photographer) {
            $photographerCompany = $photographer->first();

            if ($photographerCompany) {
                $startOfYear = Carbon::now()->startOfYear();
                $endOfYear = Carbon::now()->endOfYear();

                // Query the finances table for the current year's jobs
                $jobsCountYTD = DB::table('job_service')
                    ->join('jobs', 'job_service.job_id', '=', 'jobs.id')
                    ->where('serviceable_id', $photographerCompany->id)
                    ->where('serviceable_type', 'App\Models\OtherService')
                    ->whereBetween('jobs.job_start_date', [$startOfYear, $endOfYear])
                    ->count();

                return $jobsCountYTD;
            }
        }

        // Return 0 if no promoter company or no profits found
        return 0;
    }

    public function calculateTotalProfitsPhotographerYtd($photographer)
    {
        if ($photographer) {
            $photographerCompany = $photographer->first();

            if ($photographerCompany) {
                $startOfYear = Carbon::now()->startOfYear();
                $endOfYear = Carbon::now()->endOfYear();

                // Query the finances table for the current year's profits
                $totalProfitsYTD = Finance::where('serviceable_id', $photographerCompany->id)
                    ->where('serviceable_type', 'App\Models\OtherService')
                    ->whereBetween('date_to', [$startOfYear, $endOfYear])
                    ->sum('total_profit');

                return $totalProfitsYTD;
            }
        }

        // Return 0 if no promoter company or no profits found
        return 0;
    }

    // Band Calculations
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

    // Venue Calculations
    public function calculateTotalProfitsVenueYtd($venue)
    {
        if ($venue) {
            $venueService = $venue->first();

            if ($venueService) {
                $startOfYear = Carbon::now()->startOfYear();
                $endOfYear = Carbon::now()->endOfYear();

                // Query the finances table for the current year's profits
                $totalProfitsYTD = Finance::where('serviceable_id', $venueService->id)
                    ->where('serviceable_type', 'App\Models\Venue')
                    ->whereBetween('date_to', [$startOfYear, $endOfYear])
                    ->sum('total_profit');

                return $totalProfitsYTD;
            }
        }

        // Return 0 if no promoter company or no profits found
        return 0;
    }

    public function calculateEventsCountVenueYtd($venue)
    {
        if ($venue) {
            $venueService = $venue->first();

            if ($venueService) {
                $startOfYear = Carbon::now()->startOfYear();
                $endOfYear = Carbon::now()->endOfYear();

                // Query the finances table for the current year's profits
                $eventsCountYTD = DB::table('event_venue')
                    ->join('events', 'event_venue.event_id', '=', 'events.id')
                    ->where('venue_id', $venueService->id)
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
        return view('components.sub-nav', [
            'userType' => $this->userType,
            'overallRatingPromoter' => $this->overallRatingPromoter,
            'overallRatingBand' => $this->overallRatingBand,
            'gigsCountBandYtd' => $this->gigsCountBandYtd,
            'totalProfitsBandYtd' => $this->totalProfitsBandYtd,
            'eventsCountPromoterYtd' => $this->userType === 'promoter' ? $this->eventsCountPromoterYtd : null,
            'totalProfitsPromoterYtd' => $this->totalProfitsPromoterYtd,
            'totalProfitsVenueYtd' => $this->totalProfitsVenueYtd,
            'eventsCountVenueYtd' => $this->eventsCountVenueYtd,
            'overallRatingVenue' => $this->overallRatingVenue,
            'jobsCountPhotographerYtd' => $this->userType === 'photographer' ? $this->jobsCountPhotographerYtd : null,
            'totalProfitsPhotographerYtd' => $this->totalProfitsPhotographerYtd,
            'overallPhotographerRating' => $this->overallPhotographerRating,
        ]);
    }
}

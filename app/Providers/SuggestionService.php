<?php

namespace App\Providers;

use App\Models\Venue;
use App\Models\Promoter;
use App\Models\VenueReview;
use App\Models\OtherService;
use App\Models\PromoterReview;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SuggestionService extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('suggestions', function ($app, $context) {
            $currentRoute = Route::currentRouteName();
            $location = null;

            if (isset($context['venue'])) {
                $location = $context['venue']->postal_town;
            } elseif (isset($context['promoter'])) {
                $location = $context['promoter']->postal_town;
            }

            if (!$location) {
                return [
                    'promoter' => null,
                    'photograper' => null,
                    'videographer' => null,
                    'artist' => null,
                    'designer' => null
                ];
            }

            // Venue Block
            $venueWithHighestRating = null;
            if ($currentRoute !== 'venue') {
                $venueWithHighestRating = Venue::where('postal_town', $location)
                    ->get()
                    ->map(function ($venue) {
                        $venue->overall_score = VenueReview::calculateOverallScore($venue->id);
                        return $venue;
                    })
                    ->sortByDesc('overall_score')
                    ->first();
            }

            // Promoter Block
            $promoterWithHighestRating = null;
            if ($currentRoute !== 'promoter') {
                $promoterWithHighestRating = Promoter::where('postal_town', $location)
                    ->get()
                    ->map(function ($promoter) {
                        $promoter->overall_score = PromoterReview::calculateOverallScore($promoter->id);
                        return $promoter;
                    })
                    ->sortByDesc('overall_score')
                    ->first();
            }

            // Photographer Block
            $photographerWithHighestRating = null;
            if ($currentRoute !== 'singleService') {
                $photographerWithHighestRating = OtherService::getHighestRatedService('Photography', $location);
            }

            // Videographer Block
            $videographerWithHighestRating = null;
            if ($currentRoute !== 'singleService') {
                $videographerWithHighestRating = OtherService::getHighestRatedService('Videography', $location);
            }

            // Band Block
            $bandWithHighestRating = null;
            if ($currentRoute !== 'singleService') {
                $bandWithHighestRating = OtherService::getHighestRatedService('Artist', $location);
            }

            // Designer Block
            $designerWithHighestRating = null;
            if ($currentRoute !== 'singleService') {
                $designerWithHighestRating = OtherService::getHighestRatedService('Designer', $location);
            }

            return [
                'venue' => $venueWithHighestRating,
                'promoter' => $promoterWithHighestRating,
                'photographer' => $photographerWithHighestRating,
                'videographer' => $videographerWithHighestRating,
                'artist' => $bandWithHighestRating,
                'designer' => $designerWithHighestRating
            ];
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

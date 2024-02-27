<?php

namespace App\Http\Controllers;

use App\Models\Promoter;
use Illuminate\Http\Request;
use App\Models\PromoterReview;
use App\DataTables\PromotersDataTable;

class PromoterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PromotersDataTable $dataTable)
    {
        $promoters = Promoter::whereNull('deleted_at')
        ->with('venues')
        ->get();
        
        // Process each promoter
        foreach ($promoters as $promoter) {
            // Split the field containing multiple URLs into an array
            $urls = explode(',', $promoter->contact_link); // Assuming the field name is 'contact_links'
            $platforms = [];

            // // Check each URL against the platforms
            foreach ($urls as $url) {
                // Initialize the platform as unknown
                $matchedPlatform = 'Unknown';

                // Check if the URL contains platform names
                $platformsToCheck = ['facebook', 'twitter', 'instagram'];
                foreach ($platformsToCheck as $platform) {
                    if (stripos($url, $platform) !== false) {
                        $matchedPlatform = $platform;
                        break; // Stop checking once a platform is found
                    }
                }

                // Store the platform information for each URL
                $platforms[] = [
                    'url' => $url,
                    'platform' => $matchedPlatform
                ];
            }

            // Add the processed data to the venue
            $promoter->platforms = $platforms;
        }
        return view('promoters', compact('promoters'));
        // return $dataTable->render('venues');
    }

        /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $promoter = Promoter::where('id', $id)->first();

            // Split the field containing multiple URLs into an array
            $urls = explode(',', $promoter->contact_link); // Assuming the field name is 'contact_links'
            $platforms = [];

            // // Check each URL against the platforms
            foreach ($urls as $url) {
                // Initialize the platform as unknown
                $matchedPlatform = 'Unknown';

                // Check if the URL contains platform names
                $platformsToCheck = ['facebook', 'twitter', 'instagram'];
                foreach ($platformsToCheck as $platform) {
                    if (stripos($url, $platform) !== false) {
                        $matchedPlatform = $platform;
                        break; // Stop checking once a platform is found
                    }
                }

                // Store the platform information for each URL
                $platforms[] = [
                    'url' => $url,
                    'platform' => $matchedPlatform
                ];
            }

            // Add the processed data to the venue
            $promoter->platforms = $platforms;
            $promoter->recentReviews = PromoterReview::getRecentReviewsForPromoter($id);

        return view('promoter', compact('promoter'));
    }
}
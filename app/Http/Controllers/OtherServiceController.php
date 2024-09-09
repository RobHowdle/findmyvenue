<?php

namespace App\Http\Controllers;

use App\Models\OtherService;
use Illuminate\Http\Request;
use App\Models\OtherServiceList;
use Illuminate\Support\Facades\DB;
use App\Models\OtherServicesReview;

class OtherServiceController extends Controller
{
    /**
     * Helper function to render rating icons
     */
    private function renderRatingIcons($overallScore)
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

            // Handle the fractional icon
            if ($fraction > 0) {
                $output .= '<div class="partially-filled-icon" style="width: ' . ($fraction * 48) . 'px; overflow: hidden; display:inline-block;">';
                $output .= '<img src="' . $fullIcon . '" alt="Partial Full Icon" />';
                $output .= '</div>';
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('search_query');

        // Retrieve all services with their counts
        $otherServices = OtherService::with('otherServiceList')
            ->select('other_service_id', DB::raw('count(*) as total'))
            ->whereNull('deleted_at')
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('postal_town', 'like', "%$searchQuery%");
            })
            ->groupBy('other_service_id')
            ->paginate(10);

        // Create an array to hold counts for each service
        $serviceCounts = [];
        foreach ($otherServices as $service) {
            $serviceCounts[$service->other_service_id] = $service->total;
        }



        return view('other', compact('otherServices', 'serviceCounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function showGroup(Request $request, $serviceName)
    {
        $otherServiceIds = OtherServiceList::where('service_name', $serviceName)->pluck('id');
        $singleServices = OtherService::with('otherServiceList')
            ->whereIn('other_service_id', $otherServiceIds)
            ->get();

        // Fetch genres for initial page load
        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];

        if ($request->ajax()) {
            return response()->json([
                'otherServices' => $otherServices,
                'view' => view('partials.otherServices-list', compact('otherServices', 'genres'))->render()
            ]);
        }

        // Process contact links and map them to platforms
        foreach ($singleServices as $singleOtherService) {
            $urls = explode(',', $singleOtherService->contact_link);
            $platforms = [];

            foreach ($urls as $url) {
                $matchedPlatform = 'Unknown';
                $platformsToCheck = ['facebook', 'twitter', 'instagram', 'snapchat', 'tiktok', 'youtube'];
                foreach ($platformsToCheck as $platform) {
                    if (stripos($url, $platform) !== false) {
                        $matchedPlatform = $platform;
                        break;
                    }
                }
                $platforms[] = ['url' => $url, 'platform' => $matchedPlatform];
            }
        }

        $singleServices->platforms = $platforms;
        $overallReviews = []; // Array to store overall reviews for each venue

        foreach ($singleServices as $service) {
            $overallScore = OtherServicesReview::calculateOverallScore($service->id);
            $overallReviews[$service->id] = $this->renderRatingIcons($overallScore);
        }

        $firstService = $singleServices->first();
        $serviceName = $firstService->services;

        return view('single-service-group', compact('singleServices', 'genres', 'overallReviews', 'serviceName'));
    }

    /**
     * Display the specified resource.
     */
    public function show($serviceName, $serviceId)
    {
        $singleService = OtherService::where('id', $serviceId)->with('otherServiceList')->first();
        $singleServiceTitle =
            OtherService::where('id', $serviceId)->with('otherServiceList')->first();

        // Split the field containing multiple URLs into an array
        if ($singleService->contact_link) {
            $urls = explode(',', $singleService->contact_link);
            $platforms = [];
        }

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

        $singleService->platforms = $platforms;


        return view('single-service', compact('singleService', 'singleServiceTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OtherService $otherService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OtherService $otherService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OtherService $otherService)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BandReviews;
use App\Models\PhotographerReviews;
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

    public function showGroup(Request $request, $serviceName)
    {
        $otherServiceIds = OtherServiceList::where('service_name', $serviceName)->pluck('id');
        $searchQuery = $request->input('search_query');

        $singleServices = OtherService::with('otherServiceList')
            ->whereIn('other_service_id', $otherServiceIds)
            ->when($searchQuery, function ($query, $searchQuery) {
                // Apply search query condition only if search_query is present
                return $query->whereHas('otherServiceList', function ($query) use ($searchQuery) {
                    $query->where('postal_town', 'like', "%$searchQuery%");
                });
            })
            ->paginate(10);

        // Fetch genres for initial page load
        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];

        if ($request->ajax()) {
            return response()->json([
                'singleServices' => $singleServices,
                'view' => view('partials.otherServices-list', compact('singleServices', 'genres'))->render()
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
                $platforms[] = [
                    'url' => $url,
                    'platform' => $matchedPlatform
                ];
            }

            $singleServices->platforms = $platforms;
        }

        $overallReviews = []; // Array to store overall reviews for each venue

        foreach ($singleServices as $service) {
            $overallScore = OtherServicesReview::calculateOverallScore($service->id);
            $overallReviews[$service->id] = $this->renderRatingIcons($overallScore);
        }

        $firstService = $singleServices->first();
        $serviceName = $firstService->services;

        return view('single-service-group', compact('singleServices', 'genres', 'overallReviews', 'serviceName'));
    }

    public function filterCheckboxesSearch(Request $request)
    {
        $query = OtherService::query();

        // Search Results
        $searchQuery = $request->input('search_query');
        if ($searchQuery) {
            $query->where(function ($query) use ($searchQuery) {
                $query->where('postal_town', 'LIKE', "%$searchQuery%")
                    ->orWhere('name', 'LIKE', "%$searchQuery%");
            });
        }

        // Band Type Filter
        if ($request->has('band_type')) {
            $bandType = $request->input('band_type');
            if (!empty($bandType)) {
                $bandType = array_map('trim', $bandType);
                $query->where(function ($query) use ($bandType) {
                    foreach ($bandType as $type) {
                        $query->orWhereRaw('JSON_CONTAINS(band_type, ?)', [json_encode([$type])]);
                    }
                });
            }
        }

        // Genre Filter
        if ($request->has('genres')) {
            $genres = $request->input('genres');
            if (!empty($genres)) {
                $genres = array_map('trim', $genres); // Ensure no extra spaces
                $query->where(function ($query) use ($genres) {
                    foreach ($genres as $genre) {
                        // Ensure the genre is properly formatted
                        $query->orWhereRaw('JSON_CONTAINS(genre, ?)', [json_encode([$genre])]);
                    }
                });
            }
        }

        // Get the venues with pagination
        $otherServices = $query->paginate(10);

        // Process each venue
        $transformedData = $otherServices->getCollection()->map(function ($other) {
            // Split the field containing multiple URLs into an array
            $urls = explode(',', $other->contact_link);
            $platforms = [];

            // Check each URL against the platforms
            foreach ($urls as $url) {
                // Initialize the platform as unknown
                $matchedPlatform = 'Unknown';

                // Check if the URL contains platform names
                $platformsToCheck = ['facebook', 'twitter', 'instagram', 'snapchat', 'tiktok', 'youtube'];
                foreach ($platformsToCheck as $platform) {
                    if (stripos($url, $platform) !== false) {
                        $matchedPlatform = $platform;
                        break;
                    }
                }

                // Store the platform information for each URL
                $platforms[] = [
                    'url' => $url,
                    'platform' => $matchedPlatform
                ];
            }

            // Use the static method to calculate the overall score
            $overallScore = \App\Models\OtherServicesReview::calculateOverallScore($other->id);

            return [
                'id' => $other->id,
                'name' => $other->name,
                'postal_town' => $other->postal_town,
                'contact_number' => $other->contact_number,
                'contact_email' => $other->contact_email,
                'platforms' => $platforms,
                'average_rating' => $overallScore,
            ];
        });

        // Return the transformed data with pagination info
        return response()->json([
            'otherServices' => $transformedData,
            'pagination' => [
                'current_page' => $otherServices->currentPage(),
                'last_page' => $otherServices->lastPage(),
                'total' => $otherServices->total(),
                'per_page' => $otherServices->perPage(),
            ]
        ]);
    }
}

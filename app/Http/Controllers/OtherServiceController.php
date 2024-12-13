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

        $customKeys = [
            'Photography' => 'photography',
            'Designer' => 'designer',
            'Videography' => 'videography',
            'Band' => 'band',
        ];

        $normalizedServiceName = ucfirst(strtolower(($serviceName)));
        $key = $customKeys[$normalizedServiceName] ?? $normalizedServiceName;
        $suggestions = app('suggestions', [$key => $serviceName]);

        // Default empty suggestions array
        $promoterWithHighestRating = $photographerWithHighestRating = $videographerWithHighestRating = $bandWithHighestRating = $designerWithHighestRating = null;

        // Check if the relevant suggestions exist
        if (isset($suggestions['promoter'])) {
            $promoterWithHighestRating = $suggestions['promoter'];
        }
        if (isset($suggestions['photographer'])) {
            $photographerWithHighestRating = $suggestions['photographer'];
        }
        if (isset($suggestions['videographer'])) {
            $videographerWithHighestRating = $suggestions['videographer'];
        }
        if (isset($suggestions['band'])) {
            $bandWithHighestRating = $suggestions['band'];
        }
        if (isset($suggestions['designer'])) {
            $designerWithHighestRating = $suggestions['designer'];
        }

        // If contact_link is a JSON string, decode it into an array.
        if ($singleService->contact_link) {
            // Decode the JSON if it's in JSON format, otherwise use it as a plain string
            $urls = is_array($singleService->contact_link)
                ? $singleService->contact_link
                : json_decode($singleService->contact_link, true);

            // If the JSON decoding results in null (i.e., invalid JSON), use explode as a fallback.
            if ($urls === null) {
                $urls = explode(',', $singleService->contact_link);
            }

            $platforms = []; // Initialize the array to store platforms
        }

        // Now process each URL to associate with its platform
        foreach ($urls as $url) {
            // Initialize the platform as unknown
            $matchedPlatform = 'Unknown';

            // Check if the URL contains platform names
            $platformsToCheck = ['facebook', 'twitter', 'instagram', 'snapchat', 'tiktok', 'youtube'];
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

        // Store the platform information as an array (no need for json_decode anymore)
        $singleService->platforms = $platforms;


        $recentReviews = OtherServicesReview::getRecentReviewsForOtherService($serviceId);
        $singleService->recentReviews = $recentReviews->isNotEmpty() ? $recentReviews : null;

        $overallScore = OtherServicesReview::calculateOverallScore($serviceId);
        $overallReviews[$serviceId] = $this->renderRatingIcons($overallScore);

        // Get Review Scores
        $averageCommunicationRating = OtherServicesReview::calculateAverageScore($serviceId, 'communication_rating');
        $averageRopRating = OtherServicesReview::calculateAverageScore($serviceId, 'rop_rating');
        $averagePromotionRating = OtherServicesReview::calculateAverageScore($serviceId, 'promotion_rating');
        $averageQualityRating = OtherServicesReview::calculateAverageScore($serviceId, 'quality_rating');
        $reviewCount = OtherServicesReview::getReviewCount($serviceId);

        $members = null;
        $streamUrls = [];
        $recentReviews = null;
        $bandAverageCommunicationRating = null;
        $bandAverageMusicRating = null;
        $bandAveragePromotionRating = null;
        $bandAverageGigQualityRating = null;
        $bandReviewCount = 0;

        if ($singleService->services == "Band") {
            $members = json_decode($singleService->members);
            $streamUrls = json_decode($singleService->stream_urls) ?? [];
            $recentReviews = BandReviews::getRecentReviewsForBand($serviceId);
            $singleService->recentReviews = $recentReviews->isNotEmpty() ? $recentReviews : null;
            $overallScore = BandReviews::calculateOverallScore($serviceId);
            $overallReviews[$serviceId] = $this->renderRatingIcons($overallScore);
            $bandAverageCommunicationRating = BandReviews::calculateAverageScore($serviceId, 'communication_rating');
            $bandAverageMusicRating = BandReviews::calculateAverageScore($serviceId, 'music_rating');
            $bandAveragePromotionRating = BandReviews::calculateAverageScore($serviceId, 'promotion_rating');
            $bandAverageGigQualityRating = BandReviews::calculateAverageScore($serviceId, 'gig_quality_rating');
            $bandReviewCount = BandReviews::getReviewCount($serviceId);
        } elseif ($singleService->services == "Photographer") {
            $recentReviews = PhotographerReviews::getRecentReviewsForBand($serviceId);
            $singleService->recentReviews = $recentReviews->isNotEmpty() ? $recentReviews : null;
            $overallScore = PhotographerReviews::calculateOverallScore($serviceId);
            $overallReviews[$serviceId] = $this->renderRatingIcons($overallScore);
            $photographerAverageCommunicationRating = PhotographerReviews::calculateAverageScore($serviceId, 'communication_rating');
            $photographerAverageReliabilityRating = PhotographerReviews::calculateAverageScore($serviceId, 'reliability_rating');
            $photographerAveragePricingRating = PhotographerReviews::calculateAverageScore($serviceId, 'pricing_rating');
            $photographerAverageQualityRating = PhotographerReviews::calculateAverageScore($serviceId, 'quality_rating');
            $photographerReviewCount = PhotographerReviews::getReviewCount($serviceId);
        }

        $genres = json_decode($singleService->genre);
        $services = json_decode($singleService->packages);
        $bandType = json_decode($singleService->band_type);

        return view('single-service', compact(
            'singleService',
            'singleServiceTitle',
            'genres',
            'services',
            'overallScore',
            'overallReviews',
            'averageCommunicationRating',
            'averageRopRating',
            'averagePromotionRating',
            'averageQualityRating',
            'reviewCount',
            'members',
            'streamUrls',
            'bandType',
            'genres',
            'bandAverageCommunicationRating',
            'bandAverageMusicRating',
            'bandAveragePromotionRating',
            'bandAverageGigQualityRating',
            'bandReviewCount',
        ))
            ->with([
                'promoterWithHighestRating' => $promoterWithHighestRating,
                'photographerWithHighestRating' => $photographerWithHighestRating,
                'videographerWithHighestRating' => $videographerWithHighestRating,
                'bandWithHighestRating' => $bandWithHighestRating,
                'designerWithHighestRating' => $designerWithHighestRating,
                'renderRatingIcons' => [$this, 'renderRatingIcons']
            ]);
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

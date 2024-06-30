<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Promoter;
use App\Models\VenueReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;


class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $searchQuery = $request->input('search_query');

        $venues = Venue::whereNull('deleted_at')
            ->with('extraInfo', 'promoters')
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('postal_town', 'like', "%$searchQuery%");
            })
            ->paginate(10);

        // Fetch genres for initial page load
        $genreList = file_get_contents(storage_path('app/public/text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];

        if ($request->ajax()) {
            return response()->json([
                'venues' => $venues,
                'view' => view('partials.venue-list', compact('venues', 'genres'))->render()
            ]);
        }

        // Process each promoter
        foreach ($venues as $venue) {
            // Split the field containing multiple URLs into an array
            $urls = explode(',', $venue->contact_link);
            $platforms = [];

            // // Check each URL against the platforms
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

            // Add the processed data to the venue
            $venue->platforms = $platforms;
        }

        // Return the initial view
        return view('venues', compact('venues', 'genres'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $venue = Venue::where('id', '=', $id)->with('extraInfo')->first();
        $venueId = $venue->id;

        // Promoter Suggestion
        $existingPromoters = $venue->promoters()->get();
        $location = $venue->postal_town;
        $promotersByLocation = Promoter::where('postal_town', $location)->take(min(3, Promoter::where('postal_town', $location)->count()))->get();

        // Split the field containing multiple URLs into an array
        if ($venue->contact_link) {
            $urls = explode(',', $venue->contact_link);
            $platforms = [];
        }

        // Check each URL against the platforms
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

        // Add the processed data to the venue
        $venue->platforms = $platforms;
        $venue->recentReviews = VenueReview::getRecentReviewsForPromoter($id);

        // Get Review Scores
        $overallReview = VenueReview::calculateOverallScore($id);
        $averageCommunicationRating = VenueReview::calculateAverageScore($id, 'communication_rating');
        $averageRopRating = VenueReview::calculateAverageScore($id, 'rop_rating');
        $averagePromotionRating = VenueReview::calculateAverageScore($id, 'promotion_rating');
        $averageQualityRating = VenueReview::calculateAverageScore($id, 'quality_rating');
        $reviewCount = VenueReview::getReviewCount($id);



        $genres = json_decode($venue->genre);

        return view('venue', compact('promotersByLocation', 'existingPromoters', 'venue', 'genres', 'overallReview', 'averageCommunicationRating', 'averageRopRating', 'averagePromotionRating', 'averageQualityRating', 'reviewCount', 'venueId'));
    }

    public function locations()
    {
        $locations = Venue::whereNull('deleted_at')
            ->select('postal_town', DB::raw('COUNT(*) as count'))
            ->groupBy('postal_town')
            ->get();

        return view('locations', compact('locations'));
    }

    public function filterByCoordinates(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $searchQuery = $request->input('search_query');

        // Filter venues by latitude and longitude
        $venuesByCoordinatesQuery = Venue::where('latitude', $latitude)
            ->where('longitude', $longitude);

        // Initialize an empty query for venues by address
        $venuesByAddressQuery = Venue::query();

        // Check if the search query contains a comma (indicating both town and specific address)
        if (strpos($searchQuery, ',') !== false) {
            // If the search query contains a comma, split it into town and address
            list($town, $address) = explode(',', $searchQuery);

            // Perform search for venues matching the town or the address
            $venuesByAddressQuery->where(function ($query) use ($town, $address) {
                $query->where('postal_town', 'LIKE', "%$address%")
                    ->orWhere('postal_town', 'LIKE', "%$town%");
            });
        } else {
            // If the search query does not contain a comma, search for venues matching the town only
            $venuesByAddressQuery->where('postal_town', 'LIKE', "%$searchQuery%");
        }

        // Get the paginated results
        $venuesByCoordinates = $venuesByCoordinatesQuery->paginate(10, ['*'], 'coordinates_page');
        $venuesByAddress = $venuesByAddressQuery->paginate(10, ['*'], 'address_page');

        // Merge the paginated results, ensure to avoid duplicates
        $mergedVenues = $venuesByCoordinates->merge($venuesByAddress)->unique('id');

        // Paginate the merged results manually if needed (assuming 10 per page)
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageResults = $mergedVenues->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedResults = new LengthAwarePaginator($currentPageResults, $mergedVenues->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

        // Process contact links for each venue to identify platforms
        foreach ($paginatedResults as $venue) {
            if ($venue->contact_link) {
                $urls = explode(',', $venue->contact_link);
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
                $venue->platforms = $platforms;
            }
        }

        // Fetch genres for initial page load
        $genreList = file_get_contents(storage_path('app/public/text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];

        return view('venues', [
            'venues' => $paginatedResults,
            'genres' => $genres,
            'searchQuery' => $searchQuery
        ]);
    }

    public function filterCheckboxesSearch(Request $request)
    {
        $query = Venue::query();

        // Search Results
        $searchQuery = $request->input('search_query');
        if ($searchQuery) {
            $query->where('postal_town', 'LIKE', "%$searchQuery%");
        }

        // Band Type Filter
        if ($request->has('band_type')) {
            $bandType = $request->input('band_type');
            if (!empty($bandType)) {
                $query->where(function ($query) use ($bandType) {
                    foreach ($bandType as $type) {
                        $query->orWhereJsonContains('band_type', $type);
                    }
                });
            }
        }

        // Genre Filter
        if ($request->has('genres')) {
            $genres = $request->input('genres');
            if (!empty($genres)) {
                $query->where(function ($query) use ($genres) {
                    foreach ($genres as $genre) {
                        $query->orWhereJsonContains('genre', $genre);
                    }
                });
            }
        }

        $venues = $query->paginate(10);

        $transformedData = [
            'venues' => $venues->items(),
            'pagination' => [
                'current_page' => $venues->currentPage(),
                'last_page' => $venues->lastPage(),
            ]
        ];

        return response()->json($transformedData);
    }

    public function submitVenueReview(Request $request, Venue $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'communication-rating' => 'required',
                'rop-rating' => 'required',
                'promotion-rating' => 'required',
                'quality-rating' => 'required',
                'review_author' => 'required',
                'review_message' => 'required',
                'reviewer_ip' => 'required'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            VenueReview::create([
                'venue_id' => $id['id'],
                'communication_rating' => $request->input('communication-rating'),
                'rop_rating' => $request->input('rop-rating'),
                'promotion_rating' => $request->input('promotion-rating'),
                'quality_rating' => $request->input('quality-rating'),
                'author' => $request->input('review_author'),
                'review' => $request->input('review_message'),
                'reviewer_ip' => $request->input('reviewer_ip'),
            ]);

            return back()->with('success', 'Review submitted successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error submitting review: ' . $e->getMessage());

            // Optionally, you can return an error response or redirect to an error page
            return back()->with('error', 'An error occurred while submitting the review. Please try again later.')->withInput();
        }
    }

    public function suggestPromoters(Request $request)
    {
        $venueId = $request->input('venue_id');
        $venue = Venue::findOrFail($venueId);

        $location = $venue->postal_town;
        $promotersByLocation = Promoter::where('location', $location)->get();

        dd($promotersByLocation);

        return view('components.promoter-suggestions', [
            'venueId' => $venueId,
            'promotersByLocation' => $promotersByLocation
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\VenueReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


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

        // Return the initial view
        return view('venues', compact('venues', 'genres'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $venue = Venue::where('id', $id)->first();
        $venueId = $venue->id;
        // Split the field containing multiple URLs into an array
        if ($venue->contact_link) {
            $urls = explode(',', $venue->contact_link);
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

        return view('venue', compact('venue', 'genres', 'overallReview', 'averageCommunicationRating', 'averageRopRating', 'averagePromotionRating', 'averageQualityRating', 'reviewCount', 'venueId'));
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

        // Filter venues by latitude and longitude
        $venuesByCoordinates = Venue::where('latitude', $latitude)
            ->where('longitude', $longitude)
            ->get();

        // Get the search query from the request
        $searchQuery = $request->input('search_query');

        // Check if the search query contains a comma (indicating both town and specific address)
        if (strpos($searchQuery, ',') !== false) {
            // If the search query contains a comma, split it into town and address
            list($town, $address) = explode(',', $searchQuery);

            // Perform search for venues matching the town or the address
            $venuesByAddress = Venue::where(function ($query) use ($town, $address) {
                $query->where('postal_town', 'LIKE', "%$address%")
                    ->orWhere('postal_town', 'LIKE', "%$town%");
            })->get();
        } else {
            // If the search query does not contain a comma, search for venues matching the town only
            $venuesByAddress = Venue::where('postal_town', 'LIKE', "%$searchQuery%")
                ->get();
        }

        // Merge the search results and remove duplicates
        $venues = $venuesByCoordinates->merge($venuesByAddress)->unique();

        // Fetch genres for initial page load
        $genreList = file_get_contents(storage_path('app/public/text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];

        return view('venues', compact('venues', 'genres'));
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
                'review_message' => 'required'
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
            ]);

            return back()->with('success', 'Review submitted successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error submitting review: ' . $e->getMessage());

            // Optionally, you can return an error response or redirect to an error page
            return back()->with('error', 'An error occurred while submitting the review. Please try again later.')->withInput();
        }
    }
}

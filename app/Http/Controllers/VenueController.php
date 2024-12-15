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

        $venues = Venue::whereNull('deleted_at')
            ->with('extraInfo', 'promoters')
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('postal_town', 'like', "%$searchQuery%");
            })
            ->paginate(10);

        // Fetch genres for initial page load
        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];

        if ($request->ajax()) {
            return response()->json([
                'venues' => $venues,
                'view' => view('partials.venue-list', compact('venues', 'genres'))->render()
            ]);
        }

        // Process each venue
        foreach ($venues as $venue) {
            // Split the field containing multiple URLs into an array
            $urls = explode(',', $venue->contact_link);
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

                // Store the platform information for each URL
                $platforms[] = [
                    'url' => $url,
                    'platform' => $matchedPlatform
                ];
            }

            // Add the processed data to the venue
            $venue->platforms = $platforms;
        }

        $overallReviews = []; // Array to store overall reviews for each venue

        foreach ($venues as $venue) {
            $overallScore = VenueReview::calculateOverallScore($venue->id);
            $overallReviews[$venue->id] = $this->renderRatingIcons($overallScore);
        }

        $venuePromoterCount = count($venue['promoters']);
        return view('venues', compact('venues', 'genres', 'overallReviews', 'venuePromoterCount'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $venue = Venue::where('id', '=', $id)->with('extraInfo')->first();
        $venueId = $venue->id;
        $existingPromoters = $venue->promoters;

        $suggestions = app('suggestions', ['venue' => $venue]);


        // Split the field containing multiple URLs into an array
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

        $recentReviews = VenueReview::getRecentReviewsForVenue($id);
        $venue->recentReviews = $recentReviews->isNotEmpty() ? $recentReviews : null;

        $overallScore = VenueReview::calculateOverallScore($id);
        $overallReviews[$id] = $this->renderRatingIcons($overallScore);

        // Get Review Scores
        $averageCommunicationRating = VenueReview::calculateAverageScore($id, 'communication_rating');
        $averageRopRating = VenueReview::calculateAverageScore($id, 'rop_rating');
        $averagePromotionRating = VenueReview::calculateAverageScore($id, 'promotion_rating');
        $averageQualityRating = VenueReview::calculateAverageScore($id, 'quality_rating');
        $reviewCount = VenueReview::getReviewCount($id);

        $genres = json_decode($venue->genre);

        return view('venue', compact(
            'venue',
            'venueId',
            'genres',
            'overallScore',
            'overallReviews',
            'averageCommunicationRating',
            'averageRopRating',
            'averagePromotionRating',
            'averageQualityRating',
            'reviewCount'
        ))
            ->with([
                'promoterWithHighestRating' => $suggestions['promoter'],
                'photographerWithHighestRating' => $suggestions['photographer'],
                'videographerWithHighestRating' => $suggestions['videographer'],
                'bandWithHighestRating' => $suggestions['artist'],
                'designerWithHighestRating' => $suggestions['designer'],
                'existingPromoters' => $existingPromoters,
                'renderRatingIcons' => [$this, 'renderRatingIcons']
            ]);
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
        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];

        $overallReviews = []; // Array to store overall reviews for each venue

        foreach ($paginatedResults as $venue) {
            $overallScore = VenueReview::calculateOverallScore($venue->id);
            $overallReviews[$venue->id] = $this->renderRatingIcons($overallScore);
        }

        return view('venues', [
            'venues' => $paginatedResults,
            'genres' => $genres,
            'searchQuery' => $searchQuery,
            'overallReviews' => $overallReviews
        ]);
    }

    public function filterCheckboxesSearch(Request $request)
    {
        $query = Venue::query();

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
        $venues = $query->with('promoters') // Include promoters relationship
            ->paginate(10);

        // Process each venue
        $transformedData = $venues->getCollection()->map(function ($venue) {
            // Split the field containing multiple URLs into an array
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
            $overallScore = \App\Models\VenueReview::calculateOverallScore($venue->id);

            return [
                'id' => $venue->id,
                'name' => $venue->name,
                'postal_town' => $venue->postal_town,
                'contact_number' => $venue->contact_number,
                'contact_email' => $venue->contact_email,
                'platforms' => $platforms,
                'promoters' => $venue->promoters, // Include promoters
                'average_rating' => $overallScore,
            ];
        });

        // Return the transformed data with pagination info
        return response()->json([
            'venues' => $transformedData,
            'pagination' => [
                'current_page' => $venues->currentPage(),
                'last_page' => $venues->lastPage(),
                'total' => $venues->total(),
                'per_page' => $venues->perPage(),
            ]
        ]);
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

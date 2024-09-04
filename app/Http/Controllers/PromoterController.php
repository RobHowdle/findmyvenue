<?php

namespace App\Http\Controllers;

use App\Models\Promoter;
use Illuminate\Http\Request;
use App\Models\PromoterReview;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PromoterController extends Controller
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

        $promoters = Promoter::whereNull('deleted_at')
            ->with('venues')
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
                'promoters' => $promoters,
                'view' => view('partials.promoter-list', compact('promoters', 'genres'))->render()
            ]);
        }

        // Process each promoter
        foreach ($promoters as $promoter) {
            // Split the field containing multiple URLs into an array
            $urls = explode(',', $promoter->contact_link);
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

        $overallReviews = []; // Array to store overall reviews for each venue

        foreach ($promoters as $promoter) {
            $overallScore = PromoterReview::calculateOverallScore($promoter->id);
            $overallReviews[$promoter->id] = $this->renderRatingIcons($overallScore);
        }
        return view('promoters', compact('promoters', 'genres', 'overallReviews'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $promoter = Promoter::where('id', $id)->first();
        // Split the field containing multiple URLs into an array
        $urls = explode(',', $promoter->contact_link);
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

        // Get Review Scores
        $overallReview = PromoterReview::calculateOverallScore($id);
        $averageCommunicationRating = PromoterReview::calculateAverageScore($id, 'communication_rating');
        $averageRopRating = PromoterReview::calculateAverageScore($id, 'rop_rating');
        $averagePromotionRating = PromoterReview::calculateAverageScore($id, 'promotion_rating');
        $averageQualityRating = PromoterReview::calculateAverageScore($id, 'quality_rating');
        $reviewCount = PromoterReview::getReviewCount($id);

        return view('promoter', compact('promoter', 'overallReview', 'averageCommunicationRating', 'averageRopRating', 'averagePromotionRating', 'averageQualityRating', 'reviewCount'));
    }

    public function submitPromoterReview(Request $request, Promoter $id)
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

            PromoterReview::create([
                'promoter_id' => $id['id'],
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

    public function filterCheckboxesSearch(Request $request)
    {
        $query = Promoter::query();

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

        $promoters = $query->paginate(10);

        $transformedData = [
            'promoters' => $promoters->items(),
            'pagination' => [
                'current_page' => $promoters->currentPage(),
                'last_page' => $promoters->lastPage(),
            ]
        ];

        return response()->json($transformedData);
    }
}
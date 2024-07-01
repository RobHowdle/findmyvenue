<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venue;
use App\Models\Promoter;
use App\Models\UserService;
use App\Models\VenueReview;
use App\Models\OtherService;
use Illuminate\Http\Request;
use App\Models\PromoterReview;

use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $pendingPromoterReviews = PromoterReview::with('promoter')->where('review_approved', '0')->whereNull('deleted_at')->get();
        $pendingVenueReviews = VenueReview::with('venue')->where('review_approved', '0')->whereNull('deleted_at')->get();
        $venues = Venue::whereNull('deleted_at')->get();
        $promoters = Promoter::whereNull('deleted_at')->get();
        $otherServices = OtherService::whereNull('deleted_at')->get();
        return view('dashboard', compact('users', 'pendingPromoterReviews', 'pendingVenueReviews', 'venues', 'promoters', 'otherServices'));
    }

    public function editUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        dd($user);
    }

    public function approveDisplayPromoterReview($reviewId)
    {
        $review = PromoterReview::findOrFail($reviewId);

        $review->update([
            'review_approved' => 1,
            'display' => 1
        ]);

        return redirect()->route('dashboard')->with('success', 'Review approved and set to display.');
    }

    public function approveDisplayVenueReview($reviewId)
    {
        $review = VenueReview::findOrFail($reviewId);

        $review->update([
            'review_approved' => 1,
            'display' => 1
        ]);

        return redirect()->route('dashboard')->with('success', 'Review approved and set to display.');
    }

    public function userServiceLink(Request $request)
    {
        try {
            // Retrieve the user
            $user = User::findOrFail($request->input('user_select'));
            $typeSelect = $request->input('type-select');
            $serviceId = $request->input('service_select_id');
            // Initialize the variables as null
            $venueId = null;
            $promoterId = null;
            $otherServiceId = null;

            // Determine which type was selected and set the appropriate ID
            if ($typeSelect == 'venues') {
                $venueId = $serviceId;
            } elseif ($typeSelect == 'promoters') {
                $promoterId = $serviceId;
            } elseif ($typeSelect == 'other_service') {
                $otherServiceId = $serviceId;
            }

            // Create the UserService record based on the selected type
            if ($venueId) {
                $userService = UserService::create([
                    'user_id' => $user->id,
                    'venues_id' => $venueId
                ]);
            } elseif ($promoterId) {
                $userService = UserService::create([
                    'user_id' => $user->id,
                    'promoters_id' => $promoterId
                ]);
            } elseif ($otherServiceId) {
                $userService = UserService::create([
                    'user_id' => $user->id,
                    'other_service_id' => $otherServiceId
                ]);
            } else {
                dd('This has fucked up');
            }

            return redirect()->route('dashboard')->with('success', 'User successfully linked');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error linking user: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'An error occurred while linking the user');
        }
    }
}

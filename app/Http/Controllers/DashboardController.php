<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VenueReview;
use Illuminate\Http\Request;
use App\Models\PromoterReview;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $pendingPromoterReviews = PromoterReview::with('promoter')->where('review_approved', '0')->whereNull('deleted_at')->get();
        $pendingVenueReviews = VenueReview::with('venue')->where('review_approved', '0')->whereNull('deleted_at')->get();
        return view('dashboard', compact('users', 'pendingPromoterReviews', 'pendingVenueReviews'));
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
}

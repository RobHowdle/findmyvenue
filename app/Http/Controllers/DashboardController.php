<?php

namespace App\Http\Controllers;

use App\Models\PromoterReview;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        $pendingReviews = PromoterReview::with('promoter')->where('review_approved', '0')->whereNull('deleted_at')->get();
        return view('dashboard', compact('users', 'pendingReviews'));
    }

    public function editUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        dd($user);
    }

    public function approveDisplayReview($reviewId)
    {
        $review = PromoterReview::findOrFail($reviewId);

        $review->update([
            'review_approved' => 1,
            'display' => 1
        ]);

        return redirect()->route('dashboard')->with('success', 'Review approved and set to display.');
    }
}

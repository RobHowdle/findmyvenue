<?php

namespace App\Http\Controllers;

use App\Models\OtherServicesReview;
use App\Models\Promoter;
use Illuminate\Http\Request;
use App\Models\PromoterReview;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    public function getPromoterReviews($dashboardType, $filter = 'all')
    {
        $modules = collect(session('modules', []));

        switch ($filter) {
            case 'pending':
                $filter = 'pending';
                break;
            case 'all':
                $filter = 'all';
                break;
        }

        return view('admin.dashboards.show-reviews', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'filter' => $filter,
        ]);
    }

    public function fetchReviews($dashboardType, $filter = 'all')
    {
        $user = Auth::user()->load(['promoters', 'otherService']);
        $reviews = collect();

        if ($dashboardType === 'promoter') {
            $query = PromoterReview::where('promoter_id', $user->promoters->pluck('id'));

            if ($filter === 'pending') {
                $query->where('review_approved', 0);
            }
            $reviews = $query->orderBy('created_at', 'DESC')->get();
        } elseif ($dashboardType === 'artist') {
            $query = OtherServicesReview::where('other_services_id', $user->otherService("Artist")->pluck('other_services.id'))
                ->where('other_services_list_id', 4);

            if ($filter === 'pending') {
                $query->where('review_approved', 0);
            }
            $reviews = $query->orderBy('created_at', 'DESC')->get();
        } elseif ($dashboardType === 'designer') {
            $query = OtherServicesReview::where('other_services_id', $user->otherService("Designer")->pluck('other_services.id'))
                ->where('other_services_list_id', 3);

            if ($filter === 'pending') {
                $query->where('review_approved', 0);
            }
            $reviews = $query->orderBy('created_at', 'DESC')->get();
        }

        return response()->json(['reviews' => $reviews]);
    }

    public function approveDisplayReview($dashboardType, $reviewId)
    {
        $review = collect();

        if ($dashboardType === 'promoter') {
            $review = PromoterReview::findOrFail($reviewId);

            if ($review) {
                $review->review_approved = true;
                $review->display = true;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review displayed successfully']);
            }
        } elseif ($dashboardType === 'artist') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review) {
                $review->review_approved = true;
                $review->display = true;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review displayed successfully']);
            }
        } elseif ($dashboardType === 'designer') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review) {
                $review->review_approved = true;
                $review->display = true;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review displayed successfully']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Review not found']);
    }

    public function approveReview($dashboardType, $reviewId)
    {
        $review = collect();

        if ($dashboardType === 'promoter') {
            $review = PromoterReview::findOrFail($reviewId);

            if ($review) {
                $review->review_approved = true;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review approved successfully']);
            }
        } elseif ($dashboardType === 'artist') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review) {
                $review->review_approved = true;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review approved successfully']);
            }
        } elseif ($dashboardType === 'designer') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review) {
                $review->review_approved = true;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review approved successfully']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Review not found']);
    }

    public function displayReview($dashboardType, $reviewId)
    {
        $review = collect();

        if ($dashboardType === 'promoter') {
            $review = PromoterReview::findOrFail($reviewId);

            if ($review && $review->review_approved == 1) {
                $review->display = true;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review displayed successfully']);
            }
        } elseif ($dashboardType === 'artist') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review && $review->review_approved == 1) {
                $review->display = true;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review displayed successfully']);
            }
        } elseif ($dashboardType === 'designer') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review && $review->review_approved == 1) {
                $review->display = true;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review displayed successfully']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Review not found']);
    }

    public function hideReview($dashboardType, $reviewId)
    {
        $review = collect();

        if ($dashboardType === 'promoter') {
            $review = PromoterReview::findOrFail($reviewId);

            if ($review && $review->display == true) {
                $review->review_approved = false;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review hidden successfully.']);
            }
        } elseif ($dashboardType === 'artist') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review && $review->display == true) {
                $review->review_approved = false;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review hidden successfully.']);
            }
        } elseif ($dashboardType === 'designer') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review && $review->display == true) {
                $review->review_approved = false;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review hidden successfully.']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Review not found']);
    }

    public function unapproveReview($dashboardType, $reviewId)
    {
        $review = collect();

        if ($dashboardType === 'promoter') {
            $review = PromoterReview::findOrFail($reviewId);

            if ($review) {
                $review->review_approved = false;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review unapproved and hidden.']);
            }
        } elseif ($dashboardType === 'artist') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review) {
                $review->review_approved = false;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review unapproved and hidden.']);
            }
        } elseif ($dashboardType === 'designer') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review) {
                $review->review_approved = false;
                $review->save();

                return response()->json(['success' => true, 'message' => 'Review unapproved and hidden.']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Review not found']);
    }

    public function deleteReview($dashboardType, $reviewId)
    {
        $review = collect();

        if ($dashboardType === 'promoter') {
            $review = PromoterReview::findOrFail($reviewId);

            if ($review) {
                $review->delete();

                return response()->json(['success' => true, 'message' => 'Review deleted successfully.']);
            }
        } elseif ($dashboardType === 'artist') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review) {
                $review->delete();

                return response()->json(['success' => true, 'message' => 'Review deleted successfully.']);
            }
        } elseif ($dashboardType === 'designer') {
            $review = OtherServicesReview::findOrFail($reviewId);

            if ($review) {
                $review->delete();

                return response()->json(['success' => true, 'message' => 'Review deleted successfully.']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Review not found']);
    }
}

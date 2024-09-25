<?php

namespace App\Http\Controllers;

use App\Models\Promoter;
use Illuminate\Http\Request;
use App\Models\PromoterReview;
use Illuminate\Support\Facades\Auth;

class PromoterDashboardController extends Controller
{
    public function index()
    {
        $pendingReviews = PromoterReview::with('promoter')->where('review_approved', '0')->whereNull('deleted_at')->count();

        return view('admin.dashboards.promoter-dash', compact(
            'pendingReviews'
        ));
    }

    public function promoterFinances()
    {
        $promoter = Auth::user();

        return view('admin.dashboards.promoter.promoter-finances', compact('promoter'));
    }

    public function createNewPromoterBudget()
    {
        $promoter = Auth::user();

        return view('admin.dashboards.promoter.promoter-new-finance', compact('promoter'));
    }

    public function saveNewPromoterBudget(Request $request)
    {
        dd($request);
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

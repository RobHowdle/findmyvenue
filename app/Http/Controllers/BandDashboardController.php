<?php

namespace App\Http\Controllers;

use App\Models\OtherServicesReview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BandDashboardController extends Controller
{
    /**
     * Return the Dashboard
     */
    public function index()
    {
        $userId = Auth::id();
        $pendingReviews = OtherServicesReview::with('otherService')->where('review_approved', '0')->whereNull('deleted_at')->count();
        $band = Auth::user()->load('otherService');
        $todoItemsCount = $band->otherService()->with(['todos' => function ($query) {
            $query->where('completed', 0)->whereNull('deleted_at');
        }])->get()->pluck('todos')->flatten()->count();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $eventsCount = $band->otherService()
            ->with(['events' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('event_date', [$startOfWeek, $endOfWeek]);
            }])
            ->get()
            ->pluck('events')
            ->flatten()
            ->count();

        return view('admin.dashboards.band-dash', compact([
            'userId',
            'pendingReviews',
            'band',
            'todoItemsCount',
            'eventsCount'
        ]));
    }
}
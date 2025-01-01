<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\OtherServicesReview;
use Illuminate\Support\Facades\Auth;

class ArtistDashboardController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    /**
     * Return the Dashboard
     */
    public function index($dashboardType)
    {
        $modules = collect(session('modules', []));

        $pendingReviews = OtherServicesReview::with('otherService')->where('review_approved', '0')->whereNull('deleted_at')->count();
        $band = Auth::user()->load(['otherService', 'roles']);
        $role = $band->roles->first()->name;
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

        $service = $band->otherService(ucfirst($role))->first();
        // $dashboardType = lcfirst($service->services);

        return view('admin.dashboards.artist-dash', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'pendingReviews' => $pendingReviews,
            'artist' => $band,
            'todoItemsCount' => $todoItemsCount,
            'eventsCount' => $eventsCount,
        ]);
    }
}

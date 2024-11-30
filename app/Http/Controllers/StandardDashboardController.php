<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StandardDashboardController extends Controller
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

        $user = Auth::user();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        // $eventsCount = $user->events()
        //     ->with(['events' => function ($query) use ($startOfWeek, $endOfWeek) {
        //         $query->whereBetween('event_date', [$startOfWeek, $endOfWeek]);
        //     }])
        //     ->get()
        //     ->pluck('events')
        //     ->flatten()
        //     ->count();

        return view('admin.dashboards.standard-user-dash', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            // 'pendingReviews' => $pendingReviews,
            // 'todoItemsCount' => $todoItemsCount,
            // 'eventsCount' => $eventsCount,
        ]);
    }
}

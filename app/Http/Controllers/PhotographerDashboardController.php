<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtherServicesReview;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class PhotographerDashboardController extends Controller
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
        $photographer = Auth::user()->load(['otherService', 'roles']);
        $role = $photographer->roles->first()->name;
        $todoItemsCount = $photographer->otherService("Photography")->with(['todos' => function ($query) {
            $query->where('completed', 0)->whereNull('deleted_at');
        }])->get()->pluck('todos')->flatten()->count();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $jobsCount = $photographer->otherService('Photography')
            ->with(['jobs' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('job_start_date', [$startOfWeek, $endOfWeek]);
            }])
            ->get()
            ->pluck('jobs') // Pluck the jobs relationship
            ->flatten() // Flatten the collection of jobs
            ->count();


        $service = $photographer->otherService(ucfirst($role))->first();
        // $dashboardType = lcfirst($service->services);

        return view('admin.dashboards.photographer-dash', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'pendingReviews' => $pendingReviews,
            'photographer' => $photographer,
            'todoItemsCount' => $todoItemsCount,
            'jobsCount' => $jobsCount,
        ]);
    }
}

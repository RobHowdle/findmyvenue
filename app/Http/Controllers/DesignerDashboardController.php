<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesignerDashboardController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    public function index($dashboardType)
    {
        $modules = collect(session('modules', []));

        $designer = Auth::user()->load(['roles', 'otherService']);
        $role = $designer->roles->first()->name;
        $todoItemsCount = $designer->otherService()->with(['todos' => function ($query) {
            $query->where('completed', 0)->whereNull('deleted_at');
        }])->get()->pluck('todos')->flatten()->count();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $jobsCount = $designer->promoters()
            ->with(['jobs' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('job_end_date', [$startOfWeek, $endOfWeek]);
            }])
            ->get()
            ->pluck('jobs')
            ->flatten()
            ->count();

        return view('admin.dashboards.designer-dash', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'designer' => $designer,
            'todoItemsCount' => $todoItemsCount,
            'jobsCount' => $jobsCount,
        ]);
    }
}

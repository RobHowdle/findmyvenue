<?php

namespace App\Http\Controllers;

use App\Models\OtherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideographerJourneyController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    public function index($dashboardType)
    {
        $modules = collect(session('modules', []));
        $videographer = OtherService::videographers()->get();

        return view('admin.dashboards.videographer.videographer-journey', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'videographer' => $videographer,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $videographers = OtherService::videographers()->where('name', 'LIKE', '%' . $query . '%')->get();

        return response()->json([
            'results' => $videographers,
            'count' => $videographers->count()
        ]);
    }

    public function joinVideographer($dashboardType, Request $request)
    {
        $videographerId = $request->input('serviceable_id');
        $user = Auth::user();

        // Check designer exists
        $videographer = OtherService::find($videographerId);

        if (!$videographer) {
            return response()->json([
                'success' => false,
                'message' => 'The videographer does not exist.'
            ], 400);
        }

        if ($user->otherService('videographer')->where('serviceable_id', $videographerId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You are already linked'
            ], 400);
        }

        $user->otherService('videographer')->attach($videographerId);

        return response()->json([
            'success' => true,
            'message' => 'Successfully linked!',
            'redirect' => route('dashboard', ['dashboardType' => $dashboardType])
        ], 200);
    }
}
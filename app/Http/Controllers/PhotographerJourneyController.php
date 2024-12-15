<?php

namespace App\Http\Controllers;

use App\Models\OtherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\JoinBandRequest;

class PhotographerJourneyController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    public function index($dashboardType)
    {
        $modules = collect(session('modules', []));
        $photographers = OtherService::photographers()->get();

        return view('admin.dashboards.photographer.photographer-journey', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'photographers' => $photographers,
        ]);
    }

    public function searchPhotographer(Request $request)
    {
        $query = $request->get('query');

        if ($query) {
            $photographers = OtherService::where('other_service_id', 1)
                ->where('name', 'like', '%' . $query . '%')
                ->get();
        } else {
            $photographers = OtherService::where('other_service_id', 1)
                ->limit(8)
                ->get();
        }

        $html = '';
        foreach ($photographers as $photographer) {
            $html .= view('admin.dashboards.partials.photographer-row', compact('photographer'))->render();
        }

        return response()->json(['html' => $html]);
    }


    public function linkPhotographer($dashboardType, Request $request)
    {
        $photographerId = $request->input('photographer_id');
        $user = Auth::user();

        // Check if the band exists
        $photographer = OtherService::find($photographerId);

        if (!$photographer) {
            return response()->json([
                'success' => false,
                'message' => 'The photographer does not exist.'
            ], 404);
        }

        // Check if the user is already part of the band
        if ($user->otherService('photography')->where('serviceable_id', $photographerId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You are already a member of this photography company.'
            ], 400);
        }

        // Add the user to the band
        $user->otherService('artist')->attach($photographerId);

        return response()->json([
            'success' => true,
            'message' => 'Successfully joined the photography company!',
            'redirect' => route('dashboard', ['dashboardType' => $dashboardType])
        ], 200);
    }


    public function createPhotographer(Request $request)
    {
        // Validate and create a new band
        $request->validate([
            'photographer_name' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        // Create new band in the OtherService model
        $band = OtherService::create([
            'name' => $request->band_name,
            'other_service_id' => 1,
        ]);

        // Associate the user with the new band
        $user = auth()->user();
        $user->otherService()->attach($band->id);

        return redirect()->route('dashboard')->with('success', 'Successfully created and joined the new band!');
    }
}

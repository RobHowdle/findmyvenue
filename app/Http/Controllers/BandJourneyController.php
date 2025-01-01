<?php

namespace App\Http\Controllers;

use App\Models\OtherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\JoinBandRequest;

class BandJourneyController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    public function index($dashboardType)
    {
        $modules = collect(session('modules', []));
        $bands = OtherService::bands()->get();

        return view('admin.dashboards.band.band-journey', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'bands' => $bands,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        if ($query) {
            $artists = OtherService::where('other_service_id', 4)
                ->where('name', 'like', '%' . $query . '%')
                ->get();
        } else {
            $artists = OtherService::where('other_service_id', 4)
                ->limit(8)
                ->get();
        }

        $html = '';
        foreach ($artists as $artist) {
            $html .= view('admin.dashboards.partials.band-row', compact('artist'))->render();
        }

        return response()->json(['html' => $html]);
    }


    public function joinBand($dashboardType, Request $request)
    {
        $bandId = $request->input('band_id');
        $user = Auth::user();

        // Check if the band exists
        $band = OtherService::find($bandId);

        if (!$band) {
            return response()->json([
                'success' => false,
                'message' => 'The band does not exist.'
            ], 404);
        }

        // Check if the user is already part of the band
        if ($user->otherService('artist')->where('serviceable_id', $bandId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You are already a member of this band.'
            ], 400);
        }

        // Add the user to the band
        $user->otherService('artist')->attach($bandId);

        return response()->json([
            'success' => true,
            'message' => 'Successfully linked!',
            'redirect' => route('dashboard', ['dashboardType' => $dashboardType])
        ], 200);
    }


    public function createBand(Request $request)
    {
        // Validate and create a new band
        $request->validate([
            'band_name' => 'required|string|max:255',
            // Add other validation rules as needed
        ]);

        // Create new band in the OtherService model
        $band = OtherService::create([
            'name' => $request->band_name,
            'other_service_id' => 4,
        ]);

        // Associate the user with the new band
        $user = auth()->user();
        $user->otherService()->attach($band->id);

        return redirect()->route('dashboard')->with('success', 'Successfully created and joined the new band!');
    }
}

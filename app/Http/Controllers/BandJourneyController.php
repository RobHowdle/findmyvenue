<?php

namespace App\Http\Controllers;

use App\Models\OtherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\JoinBandRequest;

class BandJourneyController extends Controller
{
    public function index()
    {
        $bands = OtherService::bands()->get();

        return view('admin.dashboards.band.band-journey', compact('bands'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        if ($query) {
            $bands = OtherService::where('other_service_id', 4)
                ->where('name', 'like', '%' . $query . '%')
                ->get();
        } else {
            $bands = OtherService::where('other_service_id', 4)
                ->limit(8)
                ->get();
        }

        $html = '';

        foreach ($bands as $band) {
            $html .= '<tr>
                <td class="border-b px-4 py-2">' . $band->name . '</td>
                <td class="border-b px-4 py-2">
                  <button class="join-band-btn rounded-md text-sm text-gray-600 underline hover:text-gray-900" 
                          data-band-id="' . $band->id . '">Join</button>
                </td>
              </tr>';
        }

        return response()->json(['html' => $html]);
    }


    public function joinBand(Request $request)
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
        if ($user->otherService('band')->where('serviceable_id', $bandId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You are already a member of this band.'
            ], 400);
        }

        // Add the user to the band
        $user->otherService('band')->attach($bandId);

        return response()->json([
            'success' => true,
            'message' => 'Successfully joined the band!',
            'redirect' => route('dashboard')
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

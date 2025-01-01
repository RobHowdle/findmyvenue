<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VenueJourneyController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    public function index($dashboardType)
    {
        $modules = collect(session('modules', []));
        $venues = Venue::get();
        $genres = $data['genres'] ?? [];


        return view('admin.dashboards.venue.venue-journey', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'venues' => $venues,
            'genres' => $genres
        ]);
    }

    public function searchVenue(Request $request)
    {
        $query = $request->get('query');

        if ($query) {
            $venues = Venue::where('name', 'like', '%' . $query . '%')
                ->get();
        } else {
            $venues = Venue::where('name', 'like', '%' . $query . '%')
                ->limit(8)
                ->get();
        }

        $html = '';
        foreach ($venues as $venue) {
            $html .= view('admin.dashboards.partials.venue-row', compact('venue'))->render();
        }

        return response()->json(['html' => $html]);
    }

    public function linkVenue(Request $request)
    {
        $serviceableId = $request->input('serviceable_id');
        $serviceableType = 'App\Models\Venue';

        $user = auth()->user();
        $venue = Venue::find($serviceableId);

        if (!$venue) {
            return response()->json(['error' => 'Venue not found'], 404);
        }

        $existingUsersCount = DB::table('service_user')
            ->where('serviceable_id', $serviceableId)
            ->where('serviceable_type', $serviceableType)
            ->count();

        DB::table('service_user')->insert([
            'user_id' => $user->id,
            'serviceable_id' => $serviceableId,
            'serviceable_type' => $serviceableType,
            'role' => ($existingUsersCount == 0) ? 'Owner' : 'Standard',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->load('roles');
        $userRole = $user->roles->first();


        if (!$userRole) {
            return response()->json(['error' => 'User role not found'], 404);
        }


        return response()->json([
            'redirect_url' => route('dashboard', ['dashboardType' => $userRole->name]),
            'message' => 'Successfully linked! Hold tight whilst we redirect you'
        ]);
    }
}
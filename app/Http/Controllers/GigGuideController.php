<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class GigGuideController extends Controller
{
    public function showGigGuide(Request $request)
    {
        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];
        $user = auth()->user();

        $gigsCloseToMe = [];
        $gigs5Miles = [];
        $gigs10Miles = [];
        $gigs20Miles = [];
        $otherGigs = [];

        // If the user is logged in 
        if (auth()->check()) {
            // If the user has a set location
            if ($user->location) {
                $latitude = $user->latitude;
                $longitude = $user->longitude;
                $distance = $request->get('radius', 5);
                $startOfWeek = now()->startOfWeek();
                $endOfWeek = now()->endOfWeek();

                // 5  Miles
                $gigsCloseToMe = $this->fetchNearbyGigs($latitude, $longitude, $distance, $startOfWeek, $endOfWeek);
            } else {
                // No user location set; fetch all events
                $otherGigs = Event::with('venues')
                    ->orderBy('event_date', 'asc')
                    ->get();
            }
        } else {
            // If the user is not logged in, return all events sorted by event date
            $otherGigs = Event::with('venues')
                ->orderBy('event_date', 'asc')
                ->get();
        }

        return view('gig-guide', [
            'gigs5Miles' => $gigs5Miles,
            'gigs10Miles' => $gigs10Miles,
            'gigs20Miles' => $gigs20Miles,
            'otherGigs' => $otherGigs,
            'genres' => $genres,
            'gigsCloseToMe' => $gigsCloseToMe,
            'user' => $user,
        ]);
    }

    private function fetchNearbyGigs($latitude, $longitude, $distance, $startOfWeek, $endOfWeek)
    {
        $gigs = DB::table('events')
            ->join('event_venue', 'events.id', '=', 'event_venue.event_id')
            ->join('venues', 'event_venue.venue_id', '=', 'venues.id')
            ->select('events.*', 'venues.latitude', 'venues.longitude', 'venues.name')
            ->get();

        $userLocation = "{$latitude},{$longitude}";
        $venues = $gigs->map(function ($gig) {
            return "{$gig->latitude},{$gig->longitude}";
        })->implode('|');

        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/distancematrix/json", [
            'origins' => $userLocation,
            'destinations' => $venues,
            'key' => $apiKey,
            'units' => 'imperial',
        ]);

        $data = $response->json();
        dd($data);

        if (!isset($data['rows'][0]['elements'])) {
            throw new \Exception('Error fetching distance data from Google Maps API');
        }

        $distances = $data['rows'][0]['elements'];

        // Transform the gigs collection to include the distance
        $gigs = $gigs->map(function ($gig, $index) use ($distances) {
            $element = $distances[$index];

            if ($element['status'] === 'ZERO_RESULTS') {
                $gig->distance = null;
            } elseif (isset($element['distance']['value'])) {
                $gig->distance = $element['distance']['value'] / 1609.34;
            } else {
                $gig->distance = null;
            }

            return $gig;
        });

        dd($gigs);

        // Filter gigs based on distance
        $filteredGigs = $gigs->filter(fn($gig) => $gig->distance === null || $gig->distance <= $distance);

        // Sort gigs by distance
        $sortedGigs = $filteredGigs->sortBy('distance');

        return $sortedGigs;
    }



    protected function getCoordinatesFromLocation($location)
    {
        $apiKey = env('GEOCODING_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'address' => $location,
            'key' => $apiKey,
        ]);

        if ($response->successful() && isset($response['results'][0]['geometry']['location'])) {
            return [
                'lat' => $response['results'][0]['geometry']['location']['lat'],
                'lng' => $response['results'][0]['geometry']['location']['lng'],
            ];
        }

        return null;
    }

    public function filterGigs(Request $request)
    {
        $longitude = $request->get('longitude');
        $latitude = $request->get('latitude');
        $distance = $request->get('distance');
        $showOtherGigs = $request->get('showOtherGigs', false);

        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Location data is missing.'], 400);
        }

        $startOfWeek = now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = now()->endOfWeek()->format('Y-m-d');

        if ($showOtherGigs == false) {
            switch ($distance) {
                case '5':
                    $gigsCloseToMe = $this->fetchNearbyGigs($latitude, $longitude, $distance, $startOfWeek, $endOfWeek);
                    break;
                case '10':
                    $gigsCloseToMe = $this->fetchNearbyGigs($latitude, $longitude, $distance, $startOfWeek, $endOfWeek);
                    break;
                case '20':
                    $gigsCloseToMe = $this->fetchNearbyGigs($latitude, $longitude, $distance, $startOfWeek, $endOfWeek);
                    break;
                case '50':
                    $gigsCloseToMe = $this->fetchNearbyGigs($latitude, $longitude, $distance, $startOfWeek, $endOfWeek);
                    break;
                case '100':
                    $gigsCloseToMe = $this->fetchNearbyGigs($latitude, $longitude, $distance, $startOfWeek, $endOfWeek);
                    break;
            }
        }

        return response()->json([
            'gigsCloseToMe' => $gigsCloseToMe,
            // 'otherGigs' => $otherGigs,
        ]);
    }
}
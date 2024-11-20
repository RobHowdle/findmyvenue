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


        if (auth()->check()) {
            // Attempt to get coordinates from user's location dynamically
            if ($user->location) {
                $gigsCloseToMe = Event::with('venues')
                    ->whereHas('venues', function ($query) use ($user) {
                        $query->where('postal_town', $user->location);
                    })
                    ->orderBy('event_date', 'asc')
                    ->get();

                $coordinates = $this->getCoordinatesFromLocation($user->location);
                if ($coordinates) {
                    $userLat = $coordinates['lat'];
                    $userLng = $coordinates['lng'];

                    // Fetch events within specified distances
                    $gigs5Miles = Event::with('venues')
                        ->whereHas('venues', function ($query) use ($userLat, $userLng) {
                            $query->whereRaw($this->distanceQuery($userLat, $userLng, 5));
                        })
                        ->orderBy('event_date', 'asc')
                        ->get();

                    $gigs10Miles = Event::with('venues')
                        ->whereHas('venues', function ($query) use ($userLat, $userLng) {
                            $query->whereRaw($this->distanceQuery($userLat, $userLng, 10));
                        })
                        ->orderBy('event_date', 'asc')
                        ->get();

                    $gigs20Miles = Event::with('venues')
                        ->whereHas('venues', function ($query) use ($userLat, $userLng) {
                            $query->whereRaw($this->distanceQuery($userLat, $userLng, 20));
                        })
                        ->orderBy('event_date', 'asc')
                        ->get();

                    $otherGigs = Event::with('venues')
                        ->whereHas('venues', function ($query) use ($userLat, $userLng) {
                            $query->whereRaw($this->distanceQuery($userLat, $userLng, 20, '>'));
                        })
                        ->orderBy('event_date', 'asc')
                        ->get();
                }
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

        // Pass events to the view
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
        $distance = $request->get('distance');
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $earthRadius = 3959; // Earth radius in miles
        $showOtherGigs = $request->get('showOtherGigs', false);

        // Ensure latitude and longitude are available
        if (!$latitude || !$longitude) {
            return response()->json(['error' => 'Location data is missing.'], 400);
        }

        $userLatRad = deg2rad($latitude);
        $userLongRad = deg2rad($longitude);



        $startOfWeek = now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = now()->endOfWeek()->format('Y-m-d');

        // Handle "Show all" case (no filter)
        if ($distance === 'all') {
            $gigsCloseToMe = Event::whereBetween('event_date', [$startOfWeek, $endOfWeek])->get();
            $otherGigs = [];
        } else {
            // Calculate gigs within the specified distance
            $gigsCloseToMe = DB::table('events')
                ->join('event_venue', 'events.id', '=', 'event_venue.event_id')
                ->join('venues', 'event_venue.venue_id', '=', 'venues.id')
                ->whereBetween('events.event_date', [$startOfWeek, $endOfWeek])
                ->select(
                    'events.*',
                    'venues.*',
                    DB::raw("(
                    $earthRadius * acos(
                        cos(radians(?)) * cos(radians(venues.latitude)) *
                        cos(radians(venues.longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(venues.latitude))
                    )
                ) AS distance")
                )
                ->setBindings([$latitude, $longitude, $latitude], 'select') // Bind latitude and longitude
                ->having('distance', '<=', $distance)
                ->orderBy('distance')
                ->get();

            // Calculate gigs outside the specified distance
            $otherGigs = DB::table('events')
                ->join('event_venue', 'events.id', '=', 'event_venue.event_id')
                ->join('venues', 'event_venue.venue_id', '=', 'venues.id')
                ->whereBetween('events.event_date', [$startOfWeek, $endOfWeek])
                ->select(
                    'events.*',
                    'venues.*',
                    DB::raw("(
                    $earthRadius * acos(
                        cos(radians(?)) * cos(radians(venues.latitude)) *
                        cos(radians(venues.longitude) - radians(?)) +
                        sin(radians(?)) * sin(radians(venues.latitude))
                    )
                ) AS distance")
                )
                ->setBindings([$latitude, $longitude, $latitude], 'select') // Bind latitude and longitude
                ->having('distance', '>', $distance)
                ->orderBy('distance')
                ->get();
        }


        if (!$showOtherGigs) {
            // If showOtherGigs is not checked, empty the otherGigs array
            $otherGigs = [];
        }


        // // Debugging: log the calculated distances and coordinates for each gig
        // foreach ($gigsCloseToMe as $gig) {
        //     \Log::info("Gig: {$gig->name}, Venue Coordinates: lat={$gig->latitude}, long={$gig->longitude}, Calculated Distance: {$gig->distance} miles");
        // }

        // foreach ($otherGigs as $gig) {
        //     \Log::info("Gig: {$gig->name}, Venue Coordinates: lat={$gig->latitude}, long={$gig->longitude}, Calculated Distance: {$gig->distance} miles");
        // }

        return response()->json([
            'gigsCloseToMe' => $gigsCloseToMe,
            'otherGigs' => $otherGigs,
        ]);
    }
}

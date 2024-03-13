<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $searchQuery = $request->input('search_query');

        $venues = Venue::whereNull('deleted_at')
            ->with('extraInfo', 'promoters')
            ->when($searchQuery, function ($query, $searchQuery) {
                return $query->where('name', 'like', "%$searchQuery%");
            })
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'venues' => $venues,
                'view' => view('partials.venue-list', compact('venues'))->render()
            ]);
        }

        // Fetch genres for initial page load
        $genreList = file_get_contents(storage_path('app/public/text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];

        // Return the initial view
        return view('venues', compact('venues', 'genres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $venue = Venue::where('id', $id)->first();

        return view('venue', compact('venue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function locations()
    {
        $locations = Venue::whereNull('deleted_at')
                        ->select('postal_town', DB::raw('COUNT(*) as count'))
                        ->groupBy('postal_town')
                        ->get();

        return view('locations', compact('locations'));
    }

    public function filterByCoordinates(Request $request)
    {
        // Get latitude and longitude from the request
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        
        // Filter venues by latitude and longitude
        $venuesByCoordinates = Venue::where('latitude', $latitude)
            ->where('longitude', $longitude)
            ->get();

        // Get the search query from the request
        $searchQuery = $request->input('search_query');

        // dd($searchQuery);

        // Check if the search query contains a comma (indicating both town and specific address)
        if (strpos($searchQuery, ',') !== false) {
            // If the search query contains a comma, split it into town and address
            list($town, $address) = explode(',', $searchQuery);

            // Perform search for venues matching the town or the address
            $venuesByAddress = Venue::where(function($query) use ($town, $address) {
                $query->where('postal_town', 'LIKE', "%$address%")
                    ->orWhere('postal_town', 'LIKE', "%$town%");
            })->get();
        } else {
            // If the search query does not contain a comma, search for venues matching the town only
            $venuesByAddress = Venue::where('postal_town', 'LIKE', "%$searchQuery%")
                ->get();
        }
        
        // Merge the search results and remove duplicates
        $venues = $venuesByCoordinates->merge($venuesByAddress)->unique();

        // Pass the search results to the view
        return view('venues', compact('venues'));
    }

    public function filterCheckboxes(Request $request)
    {
        $query = Venue::query();

        // Existing Search If Exists

        // Band Type Filter
        if ($request->has('band_type')) {
            $bandType = $request->input('band_type');
            if (!empty($bandType)) { // Check if genres are not empty
                $query->where(function ($query) use ($bandType) {
                    foreach($bandType as $type) {
                        $query->orWhereJsonContains('band_type', $type);
                    }
                });
            }
        }


        // Genre Filter
        if ($request->has('genres')) {
            $genres = $request->input('genres');
            if (!empty($genres)) { // Check if genres are not empty
                $query->where(function ($query) use ($genres) {
                    foreach($genres as $genre) {
                        $query->orWhereJsonContains('genre', $genre);
                    }
                });
            }
        }

        $venues = $query->get();
        return response()->json($venues);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\DataTables\VenuesDataTable;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(VenuesDataTable $dataTable)
    {
        $venues = Venue::whereNull('deleted_at')
        ->with('extraInfo', 'promoters')
        ->get();
        
        // Process each venue
        foreach ($venues as $venue) {
        // Split the field containing multiple URLs into an array
        $urls = explode(',', $venue->contact_link); // Assuming the field name is 'contact_links'
        $platforms = [];

        // Check each URL against the platforms
        foreach ($urls as $url) {
                // Initialize the platform as unknown
                $matchedPlatform = 'Unknown';

                // Check if the URL contains platform names
                $platformsToCheck = ['facebook', 'twitter', 'instagram'];
                foreach ($platformsToCheck as $platform) {
                    if (stripos($url, $platform) !== false) {
                        $matchedPlatform = $platform;
                        break; // Stop checking once a platform is found
                    }
                }

                // Store the platform information for each URL
                $platforms[] = [
                    'url' => $url,
                    'platform' => $matchedPlatform
                ];
            }

            // Add the processed data to the venue
            $venue->platforms = $platforms;
        }
        return view('venues', compact('venues'));
        // return $dataTable->render('venues');
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
        //
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
                        ->select('location', DB::raw('COUNT(*) as count'))
                        ->groupBy('location')
                        ->get();

        return view('locations', compact('locations'));
    }

}
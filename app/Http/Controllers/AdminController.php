<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venue;
use App\Models\Promoter;
use Illuminate\Support\Str;
use App\Models\OtherService;
use Illuminate\Http\Request;
use App\Models\PromoterVenue;
use App\Models\OtherServiceList;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function getVenues()
    {
        $venueCount = Venue::whereNull('deleted_at')->count();
        $locationCount = Venue::whereNull('deleted_at')->distinct('postal_town')->count();

        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $promoters = Promoter::whereNull('deleted_at')->get();

        $genres = $data['genres'];

        return view('admin.venues', compact('venueCount', 'locationCount', 'genres', 'promoters'));
    }

    public function saveNewVenue(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'floating_name' => 'required|string',
                'venue_logo' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:200',
                'floating_description' => 'nullable',
                'address-input' => 'required',
                'postal-town-input' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'floating_capacity' => 'required|numeric',
                'floating_in_house_gear' => 'required|string',
                'existingPromoter' => 'nullable|string',
                'band_type' => 'required|array',
                'genres' => 'required|array',
                'floating_contact_name' => 'required|string',
                'floating_contact_number' => 'required|numeric|digits:11',
                'floating_contact_email' => 'required|email',
                'floating_contact_links' => 'nullable',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Get the uploaded image file
            $venueLogoFile = $request->file('venue_logo');
            // Generate a unique filename based on the promoter's name and extension
            $venueName = $request->input('floating_name');
            $venueLogoExtension = $venueLogoFile->getClientOriginalExtension();
            $venueLogoFilename = Str::slug($venueName) . '.' . $venueLogoExtension;

            // Specify the destination directory within the public folder
            $destinationPath = 'images/venue_logos';

            // Move the uploaded image to the specified directory
            $venueLogoFile->move(
                public_path($destinationPath),
                $venueLogoFilename
            );

            // Construct the URL to the stored image
            $logoUrl = $destinationPath . '/' . $venueLogoFilename;

            $newVenue = Venue::create([
                'name' => $request->input('floating_name'),
                'description' => $request->input('floating_description'),
                'logo_url' => $logoUrl,
                'location' => $request->input('address-input'),
                'postal_town' => $request->input('postal-town-input'),
                'longitude' => $request->input('latitude'),
                'latitude' => $request->input('longitude'),
                'capacity' => $request->input('floating_capacity'),
                'in_house_gear' => $request->input('floating_in_house_gear'),
                'band_type' => json_encode($request->input('band_type')),
                'genre' => json_encode($request->input('genres')),
                'contact_name' => $request->input('floating_contact_name'),
                'contact_number' => $request->input('floating_contact_number'),
                'contact_email' => $request->input('floating_contact_email'),
                'contact_link' => $request->input('floating_contact_links'),
                'additional_info' => $request->input('extra_info'),
            ]);

            if ($request->input('existingPromoter')) {
                PromoterVenue::create([
                    'promoters_id' => $request->input('existingPromoter'),
                    'venues_id' => $newVenue->id,
                ]);
            }

            return back()->with('success', 'Venue created successfully.');
        } catch (\Exception $e) {
            Log::error('Error saving new venue: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while saving the venue. Please try again later.')->withInput();
        }
    }

    public function viewVenueList()
    {
        $venues = Venue::whereNull('deleted_at')->get();

        return view('admin.venue-list', compact('venues'));
    }

    public function editVenue(Venue $venueId)
    {
        $venue = $venueId;
        $venue->load('promoters');

        // Load and decode the genre list
        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $genresData = json_decode($genreList, true);

        // Prepare genre names and subgenres
        $genreNames = [];
        foreach ($genresData['genres'] as $genre) {
            if (isset($genre['name']) && isset($genre['subgenres'])) {
                $genreNames[] = [
                    'name' => $genre['name'],
                    'subgenres' => $genre['subgenres']
                ];
            }
        }

        // Ensure genres and band_type are arrays
        $venueGenres = is_array($venue->genre) ? $venue->genre : json_decode($venue->genre, true);
        $venue->band_type = is_array($venue->band_type) ? $venue->band_type : json_decode($venue->band_type, true);
        $venue->genre = $venueGenres;

        return view('admin.edit-venue', compact('venue', 'genreNames'));
    }

    public function updateVenue(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'floating_name' => 'required|string',
                'venue_logo' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:200',
                'floating_description' => 'nullable',
                'floating_capacity' => 'required|numeric',
                'floating_in_house_gear' => 'required|string',
                'existingPromoter' => 'nullable|string',
                'band_type' => 'required|array',
                'genres' => 'required|array',
                'floating_contact_name' => 'required|string',
                'floating_contact_number' => 'nullable|numeric|digits:11',
                'floating_contact_email' => 'required|email',
                'floating_contact_links' => 'nullable',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $venue = Venue::findOrFail($id);

            $updatedFields = [];

            // Check if each field has been changed and update accordingly
            if ($request->input('floating_name') != $venue->name) {
                $updatedFields['name'] = $request->input('floating_name');
            }

            if ($request->hasFile('venue_logo')) {
                // Get the uploaded image file
                $venueLogoFile = $request->file('venue_logo');
                // Generate a unique filename based on the promoter's name and extension
                $venueName = $request->input('floating_name');
                $venueLogoExtension = $venueLogoFile->getClientOriginalExtension();
                $venueLogoFilename = Str::slug($venueName) . '.' . $venueLogoExtension;

                // Specify the destination directory within the public folder
                $destinationPath = 'images/venue_logos';

                // Move the uploaded image to the specified directory
                $venueLogoFile->move(
                    public_path($destinationPath),
                    $venueLogoFilename
                );

                // Construct the URL to the stored image
                $logoUrl = $destinationPath . '/' . $venueLogoFilename;
                $updatedFields['logo_url'] = $logoUrl;
            }

            if ($request->input('floating_description') != $venue->description) {
                $updatedFields['description'] = $request->input('floating_description');
            }

            if ($request->input('floating_capacity') != $venue->capacity) {
                $updatedFields['capacity'] = $request->input('floating_capacity');
            }

            if ($request->input('floating_in_house_gear') != $venue->in_house_gear) {
                $updatedFields['in_house_gear'] = $request->input('floating_in_house_gear');
            }

            if ($request->input('band_type') != json_decode($venue->band_type)) {
                $updatedFields['band_type'] = json_encode($request->input('band_type'));
            }

            if ($request->input('genres') != json_decode($venue->genre)) {
                $updatedFields['genre'] = json_encode($request->input('genres'));
            }

            if ($request->input('floating_contact_name') != $venue->contact_name) {
                $updatedFields['contact_name'] = $request->input('floating_contact_name');
            }

            if ($request->input('floating_contact_number') != $venue->contact_number) {
                $updatedFields['contact_number'] = $request->input('floating_contact_number');
            }

            if ($request->input('floating_contact_email') != $venue->contact_email) {
                $updatedFields['contact_email'] = $request->input('floating_contact_email');
            }

            if ($request->input('floating_contact_links') != $venue->contact_link) {
                $updatedFields['contact_link'] = $request->input('floating_contact_links');
            }

            if ($request->input('extra_info') != $venue->additional_info) {
                $updatedFields['additional_info'] = $request->input('extra_info');
            }

            // Update the venue with the modified fields
            $venue->update($updatedFields);

            if ($request->input('existingPromoter')) {
                PromoterVenue::updateOrCreate(
                    ['venues_id' => $venue->id],
                    ['promoters_id' => $request->input('existingPromoter')]
                );
            }

            return back()->with('success', 'Venue updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating venue: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the venue. Please try again later.')->withInput();
        }
    }

    public function deleteVenue($venueId)
    {
        $venue = Venue::findOrFail($venueId);
        $venue->delete();
        return redirect()->back()->with('success', 'Venue deleted successfully.');
    }

    public function viewPromoterList()
    {
        $promoters = Promoter::whereNull('deleted_at')->get();

        return view('admin.promoter-list', compact('promoters'));
    }

    public function getPromoters()
    {
        $promoterCount = Promoter::whereNull('deleted_at')->count();
        $venuesByTown = Venue::select('postal_town', DB::raw('GROUP_CONCAT(name) as venue_names'), DB::raw('COUNT(id) as id'))
            ->whereNull('deleted_at')
            ->groupBy('postal_town')
            ->get();

        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];
        $venues = Venue::whereNull('deleted_at')->get();
        $postalTown = Venue::whereNull('deleted_at')->select('postal_town')->groupBy('postal_town')->get();

        return view('admin.promoters', compact('promoterCount', 'venuesByTown', 'genres', 'venues', 'postalTown'));
    }

    public function saveNewPromoter(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'promoter_name' => 'required|string',
                'address-input' => 'required',
                'postal-town-input' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'promoter_logo' => 'required|image|mimes:jpeg,jpg,png,webp,svg|max:2048',
                'promoter_about_me' => 'required',
                'promoter_my_venues' => 'required',
                'promoter_contact_email' => 'nullable|email',
                'promoter_contact_number' => 'nullable|numeric|digits:11',
                'promoter_links' => 'nullable',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Get the uploaded image file
            $promoterLogoFile = $request->file('promoter_logo');

            // Generate a unique filename based on the promoter's name and extension
            $promoterName = $request->input('promoter_name');
            $promoterLogoExtension = $promoterLogoFile->getClientOriginalExtension();
            $promoterLogoFilename = Str::slug($promoterName) . '.' . $promoterLogoExtension;

            // Specify the destination directory within the public folder
            $destinationPath = 'images/promoters_logos';

            // Move the uploaded image to the specified directory
            $promoterLogoFile->move(public_path($destinationPath), $promoterLogoFilename);

            // Construct the URL to the stored i`mage
            $logoUrl = $destinationPath . '/' . $promoterLogoFilename;


            $promoter = Promoter::create([
                'name' => $request->input('promoter_name'),
                'location' => $request->input('address-input'),
                'postal_town' => $request->input('postal-town-input'),
                'longitude' => $request->input('latitude'),
                'latitude' => $request->input('longitude'),
                'logo_url' => $logoUrl,
                'about_me' => $request->input('promoter_about_me'),
                'my_venues' => $request->input('promoter_my_venues'),
                'contact_number' => $request->input('promoter_contact_number'),
                'contact_email' => $request->input('promoter_contact_email'),
                'contact_link' => $request->input('promoter_contact_links'),
            ]);

            // Get the array of venue names from the request
            $venueNames = $request->input('venues');

            // Iterate over the venue names and create records in the pivot table
            foreach ($venueNames as $venueName) {
                // Find the venue by name
                $venue = Venue::where('name', $venueName)->first();

                // If the venue exists, attach it to the promoter
                if ($venue) {
                    $promoter->venues()->attach($venue->id);
                }
            }

            return back()->with('success', 'Promoter created successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error saving new venue: ' . $e->getMessage());

            // Optionally, you can return an error response or redirect to an error page
            return back()->with('error', 'An error occurred while saving the promter. Please try again later.')->withInput();
        }
    }

    public function editPromoter(Promoter $promoterId)
    {
        $promoter = $promoterId;
        $promoter->load('venues');

        // Load and decode the genre list
        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $genresData = json_decode($genreList, true);

        // Prepare genre names and subgenres
        $genreNames = [];
        foreach ($genresData['genres'] as $genre) {
            if (isset($genre['name']) && isset($genre['subgenres'])) {
                $genreNames[] = [
                    'name' => $genre['name'],
                    'subgenres' => $genre['subgenres']
                ];
            }
        }

        // Ensure genres and band_type are arrays
        $promoterGenres = is_array($promoter->genre) ? $promoter->genre : json_decode($promoter->genre, true);
        $promoter->band_types = !empty($promoter->band_types) && is_string($promoter->band_types) ? json_decode($promoter->band_types, true) : [];
        $promoter->genre = $promoterGenres;

        return view('admin.edit-promoter', compact('promoter', 'genreNames'));
    }

    public function updatePromoter(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'promoter_name' => 'required|string',
                'promoter_logo' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:2048',
                'promoter_about_me' => 'required',
                // 'promoter_my_venues' => 'required',
                'band_type' => 'required',
                'genres' => 'required',
                'promoter_contact_name' => 'required',
                'promoter_contact_email' => 'nullable|email',
                'promoter_contact_number' => 'nullable|numeric|digits:11',
                'promoter_links' => 'nullable',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $promoter = Promoter::findOrFail($id);

            $updatedFields = [];

            // Check if each field has been changed and update accordingly
            if ($request->input('promoter_name') != $promoter->name) {
                $updatedFields['name'] = $request->input('promoter_name');
            }

            if ($request->hasFile('promoter_logo')) {
                // Get the uploaded image file
                $promoterLogoFile = $request->file('promoter_logo');
                // Generate a unique filename based on the promoter's name and extension
                $promoterName = $request->input('promoter_name');
                $promoterLogoExtension = $promoterLogoFile->getClientOriginalExtension();
                $promoterLogoFilename = Str::slug($promoterName) . '.' . $promoterLogoExtension;

                // Specify the destination directory within the public folder
                $destinationPath = 'images/promoters_logos';

                // Move the uploaded image to the specified directory
                $promoterLogoFile->move(
                    public_path($destinationPath),
                    $promoterLogoFilename
                );

                // Construct the URL to the stored image
                $logoUrl = $destinationPath . '/' . $promoterLogoFilename;
                $updatedFields['logo_url'] = $logoUrl;
            }

            if ($request->input('promoter_about_me') != $promoter->description) {
                $updatedFields['about_me'] = $request->input('promoter_about_me');
            }

            if ($request->input('promoter_my_venues') != $promoter->capacity) {
                $updatedFields['my_venues'] = $request->input('promoter_my_venues');
            }

            if ($request->input('band_type') != json_decode($promoter->band_type)) {
                $updatedFields['band_types'] = json_encode($request->input('band_type'));
            }

            if ($request->input('genres') != json_decode($promoter->genre)) {
                $updatedFields['genre'] = json_encode($request->input('genres'));
            }

            if ($request->input('promoter_contact_name') != $promoter->contact_name) {
                $updatedFields['contact_name'] = $request->input('promoter_contact_name');
            }

            if ($request->input('promoter_contact_number') != $promoter->contact_number) {
                $updatedFields['contact_number'] = $request->input('promoter_contact_number');
            }

            if ($request->input('promoter_contact_email') != $promoter->contact_email) {
                $updatedFields['contact_email'] = $request->input('promoter_contact_email');
            }

            if ($request->input('promoter_contact_links') != $promoter->contact_link) {
                $updatedFields['contact_link'] = $request->input('promoter_contact_links');
            }

            // Update the venue with the modified fields
            $promoter->update($updatedFields);

            // if ($request->input('existingPromoter')) {
            //     PromoterVenue::updateOrCreate(
            //         ['venues_id' => $venue->id],
            //         ['promoters_id' => $request->input('existingPromoter')]
            //     );
            // }

            return back()->with('success', 'Promoter updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating promoter: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the promoter. Please try again later.')->withInput();
        }
    }

    public function createOtherService()
    {
        $serviceList = OtherServiceList::all();
        return view('admin.create-other', compact('serviceList'));
    }

    public function saveNewOtherService(Request $request)
    {
        try {
            $jsonPackage = json_decode($request->input('packages_json'), true);

            // Check if there was an error during decoding
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Error saving json array: ' . json_last_error_msg());
            }

            $validator = Validator::make($request->all(), [
                'service' => 'required|exists:App\Models\OtherServiceList,id',
                'address-input' => 'required',
                'postal-town-input' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'photographer_logo' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:2048',
                'photographer_name' => 'required|string',
                'packages_json' => 'required',
                'environment_type' => 'required|array',
                'working_times' => 'required|array',
                'contact_email' => 'nullable|email',
                'contact_number' => 'nullable|numeric|digits:11',
                'contact_links' => 'nullable',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $photographerLogoFile = $request->file('photographer_logo');
            $photographerName = $request->input('photographer_name');
            $photographerLogoExtension = $photographerLogoFile->getClientOriginalExtension();
            $photographerLogoFilename = Str::slug($photographerName) . '.' . $photographerLogoExtension;

            // Specify the destination directory within the public folder
            $destinationPath = 'images/other_services';

            // Move the uploaded image to the specified directory
            $photographerLogoFile->move(public_path($destinationPath), $photographerLogoFilename);

            // Construct the URL to the stored image
            $logoUrl = $destinationPath . '/' . $photographerLogoFilename;

            $photographer = OtherService::create([
                'name' => $request->input('photographer_name'),
                'location' => $request->input('address-input'),
                'postal_town' => $request->input('postal-town-input'),
                'longitude' => $request->input('latitude'),
                'latitude' => $request->input('longitude'),
                'logo_url' => $logoUrl,
                'other_service_id' => $request->input('service'),
                'packages' => json_encode($jsonPackage),
                'environment_type' => json_encode($request->input('environment_type')),
                'working_times' => json_encode($request->input('working_times')),
                'contact_email' => $request->input('contact_email'),
                'contact_number' => $request->input('contact_number'),
                'contact_link' => $request->input('contact_links'),
            ]);

            return back()->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error saving new service: ' . $e->getMessage());

            // Optionally, you can return an error response or redirect to an error page
            return back()->with('error', 'An error occurred while saving the service. Please try again later.')->withInput();
        }
    }

    public function getVenuesBySelectedLocation(Request $request)
    {
        $location = $request->input('locations');

        if (!is_array($location) || empty($location)) {
            return response()->json([]);
        }

        $venues = Venue::whereIn('postal_town', $location)->get();
        return response()->json($venues);
    }
}

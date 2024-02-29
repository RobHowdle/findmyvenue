<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Venue;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getVenues()
    {
        $venueCount = Venue::whereNull('deleted_at')->count();
        $locationCount = Venue::whereNull('deleted_at')->distinct('location')->count();

        $genreList = file_get_contents(storage_path('app/public/text/genre_list.json'));
        $data = json_decode($genreList, true);

        $genres = $data['genres'];

        return view('admin.venues', compact('venueCount', 'locationCount', 'genres'));
    }

    public function saveNewVenue(Request $request)
    {
        $formData = $request->validate([
            'floating_name' => 'required|string',
            'address-input' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'floating_capacity' => 'required|numeric',
            'floating_in_house_gear' => 'required|string',
            'floating_band_type' => 'required',
            'genres' => 'required|array',
            'floating_contact_name' => 'required|string',
            'floating_contact_number' => 'nullable|numeric|digits:11',
            'floating_contact_email' => 'nullable|email',
            'floating_contact_links' => 'nullable|url',
        ]);

        $newVenue = Venue::create([
            'name' => $formData['floating_name'],
            'location' => $formData['address-input'],
            'longitude' => $formData['latitude'],
            'latitude' => $formData['longitude'],
            'capacity' => $formData['floating_capacity'],
            'in_house_gear' => $formData['floating_in_house_gear'],
            'band_type' => $formData['floating_band_type'],
            'genre' => json_encode($formData['genres']),
            'contact_name' => $formData['floating_contact_name'],
            'contact_number' => $formData['floating_contact_number'],
            'contact_email' => $formData['floating_contact_email'],
            'contact_link' => $formData['floating_contact_links'],
        ]);

        // $newVenue = new Venue;
        // $newVenue->name = $formData['floating_name'];
        // $newVenue->location = $formData['address-input'];
        // $newVenue->longitude = $formData['latitude'];
        // $newVenue->latitude = $formData['longitude'];
        // $newVenue->capacity = $formData['floating_capacity'];
        // $newVenue->in_house_gear = $formData['floating_in_house_gear'];
        // $newVenue->band_type = $formData['floating_band_type'];
        // $newVenue->genre = $formData['genres'];
        // $newVenue->contact_name = $formData['floating_contact_name'];
        // $newVenue->contact_number = $formData['floating_contact_number'];
        // $newVenue->contact_email = $formData['floating_contact_email'];
        // $newVenue->contact_link = $formData['floating_contact_links'];
        // $newVenue->save();

        return back();
    }
}
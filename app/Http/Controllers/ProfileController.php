<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venue;
use App\Models\Promoter;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\OtherService;
use Illuminate\Http\Request;
use App\Models\UserModuleSetting;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\BandProfileUpdateRequest;
use App\Http\Requests\VenueProfileUpdateRequest;
use App\Http\Requests\PromoterProfileUpdateRequest;

class ProfileController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }

    /**
     * Display the user's profile form.
     */
    public function edit($dashboardType, $user): View
    {
        $modules = collect(session('modules', []));
        $user = User::where('id', $user)->first();
        $roles = Role::where('name', '!=', 'administrator')->get();
        $userRole = $user->roles;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $email = $user->email;
        $location = $user->location;

        // Initialize promoter variables
        $promoterData = [];
        $bandData = [];
        $venueData = [];

        // Check if the dashboardType is 'promoter' and get promoter data
        if ($dashboardType === 'promoter') {
            $promoterData = $this->getPromoterData($user);
        } elseif ($dashboardType === 'band') {
            $bandData = $this->getBandData($user);
        } elseif ($dashboardType === 'venue') {
            $venueData = $this->getVenueData($user);
        }

        // Load the modules configuration
        $modules = collect(config('modules.modules'))->map(function ($module) {
            $module['is_enabled'] = $module['enabled'] ?? false; // Map 'enabled' to 'is_enabled'
            return $module;
        })->toArray();

        // Prepare an array to store the modules with their settings
        $modulesWithSettings = [];

        foreach ($modules as $key => $module) {
            // Include only the enabled modules
            $modulesWithSettings[$key] = [
                'name' => $module['name'],
                'description' => $module['description'],
                'is_enabled' => $module['is_enabled'],
            ];
        }

        $modulesWithSettings = $this->getModulesWithSettings($user, $dashboardType);
        $communicationSettings = $this->getCommunicationSettings($user, $dashboardType);

        return view('profile.edit', [
            'userId' => $this->getUserId(),
            'dashboardType' => $dashboardType,
            'modules' => $modules,
            'promoterData' => $promoterData,
            'bandData' => $bandData,
            'venueData' => $venueData,
            'user' => $user,
            'roles' => $roles,
            'userRole' => $userRole,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'location' => $location,
            'modules' => $modulesWithSettings,
            'communications' => $communicationSettings,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update($dashboardType, ProfileUpdateRequest $request, $userId): RedirectResponse
    {
        $user = User::findOrFail($userId);
        $userData = $request->validated();

        if (isset($userData['firstName']) || isset($userData['lastName'])) {
            $user->first_name = $userData['firstName'];
            $user->last_name = $userData['lastName'];
        }

        if (isset($userData['email'])) {
            $user->email = $userData['email'];
        }

        if (isset($userData['latitude']) && isset($userData['longitude'])) {
            $user->latitude = $userData['latitude'];
            $user->longitude = $userData['longitude'];
        }

        if (isset($userData['location'])) {
            $user->location = $userData['location'];
        }

        if ($request->has('role') && $user->hasRole($request->role)) {
            $user->syncRoles([$request->role]);
        }

        $user->fill($userData);

        $user->save();

        return redirect()->route('profile.edit', ['dashboardType' => $dashboardType, 'id' => $user->id])->with('status', 'profile-updated');
    }

    public function updatePromoter($dashboardType, PromoterProfileUpdateRequest $request, $user)
    {
        // Fetch the user
        $user = User::findOrFail($user);
        $userId = $user->id;
        $userData = $request->validated();

        if ($dashboardType == 'promoter') {
            // Fetch the promoter associated with the user via the service_user pivot table
            $promoter = Promoter::whereHas('linkedUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();

            // Check if the promoter exists
            if ($promoter) {
                // Promoter Name
                if (isset($userData['name']) && $promoter->name !== $userData['name']) {
                    $promoter->update(['name' => $userData['name']]);
                }
                // Contact Name
                if (isset($userData['contact_name']) && $promoter->contact_name !== $userData['contact_name']) {
                    $promoter->update(['contact_name' => $userData['contact_name']]);
                }
                // Location


                // Contact Email
                if (isset($userData['contact_email']) && $promoter->contact_email !== $userData['contact_email']) {
                    $promoter->update(['contact_email' => $userData['contact_email']]);
                }

                // Contact Number 
                if (isset($userData['contact_number']) && $promoter->contact_number !== $userData['contact_number']) {
                    $promoter->update(['contact_number' => $userData['contact_number']]);
                }

                // Contact Links
                if (isset($userData['contact_links']) && is_array($userData['contact_links'])) {
                    // Start with the existing `contact_links` array or an empty array if it doesn't exist
                    $updatedLinks = !empty($promoter->contact_link) ? json_decode($promoter->contact_link, true) : [];

                    // Iterate through the `contact_link` array from the request data
                    foreach ($userData['contact_links'] as $platform => $links) {
                        // Ensure we're setting only non-empty values
                        $updatedLinks[$platform] = !empty($links[0]) ? $links[0] : null;
                    }

                    // Filter out null values to remove platforms with no links
                    $updatedLinks = array_filter($updatedLinks);

                    // Encode the array back to JSON for storage and update the promoter record
                    $promoter->update(['contact_link' => json_encode($updatedLinks)]);
                }

                // About
                if (isset($userData['about']) && $promoter->description !== $userData['about']) {
                    $promoter->update(['description' => $userData['about']]);
                }

                // My Venues
                if (isset($userData['myVenues']) && $promoter->my_venues !== $userData['myVenues']) {
                    $promoter->update(['my_venues' => $userData['myVenues']]);
                }

                // Genres
                if (isset($userData['genres'])) {
                    $storedGenres = json_decode($promoter->genre, true);
                    if ($storedGenres !== $userData['genres']) {
                        $promoter->update(['genre' => json_encode($userData['genres'])]);
                    }
                }

                // Logo
                if (isset($userData['logo'])) {
                    $promoterLogoFile = $userData['logo'];

                    // Generate the file name
                    $promoterName = $request->input('name');
                    $promoterLogoExtension = $promoterLogoFile->getClientOriginalExtension() ?: $promoterLogoFile->guessExtension();
                    $promoterLogoFilename = Str::slug($promoterName) . '.' . $promoterLogoExtension;

                    // Store the file
                    // $promoterLogoFile->storeAs('public/images/promoters_logos', $promoterLogoFilename);
                    $promoterLogoFile->move(storage_path('app/public/images/promoters_logos'), $promoterLogoFilename);


                    // Log file path

                    // Get the URL to the file
                    $logoUrl = Storage::url('images/promoters_logos/' . $promoterLogoFilename);

                    // Update database
                    $promoter->update(['logo_url' => $logoUrl]);
                }


                // Return success message with redirect
                return redirect()->route('profile.edit', ['dashboardType' => $dashboardType, 'id' => $user->id])->with('status', 'profile-updated');
            } else {
                // Handle case where no promoter is linked to the user
                return response()->json(['error' => 'Promoter not found'], 404);
            }
        }
    }

    public function updateVenue($dashboardType, VenueProfileUpdateRequest $request, $user)
    {
        // Fetch the user
        $user = User::findOrFail($user);
        $userId = $user->id;
        $userData = $request->validated();

        if ($dashboardType == 'venue') {
            // Fetch the promoter associated with the user via the service_user pivot table
            $venue = Venue::whereHas('linkedUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();

            // Check if the promoter exists
            if ($venue) {
                // Promoter Name
                if (isset($userData['name']) && $venue->name !== $userData['name']) {
                    $venue->update(['name' => $userData['name']]);
                }
                // Contact Name
                if (isset($userData['contact_name']) && $venue->contact_name !== $userData['contact_name']) {
                    $venue->update(['contact_name' => $userData['contact_name']]);
                }
                // Location


                // Contact Email
                if (isset($userData['contact_email']) && $venue->contact_email !== $userData['contact_email']) {
                    $venue->update(['contact_email' => $userData['contact_email']]);
                }

                // Contact Number 
                if (isset($userData['contact_number']) && $venue->contact_number !== $userData['contact_number']) {
                    $venue->update(['contact_number' => $userData['contact_number']]);
                }

                // Contact Links
                if (isset($userData['contact_links']) && is_array($userData['contact_links'])) {
                    // Start with the existing `contact_links` array or an empty array if it doesn't exist
                    $updatedLinks = !empty($venue->contact_link) ? json_decode($venue->contact_link, true) : [];

                    // Iterate through the `contact_link` array from the request data
                    foreach ($userData['contact_links'] as $platform => $links) {
                        // Ensure we're setting only non-empty values
                        $updatedLinks[$platform] = !empty($links[0]) ? $links[0] : null;
                    }

                    // Filter out null values to remove platforms with no links
                    $updatedLinks = array_filter($updatedLinks);

                    // Encode the array back to JSON for storage and update the promoter record
                    $venue->update(['contact_link' => json_encode($updatedLinks)]);
                }

                // About
                if (isset($userData['about']) && $venue->description !== $userData['about']) {
                    $venue->update(['description' => $userData['about']]);
                }

                // My Venues
                if (isset($userData['myVenues']) && $venue->my_venues !== $userData['myVenues']) {
                    $venue->update(['my_venues' => $userData['myVenues']]);
                }

                // In House Gear
                if (isset($userData['inHouseGear']) && $venue->in_house_gear !== $userData['inHouseGear']) {
                    $venue->update(['in_house_gear' => $userData['inHouseGear']]);
                }

                // Genres
                if (isset($userData['genres'])) {
                    $storedGenres = json_decode($venue->genre, true);
                    if ($storedGenres !== $userData['genres']) {
                        $venue->update(['genre' => json_encode($userData['genres'])]);
                    }
                }

                // Logo
                if (isset($userData['logo'])) {
                    $venueLogoFile = $userData['logo'];

                    // Generate the file name
                    $venueName = $request->input('name');
                    $venueLogoExtension = $venueLogoFile->getClientOriginalExtension() ?: $venueLogoFile->guessExtension();
                    $venueLogoFilename = Str::slug($venueName) . '.' . $venueLogoExtension;

                    // Store the file
                    $venueLogoFile->move(storage_path('app/public/images/venue_logos'), $venueLogoFilename);

                    // Get the URL to the file
                    $logoUrl = Storage::url('images/venue_logos/' . $venueLogoFilename);

                    // Update database
                    $venue->update(['logo_url' => $logoUrl]);
                }

                // Capacity
                if (isset($userData['capacity'])) {
                    $venue->update(['capacity' => $userData['capacity']]);
                }


                // Return success message with redirect
                return redirect()->route('profile.edit', ['dashboardType' => $dashboardType, 'id' => $user->id])->with('status', 'profile-updated');
            } else {
                // Handle case where no promoter is linked to the user
                return response()->json(['error' => 'Venue not found'], 404);
            }
        }
    }

    public function updateBand($dashboardType, BandProfileUpdateRequest $request, $user)
    {
        // Fetch the user
        $user = User::findOrFail($user);
        $userId = $user->id;
        $userData = $request->validated();

        if ($dashboardType == 'band') {
            // Fetch the promoter associated with the user via the service_user pivot table
            $band = OtherService::where('other_service_id', 4)->whereHas('linkedUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();

            // Check if the promoter exists
            if ($band) {
                // Promoter Name
                if (isset($userData['name']) && $band->name !== $userData['name']) {
                    $band->update(['name' => $userData['name']]);
                }
                // Contact Name
                if (isset($userData['contact_name']) && $band->contact_name !== $userData['contact_name']) {
                    $band->update(['contact_name' => $userData['contact_name']]);
                }
                // Location


                // Contact Email
                if (isset($userData['contact_email']) && $band->contact_email !== $userData['contact_email']) {
                    $band->update(['contact_email' => $userData['contact_email']]);
                }

                // Contact Number 
                if (isset($userData['contact_number']) && $band->contact_number !== $userData['contact_number']) {
                    $band->update(['contact_number' => $userData['contact_number']]);
                }

                // Contact Links
                if (isset($userData['contact_links']) && is_array($userData['contact_links'])) {
                    // Start with the existing `contact_links` array or an empty array if it doesn't exist
                    $updatedLinks = !empty($band->contact_link) ? json_decode($band->contact_link, true) : [];

                    // Iterate through the `contact_link` array from the request data
                    foreach ($userData['contact_links'] as $platform => $links) {
                        // Ensure we're setting only non-empty values
                        $updatedLinks[$platform] = !empty($links[0]) ? $links[0] : null;
                    }

                    // Filter out null values to remove platforms with no links
                    $updatedLinks = array_filter($updatedLinks);

                    // Encode the array back to JSON for storage and update the promoter record
                    $band->update(['contact_link' => json_encode($updatedLinks)]);
                }

                // Stream Links
                if (isset($userData['stream_links'])) {
                    $storedStreamLinks = json_decode($band->stream_urls, true);
                    if ($storedStreamLinks !== $userData['stream_links']) {
                        $band->update(['stream_urls' => json_encode($userData['stream_links'])]);
                    }
                }

                // About
                if (isset($userData['about']) && $band->description !== $userData['about']) {
                    $band->update(['description' => $userData['about']]);
                }

                // Members
                if (isset($userData['members'])) {
                    $storedMembers = json_decode($band->members, true);
                    if ($storedMembers !== $userData['members']) {
                        $band->update(['members' => json_encode($userData['members'])]);
                    }
                }

                // Genres
                if (isset($userData['genres'])) {
                    $storedGenres = json_decode($band->genre, true);
                    if ($storedGenres !== $userData['genres']) {
                        $band->update(['genre' => json_encode($userData['genres'])]);
                    }
                }

                // Logo
                if (isset($userData['logo_url'])) {
                    $bandLogoFile = $userData['logo_url'];

                    // Generate the file name
                    $bandName = $request->input('name');
                    $bandLogoExtension = $bandLogoFile->getClientOriginalExtension() ?: $bandLogoFile->guessExtension();
                    $bandLogoFilename = Str::slug($bandName) . '.' . $bandLogoExtension;

                    // Store the file
                    $bandLogoFile->move(storage_path('app/public/images/band_logos'), $bandLogoFilename);

                    // Get the URL to the file
                    $logoUrl = Storage::url('images/band_logos/' . $bandLogoFilename);

                    // Update database
                    $band->update(['logo_url' => $logoUrl]);
                }

                // Portfolio Link
                if (isset($userData['portfolio_link'])) {
                    $band->update(['portfolio_link' => $userData['portfolio_link']]);
                }

                // Services
                if (isset($userData['services'])) {
                    $band->update(['services' => $userData['services']]);
                }

                // Return success message with redirect
                return redirect()->route('profile.edit', ['dashboardType' => $dashboardType, 'id' => $user->id])->with('status', 'profile-updated');
            } else {
                // Handle case where no promoter is linked to the user
                return response()->json(['error' => 'Venue not found'], 404);
            }
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    protected function getModulesWithSettings($user, $dashboardType)
    {
        // Load all modules from config
        $modules = config('modules.modules');

        // Get user-specific enabled modules from the session
        $userModules = collect(session('modules', [])); // This should contain user's active modules
        $modulesWithSettings = [];

        foreach ($modules as $moduleKey => $module) {
            // Check if the user has this module enabled
            $isEnabled = $userModules->has($moduleKey) && $userModules->get($moduleKey)['is_enabled'] ?? false;

            // Add the module to the settings array
            $modulesWithSettings[$module['name']] = [
                'description' => $module['description'], // Include the description
                'is_enabled' => $isEnabled, // Directly set the enabled status
            ];
        }

        return $modulesWithSettings;
    }

    public function updateModule(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'module' => 'required|string',
            'is_enabled' => 'required|boolean',
        ]);

        // Update the module settings in the database
        $module = UserModuleSetting::where('module_name', $request->module)->first();

        if ($module) {
            $module->is_enabled = $request->is_enabled;
            $module->save();

            return response()->json(['success' => true, 'message' => 'Module updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Module not found.'], 404);
    }

    private function getPromoterData(User $user)
    {
        $promoter = $user->promoters()->first();

        $name = $promoter ? $promoter->name : '';
        $location = $promoter ? $promoter->location : '';
        $logo = $promoter && $promoter->logo_url
            ? (filter_var($promoter->logo_url, FILTER_VALIDATE_URL) ? $promoter->logo_url : Storage::url($promoter->logo_url))
            : asset('images/system/yns_no_image_found.png');


        $contact_number = $promoter ? $promoter->contact_number : '';
        $contact_email = $promoter ? $promoter->contact_email : '';
        $contactLinks = $promoter ? json_decode($promoter->contact_link, true) : [];
        $contact_name = $promoter ? $promoter->contact_name : '';

        $platforms = [];
        $platformsToCheck = ['facebook', 'twitter', 'instagram', 'snapchat', 'tiktok', 'youtube'];

        // Initialize the platforms array with empty strings for each platform
        foreach ($platformsToCheck as $platform) {
            $platforms[$platform] = '';  // Set default to empty string
        }

        // Check if the contactLinks array exists and contains social links
        if ($contactLinks) {
            foreach ($platformsToCheck as $platform) {
                // Only add the link if the platform exists in the $contactLinks array
                if (isset($contactLinks[$platform])) {
                    $platforms[$platform] = $contactLinks[$platform];  // Store the link for the platform
                }
            }
        }

        $about = $promoter ? $promoter->description : '';
        $myVenues = $promoter ? $promoter->my_venues : '';
        $myEvents = $promoter ? $promoter->events()->with('venues')->get() : collect();
        $uniqueBands = $this->getUniqueBandsForPromoterEvents($promoter->id);
        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];
        $promoterGenres = is_array($promoter->genre) ? $promoter->genre : json_decode($promoter->genre, true);

        return [
            'promoter' => $promoter,
            'name' => $name,
            'location' => $location,
            'logo' => $logo,
            'contact_number' => $contact_number,
            'platforms' => $platforms,
            'platformsToCheck' => $platformsToCheck,
            'about' => $about,
            'myVenues' => $myVenues,
            'myEvents' => $myEvents,
            'contact_email' => $contact_email,
            'contact_name' => $contact_name,
            'uniqueBands' => $uniqueBands,
            'genres' => $genres,
            'promoterGenres' => $promoterGenres,
        ];
    }

    private function getVenueData(User $user)
    {
        $venue = $user->venues()->first();

        $name = $venue ? $venue->name : '';
        $location = $venue ? $venue->location : '';
        $logo = $venue && $venue->logo_url
            ? (filter_var($venue->logo_url, FILTER_VALIDATE_URL) ? $venue->logo_url : Storage::url($venue->logo_url))
            : asset('images/system/yns_no_image_found.png');

        $capacity = $venue ? $venue->capacity : '';
        $contact_number = $venue ? $venue->contact_number : '';
        $contact_email = $venue ? $venue->contact_email : '';
        $contactLinks = $venue ? json_decode($venue->contact_link, true) : [];
        $contact_name = $venue ? $venue->contact_name : '';

        $platforms = [];
        $platformsToCheck = ['facebook', 'twitter', 'instagram', 'snapchat', 'tiktok', 'youtube'];

        // Initialize the platforms array with empty strings for each platform
        foreach ($platformsToCheck as $platform) {
            $platforms[$platform] = '';  // Set default to empty string
        }

        // Check if the contactLinks array exists and contains social links
        if ($contactLinks) {
            foreach ($platformsToCheck as $platform) {
                // Only add the link if the platform exists in the $contactLinks array
                if (isset($contactLinks[$platform])) {
                    $platforms[$platform] = $contactLinks[$platform];  // Store the link for the platform
                }
            }
        }

        $about = $venue ? $venue->description : '';
        $inHouseGear = $venue ? $venue->in_house_gear : '';
        $myEvents = $venue ? $venue->events()->with('venues')->get() : collect();
        $uniqueBands = $this->getUniqueBandsForPromoterEvents($venue->id);
        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];
        $venueGenres = is_array($venue->genre) ? $venue->genre : json_decode($venue->genre, true);
        $additionalInfo = $venue ? $venue->additional_info : '';

        return [
            'venue' => $venue,
            'name' => $name,
            'location' => $location,
            'logo' => $logo,
            'contact_number' => $contact_number,
            'platforms' => $platforms,
            'platformsToCheck' => $platformsToCheck,
            'about' => $about,
            'inHouseGear' => $inHouseGear,
            'myEvents' => $myEvents,
            'contact_email' => $contact_email,
            'contact_name' => $contact_name,
            'uniqueBands' => $uniqueBands,
            'genres' => $genres,
            'venueGenres' => $venueGenres,
            'capacity' => $capacity,
            'additionalInfo' => $additionalInfo,
        ];
    }

    private function getBandData(User $user)
    {
        $band = $user->otherService("Band")->first();

        $name = $band ? $band->name : '';
        $location = $band ? $band->location : '';
        $logo = $band ? $band->logo_url : 'images/system/yns_logo.png';
        $phone = $band ? $band->contact_number : '';
        $contact_name = $band ? $band->contact_name : '';
        $contact_email = $band ? $band->contact_email : '';
        $contact_number = $band ? $band->contact_number : '';
        $contactLinks = $band ? json_decode($band->contact_link, true) : [];

        $platforms = [];
        $platformsToCheck = ['website', 'facebook', 'twitter', 'instagram', 'snapchat', 'tiktok', 'youtube'];

        // Initialize the platforms array with empty strings for each platform
        foreach ($platformsToCheck as $platform) {
            $platforms[$platform] = '';  // Set default to empty string
        }

        // Check if the contactLinks array exists and contains social links
        if ($contactLinks) {
            foreach ($platformsToCheck as $platform) {
                // Only add the link if the platform exists in the $contactLinks array
                if (isset($contactLinks[$platform])) {
                    $platforms[$platform] = $contactLinks[$platform];  // Store the link for the platform
                }
            }
        }

        $about = $band ? $band->description : '';
        $myEvents = $band ? $band->events()->with('venues')->get() : collect();
        $genreList = file_get_contents(public_path('text/genre_list.json'));
        $data = json_decode($genreList, true);
        $genres = $data['genres'];
        $bandGenres = is_array($band->genre) ? $band->genre : json_decode($band->genre, true);
        $streamLinks = is_array($band->genre) ? $band->stream_urls : json_decode($band->stream_urls, true);

        $streamPlatforms = [];
        $streamPlatformsToCheck = ['spotify', 'apple-music', 'youtube-music', 'amazon-music', 'bandcamp', 'soundcloud'];

        foreach ($streamPlatformsToCheck as $streamPlatform) {
            $streamPlatforms[$streamPlatform] = '';
        }

        if ($streamLinks) {
            foreach ($streamPlatformsToCheck as $streamPlatform) {
                if (isset($streamLinks[$streamPlatform])) {
                    $streamPlatforms[$streamPlatform] = $streamLinks[$streamPlatform];
                };
            };
        }

        $members = is_array($band->members) ? $band->members : json_decode($band->members, true);

        return [
            'band' => $band,
            'name' => $name,
            'location' => $location,
            'logo' => $logo,
            'phone' => $phone,
            'about' => $about,
            'myEvents' => $myEvents,
            'contact_name' => $contact_name,
            'contact_email' => $contact_email,
            'contact_number' => $contact_number,
            'platforms' => $platforms,
            'platformsToCheck' => $platformsToCheck,
            'genres' => $genres,
            'bandGenres' => $bandGenres,
            'streamLinks' => $streamLinks,
            'streamPlatformsToCheck' => $streamPlatformsToCheck,
            'members' => $members
        ];
    }

    public function addRole(Request $request)
    {
        try {
            // Retrieve the user
            $user = User::findOrFail($request->id);
            // \Log::info('User found: ', [$user]);

            // Validate the incoming request
            $request->validate([
                'roleId' => 'required|exists:roles,id', // Ensure roleId is valid
            ]);

            // Retrieve the role
            $role = Role::find($request->roleId);
            // \Log::info('Role found: ', [$role]);

            if (!$role) {
                return response()->json(['success' => false, 'message' => 'Role not found.'], 404);
            }

            // Check if the user already has the role
            if ($user->hasRole($role->name)) {
                return response()->json(['success' => false, 'message' => 'User already has this role.'], 400);
            }

            // Add the new role to the user
            $user->assignRole($role->name);  // This adds the role, doesn't replace existing roles

            // Return the response with success message and role name
            return response()->json([
                'success' => true,
                'message' => 'Role added successfully.',
                'newRoleName' => $role->name
            ]);
        } catch (\Exception $e) {
            // Log the error and return a response
            // \Log::error('Error adding role: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the role.'
            ], 500);
        }
    }

    public function deleteRole(Request $request)
    {
        try {
            // Retrieve the user
            $user = User::findOrFail($request->id);
            // \Log::info('User found: ', [$user]);

            // Validate the incoming request
            $request->validate([
                'roleId' => 'required|exists:roles,id', // Ensure roleId is valid
            ]);

            // Retrieve the role
            $role = Role::find($request->roleId);
            // \Log::info('Role found: ', [$role]);

            if (!$role) {
                return response()->json(['success' => false, 'message' => 'Role not found.'], 404);
            }

            // Check if the user has the role
            if (!$user->hasRole($role->name)) {
                return response()->json(['success' => false, 'message' => 'User does not have this role.'], 400);
            }

            // Remove the role from the user
            $user->removeRole($role->name);  // This removes the role from the user

            // Return the response with success message and role name
            return response()->json([
                'success' => true,
                'message' => 'Role removed successfully.',
                'removedRoleName' => $role->name
            ]);
        } catch (\Exception $e) {
            // Log the error and return a response
            // \Log::error('Error removing role: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing the role.'
            ], 500);
        }
    }

    // Communication Prefs
    protected function getCommunicationSettings($user, $dashboardType)
    {
        // Retrieve the user's mailing preferences (already decoded as array due to 'casts')
        $mailingPreferences = $user->mailing_preferences;

        // Define default preferences from config file
        $defaultPreferences = config('mailing_preferences.communication_preferences');

        // Merge the default preferences with the user's preferences (user preferences will override defaults)
        return array_merge($defaultPreferences, $mailingPreferences);
    }

    public function updatePreferences(Request $request)
    {
        $user = auth()->user();

        // Get the current mailing preferences, or set them to default if null
        $preferences = $user->mailing_preferences ?? [
            'system_announcements' => true,
            'legal_or_policy_updates' => true,
            'account_notifications' => true,
            'event_invitations' => true,
            'surveys_and_feedback' => true,
            'birthday_anniversary_holiday' => true,
        ];

        // Ensure the preferences are an array, even if the stored value is a string
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        // Update the specific preference sent in the request
        foreach ($request->all() as $key => $value) {
            if (array_key_exists($key, $preferences)) {
                $preferences[$key] = $value; // Update the preference with the new value (true or false)
            }
        }

        // Save the updated preferences (this will store the array as JSON due to the model's cast)
        $user->mailing_preferences = $preferences;
        $user->save();

        // Return a success message
        return response()->json(['message' => 'Preferences updated successfully.']);
    }

    /**
     * Get a unique list of bands linked to events associated with the given promoter.
     *
     * @param  int  $promoterId
     * @return \Illuminate\Support\Collection
     */
    private function getUniqueBandsForPromoterEvents($promoterId)
    {
        // Fetch unique bands linked to events that the promoter is associated with
        return OtherService::where('other_service_id', 4)
            ->whereHas('events', function ($query) use ($promoterId) {
                $query->whereHas('promoters', function ($q) use ($promoterId) {
                    $q->where('promoter_id', $promoterId);
                });
            })
            ->distinct()
            ->get();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Promoter;
use Illuminate\View\View;
use App\Models\OtherService;
use Illuminate\Http\Request;
use App\Models\UserModuleSetting;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
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

        // Check if the dashboardType is 'promoter' and get promoter data
        if ($dashboardType === 'promoter') {
            $promoterData = $this->getPromoterData($user);
        } elseif ($dashboardType === 'band') {
            $bandData = $this->getBandData($user);
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

        if (isset($userData['latitude']) && isset($userData['longitude'])) {
            // Assign correct latitude and longitude fields
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

    public function updatePromoter($dashboardType, PromoterProfileUpdateRequest $request, $userId)
    {
        \Log::info('User reached updatePromoter', ['user' => auth()->user()]);
        // Fetch the user
        $user = User::findOrFail($userId);
        $userData = $request->validated();

        if ($dashboardType == 'promoter') {
            // Fetch the promoter associated with the user via the service_user pivot table
            $promoter = Promoter::whereHas('linkedUsers', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->first();

            // If the promoter exists, update the fields
            if ($promoter) {
                // Update various fields for the promoter
                if (isset($userData['email']) && $promoter->contact_email !== $userData['email']) {
                    $promoter->update(['contact_email' => $userData['email']]);
                }

                // Update description, venues, genres
                if (isset($userData['about']) && $promoter->description !== $userData['about']) {
                    $promoter->update(['description' => $userData['about']]);
                }

                if (isset($userData['myVenues']) && $promoter->my_venues !== $userData['myVenues']) {
                    $promoter->update(['my_venues' => $userData['myVenues']]);
                }

                if (isset($userData['genres']) && $promoter->genre !== json_encode($userData['genres'])) {
                    $promoter->update(['genre' => json_encode($userData['genres'])]);
                }

                // Handle contact links
                \Log::info($userData['contact_links']);

                if (isset($userData['contact_links']) && is_array($userData['contact_links'])) {
                    // Initialize the contact_links array if it's empty or doesn't exist
                    if (!isset($user->contact_links)) {
                        $user->contact_links = [];
                    }

                    // Update the user's contact_links array for each platform with the new link
                    foreach ($userData['contact_links'] as $platform => $links) {
                        // Ensure we are working with an array of links (in case there are multiple links for each platform)
                        if (is_array($links)) {
                            foreach ($links as $index => $link) {
                                $user->contact_links[$platform][$index] = $link;  // Update multiple links for the same platform
                            }
                        } else {
                            // If there's just a single link, ensure it gets stored as an array
                            $user->contact_links[$platform] = [$links];
                        }
                    }

                    // Save the updated contact links directly to the user model as an associative array
                    $user->update(['contact_links' => $user->contact_links]);
                    return redirect()->route('profile.edit', ['dashboardType' => $dashboardType, 'id' => $user->id])->with('status', 'profile-updated');
                }
            } else {
                // Handle case where no promoter is linked to the user
                return response()->json(['error' => 'Promoter not found'], 404);
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

        $promoterName = $promoter ? $promoter->name : '';
        $location = $promoter ? $promoter->location : '';
        $logo = $promoter && $promoter->logo_url ? asset('storage/' . $promoter->logo_url) : asset('images/system/yns_no_image_found.png');
        $phone = $promoter ? $promoter->contact_number : '';
        $contact_email = $promoter ? $promoter->contact_email : '';
        $contactLinks = $promoter ? $promoter->contact_link : [];

        $contactName = $promoter ? $promoter->contact_name : '';

        if ($contactLinks) {
            $contactLinks = json_decode($promoter->contact_link, true);
        }
        $platforms = [];
        $platformsToCheck = ['facebook', 'twitter', 'instagram', 'snapchat', 'tiktok', 'youtube'];

        // Initialize the platforms array with empty strings for each platform
        foreach ($platformsToCheck as $platform) {
            $platforms[$platform] = [];
        }

        // Check if the contactLinks array exists and contains social links
        if (isset($contactLinks['social_links']) && is_array($contactLinks['social_links'])) {
            foreach ($contactLinks['social_links'] as $platform => $link) {
                // Only add the link if the platform is one we want to check
                if (array_key_exists($platform, $platforms)) {
                    $platforms[$platform] = $link;  // Store the link in an array for each platform
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
        $promoterGenres = is_array($promoter->genre) ? $promoter->genre : explode(',', $promoter->genre);

        return [
            'promoter' => $promoter,
            'promoterName' => $promoterName,
            'location' => $location,
            'logo' => $logo,
            'phone' => $phone,
            'platforms' => $platforms,
            'platformsToCheck' => $platformsToCheck,
            'about' => $about,
            'myVenues' => $myVenues,
            'myEvents' => $myEvents,
            'contact_email' => $contact_email,
            'contactName' => $contactName,
            'uniqueBands' => $uniqueBands,
            'genres' => $genres,
            'promoterGenres' => $promoterGenres,
        ];
    }

    private function getBandData(User $user)
    {
        $band = $user->otherService("Band")->first();

        $bandName = $band ? $band->name : '';
        $location = $band ? $band->location : '';
        $logo = $band ? $band->logo_url : 'images/system/yns_logo.png';
        $phone = $band ? $band->contact_number : '';
        $email = $band ? $band->contact_email : '';
        $contactLinks = $band ? $band->contact_link : [];

        if ($contactLinks) {
            $contactLinks = json_decode($band->contact_link, true);
        }

        $platforms = [];
        $platformsToCheck = ['facebook', 'twitter', 'instagram', 'snapchat', 'tiktok', 'youtube'];

        foreach ($platformsToCheck as $platform) {
            $platforms[$platform] = [];
        }

        if (is_array($contactLinks)) {
            foreach ($contactLinks as $platform => $links) {
                if (array_key_exists($platform, $platforms)) {
                    $platforms[$platform] = array_merge($platforms[$platform], $links);
                }
            }
        }

        $about = $band ? $band->description : '';
        $myVenues = $band ? $band->my_venues : '';

        return [
            'band' => $band,
            'bandName' => $bandName,
            'location' => $location,
            'logo' => $logo,
            'phone' => $phone,
            'platforms' => $platforms,
            'about' => $about,
            'myVenues' => $myVenues,
            'email' => $email
        ];
    }

    public function addRole(Request $request)
    {
        try {
            // Retrieve the user
            $user = User::findOrFail($request->id);
            \Log::info('User found: ', [$user]);

            // Validate the incoming request
            $request->validate([
                'roleId' => 'required|exists:roles,id', // Ensure roleId is valid
            ]);

            // Retrieve the role
            $role = Role::find($request->roleId);
            \Log::info('Role found: ', [$role]);

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
            \Log::error('Error adding role: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding the role.'
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

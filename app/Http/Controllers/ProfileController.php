<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\UserModuleSetting;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

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
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update($dashboardType, ProfileUpdateRequest $request, $userId): RedirectResponse
    {
        \Log::info("Request's latitude: {$request->latitude}, Request's longitude: {$request->longitude}");

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

        $user->fill($userData);

        if ($request->has('role') && $user->hasRole($request->role)) {
            $user->syncRoles([$request->role]);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit', ['dashboardType' => $dashboardType, 'id' => $user->id])->with('status', 'profile-updated');
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
        $logo = $promoter ? $promoter->logo_url : 'images/system/yns_logo.png';
        $phone = $promoter ? $promoter->contact_number : '';
        $email = $promoter ? $promoter->contact_email : '';
        $contactLinks = $promoter ? $promoter->contact_link : [];

        if ($contactLinks) {
            $contactLinks = json_decode($promoter->contact_link, true);
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

        $about = $promoter ? $promoter->description : '';
        $myVenues = $promoter ? $promoter->my_venues : '';

        return [
            'promoter' => $promoter,
            'promoterName' => $promoterName,
            'location' => $location,
            'logo' => $logo,
            'phone' => $phone,
            'platforms' => $platforms,
            'about' => $about,
            'myVenues' => $myVenues,
            'email' => $email
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
}

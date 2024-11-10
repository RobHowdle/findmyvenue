<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Models\UserModuleSetting;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Config;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\RegisterUserRequest;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::where('name', '!=', 'administrator')->get();
        return view('auth.register', ['roles' => $roles]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        $adminRoleId = Role::where('name', 'administrator')->pluck('id')->first();

        // Check if the selected role is not an administrator
        if ($request->has('role') && $adminRoleId) {
            $selectedRole = $request->input('role');

            if ($selectedRole != $adminRoleId) {
                try {
                    $user = User::create([
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'date_of_birth' => $request->date_of_birth,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                    ]);

                    $requestedRoleId = $request->role;
                    $role = Role::findOrFail($request->role);
                    Log::info('Requested Role:', ['role_id' => $requestedRoleId, 'role_name' => $role->name]);

                    $user->assignRole($role->name);
                    Log::info('Assigned Role:', ['user_id' => $user->id, 'role_id' => $role->id, 'role_name' => $role->name]);

                    // Set Default Modules based on Role
                    $this->setDefaultModules($user, $role->name);
                    // Set default mailing preferences
                    $this->setDefaultMailingPreferences($user);

                    event(new Registered($user));

                    Auth::login($user);

                    // Success response
                    if ($request->wantsJson()) {
                        return response()->json(['success' => true, 'message' => 'Registration successful!'], 200);
                    }

                    session()->flash('success', 'Registration successful!');
                    return redirect(RouteServiceProvider::HOME);
                } catch (\Exception $e) {
                    Log::error('Registration failed:', [
                        'message' => $e->getMessage(),
                        'user_data' => [
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'email' => $request->email,
                        ],
                        'stack' => $e->getTraceAsString(),
                    ]);

                    // Error response
                    if ($request->wantsJson()) {
                        return response()->json(['success' => false, 'message' => 'Registration failed. Please try again.'], 500);
                    }

                    return back()->withInput()->withErrors(['general' => 'Registration failed. Please try again.']);
                }
            } else {
                $ipAddress = $request->ip();
                Log::error('User attempted to register with an admin role', ['ip_address' => $ipAddress]);

                // Error response
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'You cannot register as this role.'], 403);
                }

                return back()->withInput()->withErrors(['role' => 'You cannot register as this role.']);
            }
        }

        // Error response for missing role
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Role selection is required.'], 422);
        }

        return back()->withInput()->withErrors(['role' => 'Role selection is required.']);
    }

    protected function setDefaultModules($user, $roleName)
    {
        Log::info('Setting default modules for user', ['user_id' => $user->id, 'role' => $roleName]);

        // Define all available modules
        $allModules = ['events', 'todo_list', 'notes', 'finances', 'documents', 'users', 'reviews'];

        // Define default modules based on the user role
        $defaultModules = [];
        $serviceableType = '';
        $role = Role::where('name', $roleName)->first();

        switch ($role->name) {
            case "standard": // Standard User
                $defaultModules = []; // No default modules
                break;

            case "venue":
            case "promoter":
            case "band":
                $defaultModules = $allModules; // All modules for these roles
                $serviceableType = 'App\Models\\' . ucfirst($role->name);
                break;

            case "photographer":
            case "designer":
            case "videographer":
                $defaultModules = ['todo_list', 'notes', 'finances', 'documents', 'reviews'];
                $serviceableType = 'App\Models\OtherService';
                break;

            case "administrator":
                $defaultModules = $allModules; // All modules for administrators
                break;
        }

        // Create module settings for all modules, enabling only default ones
        foreach ($allModules as $module) {
            try {
                $defaultSettings = UserModuleSetting::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'serviceable_id' => $role ? $role->id : null,
                        'serviceable_type' => $serviceableType,
                        'module_name' => $module,
                    ],
                    [
                        'is_enabled' => in_array($module, $defaultModules), // Enable if in default modules
                    ]
                );

                Log::info('UserModuleSetting processed', [
                    'user_id' => $user->id,
                    'module_name' => $module,
                    'is_enabled' => $defaultSettings->is_enabled,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create UserModuleSetting', [
                    'user_id' => $user->id,
                    'module_name' => $module,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function setDefaultMailingPreferences($user)
    {
        // Retrieve the default communication preferences from the config file
        $defaultPreferences = Config::get('mailing_preferences.communication_preferences');

        // Set all preferences to true (enabled)
        $preferences = [];
        foreach ($defaultPreferences as $preferenceKey => $preference) {
            $preferences[$preferenceKey] = true; // Default all preferences to true
        }

        // Store the preferences as an array (Laravel will handle the JSON encoding automatically)
        $user->mailing_preferences = $preferences;
        $user->save();

        // Optionally, you can return a success response
        return response()->json([
            'message' => 'Default mailing preferences set successfully.'
        ]);
    }
}

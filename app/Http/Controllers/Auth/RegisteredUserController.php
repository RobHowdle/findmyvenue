<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
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
}

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
    public function store(Request $request): RedirectResponse
    {
        $admin = Role::where('name', '=', 'administrator')->get();

        // Check if the request contains a role that is not an administrator
        if ($request->has('role') && $admin->isNotEmpty()) {
            $selectedRole = $request->input('role');

            // Check if the selected role matches the administrator role by name or id
            if (!$admin->contains('name', $selectedRole) && !$admin->contains('id', $selectedRole)) {
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                    'password' => ['required', 'confirmed', Rules\Password::defaults()],
                    'role' => ['required', 'exists:App\Models\Role,id'],
                ]);

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                $role = Role::findOrFail($request->role);
                $user->assignRole($role->name);
                event(new Registered($user));

                Auth::login($user);

                return redirect(RouteServiceProvider::HOME);
            } else {
                $ipAddress = $request->ip();
                Log::error('A user has attempted to register with a dedicate admin role, please investigate', ['ip_address' => $ipAddress]);
                return back()->withInput()->withErrors(['role' => 'There has been an error. Please try again later.']);
            }
        }
    }
}

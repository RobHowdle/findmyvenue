<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(User $user): View
    {
        $roles = Role::where('name', '!=', 'administrator')->get();
        $userRole = $user->roles;
        $name = $user->name;
        $email = $user->email;

        return view('profile.edit', compact('user', 'roles', 'userRole', 'name', 'email'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, $userId): RedirectResponse
    {
        $user = User::findOrFail($userId);
        $userData = $request->validated();

        $user->fill($userData);

        if ($request->has('role') && $user->hasRole($request->role)) {
            $user->syncRoles([$request->role]);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        dd();

        $user->save();

        return redirect()->route('profile.edit', $user->id)->with('status', 'profile-updated');
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
}

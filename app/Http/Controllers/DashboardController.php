<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('dashboard', compact('users'));
    }

    public function editUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        dd($user);
    }
}

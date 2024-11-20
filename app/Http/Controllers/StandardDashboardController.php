<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StandardDashboardController extends Controller
{
    protected function getUserId()
    {
        return Auth::id();
    }
}

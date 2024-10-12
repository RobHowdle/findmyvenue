<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Greeting extends Component
{
    public $greeting;
    public $userName;
    public $promoterCompany;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $now = now();
        $hour = $now->hour;

        // Define greeting messages
        if ($hour >= 5 && $hour < 12) {
            $this->greeting = 'Good Morning';
        } elseif ($hour >= 12 && $hour < 18) {
            $this->greeting = 'Good Afternoon';
        } else {
            $this->greeting = 'Good Evening';
        }

        $this->userName = Auth::check() ? Auth::user()->name : 'User';

        // Retrieve the promoter company name
        if (Auth::check()) {
            // Get the user's promoter service if it exists
            $promoter = Auth::user()->services()->where('pivot.serviceable_type', 'App\Models\Promoter')->first();
            $this->promoterCompany = $promoter ? $promoter->name : null;  // Access the name attribute
        } else {
            $this->promoterCompany = null;  // Default to null if not authenticated
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.greeting');
    }
}

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
    public $associatedEntity;

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

        $this->userName = Auth::check() ? Auth::user()->first_name : 'User';

        // Retrieve the associated entity based on user role
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->roles->first()->name;
            switch ($role) {
                case 'promoter':
                    $promoter = $user->promoters()->first();
                    $this->associatedEntity = $promoter ? $promoter->name : null;
                    break;

                case 'venue':
                    $venue = $user->venues()->first();
                    $this->associatedEntity = $venue ? $venue->name : null;
                    break;

                case 'artist':
                    $band = $user->otherService("Artist")->first();
                    $this->associatedEntity = $band ? $band->name : null;
                    break;

                case 'photographer':
                    $photographer = $user->otherService("Photographer")->first();
                    $this->associatedEntity = $photographer ? $photographer->name : null;
                    break;

                case 'videographer':
                    $videographer = $user->otherService("Videographer")->first();
                    $this->associatedEntity = $videographer ? $videographer->name : null;
                    break;

                case 'designer':
                    $designer = $user->otherService("Designer")->first();
                    $this->associatedEntity = $designer ? $designer->name : null;
                    break;

                case 'standard':
                    $standardUser = $user->standardUser()->first();
                    $this->associatedEntity = $standardUser ? $standardUser->name : null;

                default:
                    $this->associatedEntity = null;
                    break;
            }
        } else {
            $this->associatedEntity = null;
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

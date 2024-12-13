<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Environments extends Component
{
    public $name;
    public $label;
    public $groups;
    public $selected;

    public function __construct($name, $label = 'Select Options', $selected = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->selected = $selected;
        $this->groups = config('environment_types');
    }

    public function render()
    {
        return view('components.environments');
    }
}
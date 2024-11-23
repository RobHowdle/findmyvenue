<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GroupedCheckbox extends Component
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
        $this->groups = config('environment_types');  // Load from config
    }

    public function render()
    {
        return view('components.grouped-checkbox');
    }
}

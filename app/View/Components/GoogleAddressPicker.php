<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GoogleAddressPicker extends Component
{
    public $id;
    public $name;
    public $label;
    public $placeholder;
    public $value;
    public $latitude;
    public $longitude;
    public $dataId;

    public function __construct(
        string $id,
        string $name,
        string $label,
        string $placeholder,
        $value = null,
        $latitude = null,
        $longitude = null,
        $dataId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->dataId = $dataId;
    }

    public function render()
    {
        return view('components.google-address-picker');
    }
}

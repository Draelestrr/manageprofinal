<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardCard extends Component
{
    public $title;
    public $value;
    public $description;
    public $icon;
    public $color;
    public $textColor;

    /**
     * Create a new component instance.
     *
     * @param string $title
     * @param string $value
     * @param string $description
     * @param string $icon
     * @param string $color
     * @param string $textColor
     * @return void
     */
    public function __construct($title, $value, $description, $icon, $color, $textColor = 'text-dark')
    {
        $this->title = $title;
        $this->value = $value;
        $this->description = $description;
        $this->icon = $icon;
        $this->color = $color;
        $this->textColor = $textColor;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.dashboard-card');
    }
}

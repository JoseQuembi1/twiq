<?php

namespace Twiq\Components;

use Illuminate\View\Component;

class TwiqComponent extends Component
{
    public $position;
    public $config;

    public function __construct($position = null)
    {
        $this->position = $position ?? config('twiq.position', 'top-right');
        $this->config = config('twiq');
    }

    public function render()
    {
        return view('twiq::components.twiq');
    }
}
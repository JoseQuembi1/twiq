<?php

namespace Twiq\Components;

use Illuminate\View\Component;

class TwiqContainer extends Component
{
    public string $position;
    public int $maxNotifications;
    public bool $sound;
    public string $darkMode;

    public function __construct(
        ? string $position = null,
        ? int $maxNotifications = null,
        ? bool $sound = null,
        ? string $darkMode = null
    ) {
        $this->position = $position ?? config('twiq.position', 'top-right');
        $this->maxNotifications = $maxNotifications ?? config('twiq.max_notifications', 5);
        $this->sound = $sound ?? config('twiq.sound', false);
        $this->darkMode = $darkMode ?? config('twiq.dark_mode', 'auto');
    }

    public function render()
    {
        return view('twiq::components.twiq-container');
    }
}
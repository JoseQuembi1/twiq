<?php

namespace Twiq\Livewire;

use Livewire\Component;

class TwiqContainer extends Component
{
    public $notifications = [];
    public $position;
    public $config;

    protected $listeners = ['twiq' => 'addNotification'];

    public function mount($position = null)
    {
        $this->position = $position ?? config('twiq.position', 'top-right');
        $this->config = config('twiq');
    }

    public function addNotification($data)
    {
        $notification = [
            'id' => uniqid(),
            'type' => $data['type'] ?? 'info',
            'message' => $data['message'] ?? '',
            'title' => $data['title'] ?? null,
            'persistent' => $data['persistent'] ?? false,
            'duration' => $data['duration'] ?? $this->config['types'][$data['type']]['duration'] ?? $this->config['duration'],
            'timestamp' => now(),
        ];

        // Prevent duplicates
        if ($this->config['prevent_duplicates']) {
            $existing = collect($this->notifications)->firstWhere('message', $notification['message']);
            if ($existing) {
                return;
            }
        }

        // Check max notifications
        if (count($this->notifications) >= $this->config['max_notifications']) {
            array_shift($this->notifications);
        }

        $this->notifications[] = $notification;
    }

    public function removeNotification($id)
    {
        $this->notifications = array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        });
    }

    public function render()
    {
        return view('twiq::livewire.twiq-container');
    }
}
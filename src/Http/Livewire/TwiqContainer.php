<?php

namespace Twiq\Http\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class TwiqContainer extends Component
{
    public array $notifications = [];
    public string $position = 'top-right';
    public int $maxNotifications = 5;

    public function mount(string $position = null)
    {
        $this->position = $position ?? config('twiq.position', 'top-right');
        $this->maxNotifications = config('twiq.max_notifications', 5);
    }

    #[On('twiq')]
    public function addNotification(array $notification): void
    {
        if ($this->shouldPreventDuplicate($notification)) {
            return;
        }

        // Verificar limite máximo
        if (count($this->notifications) >= $this->maxNotifications) {
            array_shift($this->notifications);
        }

        $notification['id'] = $notification['id'] ?? uniqid();
        $notification['timestamp'] = now()->toISOString();
        
        $this->notifications[] = $notification;
    }

    #[On('twiq:clear')]
    public function clearNotifications(): void
    {
        $this->notifications = [];
    }

    public function removeNotification(string $id): void
    {
        $this->notifications = array_filter($this->notifications, function ($notification) use ($id) {
            return $notification['id'] !== $id;
        });
    }

    protected function shouldPreventDuplicate(array $notification): bool
    {
        if (!config('twiq.prevent_duplicates', true)) {
            return false;
        }

        foreach ($this->notifications as $existing) {
            if ($existing['type'] === $notification['type'] && 
                $existing['message'] === $notification['message']) {
                return true;
            }
        }

        return false;
    }

    public function render()
    {
        return view('twiq::livewire.twiq-container');
    }
}
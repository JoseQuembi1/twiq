<?php

namespace Twiq\Traits;

trait HasTwiqNotifications
{
    public function notifySuccess(string $message, array $options = []): void
    {
        $this->notify('success', $message, $options);
    }

    public function notifyError(string $message, array $options = []): void
    {
        $this->notify('error', $message, $options);
    }

    public function notifyWarning(string $message, array $options = []): void
    {
        $this->notify('warning', $message, $options);
    }

    public function notifyInfo(string $message, array $options = []): void
    {
        $this->notify('info', $message, $options);
    }

    public function notifyPersistent(string $type, string $message, array $options = []): void
    {
        $options['persistent'] = true;
        $this->notify($type, $message, $options);
    }

    public function notify(string $type, string $message, array $options = []): void
    {
        $notification = [
            'type' => $type,
            'message' => $message,
            'title' => $options['title'] ?? null,
            'duration' => $options['duration'] ?? config('twiq.types.' . $type . '.duration', config('twiq.duration', 5000)),
            'persistent' => $options['persistent'] ?? false,
            'id' => uniqid(),
            'timestamp' => now()->toISOString(),
        ];

        $this->dispatch('twiq', $notification);
    }

    public function clearNotifications(): void
    {
        $this->dispatch('twiq:clear');
    }
}
<?php

namespace Twiq\Services;

use Illuminate\Support\Facades\Session;
use Twiq\Contracts\TwiqNotificationInterface;

class TwiqNotificationService implements TwiqNotificationInterface
{
    protected const SESSION_KEY = 'twiq_notifications';

    public function success(string $message, array $options = []): void
    {
        $this->notify('success', $message, $options);
    }

    public function error(string $message, array $options = []): void
    {
        $this->notify('error', $message, $options);
    }

    public function warning(string $message, array $options = []): void
    {
        $this->notify('warning', $message, $options);
    }

    public function info(string $message, array $options = []): void
    {
        $this->notify('info', $message, $options);
    }

    public function notify(string $type, string $message, array $options = []): void
    {
        $notification = $this->createNotification($type, $message, $options);
        
        if ($this->shouldPreventDuplicate($notification)) {
            return;
        }

        $this->addNotification($notification);
    }

    public function persistent(string $type, string $message, array $options = []): void
    {
        $options['persistent'] = true;
        $this->notify($type, $message, $options);
    }

    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public function getNotifications(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    protected function createNotification(string $type, string $message, array $options = []): array
    {
        $config = config('twiq.types.' . $type, []);
        
        return [
            'id' => uniqid(),
            'type' => $type,
            'message' => $message,
            'title' => $options['title'] ?? null,
            'icon' => $options['icon'] ?? $config['icon'] ?? null,
            'color' => $options['color'] ?? $config['color'] ?? 'gray',
            'duration' => $options['duration'] ?? $config['duration'] ?? config('twiq.duration', 5000),
            'persistent' => $options['persistent'] ?? false,
            'timestamp' => now()->toISOString(),
            'group' => $options['group'] ?? null,
        ];
    }

    protected function shouldPreventDuplicate(array $notification): bool
    {
        if (!config('twiq.prevent_duplicates', true)) {
            return false;
        }

        $notifications = $this->getNotifications();
        
        foreach ($notifications as $existing) {
            if ($existing['type'] === $notification['type'] && 
                $existing['message'] === $notification['message']) {
                return true;
            }
        }

        return false;
    }

    protected function addNotification(array $notification): void
    {
        $notifications = $this->getNotifications();
        
        // Verificar limite máximo
        $maxNotifications = config('twiq.max_notifications', 5);
        if (count($notifications) >= $maxNotifications) {
            array_shift($notifications);
        }
        
        $notifications[] = $notification;
        
        Session::put(self::SESSION_KEY, $notifications);
    }
}
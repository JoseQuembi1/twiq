<?php

namespace Twiq;

use Illuminate\Support\Facades\Event;

class TwiqManager
{
    protected $config;

    public function __construct(array $config = [])
    {
        $this->config = $config ?: config('twiq');
    }

    /**
     * Dispatch a notification event
     */
    public function notify(string $type, string $message, array $options = [])
    {
        $data = array_merge([
            'type' => $type,
            'message' => $message,
        ], $options);

        Event::dispatch('twiq', $data);

        return $this;
    }

    /**
     * Dispatch a success notification
     */
    public function success(string $message, array $options = [])
    {
        return $this->notify('success', $message, $options);
    }

    /**
     * Dispatch an error notification
     */
    public function error(string $message, array $options = [])
    {
        return $this->notify('error', $message, $options);
    }

    /**
     * Dispatch a warning notification
     */
    public function warning(string $message, array $options = [])
    {
        return $this->notify('warning', $message, $options);
    }

    /**
     * Dispatch an info notification
     */
    public function info(string $message, array $options = [])
    {
        return $this->notify('info', $message, $options);
    }

    /**
     * Dispatch a persistent notification
     */
    public function persistent(string $type, string $message, array $options = [])
    {
        return $this->notify($type, $message, array_merge($options, ['persistent' => true]));
    }

    /**
     * Get configuration
     */
    public function config(?string $key = null)
    {
        if ($key === null) {
            return $this->config;
        }

        return data_get($this->config, $key);
    }
}
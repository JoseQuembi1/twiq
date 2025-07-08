<?php

namespace Twiq\Traits;

trait HasTwiqNotifications
{
    /**
     * Dispatch a notification
     */
    public function notify(string $type, string $message, array $options = [])
    {
        $this->dispatch('twiq', array_merge([
            'type' => $type,
            'message' => $message,
        ], $options));
    }

    /**
     * Dispatch a success notification
     */
    public function notifySuccess(string $message, array $options = [])
    {
        $this->notify('success', $message, $options);
    }

    /**
     * Dispatch an error notification
     */
    public function notifyError(string $message, array $options = [])
    {
        $this->notify('error', $message, $options);
    }

    /**
     * Dispatch a warning notification
     */
    public function notifyWarning(string $message, array $options = [])
    {
        $this->notify('warning', $message, $options);
    }

    /**
     * Dispatch an info notification
     */
    public function notifyInfo(string $message, array $options = [])
    {
        $this->notify('info', $message, $options);
    }

    /**
     * Dispatch a persistent notification
     */
    public function notifyPersistent(string $type, string $message, array $options = [])
    {
        $this->notify($type, $message, array_merge($options, ['persistent' => true]));
    }
}
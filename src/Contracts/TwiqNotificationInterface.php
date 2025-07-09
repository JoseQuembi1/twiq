<?php

namespace Twiq\Contracts;

interface TwiqNotificationInterface
{
    public function success(string $message, array $options = []): void;
    public function error(string $message, array $options = []): void;
    public function warning(string $message, array $options = []): void;
    public function info(string $message, array $options = []): void;
    public function notify(string $type, string $message, array $options = []): void;
    public function persistent(string $type, string $message, array $options = []): void;
    public function clear(): void;
    public function getNotifications(): array;
}
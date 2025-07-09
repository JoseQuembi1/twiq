<?php

namespace Twiq\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void success(string $message, array $options = [])
 * @method static void error(string $message, array $options = [])
 * @method static void warning(string $message, array $options = [])
 * @method static void info(string $message, array $options = [])
 * @method static void notify(string $type, string $message, array $options = [])
 * @method static void persistent(string $type, string $message, array $options = [])
 * @method static void clear()
 * @method static array getNotifications()
 */
class Twiq extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'twiq';
    }
}
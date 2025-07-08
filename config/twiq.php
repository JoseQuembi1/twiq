<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Position
    |--------------------------------------------------------------------------
    |
    | The default position for notifications
    | Options: 'top-right', 'top-left', 'bottom-right', 'bottom-left', 'top-center', 'bottom-center'
    |
    */
    'position' => 'top-right',

    /*
    |--------------------------------------------------------------------------
    | Default Duration
    |--------------------------------------------------------------------------
    |
    | Default duration in milliseconds for auto-dismissible notifications
    | Set to 0 for persistent notifications
    |
    */
    'duration' => 5000,

    /*
    |--------------------------------------------------------------------------
    | Max Notifications
    |--------------------------------------------------------------------------
    |
    | Maximum number of notifications to show at once
    |
    */
    'max_notifications' => 5,

    /*
    |--------------------------------------------------------------------------
    | Sound
    |--------------------------------------------------------------------------
    |
    | Enable sound for notifications
    |
    */
    'sound' => false,

    /*
    |--------------------------------------------------------------------------
    | Dark Mode
    |--------------------------------------------------------------------------
    |
    | Auto detect dark mode based on system preference
    |
    */
    'dark_mode' => 'auto', // 'auto', 'light', 'dark'

    /*
    |--------------------------------------------------------------------------
    | Animation
    |--------------------------------------------------------------------------
    |
    | Animation settings for notifications
    |
    */
    'animation' => [
        'enter' => 'fade-slide-in',
        'leave' => 'fade-slide-out',
        'duration' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Types Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for each notification type
    |
    */
    'types' => [
        'success' => [
            'icon' => 'check-circle',
            'color' => 'green',
            'duration' => 4000,
        ],
        'error' => [
            'icon' => 'x-circle',
            'color' => 'red',
            'duration' => 6000,
        ],
        'warning' => [
            'icon' => 'alert-triangle',
            'color' => 'yellow',
            'duration' => 5000,
        ],
        'info' => [
            'icon' => 'info',
            'color' => 'blue',
            'duration' => 4000,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Grouping
    |--------------------------------------------------------------------------
    |
    | Group similar notifications
    |
    */
    'grouping' => [
        'enabled' => true,
        'timeout' => 2000, // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Prevent Duplicates
    |--------------------------------------------------------------------------
    |
    | Prevent showing duplicate notifications
    |
    */
    'prevent_duplicates' => true,
];
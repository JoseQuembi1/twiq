<?php

namespace Twiq;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class TwiqServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/twiq.php', 'twiq');
    }

    public function boot()
    {
        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/twiq.php' => config_path('twiq.php'),
        ], 'twiq-config');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/twiq'),
        ], 'twiq-views');

        // Publish assets
        $this->publishes([
            __DIR__ . '/../resources/js' => resource_path('js/vendor/twiq'),
        ], 'twiq-assets');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'twiq');

        // Register Blade component
        Blade::component('twiq', \Twiq\Components\TwiqComponent::class);

        // Register Livewire component
        Livewire::component('twiq-container', \Twiq\Livewire\TwiqContainer::class);

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'twiq');

        // Publish translations
        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/twiq'),
        ], 'twiq-translations');
    }
}
<?php

namespace Twiq;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Twiq\Commands\TwiqInstallCommand;
use Twiq\Components\TwiqContainer;
use Twiq\Components\TwiqNotification;
use Twiq\Http\Livewire\TwiqContainer as LivewireTwiqContainer;
use Twiq\Services\TwiqNotificationService;

class TwiqServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/twiq.php', 'twiq');
        
        $this->app->singleton('twiq', function ($app) {
            return new TwiqNotificationService();
        });
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'twiq');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'twiq');
        
        $this->registerCommands();
        $this->registerComponents();
        $this->registerLivewireComponents();
        $this->registerPublishing();
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TwiqInstallCommand::class,
            ]);
        }
    }

    protected function registerComponents()
    {
        Blade::component('twiq', TwiqContainer::class);
        Blade::component('twiq-notification', TwiqNotification::class);
    }

    protected function registerLivewireComponents()
    {
        Livewire::component('twiq-container', LivewireTwiqContainer::class);
    }

    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/twiq.php' => config_path('twiq.php'),
            ], 'twiq-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/twiq'),
            ], 'twiq-views');

            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('js/vendor/twiq'),
            ], 'twiq-assets');

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/twiq'),
            ], 'twiq-translations');
        }
    }
}
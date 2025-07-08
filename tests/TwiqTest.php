<?php

namespace Twiq\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Orchestra\Testbench\TestCase;
use Twiq\Livewire\TwiqContainer;
use Twiq\TwiqServiceProvider;

class TwiqTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [TwiqServiceProvider::class];
    }

    /** @test */
    public function it_can_add_notifications()
    {
        Livewire::test(TwiqContainer::class)
            ->call('addNotification', [
                'type' => 'success',
                'message' => 'Test notification'
            ])
            ->assertSee('Test notification')
            ->assertSet('notifications', function ($notifications) {
                return count($notifications) === 1 && 
                       $notifications[0]['type'] === 'success' &&
                       $notifications[0]['message'] === 'Test notification';
            });
    }

    /** @test */
    public function it_can_remove_notifications()
    {
        $component = Livewire::test(TwiqContainer::class)
            ->call('addNotification', [
                'type' => 'success',
                'message' => 'Test notification'
            ]);

        $notifications = $component->get('notifications');
        $notificationId = $notifications[0]['id'];

        $component->call('removeNotification', $notificationId)
            ->assertSet('notifications', []);
    }

    /** @test */
    public function it_prevents_duplicate_notifications()
    {
        $component = Livewire::test(TwiqContainer::class)
            ->call('addNotification', [
                'type' => 'success',
                'message' => 'Test notification'
            ])
            ->call('addNotification', [
                'type' => 'success',
                'message' => 'Test notification'
            ]);

        $notifications = $component->get('notifications');
        $this->assertCount(1, $notifications);
    }

    /** @test */
    public function it_respects_max_notifications_limit()
    {
        $this->app['config']->set('twiq.max_notifications', 2);

        $component = Livewire::test(TwiqContainer::class)
            ->call('addNotification', [
                'type' => 'success',
                'message' => 'First notification'
            ])
            ->call('addNotification', [
                'type' => 'info',
                'message' => 'Second notification'
            ])
            ->call('addNotification', [
                'type' => 'warning',
                'message' => 'Third notification'
            ]);

        $notifications = $component->get('notifications');
        $this->assertCount(2, $notifications);
        $this->assertEquals('Second notification', $notifications[0]['message']);
        $this->assertEquals('Third notification', $notifications[1]['message']);
    }

    /** @test */
    public function it_handles_persistent_notifications()
    {
        Livewire::test(TwiqContainer::class)
            ->call('addNotification', [
                'type' => 'error',
                'message' => 'Persistent notification',
                'persistent' => true
            ])
            ->assertSet('notifications', function ($notifications) {
                return $notifications[0]['persistent'] === true;
            });
    }

    /** @test */
    public function it_can_dispatch_events()
    {
        $component = Livewire::test(TwiqContainer::class);

        $component->dispatch('twiq', [
            'type' => 'success',
            'message' => 'Event notification'
        ]);

        $notifications = $component->get('notifications');
        $this->assertCount(1, $notifications);
        $this->assertEquals('Event notification', $notifications[0]['message']);
    }
}
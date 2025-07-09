<?php

namespace Twiq\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Twiq\Http\Livewire\TwiqContainer;
use Twiq\Tests\TestCase;

class TwiqNotificationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    /** @test */
    public function it_can_add_notification()
    {
        Livewire::test(TwiqContainer::class)
            ->call('addNotification', [
                'type' => 'success',
                'message' => 'Test notification'
            ])
            ->assertSee('Test notification');
    }

    /** @test */
    public function it_can_remove_notification()
    {
        Livewire::test(TwiqContainer::class)
            ->call('addNotification', [
                'type' => 'success',
                'message' => 'Test notification',
                'id' => 'test-1'
            ])
            ->call('removeNotification', 'test-1')
            ->assertDontSee('Test notification');
    }

    /** @test */
    public function it_prevents_duplicate_notifications()
    {
        $component = Livewire::test(TwiqContainer::class);

        $notification = [
            'type' => 'success',
            'message' => 'Test notification'
        ];

        $component->call('addNotification', $notification)
            ->call('addNotification', $notification)
            ->assertCount('notifications', 1);
    }

    /** @test */
    public function it_respects_max_notifications_limit()
    {
        $component = Livewire::test(TwiqContainer::class);

        for ($i = 0; $i < 7; $i++) {
            $component->call('addNotification', [
                'type' => 'info',
                'message' => "Notification {$i}"
            ]);
        }

        $component->assertCount('notifications', 5);
    }

    /** @test */
    public function it_can_handle_persistent_notifications()
    {
        Livewire::test(TwiqContainer::class)
            ->call('addNotification', [
                'type' => 'warning',
                'message' => 'Persistent notification',
                'persistent' => true
            ])
            ->assertSee('Persistent notification');
    }
}
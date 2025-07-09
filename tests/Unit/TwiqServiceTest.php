<?php

namespace Twiq\Tests\Unit;

use Twiq\Services\TwiqNotificationService;
use Twiq\Tests\TestCase;

class TwiqServiceTest extends TestCase
{
    protected TwiqNotificationService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new TwiqNotificationService();
    }

    /** @test */
    public function it_can_create_success_notification()
    {
        $this->service->success('Test message');
        
        $notifications = $this->service->getNotifications();
        
        $this->assertCount(1, $notifications);
        $this->assertEquals('success', $notifications[0]['type']);
        $this->assertEquals('Test message', $notifications[0]['message']);
    }

    /** @test */
    public function it_can_create_error_notification()
    {
        $this->service->error('Test error');
        
        $notifications = $this->service->getNotifications();
        
        $this->assertCount(1, $notifications);
        $this->assertEquals('error', $notifications[0]['type']);
        $this->assertEquals('Test error', $notifications[0]['message']);
    }

    /** @test */
    public function it_can_clear_notifications()
    {
        $this->service->success('Test 1');
        $this->service->error('Test 2');
        
        $this->service->clear();
        
        $this->assertEmpty($this->service->getNotifications());
    }

    /** @test */
    public function it_can_create_persistent_notification()
    {
        $this->service->persistent('warning', 'Test persistent');
        
        $notifications = $this->service->getNotifications();
        
        $this->assertTrue($notifications[0]['persistent']);
    }

    /** @test */
    public function it_prevents_duplicate_notifications()
    {
        $this->service->info('Test message');
        $this->service->info('Test message');
        
        $this->assertCount(1, $this->service->getNotifications());
    }
}
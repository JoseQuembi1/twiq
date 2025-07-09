<?php

namespace Twiq\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Twiq\TwiqServiceProvider;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Configurar ambiente de teste
        $this->configureDatabase();
        
        // Publicar assets necessários para testes
        $this->publishAssets();
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app): void
    {
        // Configuração do banco de dados para testes
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Configurações do Twiq para testes
        $app['config']->set('twiq', [
            'position' => 'top-right',
            'duration' => 5000,
            'max_notifications' => 5,
            'sound' => false,
            'dark_mode' => 'auto',
            'prevent_duplicates' => true,
            'grouping' => [
                'enabled' => true,
                'timeout' => 2000,
            ],
        ]);
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            TwiqServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    /**
     * Configure the test database.
     *
     * @return void
     */
    protected function configureDatabase(): void
    {
        // Criar tabelas necessárias para testes
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Publish package assets for testing.
     *
     * @return void
     */
    protected function publishAssets(): void
    {
        $this->artisan('vendor:publish', [
            '--tag' => 'twiq-config',
            '--force' => true,
        ]);
    }

    /**
     * Create a notification for testing.
     *
     * @param string $type
     * @param string $message
     * @param array $options
     * @return array
     */
    protected function createTestNotification(
        string $type = 'success',
        string $message = 'Test notification',
        array $options = []
    ): array {
        return array_merge([
            'id' => 'test-' . uniqid(),
            'type' => $type,
            'message' => $message,
            'duration' => 5000,
            'persistent' => false,
            'timestamp' => now()->toISOString(),
        ], $options);
    }

    /**
     * Assert notification exists.
     *
     * @param array $notification
     * @param array $notifications
     * @return void
     */
    protected function assertNotificationExists(array $notification, array $notifications): void
    {
        $exists = collect($notifications)->contains(function ($item) use ($notification) {
            return $item['type'] === $notification['type'] &&
                   $item['message'] === $notification['message'];
        });

        $this->assertTrue($exists, 'Notification not found in the list.');
    }

    /**
     * Assert notification position is valid.
     *
     * @param string $position
     * @return void
     */
    protected function assertValidPosition(string $position): void
    {
        $validPositions = [
            'top-right',
            'top-left',
            'bottom-right',
            'bottom-left',
            'top-center',
            'bottom-center'
        ];

        $this->assertContains(
            $position,
            $validPositions,
            "Invalid notification position: {$position}"
        );
    }

    /**
     * Create test DOM element.
     *
     * @param string $type
     * @param string $message
     * @return \DOMElement
     */
    protected function createTestDOMElement(string $type, string $message): \DOMElement
    {
        $dom = new \DOMDocument();
        $element = $dom->createElement('div');
        $element->setAttribute('class', "notification-{$type}");
        $element->setAttribute('data-message', $message);
        return $element;
    }

    /**
     * Mock notification service for testing.
     *
     * @return object
     */
    protected function mockNotificationService(): object
    {
        return new class {
            public array $notifications = [];

            public function add(array $notification): void
            {
                $this->notifications[] = $notification;
            }

            public function clear(): void
            {
                $this->notifications = [];
            }

            public function count(): int
            {
                return count($this->notifications);
            }
        };
    }

    /**
     * Assert dark mode is properly configured.
     *
     * @param string $mode
     * @return void
     */
    protected function assertDarkModeConfiguration(string $mode): void
    {
        $validModes = ['auto', 'light', 'dark'];
        
        $this->assertContains(
            $mode,
            $validModes,
            "Invalid dark mode configuration: {$mode}"
        );
    }

    /**
     * Assert notification styling matches type.
     *
     * @param string $type
     * @param array $styles
     * @return void
     */
    protected function assertNotificationStyling(string $type, array $styles): void
    {
        $expectedStyles = [
            'success' => ['bg-green-50', 'text-green-400'],
            'error' => ['bg-red-50', 'text-red-400'],
            'warning' => ['bg-yellow-50', 'text-yellow-400'],
            'info' => ['bg-blue-50', 'text-blue-400'],
        ];

        $this->assertEquals(
            $expectedStyles[$type],
            $styles,
            "Notification styling does not match type: {$type}"
        );
    }

    /**
     * Assert animation properties are valid.
     *
     * @param array $animation
     * @return void
     */
    protected function assertValidAnimation(array $animation): void
    {
        $this->assertArrayHasKey('enter', $animation, 'Missing enter animation');
        $this->assertArrayHasKey('exit', $animation, 'Missing exit animation');
        $this->assertIsInt($animation['duration'], 'Animation duration must be an integer');
        $this->assertGreaterThan(0, $animation['duration'], 'Animation duration must be positive');
    }

    /**
     * Assert accessibility requirements are met.
     *
     * @param \DOMElement $element
     * @return void
     */
    protected function assertAccessibility(\DOMElement $element): void
    {
        $this->assertTrue(
            $element->hasAttribute('role'),
            'Element must have ARIA role attribute'
        );

        $this->assertTrue(
            $element->hasAttribute('aria-live'),
            'Element must have aria-live attribute'
        );

        $this->assertTrue(
            $element->hasAttribute('aria-atomic'),
            'Element must have aria-atomic attribute'
        );
    }

    /**
     * Assert responsive design breakpoints.
     *
     * @param array $breakpoints
     * @return void
     */
    protected function assertResponsiveBreakpoints(array $breakpoints): void
    {
        $requiredBreakpoints = ['sm', 'md', 'lg', 'xl', '2xl'];

        foreach ($requiredBreakpoints as $breakpoint) {
            $this->assertArrayHasKey(
                $breakpoint,
                $breakpoints,
                "Missing responsive breakpoint: {$breakpoint}"
            );
        }
    }
}
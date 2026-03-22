<?php

namespace Orchid\MediaLibrary\Tests\Feature\Providers;

use Orchid\MediaLibrary\Console\Commands\InstallCommand;
use Orchid\MediaLibrary\Providers\FoundationServiceProvider;
use Orchid\MediaLibrary\Tests\TestCase;

class FoundationServiceProviderTest extends TestCase
{
    /** @test */
    public function it_registers_commands(): void
    {
        $commands = $this->app->make(FoundationServiceProvider::class, ['app' => $this->app])->commands();

        $this->assertContains(InstallCommand::class, $commands);
    }

    /** @test */
    public function it_boots_correctly(): void
    {
        $provider = new FoundationServiceProvider($this->app);

        // Boot the provider
        $provider->boot();

        // Verify views are loaded
        $this->assertTrue($this->app['view']->exists('orchid-laravel-media-library::components.platform.image-preview-component'));

        // Verify publishes configurations
        $publishes = $provider->publishes();
        $this->assertIsArray($publishes);

        // Check for stubs publishing
        $this->assertArrayHasKey('orchid-media-library-stubs', $publishes);
        $this->assertArrayHasKey('routes-stubs', $publishes);
        $this->assertArrayHasKey('screens-stubs', $publishes);
    }

    /** @test */
    public function it_registers_correct_service_provider(): void
    {
        $providers = $this->app->getProviders(FoundationServiceProvider::class);

        $this->assertNotEmpty($providers);
        $this->assertInstanceOf(FoundationServiceProvider::class, $providers[0]);
    }

    /** @test */
    public function it_provides_correct_package_name(): void
    {
        $provider = new FoundationServiceProvider($this->app);

        // The package name should be consistent with composer.json
        $this->assertEquals('orchid-laravel-media-library', $provider->getPackageName());
    }

    /** @test */
    public function views_are_accessible(): void
    {
        // Test that a specific view from the package exists
        $view = view('orchid-laravel-media-library::components.platform.image-preview-component');

        $this->assertStringContainsString('image-preview-component', $view->name());
    }

    /** @test */
    public function it_has_correct_path_helper(): void
    {
        $provider = new FoundationServiceProvider($this->app);

        // Use reflection to test private method
        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('path');
        $method->setAccessible(true);

        $result = $method->invoke($provider, 'stubs/app');

        // Path should end with correct directory
        $this->assertStringEndsWith('stubs/app', $result);
        $this->assertStringContainsString('laravel-orchid-media-library', $result);
    }
}

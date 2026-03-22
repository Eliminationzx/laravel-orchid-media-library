<?php

namespace OrchidMediaLibrary\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Orchid\Platform\Models\User;
use OrchidMediaLibrary\Providers\FoundationServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    use RefreshDatabase;

    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            FoundationServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Set up media library disk
        $app['config']->set('media-library.disk_name', 'public');
        $app['config']->set('filesystems.disks.public', [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ]);

        // Orchid configuration
        $app['config']->set('platform.middleware.private', []);
        $app['config']->set('platform.middleware.public', []);
    }

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();

        // Load Spatie Media Library migrations
        $this->loadMigrationsFrom(__DIR__.'/../vendor/spatie/laravel-medialibary/database/migrations');

        // Load package migrations if any
        // $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Create a test user for authentication.
     */
    protected function createTestUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ], $attributes));
    }

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup can be placed here
    }
}

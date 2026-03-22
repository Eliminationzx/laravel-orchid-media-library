<?php

declare(strict_types=1);

namespace OrchidMediaLibrary\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use OrchidMediaLibrary\Console\Commands\InstallCommand;
use OrchidMediaLibrary\Services\ConversionService;
use OrchidMediaLibrary\Services\MediaService;

/**
 * Foundation service provider for Laravel Orchid Media Library.
 *
 * Registers package services and publishes stubs.
 */
class FoundationServiceProvider extends ServiceProvider
{
    /**
     * Register package services.
     */
    public function register(): void
    {
        $this->commands([
            InstallCommand::class,
        ]);

        // Register services as singletons
        $this->app->singleton(MediaService::class);
        $this->app->singleton(ConversionService::class);

        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__.'/../../config/orchid-media-library.php',
            'orchid-media-library'
        );

        // Allow customization via service provider booted callback
        $this->app->booted(function () {
            if ($this->app->bound('orchid-media-library.customize')) {
                $customizer = $this->app->make('orchid-media-library.customize');
                if (is_callable($customizer)) {
                    $customizer();
                }
            }
        });
    }

    /**
     * Bootstrap package services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'orchid-laravel-media-library');

        // Publish configuration file
        $this->publishes([
            __DIR__.'/../../config/orchid-media-library.php' => config_path('orchid-media-library.php'),
        ], 'orchid-media-library-config');

        // Publish stubs
        $this->publishes([
            $this->path('stubs/routes') => base_path('routes/platform'),
            $this->path('stubs/app') => app_path(),
            $this->path('stubs/images') => public_path('images'),
        ], 'orchid-media-library');

        // Load package routes
        $this->loadRoutes();
    }

    /**
     * Load package routes.
     */
    private function loadRoutes(): void
    {
        // Load routes from package if they exist
        $routesPath = __DIR__.'/../../routes/media.php';
        if (file_exists($routesPath)) {
            $this->loadRoutesFrom($routesPath);
        }
    }

    /**
     * Get absolute path to package resource.
     *
     * @param  string  $path  Relative path within package
     * @return string Absolute path
     */
    private function path(string $path): string
    {
        return __DIR__.'/../..'.Str::start($path, '/');
    }
}
<?php

declare(strict_types=1);

namespace Orchid\MediaLibrary\Services;

/**
 * Media service for Laravel Orchid Media Library.
 *
 * Provides access to media-related settings and routes with customization support.
 */
class MediaService
{

    /**
     * Customization storage.
     *
     * @var array<string, mixed>
     */
    private static array $customizations = [];

    /**
     * Get configuration value with fallback.
     *
     * @param  string  $key  Configuration key (e.g., 'screen.name')
     * @param  mixed  $default  Default value if not found
     * @return mixed Configuration value
     */
    private static function config(string $key, $default = null)
    {
        return config("orchid-media-library.{$key}", $default);
    }

    /**
     * Customize media library settings.
     *
     * @param  array<string, mixed>  $settings  Settings to customize
     */
    public static function customize(array $settings): void
    {
        self::$customizations = array_merge(self::$customizations, $settings);
    }

    /**
     * Get a customized setting or default value.
     *
     * @param  string  $key  Setting key
     * @param  mixed  $default  Default value if not customized
     * @return mixed Setting value
     */
    public static function get(string $key, $default = null)
    {
        // First check customizations
        if (array_key_exists($key, self::$customizations)) {
            return self::$customizations[$key];
        }

        // Then check configuration
        $configValue = self::config($key);
        if ($configValue !== null) {
            return $configValue;
        }

        // Finally return default
        return $default;
    }

    /**
     * Clear all customizations.
     */
    public static function clearCustomizations(): void
    {
        self::$customizations = [];
    }

    /**
     * Get the screen name.
     *
     * @return string Screen name
     */
    public static function getName(): string
    {
        return self::config('screen.name', 'Media');
    }

    /**
     * Get the screen icon.
     *
     * @return string Screen icon
     */
    public static function getIcon(): string
    {
        return self::config('screen.icon', 'film');
    }

    /**
     * Get the plural name.
     *
     * @return string Plural name
     */
    public static function getPlural(): string
    {
        return self::config('screen.plural', 'media');
    }

    /**
     * Get the route name prefix.
     *
     * @return string Route name prefix
     */
    public static function getRoutePrefix(): string
    {
        return self::config('route.name_prefix', 'platform.');
    }

    /**
     * Get the full route base.
     *
     * @return string Full route base (e.g., 'platform.media.')
     */
    public static function getRoute(): string
    {
        return self::getRoutePrefix() . self::getPlural() . '.';
    }

    /**
     * Get the list route name.
     *
     * @return string List route name (e.g., 'platform.media.list')
     */
    public static function getRouteList(): string
    {
        return self::getRoute() . 'list';
    }

    /**
     * Get the show route name.
     *
     * @return string Show route name (e.g., 'platform.media.show')
     */
    public static function getRouteShow(): string
    {
        return self::getRoute() . 'show';
    }

    /**
     * Get the edit route name.
     *
     * @return string Edit route name (e.g., 'platform.media.edit')
     */
    public static function getRouteEdit(): string
    {
        return self::getRoute() . 'edit';
    }

    /**
     * Get the route prefix for URL generation.
     *
     * @return string Route prefix (e.g., 'platform/media')
     */
    public static function getUrlPrefix(): string
    {
        $routePrefix = self::config('route.prefix', 'platform');
        $plural = self::getPlural();
        
        return "{$routePrefix}/{$plural}";
    }

    /**
     * Get the route middleware.
     *
     * @return array<string> Route middleware
     */
    public static function getRouteMiddleware(): array
    {
        return self::config('route.middleware', ['web', 'platform']);
    }

    /**
     * Get the allowed MIME types for media uploads.
     *
     * @return array<string> Allowed MIME types
     */
    public static function getAllowedMimeTypes(): array
    {
        return self::config('media.allowed_mime_types', ['image/jpeg', 'image/png', 'image/gif']);
    }

    /**
     * Get the maximum file size for uploads.
     *
     * @return int Maximum file size in kilobytes
     */
    public static function getMaxFileSize(): int
    {
        return self::config('media.max_file_size', 10240);
    }

}
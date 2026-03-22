<?php

declare(strict_types=1);

namespace OrchidMediaLibrary\Services;

/**
 * Media service for Laravel Orchid Media Library.
 *
 * Provides access to media-related settings and routes with customization support.
 */
class MediaService
{
    /**
     * Default screen configuration.
     */
    public const SCREEN_NAME = 'Media';
    public const SCREEN_ICON = 'film';
    public const SCREEN_PLURAL = 'media';

    /**
     * Default route configuration.
     */
    public const ROUTE_PREFIX = 'platform';
    public const ROUTE_MIDDLEWARE = ['web', 'platform'];
    public const ROUTE_NAME_PREFIX = 'platform.';

    /**
     * Default media configuration.
     */
    public const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif'];

    /**
     * Customization storage.
     *
     * @var array<string, mixed>
     */
    private static array $customizations = [];

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
        return self::$customizations[$key] ?? $default;
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
        return self::get('screen_name', self::SCREEN_NAME);
    }

    /**
     * Get the screen icon.
     *
     * @return string Screen icon
     */
    public static function getIcon(): string
    {
        return self::get('screen_icon', self::SCREEN_ICON);
    }

    /**
     * Get the plural name.
     *
     * @return string Plural name
     */
    public static function getPlural(): string
    {
        return self::get('screen_plural', self::SCREEN_PLURAL);
    }

    /**
     * Get the route name prefix.
     *
     * @return string Route name prefix
     */
    public static function getRoutePrefix(): string
    {
        return self::get('route_name_prefix', self::ROUTE_NAME_PREFIX);
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
        return self::get('route_url_prefix', self::ROUTE_PREFIX . '/' . self::getPlural());
    }

    /**
     * Get the route middleware.
     *
     * @return array<string> Route middleware
     */
    public static function getRouteMiddleware(): array
    {
        return self::get('route_middleware', self::ROUTE_MIDDLEWARE);
    }

    /**
     * Get the allowed MIME types for media uploads.
     *
     * @return array<string> Allowed MIME types
     */
    public static function getAllowedMimeTypes(): array
    {
        return self::get('allowed_mime_types', self::ALLOWED_MIME_TYPES);
    }

    // Legacy constants for backward compatibility (deprecated)
    public const NAME = 'Media';
    public const ICON = 'film';
    public const PLURAL = 'media';
    public const ROUTE = 'platform.media.';
    public const ROUTE_LIST = 'platform.media.list';
    public const ROUTE_SHOW = 'platform.media.show';
    public const ROUTE_EDIT = 'platform.media.edit';
}
<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Screen Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Orchid admin screen interface.
    |
    */

    'screen' => [
        /*
         * Screen name displayed in the Orchid admin panel.
         */
        'name' => env('ORCHID_MEDIA_LIBRARY_SCREEN_NAME', 'Media'),

        /*
         * Screen icon displayed in the Orchid admin panel.
         * Available icons: https://orchid.software/en/docs/icons/
         */
        'icon' => env('ORCHID_MEDIA_LIBRARY_SCREEN_ICON', 'film'),

        /*
         * Plural name used for routes and translations.
         */
        'plural' => env('ORCHID_MEDIA_LIBRARY_SCREEN_PLURAL', 'media'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for package routes.
    |
    */

    'route' => [
        /*
         * Route prefix for URL generation.
         * Example: 'platform' results in '/platform/media'
         */
        'prefix' => env('ORCHID_MEDIA_LIBRARY_ROUTE_PREFIX', 'platform'),

        /*
         * Route middleware applied to all package routes.
         */
        'middleware' => env('ORCHID_MEDIA_LIBRARY_ROUTE_MIDDLEWARE', ['web', 'platform']),

        /*
         * Route name prefix for named routes.
         * Example: 'platform.' results in 'platform.media.list'
         */
        'name_prefix' => env('ORCHID_MEDIA_LIBRARY_ROUTE_NAME_PREFIX', 'platform.'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for media uploads and handling.
    |
    */

    'media' => [
        /*
         * Allowed MIME types for media uploads.
         */
        'allowed_mime_types' => env('ORCHID_MEDIA_LIBRARY_ALLOWED_MIME_TYPES', [
            'image/jpeg',
            'image/png',
            'image/gif',
        ]),

        /*
         * Maximum file size in kilobytes (KB).
         * Set to 0 for unlimited.
         */
        'max_file_size' => env('ORCHID_MEDIA_LIBRARY_MAX_FILE_SIZE', 10240), // 10MB
    ],

    /*
    |--------------------------------------------------------------------------
    | Conversion Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for image conversions.
    |
    */

    'conversions' => [
        /*
         * Platform conversion settings.
         * Used for small previews in admin panels.
         */
        'platform' => [
            'width' => env('ORCHID_MEDIA_LIBRARY_PLATFORM_WIDTH', 100),
            'height' => env('ORCHID_MEDIA_LIBRARY_PLATFORM_HEIGHT', 100),
            'crop' => env('ORCHID_MEDIA_LIBRARY_PLATFORM_CROP', 'center'),
            'quality' => env('ORCHID_MEDIA_LIBRARY_PLATFORM_QUALITY', 70),
            'optimize' => env('ORCHID_MEDIA_LIBRARY_PLATFORM_OPTIMIZE', true),
            'queue' => env('ORCHID_MEDIA_LIBRARY_PLATFORM_QUEUE', false),
            'enabled' => env('ORCHID_MEDIA_LIBRARY_PLATFORM_ENABLED', true),
        ],

        /*
         * OpenGraph conversion settings.
         * Used for social media previews.
         */
        'opengraph' => [
            'width' => env('ORCHID_MEDIA_LIBRARY_OPENGRAPH_WIDTH', 128),
            'height' => env('ORCHID_MEDIA_LIBRARY_OPENGRAPH_HEIGHT', 128),
            'crop' => env('ORCHID_MEDIA_LIBRARY_OPENGRAPH_CROP', 'center'),
            'quality' => env('ORCHID_MEDIA_LIBRARY_OPENGRAPH_QUALITY', 80),
            'optimize' => env('ORCHID_MEDIA_LIBRARY_OPENGRAPH_OPTIMIZE', true),
            'queue' => env('ORCHID_MEDIA_LIBRARY_OPENGRAPH_QUEUE', false),
            'enabled' => env('ORCHID_MEDIA_LIBRARY_OPENGRAPH_ENABLED', true),
        ],

        /*
         * Thumbnail conversion settings.
         * Used for general thumbnail displays.
         */
        'thumbnail' => [
            'width' => env('ORCHID_MEDIA_LIBRARY_THUMBNAIL_WIDTH', 300),
            'height' => env('ORCHID_MEDIA_LIBRARY_THUMBNAIL_HEIGHT', 300),
            'crop' => env('ORCHID_MEDIA_LIBRARY_THUMBNAIL_CROP', 'center'),
            'quality' => env('ORCHID_MEDIA_LIBRARY_THUMBNAIL_QUALITY', 75),
            'optimize' => env('ORCHID_MEDIA_LIBRARY_THUMBNAIL_OPTIMIZE', true),
            'queue' => env('ORCHID_MEDIA_LIBRARY_THUMBNAIL_QUEUE', false),
            'enabled' => env('ORCHID_MEDIA_LIBRARY_THUMBNAIL_ENABLED', true),
        ],
    ],

];
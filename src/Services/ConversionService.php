<?php

declare(strict_types=1);

namespace OrchidMediaLibrary\Services;

use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;

/**
 * Conversion service for Laravel Orchid Media Library.
 *
 * Handles image conversions for platform, OpenGraph, and thumbnail sizes.
 */
class ConversionService
{
    /**
     * Platform conversion settings.
     */
    public const PLATFORM_CONVERSION = [
        'width' => 100,
        'height' => 100,
        'crop' => 'center',
        'quality' => 70,
        'optimize' => true,
        'queue' => false,
        'enabled' => true,
    ];

    /**
     * OpenGraph conversion settings.
     */
    public const OPENGRAPH_CONVERSION = [
        'width' => 128,
        'height' => 128,
        'crop' => 'center',
        'quality' => 80,
        'optimize' => true,
        'queue' => false,
        'enabled' => true,
    ];

    /**
     * Thumbnail conversion settings.
     */
    public const THUMBNAIL_CONVERSION = [
        'width' => 300,
        'height' => 300,
        'crop' => 'center',
        'quality' => 75,
        'optimize' => true,
        'queue' => false,
        'enabled' => true,
    ];

    /**
     * Get conversion configuration by name.
     *
     * @param  string  $name  Conversion name (platform, opengraph, thumbnail)
     * @return array Conversion configuration
     */
    public static function getConversion(string $name): array
    {
        return match (strtolower($name)) {
            'platform' => self::PLATFORM_CONVERSION,
            'opengraph' => self::OPENGRAPH_CONVERSION,
            'thumbnail' => self::THUMBNAIL_CONVERSION,
            default => [],
        };
    }

    /**
     * Check if a conversion is enabled.
     *
     * @param  string  $name  Conversion name
     * @return bool Whether the conversion is enabled
     */
    public static function isConversionEnabled(string $name): bool
    {
        $config = self::getConversion($name);
        
        return $config['enabled'] ?? true;
    }

    /**
     * Apply platform conversion if enabled.
     *
     * @param  HasMedia  $media  Media entity to apply conversion to
     *
     * @throws InvalidManipulation
     */
    public static function platformConversion(HasMedia $media): void
    {
        if (! self::isConversionEnabled('platform')) {
            return;
        }

        $config = self::PLATFORM_CONVERSION;

        $conversion = $media->addMediaConversion('platform')
            ->keepOriginalImageFormat()
            ->width($config['width'])
            ->crop(self::getCropMethod($config['crop']), $config['width'], $config['height'])
            ->quality($config['quality']);

        if ($config['optimize']) {
            $conversion->optimize();
        }

        if (! $config['queue']) {
            $conversion->nonQueued();
        }
    }

    /**
     * Apply OpenGraph conversion if enabled.
     *
     * @param  HasMedia  $media  Media entity to apply conversion to
     * @param  string|null  $crop  Optional crop method override
     *
     * @throws InvalidManipulation
     */
    public static function openGraphConversion(HasMedia $media, ?string $crop = null): void
    {
        if (! self::isConversionEnabled('opengraph')) {
            return;
        }

        $config = self::OPENGRAPH_CONVERSION;

        $cropMethod = $crop ? self::getCropMethod($crop) : self::getCropMethod($config['crop']);

        $conversion = $media->addMediaConversion('opengraph')
            ->keepOriginalImageFormat()
            ->width($config['width'])
            ->crop($cropMethod, $config['width'], $config['height'])
            ->quality($config['quality']);

        if ($config['optimize']) {
            $conversion->optimize();
        }

        if (! $config['queue']) {
            $conversion->nonQueued();
        }
    }

    /**
     * Apply thumbnail conversion if enabled.
     *
     * @throws InvalidManipulation
     */
    public static function thumbnailConversion(HasMedia $media): void
    {
        if (! self::isConversionEnabled('thumbnail')) {
            return;
        }

        $config = self::THUMBNAIL_CONVERSION;

        $conversion = $media->addMediaConversion('thumbnail')
            ->keepOriginalImageFormat()
            ->width($config['width'])
            ->crop(self::getCropMethod($config['crop']), $config['width'], $config['height'])
            ->quality($config['quality']);

        if ($config['optimize']) {
            $conversion->optimize();
        }

        if (! $config['queue']) {
            $conversion->nonQueued();
        }
    }

    /**
     * Convert crop string to Manipulations constant.
     */
    private static function getCropMethod(string $crop): string
    {
        return match (strtolower($crop)) {
            'top' => Manipulations::CROP_TOP,
            'bottom' => Manipulations::CROP_BOTTOM,
            'left' => Manipulations::CROP_LEFT,
            'right' => Manipulations::CROP_RIGHT,
            'top-left' => Manipulations::CROP_TOP_LEFT,
            'top-right' => Manipulations::CROP_TOP_RIGHT,
            'bottom-left' => Manipulations::CROP_BOTTOM_LEFT,
            'bottom-right' => Manipulations::CROP_BOTTOM_RIGHT,
            default => Manipulations::CROP_CENTER,
        };
    }
}
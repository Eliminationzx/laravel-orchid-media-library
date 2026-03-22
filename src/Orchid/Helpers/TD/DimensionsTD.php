<?php

declare(strict_types=1);

namespace OrchidMediaLibrary\Orchid\Helpers\TD;

use Orchid\Screen\TD;
use OrchidMediaLibrary\Models\Media;

class DimensionsTD
{
    /**
     * Create a TD for displaying image dimensions.
     *
     * @param  string  $column
     * @param  string|null  $title
     * @return \Orchid\Screen\TD
     */
    public static function make(string $column = 'dimensions', ?string $title = null): TD
    {
        return TD::make($column, $title ?? __('Dimensions'))
            ->render(static function (Media $media): string {
                $customProperties = $media->custom_properties ?? [];
                $width = $customProperties['width'] ?? null;
                $height = $customProperties['height'] ?? null;
                
                if ($width && $height) {
                    return "{$width}×{$height}";
                }
                
                // Try to get from media properties
                if ($media->hasProperty('width') && $media->hasProperty('height')) {
                    return "{$media->getCustomProperty('width')}×{$media->getCustomProperty('height')}";
                }
                
                return __('N/A');
            })
            ->sort()
            ->alignCenter();
    }
}
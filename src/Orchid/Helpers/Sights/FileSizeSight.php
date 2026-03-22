<?php

declare(strict_types=1);

namespace Orchid\MediaLibrary\Orchid\Helpers\Sights;

use Orchid\Screen\Sight;
use Orchid\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\Support\File;

class FileSizeSight
{
    /**
     * Create a file size sight with human-readable formatting.
     *
     * @param  string  $column
     * @param  string|null  $title
     * @return \Orchid\Screen\Sight
     */
    public static function make(string $column = 'size', ?string $title = null): Sight
    {
        return Sight::make($column, $title ?? __('Size'))
            ->render(static function (Media $media): string {
                $size = $media->size;
                
                if ($size === null || $size === 0) {
                    return __('Unknown');
                }
                
                return File::getHumanReadableSize($size);
            });
    }
}
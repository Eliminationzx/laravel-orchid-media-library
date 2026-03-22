<?php

declare(strict_types=1);

namespace Orchid\MediaLibrary\Orchid\Helpers\TD;

use Orchid\Screen\TD;
use Orchid\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\Support\File;

class FileSizeTD
{
    /**
     * Create a TD for displaying human-readable file sizes.
     *
     * @param  string  $column
     * @param  string|null  $title
     * @return \Orchid\Screen\TD
     */
    public static function make(string $column = 'size', ?string $title = null): TD
    {
        return TD::make($column, $title ?? __('Size'))
            ->render(static function (Media $media) use ($column): string {
                $size = $media->{$column} ?? $media->size;
                
                if ($size === null || $size === 0) {
                    return __('Unknown');
                }
                
                return File::getHumanReadableSize($size);
            })
            ->sort()
            ->alignRight();
    }
}
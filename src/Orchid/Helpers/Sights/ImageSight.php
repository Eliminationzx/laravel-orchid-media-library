<?php

declare(strict_types=1);

namespace OrchidMediaLibrary\Orchid\Helpers\Sights;

use Orchid\Screen\Sight;
use OrchidMediaLibrary\Models\Media;

class ImageSight
{
    /**
     * Create an image sight for media display.
     *
     * @param  string  $column
     * @param  string|null  $title
     * @return \Orchid\Screen\Sight
     */
    public static function make(string $column = 'media', ?string $title = null): Sight
    {
        return Sight::make($column, $title ?? __('Preview'))
            ->render(static function (Media $media): string {
                if (str_starts_with($media->mime_type, 'image/')) {
                    return view('platform.media.image-preview', [
                        'src' => $media->getFullUrl(),
                        'alt' => $media->name,
                        'width' => 200,
                        'height' => 200,
                    ])->render();
                }

                // For non-image files, show a file icon
                return view('platform.media.file-icon', [
                    'mimeType' => $media->mime_type,
                    'fileName' => $media->file_name,
                ])->render();
            });
    }
}
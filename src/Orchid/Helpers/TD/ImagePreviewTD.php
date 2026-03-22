<?php

declare(strict_types=1);

namespace Orchid\MediaLibrary\Orchid\Helpers\TD;

use Orchid\Screen\TD;
use Orchid\MediaLibrary\View\Components\Platform\ImagePreviewComponent;

class ImagePreviewTD
{
    public static function make(string $column = 'media', string $title = null): TD
    {
        return TD::make($column, $title ?? __('Preview'))
            ->component(ImagePreviewComponent::class)
            ->width('80px')
            ->alignCenter()
            ->sort();
    }
}

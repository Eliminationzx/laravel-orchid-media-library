<?php

declare(strict_types=1);

namespace Orchid\MediaLibrary\Orchid\Helpers\TD;

use Orchid\Screen\TD;
use Orchid\MediaLibrary\View\Components\Platform\ImagePreviewComponent;

class ImagePreviewTD
{
    public static function make(): TD
    {
        return TD::make('media', __('Preview'))
            ->component(ImagePreviewComponent::class)
            ->width('50px')
            ->alignCenter();
    }
}

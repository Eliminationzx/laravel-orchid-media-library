<?php

declare(strict_types=1);

namespace OrchidMediaLibrary\Orchid\Helpers\TD;

use Orchid\Screen\TD;
use OrchidMediaLibrary\View\Components\Platform\ImagePreviewComponent;

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

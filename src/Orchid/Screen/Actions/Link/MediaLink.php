<?php

declare(strict_types=1);

namespace Orchid\MediaLibrary\Orchid\Screen\Actions\Link;

use Orchid\Screen\Actions\Link;
use Orchid\MediaLibrary\Services\MediaService;

class MediaLink
{
    public static function make(): Link
    {
        return Link::make(MediaService::getName())
            ->icon(MediaService::getIcon())
            ->route(MediaService::getRouteList());
    }
}

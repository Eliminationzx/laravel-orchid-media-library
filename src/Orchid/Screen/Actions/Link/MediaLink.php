<?php

declare(strict_types=1);

namespace OrchidMediaLibrary\Orchid\Screen\Actions\Link;

use Orchid\Screen\Actions\Link;
use OrchidMediaLibrary\Services\MediaService;

class MediaLink
{
    public static function make(): Link
    {
        return Link::make(MediaService::getName())
            ->icon(MediaService::getIcon())
            ->route(MediaService::getRouteList());
    }
}

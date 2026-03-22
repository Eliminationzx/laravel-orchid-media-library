<?php

declare(strict_types=1);

namespace OrchidMediaLibrary\Orchid\Screen\Actions\Menu;

use Orchid\Screen\Actions\Menu;
use OrchidMediaLibrary\Services\MediaService;

class MediaMenu
{
    public static function make(): Menu
    {
        return Menu::make(MediaService::getName())
            ->icon(MediaService::getIcon())
            ->route(MediaService::getRouteList());
    }
}

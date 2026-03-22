<?php

declare(strict_types=1);

namespace Orchid\MediaLibrary\Orchid\Screen\Actions\Menu;

use Orchid\Screen\Actions\Menu;
use Orchid\MediaLibrary\Services\MediaService;

class MediaMenu
{
    public static function make(): Menu
    {
        return Menu::make(MediaService::getName())
            ->icon(MediaService::getIcon())
            ->route(MediaService::getRouteList());
    }
}

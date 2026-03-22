<?php

declare(strict_types=1);

namespace OrchidMediaLibrary\Orchid\Screen\Actions\Menu;

use OrchidMediaLibrary\Services\MediaService;
use Orchid\Screen\Actions\Menu;

class MediaMenu
{
    public static function make() : Menu
    {
       return Menu::make(MediaService::NAME)
            ->icon(MediaService::ICON)
            ->route(MediaService::ROUTE_LIST);
    }
}

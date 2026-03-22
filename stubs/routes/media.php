<?php

declare(strict_types=1);

use App\Orchid\Screens\Media\MediaEditScreen;
use App\Orchid\Screens\Media\MediaListScreen;
use App\Orchid\Screens\Media\MediaShowScreen;
use Illuminate\Support\Facades\Route;
use Orchid\MediaLibrary\Models\Media;
use Orchid\MediaLibrary\Services\MediaService;
use Tabuna\Breadcrumbs\Trail;

Route::name(MediaService::getRoutePrefix())
    ->group(static function () {
        Route::screen('', MediaListScreen::class)
            ->name('list')
            ->breadcrumbs(static fn (Trail $trail): Trail => $trail
                ->parent('platform.index')
                ->push(MediaService::getName(), route(MediaService::getRouteList()))
            );

        Route::prefix('{media}')->group(static function () {
            Route::screen('', MediaShowScreen::class)
                ->name('show')
                ->breadcrumbs(static fn (Trail $trail, Media $media): Trail => $trail
                    ->parent(MediaService::getRouteList())
                    ->push($media->getAttribute('name'), route(MediaService::getRouteShow(), $media))
                );

            Route::screen('edit', MediaEditScreen::class)
                ->name('edit')
                ->breadcrumbs(static fn (Trail $trail, Media $media): Trail => $trail
                    ->parent(MediaService::getRouteShow(), $media)
                    ->push(__('Edit'), route(MediaService::getRouteEdit(), $media))
                );
        });
    });
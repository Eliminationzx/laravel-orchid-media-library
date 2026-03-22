<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Media;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Repository;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use OrchidHelpers\Orchid\Helpers\Alerts\ToastAlert;
use OrchidHelpers\Orchid\Helpers\Layouts\ModelLegendLayout;
use OrchidHelpers\Orchid\Helpers\Layouts\ModelTimestampsLayout;
use OrchidHelpers\Orchid\Helpers\Buttons\DownloadButton;
use OrchidHelpers\Orchid\Helpers\Links\DeleteLink;
use OrchidHelpers\Orchid\Helpers\Links\DropdownOptions;
use OrchidHelpers\Orchid\Helpers\Links\EditLink;
use OrchidHelpers\Orchid\Helpers\Screens\ModelScreen;
use OrchidHelpers\Orchid\Helpers\Sights\EntitySight;
use OrchidHelpers\Orchid\Helpers\Sights\IdSight;
use OrchidHelpers\Orchid\Helpers\Sights\Sight;
use OrchidHelpers\Orchid\Helpers\TD\BoolTD;
use OrchidHelpers\Orchid\Helpers\TD\LinkTD;
use OrchidHelpers\Orchid\Traits\DeleteActionTrait;
use Orchid\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\Conversions\FileManipulator;
use Spatie\MediaLibrary\MediaCollections\Exceptions\InvalidConversion;
use Spatie\MediaLibrary\Support\File;

/**
 * Media show screen for Laravel Orchid Media Library.
 *
 * @property Media $model
 */
class MediaShowScreen extends ModelScreen
{
    use DeleteActionTrait;

    /**
     * Query data for media show screen.
     *
     * @param  Media  $media  Media entity to display
     * @return iterable Screen data
     */
    #[ArrayShape([0 => 'iterable', 'generated_conversions' => 'iterable'])]
    public function query(Media $media): iterable
    {
        return [
            ...$this->model($media->loadCount('activities')),
            'generated_conversions' => collect($media->getAttribute('generated_conversions'))
                ->keys()
                ->map(static function (string $conversion) use ($media): Repository {
                    $data = [
                        'conversion' => $conversion,
                        'url' => null,
                        'size' => null,
                        'generated' => false,
                    ];

                    try {
                        $data['url'] = $media->getUrl($conversion);
                        $data['size'] = file_exists($media->getPath($conversion)) ? File::getHumanReadableSize(filesize($media->getPath($conversion))) : null;
                        $data['generated'] = $media->hasGeneratedConversion($conversion);
                    } catch (InvalidConversion) {
                    }

                    return new Repository($data);
                }),
        ];
    }

    /**
     * Display header name.
     */
    public function name(): ?string
    {
        return $this->model->getAttribute('name');
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            DropdownOptions::make()->list([
                DownloadButton::make(__('Download'))
                    ->icon('cloud-download')
                    ->href($this->model->getFullUrl())
                    ->target('_blank'),
                Button::make(__('Regeneration'))
                    ->icon('reload')
                    ->method('regenerate', [
                        'media' => $this->model->getAttribute('id'),
                    ]),
                EditLink::route('platform.media.edit', $this->model),
                DeleteLink::makeFromModel($this->model),
            ]),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('platform.media.show', [
                'src' => $this->model->getFullUrl(),
            ]),

            ModelLegendLayout::make([
                IdSight::make(),
                Sight::make('uuid'),
                EntitySight::make('model', __('Object')),
                Sight::make('name'),
                Sight::make('originalUrl', __('Link'))
                    ->render(static fn (Media $media): Link => Link::make($media->originalUrl)
                        ->icon('link')
                        ->target('_blank')
                        ->href($media->originalUrl)
                    ),
                Sight::make('file_name', __('File name')),
                Sight::make('mime_type', 'MIME'),
                Sight::make('human_readable_size', __('Size')),
                Sight::make('disk', 'File system'),
                Sight::make('conversions_disk', __('Conversion disk')),
                Sight::make('order_column', __('Order')),
                Sight::make('collection_name', __('Collection')),
            ]),

            ModelTimestampsLayout::make(),

            Layout::table('generated_conversions', [
                BoolTD::make('generated', __('Generated')),
                TD::make('conversion')->alignLeft(),
                LinkTD::make('url'),
                TD::make('size')->alignRight(),
            ])
                ->title(__('Conversions')),
        ];
    }

    public function regenerate(Request $request, FileManipulator $fileManipulator): RedirectResponse
    {
        $media = Media::query()->find($request->input('media'));

        if ($media instanceof Media) {
            $fileManipulator->createDerivedFiles($media);

            ToastAlert::make(__('Image updated!'));
        }

        return back();
    }
}

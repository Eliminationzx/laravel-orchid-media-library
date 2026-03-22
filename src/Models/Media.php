<?php

declare(strict_types=1);

namespace Orchid\MediaLibrary\Models;

use Illuminate\Support\Carbon;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

/**
 * Media model for Laravel Orchid Media Library.
 *
 * Extends Spatie's Media model to provide Orchid-specific functionality.
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $uuid
 * @property string $collection_name
 * @property string $name
 * @property string $file_name
 * @property string|null $mime_type
 * @property string $disk
 * @property string|null $conversions_disk
 * @property int $size
 * @property array $manipulations
 * @property array $custom_properties
 * @property array $generated_conversions
 * @property array $responsive_images
 * @property int|null $order_column
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read mixed $extension
 * @property-read mixed $human_readable_size
 * @property-read mixed $type
 */
class Media extends BaseMedia
{
    use AsSource;
    use Filterable;

    /**
     * @var array<string> Filterable fields for Orchid filters
     */
    protected array $allowedFilters = [
        'id',
        'name',
        'collection_name',
    ];

    /**
     * @var array<string> Sortable fields for Orchid sorting
     */
    protected array $allowedSorts = [
        'id',
        'name',
        'collection_name',
        'size',
        'order_column',
        'created_at',
        'updated_at',
    ];
}

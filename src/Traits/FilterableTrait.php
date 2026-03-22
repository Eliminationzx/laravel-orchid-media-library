<?php

declare(strict_types=1);

namespace Orchid\MediaLibrary\Traits;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Screen\Layouts\Selection;

trait FilterableTrait
{
    /**
     * Get the filter selections for media.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            Selection::make('type')
                ->title(__('File Type'))
                ->options([
                    'image' => __('Images'),
                    'video' => __('Videos'),
                    'audio' => __('Audio'),
                    'document' => __('Documents'),
                    'archive' => __('Archives'),
                    'other' => __('Other'),
                ])
                ->empty(__('All Types'))
                ->query(static function (Builder $builder, string $value): void {
                    match ($value) {
                        'image' => $builder->where('mime_type', 'like', 'image/%'),
                        'video' => $builder->where('mime_type', 'like', 'video/%'),
                        'audio' => $builder->where('mime_type', 'like', 'audio/%'),
                        'document' => $builder->whereIn('mime_type', [
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'text/plain',
                        ]),
                        'archive' => $builder->whereIn('mime_type', [
                            'application/zip',
                            'application/x-rar-compressed',
                            'application/x-tar',
                            'application/gzip',
                        ]),
                        default => null,
                    };
                }),

            Selection::make('size')
                ->title(__('File Size'))
                ->options([
                    'small' => __('Small (< 1MB)'),
                    'medium' => __('Medium (1-10MB)'),
                    'large' => __('Large (10-100MB)'),
                    'huge' => __('Huge (> 100MB)'),
                ])
                ->empty(__('All Sizes'))
                ->query(static function (Builder $builder, string $value): void {
                    match ($value) {
                        'small' => $builder->where('size', '<', 1024 * 1024), // < 1MB
                        'medium' => $builder->whereBetween('size', [1024 * 1024, 10 * 1024 * 1024]), // 1-10MB
                        'large' => $builder->whereBetween('size', [10 * 1024 * 1024, 100 * 1024 * 1024]), // 10-100MB
                        'huge' => $builder->where('size', '>', 100 * 1024 * 1024), // > 100MB
                        default => null,
                    };
                }),

            Selection::make('collection')
                ->title(__('Collection'))
                ->options(static function (): array {
                    return \Orchid\MediaLibrary\Models\Media::query()
                        ->distinct()
                        ->pluck('collection_name', 'collection_name')
                        ->mapWithKeys(static fn (string $value): array => [$value => ucfirst($value)])
                        ->toArray();
                })
                ->empty(__('All Collections'))
                ->query(static function (Builder $builder, string $value): void {
                    $builder->where('collection_name', $value);
                }),
        ];
    }

    /**
     * Apply filters to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $key => $value) {
            if ($value !== null && $value !== '') {
                $this->applyFilter($query, $key, $value);
            }
        }

        return $query;
    }

    /**
     * Apply a single filter.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    protected function applyFilter(Builder $query, string $key, mixed $value): void
    {
        match ($key) {
            'search' => $this->applySearchFilter($query, $value),
            'type' => $this->applyTypeFilter($query, $value),
            'size' => $this->applySizeFilter($query, $value),
            'collection' => $query->where('collection_name', $value),
            'disk' => $query->where('disk', $value),
            'created_from' => $query->whereDate('created_at', '>=', $value),
            'created_to' => $query->whereDate('created_at', '<=', $value),
            default => null,
        };
    }

    /**
     * Apply search filter.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return void
     */
    protected function applySearchFilter(Builder $query, string $search): void
    {
        $query->where(function (Builder $query) use ($search): void {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('file_name', 'like', "%{$search}%")
                ->orWhere('mime_type', 'like', "%{$search}%");
        });
    }

    /**
     * Apply type filter.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return void
     */
    protected function applyTypeFilter(Builder $query, string $type): void
    {
        match ($type) {
            'image' => $query->where('mime_type', 'like', 'image/%'),
            'video' => $query->where('mime_type', 'like', 'video/%'),
            'audio' => $query->where('mime_type', 'like', 'audio/%'),
            'document' => $query->whereIn('mime_type', [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/plain',
            ]),
            'archive' => $query->whereIn('mime_type', [
                'application/zip',
                'application/x-rar-compressed',
                'application/x-tar',
                'application/gzip',
            ]),
            default => null,
        };
    }

    /**
     * Apply size filter.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $size
     * @return void
     */
    protected function applySizeFilter(Builder $query, string $size): void
    {
        match ($size) {
            'small' => $query->where('size', '<', 1024 * 1024), // < 1MB
            'medium' => $query->whereBetween('size', [1024 * 1024, 10 * 1024 * 1024]), // 1-10MB
            'large' => $query->whereBetween('size', [10 * 1024 * 1024, 100 * 1024 * 1024]), // 10-100MB
            'huge' => $query->where('size', '>', 100 * 1024 * 1024), // > 100MB
            default => null,
        };
    }
}
<?php

declare(strict_types=1);

namespace OrchidMediaLibrary\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SortableTrait
{
    /**
     * Default sort column.
     *
     * @var string
     */
    protected string $defaultSortColumn = 'created_at';

    /**
     * Default sort direction.
     *
     * @var string
     */
    protected string $defaultSortDirection = 'desc';

    /**
     * Available sort columns.
     *
     * @var array
     */
    protected array $sortableColumns = [
        'name' => 'Name',
        'file_name' => 'File Name',
        'size' => 'Size',
        'mime_type' => 'MIME Type',
        'created_at' => 'Created Date',
        'updated_at' => 'Updated Date',
        'collection_name' => 'Collection',
        'disk' => 'Disk',
    ];

    /**
     * Get sort options for media.
     *
     * @return array
     */
    public function sortOptions(): array
    {
        return [
            'name.asc' => __('Name (A-Z)'),
            'name.desc' => __('Name (Z-A)'),
            'size.asc' => __('Size (Smallest)'),
            'size.desc' => __('Size (Largest)'),
            'created_at.asc' => __('Created (Oldest)'),
            'created_at.desc' => __('Created (Newest)'),
            'updated_at.asc' => __('Updated (Oldest)'),
            'updated_at.desc' => __('Updated (Newest)'),
        ];
    }

    /**
     * Apply sorting to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string|null  $sort
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applySorting(Builder $query, ?string $sort = null): Builder
    {
        if (empty($sort)) {
            $sort = "{$this->defaultSortColumn}.{$this->defaultSortDirection}";
        }

        [$column, $direction] = explode('.', $sort);

        if (!in_array($column, array_keys($this->sortableColumns), true)) {
            $column = $this->defaultSortColumn;
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = $this->defaultSortDirection;
        }

        return $this->applyCustomSorting($query, $column, $direction);
    }

    /**
     * Apply custom sorting logic.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $column
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function applyCustomSorting(Builder $query, string $column, string $direction): Builder
    {
        // Handle special sorting cases
        return match ($column) {
            'name' => $query->orderBy('name', $direction),
            'file_name' => $query->orderBy('file_name', $direction),
            'size' => $query->orderBy('size', $direction),
            'mime_type' => $query->orderBy('mime_type', $direction),
            'created_at' => $query->orderBy('created_at', $direction),
            'updated_at' => $query->orderBy('updated_at', $direction),
            'collection_name' => $query->orderBy('collection_name', $direction),
            'disk' => $query->orderBy('disk', $direction),
            default => $query->orderBy($column, $direction),
        };
    }

    /**
     * Get current sort state.
     *
     * @param  string|null  $sort
     * @return array
     */
    public function getSortState(?string $sort = null): array
    {
        if (empty($sort)) {
            return [
                'column' => $this->defaultSortColumn,
                'direction' => $this->defaultSortDirection,
            ];
        }

        [$column, $direction] = explode('.', $sort);

        return [
            'column' => in_array($column, array_keys($this->sortableColumns), true) 
                ? $column 
                : $this->defaultSortColumn,
            'direction' => in_array($direction, ['asc', 'desc'], true) 
                ? $direction 
                : $this->defaultSortDirection,
        ];
    }

    /**
     * Get human-readable sort description.
     *
     * @param  string|null  $sort
     * @return string
     */
    public function getSortDescription(?string $sort = null): string
    {
        $state = $this->getSortState($sort);
        
        $columnName = $this->sortableColumns[$state['column']] ?? ucfirst(str_replace('_', ' ', $state['column']));
        $directionName = $state['direction'] === 'asc' ? __('Ascending') : __('Descending');
        
        return "{$columnName} ({$directionName})";
    }
}
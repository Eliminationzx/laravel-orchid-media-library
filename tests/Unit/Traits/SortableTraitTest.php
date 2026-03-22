<?php

namespace OrchidMediaLibrary\Tests\Unit\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OrchidMediaLibrary\Tests\TestCase;
use OrchidMediaLibrary\Traits\SortableTrait;
use PHPUnit\Framework\Attributes\Test;

// Create a test model that uses the SortableTrait
class TestSortableModel extends Model
{
    use SortableTrait;

    protected $table = 'media';
    protected $guarded = [];
}

class SortableTraitTest extends TestCase
{
    #[Test]
    public function it_provides_sort_options_method(): void
    {
        $model = new TestSortableModel();
        
        $sortOptions = $model->sortOptions();
        
        $this->assertIsArray($sortOptions);
        $this->assertNotEmpty($sortOptions);
    }

    #[Test]
    public function sort_options_include_name_sorting(): void
    {
        $model = new TestSortableModel();
        
        $sortOptions = $model->sortOptions();
        
        $this->assertArrayHasKey('name.asc', $sortOptions);
        $this->assertArrayHasKey('name.desc', $sortOptions);
    }

    #[Test]
    public function sort_options_include_size_sorting(): void
    {
        $model = new TestSortableModel();
        
        $sortOptions = $model->sortOptions();
        
        $this->assertArrayHasKey('size.asc', $sortOptions);
        $this->assertArrayHasKey('size.desc', $sortOptions);
    }

    #[Test]
    public function sort_options_include_date_sorting(): void
    {
        $model = new TestSortableModel();
        
        $sortOptions = $model->sortOptions();
        
        $this->assertArrayHasKey('created_at.asc', $sortOptions);
        $this->assertArrayHasKey('created_at.desc', $sortOptions);
        $this->assertArrayHasKey('updated_at.asc', $sortOptions);
        $this->assertArrayHasKey('updated_at.desc', $sortOptions);
    }

    #[Test]
    public function it_applies_sorting_to_query(): void
    {
        $model = new TestSortableModel();
        $query = TestSortableModel::query();
        
        $sortedQuery = $model->applySorting($query, 'name.asc');
        
        $this->assertInstanceOf(Builder::class, $sortedQuery);
        $this->assertSame($query, $sortedQuery);
    }

    #[Test]
    public function it_uses_default_sorting_when_none_provided(): void
    {
        $model = new TestSortableModel();
        $query = TestSortableModel::query();
        
        $sortedQuery = $model->applySorting($query, null);
        
        $this->assertInstanceOf(Builder::class, $sortedQuery);
    }

    #[Test]
    public function it_handles_invalid_sort_column(): void
    {
        $model = new TestSortableModel();
        $query = TestSortableModel::query();
        
        // Invalid column should fall back to default
        $sortedQuery = $model->applySorting($query, 'invalid_column.asc');
        
        $this->assertInstanceOf(Builder::class, $sortedQuery);
    }

    #[Test]
    public function it_handles_invalid_sort_direction(): void
    {
        $model = new TestSortableModel();
        $query = TestSortableModel::query();
        
        // Invalid direction should fall back to default
        $sortedQuery = $model->applySorting($query, 'name.invalid');
        
        $this->assertInstanceOf(Builder::class, $sortedQuery);
    }

    #[Test]
    public function it_gets_sort_state(): void
    {
        $model = new TestSortableModel();
        
        $state = $model->getSortState('name.asc');
        
        $this->assertIsArray($state);
        $this->assertArrayHasKey('column', $state);
        $this->assertArrayHasKey('direction', $state);
        $this->assertEquals('name', $state['column']);
        $this->assertEquals('asc', $state['direction']);
    }

    #[Test]
    public function it_gets_default_sort_state_when_none_provided(): void
    {
        $model = new TestSortableModel();
        
        $state = $model->getSortState(null);
        
        $this->assertIsArray($state);
        $this->assertArrayHasKey('column', $state);
        $this->assertArrayHasKey('direction', $state);
    }

    #[Test]
    public function it_gets_sort_description(): void
    {
        $model = new TestSortableModel();
        
        $description = $model->getSortDescription('name.asc');
        
        $this->assertIsString($description);
        $this->assertNotEmpty($description);
    }

    #[Test]
    public function it_gets_default_sort_description(): void
    {
        $model = new TestSortableModel();
        
        $description = $model->getSortDescription(null);
        
        $this->assertIsString($description);
        $this->assertNotEmpty($description);
    }

    #[Test]
    public function it_applies_custom_sorting_for_different_columns(): void
    {
        $model = new TestSortableModel();
        $query = TestSortableModel::query();
        
        $testColumns = ['name', 'size', 'created_at', 'updated_at', 'collection_name', 'disk'];
        
        foreach ($testColumns as $column) {
            $sortedQuery = $model->applySorting($query, "{$column}.asc");
            $this->assertInstanceOf(Builder::class, $sortedQuery);
        }
    }
}
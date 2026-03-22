<?php

namespace OrchidMediaLibrary\Tests\Unit\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OrchidMediaLibrary\Tests\TestCase;
use OrchidMediaLibrary\Traits\FilterableTrait;
use PHPUnit\Framework\Attributes\Test;

// Create a test model that uses the FilterableTrait
class TestFilterableModel extends Model
{
    use FilterableTrait;

    protected $table = 'media';
    protected $guarded = [];
}

class FilterableTraitTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure the media table exists (it should from migrations)
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    #[Test]
    public function it_provides_filters_method(): void
    {
        $model = new TestFilterableModel();
        
        $filters = $model->filters();
        
        $this->assertIsArray($filters);
        $this->assertNotEmpty($filters);
    }

    #[Test]
    public function filters_include_type_selection(): void
    {
        $model = new TestFilterableModel();
        
        $filters = $model->filters();
        
        // Check that type filter exists
        $hasTypeFilter = false;
        foreach ($filters as $filter) {
            if (method_exists($filter, 'getTitle') && $filter->getTitle() === 'File Type') {
                $hasTypeFilter = true;
                break;
            }
        }
        
        $this->assertTrue($hasTypeFilter, 'Type filter should be included');
    }

    #[Test]
    public function filters_include_size_selection(): void
    {
        $model = new TestFilterableModel();
        
        $filters = $model->filters();
        
        // Check that size filter exists
        $hasSizeFilter = false;
        foreach ($filters as $filter) {
            if (method_exists($filter, 'getTitle') && $filter->getTitle() === 'File Size') {
                $hasSizeFilter = true;
                break;
            }
        }
        
        $this->assertTrue($hasSizeFilter, 'Size filter should be included');
    }

    #[Test]
    public function filters_include_collection_selection(): void
    {
        $model = new TestFilterableModel();
        
        $filters = $model->filters();
        
        // Check that collection filter exists
        $hasCollectionFilter = false;
        foreach ($filters as $filter) {
            if (method_exists($filter, 'getTitle') && $filter->getTitle() === 'Collection') {
                $hasCollectionFilter = true;
                break;
            }
        }
        
        $this->assertTrue($hasCollectionFilter, 'Collection filter should be included');
    }

    #[Test]
    public function it_applies_filters_to_query(): void
    {
        $model = new TestFilterableModel();
        $query = TestFilterableModel::query();
        
        $filters = ['type' => 'image'];
        
        $filteredQuery = $model->applyFilters($query, $filters);
        
        $this->assertInstanceOf(Builder::class, $filteredQuery);
        $this->assertSame($query, $filteredQuery);
    }

    #[Test]
    public function it_handles_empty_filters(): void
    {
        $model = new TestFilterableModel();
        $query = TestFilterableModel::query();
        
        $filteredQuery = $model->applyFilters($query, []);
        
        $this->assertInstanceOf(Builder::class, $filteredQuery);
    }

    #[Test]
    public function it_applies_search_filter(): void
    {
        $model = new TestFilterableModel();
        $query = TestFilterableModel::query();
        
        // Mock the applySearchFilter method if needed, or test through applyFilters
        $filters = ['search' => 'test'];
        
        $filteredQuery = $model->applyFilters($query, $filters);
        
        $this->assertInstanceOf(Builder::class, $filteredQuery);
    }

    #[Test]
    public function it_applies_type_filter(): void
    {
        $model = new TestFilterableModel();
        $query = TestFilterableModel::query();
        
        $filters = ['type' => 'image'];
        
        $filteredQuery = $model->applyFilters($query, $filters);
        
        $this->assertInstanceOf(Builder::class, $filteredQuery);
    }

    #[Test]
    public function it_applies_size_filter(): void
    {
        $model = new TestFilterableModel();
        $query = TestFilterableModel::query();
        
        $filters = ['size' => 'small'];
        
        $filteredQuery = $model->applyFilters($query, $filters);
        
        $this->assertInstanceOf(Builder::class, $filteredQuery);
    }

    #[Test]
    public function it_applies_collection_filter(): void
    {
        $model = new TestFilterableModel();
        $query = TestFilterableModel::query();
        
        $filters = ['collection' => 'default'];
        
        $filteredQuery = $model->applyFilters($query, $filters);
        
        $this->assertInstanceOf(Builder::class, $filteredQuery);
    }
}
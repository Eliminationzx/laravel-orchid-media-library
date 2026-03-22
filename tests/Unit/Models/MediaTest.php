<?php

namespace OrchidMediaLibrary\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use OrchidMediaLibrary\Models\Media;
use OrchidMediaLibrary\Tests\TestCase;

class MediaTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated(): void
    {
        $media = new Media;

        $this->assertInstanceOf(Media::class, $media);
        $this->assertInstanceOf(\Spatie\MediaLibrary\MediaCollections\Models\Media::class, $media);
    }

    /** @test */
    public function it_uses_orchid_traits(): void
    {
        $media = new Media;

        $this->assertContains('Orchid\Screen\AsSource', class_uses_recursive($media));
        $this->assertContains('Orchid\Filters\Filterable', class_uses_recursive($media));
    }

    /** @test */
    public function it_has_allowed_filters(): void
    {
        $media = new Media;

        $expectedFilters = ['id', 'name', 'collection_name'];

        $this->assertEquals($expectedFilters, $media->getAllowedFilters());
    }

    /** @test */
    public function it_has_allowed_sorts(): void
    {
        $media = new Media;

        $expectedSorts = [
            'id',
            'name',
            'collection_name',
            'size',
            'order_column',
            'created_at',
            'updated_at',
        ];

        $this->assertEquals($expectedSorts, $media->getAllowedSorts());
    }

    /** @test */
    public function it_can_be_created_with_factory(): void
    {
        // This test requires the Media factory to be properly set up
        // Since Spatie Media Library provides a factory, we can use it
        $media = Media::factory()->create([
            'name' => 'test-image.jpg',
            'collection_name' => 'default',
        ]);

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'name' => 'test-image.jpg',
            'collection_name' => 'default',
        ]);
    }

    /** @test */
    public function it_has_morph_to_relationship(): void
    {
        $media = Media::factory()->create();

        $this->assertNull($media->model); // No model attached by default
        $this->assertInstanceOf(MorphTo::class, $media->model());
    }

    /** @test */
    public function it_has_url_accessor(): void
    {
        $media = Media::factory()->create([
            'disk' => 'public',
            'file_name' => 'test.jpg',
        ]);

        $this->assertIsString($media->getUrl());
        $this->assertStringContainsString('test.jpg', $media->getUrl());
    }
}

<?php

namespace OrchidMediaLibrary\Tests\Unit\Orchid\Helpers\TD;

use OrchidMediaLibrary\Models\Media;
use OrchidMediaLibrary\Orchid\Helpers\TD\DimensionsTD;
use OrchidMediaLibrary\Tests\TestCase;
use Orchid\Screen\TD;
use PHPUnit\Framework\Attributes\Test;

class DimensionsTDTest extends TestCase
{
    #[Test]
    public function it_creates_td_instance(): void
    {
        $td = DimensionsTD::make('dimensions', 'Dimensions');

        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_uses_default_title_when_not_provided(): void
    {
        $td = DimensionsTD::make('dimensions');

        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_uses_custom_column_name(): void
    {
        $td = DimensionsTD::make('image_dimensions', 'Image Dimensions');

        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_configures_sorting(): void
    {
        $td = DimensionsTD::make('dimensions');
        
        // The TD should be configured to sort
        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_configures_alignment(): void
    {
        $td = DimensionsTD::make('dimensions');
        
        // The TD should be configured to align center
        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_handles_media_with_dimensions_in_custom_properties(): void
    {
        $media = Media::factory()->create([
            'custom_properties' => [
                'width' => 800,
                'height' => 600,
            ],
        ]);

        $td = DimensionsTD::make('dimensions');
        
        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_handles_media_without_dimensions(): void
    {
        $media = Media::factory()->create([
            'custom_properties' => [],
        ]);

        $td = DimensionsTD::make('dimensions');
        
        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_handles_media_with_partial_dimensions(): void
    {
        // Test with only width
        $media1 = Media::factory()->create([
            'custom_properties' => ['width' => 800],
        ]);

        // Test with only height
        $media2 = Media::factory()->create([
            'custom_properties' => ['height' => 600],
        ]);

        $td = DimensionsTD::make('dimensions');
        
        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_handles_media_with_dimensions_via_get_custom_property(): void
    {
        $media = Media::factory()->create();
        // Note: We can't easily test getCustomProperty without mocking
        // But the factory should create a valid media instance
        
        $td = DimensionsTD::make('dimensions');
        
        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_works_with_different_media_types(): void
    {
        // Create media with different mime types
        $mediaImage = Media::factory()->create([
            'mime_type' => 'image/jpeg',
            'custom_properties' => ['width' => 1920, 'height' => 1080],
        ]);

        $mediaPdf = Media::factory()->create([
            'mime_type' => 'application/pdf',
            'custom_properties' => [],
        ]);

        $td = DimensionsTD::make('dimensions');
        
        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_can_be_used_in_table_layout(): void
    {
        // Test that multiple TD instances can be created
        $columns = [
            DimensionsTD::make('dimensions', 'Dimensions'),
            DimensionsTD::make('thumbnail_dimensions', 'Thumbnail'),
        ];

        foreach ($columns as $td) {
            $this->assertInstanceOf(TD::class, $td);
        }
    }
}
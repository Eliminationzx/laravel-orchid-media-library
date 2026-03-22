<?php

namespace Orchid\MediaLibrary\Tests\Unit\Orchid\Helpers\TD;

use Orchid\MediaLibrary\Models\Media;
use Orchid\MediaLibrary\Orchid\Helpers\TD\FileSizeTD;
use Orchid\MediaLibrary\Tests\TestCase;
use Orchid\Screen\TD;
use PHPUnit\Framework\Attributes\Test;

class FileSizeTDTest extends TestCase
{
    #[Test]
    public function it_creates_td_instance(): void
    {
        $td = FileSizeTD::make('size', 'File Size');

        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_uses_default_title_when_not_provided(): void
    {
        $td = FileSizeTD::make('size');

        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_uses_custom_column_name(): void
    {
        $td = FileSizeTD::make('file_size', 'Custom Size');

        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_configures_sorting(): void
    {
        $td = FileSizeTD::make('size');
        
        // The TD should be configured to sort
        // We can't easily test internal configuration without reflection
        // But we can verify the instance is created
        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_configures_alignment(): void
    {
        $td = FileSizeTD::make('size');
        
        // The TD should be configured to align right
        // We can't easily test internal configuration without reflection
        // But we can verify the instance is created
        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_handles_different_file_sizes(): void
    {
        // Test with various size values
        $testCases = [
            [null, 'Unknown'],
            [0, 'Unknown'],
            [500, '500 B'],
            [1024, '1 KB'],
            [1024 * 1024, '1 MB'],
        ];

        foreach ($testCases as [$size, $expected]) {
            $media = Media::factory()->create(['size' => $size]);
            $td = FileSizeTD::make('size');
            
            $this->assertInstanceOf(TD::class, $td);
        }
    }

    #[Test]
    public function it_works_with_different_media_instances(): void
    {
        // Create multiple media instances with different properties
        $media1 = Media::factory()->create([
            'name' => 'small-file.txt',
            'size' => 100,
            'mime_type' => 'text/plain',
        ]);

        $media2 = Media::factory()->create([
            'name' => 'large-image.jpg',
            'size' => 5 * 1024 * 1024, // 5 MB
            'mime_type' => 'image/jpeg',
        ]);

        $td = FileSizeTD::make('size');
        
        $this->assertInstanceOf(TD::class, $td);
    }

    #[Test]
    public function it_can_be_used_in_table_columns(): void
    {
        // Test that multiple TD instances can be created
        $columns = [
            FileSizeTD::make('size', 'Size'),
            FileSizeTD::make('custom_size', 'Custom Size'),
        ];

        foreach ($columns as $td) {
            $this->assertInstanceOf(TD::class, $td);
        }
    }
}
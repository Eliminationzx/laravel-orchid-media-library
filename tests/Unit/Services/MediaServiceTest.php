<?php

namespace OrchidMediaLibrary\Tests\Unit\Services;

use OrchidMediaLibrary\Services\MediaService;
use OrchidMediaLibrary\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MediaServiceTest extends TestCase
{
    public function test_it_has_correct_constants(): void
    {
        $this->assertEquals('Media', MediaService::NAME);
        $this->assertEquals('film', MediaService::ICON);
        $this->assertEquals('media', MediaService::PLURAL);
        $this->assertEquals('platform.media.', MediaService::ROUTE);
        $this->assertEquals('platform.media.list', MediaService::ROUTE_LIST);
        $this->assertEquals('platform.media.show', MediaService::ROUTE_SHOW);
        $this->assertEquals('platform.media.edit', MediaService::ROUTE_EDIT);
        
        // New constants
        $this->assertEquals('Media', MediaService::SCREEN_NAME);
        $this->assertEquals('film', MediaService::SCREEN_ICON);
        $this->assertEquals('media', MediaService::SCREEN_PLURAL);
        $this->assertEquals('platform', MediaService::ROUTE_PREFIX);
        $this->assertEquals(['web', 'platform'], MediaService::ROUTE_MIDDLEWARE);
        $this->assertEquals('platform.', MediaService::ROUTE_NAME_PREFIX);
        $this->assertEquals(['image/jpeg', 'image/png', 'image/gif'], MediaService::ALLOWED_MIME_TYPES);
    }

    #[Test]
    public function constants_are_strings(): void
    {
        $this->assertIsString(MediaService::NAME);
        $this->assertIsString(MediaService::ICON);
        $this->assertIsString(MediaService::PLURAL);
        $this->assertIsString(MediaService::ROUTE);
        $this->assertIsString(MediaService::ROUTE_LIST);
        $this->assertIsString(MediaService::ROUTE_SHOW);
        $this->assertIsString(MediaService::ROUTE_EDIT);
        
        // New constants
        $this->assertIsString(MediaService::SCREEN_NAME);
        $this->assertIsString(MediaService::SCREEN_ICON);
        $this->assertIsString(MediaService::SCREEN_PLURAL);
        $this->assertIsString(MediaService::ROUTE_PREFIX);
        $this->assertIsString(MediaService::ROUTE_NAME_PREFIX);
        $this->assertIsArray(MediaService::ROUTE_MIDDLEWARE);
        $this->assertIsArray(MediaService::ALLOWED_MIME_TYPES);
    }

    #[Test]
    public function route_constants_are_built_correctly(): void
    {
        $this->assertStringStartsWith('platform.', MediaService::ROUTE);
        $this->assertStringEndsWith('.', MediaService::ROUTE);
        $this->assertEquals(MediaService::ROUTE.'list', MediaService::ROUTE_LIST);
        $this->assertEquals(MediaService::ROUTE.'show', MediaService::ROUTE_SHOW);
        $this->assertEquals(MediaService::ROUTE.'edit', MediaService::ROUTE_EDIT);
    }

    #[Test]
    public function it_can_be_used_for_route_generation(): void
    {
        // Example of how the constants might be used
        $routeList = route(MediaService::ROUTE_LIST);
        $this->assertStringContainsString('platform.media.list', MediaService::ROUTE_LIST);

        // The actual route generation would depend on route definitions
        // This test just ensures the constant format is suitable for route() helper
        $this->assertTrue(true);
    }

    #[Test]
    public function icon_constant_is_valid_font_awesome_icon(): void
    {
        // 'film' is a valid Font Awesome icon name
        $validIcons = ['film', 'image', 'photo', 'video', 'file'];
        $this->assertContains(MediaService::ICON, $validIcons);
    }

    #[Test]
    public function it_can_customize_settings(): void
    {
        // Test default value
        $this->assertEquals(['image/jpeg', 'image/png', 'image/gif'], MediaService::getAllowedMimeTypes());
        
        // Customize the setting
        MediaService::customize([
            'allowed_mime_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        ]);
        
        // Should return customized value
        $this->assertEquals(['image/jpeg', 'image/png', 'image/gif', 'image/webp'], MediaService::getAllowedMimeTypes());
        
        // Clear customizations
        MediaService::clearCustomizations();
        
        // Should return default value again
        $this->assertEquals(['image/jpeg', 'image/png', 'image/gif'], MediaService::getAllowedMimeTypes());
    }

    #[Test]
    public function it_returns_correct_values_from_getter_methods(): void
    {
        $this->assertEquals('Media', MediaService::getName());
        $this->assertEquals('film', MediaService::getIcon());
        $this->assertEquals('media', MediaService::getPlural());
        $this->assertEquals('platform', MediaService::getRoutePrefix());
        $this->assertEquals('platform.media.', MediaService::getRoute());
        $this->assertEquals('platform.media.list', MediaService::getRouteList());
        $this->assertEquals('platform.media.show', MediaService::getRouteShow());
        $this->assertEquals('platform.media.edit', MediaService::getRouteEdit());
        $this->assertEquals('platform/media', MediaService::getUrlPrefix());
        $this->assertEquals(['web', 'platform'], MediaService::getRouteMiddleware());
        $this->assertEquals(['image/jpeg', 'image/png', 'image/gif'], MediaService::getAllowedMimeTypes());
    }

    #[Test]
    public function customization_affects_all_getter_methods(): void
    {
        // Customize multiple settings
        MediaService::customize([
            'screen_name' => 'Media Files',
            'screen_icon' => 'image',
            'allowed_mime_types' => ['image/jpeg', 'image/png'],
            'route_middleware' => ['web', 'platform', 'auth'],
        ]);
        
        $this->assertEquals('Media Files', MediaService::getName());
        $this->assertEquals('image', MediaService::getIcon());
        $this->assertEquals(['image/jpeg', 'image/png'], MediaService::getAllowedMimeTypes());
        $this->assertEquals(['web', 'platform', 'auth'], MediaService::getRouteMiddleware());
        
        MediaService::clearCustomizations();
    }
}

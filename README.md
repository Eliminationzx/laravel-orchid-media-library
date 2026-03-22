# Laravel Orchid Media Library

[![Latest Version on Packagist](https://img.shields.io/packagist/v/eliminationzx/laravel-orchid-media-library.svg?style=flat-square)](https://packagist.org/packages/eliminationzx/laravel-orchid-media-library)
[![Total Downloads](https://img.shields.io/packagist/dt/eliminationzx/laravel-orchid-media-library.svg?style=flat-square)](https://packagist.org/packages/eliminationzx/laravel-orchid-media-library)
[![PHP Version](https://img.shields.io/packagist/php-v/eliminationzx/laravel-orchid-media-library.svg?style=flat-square)](https://packagist.org/packages/eliminationzx/laravel-orchid-media-library)
[![License](https://img.shields.io/packagist/l/eliminationzx/laravel-orchid-media-library.svg?style=flat-square)](LICENSE.md)

A comprehensive media management package for Laravel Orchid Platform that seamlessly integrates Spatie's Laravel Media Library with Orchid's admin interface. This package provides a complete media management solution with configuration-driven customization, comprehensive testing, and modern PHP 8.3+ features.

## ✨ Features

- **Complete Media Management**: Upload, organize, and manage media files through Orchid's admin interface
- **Configuration-Driven**: Extensive configuration system for customizing every aspect of the media library
- **Modern PHP Support**: Built with PHP 8.3+ features including strict typing and modern patterns
- **Comprehensive Testing**: Full test suite with PHPUnit and PHPStan integration
- **Image Conversions**: Built-in image conversion system with configurable presets
- **Responsive Components**: Blade components for displaying media in your applications
- **Type Safety**: Full type hints and PHPStan level 9 compliance
- **Developer Experience**: Laravel Pint for code formatting, comprehensive IDE support

## 📋 Requirements

- PHP 8.3 or higher
- Laravel 10.x or 11.x
- Orchid Platform 14.x
- Spatie Laravel Media Library 11.x

## 🚀 Installation

### 1. Install via Composer

```bash
composer require eliminationzx/laravel-orchid-media-library
```

### 2. Install Package Resources

Run the installation command to set up the package:

```bash
php artisan orchid-media-library:install
```

This command will:
- Publish stubs for screens, routes, and images
- Set up the necessary database migrations (if using Spatie Media Library)
- Configure the basic package structure

### 4. Run Migrations

If you haven't already installed Spatie's Media Library, run its migrations:

```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="media-library-migrations"
php artisan migrate
```

## ⚙️ Configuration-Free Architecture

This package uses a configuration-free architecture with sensible defaults. Instead of configuration files, it uses:

### 1. **Constants for Defaults**
All package settings are defined as class constants with sensible defaults:

```php
// MediaService constants
MediaService::SCREEN_NAME = 'Media'
MediaService::SCREEN_ICON = 'film'
MediaService::SCREEN_PLURAL = 'media'
MediaService::ROUTE_PREFIX = 'platform'
MediaService::ROUTE_MIDDLEWARE = ['web', 'platform']
MediaService::ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/gif']

// ConversionService constants
ConversionService::PLATFORM_CONVERSION = [
    'width' => 100,
    'height' => 100,
    'crop' => 'center',
    'quality' => 70,
    'optimize' => true,
    'queue' => false,
    'enabled' => true,
]
```

### 2. **Runtime Customization API**
Customize settings at runtime without configuration files:

```php
// Customize media library settings
MediaService::customize([
    'screen_name' => 'Media Files',
    'screen_icon' => 'image',
    'allowed_mime_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
    'route_middleware' => ['web', 'platform', 'auth'],
]);

// Get customized values
$allowedTypes = MediaService::getAllowedMimeTypes();
$routePrefix = MediaService::getRoutePrefix();
```

### 3. **Spatie Media Library Configuration**
Media-specific settings use Spatie's Media Library configuration (`config/media-library.php`):

```php
// The package respects Spatie's configuration for:
// - Disk name (config('media-library.disk_name'))
// - Max file size (config('media-library.max_file_size'))
// - Image quality (config('media-library.image_quality'))
// - And other media-specific settings
```

### 4. **Available Customization Options**

| Setting | Constant | Default | Customization Method |
|---------|----------|---------|---------------------|
| Screen Name | `SCREEN_NAME` | `'Media'` | `MediaService::customize(['screen_name' => '...'])` |
| Screen Icon | `SCREEN_ICON` | `'film'` | `MediaService::customize(['screen_icon' => '...'])` |
| Route Prefix | `ROUTE_PREFIX` | `'platform'` | `MediaService::customize(['route_prefix' => '...'])` |
| Route Middleware | `ROUTE_MIDDLEWARE` | `['web', 'platform']` | `MediaService::customize(['route_middleware' => [...]])` |
| Allowed MIME Types | `ALLOWED_MIME_TYPES` | `['image/jpeg', 'image/png', 'image/gif']` | `MediaService::customize(['allowed_mime_types' => [...]])` |
| Platform Conversion | `PLATFORM_CONVERSION` | See above | Modify `ConversionService::PLATFORM_CONVERSION` constant |
| OpenGraph Conversion | `OPENGRAPH_CONVERSION` | 128x128, center crop | Modify `ConversionService::OPENGRAPH_CONVERSION` constant |
| Thumbnail Conversion | `THUMBNAIL_CONVERSION` | 300x300, center crop | Modify `ConversionService::THUMBNAIL_CONVERSION` constant |

### 5. **Resetting Customizations**
Clear runtime customizations to restore defaults:

```php
MediaService::clearCustomizations();
```

## 📖 Usage

### Accessing Media in Orchid Admin

Once installed, the media library will be available in your Orchid admin panel at `/platform/media`. You can:

1. **Upload Files**: Drag and drop or click to upload media files
2. **Organize Media**: Create collections, add descriptions, and manage metadata
3. **Preview Media**: View images, documents, and other file types
4. **Edit Metadata**: Update file names, descriptions, and other properties

### Using Media Service

The package provides a `MediaService` class for accessing configuration-based settings:

```php
use OrchidMediaLibrary\Services\MediaService;

// Get screen configuration
$screenName = MediaService::getName(); // 'Media'
$screenIcon = MediaService::getIcon(); // 'film'

// Get route names
$listRoute = MediaService::getRouteList(); // 'platform.media.list'
$showRoute = MediaService::getRouteShow(); // 'platform.media.show'
$editRoute = MediaService::getRouteEdit(); // 'platform.media.edit'
```

### Using Conversion Service

The `ConversionService` provides methods for working with image conversions:

```php
use OrchidMediaLibrary\Services\ConversionService;

// Apply a conversion to media
$convertedUrl = ConversionService::applyConversion($media, 'thumbnail');

// Check if a conversion is enabled
if (ConversionService::isConversionEnabled('platform')) {
    // Apply platform conversion
}
```

### Blade Components

Use the included Blade components to display media in your views:

```blade
{{-- Image preview component --}}
<x-orchid-laravel-media-library::components.platform.image-preview-component 
    :media="$media" 
    :conversion="'thumbnail'"
/>

{{-- Media link component --}}
<x-orchid-laravel-media-library::components.platform.media-link 
    :media="$media"
    :label="'View Media'"
/>
```

### Table Definitions in Orchid Screens

Use the included TD (Table Definition) components in your Orchid screens:

```php
use OrchidMediaLibrary\Orchid\Helpers\TD\ImagePreviewTD;

TD::make('preview', 'Preview')
    ->component(ImagePreviewTD::class)
    ->width('100px'),
```

## 🧪 Testing

The package includes a comprehensive test suite. To run tests:

```bash
# Run all tests
composer test

# Run tests with coverage report
composer test-coverage

# Run static analysis with PHPStan
composer analyse

# Format code with Laravel Pint
composer format
```

### Test Structure

- **Unit Tests**: Test individual components and services
- **Feature Tests**: Test integration with Laravel and Orchid
- **Provider Tests**: Test service provider registration and bootstrapping

## 🏗️ Development

### Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `composer test`
4. Check code quality: `composer analyse`

### Code Style

The package uses Laravel Pint for code formatting. Run `composer format` to automatically format code according to the project standards.

### Static Analysis

PHPStan is configured at level 9 for maximum type safety. Run `composer analyse` to check for type errors and code quality issues.

## 📄 License

This package is open-source software licensed under the [MIT license](LICENSE.md).

## 🏆 Credits

- [Eliminationzx](https://github.com/eliminationzx)
- [Spatie](https://spatie.be) - For the excellent Laravel Media Library package
- [Orchid Platform](https://orchid.software) - For the powerful Laravel admin panel
- [All Contributors](../../contributors)

## 🔗 Links

- [Packagist](https://packagist.org/packages/eliminationzx/laravel-orchid-media-library)
- [GitHub Repository](https://github.com/eliminationzx/laravel-orchid-media-library)
- [Orchid Documentation](https://orchid.software/en/docs)
- [Laravel Media Library Documentation](https://spatie.be/docs/laravel-medialibrary/v11/introduction)

---

**Laravel Orchid Media Library** - Professional media management for Laravel Orchid applications.
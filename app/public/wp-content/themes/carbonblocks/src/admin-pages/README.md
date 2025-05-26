# Admin Pages Directory

This directory contains the file-based routing system for WordPress admin pages using Carbon Fields theme options.

## Directory Structure

```
admin-pages/
├── README.md
└── {page-name}/
    ├── page.php                 # Main admin page
    └── {sub-page}/              # Optional sub-pages
        └── page.php
```

## How It Works

### 1. Auto-Discovery System

The system automatically discovers and registers admin pages by:
- Scanning the `admin-pages/` directory for subdirectories
- Each subdirectory name becomes the page slug
- Loading `page.php` for each page/sub-page
- Supporting nested sub-pages recursively

### 2. Page Configuration (`page.php`)

Each page directory must contain a `page.php` file that registers the admin page:

```php
<?php
/**
 * Admin Page Configuration
 */

use Carbon_Fields\Field;

// Get page slug from directory name
$page_slug = basename(dirname(__FILE__));

carbon_create_admin_page([
    'title' => 'Page Title',
    'menu_title' => 'Menu Title',
    'slug' => $page_slug,
    'icon' => 'dashicons-admin-settings',
    'position' => 30,
    'fields' => [
        Field::make('text', 'field_name', 'Field Label'),
        // ... other fields
    ]
]);
```

### 3. Sub-Pages

Sub-pages are created by adding subdirectories with their own `page.php` files:

```php
<?php
/**
 * Sub-page Configuration
 */

use Carbon_Fields\Field;

// Get parent page slug
$parent_slug = basename(dirname(dirname(__FILE__)));

carbon_create_admin_page([
    'title' => 'Sub-page Title',
    'menu_title' => 'Sub-page',
    'parent' => $parent_slug,
    'slug' => $parent_slug . '_subpage',
    'fields' => [
        // ... fields
    ]
]);
```

## Helper Function

### `carbon_create_admin_page($config)`

Creates a Carbon Fields admin page with intelligent defaults:

**Parameters:**
- `$config` (array) - Page configuration

**Configuration Options:**
- `title` (string) - Page title
- `menu_title` (string, optional) - Menu title (defaults to title)
- `slug` (string) - Page slug
- `parent` (string, optional) - Parent page slug for sub-pages
- `icon` (string, optional) - Dashicon class (ignored for sub-pages)
- `position` (int, optional) - Menu position
- `capability` (string, optional) - Required capability (default: 'manage_options')
- `fields` (array, optional) - Array of Carbon Fields

**Default Configuration:**
- `capability: 'manage_options'`
- `icon: 'dashicons-admin-generic'`
- `menu_title: {title}`

## Example: Settings Page with Sub-page

```
admin-pages/
└── example/
    ├── page.php                 # Main settings page
    └── settings/
        └── page.php             # Advanced settings sub-page
```

### Main Page (`example/page.php`)

```php
<?php
use Carbon_Fields\Field;

$page_slug = basename(dirname(__FILE__));

carbon_create_admin_page([
    'title' => 'Example Settings',
    'menu_title' => 'Example',
    'slug' => $page_slug,
    'icon' => 'dashicons-admin-settings',
    'position' => 30,
    'fields' => [
        Field::make('text', 'site_title', 'Site Title'),
        Field::make('textarea', 'site_description', 'Site Description'),
        Field::make('image', 'site_logo', 'Site Logo'),
        Field::make('select', 'theme_style', 'Theme Style')
            ->set_options([
                'light' => 'Light',
                'dark' => 'Dark',
                'auto' => 'Auto'
            ])
    ]
]);
```

### Sub-page (`example/settings/page.php`)

```php
<?php
use Carbon_Fields\Field;

$parent_slug = basename(dirname(dirname(__FILE__)));

carbon_create_admin_page([
    'title' => 'Advanced Settings',
    'menu_title' => 'Advanced',
    'parent' => $parent_slug,
    'slug' => $parent_slug . '_advanced',
    'fields' => [
        Field::make('checkbox', 'enable_cache', 'Enable Caching'),
        Field::make('text', 'cache_duration', 'Cache Duration (hours)'),
        Field::make('checkbox', 'enable_debug', 'Enable Debug Mode'),
        Field::make('textarea', 'custom_css', 'Custom CSS'),
        Field::make('separator', 'sep1', 'Performance Settings'),
        Field::make('checkbox', 'optimize_images', 'Optimize Images')
    ]
]);
```

## Field Types Available

Carbon Fields supports many field types:

```php
// Basic fields
Field::make('text', 'field_name', 'Label'),
Field::make('textarea', 'field_name', 'Label'),
Field::make('rich_text', 'field_name', 'Label'),
Field::make('checkbox', 'field_name', 'Label'),
Field::make('radio', 'field_name', 'Label'),
Field::make('select', 'field_name', 'Label'),

// Media fields
Field::make('image', 'field_name', 'Label'),
Field::make('file', 'field_name', 'Label'),
Field::make('media_gallery', 'field_name', 'Label'),

// Advanced fields
Field::make('color', 'field_name', 'Label'),
Field::make('date', 'field_name', 'Label'),
Field::make('time', 'field_name', 'Label'),
Field::make('complex', 'field_name', 'Label'),

// Layout fields
Field::make('separator', 'field_name', 'Label'),
Field::make('html', 'field_name', 'Label'),
```

## Data Access

Access theme options using Carbon Fields functions:

```php
// Get single option
$site_title = carbon_get_theme_option('site_title');

// Get all options for a page
$example_settings = [
    'title' => carbon_get_theme_option('site_title'),
    'description' => carbon_get_theme_option('site_description'),
    'logo' => carbon_get_theme_option('site_logo'),
    'style' => carbon_get_theme_option('theme_style')
];

// Use in templates
echo carbon_get_theme_option('site_title');
```

## Menu Structure Result

The file-based structure creates the following admin menu:

```
WordPress Admin
├── Example                      # /example/page.php
│   └── Advanced                 # /example/settings/page.php
└── Another Page                 # /another-page/page.php
    ├── Sub-page 1              # /another-page/sub1/page.php
    └── Sub-page 2              # /another-page/sub2/page.php
```

## Best Practices

1. **Logical Grouping**: Group related settings into the same page
2. **Descriptive Names**: Use clear, descriptive directory and field names
3. **Field Validation**: Use Carbon Fields validation where appropriate
4. **Documentation**: Comment your field configurations
5. **Capability Checking**: Set appropriate capabilities for sensitive settings
6. **Field Organization**: Use separators and HTML fields to organize complex forms

## File-Based Routing Benefits

- **Organization**: Each admin page is self-contained
- **Hierarchy**: Natural sub-page structure through directories
- **Maintainability**: Easy to add, remove, or modify pages
- **Developer Experience**: No manual hook management
- **Scalability**: Unlimited nesting of sub-pages
- **Clean URLs**: Automatic slug generation from directory names
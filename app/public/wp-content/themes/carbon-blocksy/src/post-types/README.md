# Post Types Directory

This directory contains the file-based routing system for WordPress custom post types and their associated Carbon Fields meta boxes.

## Directory Structure

```
post-types/
├── README.md
└── {post-type-name}/
    ├── config.php              # Post type registration
    └── field-groups/            # Carbon Fields meta boxes
        ├── {field-group-1}.php
        ├── {field-group-2}.php
        └── ...
```

## How It Works

### 1. Auto-Discovery System

The system automatically discovers and registers all post types by:
- Scanning the `post-types/` directory for subdirectories
- Each subdirectory name becomes the post type slug
- Loading `config.php` for post type registration
- Loading all `.php` files in `field-groups/` for meta boxes

### 2. Post Type Configuration (`config.php`)

Each post type directory must contain a `config.php` file that registers the post type:

```php
<?php
/**
 * Post Type Configuration
 */

// Get post type slug from directory name
$post_type_slug = basename(dirname(__FILE__));

carbon_create_post_type($post_type_slug, [
    'labels' => [
        'name' => 'Custom Posts',
        'singular_name' => 'Custom Post',
        // ... other labels
    ],
    'public' => true,
    'has_archive' => true,
    'menu_icon' => 'dashicons-admin-post',
    'supports' => ['title', 'editor', 'thumbnail'],
    // ... other post type arguments
]);
```

### 3. Field Groups (`field-groups/*.php`)

Meta boxes are organized into separate files within the `field-groups/` directory:

```php
<?php
/**
 * Field Group Name
 */

use Carbon_Fields\Field;

// Get post type slug from parent directory
$post_type_slug = basename(dirname(dirname(__FILE__)));

carbon_create_post_meta($post_type_slug, 'Meta Box Title', [
    Field::make('text', 'field_name', 'Field Label'),
    Field::make('textarea', 'description', 'Description'),
    // ... other fields
], [
    'context' => 'normal',  // Optional: normal, side, advanced
    'priority' => 'default' // Optional: default, high, low
]);
```

## Helper Functions

### `carbon_create_post_type($slug, $config)`

Registers a WordPress custom post type with intelligent defaults:

**Parameters:**
- `$slug` (string) - Post type slug
- `$config` (array) - Post type configuration

**Default Configuration:**
- `public: true`
- `show_in_rest: true` (Gutenberg support)
- `has_archive: true`
- `supports: ['title', 'editor', 'thumbnail', 'excerpt']`
- Auto-generated labels based on slug
- Menu position: 20

### `carbon_create_post_meta($post_type, $title, $fields, $config)`

Creates a Carbon Fields meta box:

**Parameters:**
- `$post_type` (string) - Post type slug
- `$title` (string) - Meta box title
- `$fields` (array) - Array of Carbon Fields
- `$config` (array, optional) - Additional configuration

**Configuration Options:**
- `context` - Where to show the meta box (normal, side, advanced)
- `priority` - Display priority (default, high, low)

## Example: Villas Post Type

```
post-types/
└── villas/
    ├── config.php              # Registers 'villas' post type
    └── field-groups/
        ├── villa-details.php    # Basic info, location, amenities with tabs
        └── villa-media.php      # Image gallery, videos, brochure
```

### Villa Details Field Group with Tabs (`villa-details.php`)

```php
<?php
use Carbon_Fields\Field;

$post_type_slug = basename(dirname(dirname(__FILE__)));

carbon_create_post_meta_with_tabs($post_type_slug, 'Villa Details', [
    'basic_info' => [
        'title' => 'Basic Information',
        'fields' => [
            Field::make('text', 'villa_name', 'Villa Name'),
            Field::make('text', 'villa_price', 'Price per Night'),
            Field::make('select', 'villa_type', 'Villa Type')
                ->set_options([
                    'luxury' => 'Luxury Villa',
                    'beachfront' => 'Beachfront Villa',
                    'mountain' => 'Mountain Villa'
                ]),
            Field::make('text', 'villa_bedrooms', 'Bedrooms')
                ->set_attribute('type', 'number')
        ]
    ],
    'location' => [
        'title' => 'Location & Address',
        'fields' => [
            Field::make('text', 'villa_address', 'Street Address'),
            Field::make('text', 'villa_city', 'City'),
            Field::make('text', 'villa_country', 'Country')
        ]
    ]
]);
```

## Helper Functions

### `carbon_create_post_meta_with_tabs($post_type, $title, $tabs, $config)`

Creates a Carbon Fields meta box with tabs:

**Parameters:**
- `$post_type` (string) - Post type slug
- `$title` (string) - Meta box title
- `$tabs` (array) - Array of tab configurations
- `$config` (array, optional) - Additional configuration

**Tab Configuration:**
```php
$tabs = [
    'tab_id' => [
        'title' => 'Tab Title',
        'fields' => [/* array of Carbon Fields */]
    ]
];
```

## Data Access

Access post meta data using Carbon Fields functions:

```php
// Get villa name
$villa_name = carbon_get_post_meta(get_the_ID(), 'villa_name');

// Get all villa details
$villa_details = [
    'name' => carbon_get_post_meta(get_the_ID(), 'villa_name'),
    'price' => carbon_get_post_meta(get_the_ID(), 'villa_price'),
    'type' => carbon_get_post_meta(get_the_ID(), 'villa_type'),
    'bedrooms' => carbon_get_post_meta(get_the_ID(), 'villa_bedrooms'),
    'amenities' => carbon_get_post_meta(get_the_ID(), 'villa_additional_amenities')
];
```

## Best Practices

1. **Naming Convention**: Use descriptive directory names that match your post type slug
2. **Field Prefixes**: Prefix field names with the post type (e.g., `villa_name`, `villa_price`)
3. **Logical Grouping**: Organize related fields into separate field group files
4. **Documentation**: Comment your field configurations for clarity
5. **Validation**: Use Carbon Fields validation methods where appropriate

## File-Based Routing Benefits

- **Organization**: Each post type is self-contained in its own directory
- **Modularity**: Field groups can be easily added, removed, or modified
- **Maintainability**: Clear separation of concerns and logical file structure
- **Developer Experience**: No need to manually register post types or remember hook names
- **Scalability**: Easy to add new post types without touching existing code
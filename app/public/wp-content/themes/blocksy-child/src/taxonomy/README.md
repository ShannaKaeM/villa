# Taxonomy Directory

This directory contains the file-based routing system for WordPress custom taxonomies and their associated Carbon Fields term meta boxes.

## Directory Structure

```
taxonomy/
├── README.md
└── {post-types}/                    # Comma-separated post types
    └── {taxonomy-name}/
        ├── config.php               # Taxonomy registration
        └── field-groups/            # Carbon Fields term meta
            ├── {field-group-1}.php
            ├── {field-group-2}.php
            └── ...
```

## How It Works

### 1. Multi-Post Type Support

The system supports **comma-separated post types** in directory names for flexible taxonomy assignment:

**Single Post Type:**
```
taxonomy/
└── villas/
    └── amenities/
```

**Multiple Post Types:**
```
taxonomy/
└── villas, posts/
    └── amenities/
└── villas, posts, events/
    └── locations/
```

### 2. Auto-Discovery System

The system automatically discovers and registers taxonomies by:
- Scanning the `taxonomy/` directory for post type directories
- Parsing comma-separated post types from directory names
- Each subdirectory becomes a taxonomy slug
- Loading `config.php` for taxonomy registration
- Loading all `.php` files in `field-groups/` for term meta

### 3. Taxonomy Configuration (`config.php`)

Each taxonomy directory must contain a `config.php` file:

```php
<?php
/**
 * Taxonomy Configuration
 * Applies to: villas, posts
 */

// Get taxonomy slug and post types from global variables
global $current_taxonomy_slug, $current_post_types;

carbon_create_taxonomy($current_taxonomy_slug, $current_post_types, [
    'labels' => [
        'name' => 'Amenities',
        'singular_name' => 'Amenity',
        // ... other labels
    ],
    'hierarchical' => false,
    'show_admin_column' => true,
    'show_in_rest' => true,
    // ... other taxonomy arguments
]);
```

### 4. Field Groups (`field-groups/*.php`)

Term meta boxes are organized into separate files:

```php
<?php
/**
 * Field Group Name
 */

use Carbon_Fields\Field;

// Get taxonomy slug from global variable
global $current_taxonomy_slug;

carbon_create_taxonomy_meta($current_taxonomy_slug, 'Meta Box Title', [
    Field::make('text', 'field_name', 'Field Label'),
    Field::make('image', 'icon', 'Icon'),
    // ... other fields
]);
```

## Helper Functions

### `carbon_create_taxonomy($slug, $post_types, $config)`

Registers a WordPress custom taxonomy with intelligent defaults:

**Parameters:**
- `$slug` (string) - Taxonomy slug
- `$post_types` (array) - Array of post type slugs
- `$config` (array) - Taxonomy configuration

**Default Configuration:**
- `public: true`
- `show_in_rest: true` (Gutenberg support)
- `show_admin_column: true`
- `hierarchical: false`
- Auto-generated labels based on slug

### `carbon_create_taxonomy_meta($taxonomy, $title, $fields, $config)`

Creates a Carbon Fields term meta box:

**Parameters:**
- `$taxonomy` (string) - Taxonomy slug
- `$title` (string) - Meta box title
- `$fields` (array) - Array of Carbon Fields
- `$config` (array, optional) - Additional configuration

### `carbon_create_taxonomy_meta_with_tabs($taxonomy, $title, $tabs, $config)`

Creates a Carbon Fields term meta box with tabs:

**Parameters:**
- `$taxonomy` (string) - Taxonomy slug
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

## Example: Amenities Taxonomy

```
taxonomy/
└── villas, posts/              # Applies to villas AND posts
    └── amenities/
        ├── config.php          # Registers 'amenities' taxonomy
        └── field-groups/
            └── details.php     # Icon, color, description, etc.
```

### Amenities Configuration (`config.php`)

```php
<?php
global $current_taxonomy_slug, $current_post_types;

carbon_create_taxonomy($current_taxonomy_slug, $current_post_types, [
    'labels' => [
        'name' => 'Amenities',
        'singular_name' => 'Amenity',
        'menu_name' => 'Amenities'
    ],
    'hierarchical' => false,
    'show_admin_column' => true,
    'show_in_rest' => true
]);
```

### Amenities Field Group (`details.php`)

```php
<?php
use Carbon_Fields\Field;

global $current_taxonomy_slug;

carbon_create_taxonomy_meta($current_taxonomy_slug, 'Amenity Details', [
    Field::make('image', 'amenity_icon', 'Icon'),
    Field::make('color', 'amenity_color', 'Color Theme'),
    Field::make('text', 'amenity_short_description', 'Short Description'),
    Field::make('select', 'amenity_category', 'Category')
        ->set_options([
            'comfort' => 'Comfort & Convenience',
            'entertainment' => 'Entertainment',
            'wellness' => 'Wellness & Spa'
        ])
]);
```

## Data Access

Access taxonomy term meta using Carbon Fields functions:

```php
// Get term meta
$icon = carbon_get_term_meta($term_id, 'amenity_icon');
$color = carbon_get_term_meta($term_id, 'amenity_color');

// Get all amenities for a post
$amenities = get_the_terms(get_the_ID(), 'amenities');

// Get amenity meta for each term
if ($amenities) {
    foreach ($amenities as $amenity) {
        $icon = carbon_get_term_meta($amenity->term_id, 'amenity_icon');
        $description = carbon_get_term_meta($amenity->term_id, 'amenity_short_description');
    }
}
```

## Post Type Assignment Examples

### Single Post Type
```
taxonomy/
└── villas/
    ├── amenities/          # Only for villas
    └── room-types/         # Only for villas
```

### Multiple Post Types
```
taxonomy/
└── villas, posts/
    ├── amenities/          # For villas AND posts
    └── locations/          # For villas AND posts
```

### Complex Assignments
```
taxonomy/
├── villas, posts/
│   └── amenities/          # Amenities for villas and posts
├── events, posts/
│   └── categories/         # Categories for events and posts
└── villas/
    └── room-types/         # Room types only for villas
```

## Best Practices

1. **Naming Convention**: Use descriptive directory names that match your taxonomy slug
2. **Field Prefixes**: Prefix field names with the taxonomy (e.g., `amenity_icon`, `amenity_color`)
3. **Logical Grouping**: Organize related fields into separate field group files
4. **Post Type Strategy**: Group taxonomies by their post type assignments for clarity
5. **Documentation**: Comment your field configurations and taxonomy purposes
6. **Validation**: Use Carbon Fields validation methods where appropriate

## File-Based Routing Benefits

- **Flexible Assignment**: Easy multi-post type taxonomy support
- **Organization**: Each taxonomy is self-contained in its own directory
- **Modularity**: Field groups can be easily added, removed, or modified
- **Maintainability**: Clear separation of concerns and logical file structure
- **Developer Experience**: No need to manually register taxonomies or remember hook names
- **Scalability**: Easy to add new taxonomies without touching existing code
- **Intuitive Structure**: Directory names clearly show post type relationships
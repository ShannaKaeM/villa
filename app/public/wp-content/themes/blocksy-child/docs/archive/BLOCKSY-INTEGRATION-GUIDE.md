# Blocksy Theme Integration Guide

## Overview

This guide documents the integration between Carbon Fields blocks and Blocksy theme's design system. The integration allows blocks to dynamically pull colors, typography, and spacing values from Blocksy's theme.json configuration, ensuring consistency across the site.

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Helper Functions](#helper-functions)
3. [Creating Dynamic Blocks](#creating-dynamic-blocks)
4. [Testing & Debugging](#testing--debugging)
5. [Common Issues & Solutions](#common-issues--solutions)

## Architecture Overview

### Key Components

1. **Theme JSON Helper Functions** (`/inc/theme-json-helpers.php`)
   - Bridge between Blocksy's theme.json structure and Carbon Fields
   - Handles nested data structures specific to Blocksy
   - Provides fallback values when theme data is unavailable

2. **Dynamic Blocks** 
   - Use helper functions to populate field options
   - Implement custom CSS compilation for dynamic styling
   - Support Twig-like syntax for CSS templates

3. **REST API Integration**
   - Custom endpoint for accessing theme.json data
   - Useful for JavaScript-based blocks or external integrations

### Data Flow

```
Blocksy theme.json → wp_get_global_settings() → Helper Functions → Carbon Fields → Block Output
```

## Helper Functions

### Available Functions

#### `get_theme_colors_for_blocks()`
Returns an associative array of theme colors formatted for Carbon Fields select fields.

```php
// Returns: ['black' => 'Black', 'white' => 'White', ...]
$colors = get_theme_colors_for_blocks();
```

#### `get_theme_font_sizes_for_blocks()`
Returns an associative array of font sizes from the theme.

```php
// Returns: ['small' => 'Small', 'medium' => 'Medium', ...]
$font_sizes = get_theme_font_sizes_for_blocks();
```

#### `get_theme_spacing_sizes_for_blocks()`
Returns an associative array of spacing values.

```php
// Returns: ['20' => '2X-Small', '30' => 'X-Small', ...]
$spacing = get_theme_spacing_sizes_for_blocks();
```

#### Value Getter Functions

Get the actual CSS values for selected options:

```php
// Get hex color value
$color = get_theme_color_value('primary'); // Returns: #000000

// Get font size value
$size = get_theme_font_size_value('large'); // Returns: 36px

// Get spacing value
$spacing = get_theme_spacing_value('40'); // Returns: 40px or theme value
```

### Understanding Blocksy's Data Structure

Blocksy stores theme data in a nested structure:

```php
[
    'color' => [
        'palette' => [
            'default' => [
                ['name' => 'Black', 'slug' => 'black', 'color' => '#000000'],
                // ... more colors
            ]
        ]
    ],
    'typography' => [
        'fontSizes' => [
            'default' => [
                ['name' => 'Small', 'slug' => 'small', 'size' => '13px'],
                // ... more sizes
            ]
        ]
    ]
]
```

## Creating Dynamic Blocks

### Example: Dynamic Button Block

```php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make('Dynamic Button')
    ->add_fields([
        Field::make('text', 'button_text', 'Button Text')
            ->set_default_value('Click Me'),
        
        Field::make('select', 'button_color', 'Button Color')
            ->set_options(get_theme_colors_for_blocks()),
        
        Field::make('select', 'font_size', 'Font Size')
            ->set_options(get_theme_font_sizes_for_blocks()),
        
        Field::make('select', 'padding', 'Padding')
            ->set_options(get_theme_spacing_sizes_for_blocks()),
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        // Implementation here
    });
```

### CSS Template System

The blocks use a custom CSS compilation system that supports Twig-like syntax:

```css
#{{ block_id }} .dynamic-button {
    {% if fields.button_color %}
    background-color: {{ theme.colors[fields.button_color] }};
    {% endif %}
    
    {% if fields.font_size %}
    font-size: {{ theme.fontSizes[fields.font_size] }};
    {% endif %}
}
```

## Testing & Debugging

### Test Pages

1. **Theme Data Test** (`/test-theme-data.php`)
   - Shows raw theme.json structure
   - Displays parsed helper function output

2. **Helper Functions Test** (`/test-helpers.php`)
   - Direct test of all helper functions
   - Shows Carbon Fields format output
   - Tests value getter functions

### Debug Workflow

1. Check if theme data exists:
   ```php
   $settings = wp_get_global_settings();
   var_dump($settings);
   ```

2. Test helper function output:
   ```php
   $colors = get_theme_colors_for_blocks();
   print_r($colors);
   ```

3. Verify in block editor that dropdowns populate correctly

## Common Issues & Solutions

### Issue: Dropdowns showing "Array"
**Cause**: Helper functions returning wrong format for Carbon Fields
**Solution**: Ensure functions return associative arrays (`key => value`)

### Issue: Undefined array key warnings
**Cause**: Trying to access non-existent theme data
**Solution**: Add proper checks before accessing array keys:
```php
if (isset($color['name']) && isset($color['slug'])) {
    // Safe to use
}
```

### Issue: Theme values not updating
**Cause**: WordPress caching or theme.json not properly loaded
**Solution**: 
- Clear WordPress cache
- Verify theme.json exists and is valid
- Check if Blocksy customizer settings are saved

## Best Practices

1. **Always provide fallbacks** - Theme data might not be available
2. **Use value getter functions** - Don't assume data structure
3. **Test with different themes** - Ensure compatibility
4. **Cache when appropriate** - Theme data doesn't change often
5. **Document custom CSS syntax** - Help future developers understand the system

## Next Steps

1. Extend helper functions for more theme properties
2. Create block templates using dynamic values
3. Build a visual style guide showing all theme values
4. Add support for responsive values
5. Integrate with Blocksy's dynamic CSS system

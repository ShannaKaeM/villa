# Blocksy Child Theme - Villa Community

A custom WordPress child theme for Villa Community, built on top of Blocksy with deep integration for dynamic theme values in Carbon Fields blocks.

## Features

### 🎨 Dynamic Theme Integration
- **Single Source of Truth**: All design tokens come from Blocksy's theme.json
- **No Hardcoded Values**: Blocks automatically use theme colors, typography, and spacing
- **Live Updates**: Changes in Blocksy customizer instantly reflect in all blocks
- **Carbon Fields Integration**: Custom helper functions for seamless Carbon Fields compatibility

### 🔧 Technical Features
- Custom CSS compilation with Twig-like syntax
- REST API endpoint for theme.json access
- Comprehensive helper functions for theme values
- Fallback system for missing theme data
- WordPress CSS variables support

## Documentation

### 📚 Available Guides

1. **[Blocksy Integration Guide](docs/BLOCKSY-INTEGRATION-GUIDE.md)**
   - Complete overview of the integration system
   - Architecture and data flow
   - Testing and debugging procedures
   - Common issues and solutions

2. **[Theme JSON Helpers API](docs/THEME-JSON-HELPERS-API.md)**
   - Detailed API reference for all helper functions
   - Function signatures and return types
   - Usage examples for each function
   - Error handling documentation

3. **[Carbon Fields Examples](docs/CARBON-FIELDS-BLOCKSY-EXAMPLES.md)**
   - Practical block examples
   - CSS template patterns
   - Reusable code snippets
   - Best practices for block development

## Quick Start

### Using Theme Values in Carbon Fields

```php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make('My Block')
    ->add_fields([
        // Color field with theme colors
        Field::make('select', 'bg_color', 'Background Color')
            ->set_options(get_theme_colors_for_blocks()),
        
        // Font size with theme sizes
        Field::make('select', 'text_size', 'Text Size')
            ->set_options(get_theme_font_sizes_for_blocks()),
        
        // Spacing with theme values
        Field::make('select', 'padding', 'Padding')
            ->set_options(get_theme_spacing_sizes_for_blocks()),
    ])
    ->set_render_callback(function ($fields) {
        // Get actual CSS values
        $bg = get_theme_color_value($fields['bg_color']);
        $size = get_theme_font_size_value($fields['text_size']);
        $padding = get_theme_spacing_value($fields['padding']);
        
        // Use in your block...
    });
```

### CSS Template System

```css
/* Twig-like syntax for dynamic CSS */
.my-block {
    {% if fields.bg_color %}
    background-color: {{ theme.colors[fields.bg_color] }};
    {% endif %}
    
    {% if fields.text_size %}
    font-size: {{ theme.fontSizes[fields.text_size] }};
    {% endif %}
}
```

## File Structure

```
blocksy-child/
├── blocks/                    # Carbon Fields blocks
│   ├── dynamic-button/       # Example button block
│   ├── hero-dynamic/         # Example hero block
│   └── listing-section/      # Property listing block
├── docs/                     # Documentation
│   ├── BLOCKSY-INTEGRATION-GUIDE.md
│   ├── THEME-JSON-HELPERS-API.md
│   └── CARBON-FIELDS-BLOCKSY-EXAMPLES.md
├── inc/                      # PHP includes
│   └── theme-json-helpers.php  # Core helper functions
├── functions.php             # Theme functions
└── style.css                # Child theme styles
```

## Testing

### Available Test Files

1. **`test-theme-data.php`** - Template that displays raw theme data
2. **`test-helpers.php`** - Direct test of helper functions output
3. **`/stylebook/`** - Blocksy's built-in style reference

### REST API Endpoint

Access complete theme.json data:
```
GET /wp-json/blocksy-child/v1/theme-json
```

## Requirements

- WordPress 6.0+
- Blocksy Theme (Parent)
- Carbon Fields Plugin
- PHP 7.4+

## Key Helper Functions

### For Block Options
- `get_theme_colors_for_blocks()` - Returns colors for select fields
- `get_theme_font_sizes_for_blocks()` - Returns font sizes
- `get_theme_spacing_sizes_for_blocks()` - Returns spacing options

### For CSS Values
- `get_theme_color_value($slug)` - Get hex/rgb color
- `get_theme_font_size_value($slug)` - Get size with unit
- `get_theme_spacing_value($slug)` - Get spacing with unit

### CSS Compilation
- `compile_css_with_theme_values($template, $data)` - Compile dynamic CSS

## Best Practices

1. **Always Use Helper Functions** - Never hardcode theme values
2. **Provide Fallbacks** - Theme data might not always be available
3. **Test Across Themes** - Ensure compatibility beyond Blocksy
4. **Cache When Possible** - Theme data doesn't change frequently
5. **Document Custom Syntax** - Help future developers understand the system

## Support

For issues or questions:
1. Check the documentation in `/docs/`
2. Review test files for examples
3. Use the REST API endpoint to inspect theme data
4. Check Blocksy's documentation for theme.json structure

## License

This child theme inherits the license from its parent theme, Blocksy.

# Theme JSON Helpers API Reference

## Overview

The Theme JSON Helpers provide a standardized way to access Blocksy theme values within Carbon Fields blocks. These functions handle the complexity of Blocksy's nested data structure and provide consistent fallbacks.

## Core Functions

### `get_theme_colors_for_blocks()`

Retrieves all available theme colors formatted for Carbon Fields select fields.

**Returns:** `array` - Associative array of color slugs to labels

**Example:**
```php
$colors = get_theme_colors_for_blocks();
// Returns: ['black' => 'Black', 'white' => 'White', 'primary' => 'Primary', ...]

// Usage in Carbon Fields:
Field::make('select', 'text_color', 'Text Color')
    ->set_options(get_theme_colors_for_blocks())
```

**Data Source:** 
- Primary: `$settings['color']['palette']['default']`
- Fallback: `$settings['color']['palette']`
- Default: Basic color set if no theme colors found

---

### `get_theme_font_sizes_for_blocks()`

Retrieves all available font sizes from the theme.

**Returns:** `array` - Associative array of size slugs to labels

**Example:**
```php
$sizes = get_theme_font_sizes_for_blocks();
// Returns: ['small' => 'Small', 'medium' => 'Medium', 'large' => 'Large', ...]

// Usage in Carbon Fields:
Field::make('select', 'heading_size', 'Heading Size')
    ->set_options(get_theme_font_sizes_for_blocks())
```

**Data Source:**
- Primary: `$settings['typography']['fontSizes']['default']`
- Fallback: `$settings['typography']['fontSizes']`
- Default: Standard size set if no theme sizes found

---

### `get_theme_spacing_sizes_for_blocks()`

Retrieves all available spacing values from the theme.

**Returns:** `array` - Associative array of spacing slugs to labels

**Example:**
```php
$spacing = get_theme_spacing_sizes_for_blocks();
// Returns: ['20' => '2X-Small', '30' => 'X-Small', '40' => 'Small', ...]

// Usage in Carbon Fields:
Field::make('select', 'block_padding', 'Block Padding')
    ->set_options(get_theme_spacing_sizes_for_blocks())
```

**Data Source:**
- Primary: `$settings['spacing']['spacingSizes']['default']`
- Fallback: `$settings['spacing']['spacingSizes']`
- Default: Pixel-based spacing if no theme spacing found

---

## Value Getter Functions

### `get_theme_color_value($slug)`

Gets the actual color value (hex/rgb) for a given color slug.

**Parameters:**
- `$slug` (string) - The color slug to retrieve

**Returns:** `string` - Hex color value or CSS variable fallback

**Example:**
```php
$primary_color = get_theme_color_value('primary');
// Returns: '#007cba' or 'var(--wp--preset--color--primary)'

// Usage in CSS generation:
$css = "background-color: " . get_theme_color_value($fields['bg_color']) . ";";
```

---

### `get_theme_font_size_value($slug)`

Gets the actual size value for a given font size slug.

**Parameters:**
- `$slug` (string) - The font size slug to retrieve

**Returns:** `string` - Size value with unit or CSS variable fallback

**Example:**
```php
$large_size = get_theme_font_size_value('large');
// Returns: '36px' or 'var(--wp--preset--font-size--large)'

// Usage in CSS generation:
$css = "font-size: " . get_theme_font_size_value($fields['text_size']) . ";";
```

---

### `get_theme_spacing_value($slug)`

Gets the actual spacing value for a given spacing slug.

**Parameters:**
- `$slug` (string) - The spacing slug to retrieve

**Returns:** `string` - Spacing value with unit

**Example:**
```php
$medium_spacing = get_theme_spacing_value('40');
// Returns: '40px' or theme-defined value

// Usage in CSS generation:
$css = "padding: " . get_theme_spacing_value($fields['padding']) . ";";
```

---

## Utility Functions

### `compile_css_with_theme_values($css_template, $data)`

Compiles CSS templates with Twig-like syntax, replacing theme values.

**Parameters:**
- `$css_template` (string) - CSS template with Twig syntax
- `$data` (array) - Data array containing fields and theme values

**Returns:** `string` - Compiled CSS

**Example:**
```php
$css_template = '
.my-block {
    {% if fields.text_color %}
    color: {{ theme.colors[fields.text_color] }};
    {% endif %}
}';

$data = [
    'fields' => ['text_color' => 'primary'],
    'theme' => [
        'colors' => ['primary' => '#007cba']
    ]
];

$compiled_css = compile_css_with_theme_values($css_template, $data);
// Returns: '.my-block { color: #007cba; }'
```

---

### `evaluate_condition($condition, $data)`

Evaluates conditions in CSS templates (used internally by compile_css_with_theme_values).

**Parameters:**
- `$condition` (string) - Condition to evaluate
- `$data` (array) - Data context for evaluation

**Returns:** `bool` - Result of condition evaluation

**Supported Operators:**
- `==` - Equality
- `!=` - Inequality
- `>` - Greater than
- `<` - Less than
- `>=` - Greater than or equal
- `<=` - Less than or equal

---

## Complete Example

```php
// In your block registration:
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make('Theme Integrated Block')
    ->add_fields([
        Field::make('select', 'bg_color', 'Background Color')
            ->set_options(get_theme_colors_for_blocks()),
        
        Field::make('select', 'text_size', 'Text Size')
            ->set_options(get_theme_font_sizes_for_blocks()),
        
        Field::make('select', 'padding', 'Padding')
            ->set_options(get_theme_spacing_sizes_for_blocks()),
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        $block_id = 'block-' . uniqid();
        
        // Get actual values
        $bg_color = get_theme_color_value($fields['bg_color']);
        $font_size = get_theme_font_size_value($fields['text_size']);
        $padding = get_theme_spacing_value($fields['padding']);
        
        // Generate CSS
        $css = "
        #{$block_id} {
            background-color: {$bg_color};
            font-size: {$font_size};
            padding: {$padding};
        }";
        
        // Output
        echo "<style>{$css}</style>";
        echo "<div id='{$block_id}'>Your content here</div>";
    });
```

## Error Handling

All helper functions include error handling:
- Check for existence of array keys before access
- Provide sensible defaults when theme data is missing
- Return CSS variables as fallbacks for value getters
- Never throw fatal errors - always return usable values

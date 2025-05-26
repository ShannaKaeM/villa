# Blocksy Child Theme - Villa Community

A custom WordPress child theme for Villa Community, built on top of Blocksy with theme.json integration and dynamic theme values in Carbon Fields blocks.

## Features

### 🎨 Theme.json + Blocksy Integration
- **Dual Control System**: Edit colors in theme.json OR Blocksy Customizer
- **Unified Variables**: Both systems work together seamlessly
- **Single Source of Truth**: Theme.json defines the design system
- **Live Updates**: Changes in either system instantly reflect everywhere
- **Carbon Fields Integration**: Blocks automatically see both theme.json and Blocksy values

### 🔧 Technical Features
- WordPress theme.json for design tokens
- Blocksy Customizer for user-friendly editing
- Custom CSS compilation with dynamic values
- REST API endpoint for theme data access
- Comprehensive helper functions
- Fallback system for missing data

## Documentation

### 📚 Available Guides

1. **[Theme.json Integration Guide](docs/THEME-JSON-INTEGRATION.md)** 🆕
   - How theme.json works with Blocksy
   - Available CSS variables
   - Best practices for hybrid approach
   - Examples and troubleshooting

2. **[Blocksy Global Settings Guide](docs/BLOCKSY-GLOBAL-SETTINGS-GUIDE.md)** 📝
   - Two methods for managing settings
   - theme.json vs Customizer comparison
   - Quick reference for variables
   - Practical examples

3. **[Theme JSON Helpers API](docs/THEME-JSON-HELPERS-API.md)**
   - Detailed API reference for all helper functions
   - Function signatures and return types
   - Usage examples for each function
   - Error handling documentation

4. **[Carbon Fields Examples](docs/CARBON-FIELDS-BLOCKSY-EXAMPLES.md)**
   - Practical block examples
   - CSS template patterns
   - Reusable code snippets
   - Best practices for block development

5. **[Blocksy Integration Guide](docs/BLOCKSY-INTEGRATION-GUIDE.md)**
   - Complete overview of the integration system
   - Architecture and data flow
   - Testing and debugging procedures
   - Common issues and solutions

6. **[Twig Integration Guide](docs/TWIG-INTEGRATION-GUIDE.md)**
   - Overview of Twig templating engine
   - Key components and reusable components
   - WordPress integration and available Twig functions
   - File structure and getting started guide

## Quick Start

### Method 1: Edit Colors in theme.json

```json
// /wp-content/themes/blocksy-child/theme.json
{
  "version": 3,
  "settings": {
    "color": {
      "palette": [
        {
          "slug": "primary",
          "color": "#2c3e50",
          "name": "Primary"
        }
      ]
    }
  }
}
```

### Method 2: Use Blocksy Customizer

Appearance → Customize → Colors → Set your palette

### Using in Your Code

```css
/* Use theme.json variables */
.my-component {
  background: var(--wp--preset--color--primary);
  padding: var(--wp--preset--spacing--20);
}

/* Use Blocksy variables */
.header {
  color: var(--paletteColor1);
}

/* Mix both! */
.hero {
  background: linear-gradient(
    var(--wp--preset--color--primary),
    var(--paletteColor2)
  );
}
```

### Using Theme Values in Carbon Fields

```php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make('My Block')
    ->add_fields([
        // This gets colors from BOTH theme.json and Blocksy!
        Field::make('select', 'bg_color', 'Background Color')
            ->set_options(get_theme_colors_for_blocks()),
{{ ... }}

## File Structure

```
blocksy-child/
├── theme.json               # 🆕 WordPress theme configuration
├── blocks/                  # Carbon Fields blocks
│   ├── hero/               # Hero section block
│   └── listing-section/    # Property listing block
├── assets/
│   └── css/
│       ├── theme-integration.css  # 🆕 Uses theme.json variables
│       └── backup/               # Old CSS files backup
├── docs/                    # Documentation
│   ├── THEME-JSON-INTEGRATION.md     # 🆕
│   ├── BLOCKSY-GLOBAL-SETTINGS-GUIDE.md  # 📝 Updated
│   ├── THEME-JSON-HELPERS-API.md
│   ├── CARBON-FIELDS-BLOCKSY-EXAMPLES.md
│   └── TWIG-INTEGRATION-GUIDE.md
├── inc/                     # PHP includes
│   └── theme-json-helpers.php  # Core helper functions
│   └── TwigIntegration.php    # Main Twig class
├── functions.php            # Theme functions
└── style.css               # Child theme declaration
```

## Available CSS Variables

### From theme.json
```css
--wp--preset--color--primary
--wp--preset--color--secondary
--wp--preset--color--accent
--wp--preset--font-size--large
--wp--preset--spacing--20
```

### From Blocksy
```css
--paletteColor1 through --paletteColor8
--theme-text-color
--backgroundColor
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
- `get_theme_colors_for_blocks()` - Returns colors from theme.json + Blocksy
- `get_theme_font_sizes_for_blocks()` - Returns font sizes
- `get_theme_spacing_sizes_for_blocks()` - Returns spacing options

### For CSS Values
- `get_theme_color_value($slug)` - Get hex/rgb color
- `get_theme_font_size_value($slug)` - Get size with unit
- `get_theme_spacing_value($slug)` - Get spacing with unit

### CSS Compilation
- `compile_css_with_theme_values($template, $data)` - Compile dynamic CSS

## Best Practices

1. **Use theme.json for design system** - Core colors, typography, spacing
2. **Use Customizer for user settings** - Site-specific overrides
3. **Never hardcode values** - Always use variables
4. **Test both systems** - Ensure changes work in both places
5. **Document your approach** - Help future developers

## Support

For issues or questions:
1. Check the documentation in `/docs/`
2. Review the theme.json file for available values
3. Use the REST API endpoint to inspect theme data
4. Check Blocksy's documentation for customizer options

## License

This child theme inherits the license from its parent theme, Blocksy.

## 🎨 OKLCH Color System

### What is OKLCH?

OKLCH is a perceptually uniform color space that provides:
- **L** - Lightness (0-100%)
- **C** - Chroma (color intensity, 0-0.4)
- **H** - Hue (angle, 0-360°)

### Our Semantic Color System

We use 14 semantic colors organized into 4 groups:

#### Primary Colors
- `--theme-primary-light`: oklch(70% 0.045 194)
- `--theme-primary`: oklch(56% 0.064 194)
- `--theme-primary-dark`: oklch(40% 0.075 194)

#### Secondary Colors
- `--theme-secondary-light`: oklch(75% 0.065 64)
- `--theme-secondary`: oklch(60% 0.089 64)
- `--theme-secondary-dark`: oklch(45% 0.095 64)

#### Neutral Colors
- `--theme-neutral-light`: oklch(85% 0.01 210)
- `--theme-neutral`: oklch(66% 0.016 210)
- `--theme-neutral-dark`: oklch(45% 0.02 210)

#### Base Colors
- `--theme-base-lightest`: oklch(98% 0 0)
- `--theme-base-light`: oklch(90% 0 0)
- `--theme-base`: oklch(66% 0 0)
- `--theme-base-dark`: oklch(35% 0 0)
- `--theme-base-darkest`: oklch(15% 0 0)

### Interactive Color Reference

View and edit colors in real-time using the **Semantic Color Reference** page template:
1. Create a new page
2. Select "Semantic Color Reference" as the page template
3. View the page to see all colors with live OKLCH sliders

## 🌿 Twig Integration

### Overview

Twig provides a clean, secure templating system that separates logic from presentation.

### Key Components

#### 1. TwigIntegration Class (`/inc/TwigIntegration.php`)
- Singleton pattern with global `twig()` function
- Auto-loads templates from `/templates/`, `/blocks/`, and `/partials/`
- Integrates WordPress functions
- Custom theme functions for colors and spacing

#### 2. Reusable Components

**Button Component** (`/templates/components/button.twig`)
```twig
{{ component('button', {
    text: 'Click Me',
    url: '#',
    variant: 'primary', // primary, secondary, neutral
    size: 'medium'      // small, medium, large
}) }}
```

**Card Component** (`/templates/components/card.twig`)
```twig
{{ component('card', {
    title: 'Card Title',
    content: 'Card content here',
    variant: 'default', // default, elevated, outlined
    link: '/path/to/page'
}) }}
```

#### 3. WordPress Integration

**Shortcodes**
```
[twig_button text="Click Me" variant="primary" size="large"]
[twig_card title="My Card" variant="elevated"]Content here[/twig_card]
```

**In PHP Files**
```php
// Display a template
twig()->display('template-name.twig', [
    'title' => 'Page Title',
    'content' => $content
]);

// Render to string
$html = twig()->render('component.twig', $data);
```

### Twig Demo Block

A Carbon Fields block demonstrating Twig integration:
- Found in "Twig Blocks" category
- Three layout options: Cards, Buttons, Mixed
- Shows template code examples

### Available Twig Functions

#### Theme Functions
- `semantic_color(name)` - Get semantic color value
- `theme_spacing(size)` - Get spacing value (10-80)
- `blocksy_color(name)` - Get Blocksy palette color
- `component(name, props)` - Render a component

#### WordPress Functions
All common WordPress functions are available:
- `get_header()`, `get_footer()`
- `wp_head()`, `wp_footer()`
- `get_template_part()`
- `get_permalink()`, `get_the_title()`
- `the_content()`, `the_excerpt()`
- And many more...

## 📁 File Structure

```
blocksy-child/
├── blocks/
│   └── twig-demo/          # Demo block showing Twig usage
├── docs/
│   ├── archive/            # PHP-specific documentation
│   ├── README.md           # This file
│   └── TWIG-INTEGRATION-GUIDE.md
├── inc/
│   ├── TwigIntegration.php # Main Twig class
│   ├── blocksy-color-sync.php
│   └── theme-json-helpers.php
├── templates/
│   ├── components/         # Reusable Twig components
│   │   ├── button.twig
│   │   └── card.twig
│   └── page-twig-demo.twig # Demo page template
├── composer.json           # Twig dependency
├── functions.php           # Simplified for Twig
├── page-twig-demo.php      # WordPress template file
└── semantic-color-reference.php # OKLCH color editor
```

## 🚀 Getting Started

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Create Cache Directory**
   ```bash
   mkdir -p ../../wp-content/cache/twig
   ```

3. **Test the Integration**
   - Add the Twig Demo block to any page
   - Use shortcodes in content
   - Create a page with "Twig Demo Page" template

## 🎯 Benefits

- **Clean Templates**: HTML-like syntax, easy to read
- **Security**: Auto-escaping prevents XSS
- **Reusability**: Component-based architecture
- **Performance**: Template caching
- **Modern Colors**: OKLCH provides better color manipulation
- **Semantic Design**: Colors have meaning, not just values

## 📚 Additional Resources

- [Twig Documentation](https://twig.symfony.com/doc/3.x/)
- [OKLCH Color Space](https://oklch.com/)
- [WordPress Theme Development](https://developer.wordpress.org/themes/)

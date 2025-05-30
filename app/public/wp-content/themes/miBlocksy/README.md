# miBlocksy Child Theme

A modern Blocksy child theme designed for mi Agency projects.

## Features

- **Clean Design System**: Built with CSS custom properties and modern color system
- **Responsive**: Mobile-first approach with flexible layouts
- **Blocksy Integration**: Seamlessly extends Blocksy's powerful features
- **Custom Blocks**: Ready for custom Gutenberg blocks
- **Performance Optimized**: Minimal CSS and efficient loading

## Color System

The theme uses a 4-color system with CSS `color-mix()` for automatic variations:

- **Primary**: `#4d6a6d` (Teal/Green)
- **Secondary**: `#9c5961` (Rose/Pink)  
- **Neutral**: `#a69f95` (Warm Gray)
- **Base**: `#808080` (True Gray)

## Typography

- **Font Family**: Inter (with system fallbacks)
- **Font Sizes**: Responsive scale from 0.875rem to 1.5rem
- **Line Height**: 1.6 for body text, 1.2 for headings

## Spacing Scale

Consistent spacing using CSS custom properties:
- `--mi-spacing-xs`: 0.25rem
- `--mi-spacing-sm`: 0.5rem
- `--mi-spacing-md`: 1rem
- `--mi-spacing-lg`: 1.5rem
- `--mi-spacing-xl`: 2rem
- `--mi-spacing-2xl`: 3rem

## Development

### File Structure
```
miBlocksy/
├── style.css          # Main stylesheet with CSS variables
├── functions.php      # Theme functions and hooks
├── theme.json         # WordPress theme configuration
├── README.md          # This file
└── blocks/            # Custom blocks (create as needed)
```

### Adding Custom Blocks

1. Create a new directory in `/blocks/`
2. Add your block files (block.json, index.js, etc.)
3. Register the block in `functions.php`

### Customizing Colors

Colors are defined in both `style.css` and `theme.json`. To change the color scheme:

1. Update the CSS custom properties in `style.css`
2. Update the color palette in `theme.json`
3. The theme will automatically generate color variations

## Installation

1. Upload the theme to `/wp-content/themes/miBlocksy/`
2. Activate the theme in WordPress Admin
3. Customize via Appearance > Customize (Blocksy options)

## Compatibility

- WordPress 6.0+
- Blocksy Theme (parent theme required)
- PHP 7.4+

## Support

For questions or issues, contact mi Agency development team.

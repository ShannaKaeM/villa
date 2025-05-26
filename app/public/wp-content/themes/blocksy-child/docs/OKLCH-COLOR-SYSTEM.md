# OKLCH Color System Documentation

## Overview

The Villa Community theme uses OKLCH (Oklab Lightness Chroma Hue) as its primary color space. OKLCH is a perceptually uniform color space that provides better color manipulation than traditional RGB or HSL.

## Why OKLCH?

1. **Perceptual Uniformity**: Changes in lightness appear consistent across all colors
2. **Better Gradients**: Creates smoother, more natural color transitions
3. **Predictable Adjustments**: Changing lightness maintains color appearance
4. **Wide Gamut**: Supports colors beyond sRGB when displays allow
5. **Future-Proof**: Part of CSS Color Module Level 4

## Color Components

### Lightness (L)
- Range: 0% to 100%
- 0% = pure black
- 100% = pure white
- Perceptually uniform across all hues

### Chroma (C)
- Range: 0 to 0.4 (practically)
- 0 = gray (no color)
- Higher values = more vivid colors
- Maximum varies by hue and lightness

### Hue (H)
- Range: 0° to 360°
- Color wheel position
- 0°/360° = pink/red
- 90° = yellow
- 180° = cyan
- 270° = blue

## Our Semantic Color System

### Primary Colors (Blue)
```css
--theme-primary-light: oklch(70% 0.045 194);
--theme-primary: oklch(56% 0.064 194);
--theme-primary-dark: oklch(40% 0.075 194);
```

### Secondary Colors (Green)
```css
--theme-secondary-light: oklch(75% 0.065 64);
--theme-secondary: oklch(60% 0.089 64);
--theme-secondary-dark: oklch(45% 0.095 64);
```

### Neutral Colors (Blue-Gray)
```css
--theme-neutral-light: oklch(85% 0.01 210);
--theme-neutral: oklch(66% 0.016 210);
--theme-neutral-dark: oklch(45% 0.02 210);
```

### Base Colors (True Gray)
```css
--theme-base-lightest: oklch(98% 0 0);
--theme-base-light: oklch(90% 0 0);
--theme-base: oklch(66% 0 0);
--theme-base-dark: oklch(35% 0 0);
--theme-base-darkest: oklch(15% 0 0);
```

## Interactive Color Editor

The theme includes an interactive OKLCH color editor at `semantic-color-reference.php`:

1. **Live Preview**: See color changes in real-time
2. **Sliders**: Adjust L, C, and H values independently
3. **Hex Values**: Automatically calculated for compatibility
4. **Copy Function**: Quick copy hex values to clipboard
5. **Blocksy Sync**: Shows which colors map to Blocksy palette slots

### Using the Color Editor

1. Create a new WordPress page
2. Select "Semantic Color Reference" as the template
3. Publish and view the page
4. Adjust sliders to experiment with colors
5. Copy hex values for use in other tools

## CSS Usage

### Direct Usage
```css
.element {
    color: oklch(56% 0.064 194); /* Primary color */
    background: oklch(98% 0 0);   /* Base lightest */
}
```

### Using CSS Variables
```css
.element {
    color: var(--theme-primary);
    background: var(--theme-base-lightest);
}
```

### Creating Variations
```css
/* Lighter version of primary */
.element {
    color: oklch(from var(--theme-primary) calc(l + 10) c h);
}

/* Desaturated version */
.element {
    color: oklch(from var(--theme-primary) l calc(c * 0.5) h);
}
```

## PHP Integration

### Get OKLCH Values
```php
// Using theme helper
$primary = get_theme_mod('semantic_colors')['primary'] ?? 'oklch(56% 0.064 194)';

// Using CSS variable
$color = 'var(--theme-primary)';
```

### In Twig Templates
```twig
{# Direct usage #}
<div style="color: {{ semantic_color('primary') }}">

{# As CSS variable #}
<div style="color: var(--theme-primary)">
```

## Color Accessibility

### Contrast Ratios
- Text on light backgrounds: Use colors with L < 50%
- Text on dark backgrounds: Use colors with L > 70%
- Important UI elements: Maintain WCAG AA compliance (4.5:1)

### Testing Contrast
```javascript
// Quick lightness check
const isDark = lightness < 50;
const textColor = isDark ? 'white' : 'black';
```

## Browser Support

OKLCH is supported in:
- Chrome 111+
- Firefox 113+
- Safari 15.4+
- Edge 111+

### Fallback Strategy
```css
.element {
    color: #2563eb; /* Fallback hex */
    color: oklch(56% 0.064 194); /* Modern browsers */
}
```

## Best Practices

1. **Maintain Consistency**: Keep hue constant when creating color variations
2. **Adjust Lightness First**: For accessible variations, change L before C
3. **Limit Chroma**: High chroma can be harsh; stay below 0.15 for large areas
4. **Test on Devices**: OKLCH can exceed sRGB; test on various displays
5. **Use Semantic Names**: Reference colors by purpose, not appearance

## Tools and Resources

- [OKLCH Color Picker](https://oklch.com/)
- [OKLCH in CSS](https://developer.mozilla.org/en-US/docs/Web/CSS/color_value/oklch)
- [Color.js Library](https://colorjs.io/) - For advanced color manipulation
- [Contrast Checker](https://webaim.org/resources/contrastchecker/)

## Migration from RGB/Hex

To convert existing colors:

1. Use the online OKLCH picker
2. Input your hex/RGB color
3. Note the L, C, H values
4. Replace in your CSS

Example:
- `#3b82f6` → `oklch(56% 0.064 194)`
- `#10b981` → `oklch(60% 0.089 64)`

## Future Enhancements

- Dynamic theme generation based on single hue
- Automatic dark mode with inverted lightness
- Color harmony calculations
- Accessibility warnings in editor

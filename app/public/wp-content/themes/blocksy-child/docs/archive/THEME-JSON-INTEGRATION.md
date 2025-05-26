# Theme.json Integration with Blocksy

## Overview

Our setup uses WordPress's theme.json system to define colors, typography, and spacing that work alongside Blocksy's customizer. This gives you the best of both worlds:

1. **Edit colors in theme.json** → They become available as CSS variables
2. **Edit colors in Blocksy Customizer** → They work alongside theme.json
3. **Your CSS uses both** → No conflicts!

## How It Works

### 1. Theme.json Structure

```json
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

### 2. Generated CSS Variables

WordPress automatically creates CSS variables from theme.json:

```css
/* Color variables */
--wp--preset--color--primary: #2c3e50;
--wp--preset--color--secondary: #3498db;

/* Font size variables */
--wp--preset--font-size--large: 1.25rem;
--wp--preset--font-size--x-large: 1.5rem;

/* Spacing variables */
--wp--preset--spacing--20: 1rem;
--wp--preset--spacing--40: 2rem;
```

### 3. Using in Your CSS

```css
/* Use theme.json colors */
.my-button {
  background: var(--wp--preset--color--primary);
  padding: var(--wp--preset--spacing--20);
  font-size: var(--wp--preset--font-size--medium);
}

/* Combine with Blocksy variables */
.header-special {
  background: var(--paletteColor1); /* From Blocksy */
  border-color: var(--wp--preset--color--accent); /* From theme.json */
}
```

## Editing Colors

### Option 1: Edit theme.json
1. Open `/wp-content/themes/blocksy-child/theme.json`
2. Modify the color values
3. Save and refresh - changes appear immediately

### Option 2: Use Blocksy Customizer
1. Go to Appearance → Customize
2. Change colors in Blocksy's color palette
3. These work alongside theme.json colors

## Available Variables

### Colors
- `var(--wp--preset--color--primary)` - Primary brand color
- `var(--wp--preset--color--secondary)` - Secondary brand color
- `var(--wp--preset--color--accent)` - Accent/highlight color
- `var(--wp--preset--color--neutral)` - Neutral gray
- `var(--wp--preset--color--neutral-dark)` - Dark text color
- `var(--wp--preset--color--neutral-light)` - Light background

### Typography
- `var(--wp--preset--font-size--small)` - 0.875rem
- `var(--wp--preset--font-size--medium)` - 1rem
- `var(--wp--preset--font-size--large)` - 1.25rem
- `var(--wp--preset--font-size--x-large)` - 1.5rem
- `var(--wp--preset--font-size--xx-large)` - 2.5rem

### Spacing
- `var(--wp--preset--spacing--10)` - 0.5rem
- `var(--wp--preset--spacing--20)` - 1rem
- `var(--wp--preset--spacing--30)` - 1.5rem
- `var(--wp--preset--spacing--40)` - 2rem
- `var(--wp--preset--spacing--50)` - 2.5rem

## Using in Carbon Fields Blocks

The theme.json colors are automatically available in our block helpers:

```php
Field::make('select', 'text_color', 'Text Color')
    ->set_options('get_theme_colors_for_blocks')
```

This pulls colors from both theme.json AND Blocksy's customizer.

## Best Practices

1. **Use theme.json for base design system** - Define your core colors, fonts, and spacing
2. **Use Blocksy Customizer for user preferences** - Let users override via the UI
3. **Use CSS for enhancements** - Add gradients, shadows, animations on top
4. **Never hardcode colors in CSS** - Always use variables

## Example: Complete Integration

```css
/* Bad - Hardcoded */
.hero {
  background: #2c3e50;
}

/* Good - Uses variables */
.hero {
  background: var(--wp--preset--color--primary);
  /* Add enhancement */
  background-image: linear-gradient(
    135deg,
    var(--wp--preset--color--primary),
    var(--wp--preset--color--secondary)
  );
}
```

## Troubleshooting

### Colors not updating?
1. Clear cache (browser and any caching plugins)
2. Check theme.json syntax is valid
3. Ensure you're using the correct variable names

### Want to see all available variables?
Add this to any page:
```html
<style>
  body::before {
    content: '';
    /* All theme.json variables are now in DevTools */
  }
</style>
```
Then inspect in DevTools → Computed → CSS Variables

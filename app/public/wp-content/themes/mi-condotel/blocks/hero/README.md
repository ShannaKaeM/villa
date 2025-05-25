# Hero Block Development Guide

This guide provides comprehensive instructions for creating WordPress blocks in the mi-condotel theme, using the Hero block as a reference implementation.

## Overview

The Hero block is a dynamic WordPress block that provides a customizable hero section with:
- Flexible width options
- Rounded corner variations
- Background image support
- Overlay color options
- Extensive text styling controls

## Block Structure

### Required Files

1. **block.json** - Block metadata and attributes
2. **render.php** - Server-side rendering logic
3. **src/index.js** - React component for editor
4. **styles/block.css** - Frontend styles
5. **styles/editor.css** - Editor-specific styles

### Directory Structure
```
blocks/hero/
├── block.json
├── render.php
├── src/
│   └── index.js
├── styles/
│   ├── block.css
│   └── editor.css
└── README.md
```

## Step-by-Step Creation Process

### Step 1: Create block.json

The `block.json` file defines the block metadata and attributes:

```json
{
    "$schema": "https://schemas.wp.org/trunk/block.json",
    "apiVersion": 3,
    "name": "miblocks/hero",
    "version": "1.0.0",
    "title": "Hero",
    "category": "miblocks",
    "icon": "cover-image",
    "description": "A customizable hero section with background image and text overlay",
    "supports": {
        "html": false,
        "align": ["wide", "full"]
    },
    "textdomain": "mi-condotel",
    "editorScript": "file:./index.js",
    "editorStyle": "file:./styles/editor.css",
    "style": "file:./styles/block.css",
    "render": "file:./render.php",
    "attributes": {
        // Define all block attributes here
    }
}
```

### Step 2: Define Attributes

Key attribute patterns used in this theme:

#### Text Attributes
```json
"title": {
    "type": "string",
    "default": ""
},
"titleSize": {
    "type": "string",
    "default": "3xl",
    "enum": ["xs", "sm", "base", "lg", "xl", "2xl", "3xl", "4xl", "5xl"]
}
```

#### Color Attributes
```json
"overlayColor": {
    "type": "string",
    "default": "primary",
    "enum": ["primary", "secondary", "tertiary", "neutral", "base", "white", "black"]
},
"overlayVariation": {
    "type": "string",
    "default": "med",
    "enum": ["lightest", "light", "med", "dark", "darkest"]
}
```

#### Layout Attributes
```json
"widthOption": {
    "type": "string",
    "default": "90",
    "enum": ["90", "content"]
},
"borderRadius": {
    "type": "string",
    "default": "2xl",
    "enum": ["none", "xs", "sm", "md", "lg", "xl", "2xl", "3xl", "4xl", "full"]
}
```

### Step 3: Create render.php

The render.php file handles server-side rendering:

```php
<?php
/**
 * Hero Block Template
 */

// Get attributes with defaults
$title = $attributes['title'] ?? '';
$widthOption = $attributes['widthOption'] ?? '90';

// Build wrapper classes
$wrapper_classes = ['hero-block'];
if ($widthOption === 'content') {
    $wrapper_classes[] = 'hero-block--content-width';
}

// Helper function for color variables
function get_color_var($color, $variation = 'med') {
    if ($color === 'white' || $color === 'black') {
        return "var(--color-{$color})";
    }
    return $variation === 'med' 
        ? "var(--color-{$color}-med)" 
        : "var(--color-{$color}-{$variation})";
}

// Build inline styles
$container_style = '';
if (!empty($backgroundImage)) {
    $container_style .= "background-image: url({$backgroundImage});";
}

// Render the block
?>
<div <?php echo get_block_wrapper_attributes(['class' => implode(' ', $wrapper_classes)]); ?>>
    <div class="hero-block__container" style="<?php echo esc_attr($container_style); ?>">
        <!-- Block content -->
    </div>
</div>
```

### Step 4: Create React Component (src/index.js)

Key patterns for the editor component:

```javascript
import { registerBlockType } from '@wordpress/blocks';
import { 
    InspectorControls, 
    MediaUpload, 
    useBlockProps 
} from '@wordpress/block-editor';

const Edit = ({ attributes, setAttributes }) => {
    // Helper for color variables
    const getColorVar = (color, variation = 'med') => {
        if (color === 'white' || color === 'black') {
            return `var(--color-${color})`;
        }
        return variation === 'med' 
            ? `var(--color-${color}-med)` 
            : `var(--color-${color}-${variation})`;
    };

    // Build classes and styles
    const blockProps = useBlockProps({
        className: wrapperClasses.join(' '),
    });

    return (
        <>
            <InspectorControls>
                {/* Add control panels */}
            </InspectorControls>
            <div {...blockProps}>
                {/* Block preview */}
            </div>
        </>
    );
};
```

### Step 5: Create Styles

#### block.css - Frontend Styles
```css
.hero-block {
    margin-bottom: var(--spacing-2xl);
}

/* Width variations */
.hero-block__container {
    width: 90%;
    margin: 0 auto;
}

.hero-block--content-width .hero-block__container {
    width: 100%;
    max-width: var(--site-content-width);
}

/* Border radius modifiers */
.hero-block__container--radius-sm {
    border-radius: var(--radius-sm);
}
```

#### editor.css - Editor Styles
```css
/* Ensure full width in editor */
.wp-block-miblocks-hero {
    max-width: none !important;
}
```

## CSS Variable System

This theme uses a comprehensive CSS variable system. Key variables:

### Colors
- `--color-primary-[variation]`
- `--color-secondary-[variation]`
- `--color-tertiary-[variation]`
- `--color-neutral-[variation]`
- `--color-base-[variation]`
- `--color-white`
- `--color-black`

Variations: `lightest`, `light`, `med`, `dark`, `darkest`

### Typography
- Font sizes: `--font-size-[xs|sm|base|lg|xl|2xl|3xl|4xl|5xl]`
- Font weights: `--font-weight-[extra-light|light|normal|semibold|bold|extra-bold]`
- Letter spacing: `--letter-spacing-[tighter|tight|normal|wide|wider|widest]`
- Line height: `--line-height-[tight|snug|normal|relaxed|loose]`

### Spacing
- `--spacing-[xs|sm|md|lg|xl|2xl|3xl|4xl|5xl]`

### Border Radius
- `--radius-[xs|sm|md|lg|xl|2xl|3xl|4xl|full]`

### Layout
- `--site-content-width: 1200px`

## Control Patterns

### Color Controls
```javascript
<SelectControl
    label={__('Color', 'mi-condotel')}
    value={color}
    options={[
        { label: 'Primary', value: 'primary' },
        { label: 'Secondary', value: 'secondary' },
        // etc.
    ]}
    onChange={(value) => setAttributes({ color: value })}
/>
```

### Conditional Variation Control
Show variation control only for colors that support it:
```javascript
{color !== 'white' && color !== 'black' && (
    <SelectControl
        label={__('Color Variation', 'mi-condotel')}
        value={variation}
        options={[/* variation options */]}
        onChange={(value) => setAttributes({ variation: value })}
    />
)}
```

## Build Process

1. Ensure you have Node.js and npm installed
2. Navigate to the theme directory
3. Run `npm install` to install dependencies
4. Run `npm run build` to compile the block
5. The block will be automatically registered via the theme's block registration system

## Testing Checklist

- [ ] Block appears in the block inserter
- [ ] All controls function correctly in the editor
- [ ] Preview updates live as settings change
- [ ] Frontend rendering matches editor preview
- [ ] Responsive behavior works correctly
- [ ] All CSS variables are properly applied
- [ ] No console errors in editor or frontend

## Common Patterns

### Dynamic Color with Opacity
```php
$overlay_style = sprintf(
    'background-color: color-mix(in srgb, %s %d%%, transparent);',
    get_color_var($overlayColor, $overlayVariation),
    $overlayOpacity * 100
);
```

### BEM Class Naming
- Block: `hero-block`
- Element: `hero-block__container`
- Modifier: `hero-block--content-width`

### Responsive Design
```css
@media (max-width: 768px) {
    .hero-block__container {
        width: 95%;
    }
}
```

## Troubleshooting

1. **Block not appearing**: Check that block.json is valid JSON and the block is registered in functions.php
2. **Styles not loading**: Ensure style files are referenced correctly in block.json
3. **Editor crashes**: Check for JavaScript errors in the console
4. **PHP errors**: Enable WP_DEBUG to see detailed error messages

## Additional Resources

- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [Block API Reference](https://developer.wordpress.org/block-editor/reference-guides/block-api/)
- Theme CSS Architecture Guide (see CSS_ARCHITECTURE_GUIDE.md)

# Villa Community Theme Development Guide

## Table of Contents
1. [Project Overview](#project-overview)
2. [Theme Architecture](#theme-architecture)
3. [CSS Architecture](#css-architecture)
4. [Native Block Development](#native-block-development)
5. [Blocksy Theme Integration](#blocksy-theme-integration)
6. [Development Workflow](#development-workflow)
7. [Best Practices](#best-practices)

---

## Project Overview

Villa Community is a WordPress theme built as a child theme of Blocksy, designed for a property listing and community website. The theme uses a modern development approach with native WordPress blocks and a semantic CSS architecture.

### Key Features
- Custom post types (Properties, Businesses, Articles)
- Native WordPress blocks with React-based editors
- Global CSS architecture with semantic classes
- Blocksy parent theme integration
- Custom color system using OKLCH color space

### Technology Stack
- **WordPress**: 6.x
- **Parent Theme**: Blocksy
- **Block Development**: @wordpress/scripts, React
- **CSS**: Custom semantic CSS with CSS variables
- **Build Tools**: npm, webpack (via wp-scripts)

---

## Theme Architecture

### Directory Structure
```
blocksy-child/
├── assets/
│   ├── css/
│   │   ├── main.css          # Entry point importing all styles
│   │   ├── base.css          # Reset, typography, foundations
│   │   ├── layout.css        # Layout patterns and grids
│   │   ├── components.css    # Reusable UI components
│   │   ├── filters.css       # Filter system styles
│   │   ├── states.css        # UI states (loading, empty, etc.)
│   │   └── utilities.css     # Utility classes
│   └── js/
│       └── main.js           # Global JavaScript
├── blocks/
│   └── card-loop-native/     # Native block example
│       ├── block.json        # Block metadata
│       ├── render.php        # Server-side rendering
│       ├── src/
│       │   ├── index.js      # Block registration
│       │   ├── edit.js       # Editor component
│       │   ├── style.scss    # Frontend styles
│       │   └── editor.scss   # Editor-specific styles
│       └── build/            # Compiled assets
├── functions.php             # Theme functions and hooks
├── style.css                 # Theme metadata
└── package.json             # Node dependencies

```

### Key Files

#### functions.php
Contains all theme initialization, including:
- CSS/JS enqueuing
- Block registration
- Custom post type registration
- Theme support declarations

#### package.json
Manages build dependencies:
```json
{
  "name": "blocksy-child-blocks",
  "version": "1.0.0",
  "scripts": {
    "build": "wp-scripts build blocks/*/src/index.js --output-path=blocks/$npm_config_block/build",
    "start": "wp-scripts start blocks/*/src/index.js --output-path=blocks/$npm_config_block/build"
  },
  "devDependencies": {
    "@wordpress/scripts": "^26.19.0"
  }
}
```

---

## CSS Architecture

### Philosophy
The theme uses a **semantic CSS approach** with global, reusable styles. This provides:
- Better maintainability
- Consistent design patterns
- Smaller file sizes
- AI-friendly structure

### CSS Structure

#### 1. Base Styles (`base.css`)
Foundation styles including:
- CSS reset
- Typography scale
- Default element styles
- CSS custom properties (variables)

#### 2. Layout Patterns (`layout.css`)
Common layout structures:
```css
/* Container System */
.container { /* Responsive container */ }
.container--xl { /* Extra large container */ }

/* Grid System */
.grid { /* Base grid */ }
.grid--responsive { /* Auto-responsive grid */ }

/* Layout with Sidebar */
.layout { /* Layout wrapper */ }
.layout--sidebar-left { /* Left sidebar layout */ }
.layout__main { /* Main content area */ }
.layout__sidebar { /* Sidebar area */ }
```

#### 3. Components (`components.css`)
Reusable UI components:
```css
/* Card Component */
.card { /* Card container */ }
.card__image { /* Card image wrapper */ }
.card__content { /* Card content area */ }
.card__title { /* Card title */ }
.card__description { /* Card description */ }
.card__actions { /* Card action buttons */ }

/* Button Component */
.btn { /* Base button */ }
.btn--primary { /* Primary button variant */ }
.btn--secondary { /* Secondary button variant */ }
```

#### 4. Filter System (`filters.css`)
Filter sidebar components:
```css
.filter-sidebar { /* Filter container */ }
.filter-header { /* Filter header */ }
.filter-section { /* Filter section */ }
.filter-checkbox { /* Checkbox filter */ }
```

#### 5. UI States (`states.css`)
State-based styles:
```css
.empty-state { /* Empty results state */ }
.loading-state { /* Loading spinner */ }
.error-state { /* Error messages */ }
```

### Color System
Custom OKLCH-based color system with CSS variables:
```css
:root {
  /* Primary Colors */
  --color-primary: oklch(60% 0.15 200);
  --color-primary-light: oklch(70% 0.15 200);
  --color-primary-dark: oklch(50% 0.15 200);
  
  /* Secondary Colors */
  --color-secondary: oklch(60% 0.15 20);
  --color-secondary-light: oklch(70% 0.15 20);
  --color-secondary-dark: oklch(50% 0.15 20);
  
  /* Neutral Colors */
  --color-neutral: oklch(65% 0.05 60);
  --color-base: oklch(60% 0 0);
}
```

---

## Native Block Development

### Block Creation Process

#### 1. Create Block Structure
```bash
# Create block directory
mkdir blocks/my-block-name

# Create required files
touch blocks/my-block-name/block.json
touch blocks/my-block-name/render.php
mkdir blocks/my-block-name/src
```

#### 2. Define Block Metadata (`block.json`)
```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "miblocks/my-block",
  "version": "1.0.0",
  "title": "My Block",
  "category": "miblocks",
  "icon": "grid-view",
  "description": "Block description",
  "supports": {
    "html": false,
    "align": ["wide", "full"]
  },
  "attributes": {
    "postType": {
      "type": "string",
      "default": "post"
    }
  },
  "textdomain": "miblocks",
  "editorScript": "file:./build/index.js",
  "editorStyle": "file:./build/index.css",
  "style": "file:./build/style-index.css",
  "render": "file:./render.php"
}
```

#### 3. Create Editor Component (`src/edit.js`)
```javascript
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
    const { postType } = attributes;
    
    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Settings', 'miblocks')}>
                    <SelectControl
                        label={__('Post Type', 'miblocks')}
                        value={postType}
                        onChange={(value) => setAttributes({ postType: value })}
                        options={[
                            { label: 'Posts', value: 'post' },
                            { label: 'Properties', value: 'property' }
                        ]}
                    />
                </PanelBody>
            </InspectorControls>
            
            <div {...useBlockProps()}>
                <p>Block preview content</p>
            </div>
        </>
    );
}
```

#### 4. Register Block (`src/index.js`)
```javascript
import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import metadata from '../block.json';

registerBlockType(metadata.name, {
    edit: Edit,
});
```

#### 5. Server-side Rendering (`render.php`)
```php
<?php
/**
 * Block server-side rendering
 *
 * @param array    $attributes Block attributes
 * @param string   $content    Block content
 * @param WP_Block $block      Block instance
 */

// Ensure attributes exist
$attributes = $attributes ?? [];
$post_type = $attributes['postType'] ?? 'post';

// Build query
$query = new WP_Query([
    'post_type' => $post_type,
    'posts_per_page' => 10,
    'post_status' => 'publish'
]);

// Get block wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'my-custom-block'
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <?php if ($query->have_posts()) : ?>
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <article>
                <h3><?php the_title(); ?></h3>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
</div>

<?php
// Return output
echo ob_get_clean();
```

#### 6. Build the Block
```bash
# Build for production
npm run build

# Or watch for development
npm run start
```

### Block Registration in functions.php
```php
/**
 * Register native WordPress blocks
 */
function mi_register_native_blocks() {
    $blocks_dir = get_stylesheet_directory() . '/blocks';
    
    if (!is_dir($blocks_dir)) {
        return;
    }
    
    // Find all block.json files
    $block_json_files = glob($blocks_dir . '/*/block.json');
    
    foreach ($block_json_files as $block_json) {
        register_block_type(dirname($block_json));
    }
}
add_action('init', 'mi_register_native_blocks');
```

---

## Blocksy Theme Integration

### Child Theme Setup
The Villa Community theme extends Blocksy through proper child theme architecture:

#### 1. Theme Declaration (`style.css`)
```css
/*
Theme Name: Villa Community
Template: blocksy
Author: MI Agency
Description: Custom theme for Villa Community
Version: 1.0.0
*/
```

#### 2. Enqueuing Parent Styles
```php
function villa_enqueue_styles() {
    // Parent theme styles
    wp_enqueue_style('blocksy-parent-style', 
        get_template_directory_uri() . '/style.css'
    );
    
    // Child theme styles
    wp_enqueue_style('villa-child-style', 
        get_stylesheet_uri(), 
        ['blocksy-parent-style']
    );
    
    // Global CSS architecture
    wp_enqueue_style('villa-main-css', 
        get_stylesheet_directory_uri() . '/assets/css/main.css', 
        ['villa-child-style']
    );
}
add_action('wp_enqueue_scripts', 'villa_enqueue_styles');
```

### Blocksy Overrides

#### 1. Color System Integration
Override Blocksy's color variables:
```css
/* In main.css or base.css */
:root {
    /* Override Blocksy colors */
    --theme-palette-color-1: var(--color-primary);
    --theme-palette-color-2: var(--color-secondary);
    --theme-palette-color-3: var(--color-neutral);
    --theme-palette-color-4: var(--color-base);
}
```

#### 2. Typography Overrides
```css
/* Override Blocksy typography */
body {
    --theme-font-family: -apple-system, BlinkMacSystemFont, 
                         "Segoe UI", Roboto, sans-serif;
    --theme-font-size: 16px;
    --theme-line-height: 1.6;
}
```

#### 3. Layout Modifications
```css
/* Adjust Blocksy container widths */
.ct-container {
    max-width: var(--container-max-width, 1200px);
}
```

---

## Development Workflow

### Initial Setup
1. Clone the repository
2. Navigate to the theme directory
3. Install dependencies:
   ```bash
   npm install
   ```

### Creating a New Block
1. Create block structure in `/blocks/block-name/`
2. Define block.json with metadata
3. Create React components in `/src/`
4. Add server-side rendering in `render.php`
5. Build the block:
   ```bash
   npm run build
   ```

### CSS Development
1. Identify if styles are global or block-specific
2. For global styles:
   - Add to appropriate file in `/assets/css/`
   - Use semantic class names
   - Define CSS variables for customizable values
3. For block-specific styles:
   - Add to block's `style.scss`
   - Scope with block class
   - Import global variables

### Testing Workflow
1. **Local Development**: Use Local by Flywheel or similar
2. **Build Process**: Run `npm run build` after changes
3. **Browser Testing**: Test in multiple browsers
4. **Block Testing**: Verify in both editor and frontend
5. **Responsive Testing**: Check all breakpoints

---

## Best Practices

### 1. Block Development
- Always use semantic HTML
- Implement proper accessibility (ARIA labels, keyboard navigation)
- Provide meaningful default values
- Use WordPress internationalization functions
- Handle edge cases (no results, errors)

### 2. CSS Guidelines
- Use CSS variables for all colors and spacing
- Follow BEM-like naming for components
- Keep specificity low
- Mobile-first responsive design
- Avoid !important

### 3. Performance
- Minimize HTTP requests
- Use WordPress enqueue system
- Lazy load images
- Optimize build output
- Cache static assets

### 4. Code Organization
- One block per directory
- Consistent file naming
- Clear documentation
- Modular components
- Version control best practices

### 5. WordPress Standards
- Follow WordPress Coding Standards
- Use proper hooks and filters
- Sanitize and escape data
- Implement nonces for security
- Use WordPress APIs

---

## Troubleshooting

### Common Issues

#### Block Not Appearing
1. Check block.json is valid JSON
2. Verify build process completed
3. Check browser console for errors
4. Ensure block is registered in functions.php

#### Styles Not Loading
1. Verify CSS file paths in block.json
2. Check build output exists
3. Inspect browser network tab
4. Clear WordPress cache

#### Build Errors
1. Delete node_modules and reinstall
2. Check Node.js version compatibility
3. Verify package.json scripts
4. Check for syntax errors in source files

### Debug Mode
Enable WordPress debug mode in `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

---

## Resources

### Documentation
- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [Blocksy Documentation](https://creativethemes.com/blocksy/docs/)
- [@wordpress/scripts](https://www.npmjs.com/package/@wordpress/scripts)

### Tools
- [WordPress Plugin Boilerplate](https://wppb.me/)
- [GenerateWP](https://generatewp.com/)
- [Block Builder](https://blockbuilder.dev/)

### Community
- WordPress Stack Exchange
- Blocksy Support Forum
- WordPress Developer Resources

---

## Conclusion

The Villa Community theme demonstrates modern WordPress development practices with a focus on maintainability, performance, and developer experience. By combining native blocks with a semantic CSS architecture and proper Blocksy integration, the theme provides a solid foundation for building complex WordPress sites.

For questions or contributions, please refer to the project repository or contact the development team.

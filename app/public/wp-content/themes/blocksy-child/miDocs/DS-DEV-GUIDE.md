# Villa Community Theme Development Guide

## Table of Contents
1. [Project Overview](#project-overview)
2. [Theme Architecture](#theme-architecture)
3. [CSS Architecture](#css-architecture)
   - [Component Naming Convention](#component-naming-convention)
   - [Spacing System](#spacing-system)
4. [Native Block Development](#native-block-development)
5. [Blocksy Theme Integration](#blocksy-theme-integration)
6. [Development Workflow](#development-workflow)
7. [Best Practices](#best-practices)
8. [Troubleshooting](#troubleshooting)
9. [Resources](#resources)
10. [Recent Updates](#recent-updates)
11. [AI Block Building Instructions](#ai-block-building-instructions)
12. [Conclusion](#conclusion)

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
│       ├── partials/         # Template partials
│       │   └── filters.php   # Filter sidebar template
│       ├── src/
│       │   ├── index.js      # Block registration
│       │   ├── edit.js       # Editor component
│       │   ├── view.js       # Frontend JavaScript
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

### Component Naming Convention
To avoid conflicts with the parent Blocksy theme and other plugins, the theme uses a prefixed naming system:

#### Naming Prefixes
- **`mi-`** - WordPress blocks (e.g. `mi-card-loop`, `mi-filter-block`)
- **`m-`** - Reusable components (e.g. `m-card`, `m-button`, `m-badge`)

#### Component Class Structure (BEM-like)
```css
/* Component */
.m-card { /* Main component */ }

/* Elements */
.m-card__image { /* Child element */ }
.m-card__content { /* Child element */ }
.m-card__title { /* Child element */ }

/* Modifiers */
.m-card--minimal { /* Component variant */ }
.m-card--horizontal { /* Component variant */ }
```

#### Benefits of Prefixed Components
- **Conflict Prevention**: No clashes with parent theme CSS
- **Clear Ownership**: Easy to identify custom vs. theme styles  
- **Future-Proof**: Safe from plugin conflicts
- **Maintainable**: Consistent naming across all components

### Spacing System
The theme uses a numbered spacing scale for consistent and flexible spacing:

#### Spacing Scale
```css
/* Numbered Spacing Scale */
--spacing-1: 0.25rem;   /* 4px  - Tight spacing, borders */
--spacing-2: 0.5rem;    /* 8px  - Small padding, margins */
--spacing-3: 0.75rem;   /* 12px - Button padding, form inputs */
--spacing-4: 1rem;      /* 16px - Standard spacing */
--spacing-5: 1.25rem;   /* 20px - Cards, Sidebars */
--spacing-6: 1.5rem;    /* 24px - Section spacing */
--spacing-8: 2rem;      /* 32px - Large sections */
--spacing-10: 2.5rem;   /* 40px - Hero spacing */
--spacing-12: 3rem;     /* 48px - Major sections */
--spacing-16: 4rem;     /* 64px - Landing sections */
```

#### Usage Guidelines
The numbered system provides flexibility while maintaining consistency:

- **`--spacing-1/2`**: Small UI elements and tight spacing
- **`--spacing-3/4`**: Standard component spacing  
- **`--spacing-5`**: Card content and sidebar padding (most common)
- **`--spacing-6/8`**: Section and layout spacing
- **`--spacing-10+`**: Large page sections and hero areas

#### Implementation Example
```css
/* Component using numbered spacing */
.m-card {
  padding: 0; /* No outer padding for full-bleed images */
}

.m-card__content {
  padding: var(--spacing-5); /* Standard card content padding */
}

.filter-sidebar {
  padding: var(--spacing-5); /* Same as cards for consistency */
}

/* Easy to adjust by changing the number */
.m-card__content--compact {
  padding: var(--spacing-3); /* Smaller padding variant */
}
```

#### Benefits
- **Flexibility**: Easy to adjust by changing the number (spacing-3 → spacing-4)
- **Consistency**: Unified spacing across all components
- **Simplicity**: No need to remember semantic meanings
- **Scalability**: Clear progression from small to large spacing

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
.m-card {
  background: var(--color-bg-primary);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.m-card__image {
  aspect-ratio: 16/10;
  overflow: hidden;
}

.m-card__content {
  padding: var(--spacing-5);
}

/* Button Component */
.btn {
  padding: var(--spacing-2) var(--spacing-3);
  border-radius: 6px;
  font-weight: 500;
  transition: all 0.2s ease;
}

.btn--primary {
  background: var(--color-primary);
  color: white;
}
```

#### 4. Filter System (`filters.css`)
Comprehensive filter UI components:
```css
/* Filter Layout */
.filter-sidebar {
    padding: var(--spacing-5);
    background: var(--color-bg-secondary);
}

/* Filter Sections */
.filter-section {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--color-border);
}

/* Filter Grid */
.filter-group {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}

/* Checkbox Styling */
.filter-checkbox {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    font-size: 0.875rem;
}

.filter-checkbox__indicator {
    width: 16px;
    height: 16px;
    margin-right: 0.5rem;
}

/* Range Sliders */
.filter-range__input {
    width: 100%;
    -webkit-appearance: none;
    appearance: none;
}
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
mkdir blocks/my-block-name/partials  # For template parts
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
  "viewScript": "file:./build/view.js",
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

### Card Loop Block Implementation

The Card Loop block is a comprehensive example of native block development with advanced features:

#### Features
- Dynamic post type selection
- AJAX-powered filtering
- Responsive grid layouts
- Multiple card styles
- Pagination support
- Customizable filters

#### Block Structure
```
card-loop-native/
├── block.json          # Block metadata
├── render.php          # Server-side rendering
├── partials/
│   └── filters.php     # Filter template
├── src/
│   ├── index.js        # Block registration
│   ├── edit.js         # Editor component
│   ├── view.js         # Frontend interactions
│   ├── style.scss      # Frontend styles
│   └── editor.scss     # Editor styles
└── build/              # Compiled assets
```

#### AJAX Implementation
The block uses WordPress AJAX for dynamic filtering:

```php
// functions.php - AJAX handler
function mi_ajax_filter_properties() {
    // Verify nonce
    check_ajax_referer('mi_ajax_nonce', 'nonce');
    
    // Get filter parameters
    $filters = $_POST['filters'] ?? [];
    $columns = isset($_POST['columns']) ? intval($_POST['columns']) : 3;
    
    // Build query
    $args = [
        'post_type' => 'property',
        'posts_per_page' => 12,
        'meta_query' => [],
        'tax_query' => []
    ];
    
    // Apply filters...
    
    // Return HTML
    wp_send_json_success(['html' => $html]);
}
add_action('wp_ajax_mi_filter_properties', 'mi_ajax_filter_properties');
add_action('wp_ajax_nopriv_mi_filter_properties', 'mi_ajax_filter_properties');
```

#### Frontend JavaScript
```javascript
// view.js - Filter interactions
function initializeFilters(block) {
    const checkboxes = block.querySelectorAll('.filter-checkbox__input');
    const rangeInputs = block.querySelectorAll('.filter-range__input');
    
    // Handle checkbox changes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            applyFilters(block);
        });
    });
    
    // Handle range changes
    rangeInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const valueDisplay = input.closest('.filter-range')
                .querySelector('.filter-range__value span');
            if (valueDisplay) {
                valueDisplay.textContent = e.target.value;
            }
        });
    });
}
```

#### Filter Organization
Filters are displayed in a specific order for optimal UX:
1. **Location** - 2-column grid
2. **Property Type** - 2-column grid  
3. **Bedrooms** - Range slider
4. **Bathrooms** - Range slider
5. **Amenities** - Single column (at bottom)

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

/**
 * Enqueue frontend scripts for blocks
 */
function mi_enqueue_frontend_scripts() {
    if (has_block('miblocks/card-loop-native')) {
        wp_enqueue_script(
            'miblocks-ajax',
            get_stylesheet_directory_uri() . '/blocks/card-loop-native/build/view.js',
            array('wp-element'),
            filemtime(get_stylesheet_directory() . '/blocks/card-loop-native/build/view.js'),
            true
        );
        
        // Localize script with AJAX data
        wp_localize_script('miblocks-ajax', 'mi_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mi_ajax_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'mi_enqueue_frontend_scripts');
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

### Building Blocks
```bash
# Build all blocks
npm run build

# Build specific block
npm run build -- --config blocks/card-loop-native/src/index.js

# Watch mode for development
npm run start
```

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
- Use semantic class names that describe purpose, not appearance

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
- Localize JavaScript when passing data from PHP

### 6. AJAX Best Practices
- Always verify nonces
- Sanitize all input data
- Return consistent response formats
- Handle errors gracefully
- Show loading states
- Maintain accessibility during updates

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

#### AJAX Not Working
1. Check nonce verification
2. Verify AJAX URL is correct
3. Check browser network tab for response
4. Ensure actions are registered for both logged-in and logged-out users

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
- [Local by Flywheel](https://localwp.com/)

### Community
- WordPress Stack Exchange
- Blocksy Support Forum
- WordPress Developer Resources

---

## Recent Updates

### Card Loop Block Enhancements (May 2024)
1. **Filter Layout Improvements**
   - Reorganized filters with specific ordering
   - Condensed layout with 2-column grid for Location/Property Type
   - Moved Amenities to bottom with single column layout
   - Reduced padding and font sizes for compact display

2. **AJAX Functionality**
   - Added columns parameter to maintain grid layout after filtering
   - Improved error handling and loading states
   - Maintained accessibility during updates

3. **CSS Architecture Updates**
   - Enhanced filter component styles
   - Added proper CSS variable fallbacks
   - Fixed vendor prefix issues
   - Improved responsive behavior

4. **JavaScript Enhancements**
   - Updated range slider value display logic
   - Improved filter state management
   - Added proper event delegation

### Component Refactoring (May 2024)
1. **Component Namespace Implementation**
   - Refactored all card components to use `m-` prefix
   - Updated `.card` → `.m-card` and all child elements
   - Applied changes across `components.css` and `render.php` templates
   - Eliminated conflicts with parent Blocksy theme styles

2. **Spacing System**
   - Implemented numbered spacing scale
   - Created scale from `--spacing-1` (4px) to `--spacing-16` (64px)
   - Added usage guidelines for each spacing level
   - Replaced specific tokens with numbered ones across components

3. **Naming Convention Standardization**
   - Established `mi-` prefix for WordPress blocks
   - Established `m-` prefix for reusable components
   - Created BEM-like structure for component organization
   - Documented clear ownership and usage patterns

4. **Benefits Achieved**
   - **Conflict Prevention**: No more CSS clashes with parent theme
   - **Future-Proof**: Safe from plugin conflicts
   - **Maintainable**: Clear component ownership and simple spacing
   - **Flexible**: Easy to adjust spacing by changing numbers (spacing-3 → spacing-4)

---

## AI Block Building Instructions

When building blocks for the Villa Community theme, follow these **strict requirements**:

### Core Development Rules

#### Rule 1: Always Use Grid Over Flex
- **REQUIRED**: Use CSS Grid for all layout systems
- **AVOID**: Flexbox should only be used for simple single-direction alignment
- **Rationale**: Grid provides better responsiveness and consistent layout behavior

> **⚠️ LEGACY FLEX REVIEW NEEDED**: Some existing components still use flex and should be reviewed for potential conversion to grid:
> - `.view-controls` - Filter view toggle controls
> - `.filter-bar` - Filter sidebar components  
> - Other utility components
> 
> These should be evaluated on a case-by-case basis to determine if grid would provide better layout control.

```css
/* ✅ CORRECT: Use Grid */
.m-card-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-4);
}

/* ❌ INCORRECT: Avoid Flex for layouts */
.m-card-container {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-4);
}
```

#### Rule 2: Follow OUTPUT_TEMPLATE_FORMAT
- **REQUIRED**: All blocks must follow the established template structure
- **Structure**: Use the template format from existing blocks (card-loop-native)
- **Components**: Maintain consistent file organization and naming

```php
<?php
// ✅ CORRECT: Follow template structure
$attributes = $attributes ?? [];
$post_type = $attributes['postType'] ?? 'post';

// Build consistent wrapper
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'mi-block-name'
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <!-- Block content following established patterns -->
</div>
```

#### Rule 3: Always Use the Design System
- **REQUIRED**: Use only established CSS variables and component classes
- **Spacing**: Use numbered spacing system (`--spacing-1` through `--spacing-16`)
- **Components**: Use `m-` prefixed component classes
- **Colors**: Use theme color variables only

```css
/* ✅ CORRECT: Use design system */
.m-my-component {
    padding: var(--spacing-4);
    margin-bottom: var(--spacing-3);
    background: var(--color-primary-light);
    border-radius: var(--border-radius-md);
}

/* ❌ INCORRECT: Don't use arbitrary values */
.m-my-component {
    padding: 20px;
    margin-bottom: 15px;
    background: #97bfc4;
    border-radius: 8px;
}
```

### Block Development Checklist

When creating new blocks, ensure:

#### ✅ Architecture Requirements
- [ ] Block uses `mi-` prefix for the main block class
- [ ] Components use `m-` prefix for reusable elements
- [ ] Grid layout is used for all multi-item displays
- [ ] Design system variables are used exclusively
- [ ] BEM-like naming convention is followed

#### ✅ File Structure Requirements
- [ ] `block.json` with proper metadata
- [ ] `render.php` following template format
- [ ] `src/edit.js` for editor component
- [ ] `src/view.js` for frontend interactions (if needed)
- [ ] `src/style.scss` for frontend styles
- [ ] `src/editor.scss` for editor-specific styles

#### ✅ CSS Requirements
- [ ] No hardcoded values (use CSS variables)
- [ ] Grid-first layout approach
- [ ] Proper component naming with `m-` prefix
- [ ] Responsive design using established breakpoints
- [ ] Semantic class names following BEM patterns

#### ✅ PHP Requirements
- [ ] Proper attribute sanitization
- [ ] Consistent error handling
- [ ] Template structure matching existing blocks
- [ ] Proper escaping of all output
- [ ] AJAX handlers with nonce verification (if applicable)

#### ✅ JavaScript Requirements
- [ ] Modern ES6+ syntax
- [ ] Proper event delegation
- [ ] Error handling and loading states
- [ ] Accessibility considerations
- [ ] No jQuery dependencies

### Code Quality Standards

#### Performance
- Use semantic CSS that can be cached and reused
- Minimize JavaScript dependencies
- Implement lazy loading where appropriate
- Optimize images and assets

#### Maintainability
- Follow established naming conventions
- Use consistent code formatting
- Document complex logic with comments
- Keep components modular and focused

#### Accessibility
- Include proper ARIA labels and roles
- Ensure keyboard navigation support
- Maintain color contrast standards
- Implement screen reader compatibility

### Example Implementation

Here's a minimal example following all rules:

```css
/* style.scss - Following design system */
.mi-example-block {
    padding: var(--spacing-5);
}

.m-example-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-4);
}

.m-example-card {
    background: var(--color-base-white);
    padding: var(--spacing-4);
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-sm);
}
```

```php
<?php
// render.php - Following template format
$attributes = $attributes ?? [];
$items_per_page = $attributes['itemsPerPage'] ?? 6;

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'mi-example-block'
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="m-example-grid">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <div class="m-example-card">
                <!-- Card content -->
            </div>
        <?php endwhile; ?>
    </div>
</div>
```

### Violation Consequences

Failing to follow these rules will result in:
- CSS conflicts with parent theme
- Inconsistent user experience
- Maintenance difficulties
- Performance issues
- Failed code reviews

**Remember**: These rules ensure consistency, prevent conflicts, and maintain the high quality of the Villa Community theme.

---

## Conclusion

The Villa Community theme demonstrates modern WordPress development practices with a focus on maintainability, performance, and developer experience. By combining native blocks with a semantic CSS architecture and proper Blocksy integration, the theme provides a solid foundation for building complex WordPress sites.

For questions or contributions, please refer to the project repository or contact the development team.

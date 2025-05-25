# CSS Architecture Guide

This document outlines the CSS architecture for the mi-condotel custom WordPress theme.

## Table of Contents
1. [CSS Architecture Overview](#css-architecture-overview)
2. [CSS File Structure](#css-file-structure)
3. [Design System & Variables](#design-system--variables)
4. [Block Development Guide](#block-development-guide)

---

## CSS Architecture Overview

The mi-condotel theme is a **standalone custom WordPress theme** that uses a plain CSS architecture separating global theme styles from block-specific styles.

### Key Architecture Decisions
- **No SASS/SCSS**: All styles use plain CSS for simplicity and maintainability
- **Modern CSS Features**: Leverages CSS variables and color-mix() instead of preprocessors
- **Island Architecture**: Each block is completely independent
- **No CSS Build Process**: CSS files are edited directly without compilation
- **Custom Theme**: Standalone theme, not a child theme

### CSS Loading Order
1. **WordPress Core CSS** - Base WordPress styles
2. **Theme style.css** - Main theme stylesheet
3. **Google Fonts** - Poppins and Merriweather
4. **main.css** - Main theme styles (imports base.css)
5. **Individual Block CSS** - Loaded only when blocks are used on a page
6. **Block Editor CSS** - Loaded in editor context only

### Key Principles
- **Single Source of Truth**: All design tokens defined in `base.css`
- **CSS Variables**: Used throughout for consistency
- **Block Isolation**: Each block has its own CSS file
- **No Web Components**: Blocks use server-side rendering
- **Direct Editing**: No build step required for CSS changes

---

## CSS File Structure

```
mi-condotel/
├── style.css                    # Main theme stylesheet
├── assets/
│   └── css/
│       ├── base.css            # Core design system & variables
│       └── main.css            # Theme entry point & overrides
├── blocks/
│   ├── page-header/
│   │   ├── styles/
│   │   │   ├── block.css      # Self-contained block styles
│   │   │   └── editor.css     # Block-specific editor overrides
│   │   ├── src/
│   │   │   └── index.js       # Block registration & editor
│   │   ├── render.php         # Server-side rendering
│   │   ├── block.json         # Block configuration
│   │   └── build/             # Compiled JS
│   └── listing-section/
│       ├── styles/
│       │   ├── block.css      # Self-contained block styles
│       │   └── editor.css     # Block-specific editor overrides
│       ├── src/
│       │   └── index.js       # Block registration & editor
│       ├── render.php         # Server-side rendering
│       ├── block.json         # Block configuration
│       └── build/             # Compiled JS
├── inc/                         # Theme functionality
│   ├── mi-cpt-registration.php # Custom post types
│   ├── carbon-fields-setup.php # Custom fields setup
│   └── [other includes]
├── functions.php               # Theme functions
├── header.php                 # Header template
├── footer.php                 # Footer template
└── [other template files]
```

### File Purposes

#### Global CSS (`/assets/css/`)
- **base.css**: Contains all CSS variables for colors, typography, spacing, shadows, and transitions. This is the single source of truth for the design system.
- **main.css**: Imports base.css and applies theme-wide styles and component styling.

#### Block CSS (`/blocks/`)
- **[block]/styles/block.css**: Block-specific styles that can be edited directly
- **[block]/styles/editor.css**: Block-specific editor overrides
- **[block]/src/index.js**: Block registration and editor interface

---

## Design System & Variables

### Color System
The theme uses a simplified 5-color system with CSS color-mix() modifiers:

```css
/* Main Theme Colors (Single Source of Truth) */
--color-primary: #4d6a6d;      /* Teal/Green */
--color-secondary: #9c5961;    /* Rose/Pink */
--color-tertiary:rgb(9, 120, 142);     /* Teal/Green */
--color-neutral: #a69f95;      /* Sand */
--color-base: #808080;         /* True Gray */
--color-white: #ffffff;         /* White */
--color-black: #000000;         /* Black */

/* Color Variations (7 levels each but not for black and white) */
/* Each color has these variations created with CSS color-mix(): */
/* -lightest: color-mix(in srgb, [color] 20%, white) */
/* -light: color-mix(in srgb, [color] 60%, white) */
/* -med: [color] (unchanged) */
/* -dark: color-mix(in srgb, [color] 80%, black) */
/* -darkest: color-mix(in srgb, [color] 60%, black) */


/* Base Scale (7 levels including extremes) */
--color-base-extreme-light: color-mix(in srgb, var(--color-base) 0%, white 100%);
--color-base-lightest: color-mix(in srgb, var(--color-base) 20%, white);
--color-base-light: color-mix(in srgb, var(--color-base) 60%, white);
--color-base-med: var(--color-base);
--color-base-dark: color-mix(in srgb, var(--color-base) 80%, black);
--color-base-darkest: color-mix(in srgb, var(--color-base) 60%, black);
--color-base-extreme-dark: color-mix(in srgb, var(--color-base) 0%, black 100%);

/* Site Background */
--color-site-bg: var(--color-white);
```

### Typography System
```css
/* Font Families */
--font-primary: 'Montserrat', sans-serif;
--font-secondary: 'Poppins', sans-serif;

/* Font Weights */
--font-weight-extra-light: 100;
--font-weight-light: 300;
--font-weight-normal: 400;
--font-weight-semibold: 600;
--font-weight-bold: 700;
--font-weight-extra-bold: 800;

/* Font Sizes */
--font-size-xs: 0.75rem;
--font-size-sm: 0.875rem;
--font-size-base: 1rem;
--font-size-lg: 1.125rem;
--font-size-xl: 1.25rem;
--font-size-2xl: 1.5rem;
--font-size-3xl: 2.5rem;
--font-size-4xl: 3.5rem;
--font-size-5xl: 4.5rem;
```

### Spacing System
```css
/* Spacing Scale */
--spacing-xs: 0.25rem;
--spacing-sm: 0.5rem;
--spacing-md: 1rem;
--spacing-lg: 1.5rem;
--spacing-xl: 2rem;
--spacing-2xl: 3rem;
--spacing-3xl: 4rem;

/* Layout & Container Widths */
--site-content-width: 1200px;
--container-width-xs: 480px;
--container-width-sm: 640px;
--container-width-md: 768px;
--container-width-lg: 1024px;
--container-width-xl: 1280px;
--container-width-2xl: 1536px;
```

---

## Block Development Guide

### Island Architecture Structure
```
block-name/
├── block.json         # Block configuration
├── render.php         # Server-side rendering
├── styles/
│   ├── block.css     # Self-contained styles (uses theme CSS variables)
│   └── editor.css    # Editor-specific overrides (optional)
├── src/
│   ├── index.js      # Block registration
└── build/            # Compiled JS only
```

#### Block Attributes
- **Title**: Main heading text
- **Subtitle**: Secondary text below title
- **Background Image**: Hero background with overlay support
- **Typography Settings**: Font size, weight, line height
- **Spacing**: Padding controls for top/bottom
- **Alignment**: Text alignment (left, center, right)

#### CSS Structure
```css
/* BEM naming convention */
.wp-block-miblocks-page-header {
    /* Block container styles */
}

.page-header__overlay {
    /* Overlay styles */
}

.page-header__content {
    /* Content container */
}

.page-header__title {
    /* Title styles */
}

.page-header__subtitle {
    /* Subtitle styles */
}
```

### Available Blocks

1. **Page Header** (`miblocks/page-header`)
   - Hero sections with background images
   - Customizable titles and subtitles
   - Fixed overlay opacity (50%)
   - Completely self-contained

2. **Listing Section** (`miblocks/listing-section`)
   - Displays posts/properties in a grid
   - Supports filtering and pagination
   - Multiple card styles
   - Completely self-contained

---

## Maintenance Notes

### When Updating Styles
1. Global changes → Edit `base.css` directly
2. Theme-wide changes → Edit `main.css` directly
3. Block-specific changes → Edit `blocks/[name]/styles/block.css` directly
4. Editor fixes → Edit `blocks/[name]/styles/editor.css` directly
5. **No compilation needed** - changes are immediate!

### When Adding Colors
1. Add to `base.css` first
2. Use CSS color-mix() for variations
3. Follow the naming convention: `--color-[name]-[level]`

### When Adding Typography
1. Add font sizes to `base.css`
2. Use rem units for scalability
3. Follow the naming convention: `--font-size-[size]`

### When Adding Spacing
1. Add to `base.css` spacing scale
2. Use rem units for consistency
3. Follow the naming convention: `--spacing-[size]`

## Core Principles

1. **No SASS/SCSS** - All styles use plain CSS
2. **Island Architecture** - Each block is completely self-contained
3. **CSS Variables** - Design tokens from theme available globally
4. **Block-Level Editor Styles** - Each block handles its own editor overrides

## Directory Structure

```
mi-condotel/
├── assets/
│   └── css/
│       ├── base.css
│       └── main.css
├── blocks/
│   ├── page-header/
│   │   ├── styles/
│   │   ├── src/
│   │   └── build/
│   └── listing-section/
│       ├── styles/
│       ├── src/
│       └── build/
├── inc/
│   └── [theme includes]
├── functions.php
├── style.css
└── [template files]
```

## CSS Loading Order

1. **WordPress Core CSS**
2. **Theme style.css** (main theme stylesheet)
3. **Google Fonts**
4. **main.css** (imports base.css with CSS variables)
5. **Individual Block CSS** (loaded independently when blocks are used)
6. **Block Editor CSS** (loaded in editor context only)

## Design System (base.css)

### BEM Naming Convention
The theme uses BEM (Block Element Modifier) naming for CSS classes:

```css
/* Block */
.wp-block-miblocks-page-header { }

/* Element */
.page-header__title { }
.page-header__subtitle { }

/* Modifier */
.page-header__title--large { }
```

### Key Points:
- **No SCSS files** - We don't use or need any .scss files
- **No shared block styles** - Each block is completely independent
- **Theme variables available** - CSS variables from base.css are global
- **wp-scripts** - Only compiles JavaScript, not CSS
- **Editor styles** - Each block can have its own editor.css for WordPress editor overrides

### Block CSS Example
```css
/* blocks/listing-section/styles/block.css */
.wp-block-miblocks-listing-section {
    /* CSS variables from base.css are available globally */
    background: var(--color-base-light);
    padding: var(--spacing-xl);
}

.listing-section__title {
    font-size: var(--font-size-2xl);
    color: var(--color-neutral-dark);
}
```

### Editor CSS Example
```css
/* blocks/listing-section/styles/editor.css */
.editor-styles-wrapper .wp-block-miblocks-listing-section {
    /* Override default editor colors */
    --linkInitialColor: var(--color-primary) !important;
    --linkHoverColor: var(--color-primary-dark) !important;
}
```

## Working with Blocks

### Adding a New Block

1. Create block directory structure
2. Add `block.json` configuration
3. Create `render.php` for server-side rendering
4. Add JavaScript in `src/`
5. Create `styles/block.css` with plain CSS (must use theme variables)
6. Optionally create `styles/editor.css` for editor-specific overrides
7. Run `npm run build` to compile JavaScript

## CSS Best Practices

### Use Theme CSS Variables
```css
/* Good - uses theme variables */
.my-element {
    color: var(--color-primary);
    padding: var(--spacing-md);
}

/* Dont use hardcoded values */
.my-element {
    color: #0066CC;
    padding: 16px;
}
```

### Mobile-First Responsive
```css
/* Mobile styles (default) */
.card {
    padding: var(--spacing-sm);
}

/* Tablet and up */
@media (min-width: 768px) {
    .card {
        padding: var(--spacing-md);
    }
}
```

### Modern CSS Features
```css
/* CSS Grid for layouts */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-lg);
}

/* Color mixing for variations */
.hover-state {
    background: color-mix(in srgb, var(--color-primary) 90%, white);
}
```

## Important Notes

1. **True Island Architecture**: Each block is completely self-contained
2. **No Shared Block Styles**: No `/blocks/styles/` directory needed
3. **Theme Variables Global**: CSS variables from `base.css` available everywhere
4. **Custom Theme**: Standalone theme, not a child theme
5. **Theme Integration**: Custom header/footer with fallback menu system

## Development Commands

```bash
# Install dependencies
npm install

# Build JavaScript (CSS is already plain)
npm run build

# Watch mode for JavaScript development
npm run start
```

## Troubleshooting

### Styles Not Applying
1. Check CSS variable names match `base.css`
2. Ensure block CSS file is in `styles/block.css`
3. Verify `block.json` references the CSS file correctly
4. Confirm theme's `base.css` is loaded before block styles

### Editor Styles Not Working
1. Check if `styles/editor.css` exists for the block
2. Verify selectors include `.editor-styles-wrapper`
3. Use `!important` for overriding default styles if needed
4. Clear browser cache and reload editor

### Block Not Appearing
1. Check block is registered in WordPress
2. Verify `block.json` is valid JSON
3. Ensure JavaScript builds without errors

---

*Last Updated: May 2025*
*mi-condotel Theme v1.0*

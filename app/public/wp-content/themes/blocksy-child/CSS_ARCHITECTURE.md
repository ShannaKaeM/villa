# Villa Community CSS Architecture Guide

## Overview
This document explains the CSS structure and how different CSS files work together in the Villa Community theme.

## CSS File Structure

### 1. Global Theme CSS (`/assets/css/`)

#### `base.css` - Core Design System
- **Purpose**: Defines ALL CSS variables and design tokens
- **Contents**:
  - Color system (7 colors with 5 variations each)
  - Typography scale
  - Spacing system
  - Border radius values
  - Transitions
  - Z-index layers
- **Key Point**: This is the SINGLE SOURCE OF TRUTH for all design values

#### `main.css` - Theme Entry Point
- **Purpose**: Main CSS file that gets loaded on frontend
- **Contents**:
  - Imports Google Fonts (Montserrat)
  - Imports base.css
  - Blocksy theme overrides (removes blue colors, overlays)
  - Global component styles
- **Usage**: Loaded via `functions.php` on all pages

#### `editor-overrides.css` - Gutenberg Editor Fixes
- **Purpose**: Forces theme colors in WordPress block editor
- **Contents**:
  - Overrides Blocksy's editor colors
  - Removes editor overlays/filters
  - Forces theme colors on buttons, links, etc.
- **Usage**: Loaded only in the editor via `add_editor_style()`

### 2. Block-Specific CSS (`/blocks/`)

#### `blocks/styles/variables.css` - Shared Block Variables
- **Purpose**: Mirrors the CSS variables from base.css for blocks
- **Contents**: Same color, spacing, typography variables
- **Why Duplicate?**: Blocks may load independently, ensures variables are available

#### Individual Block CSS (`blocks/[block-name]/styles/block.css`)
- **Purpose**: Styles specific to each block
- **Examples**:
  - `page-header/styles/block.css` - Page header block styles
  - `listing-section/styles/block.css` - Listing/grid styles
- **Key Feature**: Editable via GutenVibes CSS Editor

### 3. Build Process CSS (`blocks/[block-name]/src/`)

#### `style.scss` - Frontend Block Styles (Source)
- **Purpose**: SCSS source for frontend block styles
- **Compiles to**: `build/style-index.css`
- **Note**: Currently empty in listing-section (styles in block.css instead)

#### `editor.scss` - Editor-Only Block Styles (Source)
- **Purpose**: SCSS source for editor-specific block styles
- **Compiles to**: `build/index.css`
- **Usage**: Makes blocks look correct in editor

## CSS Loading Order

1. **Frontend Page Load**:
   ```
   1. Blocksy parent theme CSS
   2. Child theme style.css
   3. Google Fonts
   4. main.css (imports base.css)
   5. Individual block CSS files (when blocks are used)
   ```

2. **Editor Load**:
   ```
   1. WordPress editor styles
   2. editor-overrides.css
   3. Block editor styles (editor.scss compiled)
   4. Block frontend styles
   ```

## Color System Architecture

### Base Colors (7 total)
```css
--color-primary: #5a7f80;    /* Goldenrod */
--color-secondary: #a85d57;  /* Saddle Brown */
--color-emphasis: #3c9da1;   /* Tomato */
--color-neutral: #9b8974;    /* Light Sand */
--color-base: #9c9c9c;       /* Mid Gray */
--color-white: #FFFFFF;      /* Standalone */
--color-black: #000000;      /* Standalone */
```

### Color Variations (5 per color)
Each main color (except white/black) has:
- `lightest` - 20% color, 80% white
- `light` - 40% color, 60% white
- `med` - 100% color (same as base)
- `dark` - 80% color, 20% black
- `darkest` - 60% color, 40% black

## Best Practices

### 1. Where to Add New Styles

- **Global styles**: Add to `base.css` (variables) or `main.css` (implementations)
- **Block-specific styles**: Add to the block's `styles/block.css`
- **Editor-only fixes**: Add to `editor-overrides.css`

### 2. Using CSS Variables

Always use CSS variables for:
- Colors: `var(--color-primary)`
- Spacing: `var(--spacing-4)`
- Typography: `var(--font-size-lg)`
- Borders: `var(--radius-md)`

### 3. Overriding Blocksy

When overriding Blocksy styles:
- Use `!important` sparingly but necessary for theme overrides
- Target specific Blocksy classes
- Remove unwanted effects (overlays, filters, opacity)

### 4. Block Development

For new blocks:
1. Create `styles/block.css` for GutenVibes editing
2. Use `src/style.scss` for complex styles that need SCSS
3. Use `src/editor.scss` for editor-specific adjustments
4. Always reference shared variables

## Common Issues & Solutions

### Issue: Colors not showing correctly
**Solution**: Check loading order, ensure base.css variables are available

### Issue: Blocksy blue still showing
**Solution**: Add more specific overrides in main.css or editor-overrides.css

### Issue: Styles work on frontend but not editor
**Solution**: Add editor-specific overrides in editor-overrides.css

### Issue: Block styles not loading
**Solution**: Ensure block is registered and CSS is enqueued properly

## File Responsibilities Summary

| File | Purpose | Edit When |
|------|---------|-----------|
| base.css | Design tokens | Changing colors, spacing, typography |
| main.css | Global overrides | Fixing Blocksy issues, global components |
| editor-overrides.css | Editor fixes | Editor color/display issues |
| blocks/*/styles/block.css | Block styles | Styling specific blocks |
| variables.css | Block variables | Keeping in sync with base.css |

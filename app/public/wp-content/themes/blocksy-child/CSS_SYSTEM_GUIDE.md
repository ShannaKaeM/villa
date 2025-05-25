# Villa Community CSS System Guide

## Overview
The Villa Community theme uses a clean, organized CSS system that separates global styles from block-specific styles.

## CSS Architecture

### 1. Global Styles (`/assets/css/`)
- **`base.css`** - Core CSS variables and reset styles
  - Defines all design tokens (colors, typography, spacing, etc.)
  - Provides CSS custom properties used throughout the theme
  - Imported by main.css

- **`main.css`** - Main entry point
  - Imports Google Fonts (Montserrat)
  - Imports base.css
  - Contains Blocksy theme overrides
  - Loaded via style.css

### 2. Block Styles (`/blocks/*/styles/block.css`)
Each WordPress block has its own CSS file that can be edited independently:
- `/blocks/page-header/styles/block.css`
- `/blocks/listing-section/styles/block.css`

These files are:
- Loaded automatically by the `mi_load_block_css_files()` function
- Designed for visual editing with GutenVibes CSS Editor
- Use the CSS variables defined in base.css

### 3. Shared Variables (`/blocks/styles/variables.css`)
- Mirrors the variables from base.css
- Ensures consistency across all blocks
- Loaded before individual block styles

## How It Works

### Loading Order:
1. **style.css** → imports `main.css`
2. **main.css** → imports `base.css`
3. **PHP function** → loads `/blocks/styles/variables.css`
4. **PHP function** → loads each `/blocks/*/styles/block.css`

### Key Benefits:
- **Separation of Concerns**: Global styles vs block-specific styles
- **Visual Editing**: Block CSS can be edited via GutenVibes
- **No Conflicts**: Each block's styles are isolated
- **Consistent Design**: All styles use the same CSS variables

## CSS Variables
All styles use CSS custom properties defined in base.css:
- Colors: `var(--color-primary)`, `var(--color-white)`, etc.
- Typography: `var(--font-size-xl)`, `var(--font-weight-bold)`, etc.
- Spacing: `var(--spacing-4)`, `var(--spacing-8)`, etc.
- Borders: `var(--radius-md)`, `var(--radius-lg)`, etc.

## Adding New Blocks
1. Create block directory: `/blocks/your-block/`
2. Add `block.json` for WordPress registration
3. Create `/blocks/your-block/styles/block.css`
4. The CSS will be loaded automatically

## GutenVibes Integration
- Configuration: `/blocks/gutenvibes.config.json`
- API endpoints: `/blocks/gutenvibes-integration.php`
- Allows visual CSS editing without touching code
- See `/blocks/GUTENVIBES_README.md` for details

## Best Practices
1. Always use CSS variables from base.css
2. Keep block styles in their respective block.css files
3. Don't use `!important` unless overriding Blocksy
4. Follow BEM naming conventions (block__element--modifier)
5. Avoid legacy prefixes (no more `m-` prefixes)

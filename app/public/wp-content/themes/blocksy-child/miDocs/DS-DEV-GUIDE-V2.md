# Villa Community Design System Development Guide V2

## Table of Contents
1. [Critical Overview - READ FIRST](#critical-overview)
2. [Design System Architecture](#design-system-architecture)
3. [Token System Reference](#token-system-reference)
4. [CSS File Structure](#css-file-structure)
5. [Component Development Rules](#component-development-rules)
6. [Block Development Process](#block-development-process)
7. [Color System (OKLCH)](#color-system-oklch)
8. [Common Patterns](#common-patterns)
9. [Testing Checklist](#testing-checklist)
10. [Migration Notes](#migration-notes)

---

## Critical Overview - READ FIRST

### 🚨 IMPORTANT: This is a Token-Based Design System
**NEVER use hardcoded values**. Every measurement, color, spacing, and style property MUST use semantic tokens defined in `main.css`.

### Key Principles
1. **Token-First Development**: All values come from CSS variables
2. **OKLCH Color Space**: All colors use OKLCH format for consistency
3. **Semantic Naming**: Components use meaningful, descriptive names
4. **BEM-like Structure**: Follow the established naming patterns
5. **No Magic Numbers**: Every value must have semantic meaning

### File Organization
```
assets/css/
├── main.css        # Token definitions & imports (DO NOT MODIFY TOKENS)
├── base.css        # Reset & typography
├── layout.css      # Grid systems & containers
├── components.css  # All UI components (buttons, cards, etc.)
├── filters.css     # Filter-specific styles
├── states.css      # Loading, empty, error states
└── utilities.css   # Helper classes (minimal)
```

---

## Design System Architecture

### Naming Conventions

#### Prefixes (MANDATORY)
- **`mi-`** - WordPress blocks (e.g., `mi-card-loop`)
- **`m-`** - Reusable components (e.g., `m-card`, `m-button`)
- **`.`** - Standard CSS classes

#### BEM Structure
```css
/* Block */
.m-card { }

/* Element (use double underscore) */
.m-card__header { }
.m-card__title { }
.m-card__content { }

/* Modifier (use double dash) */
.m-card--featured { }
.m-card--horizontal { }
```

### Component Hierarchy
1. **Blocks** (`mi-*`): Full WordPress blocks
2. **Components** (`m-*`): Reusable UI elements
3. **Elements** (`__*`): Parts of components
4. **Modifiers** (`--*`): Variations

---

## Token System Reference

### ⚠️ CRITICAL: Use ONLY These Tokens

#### Spacing Tokens
```css
/* Use these for ALL padding, margin, gap values */
--spacing-0: 0
--spacing-px: 1px
--spacing-0-5: 0.125rem  /* 2px */
--spacing-1: 0.25rem     /* 4px */
--spacing-2: 0.5rem      /* 8px */
--spacing-3: 0.75rem     /* 12px */
--spacing-4: 1rem        /* 16px */
--spacing-5: 1.25rem     /* 20px */
--spacing-6: 1.5rem      /* 24px */
--spacing-8: 2rem        /* 32px */
--spacing-10: 2.5rem     /* 40px */
--spacing-12: 3rem       /* 48px */
--spacing-16: 4rem       /* 64px */
--spacing-20: 5rem       /* 80px */
--spacing-24: 6rem       /* 96px */
```

#### Color Tokens (OKLCH Format)
```css
/* Core Colors - Use these, not raw values */
--primary: var(--primary-l) var(--primary-c) var(--primary-h)
--secondary: var(--secondary-l) var(--secondary-c) var(--secondary-h)
--accent: var(--accent-l) var(--accent-c) var(--accent-h)
--destructive: var(--destructive-l) var(--destructive-c) var(--destructive-h)
--success: var(--success-l) var(--success-c) var(--success-h)

/* Semantic Colors */
--background: 100% 0 0
--foreground: 20% 0 0
--muted: 96% 0 0
--muted-foreground: 45% 0 0
--card: 100% 0 0
--border: 90% 0 0

/* Usage Example */
color: oklch(var(--primary));
background-color: oklch(var(--muted));
```

#### Typography Tokens
```css
/* Font Sizes */
--font-size-xs: 0.75rem
--font-size-sm: 0.875rem
--font-size-base: 1rem
--font-size-lg: 1.125rem
--font-size-xl: 1.25rem
--font-size-2xl: 1.5rem
--font-size-3xl: 1.875rem
--font-size-4xl: 2.25rem
--font-size-5xl: 3rem

/* Font Weights */
--font-weight-normal: 400
--font-weight-medium: 500
--font-weight-semibold: 600
--font-weight-bold: 700

/* Line Heights */
--line-height-none: 1
--line-height-tight: 1.25
--line-height-snug: 1.375
--line-height-normal: 1.5
--line-height-relaxed: 1.75
--line-height-loose: 2
```

#### Border & Radius Tokens
```css
/* Border Widths */
--border-width: 1px
--border-width-2: 2px
--border-width-thin: 1px
--border-width-base: 2px
--border-width-thick: 3px

/* Border Radius */
--radius-none: 0
--radius-sm: 0.25rem
--radius-md: 0.5rem
--radius-lg: 0.75rem
--radius-xl: 1rem
--radius-2xl: 1.5rem
--radius-full: 9999px
```

#### Shadow Tokens
```css
--shadow-none: none
--shadow-sm: 0 1px 2px 0 oklch(0% 0 0 / 0.05)
--shadow-md: 0 4px 6px -1px oklch(0% 0 0 / 0.1)
--shadow-lg: 0 10px 15px -3px oklch(0% 0 0 / 0.1)
--shadow-xl: 0 20px 25px -5px oklch(0% 0 0 / 0.1)
```

#### Animation Tokens
```css
/* Transitions */
--transition-fast: 150ms ease-in-out
--transition-base: 200ms ease-in-out
--transition-slow: 300ms ease-in-out

/* Durations */
--animation-duration-fast: 0.2s
--animation-duration-base: 1s
--animation-duration-slow: 1.4s
--animation-duration-slower: 2s
```

#### Layout Tokens
```css
/* Aspect Ratios */
--aspect-ratio-16-9: 56.25%
--aspect-ratio-4-3: 75%
--aspect-ratio-1-1: 100%
--aspect-ratio-3-2: 66.67%
--aspect-ratio-21-9: 42.86%

/* Avatar Sizes */
--avatar-size-xs: 1.5rem
--avatar-size-sm: 2rem
--avatar-size-md: 2.5rem
--avatar-size-lg: 3rem
--avatar-size-xl: 4rem
```

---

## CSS File Structure

### main.css (Entry Point)
```css
/* DO NOT MODIFY TOKEN DEFINITIONS */
/* Only add new tokens if absolutely necessary */
@import 'base.css';
@import 'layout.css';
@import 'components.css';
@import 'filters.css';
@import 'states.css';
@import 'utilities.css';
```

### components.css Structure
```css
/* ==========================================================================
   Component Name
   ========================================================================== */

/* Base component */
.m-component {
  /* Use tokens for EVERYTHING */
  padding: var(--spacing-4);
  background-color: oklch(var(--card));
  border-radius: var(--radius-md);
}

/* Elements */
.m-component__header {
  margin-bottom: var(--spacing-3);
}

/* Modifiers */
.m-component--large {
  padding: var(--spacing-6);
}
```

---

## Component Development Rules

### ✅ DO's
```css
/* Use semantic tokens */
.m-card {
  padding: var(--spacing-4);
  background-color: oklch(var(--card));
  border: var(--border-width) solid oklch(var(--border));
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-sm);
}

/* Use OKLCH for colors */
.m-button:hover {
  background-color: oklch(calc(var(--primary-l) + var(--darken-10)) var(--primary-c) var(--primary-h));
}

/* Use calc() for variations */
.m-card--compact {
  padding: calc(var(--spacing-4) * 0.75);
}
```

### ❌ DON'TS
```css
/* NEVER use hardcoded values */
.m-card {
  padding: 16px; /* WRONG */
  background-color: #ffffff; /* WRONG */
  border: 1px solid #e5e5e5; /* WRONG */
  border-radius: 8px; /* WRONG */
}

/* NEVER use RGB/HSL colors */
.m-button {
  background-color: rgb(151, 191, 196); /* WRONG */
  color: hsl(200, 50%, 50%); /* WRONG */
}

/* NEVER use arbitrary calculations */
.m-card--compact {
  padding: 12px; /* WRONG - use calc() with tokens */
}
```

---

## Block Development Process

### 1. Create Block Structure
```bash
# Create block directory
mkdir blocks/mi-block-name

# Required files
blocks/mi-block-name/
├── block.json
├── render.php
├── src/
│   ├── index.js
│   ├── edit.js
│   ├── view.js
│   ├── style.scss
│   └── editor.scss
└── partials/
    └── template-parts.php
```

### 2. Block Metadata (block.json)
```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "miblocks/block-name",
  "title": "Block Title",
  "category": "miblocks",
  "icon": "grid-view",
  "description": "Block description",
  "supports": {
    "html": false,
    "align": ["wide", "full"]
  },
  "attributes": {
    "exampleAttribute": {
      "type": "string",
      "default": "default-value"
    }
  },
  "editorScript": "file:./build/index.js",
  "editorStyle": "file:./build/index.css",
  "style": "file:./build/style-index.css",
  "viewScript": "file:./build/view.js",
  "render": "file:./render.php"
}
```

### 3. PHP Rendering (render.php)
```php
<?php
/**
 * Block Template
 * 
 * @param array    $attributes Block attributes
 * @param string   $content    Block content
 * @param WP_Block $block      Block instance
 */

// Get attributes with defaults
$attributes = $attributes ?? [];
$example = $attributes['exampleAttribute'] ?? 'default';

// Get wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'mi-block-name'
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="m-component">
        <!-- Component content -->
    </div>
</div>
```

### 4. Styles (style.scss)
```scss
// Use CSS variables, not SCSS variables
.mi-block-name {
  padding: var(--spacing-6);
}

.m-component {
  display: grid;
  gap: var(--spacing-4);
  
  &__header {
    padding: var(--spacing-4);
    background-color: oklch(var(--muted));
  }
  
  &--modifier {
    // Modifier styles
  }
}
```

### 5. Build Process
```bash
# Development
npm run start -- --block=mi-block-name

# Production
npm run build -- --block=mi-block-name
```

---

## Color System (OKLCH)

### Understanding OKLCH
OKLCH = Lightness, Chroma, Hue
- **L**: Lightness (0-100%)
- **C**: Chroma/Saturation (0-0.4+)
- **H**: Hue (0-360)

### Color Modifications
```css
/* Lighten by 10% */
background-color: oklch(calc(var(--primary-l) + var(--lighten-10)) var(--primary-c) var(--primary-h));

/* Darken by 10% */
background-color: oklch(calc(var(--primary-l) + var(--darken-10)) var(--primary-c) var(--primary-h));

/* Reduce saturation */
background-color: oklch(var(--primary-l) calc(var(--primary-c) * 0.5) var(--primary-h));
```

### Lightness Modifiers
```css
/* Darkening */
--darken-5: -0.05
--darken-10: -0.1
--darken-15: -0.15
--darken-20: -0.2

/* Lightening */
--lighten-5: 0.05
--lighten-10: 0.1
--lighten-15: 0.15
--lighten-20: 0.2
```

---

## Common Patterns

### Card Component
```css
.m-card {
  background: oklch(var(--card));
  border-radius: var(--radius-lg);
  overflow: hidden;
  transition: all var(--transition-base);
  box-shadow: var(--shadow-sm);
  border: var(--border-width-thin) solid oklch(var(--border));
}

.m-card:hover {
  transform: translateY(var(--translate-y-px));
  box-shadow: var(--shadow-md);
}

.m-card__image {
  position: relative;
  width: 100%;
  padding-top: var(--aspect-ratio-16-9);
  overflow: hidden;
  background-color: oklch(var(--muted));
}
```

### Button Component
```css
.m-btn {
  padding: var(--spacing-3) var(--spacing-6);
  font-size: var(--font-size-sm);
  font-weight: var(--font-weight-medium);
  border-radius: var(--radius-md);
  transition: all var(--transition-fast);
  cursor: pointer;
}

.m-btn--primary {
  background-color: oklch(var(--primary));
  color: oklch(var(--primary-foreground));
  border: var(--border-width) solid oklch(var(--primary));
}

.m-btn--primary:hover {
  background-color: oklch(calc(var(--primary-l) + var(--darken-10)) var(--primary-c) var(--primary-h));
}
```

### Grid Layouts
```css
.m-grid {
  display: grid;
  gap: var(--spacing-6);
}

.m-grid--2 {
  grid-template-columns: repeat(2, 1fr);
}

.m-grid--responsive {
  grid-template-columns: repeat(auto-fill, minmax(min(100%, 300px), 1fr));
}
```

---

## Testing Checklist

### Before Committing
- [ ] All values use semantic tokens
- [ ] No hardcoded colors, sizes, or spacing
- [ ] All colors use OKLCH format
- [ ] Components follow naming conventions
- [ ] Responsive design works correctly
- [ ] No console errors
- [ ] Accessibility standards met

### Token Usage Audit
```bash
# Check for hardcoded values (these should return no results)
grep -E '[0-9]+px|#[0-9a-fA-F]{3,6}|rgb\(|hsl\(' *.css
grep -E '(margin|padding|gap):\s*[0-9]' *.css
```

### Component Checklist
- [ ] Uses `m-` prefix for components
- [ ] Follows BEM structure
- [ ] All interactive states defined
- [ ] Hover/focus states use token modifications
- [ ] Responsive breakpoints handled

---

## Migration Notes

### From V1 to V2
1. **Color System**: All HSL colors converted to OKLCH
2. **Tokens**: All hardcoded values replaced with tokens
3. **Animations**: New animation duration and delay tokens
4. **Aspect Ratios**: New tokens for common aspect ratios
5. **Border Widths**: Expanded border width tokens

### Breaking Changes
- HSL color functions no longer work
- Some utility classes removed (use components instead)
- Direct pixel values will cause inconsistencies

### Update Process
1. Replace all HSL colors with OKLCH equivalents
2. Replace all hardcoded values with tokens
3. Update any custom components to use new tokens
4. Test all interactive states
5. Verify responsive behavior

---

## Quick Reference Card

### Most Used Tokens
```css
/* Spacing */
--spacing-2: 0.5rem    /* Small gaps */
--spacing-4: 1rem      /* Standard padding */
--spacing-6: 1.5rem    /* Section spacing */

/* Colors */
color: oklch(var(--foreground));
background-color: oklch(var(--background));
border-color: oklch(var(--border));

/* Typography */
font-size: var(--font-size-base);
font-weight: var(--font-weight-medium);
line-height: var(--line-height-normal);

/* Borders */
border: var(--border-width) solid oklch(var(--border));
border-radius: var(--radius-md);

/* Shadows */
box-shadow: var(--shadow-sm);

/* Transitions */
transition: all var(--transition-base);
```

### Component Template
```css
.m-component {
  /* Layout */
  display: grid;
  gap: var(--spacing-4);
  padding: var(--spacing-4);
  
  /* Visual */
  background-color: oklch(var(--card));
  border: var(--border-width) solid oklch(var(--border));
  border-radius: var(--radius-md);
  box-shadow: var(--shadow-sm);
  
  /* Typography */
  font-size: var(--font-size-base);
  line-height: var(--line-height-normal);
  
  /* Interaction */
  transition: all var(--transition-base);
  
  &:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(var(--translate-y-px));
  }
}
```

---

## Final Notes

### Remember
1. **NEVER** hardcode values - use tokens
2. **ALWAYS** use OKLCH for colors
3. **FOLLOW** naming conventions strictly
4. **TEST** all responsive breakpoints
5. **MAINTAIN** consistency across components

### Support
- Check existing components for patterns
- Refer to main.css for all available tokens
- Test in multiple browsers
- Validate accessibility

This guide ensures consistency and maintainability. Following these rules prevents technical debt and ensures a scalable design system.

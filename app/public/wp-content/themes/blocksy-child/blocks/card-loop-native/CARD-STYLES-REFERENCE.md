# Card Styles Reference

This document provides a comprehensive overview of all card-related styles in the theme's CSS architecture, as well as the custom styles we've added for the Card Loop block.

## Theme Color System

The Villa Community theme uses a sophisticated color system based on OKLCH color space for better color manipulation:

```css
/* FOUNDATIONAL PALETTE (Single Source of Truth) */
--theme-palette-color-1: #598992; /* Primary */
--theme-palette-color-2: #9c5961; /* Secondary */
--theme-palette-color-3: #9b8974; /* Neutral */
--theme-palette-color-4: #828282; /* Base */
--theme-palette-color-5: #ffffff; /* White */
--theme-palette-color-6: #000000; /* Black */

/* Base color definitions using OKLCH */
--primary: oklch(from var(--theme-palette-color-1) l c h);
--secondary: oklch(from var(--theme-palette-color-2) l c h);
--neutral: oklch(from var(--theme-palette-color-3) l c h);
--base: oklch(from var(--theme-palette-color-4) l c h);
--white: oklch(from var(--theme-palette-color-5) l c h);
--black: oklch(from var(--theme-palette-color-6) l c h);

/* COLOR VARIATIONS */
/* Each color has 5 variations for flexibility */

/* Primary color variations */
--color-primary: var(--primary);
--color-primary-lightest: oklch(from var(--primary) calc(l + 20%) c h);
--color-primary-light: oklch(from var(--primary) calc(l + 10%) c h);
--color-primary-med: oklch(from var(--primary) calc(l + 0%) c h);
--color-primary-dark: oklch(from var(--primary) calc(l - 10%) c h);
--color-primary-darkest: oklch(from var(--primary) calc(l - 20%) c h);

/* Secondary color variations */
--color-secondary: var(--secondary);
--color-secondary-lightest: oklch(from var(--secondary) calc(l + 20%) c h);
--color-secondary-light: oklch(from var(--secondary) calc(l + 10%) c h);
--color-secondary-med: oklch(from var(--secondary) calc(l + 0%) c h);
--color-secondary-dark: oklch(from var(--secondary) calc(l - 10%) c h);
--color-secondary-darkest: oklch(from var(--secondary) calc(l - 20%) c h);

/* Neutral color variations */
--color-neutral: var(--neutral);
--color-neutral-lightest: oklch(from var(--neutral) calc(l + 20%) c h);
--color-neutral-light: oklch(from var(--neutral) calc(l + 10%) c h);
--color-neutral-med: oklch(from var(--neutral) calc(l + 0%) c h);
--color-neutral-dark: oklch(from var(--neutral) calc(l - 10%) c h);
--color-neutral-darkest: oklch(from var(--neutral) calc(l - 20%) c h);

/* Base color variations */
--color-base: var(--base);
--color-base-lightest: oklch(from var(--base) calc(l + 20%) c h);
--color-base-light: oklch(from var(--base) calc(l + 10%) c h);
--color-base-med: oklch(from var(--base) calc(l + 0%) c h);
--color-base-dark: oklch(from var(--base) calc(l - 10%) c h);
--color-base-darkest: oklch(from var(--base) calc(l - 20%) c h);

/* White and Black (no variations needed) */
--color-white: var(--white);
--color-black: var(--black);
```

### Semantic Color Mappings

The theme maps these color variables to semantic uses:

```css
--background: var(--color-white);
--foreground: var(--color-base-darkest);
--card: var(--color-white);
--card-foreground: var(--color-base-dark);
--popover: var(--color-white);
--popover-foreground: var(--color-base-dark);
--muted: var(--color-neutral-light);
--muted-foreground: var(--color-base);
--accent: var(--color-secondary-light);
--accent-foreground: var(--color-secondary-darkest);
--border: var(--color-base-light);
--input: var(--color-base-light);
--ring: var(--color-primary);
```

### Design Tokens

The theme includes design tokens for consistent styling:

```css
--radius: 0.5rem;
--destructive: oklch(0.5 0.2 30);
--destructive-foreground: var(--color-white);
```

### Utility Classes

The theme provides utility classes similar to Tailwind but using CSS variables:

```css
/* Background utilities */
.bg-primary { background-color: var(--color-primary); }
.bg-primary-lightest { background-color: var(--color-primary-lightest); }
.bg-primary-light { background-color: var(--color-primary-light); }
.bg-primary-dark { background-color: var(--color-primary-dark); }
.bg-primary-darkest { background-color: var(--color-primary-darkest); }

/* Text color utilities */
.text-primary { color: var(--color-primary); }
.text-primary-lightest { color: var(--color-primary-lightest); }
.text-primary-light { color: var(--color-primary-light); }
.text-primary-dark { color: var(--color-primary-dark); }
.text-primary-darkest { color: var(--color-primary-darkest); }
```

## Global Card Styles (from components.css)

These are the base card styles that are available throughout the theme:

```css
/* Base Card Component */
.card {
  background-color: var(--card-bg, var(--color-base-white));
  border-radius: var(--card-radius, var(--radius-md));
  box-shadow: var(--card-shadow, var(--shadow-md));
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: var(--card-hover-transform, translateY(-2px));
  box-shadow: var(--card-hover-shadow, var(--shadow-lg));
}

.card__header {
  padding: var(--card-header-padding, 1.5rem);
  border-bottom: 1px solid var(--card-border-color, var(--color-neutral-light));
}

.card__body {
  padding: var(--card-body-padding, 1.5rem);
}

.card__footer {
  padding: var(--card-footer-padding, 1.5rem);
  border-top: 1px solid var(--card-border-color, var(--color-neutral-light));
}

.card__image {
  aspect-ratio: var(--card-image-ratio, 16/9);
  object-fit: cover;
  width: 100%;
}

/* Extended Card Component Styles */
.card {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  height: 100%;
  transition: transform var(--transition-base), box-shadow var(--transition-base);
}

.card:hover {
  transform: translateY(-5px);
}

/* Card Image */
.card__image {
  position: relative;
  aspect-ratio: var(--card-image-ratio, 16/9);
  overflow: hidden;
  margin: calc(var(--card-padding) * -1);
  margin-bottom: 0;
}

.card__image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  transition: transform var(--transition-slow);
}

.card:hover .card__image img {
  transform: scale(1.05);
}

/* Card Image Placeholder */
.card__image-placeholder {
  width: 100%;
  height: 100%;
  background-color: var(--color-neutral-light);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-neutral-med);
}

.card__image-placeholder-icon {
  font-size: 2.5rem;
}

/* Card Content */
.card__content {
  padding: var(--card-content-padding, var(--card-padding));
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.card--has-image .card__content {
  padding-top: var(--card-padding);
}

/* Card Title */
.card__title {
  font-size: var(--card-title-size, 1.125rem);
  font-weight: var(--card-title-weight, 600);
  color: var(--card-title-color, var(--color-text-heading));
  margin-bottom: var(--card-title-spacing, 0.5rem);
  display: -webkit-box;
  -webkit-line-clamp: var(--card-title-lines, 2);
  line-clamp: var(--card-title-lines, 2);
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.3;
}

/* Card Description */
.card__description {
  font-size: var(--card-description-size, 0.875rem);
  color: var(--card-description-color, var(--color-text-muted));
  margin-bottom: var(--card-description-spacing, 1rem);
  display: -webkit-box;
  -webkit-line-clamp: var(--card-description-lines, 2);
  line-clamp: var(--card-description-lines, 2);
  -webkit-box-orient: vertical;
  overflow: hidden;
  line-height: 1.5;
}

/* Card Meta */
.card__meta {
  font-size: var(--card-meta-size, 0.875rem);
  color: var(--card-meta-color, var(--color-text-muted));
  margin-bottom: var(--card-meta-spacing, 1rem);
}

.card__meta-item {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  margin-bottom: 0.25rem;
}

.card__meta-icon {
  color: var(--card-meta-icon-color, var(--color-primary));
}

/* Card Tags */
.card__tags {
  display: flex;
  flex-wrap: wrap;
  gap: var(--card-tags-gap, 0.5rem);
  margin-bottom: var(--card-tags-spacing, 1rem);
}

/* Card Actions */
.card__actions {
  margin-top: auto;
  display: flex;
  gap: var(--card-actions-gap, 0.5rem);
}

.card__button {
  flex: 1;
}

/* Card Variants */
.card--horizontal {
  flex-direction: row;
}

.card--horizontal .card__image {
  width: var(--card-horizontal-image-width, 250px);
  height: 100%;
  aspect-ratio: auto;
  margin: 0;
}

.card--horizontal .card__content {
  flex: 1;
}

.card--compact {
  padding: var(--card-padding-compact, 1rem);
}

.card--featured {
  border: 2px solid var(--color-primary);
}

/* Responsive Card Adjustments */
@media (max-width: 768px) {
  .card__image {
    aspect-ratio: var(--card-image-ratio-mobile, 4/3);
  }
  
  .card__content {
    padding: var(--card-padding-mobile, 0.75rem);
  }
  
  .card__title {
    font-size: var(--card-title-size-mobile, 1rem);
  }
  
  .card__description {
    font-size: var(--card-description-size-mobile, 0.75rem);
  }
  
  .card--horizontal {
    flex-direction: column;
  }
  
  .card--horizontal .card__image {
    width: 100%;
    aspect-ratio: var(--card-image-ratio-mobile, 4/3);
  }
}
```

## Theme Variables for Cards (from shadcn-custom.css)

These are the CSS variables that define card colors in the theme:

```css
:root {
  /* Light mode card variables */
  --card: var(--color-white);
  --card-foreground: var(--color-base-dark);
}

.dark {
  /* Dark mode card variables */
  --card: var(--color-base-dark);
  --card-foreground: var(--color-white);
}
```

## Card Loop Block Custom Styles

These are the custom styles we've added specifically for the Card Loop block:

```css
/* Price and Type Tags */
.mi-card-loop .card__price-tag {
  position: absolute !important;
  top: 10px !important;
  left: 10px !important;
  background-color: var(--color-primary, #598992) !important;
  color: white !important;
  padding: 4px 10px !important;
  font-size: 0.75rem !important;
  font-weight: 600 !important;
  border-radius: 4px !important;
  z-index: 2 !important;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
}

.mi-card-loop .card__type-tag {
  position: absolute !important;
  top: 10px !important;
  right: 10px !important;
  background-color: var(--color-secondary, #9c5961) !important;
  color: white !important;
  padding: 4px 10px !important;
  font-size: 0.75rem !important;
  font-weight: 600 !important;
  border-radius: 4px !important;
  z-index: 2 !important;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2) !important;
}

/* Features Row */
.mi-card-loop .card__features-row {
  display: flex !important;
  justify-content: space-between !important;
  margin: 1.5rem 0 !important;
  padding: 0 !important;
  text-align: center !important;
}

.mi-card-loop .card__feature {
  display: flex !important;
  flex-direction: column !important;
  align-items: center !important;
  width: 33.333% !important;
  position: relative !important;
}

.mi-card-loop .card__feature:not(:last-child):after {
  content: '' !important;
  position: absolute !important;
  right: 0 !important;
  top: 10% !important;
  height: 80% !important;
  width: 1px !important;
  background-color: var(--color-neutral-light, #e0e0e0) !important;
}

.mi-card-loop .card__feature-number {
  display: block !important;
  font-weight: 600 !important;
  font-size: 1.75rem !important;
  color: var(--color-base-darkest, #333) !important;
  margin-bottom: 4px !important;
  line-height: 1.2 !important;
}

.mi-card-loop .card__feature-label {
  display: block !important;
  font-size: 0.6875rem !important;
  color: var(--color-base-med, #828282) !important;
  text-transform: uppercase !important;
  letter-spacing: 0.5px !important;
  font-weight: 500 !important;
}

/* Card Content Improvements */
.mi-card-loop .card__content {
  padding: 1.25rem !important;
}

.mi-card-loop .card__title {
  margin: 0 0 0.75rem 0 !important;
  font-size: 1.125rem !important;
  font-weight: 600 !important;
  line-height: 1.3 !important;
}

.mi-card-loop .card__title-link {
  color: var(--color-base-darkest, #333) !important;
  text-decoration: none !important;
}

.mi-card-loop .card__title-link:hover {
  color: var(--color-primary, #598992) !important;
}

.mi-card-loop .card__excerpt {
  font-size: 0.875rem !important;
  color: var(--color-base-med, #828282) !important;
  margin-bottom: 0.75rem !important;
  line-height: 1.5 !important;
  min-height: 2.5rem !important;
}

.mi-card-loop .card__more-link {
  display: inline-block !important;
  padding: 0.5rem 1.25rem !important;
  background-color: var(--color-primary, #598992) !important;
  color: white !important;
  text-decoration: none !important;
  border-radius: 4px !important;
  font-size: 0.875rem !important;
  font-weight: 500 !important;
  transition: background-color 0.2s ease !important;
  text-align: center !important;
}

.mi-card-loop .card__more-link:hover {
  background-color: var(--color-primary-dark, #3e6065) !important;
}

.mi-card-loop .card__footer {
  margin-top: 1.25rem !important;
  text-align: center !important;
}

/* Filter Sidebar Styles */
.mi-card-loop .filter-sidebar {
  background-color: var(--color-base-white, #fff) !important;
  border-radius: var(--radius-md, 6px) !important;
  box-shadow: var(--shadow-sm, 0 1px 3px rgba(0,0,0,0.1)) !important;
  padding: 1.5rem !important;
}

.mi-card-loop .filter-sidebar__title {
  font-size: 1.25rem !important;
  font-weight: 600 !important;
  margin-bottom: 1.5rem !important;
  color: var(--color-base-darkest, #333) !important;
}

.mi-card-loop .filter-section {
  margin-bottom: 1.5rem !important;
}

.mi-card-loop .filter-section__title {
  font-size: 1rem !important;
  font-weight: 600 !important;
  margin-bottom: 0.75rem !important;
  color: var(--color-base-dark, #555) !important;
}

.mi-card-loop .filter-section__checkboxes {
  display: flex !important;
  flex-direction: column !important;
  gap: 0.5rem !important;
}

.mi-card-loop .filter-section__checkbox-label {
  display: flex !important;
  align-items: center !important;
  gap: 0.5rem !important;
  font-size: 0.875rem !important;
  color: var(--color-base-med, #666) !important;
  cursor: pointer !important;
}

.mi-card-loop .filter-section__checkbox {
  width: 16px !important;
  height: 16px !important;
}

.mi-card-loop .filter-section__slider-container {
  margin-top: 0.75rem !important;
  padding: 0.5rem 0 !important;
}

.mi-card-loop .filter-section__slider-track {
  height: 4px !important;
  background-color: var(--color-neutral-light, #e0e0e0) !important;
  border-radius: 2px !important;
  position: relative !important;
  margin: 0 10px !important;
}

.mi-card-loop .filter-section__slider-fill {
  position: absolute !important;
  left: 0 !important;
  top: 0 !important;
  height: 100% !important;
  width: 50% !important;
  background-color: var(--color-primary, #598992) !important;
  border-radius: 2px !important;
}

.mi-card-loop .filter-section__slider-handle {
  position: absolute !important;
  top: 50% !important;
  width: 16px !important;
  height: 16px !important;
  background-color: white !important;
  border: 2px solid var(--color-primary, #598992) !important;
  border-radius: 50% !important;
  transform: translate(-50%, -50%) !important;
  cursor: pointer !important;
  box-shadow: 0 1px 3px rgba(0,0,0,0.2) !important;
}

.mi-card-loop .filter-section__slider-label {
  font-size: 0.75rem !important;
  color: var(--color-base-med, #828282) !important;
  text-align: center !important;
  margin-top: 1rem !important;
}
```

## CSS Architecture Notes

1. **Global vs. Block-Specific Styles**:
   - The global card styles in `components.css` provide a foundation for all cards
   - The block-specific styles in `additional-styles.css` override or extend these styles for the Card Loop block

2. **CSS Variables**:
   - The theme uses CSS variables for consistent styling
   - Variables like `--color-primary`, `--card-radius`, etc. allow for easy theming

3. **Naming Conventions**:
   - BEM (Block Element Modifier) methodology is used for class names
   - `.card` is the block, `.card__title` is an element, `.card--horizontal` is a modifier

4. **Responsive Design**:
   - Media queries are used to adjust card styling on different screen sizes
   - Mobile-specific variables like `--card-image-ratio-mobile` are available

5. **Specificity**:
   - Block-specific styles use the `.mi-card-loop` prefix to increase specificity
   - `!important` flags are used to ensure styles override theme defaults

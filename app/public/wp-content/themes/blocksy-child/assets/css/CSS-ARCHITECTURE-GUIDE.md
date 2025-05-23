# CSS Architecture Guide

## Overview

This guide outlines our CSS architecture strategy, moving away from utility-first approaches to a more traditional, maintainable CSS structure that leverages AI capabilities and modern CSS features.

## Directory Structure

```
assets/css/
├── main.css              # Main entry point - imports all other files
├── shadcn-custom.css     # Theme variables and color system
├── base.css              # Resets, typography, global defaults
├── layout.css            # Common layout patterns
├── components.css        # Reusable component styles
├── utilities.css         # Utility classes (use sparingly)
└── CSS-ARCHITECTURE-GUIDE.md

blocks/{block-name}/
├── style.css             # Block-specific styles
├── css/                  # Additional block CSS if needed
│   ├── {component}.css   # Component-specific styles
│   └── {feature}.css     # Feature-specific styles
└── ...
```

## Core Principles

### 1. **Separation of Concerns**
- **Global styles** in `/assets/css/` for site-wide patterns
- **Block-specific styles** stay with the block
- Clear boundaries between global and local styles

### 2. **CSS Variables First**
- Use CSS custom properties for all values that might change
- Create semantic variable names that describe purpose, not appearance
- Layer variables for flexibility:
  ```css
  /* Foundation */
  --color-primary: #598992;
  
  /* Semantic */
  --button-bg: var(--color-primary);
  
  /* Component */
  .btn { background: var(--button-bg); }
  ```

### 3. **Component-Based Architecture**
- Think in components, not pages
- Create reusable patterns that can be composed
- Use semantic class names that describe the component

### 4. **Progressive Enhancement**
- Start with solid HTML structure
- Layer on CSS for visual design
- Add JavaScript for interactivity

## Best Practices

### Global vs Block Styles

**Put in Global (`/assets/css/`):**
- Reset styles
- Typography scales
- Common components (buttons, forms, cards)
- Layout patterns (grids, containers)
- Design tokens (colors, spacing, shadows)

**Keep in Block:**
- Unique component variations
- Block-specific layouts
- Overrides for specific use cases
- Animation/interaction styles unique to the block

### Naming Conventions

Use BEM-inspired naming for clarity:
```css
/* Block */
.card { }

/* Element */
.card__header { }
.card__body { }
.card__footer { }

/* Modifier */
.card--featured { }
.card--compact { }

/* State */
.card.is-loading { }
.card.is-active { }
```

### CSS Variable Naming

Follow a consistent pattern:
```css
/* Colors */
--color-{semantic}-{shade}
--color-primary-dark

/* Spacing */
--spacing-{size}
--spacing-4

/* Component-specific */
--{component}-{property}-{variant}
--button-padding-large
```

## Refactoring Strategy

### Phase 1: Audit Existing Styles
1. Identify common patterns across blocks
2. Find duplicate styles
3. Map Tailwind classes to semantic equivalents

### Phase 2: Create Global Components
1. Extract common patterns to `/assets/css/components.css`
2. Define CSS variables for customization
3. Document usage patterns

### Phase 3: Refactor Templates
1. Replace utility classes with semantic classes
2. Use data attributes for JavaScript hooks
3. Maintain accessibility features

### Phase 4: Optimize
1. Remove unused styles
2. Combine similar rules
3. Ensure proper cascade and specificity

## AI-Driven Workflow

### 1. **Inspiration Analysis**
When given an inspiration site/image:
- Extract color palette → Update CSS variables
- Identify layout patterns → Create/update layout classes
- Analyze components → Generate component styles

### 2. **Style Generation**
AI can help by:
- Converting designs to CSS
- Suggesting semantic class names
- Creating consistent variations
- Optimizing CSS structure

### 3. **Pattern Recognition**
Use AI to:
- Find duplicate styles across blocks
- Suggest consolidation opportunities
- Identify inconsistencies
- Recommend best practices

## Example: Refactoring Tailwind to Semantic CSS

### Before (Tailwind):
```html
<div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
  <h3 class="text-xl font-semibold mb-2 text-gray-800">Title</h3>
  <p class="text-gray-600 line-clamp-2">Description</p>
</div>
```

### After (Semantic):
```html
<div class="card">
  <h3 class="card__title">Title</h3>
  <p class="card__description">Description</p>
</div>
```

```css
.card {
  background-color: var(--card-bg, var(--color-base-white));
  border-radius: var(--card-radius, var(--radius-lg));
  box-shadow: var(--card-shadow, var(--shadow-md));
  padding: var(--card-padding, 1.5rem);
  transition: box-shadow var(--transition-base);
}

.card:hover {
  box-shadow: var(--card-shadow-hover, var(--shadow-lg));
}

.card__title {
  font-size: var(--card-title-size, 1.25rem);
  font-weight: var(--card-title-weight, 600);
  margin-bottom: var(--card-title-spacing, 0.5rem);
  color: var(--card-title-color, var(--color-text-heading));
}

.card__description {
  color: var(--card-description-color, var(--color-text-muted));
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
```

## Migration Checklist

- [ ] Create global CSS architecture files
- [ ] Define comprehensive CSS variables
- [ ] Extract common components from blocks
- [ ] Create semantic class equivalents for utility classes
- [ ] Update block templates to use semantic classes
- [ ] Remove redundant block-specific styles
- [ ] Document component usage patterns
- [ ] Test across different contexts
- [ ] Optimize final CSS output

## Benefits of This Approach

1. **Maintainability**: Clear structure and naming makes updates easier
2. **Reusability**: Components can be used across different blocks
3. **Performance**: Less CSS duplication, better caching
4. **AI-Friendly**: Semantic names help AI understand and generate better code
5. **Flexibility**: CSS variables allow easy theming and variations
6. **Scalability**: New blocks can leverage existing components

## Next Steps

1. Start with high-impact components (buttons, cards, forms)
2. Gradually migrate blocks one at a time
3. Document patterns as you create them
4. Use AI to help identify optimization opportunities
5. Keep refining based on actual usage patterns

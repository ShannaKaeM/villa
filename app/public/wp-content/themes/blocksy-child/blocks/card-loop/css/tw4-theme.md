/**
 * Theme Variables - Tailwind 4 Optimized
 * Leveraging TW4's automatic CSS variable exposure and OKLCH color space
 */

@import "tailwindcss";

/* via esm.sh CDN */
@plugin 'https://esm.sh/@tailwindcss/typography'; 
@plugin '@tailwindcss/typography' {
    strategy: 'class';
}

:root {
  /* ----------------------------------------
   * FOUNDATIONAL PALETTE (Single Source of Truth)
   * Using OKLCH for better color manipulation
   * ---------------------------------------- */
  --theme-palette-color-1: #598992; /* Brand1 */
  --theme-palette-color-2: #9c5961; /* Brand2 */
  --theme-palette-color-3: #9b8974; /* Neutral */
  --theme-palette-color-4: #828282; /* Base */
  --theme-palette-color-5: #ffffff; /* White */
  --theme-palette-color-6: #000000; /* Black */

  --brand1:  oklch(from var(--theme-palette-color-1) l c h);
  --brand2:  oklch(from var(--theme-palette-color-2) l c h);
  --neutral: oklch(from var(--theme-palette-color-3) l c h);
  --base:    oklch(from var(--theme-palette-color-4) l c h);
  --white:   oklch(from var(--theme-palette-color-5) l c h);
  --black:   oklch(from var(--theme-palette-color-6) l c h);
}

@theme inline {
   --color-* : initial;
   
  --color-brand1: var(--brand1);
  --color-brand1-lightest: oklch(from var(--brand1) calc(l + 20%) c h);
  --color-brand1-light:    oklch(from var(--brand1) calc(l + 10%) c h);
  --color-brand1-med:     oklch(from var(--brand1) calc(l + 0%) c h);
  --color-brand1-dark:     oklch(from var(--brand1) calc(l - 10%) c h);
  --color-brand1-darkest:  oklch(from var(--brand1) calc(l - 20%) c h);
  
  --color-brand2: var(--brand2);
  --color-brand2-lightest: oklch(from var(--brand2) calc(l + 20%) c h);
  --color-brand2-light:    oklch(from var(--brand2) calc(l + 10%) c h);
  --color-brand2-med:     oklch(from var(--brand2) calc(l + 0%) c h);
  --color-brand2-dark:     oklch(from var(--brand2) calc(l - 10%) c h);
  --color-brand2-darkest:  oklch(from var(--brand2) calc(l - 20%) c h);
  
  --color-neutral: var(--neutral);
  --color-neutral-lightest: oklch(from var(--neutral) calc(l + 20%) c h);
  --color-neutral-light:    oklch(from var(--neutral) calc(l + 10%) c h);
  --color-neutral-med:     oklch(from var(--neutral) calc(l + 0%) c h);
  --color-neutral-dark:     oklch(from var(--neutral) calc(l - 10%) c h);
  --color-neutral-darkest:  oklch(from var(--neutral) calc(l - 20%) c h);
  
  --color-base: var(--base);
  --color-base-lightest: oklch(from var(--base) calc(l + 20%) c h);
  --color-base-light:    oklch(from var(--base) calc(l + 10%) c h);
  --color-base-med:     oklch(from var(--base) calc(l + 0%) c h);
  --color-base-dark:     oklch(from var(--base) calc(l - 10%) c h);
  --color-base-darkest:  oklch(from var(--base) calc(l - 20%) c h);
  
  --color-white: var(--white);
  
  --color-black: var(--black);
  
}
  
  /* ----------------------------------------
   * SEMANTIC COLOR ASSIGNMENTS
   * Using more descriptive, purpose-based naming
   * ---------------------------------------- */
  
  /* Background colors */
  --color-canvas-bg: var(--color-white);
  --color-surface-bg: var(--color-white);
  --color-card-bg: var(--color-white);
  --color-sidebar-bg: var(--color-white);
  --color-input-bg: var(--color-white);
  --color-tag-bg: var(--color-neutral-light);
  --color-badge-bg: var(--color-brand1-light);

  /* Text colors */
  --color-title: var(--color-brand1-dark);
  --color-pretitle: var(--color-brand1-dark);
  --color-subtitle: var(--color-base-med);
  --color-body: var(--color-base-med);
  
  
  /* Border colors */
  --color-border-light: var(--color-base-light);
  --color-border-input-bg: var(--color-neutral-light);
  --color-border-focus: var(--color-brand1-med);
  
  /* Interactive state colors */
  --color-state-hover: var(--color-brand1-med);
  --color-state-active: var(--color-brand1-dark);
  --color-state-focus: var(--color-brand1-light);
  --color-state-disabled: var(--color-base-light);

  /* ----------------------------------------
   * TYPOGRAPHY
   * Font sizes, weights, and line heights
   * ---------------------------------------- */
  
  /* Font sizes */
  --font-size-xs: 0.75rem;    /* 12px - Tags, badges, small text */
  --font-size-sm: 0.875rem;   /* 14px - Descriptions, secondary text */
  --font-size-base: 1rem;     /* 16px - Regular text */
  --font-size-lg: 1.125rem;   /* 18px - Card titles, section headings */
  --font-size-xl: 1.25rem;    /* 20px - Main headings */
  --font-size-2xl: 1.5rem;    /* 24px - Large headings */
  
  /* Font weights */
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
  
  /* Line heights */
  --line-height-tight: 1.2;
  --line-height-normal: 1.4;
  --line-height-relaxed: 1.6;

  /* ----------------------------------------
   * SPACING SYSTEM
   * Consistent spacing values for margins, padding, gaps
   * ---------------------------------------- */
  
  --spacing-1: 0.25rem;  /* 4px */
  --spacing-2: 0.5rem;   /* 8px */
  --spacing-3: 0.75rem;  /* 12px */
  --spacing-4: 1rem;     /* 16px */
  --spacing-5: 1.25rem;  /* 20px */
  --spacing-6: 1.5rem;   /* 24px */
  --spacing-8: 2rem;     /* 32px */
  --spacing-10: 2.5rem;  /* 40px */
  --spacing-12: 3rem;    /* 48px */
  --spacing-16: 4rem;    /* 64px */

  /* ----------------------------------------
   * BORDERS & SHAPES
   * Border radii, widths, and styles
   * ---------------------------------------- */
  
  /* Border radii */
  --radius-sm: 0.25rem;  /* 4px - Tags, small elements */
  --radius-md: 0.5rem;   /* 8px - Cards, containers */
  --radius-lg: 0.75rem;  /* 12px - Large elements */
  --radius-xl: 1rem;     /* 16px - Extra large elements */
  --radius-full: 9999px; /* Fully rounded (circles, pills) */
  
  /* Border widths */
  --border-width-thin: 1px;
  --border-width-normal: 2px;
  --border-width-thick: 3px;

  /* ----------------------------------------
   * EFFECTS
   * Shadows, transitions, animations
   * ---------------------------------------- */
  
  /* Shadows */
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  
  /* Transitions */
  --transition-fast: 0.2s ease;
  --transition-normal: 0.3s ease;
  --transition-slow: 0.5s ease;

  /* ----------------------------------------
   * LAYOUT
   * Container sizes, breakpoints, grid settings
   * ---------------------------------------- */
  
  /* Container widths */
  --container-sm: 640px;
  --container-md: 768px;
  --container-lg: 1024px;
  --container-xl: 1280px;
  --container-2xl: 1536px;
  
  /* Breakpoints */
  --breakpoint-sm: 640px;
  --breakpoint-md: 768px;
  --breakpoint-lg: 1024px;
  --breakpoint-xl: 1280px;
  --breakpoint-2xl: 1536px;
  
  /* Grid settings */
  --grid-gap-sm: 0.5rem;
  --grid-gap-md: 1rem;
  --grid-gap-lg: 1.5rem;
  --grid-gap-xl: 2rem;

  /* ----------------------------------------
   * COMPONENT-SPECIFIC VARIABLES
   * Using more generic, purpose-based naming
   * ---------------------------------------- */
  
  /* Card Component */
  --card-bg: var(--color-base-white);
  --card-title-color: var(--color-brand-dark);
  --card-desc-color: var(--color-base-med);
  --card-border-radius: var(--radius-md);
  --card-padding: var(--spacing-4);
  --card-image-ratio: 6/4;
  --card-shadow: var(--shadow-md);
  --card-shadow-hover: var(--shadow-lg);
  --card-hover-transform: translateY(-5px);
  
  /* Tag Component */
  --tag-bg: var(--color-neutral-light);
  --tag-color: var(--color-neutral-dark);
  --tag-radius: var(--radius-sm);
  --tag-shadow: var(--shadow-sm);
  --tag-padding: var(--spacing-1) var(--spacing-2);
  --tag-font-size: var(--font-size-xs);
  
  /* Feature Tag Component */
  --feature-bg: var(--color-brand-light);
  --feature-color: var(--color-brand-dark);
  
  /* Badge Component */
  --badge-bg: var(--color-accent-light);
  --badge-color: var(--color-accent-dark);
  --badge-radius: var(--radius-sm);
  --badge-shadow: var(--shadow-sm);
  
  /* Button Component */
  --button-bg: var(--color-bg-button);
  --button-color: var(--color-text-button);
  --button-hover-bg: var(--color-bg-button-hover);
  --button-radius: var(--radius-md);
  --button-padding: var(--spacing-2) var(--spacing-4);
  --button-font-weight: var(--font-weight-medium);
  
  /* Filter Component */
  --filter-bg: var(--color-bg-sidebar);
  --filter-title-color: var(--color-text-heading);
  --filter-text-color: var(--color-text-body);
  --filter-border-color: var(--color-border-light);
  --filter-section-title-color: var(--color-text-link);
  --filter-border-radius: var(--radius-md);
  --filter-padding: var(--spacing-6);
  --filter-shadow: var(--shadow-md);
}

/**
 * Theme Style Variants
 * These are complete theme variants that can be applied to the block
 */

/* Theme Style 1 - Default/Light (defined in :root above) */

/* Theme Style 2 - Warm */
.theme-style2 {
  /* Brand color family - Orange */
  --color-brand-light: oklch(85% 0.15 60);
  --color-brand-base: oklch(70% 0.2 60);
  --color-brand-dark: oklch(55% 0.25 60);
  
  /* Accent color family - Red */
  --color-accent-light: oklch(85% 0.15 30);
  --color-accent-base: oklch(70% 0.2 30);
  --color-accent-dark: oklch(55% 0.25 30);
  
  /* Background colors */
  --color-bg-page: oklch(98% 0.03 80);
  --color-bg-card: oklch(98% 0.03 80);
  --color-bg-sidebar: oklch(98% 0.03 80);
  
  /* Feature tag */
  --color-bg-feature: oklch(calc(lightness(var(--color-brand-light)) + 5%) calc(chroma(var(--color-brand-light)) * 0.5) hue(var(--color-brand-light)));
}

/* Theme Style 3 - Cool */
.theme-style3 {
  /* Brand color family - Blue */
  --color-brand-light: oklch(85% 0.15 240);
  --color-brand-base: oklch(70% 0.2 240);
  --color-brand-dark: oklch(55% 0.25 240);
  
  /* Accent color family - Purple */
  --color-accent-light: oklch(85% 0.15 280);
  --color-accent-base: oklch(70% 0.2 280);
  --color-accent-dark: oklch(55% 0.25 280);
  
  /* Background colors */
  --color-bg-page: oklch(98% 0.03 220);
  --color-bg-card: oklch(98% 0.03 220);
  --color-bg-sidebar: oklch(98% 0.03 220);
  
  /* Feature tag */
  --color-bg-feature: oklch(calc(lightness(var(--color-brand-light)) + 5%) calc(chroma(var(--color-brand-light)) * 0.5) hue(var(--color-brand-light)));
}

/* Dark Mode - can be combined with any style */
.theme-dark {
  /* Base colors */
  --color-base-white: oklch(25% 0 0);
  --color-base-light: oklch(35% 0 0);
  --color-base-med: oklch(50% 0 0);
  --color-base-dark: oklch(90% 0 0);
  --color-base-black: oklch(98% 0 0);
  
  /* Neutral colors */
  --color-neutral-light: oklch(35% 0.02 60);
  --color-neutral-base: oklch(70% 0.02 60);
  --color-neutral-dark: oklch(85% 0.02 60);
  
  /* Background colors */
  --color-bg-page: oklch(15% 0 0);
  --color-bg-card: oklch(25% 0 0);
  --color-bg-sidebar: oklch(25% 0 0);
  
  /* Text colors */
  --color-text-heading: oklch(95% 0 0);
  --color-text-body: oklch(90% 0 0);
  --color-text-muted: oklch(80% 0 0);
  
  /* Shadows */
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
  
  /* Tag */
  --color-bg-tag: oklch(35% 0.02 60);
  --color-text-tag: oklch(90% 0 0);
}

/**
 * Component Style Variants
 * These are style variants for specific components
 */

/* Card Style Variants */
.card-style1 {
  /* Default card style (defined in :root) */
}

.card-style2 {
  --card-bg: var(--color-base-light);
  --card-shadow: none;
  border: 1px solid var(--color-border-light);
}

.card-style3 {
  --card-bg: var(--color-brand-light);
  --card-title-color: var(--color-brand-dark);
}

/* Tag Style Variants */
.tag-style1 {
  /* Default tag style (defined in :root) */
}

.tag-style2 {
  --tag-bg: oklch(calc(lightness(var(--color-brand-light)) + 10%) calc(chroma(var(--color-brand-light)) * 0.1) hue(var(--color-brand-light)));
  --tag-color: var(--color-text-link);
  --tag-radius: 0;
  border-bottom: 1px solid var(--color-border-light);
}

.tag-style3 {
  --tag-bg: var(--color-brand-base);
  --tag-color: var(--color-text-button);
  --tag-radius: var(--radius-full);
}

/* Button Style Variants */
.button-style1 {
  /* Default button style (defined in :root) */
}

.button-style2 {
  --button-bg: oklch(calc(lightness(var(--color-brand-light)) + 10%) calc(chroma(var(--color-brand-light)) * 0.2) hue(var(--color-brand-light)));
  --button-color: var(--color-brand-base);
  --button-hover-bg: oklch(calc(lightness(var(--color-brand-light)) + 5%) calc(chroma(var(--color-brand-light)) * 0.3) hue(var(--color-brand-light)));
  border: 1px solid var(--color-brand-base);
}

.button-style3 {
  --button-radius: var(--radius-full);
  --button-bg: var(--color-accent-base);
  --button-hover-bg: var(--color-accent-dark);
}

@import "tailwindcss";

/* via esm.sh CDN */
@plugin 'https://esm.sh/@tailwindcss/typography'; 
@plugin '@tailwindcss/typography' {
    strategy: 'class';
}

:root {
  /* ----------------------------------------
   * FOUNDATIONAL PALETTE (Single Source of Truth)
   * ---------------------------------------- */
  --theme-palette-color-1: #97bfc4; /* Primary Light */
  --theme-palette-color-2: #598992; /* Primary Med */
  --theme-palette-color-3: #276270; /* Primary Dark */
  --theme-palette-color-4: #e7c7ca; /* Secondary Light */
  --theme-palette-color-5: #ac727a; /* Secondary Med */
  --theme-palette-color-6: #90494f; /* Secondary Dark */
  --theme-palette-color-7: #d9d8d5; /* Neutral Light */
  --theme-palette-color-8: #a29d94; /* Neutral Med */
  --theme-palette-color-10: #4f4d46; /* Neutral Dark */
  --theme-palette-color-11: #ffffff; /* White */
  --theme-palette-color-12: #c9c9c9; /* Base Light */
  --theme-palette-color-13: #828282; /* Base Med */
  --theme-palette-color-14: #464646; /* Base Dark */
  --theme-palette-color-15: #000000; /* Black */
}

@theme inline {
  /* ----------------------------------------
   * BASE COLOR PALETTE
   * These are the foundation colors that all other variables reference
   * ---------------------------------------- */
  
  /* Primary color family - Teal/Blue */
  --color-primary-light: var(--theme-palette-color-1);
  --color-primary-med: var(--theme-palette-color-2);
  --color-primary-dark: var(--theme-palette-color-3);
  
  /* Secondary color family - Purple/Pink */
  --color-secondary-light: var(--theme-palette-color-4);
  --color-secondary-med: var(--theme-palette-color-5);
  --color-secondary-dark: var(--theme-palette-color-6);
  
  /* Neutral color family - Grays */
  --color-neutral-light: var(--theme-palette-color-7);
  --color-neutral-med: var(--theme-palette-color-8);
  --color-neutral-dark: var(--theme-palette-color-10);
  
  /* Base color family - White to Black */
  --color-base-white: var(--theme-palette-color-11);
  --color-base-light: var(--theme-palette-color-12);
  --color-base-med: var(--theme-palette-color-13);
  --color-base-dark: var(--theme-palette-color-14);
  --color-base-black: var(--theme-palette-color-15);

  /* RGB versions for opacity adjustments */
  --color-primary-light-rgb: 151, 191, 196; /* #97bfc4 */
  --color-primary-med-rgb: 89, 137, 146; /* #598992 */
  --color-primary-dark-rgb: 39, 98, 112; /* #276270 */
  
  /* ----------------------------------------
   * SEMANTIC COLOR ASSIGNMENTS
   * These map the base colors to their semantic purposes
   * ---------------------------------------- */
  
  /* Background colors */
  --color-site-background: var(--color-base-white);
  --color-card-background: var(--color-base-white);
  --color-sidebar-background: var(--color-base-white);
  
  /* Text colors */
  --color-text-primary: var(--color-base-dark);
  --color-text-secondary: var(--color-neutral-med);
  --color-text-accent: var(--color-primary-med);
  --color-sidebar-text: var(--color-base-dark);
  
  /* Interactive state colors */
  --color-primary-hover: var(--color-primary-light);
  --color-primary-active: var(--color-primary-dark);
  --color-secondary-hover: var(--color-secondary-light);
  --color-secondary-active: var(--color-secondary-dark);
  --color-neutral-hover: var(--color-neutral-light);
  --color-neutral-active: var(--color-neutral-dark);
  --color-base-hover: var(--color-base-light);
  --color-base-active: var(--color-base-dark);

  /* ----------------------------------------
   * TYPOGRAPHY
   * Font sizes, weights, and line heights
   * ---------------------------------------- */
  
  /* Font sizes */
  --font-xs: 0.75rem;    /* 12px - Tags, badges, small text */
  --font-sm: 0.875rem;   /* 14px - Descriptions, secondary text */
  --font-base: 1rem;     /* 16px - Regular text */
  --font-lg: 1.125rem;   /* 18px - Card titles, section headings */
  --font-xl: 1.25rem;    /* 20px - Main headings */
  --font-2xl: 1.5rem;    /* 24px - Large headings */
  
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
  
  /* Specific named radii */
  --radius-btn: 0.375rem;  /* Button radius */
  --radius-tag: 0.25rem;   /* Tag radius */

  /* ----------------------------------------
   * EFFECTS
   * Shadows, transitions, animations
   * ---------------------------------------- */
  
  /* Shadows */
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  
  /* Focus effects */
  --shadow-btn: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  --shadow-btn-focus-ring: 0 0 0 3px;
  --focus-ring-offset-width: 2px;
  
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
   * Variables for specific UI components
   * ---------------------------------------- */
  
  /* Card Component */
  --card-bg: var(--color-card-background);
  --card-title-color: var(--color-text-primary);
  --card-desc-color: var(--color-text-secondary);
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
  --tag-font-size: var(--font-xs);
  
  /* Feature Tag Component */
  --feature-tag-bg: rgba(var(--color-primary-light-rgb), 0.3);
  --feature-tag-color: var(--color-primary-dark);
  
  /* Badge Component */
  --badge-primary-bg: var(--color-primary-med);
  --badge-primary-color: white;
  --badge-secondary-bg: var(--color-secondary-med);
  --badge-secondary-color: white;
  --badge-radius: var(--radius-sm);
  --badge-shadow: var(--shadow-sm);
  
  /* Button Component */
  --button-primary-bg: var(--color-primary-med);
  --button-primary-color: white;
  --button-primary-hover-bg: var(--color-primary-dark);
  --button-radius: var(--radius-btn);
  --button-padding: var(--spacing-2) var(--spacing-4);
  --button-font-weight: var(--font-weight-medium);
  
  /* Filter Component */
  --filter-bg: var(--color-sidebar-background);
  --filter-title-color: var(--color-text-primary);
  --filter-text-color: var(--color-text-primary);
  --filter-border-color: var(--color-base-light);
  --filter-section-title-color: var(--color-primary-med);
  --filter-border-radius: var(--radius-md);
  --filter-padding: var(--spacing-6);
  --filter-shadow: var(--shadow-md);
}
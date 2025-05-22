/**
 * Theme Style Variants
 * These are complete theme variants that can be applied to the block
 */

/* Theme Style 1 - Default/Light (defined in :root above) */

/* Theme Style 2 - Warm */
.theme-style2 {
  /* Primary color family - Orange */
  --color-primary-light: #FED7AA;
  --color-primary-med: #F97316;
  --color-primary-dark: #C2410C;
  
  /* Secondary color family - Red */
  --color-secondary-light: #FECACA;
  --color-secondary-med: #EF4444;
  --color-secondary-dark: #B91C1C;
  
  /* Update RGB values */
  --color-primary-light-rgb: 254, 215, 170;
  --color-primary-med-rgb: 249, 115, 22;
  --color-primary-dark-rgb: 194, 65, 12;
  
  /* Background colors */
  --color-site-background: #FFFBEB;
  --color-card-background: #FFFBEB;
  --color-sidebar-background: #FFFBEB;
  
  /* Other component-specific overrides as needed */
  --feature-tag-bg: rgba(var(--color-primary-light-rgb), 0.3);
  --feature-tag-color: var(--color-primary-dark);
}

/* Theme Style 3 - Cool */
.theme-style3 {
  /* Primary color family - Blue */
  --color-primary-light: #BFDBFE;
  --color-primary-med: #3B82F6;
  --color-primary-dark: #1D4ED8;
  
  /* Secondary color family - Purple */
  --color-secondary-light: #DDD6FE;
  --color-secondary-med: #8B5CF6;
  --color-secondary-dark: #6D28D9;
  
  /* Update RGB values */
  --color-primary-light-rgb: 191, 219, 254;
  --color-primary-med-rgb: 59, 130, 246;
  --color-primary-dark-rgb: 29, 78, 216;
  
  /* Background colors */
  --color-site-background: #F0F9FF;
  --color-card-background: #F0F9FF;
  --color-sidebar-background: #F0F9FF;
  
  /* Other component-specific overrides as needed */
  --feature-tag-bg: rgba(var(--color-primary-light-rgb), 0.3);
  --feature-tag-color: var(--color-primary-dark);
}

/* Dark Mode - can be combined with any style */
.theme-dark {
  /* Base colors */
  --color-base-white: #1F2937;
  --color-base-light: #374151;
  --color-base-med: #4B5563;
  --color-base-dark: #E5E7EB;
  --color-base-black: #F9FAFB;
  
  /* Neutral colors */
  --color-neutral-light: #374151;
  --color-neutral-med: #9CA3AF;
  --color-neutral-dark: #D1D5DB;
  
  /* Background colors */
  --color-site-background: #111827;
  --color-card-background: #1F2937;
  --color-sidebar-background: #1F2937;
  
  /* Text colors */
  --color-text-primary: #F9FAFB;
  --color-text-secondary: #E5E7EB;
  
  /* Shadows */
  --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
  
  /* Other component-specific overrides as needed */
  --tag-bg: #374151;
  --tag-color: #E5E7EB;
}

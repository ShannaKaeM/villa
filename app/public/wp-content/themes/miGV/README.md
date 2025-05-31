# miGV WordPress Theme

A modern, custom WordPress theme built for mi Agency with a focus on performance, accessibility, and the block editor. Features the **Villa DesignBook** - a comprehensive design system management tool for maintaining consistent design tokens and components.

## Features

- **Modern Design**: Clean, professional design with customizable colors and typography
- **Villa DesignBook**: Integrated design system management with live token editing
- **4-Tier Design System**: Atomic design architecture (Primitives → Elements → Components → Sections)
- **Block Editor Ready**: Full support for Gutenberg blocks with custom styling
- **Responsive**: Mobile-first design that works on all devices
- **Accessible**: Built with accessibility best practices (WCAG 2.1 AA compliant)
- **Performance Optimized**: Lightweight and fast-loading
- **Design Token Management**: Centralized control of colors, typography, spacing, and layout
- **SEO Friendly**: Semantic HTML and proper heading structure

## Villa DesignBook System

The Villa DesignBook is a comprehensive design system management tool integrated into the WordPress admin. It provides a visual interface for managing design tokens and components across four architectural tiers.

### Design System Architecture

```
Villa DesignBook
├── 🎨 Primitives (Design Tokens)
│   ├── Color Book        # Color palettes and swatches
│   ├── Typography Book   # Font sizes, weights, line heights
│   ├── Spacing Book      # Margins, padding, gaps
│   └── Layout Book       # Breakpoints, containers, grids
├── 🧩 Elements (Basic Components)
│   ├── Button Book       # Button variants and states
│   ├── Form Book         # Input fields, labels, validation
│   └── Icon Book         # Icon library and usage
├── 🏗️ Components (Complex UI)
│   ├── Card Book         # Card layouts and variations
│   ├── Navigation Book   # Menu and navigation patterns
│   └── Hero Book         # Hero section templates
└── 📄 Sections (Page Layouts)
    ├── Header Book       # Header layouts and styles
    ├── Footer Book       # Footer configurations
    └── Content Book      # Content section templates
```

### Accessing Villa DesignBook

Navigate to **WordPress Admin → Villa DesignBook** to access the design system dashboard. From here you can:

- **View Design Tokens**: Browse all primitives (colors, typography, spacing, layout)
- **Edit Live Values**: Modify design tokens with real-time preview
- **Manage Components**: Configure and preview UI components
- **Export Tokens**: Generate CSS custom properties for development

### Design Token Management

Design tokens are stored in `theme.json` and automatically converted to CSS custom properties:

```json
// theme.json
{
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Primary",
          "slug": "primary",
          "color": "#2563eb"
        }
      ]
    }
  }
}
```

Becomes:
```css
:root {
  --wp--preset--color--primary: #2563eb;
}
```

## Installation

1. Download the theme files
2. Upload to `/wp-content/themes/miGV/` directory
3. Activate the theme in WordPress admin
4. Install the Villa DesignBook mu-plugin (if not already present)
5. Access **Villa DesignBook** from the admin menu to configure design tokens

## Theme Structure

```
miGV/
├── assets/
│   ├── css/
│   │   ├── blocks.css              # Block-specific styles
│   │   └── villa-design-book.css   # DesignBook admin styling
│   └── js/
│       ├── main.js                 # Main theme JavaScript
│       ├── customizer.js           # Customizer preview JS
│       └── design-book.js          # DesignBook interactions
├── templates/
│   ├── components/
│   │   ├── card-book.twig          # Card component templates
│   │   ├── primitive-books/        # Primitive component templates
│   │   │   ├── color.twig          # Color swatch templates
│   │   │   ├── spacing.twig        # Spacing preview templates
│   │   │   └── typography.twig     # Typography preview templates
│   │   └── element-books/          # Element component templates
│   └── sections/                   # Section templates
├── blocks/                         # Custom Gutenberg blocks
├── inc/
│   ├── customizer.php              # Customizer settings
│   └── template-functions.php      # Template helper functions
├── functions.php                   # Main theme functions
├── style.css                       # Main stylesheet
├── theme.json                      # Block editor & design token configuration
└── README.md                       # This file
```

### Villa DesignBook Files

```
wp-content/
├── mu-plugins/
│   └── villa-design-book.php       # Main DesignBook plugin
└── themes/miGV/
    ├── assets/css/
    │   └── villa-design-book.css   # DesignBook admin styles
    ├── templates/components/       # Twig component templates
    └── theme.json                  # Design token definitions
```

## Styling Architecture

### CSS Organization

The theme follows a structured CSS architecture:

1. **Design Tokens** (`theme.json`) → CSS Custom Properties
2. **Base Styles** (`style.css`) → Global resets and typography
3. **Component Styles** (`blocks.css`) → Block-specific styling
4. **Admin Styles** (`villa-design-book.css`) → DesignBook interface

### Design Token Flow

```
theme.json → CSS Custom Properties → Component Styles → Final Output
```

Example:
```css
/* Generated from theme.json */
:root {
  --wp--preset--color--primary: #2563eb;
  --wp--preset--font-size--large: 1.25rem;
  --wp--preset--spacing--medium: 1.5rem;
}

/* Used in component styles */
.wp-block-button__link {
  background-color: var(--wp--preset--color--primary);
  font-size: var(--wp--preset--font-size--large);
  padding: var(--wp--preset--spacing--medium);
}
```

### Responsive Design

The theme uses a mobile-first approach with these breakpoints:

- **Mobile**: < 768px
- **Tablet**: 768px - 1024px  
- **Desktop**: 1024px - 1280px
- **Large**: > 1280px

## Villa DesignBook Usage

### Color Management

1. Navigate to **Villa DesignBook → Color Book**
2. Edit color values using the visual color picker
3. Preview changes in real-time
4. Save to update `theme.json` automatically

### Typography System

1. Access **Villa DesignBook → Typography Book**
2. Configure font sizes, weights, line heights, and letter spacing
3. Preview text samples at different scales
4. Export CSS custom properties for development

### Component Development

Components are built using Twig templates with design token integration:

```twig
{# templates/components/card-book.twig #}
<div class="card" style="
  background: var(--wp--preset--color--surface);
  padding: var(--wp--preset--spacing--large);
  border-radius: var(--wp--preset--border-radius--medium);
">
  <h3 style="color: var(--wp--preset--color--primary);">
    {{ card.title }}
  </h3>
  <p style="color: var(--wp--preset--color--text);">
    {{ card.description }}
  </p>
</div>
```

## Customization

### Adding New Design Tokens

1. Edit `theme.json` to add new tokens:
```json
{
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Custom Color",
          "slug": "custom",
          "color": "#ff6b6b"
        }
      ]
    }
  }
}
```

2. Access via CSS custom property:
```css
.my-component {
  color: var(--wp--preset--color--custom);
}
```

### Creating Custom Components

1. Create Twig template in `templates/components/`
2. Add component preview to appropriate DesignBook section
3. Style using design tokens from `theme.json`
4. Test across all breakpoints

## Development Workflow

### Design Token Updates

1. **Edit in DesignBook**: Use visual interface for quick changes
2. **Direct JSON Edit**: Modify `theme.json` for bulk updates  
3. **CSS Generation**: Tokens automatically become CSS custom properties
4. **Component Update**: All components inherit new token values

### Component Development

1. **Design in DesignBook**: Preview and iterate on component design
2. **Build Template**: Create Twig template with token integration
3. **Add Styles**: Use CSS custom properties for consistent styling
4. **Test Responsive**: Verify across all breakpoints
5. **Document**: Add to appropriate DesignBook section

## Browser Support

- Chrome (latest 2 versions)
- Firefox (latest 2 versions)  
- Safari (latest 2 versions)
- Edge (latest 2 versions)

## Performance

- **Lightweight**: Minimal CSS and JavaScript
- **Design Token Efficiency**: CSS custom properties reduce redundancy
- **Optimized Images**: Responsive image support
- **Caching Friendly**: Proper asset versioning
- **Core Web Vitals**: Optimized for Google's performance metrics

## Accessibility

- **WCAG 2.1 AA Compliant**: Meets accessibility standards
- **Keyboard Navigation**: Full keyboard support
- **Screen Reader Friendly**: Proper ARIA labels and semantic HTML
- **Focus Management**: Visible focus indicators
- **Color Contrast**: Automated contrast checking in DesignBook
- **Design Token Accessibility**: Built-in contrast ratio validation

## Development

### Requirements

- WordPress 6.0+
- PHP 8.0+
- Modern browser with CSS Grid support
- Timber/Twig for templating

### Local Development

1. Clone the repository
2. Set up local WordPress environment
3. Install theme in `/wp-content/themes/`
4. Ensure Villa DesignBook mu-plugin is active
5. Access DesignBook via admin menu

### Code Standards

- **WordPress Coding Standards**: PSR-4 autoloading
- **Modern CSS**: CSS Grid, Flexbox, Custom Properties
- **Design Token Architecture**: Atomic design principles
- **Twig Templating**: Component-based architecture
- **ES6+ JavaScript**: Modern JavaScript features
- **Accessibility First**: WCAG guidelines followed

## Troubleshooting

### DesignBook Not Loading
- Verify `villa-design-book.php` is in `mu-plugins/`
- Check file permissions
- Ensure Timber plugin is active

### Design Tokens Not Updating
- Clear any caching plugins
- Verify `theme.json` syntax is valid
- Check browser console for JavaScript errors

### Component Styling Issues
- Verify CSS custom properties are loading
- Check for conflicting styles
- Use browser dev tools to inspect token values

## Support

For support and customization requests, contact mi Agency:

- Website: [miagency.com](https://miagency.com)
- Email: support@miagency.com

## License

This theme is proprietary software developed by mi Agency. All rights reserved.

## Changelog

### Version 2.0.0
- Added Villa DesignBook system
- Implemented 4-tier design architecture  
- Integrated design token management
- Added Twig templating for components
- Enhanced responsive design system
- Improved accessibility features

### Version 1.0.0
- Initial release
- Core theme functionality
- Block editor support
- Customizer integration
- Responsive design
- Accessibility features

---

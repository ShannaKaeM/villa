# miGV WordPress Theme

A modern, custom WordPress theme built for mi Agency with a focus on performance, accessibility, and the block editor.

## Features

- **Modern Design**: Clean, professional design with customizable colors and typography
- **Block Editor Ready**: Full support for Gutenberg blocks with custom styling
- **Responsive**: Mobile-first design that works on all devices
- **Accessible**: Built with accessibility best practices (WCAG 2.1 AA compliant)
- **Performance Optimized**: Lightweight and fast-loading
- **Customizable**: Extensive customizer options for colors, typography, and layout
- **SEO Friendly**: Semantic HTML and proper heading structure

## Installation

1. Download the theme files
2. Upload to `/wp-content/themes/miGV/` directory
3. Activate the theme in WordPress admin
4. Customize via Appearance > Customize

## Theme Structure

```
miGV/
├── assets/
│   ├── css/
│   │   └── blocks.css          # Block-specific styles
│   └── js/
│       ├── main.js             # Main theme JavaScript
│       └── customizer.js       # Customizer preview JS
├── blocks/                     # Custom Gutenberg blocks
├── inc/
│   ├── customizer.php          # Customizer settings
│   └── template-functions.php  # Template helper functions
├── functions.php               # Main theme functions
├── style.css                   # Main stylesheet
├── theme.json                  # Block editor configuration
├── index.php                   # Main template
├── header.php                  # Header template
├── footer.php                  # Footer template
├── sidebar.php                 # Sidebar template
├── single.php                  # Single post template
├── page.php                    # Page template
└── README.md                   # This file
```

## Customization

### Colors

The theme uses a comprehensive color system with CSS custom properties:

- **Primary**: Main brand color (default: #2563eb)
- **Secondary**: Accent color (default: #7c3aed)
- **Neutral**: Text and UI elements (default: #6b7280)
- **Gray Scale**: 50-900 variations for backgrounds and borders

### Typography

- **Primary Font**: Inter (system fallback)
- **Monospace Font**: JetBrains Mono (system fallback)
- **Responsive Scale**: 10 predefined font sizes
- **Custom Properties**: Easy to override via CSS

### Layout

- **Container Width**: 1280px (customizable)
- **Wide Width**: 1536px
- **Responsive Breakpoints**: Mobile-first approach
- **Sidebar**: Configurable left/right/none positioning

## Block Editor Support

The theme includes comprehensive styling for all core WordPress blocks:

- **Button**: Custom styling with hover effects
- **Quote/Pullquote**: Branded styling with primary color accents
- **Code/Preformatted**: Syntax highlighting ready
- **Table**: Clean, responsive table styling
- **Image/Gallery**: Responsive with rounded corners
- **Navigation**: Mobile-friendly with dropdown support
- **Search**: Styled form elements

## Customizer Options

Access via **Appearance > Customize > miGV Theme Options**:

### Colors
- Primary Color
- Secondary Color

### Typography
- Body Font Size

### Layout
- Container Width
- Sidebar Position

### Header
- Header Style (Default/Minimal/Centered)
- Show Search in Header

### Footer
- Footer Copyright Text
- Show Footer Widgets

## Menus

The theme supports two menu locations:

1. **Primary Menu**: Main navigation in header
2. **Footer Menu**: Links in footer area

## Widget Areas

1. **Sidebar**: Main sidebar widget area
2. **Footer**: Footer widget area

## Custom Functions

### Template Functions

- `migv_posted_on()`: Display post date
- `migv_posted_by()`: Display post author
- `migv_entry_footer()`: Display post meta
- `migv_get_theme_color()`: Get theme color values
- `migv_responsive_image()`: Generate responsive images

### Customizer Functions

- Live preview for colors and typography
- Real-time updates without page refresh
- Sanitization for all user inputs

## Browser Support

- Chrome (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Edge (latest 2 versions)

## Performance

- **Lightweight**: Minimal CSS and JavaScript
- **Optimized Images**: Responsive image support
- **Caching Friendly**: Proper asset versioning
- **Core Web Vitals**: Optimized for Google's performance metrics

## Accessibility

- **WCAG 2.1 AA Compliant**: Meets accessibility standards
- **Keyboard Navigation**: Full keyboard support
- **Screen Reader Friendly**: Proper ARIA labels and semantic HTML
- **Focus Management**: Visible focus indicators
- **Color Contrast**: Meets minimum contrast ratios

## Development

### Requirements

- WordPress 5.9+
- PHP 7.4+
- Modern browser with CSS Grid support

### Local Development

1. Clone the repository
2. Set up local WordPress environment
3. Install theme in `/wp-content/themes/`
4. Activate and customize

### Code Standards

- **WordPress Coding Standards**: PSR-4 autoloading
- **Modern CSS**: CSS Grid, Flexbox, Custom Properties
- **ES6+ JavaScript**: Modern JavaScript features
- **Accessibility First**: WCAG guidelines followed

## Support

For support and customization requests, contact mi Agency:

- Website: [miagency.com](https://miagency.com)
- Email: support@miagency.com

## License

This theme is proprietary software developed by mi Agency. All rights reserved.

## Changelog

### Version 1.0.0
- Initial release
- Core theme functionality
- Block editor support
- Customizer integration
- Responsive design
- Accessibility features

---

**miGV Theme** - Built with ❤️ by mi Agency

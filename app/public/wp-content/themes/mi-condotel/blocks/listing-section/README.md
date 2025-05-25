# Listing Section Block

A modern, island-architecture block for displaying listings with filtering capabilities.

## Architecture

This block follows the **island architecture** pattern:
- **No SASS/SCSS** - Uses plain CSS only
- **Self-contained** - All styles are in `styles/block.css`

## File Structure

```
listing-section/
├── block.json          # Block configuration
├── render.php          # Server-side rendering
├── styles/
│   └── block.css      # ALL STYLES ARE HERE (plain CSS)
├── src/
│   ├── index.js       # Block registration
│   ├── edit.js        # Editor interface
│   └── view.js        # Frontend JavaScript
├── build/             # Compiled JavaScript only
└── partials/          # PHP templates for cards

```

## Important Notes

1. **CSS Location**: All styles are in `styles/block.css` - this is plain CSS, not SASS
2. **No SCSS Files**: Unlike traditional WordPress blocks, we don't use or need any SCSS files
3. **No Legacy**: This replaces the old `card-loop-native` block

## Block Controls

- **Post Type**: Select from Properties, Articles, Businesses, or User Profiles
- **Posts Per Page**: Number of items to display
- **Columns**: Grid layout (1-4 columns)
- **Show Filter**: Toggle filtering sidebar
- **Filter Position**: Left or top
- **Card Style**: Different card layouts per post type
- **Show Pagination**: Enable/disable pagination

## Development

```bash
# Build JavaScript files only (CSS is already plain)
npm run build

# Watch mode for development
npm run start
```

## CSS Variables

The block uses CSS variables from the theme's design system:
- Colors: `--color-primary`, `--color-neutral-*`, etc.
- Typography: `--font-size-*`, `--font-weight-*`
- Spacing: Standard rem units

All variables are defined in `/assets/css/base.css`

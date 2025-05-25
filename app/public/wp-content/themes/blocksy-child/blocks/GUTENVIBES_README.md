# GutenVibes CSS Editor Integration

This document explains how the Villa Community blocks are set up to work with the GutenVibes CSS Editor.

## Structure Overview

```
blocks/
├── gutenvibes.config.json      # Configuration file for GutenVibes
├── gutenvibes-integration.php   # PHP integration endpoints
├── styles/
│   └── variables.css           # Global CSS variables
├── page-header/
│   ├── block.json              # WordPress block definition
│   ├── styles/
│   │   └── block.css          # Editable CSS for this block
│   └── ... (other block files)
└── listing-section/
    ├── block.json
    ├── styles/
    │   └── block.css
    └── ... (other block files)
```

## Key Features

### 1. Separated CSS Files
Each block has its own `styles/block.css` file that can be edited independently without affecting the block's JavaScript or PHP functionality.

### 2. CSS Variables System
The `styles/variables.css` file provides consistent design tokens across all blocks:
- Typography scales
- Color palette
- Spacing units
- Shadows and transitions

### 3. REST API Endpoints
The integration provides two endpoints:
- `GET /wp-json/gutenvibes/v1/blocks` - Returns block configuration and current CSS
- `POST /wp-json/gutenvibes/v1/blocks/{block-name}/css` - Updates a block's CSS

### 4. Configuration File
The `gutenvibes.config.json` file tells the CSS editor:
- Which blocks are available
- What selectors each block uses
- Which controls are available in WordPress
- Editor capabilities (pseudo-elements, media queries, etc.)

## CSS Class Naming Conventions

### Clean Semantic Classes
All blocks use clean, semantic CSS class names without legacy prefixes:
- `.card` (not `.m-card`)
- `.filter-sidebar` (not `.m-filter-sidebar`)
- `.badge` (not `.m-badge`)

### BEM-style Naming
Components follow BEM-style naming for clarity:
- Block: `.card`
- Element: `.card__title`, `.card__content`
- Modifier: `.card--property`, `.card--article`

### Recent Updates (May 2025)
- Removed all `m-` prefixes from listing-section block
- Updated all PHP templates to use new class names
- Updated JavaScript selectors to match
- Synchronized gutenvibes.config.json with new selectors

## For Daniel's CSS Editor

### Reading Block CSS
1. Make a GET request to `/wp-json/gutenvibes/v1/blocks`
2. This returns the complete configuration including current CSS content
3. Each block includes:
   - Name and title
   - CSS file location
   - Available selectors
   - Current CSS content

### Updating Block CSS
1. Make a POST request to `/wp-json/gutenvibes/v1/blocks/{block-name}/css`
2. Include the updated CSS in the request body
3. The server will:
   - Validate permissions
   - Write the CSS file
   - Clear caches
   - Return success/error status

### Selector Mapping
Each block configuration includes a `selectors` object that maps semantic names to actual CSS selectors:

```json
"selectors": {
  "root": ".wp-block-miblocks-page-header",
  "title": ".page-header-title",
  "subtitle": ".page-header-subtitle"
}
```

This allows the CSS editor to provide a user-friendly interface while generating proper CSS.

### WordPress Controls Integration
The `controls` array in each block configuration lists which settings are controlled via WordPress block controls:

```json
"controls": [
  "backgroundColor",
  "textColor",
  "overlayOpacity",
  "height"
]
```

The CSS editor should avoid overriding these properties to prevent conflicts.

## Security

- All endpoints require authentication
- CSS updates require `edit_theme_options` capability
- CSS content is sanitized before saving

## Next Steps for Integration

1. **CSS Editor JavaScript**: The editor needs to be loaded in WordPress admin
2. **Visual Preview**: Consider using WordPress's block editor preview API
3. **Sync with Block Controls**: The editor should read block attributes to avoid conflicts
4. **Version Control**: Consider adding CSS versioning/history

## Testing the Integration

1. Check if endpoints are working:
   ```bash
   curl -X GET https://your-site.com/wp-json/gutenvibes/v1/blocks \
     -H "X-WP-Nonce: YOUR_NONCE"
   ```

2. Update block CSS:
   ```bash
   curl -X POST https://your-site.com/wp-json/gutenvibes/v1/blocks/page-header/css \
     -H "X-WP-Nonce: YOUR_NONCE" \
     -H "Content-Type: application/json" \
     -d '{"css": "/* Your CSS here */"}'
   ```

## Questions for Daniel

1. **Editor Loading**: How should the CSS editor be initialized? As a WordPress plugin, or embedded in the theme?
2. **Preview System**: Should we use WordPress's block preview or a custom preview system?
3. **Selector Restrictions**: Are there any CSS selectors that should be prohibited for security?
4. **Storage**: Should we store CSS history/versions in the database?
5. **Multi-site**: Any special considerations for WordPress multisite installations?

## Contact

For questions about this integration, please contact the Villa Community development team.

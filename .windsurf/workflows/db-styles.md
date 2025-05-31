---
description: Villa DesignBook - Complete Architecture & Development Guide
---

# Villa DesignBook Architecture Guide

## 🏗️ **ADMIN PAGES ARCHITECTURE**

### WordPress Admin Interface
- **Location**: `/app/public/wp-content/mu-plugins/design-book.php`
- **Class**: `DesignBook` 
- **Access**: WordPress Admin → DesignBook menu
- **Pages Structure**:
  - **Main Dashboard**: `design-book` (render_main_page)
  - **🎨 Color Book**: `color-book` (render_color_book) 
  - **📝 Typography Book**: `typography-book` (render_typography_book)
  - **📐 Layout Book**: `layout-book` (render_layout_book)
  - **🖋️ Button Book**: `button-book` (render_button_book)
  - **📄 Text Book**: `text-book` (render_text_book)
  - **🃏 Card Book**: `card-book` (render_component_book)
  - **🦸 Hero Book**: `hero-book` (render_hero_book)

### Admin Page Methods
Each admin page has a corresponding `render_*()` method in the DesignBook class that generates the HTML interface.

## 📁 **FILE LOCATIONS**

### CSS Styling
- **Location**: `app/public/wp-content/themes/miGV/assets/css/design-book.css`
- **Purpose**: All styling for DesignBook admin interfaces
- **Includes**: Tabs, cards, controls, previews, grids, responsive design

### JavaScript Functionality  
- **Location**: `app/public/wp-content/themes/miGV/assets/js/design-book.js`
- **Purpose**: Interactive functionality for all DesignBook pages
- **Modules**: ColorBook, TextBook, CardBook, ButtonBook, etc.
- **Features**: Live previews, AJAX save/reset, tab switching, form controls

### Twig Components
- **Base Location**: `app/public/wp-content/themes/miGV/templates/components/`
- **Organization**:
  - **Primitives**: `prmimitive-books/` (color.twig, typography.twig, layout.twig, spacing.twig)
  - **Elements**: `element-books/` (text-book.twig, button-book.twig)
  - **Components**: `components/` (card-atomic.twig, etc.)
  - **Sections**: `sections/` (hero components, etc.)

### Theme Configuration
- **Location**: `app/public/wp-content/themes/miGV/theme.json`
- **Purpose**: WordPress design tokens (colors, typography, spacing, etc.)

### PHP Functions
- **Location**: `app/public/wp-content/themes/miGV/functions.php`
- **Purpose**: AJAX handlers for save/reset operations
- **Handlers**: 
  - `villa_handle_save_text_styles()`
  - `villa_handle_reset_text_styles()`
  - `villa_handle_save_base_styles()`
  - `villa_handle_reset_base_styles()`

## 🔄 **INTEGRATION FLOW**

### Admin Page → Twig Template Integration
1. **Admin Method**: `render_typography_book()` in `design-book.php`
2. **Renders**: Twig template via `Timber::render('components/prmimitive-books/typography-book.twig')`
3. **Context**: Passes theme.json data, nonces, AJAX URLs to template

### Data Synchronization
- **Source of Truth**: `theme.json` design tokens
- **Sync Target 1**: Twig component defaults (primitives)
- **Sync Target 2**: CSS custom properties
- **Process**: Admin changes → AJAX → PHP handlers → Update both theme.json AND Twig components

### Atomic Design Hierarchy
```
theme.json → Primitives → Elements → Components → Sections
```
- **Primitives** consume theme.json tokens directly
- **Elements** compose primitives into semantic components  
- **Components** compose elements for complex UI
- **Sections** compose components for page layouts

## 🛠️ **DEVELOPMENT WORKFLOW**

### To Update a DesignBook Page:
1. **Admin Interface**: Modify `render_*()` method in `/mu-plugins/design-book.php`
2. **Twig Template**: Update corresponding template in `/templates/components/`
3. **Styling**: Add CSS to `/assets/css/design-book.css`
4. **Functionality**: Add JavaScript to `/assets/js/design-book.js`
5. **AJAX Handlers**: Add PHP handlers to `/functions.php`

### Key Integration Points:
- **Admin pages** use PHP methods to render interfaces
- **Twig templates** provide the actual UI structure
- **CSS/JS assets** handle styling and interactivity
- **AJAX handlers** process save/reset operations
- **theme.json** stores design tokens
- **Twig primitives** consume and apply tokens

### Security & Nonces:
- All AJAX calls use `migv_nonce` for security
- Capability checks: `manage_options` required
- Error handling and user feedback included

## 📋 **CURRENT STATUS**

### Completed DesignBook Pages:
- ✅ **Typography Book**: Interactive editor with tabs, live preview, save/reset
- ✅ **Card Book**: Component builder with variants and code generation
- ✅ **Color Book**: Color palette editor (existing)
- ✅ **Button Book**: Button style editor (existing)

### Architecture Benefits:
- Single source of truth in theme.json
- Atomic design compliance
- Live preview capabilities  
- Persistent design token management
- WordPress admin integration
- Security and error handling
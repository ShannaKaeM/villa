# Carbon Blocks Framework Configuration

This directory contains the core configuration files for the **Carbon Blocks Framework** - a comprehensive file-based routing system for WordPress development using Carbon Fields and Timber.

## 🏗️ System Overview

The Carbon Blocks Framework provides a **zero-configuration, file-based approach** to WordPress development with:

- **🧱 Gutenberg Blocks** - Component-based blocks with responsive CSS compilation
- **📄 Custom Post Types** - File-based post type and meta field registration  
- **🏷️ Custom Taxonomies** - Multi-post type taxonomy support with term meta
- **⚙️ Admin Pages** - Hierarchical admin interface with Carbon Fields
- **📱 Responsive Design** - Built-in breakpoint system with automatic media query wrapping
- **🎨 Component Architecture** - Reusable Twig components and templates

## 📁 Project Structure

```
src/
├── config/              # 🔧 Core configuration files
├── blocks/              # 🧱 Gutenberg blocks (categorized)
├── post-types/          # 📄 Custom post types with field groups
├── taxonomy/            # 🏷️ Custom taxonomies with multi-post type support
└── admin-pages/         # ⚙️ WordPress admin pages and settings
```

## 📚 Documentation Index

### Core System Documentation
- **[📄 Post Types README](../post-types/README.md)** - File-based post type registration and Carbon Fields meta boxes
- **[🏷️ Taxonomies README](../taxonomy/README.md)** - Multi-post type taxonomy system with term meta
- **[⚙️ Admin Pages README](../admin-pages/README.md)** - Hierarchical admin interface creation
- **[🧱 Blocks README](../blocks/README.md)** - Gutenberg blocks with responsive CSS compilation

### Configuration Files
- **[📋 This README](#configuration-files)** - Core configuration overview (you are here)

## 🔧 Configuration Files

### Core Files

- **`setup.php`** - Main theme initialization and auto-discovery orchestration
- **`breakpoints.php`** - Responsive breakpoint definitions for CSS compilation
- **`compile_helpers.php`** - CSS/JS compilation and Timber rendering functions
- **`admin_pages.php`** - Admin pages auto-discovery and helper functions
- **`post_types.php`** - Post types auto-discovery and helper functions  
- **`taxonomy.php`** - Taxonomy auto-discovery and helper functions

### Subdirectories

- **`timber/`** - Timber-specific configuration
  - **`paths.php`** - Auto-discovery of block categories for Timber namespacing
- **`cli/`** - Command-line interface tools (reserved for future use)

## 🚀 How The System Works

### 1. Auto-Discovery Architecture

The framework uses **file-based routing** with automatic discovery:

```php
// Post Types: src/post-types/{type-name}/config.php
// Taxonomies: src/taxonomy/{post-types}/{taxonomy-name}/config.php  
// Admin Pages: src/admin-pages/{page-name}/page.php
// Blocks: src/blocks/{Category}/{block-name}/block.php
```

### 2. Initialization Sequence (`setup.php`)

```php
// 1. Load Carbon Fields & Timber
add_action('after_setup_theme', 'crb_load');
add_action('after_setup_theme', 'example_theme_init_timber');

// 2. Register post types & taxonomies early
add_action('init', function() {
    carbon_post_types_register_all();
    carbon_taxonomies_register_all();
});

// 3. Register Carbon Fields containers after ready
add_action('carbon_fields_register_fields', function() {
    carbon_blocks_register_all();
    carbon_admin_pages_register_all();
    carbon_post_types_register_field_groups_all();
    carbon_taxonomies_register_field_groups_all();
});
```

### 3. Responsive CSS Compilation (`breakpoints.php` + `compile_helpers.php`)

**Breakpoint System:**
```php
'XS' => '@media (max-width: 575.98px)',
'SM' => '@media (min-width: 576px) and (max-width: 767.98px)', 
'MD' => '@media (min-width: 768px) and (max-width: 991.98px)',
'LG' => '', // Base breakpoint - no media query
'XL' => '@media (min-width: 1200px) and (max-width: 1399.98px)',
'2XL' => '@media (min-width: 1400px)'
```

**Compilation Process:**
- **LG.css** = Base styles (no wrapper)
- **Other breakpoints** = Auto-wrapped with media queries
- **Inline injection** = Compiled CSS/JS injected into block output

### 4. Component Architecture

**Timber Integration:**
- Auto-discovered category paths for `@blocks/` namespace
- Component support: `@blocks/category/block-name/components/component.twig`
- Context passing between templates and components

## 🎯 Helper Functions

### Block System
- `carbon_blocks_render_gutenberg()` - Render Gutenberg blocks with Timber
- `carbon_blocks_compile_styles()` - Compile responsive CSS with media queries
- `carbon_blocks_compile_scripts()` - Compile and concatenate JavaScript files

### Post Types  
- `carbon_create_post_type()` - Register post type with intelligent defaults
- `carbon_create_post_meta()` - Create Carbon Fields meta boxes
- `carbon_create_post_meta_with_tabs()` - Create tabbed meta boxes

### Taxonomies
- `carbon_create_taxonomy()` - Register taxonomy with multi-post type support
- `carbon_create_taxonomy_meta()` - Create taxonomy term meta boxes
- `carbon_create_taxonomy_meta_with_tabs()` - Create tabbed taxonomy meta

### Admin Pages
- `carbon_create_admin_page()` - Create admin pages with automatic hierarchy

## 📱 Responsive Design System

### Mobile-First Approach
1. **LG.css** - Desktop base styles (no media query)
2. **Smaller breakpoints** - Mobile-first overrides
3. **Larger breakpoints** - Desktop enhancements

### Example Compilation
```css
/* LG.css base */
.carbon-block--hero-section { font-size: 3.5rem; }

/* SM.css auto-wrapped */
@media (min-width: 576px) and (max-width: 767.98px) {
    .carbon-block--hero-section { font-size: 2rem; }
}
```

## 🗂️ File-Based Routing Examples

### Blocks
```
src/blocks/Heroes/hero-section/
├── block.php           # Auto-registers as "Hero Section" in "Heroes" category
├── block.twig         # Template with responsive CSS/JS injection
├── components/        # Reusable components
├── scripts/          # Auto-compiled JavaScript  
└── styles/           # Responsive CSS (XS, SM, MD, LG, XL, 2XL)
```

### Post Types
```
src/post-types/villas/
├── config.php                    # Registers 'villas' post type
└── field-groups/
    ├── villa-details.php         # Tabbed meta box: Basic Info, Location, Amenities
    └── villa-media.php           # Media gallery and files
```

### Taxonomies
```
src/taxonomy/villas, posts/       # Multi-post type assignment
└── amenities/
    ├── config.php               # Registers 'amenities' for villas AND posts
    └── field-groups/
        └── details.php          # Icon, color, description, category
```

### Admin Pages
```
src/admin-pages/
└── example/
    ├── page.php                 # Main settings page
    └── settings/
        └── page.php             # Sub-page under main page
```

## 🔄 Development Workflow

### Creating New Components

1. **Create directory structure** following the patterns above
2. **Copy reusable templates** (block.php files are completely reusable)
3. **Add responsive styles** in appropriate breakpoint files
4. **Define fields** using Carbon Fields helper functions
5. **Build templates** with Twig and component architecture

### Best Practices

1. **File Naming**: Use kebab-case for directories and files
2. **CSS Classes**: Follow BEM methodology (`carbon-block--{name}__element`)
3. **Field Prefixes**: Prefix fields with component name (`villa_name`, `amenity_icon`)
4. **Component Reuse**: Extract common elements to `components/` directories
5. **Documentation**: Comment complex configurations and field purposes

## 🎁 Framework Benefits

- **🚀 Zero Configuration** - File structure defines functionality
- **📱 Responsive by Default** - Built-in breakpoint system
- **🧩 Component Architecture** - Reusable, maintainable code
- **🔧 Developer Experience** - No manual registration or compilation
- **📈 Scalability** - Easy to add new features without touching existing code
- **🎯 Consistency** - Standardized patterns across all components
- **🛠️ Maintainability** - Self-contained, logically organized structure

## 🚀 Getting Started

1. **Read the documentation** for each component type you need
2. **Copy examples** from existing implementations
3. **Follow the file structure** patterns shown in the READMEs
4. **Leverage helper functions** for rapid development
5. **Build components** using the responsive CSS system

The Carbon Blocks Framework provides everything you need for modern WordPress development with file-based routing, responsive design, and component architecture built-in! 🎯
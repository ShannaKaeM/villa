# Carbon Blocks Directory

This directory contains the file-based routing system for Gutenberg blocks built with Carbon Fields, featuring responsive CSS compilation, JavaScript bundling, and component support.

## Directory Structure

```
blocks/
├── README.md
└── {Category}/                      # Block category
    └── {block-name}/
        ├── block.php                # Block registration & fields
        ├── block.twig               # Twig template
        ├── components/              # Reusable components
        │   └── {component}.twig
        ├── scripts/                 # JavaScript files
        │   └── *.js
        └── styles/                  # Responsive CSS files
            ├── XS.css               # Extra small screens
            ├── SM.css               # Small screens
            ├── MD.css               # Medium screens
            ├── LG.css               # Large screens (base)
            ├── XL.css               # Extra large screens
            └── 2XL.css              # Extra extra large screens
```

## How The System Works

### 1. Auto-Discovery & Registration

The system automatically discovers and registers blocks by:
- Scanning `blocks/` directory for category directories
- Each category becomes a Gutenberg block category
- Loading `block.php` files within each block directory
- Registering blocks with Carbon Fields' Block API

### 2. Dynamic Block Registration (`block.php`)

Each block uses a **completely reusable** template that auto-detects its name and category:

```php
<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

// Get component name from parent directory
$component = basename(dirname(__FILE__));

// Get category from grandparent directory  
$category = basename(dirname(dirname(__FILE__)));

Block::make(__(ucwords(str_replace('-', ' ', $component))))
    ->add_fields([
        Field::make('text', 'title', __('Title')),
        // ... other fields
    ])
    ->set_category('carbon-blocks-' . $category)
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($component, $category) {
        carbon_blocks_render_gutenberg($category . '/' . $component, $fields, $attributes, $inner_blocks);
    });
```

**Key Features:**
- **Auto-naming**: Block title from directory name (`hero-section` → "Hero Section")
- **Auto-categorization**: Category from parent directory (`Heroes/hero-section` → "Heroes")
- **Dynamic rendering**: Passes `$category/$component` to renderer

### 3. Responsive CSS Compilation System

The system compiles responsive CSS automatically with **breakpoint-based media query wrapping**:

#### Breakpoint Configuration (`config/breakpoints.php`)
```php
function carbon_blocks_get_breakpoints() {
    return [
        'XS' => '@media (max-width: 575.98px)',
        'SM' => '@media (min-width: 576px) and (max-width: 767.98px)', 
        'MD' => '@media (min-width: 768px) and (max-width: 991.98px)',
        'LG' => '', // Base breakpoint - no media query
        'XL' => '@media (min-width: 1200px) and (max-width: 1399.98px)',
        '2XL' => '@media (min-width: 1400px)'
    ];
}
```

#### CSS Compilation Process (`compile_helpers.php`)

**File Structure:**
```
styles/
├── LG.css      # Base styles (no media query wrapper)
├── SM.css      # Small screen overrides  
└── XS.css      # Mobile overrides
```

**Compilation Result:**
```css
/* LG.css content (base) */
.carbon-block--hero-section {
    min-height: 100vh;
    font-size: 3.5rem;
}

/* SM.css wrapped in media query */
@media (min-width: 576px) and (max-width: 767.98px) {
    .carbon-block--hero-section {
        min-height: 70vh;
        font-size: 2rem;
    }
}

/* XS.css wrapped in media query */
@media (max-width: 575.98px) {
    .carbon-block--hero-section {
        min-height: 60vh;
        font-size: 1.5rem;
    }
}
```

**Key Features:**
- **LG.css is the base** - no media query wrapper
- **Other breakpoints auto-wrapped** with appropriate media queries
- **Mobile-first approach** supported
- **Only existing files compiled** - missing breakpoints ignored

### 4. JavaScript Compilation

All JavaScript files in the `scripts/` directory are automatically compiled and concatenated:

```javascript
// scripts/example.js
document.addEventListener('DOMContentLoaded', function() {
    const heroSections = document.querySelectorAll('.carbon-block--hero-section');
    
    heroSections.forEach(function(hero) {
        // Block-specific JavaScript
        hero.style.opacity = '0';
        hero.style.transition = 'opacity 1s ease-in-out';
        
        setTimeout(function() {
            hero.style.opacity = '1';
        }, 100);
    });
});
```

**Features:**
- **Multiple JS files supported** - all `.js` files concatenated
- **Block-scoped** - use CSS selectors to target specific blocks
- **Inline injection** - JavaScript injected directly into the block output

### 5. Twig Template System (`block.twig`)

Templates use **block context** provided by the compilation system:

```twig
<div class="{{ block.css_class }} {{ block.css_class }}--{{ fields.text_alignment }}" 
     {% if fields.background_image %}style="background-image: url('{{ Image(fields.background_image).src }}')"{% endif %}>
    
    {% if block.styles %}
        <style>{{ block.styles|raw }}</style>
    {% endif %}
    
    <div class="{{ block.css_class }}__container">
        <div class="{{ block.css_class }}__content">
            {% if fields.title %}
                <h1 class="{{ block.css_class }}__title">{{ fields.title }}</h1>
            {% endif %}
            
            {% if fields.button_text and fields.button_url %}
                {{ include('@blocks/hero-section/components/button.twig', {
                    text: fields.button_text,
                    url: fields.button_url,
                    block_name: block.name
                }) }}
            {% endif %}
        </div>
    </div>
    
    {% if block.scripts %}
        <script>{{ block.scripts|raw }}</script>
    {% endif %}
</div>
```

**Available Context:**
- **`block.name`** - Component name (e.g., "hero-section")
- **`block.css_class`** - Base CSS class (e.g., "carbon-block carbon-block--hero-section")
- **`block.styles`** - Compiled responsive CSS
- **`block.scripts`** - Compiled JavaScript
- **`fields`** - Carbon Fields data
- **`attributes`** - Gutenberg block attributes
- **`inner_blocks`** - Inner block content

### 6. Component System

Reusable components are stored in the `components/` directory:

```twig
<!-- components/button.twig -->
<a href="{{ url }}" class="carbon-block--{{ block_name }}__button">
    {{ text }}
</a>
```

**Usage in Templates:**
```twig
{{ include('@blocks/hero-section/components/button.twig', {
    text: fields.button_text,
    url: fields.button_url,
    block_name: block.name
}) }}
```

**Features:**
- **Namespaced paths** - `@blocks/category/block-name/components/`
- **Context passing** - Pass variables to components
- **Reusability** - Share components across blocks

## Block Context Compilation

The `carbon_blocks_create_context()` function creates the context available in Twig templates:

```php
function carbon_blocks_create_context($block_name, $fields = [], $additional_context = []) {
    // Extract component name for CSS classes (remove category/)
    $component_name = strpos($block_name, '/') !== false 
        ? basename($block_name) 
        : $block_name;
    
    $context = [
        'block' => [
            'name'      => $component_name,                              // "hero-section"
            'css_class' => 'carbon-block carbon-block--' . $component_name, // "carbon-block carbon-block--hero-section"
            'styles'    => carbon_blocks_compile_styles($block_name),    // Compiled responsive CSS
            'scripts'   => carbon_blocks_compile_scripts($block_name),   // Compiled JavaScript
        ],
        'fields' => $fields,  // Carbon Fields data
    ];
    
    return array_merge($context, $additional_context);
}
```

## CSS Class Naming Convention

The system uses **BEM methodology** with consistent naming:

```css
/* Block */
.carbon-block--hero-section { }

/* Elements */
.carbon-block--hero-section__container { }
.carbon-block--hero-section__content { }
.carbon-block--hero-section__title { }
.carbon-block--hero-section__button { }

/* Modifiers */
.carbon-block--hero-section--left { }
.carbon-block--hero-section--center { }
.carbon-block--hero-section--right { }
```

## Example: Hero Section Block

### Directory Structure
```
blocks/
└── Heroes/                          # Category
    └── hero-section/               # Block
        ├── block.php               # Registration
        ├── block.twig              # Template
        ├── components/
        │   └── button.twig         # Button component
        ├── scripts/
        │   └── example.js          # Animations
        └── styles/
            ├── LG.css              # Desktop base
            ├── MD.css              # Tablet
            ├── SM.css              # Mobile landscape
            └── XS.css              # Mobile portrait
```

### CSS Compilation Example

**LG.css (Base - no wrapper):**
```css
.carbon-block--hero-section {
    min-height: 100vh;
    font-size: 3.5rem;
}
```

**SM.css (Auto-wrapped):**
```css
.carbon-block--hero-section {
    min-height: 70vh;
    font-size: 2rem;
}
```

**Compiled Output:**
```css
.carbon-block--hero-section {
    min-height: 100vh;
    font-size: 3.5rem;
}

@media (min-width: 576px) and (max-width: 767.98px) {
    .carbon-block--hero-section {
        min-height: 70vh;
        font-size: 2rem;
    }
}
```

## Timber Path Configuration

The system registers category directories as Timber paths:

```php
// config/timber/paths.php
$paths['blocks'] = [
    '/src/blocks',           # Main blocks directory
    '/src/blocks/Heroes',    # Category directories auto-discovered
    '/src/blocks/Content',   # Each category becomes a searchable path
];
```

This enables the `@blocks/` namespace in Twig templates.

## Development Workflow

### Creating a New Block

1. **Create directory structure:**
   ```bash
   mkdir -p blocks/Content/testimonial/{components,scripts,styles}
   ```

2. **Copy reusable `block.php`** (auto-detects name/category)

3. **Create `block.twig` template** with block context

4. **Add responsive styles:**
   - `LG.css` - Desktop base styles
   - `MD.css`, `SM.css`, `XS.css` - Responsive overrides

5. **Add JavaScript** in `scripts/` directory

6. **Create components** in `components/` directory

### Best Practices

1. **Mobile-First CSS**: Start with mobile styles, enhance for larger screens
2. **BEM Naming**: Use consistent `.carbon-block--{name}__element` convention
3. **Component Reuse**: Extract common elements to `components/`
4. **Scoped JavaScript**: Use block-specific CSS selectors
5. **Semantic HTML**: Use proper heading hierarchy and semantic elements

## File-Based Routing Benefits

- **Zero Configuration**: Blocks auto-register based on directory structure
- **Consistent Naming**: Automatic CSS classes and block names
- **Responsive by Default**: Built-in breakpoint system
- **Component Architecture**: Reusable Twig components
- **Developer Experience**: No manual registration or compilation setup
- **Scalability**: Easy to add new blocks and categories
- **Maintainability**: Self-contained block directories
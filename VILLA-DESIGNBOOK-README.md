# Villa DesignBook System Documentation

## 🏗️ Architecture Overview

The Villa DesignBook system follows atomic design principles with a clean primitive-based composition architecture:

```
templates/
├── components/primitives/     # Pure design token utilities
│   ├── color.twig            # Color utilities
│   ├── typography.twig       # Typography utilities  
│   ├── spacing.twig          # Spacing utilities
│   └── layout.twig           # Layout utilities
├── elements/                 # Reusable components with variants
│   ├── button-book.twig      # Unified button element + showcase
│   └── text-book.twig        # Text element with semantic types
└── components/               # Complex compositions
    └── card-book.twig        # Card component using elements + primitives
```

## 🎨 Design Token Flow

```
DesignBook Admin → theme.json → Primitives → Elements → Components
```

1. **DesignBook Admin Pages** (ColorBook, TextBook, ButtonBook) update design tokens
2. **theme.json** stores design tokens as CSS custom properties
3. **Primitives** consume design tokens via CSS custom properties
4. **Elements & Components** use primitives for consistent styling

## 📁 File Structure & Locations

### Core Files

| File | Location | Purpose |
|------|----------|---------|
| **Admin Plugin** | `app/public/wp-content/mu-plugins/villa-design-book.php` | Main DesignBook admin interface |
| **Admin Styles** | `app/public/wp-content/themes/miGV/assets/css/villa-design-book.css` | DesignBook admin UI styling |
| **Admin Scripts** | `app/public/wp-content/themes/miGV/assets/js/villa-design-book.js` | DesignBook admin functionality |
| **Design Tokens** | `app/public/wp-content/themes/miGV/theme.json` | Single source of truth for design tokens |

### Template Files

| Component Type | Location | Files |
|----------------|----------|-------|
| **Primitives** | `app/public/wp-content/themes/miGV/templates/components/primitives/` | `color.twig`, `typography.twig`, `spacing.twig`, `layout.twig` |
| **Elements** | `app/public/wp-content/themes/miGV/templates/elements/` | `button-book.twig`, `text-book.twig` |
| **Components** | `app/public/wp-content/themes/miGV/templates/components/` | `card-book.twig` |

## 🔧 How to Update DesignBook Admin Pages

### 1. Adding a New Book Page

**Step 1: Register Admin Menu**
```php
// In villa-design-book.php, add to register_admin_menu()
add_submenu_page(
    'villa-design-book',
    'NewBook',
    '📚 NewBook',
    'manage_options',
    'villa-new-book',
    array($this, 'render_new_book')
);
```

**Step 2: Create Render Function**
```php
public function render_new_book() {
    ?>
    <div class="wrap villa-new-book">
        <h1>📚 NewBook</h1>
        <p class="description">Description of your new book.</p>
        
        <!-- Your admin interface here -->
        
    </div>
    <?php
}
```

**Step 3: Add Dashboard Card**
```php
// In render_main_page(), add to design-book-grid
<div class="design-book-card" onclick="location.href='<?php echo admin_url('admin.php?page=villa-new-book'); ?>'">
    <span class="card-icon">📚</span>
    <h3>NewBook</h3>
    <p>Description of your new book functionality.</p>
</div>
```

### 2. Adding AJAX Handlers

**Step 1: Register AJAX Actions**
```php
// In __construct()
add_action('wp_ajax_save_new_book_data', array($this, 'save_new_book_data'));
add_action('wp_ajax_reset_new_book_data', array($this, 'reset_new_book_data'));
```

**Step 2: Create Handler Functions**
```php
public function save_new_book_data() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'villa_design_book_nonce')) {
        wp_die('Security check failed');
    }
    
    // Check capabilities
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions');
    }
    
    // Process and save data
    $data = sanitize_text_field($_POST['data']);
    update_option('villa_new_book_data', $data);
    
    wp_send_json_success('Data saved successfully');
}
```

### 3. Adding JavaScript Functionality

**Step 1: Add to villa-design-book.js**
```javascript
// NewBook Module
const NewBook = {
    init() {
        this.bindEvents();
    },
    
    bindEvents() {
        // Bind your event handlers
        document.addEventListener('change', (e) => {
            if (e.target.matches('.new-book-control')) {
                this.updatePreview();
            }
        });
    },
    
    updatePreview() {
        // Update live preview logic
    },
    
    save() {
        // AJAX save logic
    }
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.villa-new-book')) {
        NewBook.init();
    }
});
```

### 4. Adding CSS Styles

**Add to villa-design-book.css:**
```css
/* ===================================
   NEW BOOK STYLES
   ================================== */

.villa-new-book {
    /* Your styles here */
}

.new-book-controls {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.new-book-preview {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}
```

## 🎨 How to Update Twig Templates

### 1. Creating New Primitives

**Location:** `templates/components/primitives/`

**Template Structure:**
```twig
{# new-primitive.twig #}
{% set element = element|default('div') %}
{% set property = property|default('') %}
{% set value = value|default('') %}

{% if inline %}
    <{{ element }}{% if attributes %} {{ attributes }}{% endif %} style="{{ property }}: var(--wp--custom--{{ value }});">
        {{ content }}
    </{{ element }}>
{% else %}
    {% set css_class = 'primitive-' ~ property|replace({'_': '-'}) ~ '-' ~ value %}
    <{{ element }} class="{{ css_class }}{% if class %} {{ class }}{% endif %}"{% if attributes %} {{ attributes }}{% endif %}>
        {{ content }}
    </{{ element }}>
{% endif %}
```

### 2. Creating New Elements

**Location:** `templates/elements/`

**Template Structure:**
```twig
{# new-element.twig #}
{% set element = element|default('div') %}
{% set variant = variant|default('default') %}
{% set size = size|default('medium') %}

<{{ element }} class="villa-new-element villa-new-element--{{ variant }} villa-new-element--{{ size }}{% if class %} {{ class }}{% endif %}"{% if attributes %} {{ attributes }}{% endif %}>
    
    {# Use primitives for styling #}
    {% include 'components/primitives/typography.twig' with {
        'size': size,
        'weight': weight|default('400'),
        'inline': true
    } %}
    
    {% include 'components/primitives/color.twig' with {
        'color': color|default('primary'),
        'property': 'background-color',
        'inline': true
    } %}
    
    {{ content }}
    
</{{ element }}>
```

### 3. Creating New Components

**Location:** `templates/components/`

**Template Structure:**
```twig
{# new-component.twig #}
<div class="villa-new-component{% if class %} {{ class }}{% endif %}"{% if attributes %} {{ attributes }}{% endif %}>
    
    {# Use elements and primitives #}
    {% include 'elements/text-book.twig' with {
        'type': 'title',
        'content': title
    } %}
    
    {% include 'elements/button-book.twig' with {
        'variant': 'primary',
        'content': 'Learn More'
    } %}
    
</div>
```

## 🎯 Design Token Integration

### 1. Adding New Design Tokens

**Step 1: Update theme.json**
```json
{
  "settings": {
    "custom": {
      "newCategory": {
        "newToken": "value",
        "anotherToken": "anotherValue"
      }
    }
  }
}
```

**Step 2: Use in Primitives**
```twig
{# In primitive template #}
style="{{ property }}: var(--wp--custom--new-category--new-token);"
```

**Step 3: Update Admin Interface**
```php
// In admin page render function
<input type="text" id="new-token" data-token="newCategory.newToken" value="<?php echo $current_value; ?>">
```

### 2. CSS Custom Property Naming Convention

| Token Type | CSS Custom Property | Example |
|------------|-------------------|---------|
| **Colors** | `--wp--preset--color--{name}` | `--wp--preset--color--primary` |
| **Font Sizes** | `--wp--preset--font-size--{size}` | `--wp--preset--font-size--large` |
| **Spacing** | `--wp--custom--layout--spacing--{size}` | `--wp--custom--layout--spacing--lg` |
| **Border Radius** | `--wp--custom--layout--border-radius--{size}` | `--wp--custom--layout--border-radius--md` |
| **Shadows** | `--wp--custom--layout--shadows--{intensity}` | `--wp--custom--layout--shadows--lg` |

## 🔄 Update Workflow

### 1. When Adding New Features

1. **Update theme.json** with new design tokens
2. **Create/Update primitives** to use new tokens
3. **Update elements** to use new primitives
4. **Update admin interface** to control new tokens
5. **Add CSS styles** for admin interface
6. **Add JavaScript** for live preview and AJAX
7. **Test integration** end-to-end

### 2. When Modifying Existing Components

1. **Identify dependencies** (what uses this component?)
2. **Update primitive/element/component** template
3. **Update admin interface** if needed
4. **Test all dependent components**
5. **Verify design token integration**

## 🧪 Testing Checklist

### Admin Interface Testing
- [ ] All controls update live preview
- [ ] Save functionality works via AJAX
- [ ] Reset functionality restores defaults
- [ ] Notifications show success/error states
- [ ] Responsive design works on mobile

### Template Testing
- [ ] Primitives use correct CSS custom properties
- [ ] Elements compose primitives correctly
- [ ] Components use elements and primitives
- [ ] Design token changes propagate to frontend
- [ ] All variants render correctly

### Integration Testing
- [ ] DesignBook changes update theme.json
- [ ] theme.json changes reflect in templates
- [ ] Templates render with correct styling
- [ ] No console errors or PHP warnings
- [ ] Performance is acceptable

## 🚀 Best Practices

### 1. Naming Conventions
- **Primitives**: `{property}.twig` (e.g., `color.twig`, `spacing.twig`)
- **Elements**: `{component}-book.twig` (e.g., `button-book.twig`)
- **Components**: `{component}-book.twig` (e.g., `card-book.twig`)
- **CSS Classes**: `villa-{component}--{variant}--{size}`

### 2. Code Organization
- Keep primitives pure (no complex logic)
- Elements should use primitives for styling
- Components should compose elements and primitives
- Admin pages should follow consistent patterns

### 3. Performance
- Minimize inline styles (prefer CSS classes)
- Use CSS custom properties for design tokens
- Optimize AJAX requests (debounce rapid changes)
- Cache design token values when possible

### 4. Maintainability
- Document all custom design tokens
- Use semantic naming for variants and sizes
- Keep admin interface consistent across books
- Write clear, commented code

## 🔧 Troubleshooting

### Common Issues

**1. Design tokens not updating**
- Check theme.json syntax
- Verify CSS custom property names
- Clear any caching
- Check file permissions

**2. Admin interface not working**
- Verify AJAX handlers are registered
- Check JavaScript console for errors
- Ensure nonce verification is correct
- Verify user capabilities

**3. Templates not rendering**
- Check Twig syntax
- Verify file paths are correct
- Ensure Timber is properly configured
- Check for PHP errors

**4. Styles not applying**
- Verify CSS file is enqueued
- Check CSS selector specificity
- Ensure design tokens are defined
- Clear browser cache

### Debug Tools

**1. Check Design Tokens**
```javascript
// In browser console
getComputedStyle(document.documentElement).getPropertyValue('--wp--preset--color--primary')
```

**2. Verify AJAX Endpoints**
```javascript
// Check if AJAX URL is correct
console.log(villa_design_book_ajax.ajax_url);
```

**3. Test Template Rendering**
```php
// In WordPress debug mode
error_log(print_r($template_data, true));
```

## 📚 Additional Resources

- [WordPress theme.json Documentation](https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-json/)
- [Timber/Twig Documentation](https://timber.github.io/docs/)
- [Atomic Design Methodology](https://atomicdesign.bradfrost.com/)
- [CSS Custom Properties](https://developer.mozilla.org/en-US/docs/Web/CSS/Using_CSS_custom_properties)

---

**Last Updated:** May 31, 2025  
**Version:** 2.0  
**Author:** Villa DesignBook System

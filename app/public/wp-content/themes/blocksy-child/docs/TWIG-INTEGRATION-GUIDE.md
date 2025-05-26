# Twig Integration Guide

This guide explains how to use Twig templating in the Villa Community WordPress theme.

## Overview

We've integrated Twig 3.0 into our WordPress theme to provide:
- Clean, maintainable templates
- Reusable components
- Better separation of logic and presentation
- Integration with our semantic color system

## Installation

Twig is already installed via Composer. If you need to reinstall:

```bash
cd wp-content/themes/blocksy-child
composer install
```

## Basic Usage

### In PHP Files

```php
use MiAgency\twig;

// Render a template
$html = twig()->render('template-name.twig', [
    'title' => 'Hello World',
    'content' => 'This is Twig content'
]);

// Display a template directly
twig()->display('template-name.twig', $data);
```

### In Blocks

```php
use Carbon_Fields\Block;
use MiAgency\twig;

Block::make('My Block')
    ->set_render_callback(function ($fields) {
        twig()->display('blocks/my-block/template.twig', [
            'fields' => $fields
        ]);
    });
```

## Template Locations

Templates are automatically loaded from these directories:
- `/templates/` - Main templates
- `/blocks/` - Block templates
- `/partials/` - Partial templates

## Available Functions

### WordPress Functions
All common WordPress functions are available:
- `wp_head()`, `wp_footer()`
- `get_permalink()`, `home_url()`, `site_url()`
- `esc_html()`, `esc_attr()`, `esc_url()`
- `__()`, `_e()` for translations
- And many more...

### Theme Functions

#### Color Functions
```twig
{# Semantic colors #}
{{ semantic_color('primary') }}
{{ semantic_color('text-primary') }}
{{ semantic_color('bg-secondary') }}

{# Theme colors #}
{{ theme_color('primary-500') }}

{# Blocksy colors #}
{{ blocksy_color('1') }}  {# Returns var(--paletteColor1) #}
```

#### Spacing Functions
```twig
{{ theme_spacing('20') }}  {# Returns var(--wp--preset--spacing--20) #}
{{ theme_spacing('40') }}
```

#### Component Function
```twig
{{ component('button', {
    text: 'Click Me',
    variant: 'primary',
    size: 'medium'
}) }}
```

## Components

### Button Component

```twig
{{ component('button', {
    text: 'Button Text',
    url: '#',
    variant: 'primary',  {# primary, secondary, neutral #}
    size: 'medium',      {# small, medium, large #}
    icon: '',            {# optional icon #}
    class: ''            {# additional CSS classes #}
}) }}
```

### Card Component

```twig
{{ component('card', {
    title: 'Card Title',
    content: 'Card content here',
    image_id: 123,       {# WordPress attachment ID #}
    image_url: '',       {# Or direct URL #}
    link: '#',           {# Optional link #}
    variant: 'default',  {# default, elevated, outlined #}
    class: ''            {# additional CSS classes #}
}) }}
```

## Creating Templates

### Basic Template Structure

```twig
{# templates/my-template.twig #}

<div class="my-template">
    <h1>{{ title|esc_html }}</h1>
    
    {% if content %}
        <div class="content">
            {{ content|wp_kses_post }}
        </div>
    {% endif %}
    
    {% for item in items %}
        <div class="item">
            {{ item.name }}
        </div>
    {% endfor %}
</div>
```

### Using Global Data

All templates have access to global data:

```twig
{# Site information #}
{{ site.name }}
{{ site.description }}
{{ site.url }}

{# Theme information #}
{{ theme.url }}
{{ theme.path }}

{# Conditional checks #}
{% if is.home %}
    {# Homepage content #}
{% endif %}

{% if is.single %}
    {# Single post content #}
{% endif %}

{# Current user #}
{{ user.display_name }}
```

## Shortcodes

We've created shortcodes for easy component usage:

```
[twig_button text="Click Me" variant="primary" size="large" url="/contact"]

[twig_card title="My Card" variant="elevated"]
This is the card content.
[/twig_card]
```

## Best Practices

1. **Escape Output**: Always escape user data
   ```twig
   {{ title|esc_html }}
   {{ url|esc_url }}
   {{ content|wp_kses_post }}
   ```

2. **Use Components**: Create reusable components for common UI elements

3. **Semantic Colors**: Use our semantic color system
   ```twig
   style="color: {{ semantic_color('text-primary') }}"
   ```

4. **Theme Spacing**: Use theme spacing for consistency
   ```twig
   style="padding: {{ theme_spacing('30') }}"
   ```

5. **Comments**: Document your templates
   ```twig
   {#
       Component: Feature Card
       Parameters:
       - title: Card title
       - content: Card content
   #}
   ```

## Debugging

When `WP_DEBUG` is enabled:
- Template errors show detailed messages
- The `dump()` function is available for debugging
- Template cache is automatically refreshed

```twig
{# Debug variable #}
{{ dump(my_variable) }}
```

## Example: Custom Block with Twig

```php
// blocks/feature-section/index.php
use Carbon_Fields\Block;
use Carbon_Fields\Field;
use MiAgency\twig;

Block::make('Feature Section')
    ->add_fields([
        Field::make('text', 'title', 'Title'),
        Field::make('complex', 'features', 'Features')
            ->add_fields([
                Field::make('text', 'title', 'Feature Title'),
                Field::make('textarea', 'description', 'Description'),
                Field::make('icon', 'icon', 'Icon')
            ])
    ])
    ->set_render_callback(function ($fields) {
        twig()->display('blocks/feature-section.twig', [
            'title' => $fields['title'],
            'features' => $fields['features'] ?? []
        ]);
    });
```

```twig
{# blocks/feature-section.twig #}
<section class="feature-section" style="padding: {{ theme_spacing('50') }};">
    {% if title %}
        <h2 style="text-align: center; color: {{ semantic_color('text-primary') }};">
            {{ title|esc_html }}
        </h2>
    {% endif %}
    
    <div class="features-grid" style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: {{ theme_spacing('30') }};
        margin-top: {{ theme_spacing('40') }};
    ">
        {% for feature in features %}
            {{ component('card', {
                title: feature.title,
                content: feature.description,
                variant: 'elevated'
            }) }}
        {% endfor %}
    </div>
</section>
```

## Migration from PHP Templates

To migrate existing PHP templates to Twig:

1. Move HTML to `.twig` files
2. Replace PHP echo with Twig syntax
3. Convert loops and conditionals
4. Use Twig functions instead of PHP

### Before (PHP):
```php
<h1><?php echo esc_html($title); ?></h1>
<?php if ($show_content): ?>
    <div><?php echo wp_kses_post($content); ?></div>
<?php endif; ?>
```

### After (Twig):
```twig
<h1>{{ title|esc_html }}</h1>
{% if show_content %}
    <div>{{ content|wp_kses_post }}</div>
{% endif %}
```

## Resources

- [Twig Documentation](https://twig.symfony.com/doc/3.x/)
- [WordPress Escaping Functions](https://developer.wordpress.org/themes/theme-security/data-sanitization-escaping/)
- Theme components in `/templates/components/`

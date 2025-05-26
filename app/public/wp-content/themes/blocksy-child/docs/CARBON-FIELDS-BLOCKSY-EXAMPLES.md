# Carbon Fields + Blocksy Integration Examples

This document provides practical examples of creating Carbon Fields blocks that integrate with Blocksy's theme system.

## Table of Contents

1. [Basic Examples](#basic-examples)
2. [Advanced Examples](#advanced-examples)
3. [CSS Template Patterns](#css-template-patterns)
4. [Common Patterns](#common-patterns)

## Basic Examples

### Simple Button Block

```php
<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make('Theme Button')
    ->add_fields([
        Field::make('text', 'button_text', 'Button Text')
            ->set_default_value('Click Me'),
        
        Field::make('text', 'button_url', 'Button URL')
            ->set_default_value('#'),
        
        Field::make('select', 'button_style', 'Button Style')
            ->set_options([
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'outline' => 'Outline'
            ]),
        
        Field::make('select', 'button_size', 'Button Size')
            ->set_options(get_theme_font_sizes_for_blocks()),
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        $button_class = 'theme-button theme-button--' . $fields['button_style'];
        $font_size = get_theme_font_size_value($fields['button_size']);
        
        ?>
        <style>
            .theme-button--<?php echo esc_attr($fields['button_style']); ?> {
                font-size: <?php echo esc_attr($font_size); ?>;
            }
        </style>
        <a href="<?php echo esc_url($fields['button_url']); ?>" 
           class="<?php echo esc_attr($button_class); ?>">
            <?php echo esc_html($fields['button_text']); ?>
        </a>
        <?php
    });
```

### Card Block with Theme Colors

```php
Block::make('Theme Card')
    ->add_fields([
        Field::make('text', 'title', 'Card Title'),
        Field::make('textarea', 'content', 'Card Content'),
        Field::make('select', 'bg_color', 'Background Color')
            ->set_options(get_theme_colors_for_blocks()),
        Field::make('select', 'text_color', 'Text Color')
            ->set_options(get_theme_colors_for_blocks()),
        Field::make('select', 'padding', 'Padding')
            ->set_options(get_theme_spacing_sizes_for_blocks()),
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        $card_id = 'card-' . uniqid();
        
        $css_template = '
        #{{ card_id }} {
            {% if fields.bg_color %}
            background-color: {{ theme.colors[fields.bg_color] }};
            {% endif %}
            {% if fields.text_color %}
            color: {{ theme.colors[fields.text_color] }};
            {% endif %}
            {% if fields.padding %}
            padding: {{ theme.spacing[fields.padding] }};
            {% endif %}
        }';
        
        $data = [
            'card_id' => $card_id,
            'fields' => $fields,
            'theme' => [
                'colors' => array_map('get_theme_color_value', array_keys(get_theme_colors_for_blocks())),
                'spacing' => array_map('get_theme_spacing_value', array_keys(get_theme_spacing_sizes_for_blocks()))
            ]
        ];
        
        $compiled_css = compile_css_with_theme_values($css_template, $data);
        
        ?>
        <style><?php echo $compiled_css; ?></style>
        <div id="<?php echo esc_attr($card_id); ?>" class="theme-card">
            <h3><?php echo esc_html($fields['title']); ?></h3>
            <p><?php echo esc_html($fields['content']); ?></p>
        </div>
        <?php
    });
```

## Advanced Examples

### Hero Section with Full Theme Integration

```php
Block::make('Hero Section')
    ->add_fields([
        // Content Fields
        Field::make('text', 'heading', 'Heading'),
        Field::make('textarea', 'subheading', 'Subheading'),
        Field::make('text', 'button_text', 'Button Text'),
        Field::make('text', 'button_url', 'Button URL'),
        
        // Style Fields
        Field::make('select', 'bg_color', 'Background Color')
            ->set_options(get_theme_colors_for_blocks()),
        Field::make('select', 'text_color', 'Text Color')
            ->set_options(get_theme_colors_for_blocks()),
        Field::make('select', 'heading_size', 'Heading Size')
            ->set_options(get_theme_font_sizes_for_blocks()),
        Field::make('select', 'subheading_size', 'Subheading Size')
            ->set_options(get_theme_font_sizes_for_blocks()),
        Field::make('select', 'padding_top', 'Padding Top')
            ->set_options(get_theme_spacing_sizes_for_blocks()),
        Field::make('select', 'padding_bottom', 'Padding Bottom')
            ->set_options(get_theme_spacing_sizes_for_blocks()),
        
        // Layout Fields
        Field::make('select', 'width', 'Section Width')
            ->set_options([
                'content' => 'Content Width',
                'wide' => 'Wide',
                'full' => 'Full Width'
            ]),
        Field::make('select', 'text_align', 'Text Alignment')
            ->set_options([
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right'
            ]),
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        $hero_id = 'hero-' . uniqid();
        
        // Build theme data array
        $theme_data = [
            'colors' => [],
            'fontSizes' => [],
            'spacing' => []
        ];
        
        // Populate theme values
        foreach (get_theme_colors_for_blocks() as $slug => $label) {
            $theme_data['colors'][$slug] = get_theme_color_value($slug);
        }
        foreach (get_theme_font_sizes_for_blocks() as $slug => $label) {
            $theme_data['fontSizes'][$slug] = get_theme_font_size_value($slug);
        }
        foreach (get_theme_spacing_sizes_for_blocks() as $slug => $label) {
            $theme_data['spacing'][$slug] = get_theme_spacing_value($slug);
        }
        
        // CSS Template
        $css_template = '
        #{{ hero_id }} {
            {% if fields.bg_color %}
            background-color: {{ theme.colors[fields.bg_color] }};
            {% endif %}
            {% if fields.text_color %}
            color: {{ theme.colors[fields.text_color] }};
            {% endif %}
            {% if fields.padding_top %}
            padding-top: {{ theme.spacing[fields.padding_top] }};
            {% endif %}
            {% if fields.padding_bottom %}
            padding-bottom: {{ theme.spacing[fields.padding_bottom] }};
            {% endif %}
            text-align: {{ fields.text_align }};
        }
        
        #{{ hero_id }} .hero-heading {
            {% if fields.heading_size %}
            font-size: {{ theme.fontSizes[fields.heading_size] }};
            {% endif %}
        }
        
        #{{ hero_id }} .hero-subheading {
            {% if fields.subheading_size %}
            font-size: {{ theme.fontSizes[fields.subheading_size] }};
            {% endif %}
        }
        
        {% if fields.width == "content" %}
        #{{ hero_id }} .hero-inner {
            max-width: var(--wp--style--global--content-size);
            margin: 0 auto;
        }
        {% elseif fields.width == "wide" %}
        #{{ hero_id }} .hero-inner {
            max-width: var(--wp--style--global--wide-size);
            margin: 0 auto;
        }
        {% endif %}';
        
        $compiled_css = compile_css_with_theme_values($css_template, [
            'hero_id' => $hero_id,
            'fields' => $fields,
            'theme' => $theme_data
        ]);
        
        ?>
        <style><?php echo $compiled_css; ?></style>
        <section id="<?php echo esc_attr($hero_id); ?>" class="hero-section">
            <div class="hero-inner">
                <?php if ($fields['heading']): ?>
                    <h1 class="hero-heading"><?php echo esc_html($fields['heading']); ?></h1>
                <?php endif; ?>
                
                <?php if ($fields['subheading']): ?>
                    <p class="hero-subheading"><?php echo esc_html($fields['subheading']); ?></p>
                <?php endif; ?>
                
                <?php if ($fields['button_text'] && $fields['button_url']): ?>
                    <a href="<?php echo esc_url($fields['button_url']); ?>" class="hero-button">
                        <?php echo esc_html($fields['button_text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </section>
        <?php
    });
```

## CSS Template Patterns

### Conditional Styling

```css
/* Basic conditional */
{% if fields.show_border %}
border: 1px solid {{ theme.colors[fields.border_color] }};
{% endif %}

/* Multiple conditions */
{% if fields.style == "outlined" %}
    background: transparent;
    border: 2px solid {{ theme.colors[fields.color] }};
    color: {{ theme.colors[fields.color] }};
{% elseif fields.style == "filled" %}
    background: {{ theme.colors[fields.color] }};
    color: white;
{% else %}
    background: transparent;
    color: {{ theme.colors[fields.color] }};
{% endif %}

/* Nested conditions */
{% if fields.enable_hover %}
    {% if fields.hover_style == "darken" %}
    &:hover {
        filter: brightness(0.9);
    }
    {% endif %}
{% endif %}
```

### Responsive Patterns

```css
/* Mobile-first approach */
.my-block {
    font-size: {{ theme.fontSizes.small }};
    padding: {{ theme.spacing.20 }};
}

@media (min-width: 768px) {
    .my-block {
        {% if fields.tablet_size %}
        font-size: {{ theme.fontSizes[fields.tablet_size] }};
        {% endif %}
        {% if fields.tablet_padding %}
        padding: {{ theme.spacing[fields.tablet_padding] }};
        {% endif %}
    }
}

@media (min-width: 1024px) {
    .my-block {
        {% if fields.desktop_size %}
        font-size: {{ theme.fontSizes[fields.desktop_size] }};
        {% endif %}
        {% if fields.desktop_padding %}
        padding: {{ theme.spacing[fields.desktop_padding] }};
        {% endif %}
    }
}
```

## Common Patterns

### Creating a Theme Value Map

```php
// Helper function to create theme data for CSS compilation
function get_theme_data_for_css() {
    $theme_data = [
        'colors' => [],
        'fontSizes' => [],
        'spacing' => []
    ];
    
    // Get all theme values
    $colors = get_theme_colors_for_blocks();
    $font_sizes = get_theme_font_sizes_for_blocks();
    $spacing = get_theme_spacing_sizes_for_blocks();
    
    // Map to actual values
    foreach ($colors as $slug => $label) {
        $theme_data['colors'][$slug] = get_theme_color_value($slug);
    }
    foreach ($font_sizes as $slug => $label) {
        $theme_data['fontSizes'][$slug] = get_theme_font_size_value($slug);
    }
    foreach ($spacing as $slug => $label) {
        $theme_data['spacing'][$slug] = get_theme_spacing_value($slug);
    }
    
    return $theme_data;
}
```

### Reusable Block Wrapper

```php
// Reusable function for block rendering
function render_theme_block($block_name, $fields, $content_callback) {
    $block_id = $block_name . '-' . uniqid();
    $theme_data = get_theme_data_for_css();
    
    // Get CSS template (could be from file or passed in)
    $css_template = get_block_css_template($block_name);
    
    // Compile CSS
    $compiled_css = compile_css_with_theme_values($css_template, [
        'block_id' => $block_id,
        'fields' => $fields,
        'theme' => $theme_data
    ]);
    
    // Output
    echo '<style>' . $compiled_css . '</style>';
    echo '<div id="' . esc_attr($block_id) . '" class="' . esc_attr($block_name) . '">';
    call_user_func($content_callback, $fields);
    echo '</div>';
}
```

### Field Groups for Consistency

```php
// Reusable field groups
function get_color_fields($prefix = '') {
    return [
        Field::make('select', $prefix . 'bg_color', 'Background Color')
            ->set_options(get_theme_colors_for_blocks()),
        Field::make('select', $prefix . 'text_color', 'Text Color')
            ->set_options(get_theme_colors_for_blocks()),
    ];
}

function get_typography_fields($prefix = '') {
    return [
        Field::make('select', $prefix . 'font_size', 'Font Size')
            ->set_options(get_theme_font_sizes_for_blocks()),
        Field::make('select', $prefix . 'font_weight', 'Font Weight')
            ->set_options([
                '300' => 'Light',
                '400' => 'Regular',
                '500' => 'Medium',
                '600' => 'Semi Bold',
                '700' => 'Bold'
            ]),
    ];
}

function get_spacing_fields($prefix = '') {
    return [
        Field::make('select', $prefix . 'padding', 'Padding')
            ->set_options(get_theme_spacing_sizes_for_blocks()),
        Field::make('select', $prefix . 'margin', 'Margin')
            ->set_options(get_theme_spacing_sizes_for_blocks()),
    ];
}

// Usage in block
Block::make('Styled Section')
    ->add_fields(array_merge(
        [
            Field::make('text', 'title', 'Section Title'),
            Field::make('rich_text', 'content', 'Section Content'),
        ],
        get_color_fields(),
        get_typography_fields(),
        get_spacing_fields()
    ))
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        // Render logic here
    });
```

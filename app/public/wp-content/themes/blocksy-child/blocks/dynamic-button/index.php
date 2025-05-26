<?php
/**
 * Dynamic Button Block - Proof of Concept
 * Demonstrates pulling options from theme.json
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make('Dynamic Button')
    ->add_fields([
        Field::make('text', 'button_text', 'Button Text')
            ->set_default_value('Click Me'),
        
        Field::make('text', 'button_url', 'Button URL')
            ->set_default_value('#'),
        
        Field::make('select', 'button_color', 'Button Color')
            ->set_options(get_theme_colors_for_blocks()),
        
        Field::make('select', 'text_color', 'Text Color')
            ->set_options(get_theme_colors_for_blocks()),
        
        Field::make('select', 'font_size', 'Font Size')
            ->set_options(get_theme_font_sizes_for_blocks()),
        
        Field::make('select', 'padding', 'Padding')
            ->set_options(get_theme_spacing_sizes_for_blocks()),
        
        Field::make('select', 'border_radius', 'Border Radius')
            ->set_options([
                'none' => 'None',
                'small' => 'Small (4px)',
                'medium' => 'Medium (8px)',
                'large' => 'Large (16px)',
                'full' => 'Full (9999px)'
            ])
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        // Generate unique ID for scoped styles
        $block_id = 'dynamic-button-' . uniqid();
        
        // Prepare data for Twig
        $block_data = [
            'block_id' => $block_id,
            'fields' => $fields
        ];
        
        // CSS template with Twig syntax
        $css_template = '
        #{{ block_id }} .dynamic-button {
            background-color: {{ color_var(fields.button_color) }};
            color: {{ color_var(fields.text_color) }};
            font-size: {{ font_size_var(fields.font_size) }};
            padding: {{ spacing_var(fields.padding) }};
            {% if fields.border_radius == "small" %}
            border-radius: 4px;
            {% elseif fields.border_radius == "medium" %}
            border-radius: 8px;
            {% elseif fields.border_radius == "large" %}
            border-radius: 16px;
            {% elseif fields.border_radius == "full" %}
            border-radius: 9999px;
            {% endif %}
            display: inline-block;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        #{{ block_id }} .dynamic-button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        ';
        
        // Compile CSS with dynamic values
        $compiled_css = compile_block_css($css_template, $block_data);
        
        ?>
        <div id="<?php echo esc_attr($block_id); ?>" class="dynamic-button-block">
            <style>
                <?php echo $compiled_css; ?>
            </style>
            <a href="<?php echo esc_url($fields['button_url']); ?>" class="dynamic-button">
                <?php echo esc_html($fields['button_text']); ?>
            </a>
        </div>
        <?php
    });

<?php
/**
 * Hero Dynamic Block
 * Demonstrates full integration with Blocksy theme.json
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make('Hero Dynamic')
    ->add_fields([
        // Content Fields
        Field::make('text', 'title', 'Title')
            ->set_default_value('Welcome to Our Site'),
        
        Field::make('textarea', 'subtitle', 'Subtitle')
            ->set_default_value('Discover amazing features powered by dynamic theme values'),
        
        Field::make('text', 'button_text', 'Button Text')
            ->set_default_value('Get Started'),
        
        Field::make('text', 'button_url', 'Button URL')
            ->set_default_value('#'),
        
        // Layout Options
        Field::make('select', 'width_option', 'Width Option')
            ->set_options([
                'wide' => 'Wide (1400px max)',
                'content' => 'Content Width (1200px)',
                'full' => 'Full Width'
            ])
            ->set_default_value('wide'),
        
        Field::make('select', 'alignment', 'Text Alignment')
            ->set_options([
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right'
            ])
            ->set_default_value('center'),
        
        // Dynamic Theme Options
        Field::make('select', 'bg_color', 'Background Color')
            ->set_options(get_theme_colors_for_blocks())
            ->set_default_value('primary'),
        
        Field::make('select', 'text_color', 'Text Color')
            ->set_options(get_theme_colors_for_blocks())
            ->set_default_value('base-lightest'),
        
        Field::make('select', 'button_bg_color', 'Button Background')
            ->set_options(get_theme_colors_for_blocks())
            ->set_default_value('secondary'),
        
        Field::make('select', 'button_text_color', 'Button Text Color')
            ->set_options(get_theme_colors_for_blocks())
            ->set_default_value('base-lightest'),
        
        // Typography
        Field::make('select', 'title_size', 'Title Size')
            ->set_options(get_theme_font_sizes_for_blocks())
            ->set_default_value('x-large'),
        
        Field::make('select', 'subtitle_size', 'Subtitle Size')
            ->set_options(get_theme_font_sizes_for_blocks())
            ->set_default_value('medium'),
        
        // Spacing
        Field::make('select', 'padding_top', 'Padding Top')
            ->set_options(get_theme_spacing_sizes_for_blocks())
            ->set_default_value('60'),
        
        Field::make('select', 'padding_bottom', 'Padding Bottom')
            ->set_options(get_theme_spacing_sizes_for_blocks())
            ->set_default_value('60'),
        
        // Border Radius
        Field::make('select', 'border_radius', 'Border Radius')
            ->set_options([
                'none' => 'None',
                'small' => 'Small',
                'medium' => 'Medium',
                'large' => 'Large'
            ])
            ->set_default_value('medium')
            ->set_conditional_logic([
                [
                    'field' => 'width_option',
                    'value' => 'full',
                    'compare' => '!='
                ]
            ])
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        $block_id = 'hero-' . uniqid();
        
        // Prepare data for CSS compilation
        $block_data = [
            'block_id' => $block_id,
            'fields' => $fields
        ];
        
        // CSS template with dynamic values
        $css_template = '
        #{{ block_id }} {
            background-color: {{ color_var(fields.bg_color) }};
            color: {{ color_var(fields.text_color) }};
            padding-top: {{ spacing_var(fields.padding_top) }};
            padding-bottom: {{ spacing_var(fields.padding_bottom) }};
            text-align: {{ fields.alignment }};
        }
        
        #{{ block_id }} .hero-inner {
            {% if fields.width_option == "wide" %}
            max-width: 1400px;
            {% elseif fields.width_option == "content" %}
            max-width: var(--wp--custom--layout--content-size, 1200px);
            {% elseif fields.width_option == "full" %}
            max-width: 100%;
            {% endif %}
            margin: 0 auto;
            padding: 0 var(--wp--preset--spacing--30);
        }
        
        {% if fields.width_option != "full" and fields.border_radius != "none" %}
        #{{ block_id }} {
            {% if fields.border_radius == "small" %}
            border-radius: var(--wp--custom--border-radius--small, 4px);
            {% elseif fields.border_radius == "medium" %}
            border-radius: var(--wp--custom--border-radius--medium, 8px);
            {% elseif fields.border_radius == "large" %}
            border-radius: var(--wp--custom--border-radius--large, 16px);
            {% endif %}
            overflow: hidden;
        }
        {% endif %}
        
        #{{ block_id }} .hero-title {
            font-size: {{ font_size_var(fields.title_size) }};
            font-weight: var(--wp--custom--font-weight--bold, 700);
            margin: 0 0 var(--wp--preset--spacing--20) 0;
            line-height: var(--wp--custom--line-height--tight, 1.2);
        }
        
        #{{ block_id }} .hero-subtitle {
            font-size: {{ font_size_var(fields.subtitle_size) }};
            margin: 0 0 var(--wp--preset--spacing--40) 0;
            opacity: 0.9;
        }
        
        #{{ block_id }} .hero-button {
            background-color: {{ color_var(fields.button_bg_color) }};
            color: {{ color_var(fields.button_text_color) }};
            padding: var(--wp--preset--spacing--20) var(--wp--preset--spacing--40);
            border-radius: var(--wp--custom--border-radius--medium, 8px);
            text-decoration: none;
            display: inline-block;
            font-weight: var(--wp--custom--font-weight--semibold, 600);
            transition: all 0.3s ease;
        }
        
        #{{ block_id }} .hero-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        @media (max-width: 768px) {
            #{{ block_id }} .hero-title {
                font-size: calc({{ font_size_var(fields.title_size) }} * 0.8);
            }
            
            #{{ block_id }} .hero-inner {
                padding: 0 var(--wp--preset--spacing--20);
            }
        }
        ';
        
        // Compile CSS
        $compiled_css = compile_block_css($css_template, $block_data);
        
        ?>
        <div id="<?php echo esc_attr($block_id); ?>" class="hero-dynamic-block">
            <style><?php echo $compiled_css; ?></style>
            
            <div class="hero-inner">
                <?php if (!empty($fields['title'])): ?>
                    <h1 class="hero-title"><?php echo esc_html($fields['title']); ?></h1>
                <?php endif; ?>
                
                <?php if (!empty($fields['subtitle'])): ?>
                    <p class="hero-subtitle"><?php echo esc_html($fields['subtitle']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($fields['button_text']) && !empty($fields['button_url'])): ?>
                    <a href="<?php echo esc_url($fields['button_url']); ?>" class="hero-button">
                        <?php echo esc_html($fields['button_text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    });

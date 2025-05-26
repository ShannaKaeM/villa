<?php
/**
 * Hero Block - Refactored with Enhanced Features
 * Includes width options, height options, overlay, and advanced typography controls
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

Block::make('Hero Section')
    ->add_fields([
        // Content Fields
        Field::make('text', 'title', 'Title')
            ->set_default_value('Sofas'),
        
        Field::make('text', 'subtitle', 'Subtitle')
            ->set_default_value(''),
        
        // Layout Options
        Field::make('select', 'width_option', 'Width Option')
            ->set_options([
                'wide' => 'Wide (1450px max)',
                'content' => 'Content Width (1200px)',
                'full' => 'Full Width'
            ])
            ->set_default_value('wide'),
        
        Field::make('select', 'height_option', 'Height Option')
            ->set_options([
                'small' => 'Small (250px)',
                'medium' => 'Medium (350px)',
                'large' => 'Large (450px)',
                'xlarge' => 'Extra Large (550px)',
                'custom' => 'Custom Height'
            ])
            ->set_default_value('medium'),
        
        Field::make('text', 'custom_height', 'Custom Height (px)')
            ->set_attribute('type', 'number')
            ->set_default_value('350')
            ->set_conditional_logic([
                [
                    'field' => 'height_option',
                    'value' => 'custom',
                    'compare' => '='
                ]
            ]),
        
        Field::make('select', 'alignment', 'Content Alignment')
            ->set_options([
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right'
            ])
            ->set_default_value('center'),
        
        Field::make('select', 'vertical_alignment', 'Vertical Alignment')
            ->set_options([
                'top' => 'Top',
                'center' => 'Center',
                'bottom' => 'Bottom'
            ])
            ->set_default_value('center'),
        
        // Background Options
        Field::make('image', 'background_image', 'Background Image'),
        
        Field::make('select', 'bg_color', 'Background Color')
            ->set_options(get_theme_colors_for_blocks())
            ->set_default_value('neutral-dark'),
        
        // Overlay Options
        Field::make('checkbox', 'enable_overlay', 'Enable Overlay')
            ->set_default_value(true),
        
        Field::make('select', 'overlay_color', 'Overlay Color')
            ->set_options(get_theme_colors_for_blocks())
            ->set_default_value('black')
            ->set_conditional_logic([
                [
                    'field' => 'enable_overlay',
                    'value' => true,
                    'compare' => '='
                ]
            ]),
        
        Field::make('text', 'overlay_opacity', 'Overlay Opacity (0-100)')
            ->set_attribute('type', 'number')
            ->set_attribute('min', '0')
            ->set_attribute('max', '100')
            ->set_default_value('40')
            ->set_conditional_logic([
                [
                    'field' => 'enable_overlay',
                    'value' => true,
                    'compare' => '='
                ]
            ]),
        
        // Typography - Title
        Field::make('separator', 'title_separator', 'Title Typography'),
        
        Field::make('select', 'title_color', 'Title Color')
            ->set_options(get_theme_colors_for_blocks())
            ->set_default_value('white'),
        
        Field::make('select', 'title_size', 'Title Size')
            ->set_options(get_theme_font_sizes_for_blocks())
            ->set_default_value('x-large'),
        
        Field::make('select', 'title_weight', 'Title Weight')
            ->set_options([
                '300' => 'Light',
                '400' => 'Regular',
                '500' => 'Medium',
                '600' => 'Semi Bold',
                '700' => 'Bold',
                '800' => 'Extra Bold',
                '900' => 'Black'
            ])
            ->set_default_value('400'),
        
        Field::make('select', 'title_tracking', 'Title Letter Spacing')
            ->set_options([
                'tight' => 'Tight (-0.05em)',
                'normal' => 'Normal (0)',
                'wide' => 'Wide (0.05em)',
                'wider' => 'Wider (0.1em)',
                'widest' => 'Widest (0.2em)'
            ])
            ->set_default_value('wide'),
        
        // Typography - Subtitle
        Field::make('separator', 'subtitle_separator', 'Subtitle Typography'),
        
        Field::make('select', 'subtitle_color', 'Subtitle Color')
            ->set_options(get_theme_colors_for_blocks())
            ->set_default_value('white'),
        
        Field::make('select', 'subtitle_size', 'Subtitle Size')
            ->set_options(get_theme_font_sizes_for_blocks())
            ->set_default_value('medium'),
        
        Field::make('select', 'subtitle_weight', 'Subtitle Weight')
            ->set_options([
                '300' => 'Light',
                '400' => 'Regular',
                '500' => 'Medium',
                '600' => 'Semi Bold',
                '700' => 'Bold'
            ])
            ->set_default_value('400'),
        
        Field::make('select', 'subtitle_tracking', 'Subtitle Letter Spacing')
            ->set_options([
                'tight' => 'Tight (-0.05em)',
                'normal' => 'Normal (0)',
                'wide' => 'Wide (0.05em)',
                'wider' => 'Wider (0.1em)'
            ])
            ->set_default_value('normal'),
        
        // Border Radius
        Field::make('select', 'border_radius', 'Border Radius')
            ->set_options([
                'none' => 'None',
                'small' => 'Small (8px)',
                'medium' => 'Medium (16px)',
                'large' => 'Large (24px)'
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
        
        // Get height value
        $height = '350px';
        switch ($fields['height_option']) {
            case 'small':
                $height = '250px';
                break;
            case 'medium':
                $height = '350px';
                break;
            case 'large':
                $height = '450px';
                break;
            case 'xlarge':
                $height = '550px';
                break;
            case 'custom':
                $height = $fields['custom_height'] . 'px';
                break;
        }
        
        // Get letter spacing values
        $title_tracking_values = [
            'tight' => '-0.05em',
            'normal' => '0',
            'wide' => '0.05em',
            'wider' => '0.1em',
            'widest' => '0.2em'
        ];
        
        $subtitle_tracking_values = [
            'tight' => '-0.05em',
            'normal' => '0',
            'wide' => '0.05em',
            'wider' => '0.1em'
        ];
        
        // Get background image URL
        $bg_image_url = '';
        if (!empty($fields['background_image'])) {
            $bg_image_url = wp_get_attachment_image_url($fields['background_image'], 'full');
        }
        
        // Prepare data for CSS compilation
        $block_data = [
            'block_id' => $block_id,
            'fields' => $fields,
            'height' => $height,
            'bg_image_url' => $bg_image_url,
            'title_tracking' => $title_tracking_values[$fields['title_tracking']],
            'subtitle_tracking' => $subtitle_tracking_values[$fields['subtitle_tracking']]
        ];
        
        // CSS template with dynamic values
        $css_template = '
        #{{ block_id }} {
            position: relative;
            height: {{ height }};
            {% if bg_image_url %}
            background-image: url({{ bg_image_url }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            {% else %}
            background-color: {{ color_var(fields.bg_color) }};
            {% endif %}
            display: flex;
            align-items: {{ fields.vertical_alignment }};
            overflow: hidden;
        }
        
        {% if fields.enable_overlay %}
        #{{ block_id }}::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: {{ color_var(fields.overlay_color) }};
            opacity: {{ fields.overlay_opacity / 100 }};
            z-index: 1;
        }
        {% endif %}
        
        #{{ block_id }} .hero-inner {
            position: relative;
            z-index: 2;
            width: 100%;
            {% if fields.width_option == "wide" %}
            max-width: 1450px;
            {% elseif fields.width_option == "content" %}
            max-width: var(--wp--custom--layout--content-size, 1200px);
            {% elseif fields.width_option == "full" %}
            max-width: 100%;
            {% endif %}
            margin: 0 auto;
            padding: 0 40px;
            text-align: {{ fields.alignment }};
        }
        
        {% if fields.width_option != "full" and fields.border_radius != "none" %}
        #{{ block_id }} {
            {% if fields.border_radius == "small" %}
            border-radius: 8px;
            {% elseif fields.border_radius == "medium" %}
            border-radius: 16px;
            {% elseif fields.border_radius == "large" %}
            border-radius: 24px;
            {% endif %}
        }
        {% endif %}
        
        #{{ block_id }} .hero-title {
            color: {{ color_var(fields.title_color) }};
            font-size: {{ font_size_var(fields.title_size) }};
            font-weight: {{ fields.title_weight }};
            letter-spacing: {{ title_tracking }};
            margin: 0 0 16px 0;
            line-height: 1.2;
        }
        
        #{{ block_id }} .hero-subtitle {
            color: {{ color_var(fields.subtitle_color) }};
            font-size: {{ font_size_var(fields.subtitle_size) }};
            font-weight: {{ fields.subtitle_weight }};
            letter-spacing: {{ subtitle_tracking }};
            margin: 0;
            line-height: 1.5;
        }
        
        /* Breadcrumb styles */
        #{{ block_id }} .hero-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            color: {{ color_var(fields.subtitle_color) }};
            {% if fields.alignment == "center" %}
            justify-content: center;
            {% elseif fields.alignment == "right" %}
            justify-content: flex-end;
            {% endif %}
        }
        
        #{{ block_id }} .hero-breadcrumb a {
            color: {{ color_var(fields.subtitle_color) }};
            text-decoration: none;
            transition: opacity 0.2s;
        }
        
        #{{ block_id }} .hero-breadcrumb a:hover {
            opacity: 0.8;
        }
        
        #{{ block_id }} .hero-breadcrumb .separator {
            opacity: 0.5;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            #{{ block_id }} {
                height: calc({{ height }} * 0.8);
            }
            
            #{{ block_id }} .hero-inner {
                padding: 0 20px;
            }
            
            #{{ block_id }} .hero-title {
                font-size: calc({{ font_size_var(fields.title_size) }} * 0.85);
            }
            
            #{{ block_id }} .hero-subtitle {
                font-size: calc({{ font_size_var(fields.subtitle_size) }} * 0.9);
            }
        }
        ';
        
        // Compile CSS
        $compiled_css = compile_block_css($css_template, $block_data);
        
        ?>
        <div id="<?php echo esc_attr($block_id); ?>" class="hero-section-block">
            <style><?php echo $compiled_css; ?></style>
            
            <div class="hero-inner">
                <!-- Example breadcrumb - you can make this dynamic -->
                <div class="hero-breadcrumb">
                    <a href="/">HOME</a>
                    <span class="separator">›</span>
                    <a href="/shop">SHOP</a>
                    <span class="separator">›</span>
                    <span>SOFAS</span>
                </div>
                
                <?php if (!empty($fields['title'])): ?>
                    <h1 class="hero-title"><?php echo esc_html($fields['title']); ?></h1>
                <?php endif; ?>
                
                <?php if (!empty($fields['subtitle'])): ?>
                    <p class="hero-subtitle"><?php echo esc_html($fields['subtitle']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    });

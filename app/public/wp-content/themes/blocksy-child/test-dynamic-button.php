<?php
/**
 * Template Name: Test Dynamic Button
 * 
 * Test page for the dynamic button block
 */

get_header();
?>

<div class="site-content">
    <div class="container">
        <h1>Dynamic Button Block Test</h1>
        
        <p>This page demonstrates the Dynamic Button block that pulls options from theme.json.</p>
        
        <h2>Available Theme Colors:</h2>
        <pre><?php print_r(get_theme_colors_for_blocks()); ?></pre>
        
        <h2>Available Font Sizes:</h2>
        <pre><?php print_r(get_theme_font_sizes_for_blocks()); ?></pre>
        
        <h2>Available Spacing Sizes:</h2>
        <pre><?php print_r(get_theme_spacing_sizes_for_blocks()); ?></pre>
        
        <h2>Sample Button Rendering:</h2>
        <?php
        // Simulate block rendering
        $fields = [
            'button_text' => 'Dynamic Button',
            'button_url' => '#test',
            'button_color' => 'primary',
            'text_color' => 'base-lightest',
            'font_size' => 'medium',
            'padding' => '40',
            'border_radius' => 'medium'
        ];
        
        $block_id = 'dynamic-button-test';
        $block_data = [
            'block_id' => $block_id,
            'fields' => $fields
        ];
        
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
        
        <h2>Compiled CSS:</h2>
        <pre><?php echo htmlspecialchars($compiled_css); ?></pre>
    </div>
</div>

<?php
get_footer();

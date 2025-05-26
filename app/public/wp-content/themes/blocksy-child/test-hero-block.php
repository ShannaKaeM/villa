<?php
/**
 * Template Name: Test Hero Block
 * 
 * Test page for the refactored Hero block
 */

get_header();
?>

<div class="test-hero-container">
    <h2 style="text-align: center; padding: 40px 0;">Hero Block Examples</h2>
    
    <!-- Example 1: Default Hero (like the screenshot) -->
    <div style="margin-bottom: 40px;">
        <h3 style="text-align: center; margin-bottom: 20px;">Example 1: Default Hero (Wide 1450px, 350px height)</h3>
        <?php
        // Simulate the hero block with default settings
        $hero_fields = [
            'title' => 'Sofas',
            'subtitle' => '',
            'width_option' => 'wide',
            'height_option' => 'medium',
            'alignment' => 'center',
            'vertical_alignment' => 'center',
            'background_image' => '', // You would set an actual image ID here
            'bg_color' => 'neutral-dark',
            'enable_overlay' => true,
            'overlay_color' => 'black',
            'overlay_opacity' => '40',
            'title_color' => 'white',
            'title_size' => 'x-large',
            'title_weight' => '400',
            'title_tracking' => 'wide',
            'subtitle_color' => 'white',
            'subtitle_size' => 'medium',
            'subtitle_weight' => '400',
            'subtitle_tracking' => 'normal',
            'border_radius' => 'medium'
        ];
        
        // Render the block
        echo render_hero_block($hero_fields);
        ?>
    </div>
    
    <!-- Example 2: Full Width Hero -->
    <div style="margin-bottom: 40px;">
        <h3 style="text-align: center; margin-bottom: 20px;">Example 2: Full Width Hero</h3>
        <?php
        $hero_fields['width_option'] = 'full';
        $hero_fields['title'] = 'Full Width Hero';
        $hero_fields['subtitle'] = 'This hero spans the entire width of the viewport';
        echo render_hero_block($hero_fields);
        ?>
    </div>
    
    <!-- Example 3: Large Height with Custom Typography -->
    <div style="margin-bottom: 40px;">
        <h3 style="text-align: center; margin-bottom: 20px;">Example 3: Large Height with Bold Typography</h3>
        <?php
        $hero_fields['width_option'] = 'wide';
        $hero_fields['height_option'] = 'large';
        $hero_fields['title'] = 'Bold Statement';
        $hero_fields['subtitle'] = 'With extra height and bold typography';
        $hero_fields['title_weight'] = '700';
        $hero_fields['title_tracking'] = 'widest';
        echo render_hero_block($hero_fields);
        ?>
    </div>
</div>

<?php
// Helper function to render the hero block
function render_hero_block($fields) {
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
    
    // For demo purposes, using a placeholder image
    $bg_image_url = 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1600&h=600&fit=crop';
    
    // Prepare data for CSS compilation
    $block_data = [
        'block_id' => $block_id,
        'fields' => $fields,
        'height' => $height,
        'bg_image_url' => $bg_image_url,
        'title_tracking' => $title_tracking_values[$fields['title_tracking']],
        'subtitle_tracking' => $subtitle_tracking_values[$fields['subtitle_tracking']]
    ];
    
    // CSS template
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
    ';
    
    // Compile CSS
    $compiled_css = compile_block_css($css_template, $block_data);
    
    ob_start();
    ?>
    <div id="<?php echo esc_attr($block_id); ?>" class="hero-section-block">
        <style><?php echo $compiled_css; ?></style>
        
        <div class="hero-inner">
            <!-- Example breadcrumb -->
            <div class="hero-breadcrumb">
                <a href="/">HOME</a>
                <span class="separator">›</span>
                <a href="/shop">SHOP</a>
                <span class="separator">›</span>
                <span><?php echo strtoupper($fields['title']); ?></span>
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
    return ob_get_clean();
}

get_footer();
?>

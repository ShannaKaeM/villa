<?php
/**
 * Template Name: Test Theme Data
 * 
 * Debug page to see what theme data is available
 */

get_header();

// Include the helper functions
require_once get_stylesheet_directory() . '/inc/theme-json-helpers.php';
?>

<div class="site-content" style="padding: 40px;">
    <div class="container">
        <h1>Theme Data Test</h1>
        
        <h2>Global Settings Structure:</h2>
        <pre style="background: #f5f5f5; padding: 20px; overflow: auto;">
<?php 
$settings = wp_get_global_settings();
print_r($settings);
?>
        </pre>
        
        <h2>Available Colors:</h2>
        <pre style="background: #f5f5f5; padding: 20px;">
<?php print_r(get_theme_colors_for_blocks()); ?>
        </pre>
        
        <h2>Available Font Sizes:</h2>
        <pre style="background: #f5f5f5; padding: 20px;">
<?php print_r(get_theme_font_sizes_for_blocks()); ?>
        </pre>
        
        <h2>Available Spacing:</h2>
        <pre style="background: #f5f5f5; padding: 20px;">
<?php print_r(get_theme_spacing_sizes_for_blocks()); ?>
        </pre>
    </div>
</div>

<?php
get_footer();

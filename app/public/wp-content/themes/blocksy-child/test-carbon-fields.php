<?php
/**
 * Template Name: Test Carbon Fields
 */

get_header();
?>

<div class="container" style="padding: 40px;">
    <h1>Carbon Fields Test</h1>
    
    <?php
    // Test if Carbon Fields is loaded
    if (class_exists('\Carbon_Fields\Carbon_Fields')) {
        echo '<p style="color: green;">✓ Carbon Fields is loaded</p>';
    } else {
        echo '<p style="color: red;">✗ Carbon Fields is NOT loaded</p>';
    }
    
    // Test if Block class exists
    if (class_exists('\Carbon_Fields\Block')) {
        echo '<p style="color: green;">✓ Carbon Fields Block class exists</p>';
    } else {
        echo '<p style="color: red;">✗ Carbon Fields Block class does NOT exist</p>';
    }
    
    // Test if our block registration function exists
    if (function_exists('mi_register_card_loop_block')) {
        echo '<p style="color: green;">✓ Card Loop block registration function exists</p>';
    } else {
        echo '<p style="color: red;">✗ Card Loop block registration function does NOT exist</p>';
    }
    
    // Check if blocks are registered
    $registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
    $carbon_blocks = array_filter($registered_blocks, function($block) {
        return strpos($block->name, 'carbon-fields') !== false;
    });
    
    echo '<h2>Registered Carbon Fields Blocks:</h2>';
    if (!empty($carbon_blocks)) {
        echo '<ul>';
        foreach ($carbon_blocks as $block) {
            echo '<li>' . esc_html($block->name) . '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p style="color: red;">No Carbon Fields blocks found</p>';
    }
    ?>
</div>

<?php
get_footer();

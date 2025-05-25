<?php
/**
 * GutenVibes CSS Editor Integration
 * 
 * This file provides integration points for the GutenVibes CSS Editor
 * to work with our custom blocks.
 */

// Register GutenVibes REST API endpoints
add_action('rest_api_init', function() {
    // Endpoint to get block configuration
    register_rest_route('gutenvibes/v1', '/blocks', array(
        'methods' => 'GET',
        'callback' => 'gutenvibes_get_blocks_config',
        'permission_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
    
    // Endpoint to update block CSS
    register_rest_route('gutenvibes/v1', '/blocks/(?P<block>[a-zA-Z0-9_-]+)/css', array(
        'methods' => 'POST',
        'callback' => 'gutenvibes_update_block_css',
        'permission_callback' => function() {
            return current_user_can('edit_theme_options');
        },
        'args' => array(
            'block' => array(
                'required' => true,
                'validate_callback' => function($param) {
                    return preg_match('/^[a-zA-Z0-9_-]+$/', $param);
                }
            ),
            'css' => array(
                'required' => true,
                'sanitize_callback' => 'wp_strip_all_tags'
            )
        )
    ));
});

/**
 * Get blocks configuration for GutenVibes
 */
function gutenvibes_get_blocks_config() {
    $config_file = get_stylesheet_directory() . '/blocks/gutenvibes.config.json';
    
    if (!file_exists($config_file)) {
        return new WP_Error('config_not_found', 'GutenVibes configuration file not found', array('status' => 404));
    }
    
    $config = json_decode(file_get_contents($config_file), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return new WP_Error('invalid_config', 'Invalid GutenVibes configuration', array('status' => 500));
    }
    
    // Add CSS content for each block
    foreach ($config['blocks'] as &$block) {
        $block_dir = dirname($config_file) . '/' . str_replace('miblocks/', '', $block['name']);
        $css_file = $block_dir . '/' . $block['cssFile'];
        
        if (file_exists($css_file)) {
            $block['cssContent'] = file_get_contents($css_file);
        }
    }
    
    return rest_ensure_response($config);
}

/**
 * Update block CSS via GutenVibes
 */
function gutenvibes_update_block_css($request) {
    $block_name = $request['block'];
    $css_content = $request['css'];
    
    // Validate block exists
    $blocks_dir = get_stylesheet_directory() . '/blocks';
    $block_dir = $blocks_dir . '/' . $block_name;
    
    if (!is_dir($block_dir)) {
        return new WP_Error('block_not_found', 'Block directory not found', array('status' => 404));
    }
    
    // Create styles directory if it doesn't exist
    $styles_dir = $block_dir . '/styles';
    if (!is_dir($styles_dir)) {
        wp_mkdir_p($styles_dir);
    }
    
    // Write CSS file
    $css_file = $styles_dir . '/block.css';
    $result = file_put_contents($css_file, $css_content);
    
    if ($result === false) {
        return new WP_Error('write_failed', 'Failed to write CSS file', array('status' => 500));
    }
    
    // Clear any caches
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
    
    return rest_ensure_response(array(
        'success' => true,
        'message' => 'CSS updated successfully',
        'file' => $css_file
    ));
}

/**
 * Add GutenVibes editor button to admin bar
 */
add_action('admin_bar_menu', function($wp_admin_bar) {
    if (!current_user_can('edit_theme_options')) {
        return;
    }
    
    $wp_admin_bar->add_node(array(
        'id' => 'gutenvibes-editor',
        'title' => '🎨 GutenVibes CSS Editor',
        'href' => '#',
        'meta' => array(
            'onclick' => 'window.gutenVibesEditor && window.gutenVibesEditor.open(); return false;'
        )
    ));
}, 100);

/**
 * Enqueue GutenVibes editor scripts
 */
add_action('admin_enqueue_scripts', function() {
    if (!current_user_can('edit_theme_options')) {
        return;
    }
    
    // This assumes GutenVibes provides a JavaScript file
    // Update this path when you have the actual GutenVibes editor files
    /*
    wp_enqueue_script(
        'gutenvibes-editor',
        get_stylesheet_directory_uri() . '/blocks/gutenvibes-editor.js',
        array('wp-blocks', 'wp-element', 'wp-editor'),
        '1.0.0',
        true
    );
    */
    
    // Pass configuration to JavaScript
    wp_localize_script('gutenvibes-editor', 'gutenVibesConfig', array(
        'apiUrl' => rest_url('gutenvibes/v1'),
        'nonce' => wp_create_nonce('wp_rest'),
        'blocksDir' => get_stylesheet_directory_uri() . '/blocks'
    ));
});

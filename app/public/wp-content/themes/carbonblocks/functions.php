<?php

// Load theme setup and dependencies
require_once get_stylesheet_directory() . '/src/config/setup.php';

// Load Villa Community theme integration
add_action('wp_enqueue_scripts', function() {
    // Enqueue minimal theme integration CSS
    wp_enqueue_style(
        'carbonblocks-theme-integration',
        get_stylesheet_directory_uri() . '/assets/css/theme-integration.css',
        array(),
        '1.0.0'
    );
}, 20);

// Register block categories
add_filter('block_categories_all', function($categories) {
    return array_merge(
        array(
            array(
                'slug' => 'carbon-blocks-villa',
                'title' => __('Villa Community Blocks', 'carbonblocks'),
                'icon' => 'admin-home'
            )
        ),
        $categories
    );
}, 10, 1);

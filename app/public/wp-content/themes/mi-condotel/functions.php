<?php
/**
 * mi-Condotel Theme Functions
 *
 * @package mi-condotel
 */

// Theme Setup
function mi_condotel_setup() {
    // Add theme support
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Add support for block styles
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'mi-condotel'),
        'footer'  => esc_html__('Footer Menu', 'mi-condotel'),
    ));
    
    // Add support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'mi_condotel_setup');

// Enqueue styles and scripts
function mi_condotel_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'mi-condotel-google-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap',
        array(),
        null
    );
    
    // Theme styles
    wp_enqueue_style(
        'mi-condotel-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );
    
    // Main CSS (includes base.css)
    wp_enqueue_style(
        'mi-condotel-main',
        get_template_directory_uri() . '/assets/css/main.css',
        array('mi-condotel-style'),
        wp_get_theme()->get('Version')
    );
    
    // Theme scripts
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'mi_condotel_scripts');

// Register block styles for editor
function mi_condotel_editor_styles() {
    add_editor_style('assets/css/main.css');
}
add_action('admin_init', 'mi_condotel_editor_styles');

// Register custom blocks
function mi_condotel_register_blocks() {
    $blocks_dir = get_template_directory() . '/blocks';
    
    if (!is_dir($blocks_dir)) {
        return;
    }
    
    // Find all block.json files
    $block_json_files = glob($blocks_dir . '/*/block.json');
    
    foreach ($block_json_files as $block_json) {
        register_block_type(dirname($block_json));
    }
}
add_action('init', 'mi_condotel_register_blocks');

// Custom Post Types
function mi_condotel_register_post_types() {
    // Property Post Type
    register_post_type('property', array(
        'labels' => array(
            'name'               => __('Properties', 'mi-condotel'),
            'singular_name'      => __('Property', 'mi-condotel'),
            'add_new'            => __('Add New Property', 'mi-condotel'),
            'add_new_item'       => __('Add New Property', 'mi-condotel'),
            'edit_item'          => __('Edit Property', 'mi-condotel'),
            'new_item'           => __('New Property', 'mi-condotel'),
            'view_item'          => __('View Property', 'mi-condotel'),
            'search_items'       => __('Search Properties', 'mi-condotel'),
            'not_found'          => __('No properties found', 'mi-condotel'),
            'not_found_in_trash' => __('No properties found in Trash', 'mi-condotel'),
        ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'properties'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-building',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    ));
    
    // Property Type Taxonomy
    register_taxonomy('property_type', 'property', array(
        'labels' => array(
            'name'              => __('Property Types', 'mi-condotel'),
            'singular_name'     => __('Property Type', 'mi-condotel'),
            'search_items'      => __('Search Property Types', 'mi-condotel'),
            'all_items'         => __('All Property Types', 'mi-condotel'),
            'edit_item'         => __('Edit Property Type', 'mi-condotel'),
            'update_item'       => __('Update Property Type', 'mi-condotel'),
            'add_new_item'      => __('Add New Property Type', 'mi-condotel'),
            'new_item_name'     => __('New Property Type Name', 'mi-condotel'),
            'menu_name'         => __('Property Types', 'mi-condotel'),
        ),
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'property-type'),
        'show_in_rest'      => true,
    ));
    
    // Property Features Taxonomy
    register_taxonomy('property_features', 'property', array(
        'labels' => array(
            'name'              => __('Property Features', 'mi-condotel'),
            'singular_name'     => __('Property Feature', 'mi-condotel'),
            'search_items'      => __('Search Property Features', 'mi-condotel'),
            'all_items'         => __('All Property Features', 'mi-condotel'),
            'edit_item'         => __('Edit Property Feature', 'mi-condotel'),
            'update_item'       => __('Update Property Feature', 'mi-condotel'),
            'add_new_item'      => __('Add New Property Feature', 'mi-condotel'),
            'new_item_name'     => __('New Property Feature Name', 'mi-condotel'),
            'menu_name'         => __('Property Features', 'mi-condotel'),
        ),
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'property-features'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'mi_condotel_register_post_types');

// Theme activation - flush rewrite rules
function mi_condotel_rewrite_flush() {
    mi_condotel_register_post_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'mi_condotel_rewrite_flush');

// Disable Gutenberg default styles (we have our own)
function mi_condotel_disable_gutenberg_styles() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
}
add_action('wp_enqueue_scripts', 'mi_condotel_disable_gutenberg_styles', 100);

// Add custom image sizes
add_image_size('property-thumbnail', 400, 300, true);
add_image_size('property-featured', 1200, 800, true);
add_image_size('property-gallery', 800, 600, true);

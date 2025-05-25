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

/**
 * Fallback menu function - displays pages if no menu is set
 */
function mi_condotel_fallback_menu() {
    $pages = get_pages(array(
        'sort_order' => 'ASC',
        'sort_column' => 'menu_order,post_title',
        'parent' => 0,
        'exclude' => get_option('page_on_front')
    ));
    
    if ($pages) {
        echo '<div class="menu-container">';
        echo '<ul id="primary-menu" class="menu">';
        
        // Add Home link
        echo '<li class="menu-item"><a href="' . esc_url(home_url('/')) . '">Home</a></li>';
        
        // Add top-level pages
        foreach ($pages as $page) {
            echo '<li class="menu-item">';
            echo '<a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html($page->post_title) . '</a>';
            echo '</li>';
        }
        
        // Add Properties, Businesses, and Articles if they exist
        if (post_type_exists('property')) {
            echo '<li class="menu-item"><a href="' . esc_url(get_post_type_archive_link('property')) . '">Properties</a></li>';
        }
        if (post_type_exists('business')) {
            echo '<li class="menu-item"><a href="' . esc_url(get_post_type_archive_link('business')) . '">Businesses</a></li>';
        }
        if (post_type_exists('article')) {
            echo '<li class="menu-item"><a href="' . esc_url(get_post_type_archive_link('article')) . '">Articles</a></li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}

// Include additional functionality
require_once get_template_directory() . '/inc/mi-cleanup.php';
require_once get_template_directory() . '/inc/mi-cpt-registration.php';
require_once get_template_directory() . '/inc/carbon-fields-setup.php';
require_once get_template_directory() . '/inc/mi-property-fields.php';
require_once get_template_directory() . '/inc/mi-property-importer.php';
require_once get_template_directory() . '/inc/mi-taxonomy-importer.php';
require_once get_template_directory() . '/inc/mi-site-migration.php';
require_once get_template_directory() . '/inc/mi-export-helper.php';

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

// Theme activation - flush rewrite rules
function mi_condotel_rewrite_flush() {
    // Call the CPT registration functions from mi-cpt-registration.php
    mi_register_property_post_type();
    mi_register_business_post_type();
    mi_register_article_post_type();
    mi_register_user_profile_post_type();
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

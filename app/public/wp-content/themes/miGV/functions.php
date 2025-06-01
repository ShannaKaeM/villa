<?php
/**
 * miGV Theme Functions (Updated with Timber & CMB2)
 * 
 * @package miGV
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load Composer autoloader
$autoloader_path = dirname(__FILE__) . '/../../../../../vendor/autoload.php';
if (file_exists($autoloader_path)) {
    require_once $autoloader_path;
} else {
    // Add admin notice if autoloader is missing
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>Composer autoloader not found. Please run "composer install" in the project root.</p></div>';
    });
}

// Initialize Timber
if (class_exists('Timber\Timber')) {
    Timber\Timber::init();
    
    // Set Timber directories
    Timber\Timber::$dirname = array('templates', 'views');
    
    // Add Timber context filters
    add_filter('timber/context', 'migv_add_to_context');
    add_filter('timber/twig', 'migv_add_to_twig');
} else {
    // Add admin notice if Timber is not available
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>Timber not found. Please install Timber via Composer.</p></div>';
    });
}

/**
 * Theme setup
 */
function migv_setup() {
    // Add theme support for various features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Add theme support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add theme support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
    
    // Add theme support for wide and full alignment
    add_theme_support('align-wide');
    
    // Add theme support for block editor color palette
    add_theme_support('editor-color-palette');
    
    // Add theme support for custom line height
    add_theme_support('custom-line-height');
    
    // Add theme support for custom spacing
    add_theme_support('custom-spacing');
    
    // Add theme support for custom units
    add_theme_support('custom-units');
    
    // Add theme support for post formats
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'status',
        'video',
        'audio',
        'chat',
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'migv'),
        'footer'  => __('Footer Menu', 'migv'),
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1280;
    }
}
add_action('after_setup_theme', 'migv_setup');

/**
 * Enqueue scripts and styles
 */
function migv_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('migv-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue block styles
    wp_enqueue_style('migv-blocks', get_template_directory_uri() . '/assets/css/blocks.css', array('migv-style'), '1.0.0');
    
    // Enqueue villa dashboard styles
    wp_enqueue_style('villa-dashboard', get_template_directory_uri() . '/assets/css/villa-dashboard.css', array('migv-blocks'), '1.0.0');
    
    // Enqueue main JavaScript
    wp_enqueue_script('migv-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Enqueue villa dashboard JavaScript
    wp_enqueue_script('villa-dashboard', get_template_directory_uri() . '/assets/js/villa-dashboard.js', array('jquery'), '1.0.0', true);
    
    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Localize script for AJAX
    wp_localize_script('migv-main', 'migv_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('migv_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'migv_scripts');

/**
 * Enqueue admin scripts and styles
 */
function migv_admin_scripts() {
    wp_enqueue_style('migv-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'migv_admin_scripts');

/**
 * Register widget areas
 */
function migv_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'migv'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'migv'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer', 'migv'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'migv'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'migv_widgets_init');

/**
 * Add to Timber context
 */
function migv_add_to_context($context) {
    // Add menus to context
    $context['menu'] = Timber\Timber::get_menu('primary');
    $context['footer_menu'] = Timber\Timber::get_menu('footer');
    
    // Add site info
    $context['site'] = new Timber\Site();
    $context['site']->language_attributes = function() {
        return get_language_attributes();
    };
    
    // Add WordPress functions as callable functions
    $context['wp_head'] = function() {
        ob_start();
        wp_head();
        return ob_get_clean();
    };
    
    $context['wp_body_open'] = function() {
        ob_start();
        wp_body_open();
        return ob_get_clean();
    };
    
    $context['wp_footer'] = function() {
        ob_start();
        wp_footer();
        return ob_get_clean();
    };
    
    $context['body_class'] = function() {
        return implode(' ', get_body_class());
    };
    
    // Add translation function
    $context['__'] = function($text, $domain = 'migv') {
        return __($text, $domain);
    };
    
    // Add sidebar
    $context['sidebar'] = Timber\Timber::get_widgets('sidebar-1');
    $context['footer_widgets'] = Timber\Timber::get_widgets('footer-1');
    
    return $context;
}

/**
 * Add to Twig
 */
function migv_add_to_twig($twig) {
    // Add custom functions to Twig if needed
    
    return $twig;
}

/**
 * Register custom post types
 */
function migv_register_post_types() {
    // Property post type
    register_post_type('property', array(
        'labels' => array(
            'name'               => __('Properties', 'migv'),
            'singular_name'      => __('Property', 'migv'),
            'menu_name'          => __('Properties', 'migv'),
            'add_new'            => __('Add New', 'migv'),
            'add_new_item'       => __('Add New Property', 'migv'),
            'edit_item'          => __('Edit Property', 'migv'),
            'new_item'           => __('New Property', 'migv'),
            'view_item'          => __('View Property', 'migv'),
            'search_items'       => __('Search Properties', 'migv'),
            'not_found'          => __('No properties found', 'migv'),
            'not_found_in_trash' => __('No properties found in Trash', 'migv'),
        ),
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-admin-home',
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'rewrite'       => array('slug' => 'properties'),
        'show_in_rest'  => true,
    ));
    
    // Business post type
    register_post_type('business', array(
        'labels' => array(
            'name'               => __('Businesses', 'migv'),
            'singular_name'      => __('Business', 'migv'),
            'menu_name'          => __('Businesses', 'migv'),
            'add_new'            => __('Add New', 'migv'),
            'add_new_item'       => __('Add New Business', 'migv'),
            'edit_item'          => __('Edit Business', 'migv'),
            'new_item'           => __('New Business', 'migv'),
            'view_item'          => __('View Business', 'migv'),
            'search_items'       => __('Search Businesses', 'migv'),
            'not_found'          => __('No businesses found', 'migv'),
            'not_found_in_trash' => __('No businesses found in Trash', 'migv'),
        ),
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-store',
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt'),
        'rewrite'       => array('slug' => 'businesses'),
        'show_in_rest'  => true,
    ));
    
    // Article post type
    register_post_type('article', array(
        'labels' => array(
            'name'               => __('Articles', 'migv'),
            'singular_name'      => __('Article', 'migv'),
            'menu_name'          => __('Articles', 'migv'),
            'add_new'            => __('Add New', 'migv'),
            'add_new_item'       => __('Add New Article', 'migv'),
            'edit_item'          => __('Edit Article', 'migv'),
            'new_item'           => __('New Article', 'migv'),
            'view_item'          => __('View Article', 'migv'),
            'search_items'       => __('Search Articles', 'migv'),
            'not_found'          => __('No articles found', 'migv'),
            'not_found_in_trash' => __('No articles found in Trash', 'migv'),
        ),
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-media-document',
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'),
        'rewrite'       => array('slug' => 'articles'),
        'show_in_rest'  => true,
    ));
}
add_action('init', 'migv_register_post_types');

/**
 * Register custom taxonomies
 */
function migv_register_taxonomies() {
    // Property categories
    register_taxonomy('property_category', 'property', array(
        'labels' => array(
            'name'              => __('Property Categories', 'migv'),
            'singular_name'     => __('Property Category', 'migv'),
            'search_items'      => __('Search Property Categories', 'migv'),
            'all_items'         => __('All Property Categories', 'migv'),
            'edit_item'         => __('Edit Property Category', 'migv'),
            'update_item'       => __('Update Property Category', 'migv'),
            'add_new_item'      => __('Add New Property Category', 'migv'),
            'new_item_name'     => __('New Property Category Name', 'migv'),
            'menu_name'         => __('Categories', 'migv'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'property-category'),
        'show_in_rest'      => true,
    ));
    
    // Property locations
    register_taxonomy('property_location', 'property', array(
        'labels' => array(
            'name'              => __('Property Locations', 'migv'),
            'singular_name'     => __('Property Location', 'migv'),
            'search_items'      => __('Search Property Locations', 'migv'),
            'all_items'         => __('All Property Locations', 'migv'),
            'edit_item'         => __('Edit Property Location', 'migv'),
            'update_item'       => __('Update Property Location', 'migv'),
            'add_new_item'      => __('Add New Property Location', 'migv'),
            'new_item_name'     => __('New Property Location Name', 'migv'),
            'menu_name'         => __('Locations', 'migv'),
        ),
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'location'),
        'show_in_rest'      => true,
    ));
}
add_action('init', 'migv_register_taxonomies');

/**
 * Enqueue Customizer scripts
 */
function migv_customize_preview_js() {
    wp_enqueue_script('migv-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), '1.0.0', true);
}
add_action('customize_preview_init', 'migv_customize_preview_js');

/**
 * Register custom block category
 */
function migv_register_block_category($categories) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'migv-blocks',
                'title' => __('miGV Blocks', 'migv'),
                'icon'  => 'admin-home',
            ),
        )
    );
}
add_filter('block_categories_all', 'migv_register_block_category');

/**
 * Custom page templates for membership pages
 */
function migv_custom_page_templates($template) {
    if (is_page()) {
        global $post;
        $page_slug = $post->post_name;
        
        // Check for custom Twig templates
        $custom_templates = array(
            'login' => 'page-login.twig',
            'register' => 'page-register.twig', 
            'members' => 'page-members.twig',
            'user' => 'page-profile.twig'
        );
        
        if (isset($custom_templates[$page_slug])) {
            $context = Timber::context();
            $context['post'] = new Timber\Post();
            
            Timber::render($custom_templates[$page_slug], $context);
            exit;
        }
    }
    
    return $template;
}
add_filter('template_include', 'migv_custom_page_templates');

/**
 * Include required files
 */
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/design-book-router.php';

/**
 * Timber Site class extension
 */
class MiGVSite extends Timber\Site {
    public function __construct() {
        add_action('after_setup_theme', array($this, 'theme_supports'));
        add_filter('timber/context', array($this, 'add_to_context'));
        add_filter('timber/twig', array($this, 'add_to_twig'));
        parent::__construct();
    }

    public function theme_supports() {
        // Additional theme supports can be added here
    }

    public function add_to_context($context) {
        $context['foo'] = 'bar';
        $context['stuff'] = 'I am a value set in your functions.php file';
        $context['notes'] = 'These values are available everytime you call Timber::context();';
        return $context;
    }

    public function add_to_twig($twig) {
        /* this is where you can add your own functions to twig */
        $twig->addExtension(new Twig\Extension\StringLoaderExtension());
        return $twig;
    }
}

new MiGVSite();

/**
 * Design Book AJAX Handlers
 */

// Typography/Text Styles Handlers
add_action('wp_ajax_villa_save_text_styles', 'villa_handle_save_text_styles');
add_action('wp_ajax_save_text_styles', 'villa_handle_save_text_styles');

add_action('wp_ajax_villa_reset_text_styles', 'villa_handle_reset_text_styles');
add_action('wp_ajax_reset_text_styles', 'villa_handle_reset_text_styles');

add_action('wp_ajax_save_base_styles', 'villa_handle_save_base_styles');
add_action('wp_ajax_reset_base_styles', 'villa_handle_reset_base_styles');

/**
 * Handle saving text styles to theme.json and Twig components
 */
function villa_handle_save_text_styles() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'migv_nonce')) {
        wp_die('Security check failed');
    }
    
    // Check user capabilities
    if (!current_user_can('edit_theme_options')) {
        wp_die('Insufficient permissions');
    }
    
    try {
        $text_styles = json_decode(stripslashes($_POST['text_styles']), true);
        if (!$text_styles) {
            $styles = $_POST['styles'] ?? [];
            $text_styles = $styles;
        }
        
        // Update theme.json
        villa_update_theme_json_typography($text_styles);
        
        // Update Twig component defaults
        villa_update_typography_twig_defaults($text_styles);
        
        wp_send_json_success('Text styles saved successfully');
        
    } catch (Exception $e) {
        wp_send_json_error('Failed to save text styles: ' . $e->getMessage());
    }
}

/**
 * Handle resetting text styles to defaults
 */
function villa_handle_reset_text_styles() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'migv_nonce')) {
        wp_die('Security check failed');
    }
    
    // Check user capabilities
    if (!current_user_can('edit_theme_options')) {
        wp_die('Insufficient permissions');
    }
    
    try {
        // Reset to default typography values
        villa_reset_theme_json_typography();
        villa_reset_typography_twig_defaults();
        
        wp_send_json_success('Text styles reset successfully');
        
    } catch (Exception $e) {
        wp_send_json_error('Failed to reset text styles: ' . $e->getMessage());
    }
}

/**
 * Handle saving base typography styles
 */
function villa_handle_save_base_styles() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'migv_nonce')) {
        wp_die('Security check failed');
    }
    
    // Check user capabilities
    if (!current_user_can('edit_theme_options')) {
        wp_die('Insufficient permissions');
    }
    
    try {
        $base_styles = $_POST['base_styles'] ?? [];
        
        // Update theme.json base typography
        villa_update_theme_json_base_typography($base_styles);
        
        wp_send_json_success('Base styles saved successfully');
        
    } catch (Exception $e) {
        wp_send_json_error('Failed to save base styles: ' . $e->getMessage());
    }
}

/**
 * Handle resetting base typography styles
 */
function villa_handle_reset_base_styles() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'migv_nonce')) {
        wp_die('Security check failed');
    }
    
    // Check user capabilities
    if (!current_user_can('edit_theme_options')) {
        wp_die('Insufficient permissions');
    }
    
    try {
        // Reset base typography to defaults
        villa_reset_theme_json_base_typography();
        
        wp_send_json_success('Base styles reset successfully');
        
    } catch (Exception $e) {
        wp_send_json_error('Failed to reset base styles: ' . $e->getMessage());
    }
}

/**
 * Update theme.json typography settings
 */
function villa_update_theme_json_typography($text_styles) {
    $theme_json_path = get_template_directory() . '/theme.json';
    
    if (!file_exists($theme_json_path)) {
        throw new Exception('theme.json file not found');
    }
    
    $theme_json = json_decode(file_get_contents($theme_json_path), true);
    
    if (!$theme_json) {
        throw new Exception('Invalid theme.json format');
    }
    
    // Update typography settings in theme.json
    foreach ($text_styles as $style_key => $style_data) {
        // Map to theme.json structure
        if (isset($style_data['fontSize'])) {
            $theme_json['settings']['typography']['fontSizes'][] = [
                'slug' => $style_key,
                'size' => $style_data['fontSize'],
                'name' => ucfirst($style_key)
            ];
        }
    }
    
    // Write back to theme.json
    file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

/**
 * Update typography.twig component defaults
 */
function villa_update_typography_twig_defaults($text_styles) {
    $typography_path = get_template_directory() . '/templates/components/prmimitive-books/typography.twig';
    
    if (!file_exists($typography_path)) {
        return;
    }
    
    $content = file_get_contents($typography_path);
    
    // Update default values in the Twig component
    foreach ($text_styles as $style_key => $style_data) {
        if (isset($style_data['fontSize'])) {
            $pattern = "/({% set font_size = font_size\|default\(')([^']+)('\) %})/";
            $replacement = '${1}' . $style_data['fontSize'] . '${3}';
            $content = preg_replace($pattern, $replacement, $content);
        }
        
        if (isset($style_data['fontWeight'])) {
            $pattern = "/({% set font_weight = font_weight\|default\(')([^']+)('\) %})/";
            $replacement = '${1}' . $style_data['fontWeight'] . '${3}';
            $content = preg_replace($pattern, $replacement, $content);
        }
    }
    
    file_put_contents($typography_path, $content);
}

/**
 * Reset theme.json typography to defaults
 */
function villa_reset_theme_json_typography() {
    // Implementation for resetting typography
    $theme_json_path = get_template_directory() . '/theme.json';
    
    // You would implement default typography values here
    // For now, we'll just log that reset was called
    error_log('Typography reset called - implement default values');
}

/**
 * Reset typography.twig to defaults
 */
function villa_reset_typography_twig_defaults() {
    // Implementation for resetting Twig defaults
    error_log('Typography Twig reset called - implement default values');
}

/**
 * Update base typography in theme.json
 */
function villa_update_theme_json_base_typography($base_styles) {
    $theme_json_path = get_template_directory() . '/theme.json';
    
    if (!file_exists($theme_json_path)) {
        throw new Exception('theme.json file not found');
    }
    
    $theme_json = json_decode(file_get_contents($theme_json_path), true);
    
    // Update base typography settings
    if (isset($base_styles['baseFontSize'])) {
        $theme_json['settings']['typography']['fontSize'] = $base_styles['baseFontSize'];
    }
    
    file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

/**
 * Reset base typography in theme.json
 */
function villa_reset_theme_json_base_typography() {
    // Implementation for resetting base typography
    error_log('Base typography reset called - implement default values');
}

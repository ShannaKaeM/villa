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
    $context['menu'] = new Timber\Menu('primary');
    $context['footer_menu'] = new Timber\Menu('footer');
    
    // Add site info
    $context['site'] = new Timber\Site();
    
    // Add theme options
    $context['theme_options'] = array(
        'copyright_text' => mi_get_theme_option('mi_copyright_text'),
        'contact_email' => mi_get_theme_option('mi_contact_email'),
        'phone_number' => mi_get_theme_option('mi_phone_number'),
        'address' => mi_get_theme_option('mi_address'),
    );
    
    // Add sidebar
    $context['sidebar'] = Timber\Timber::get_widgets('sidebar-1');
    $context['footer_widgets'] = Timber\Timber::get_widgets('footer-1');
    
    return $context;
}

/**
 * Add to Twig
 */
function migv_add_to_twig($twig) {
    // Add custom functions to Twig
    $twig->addFunction(new Timber\Twig_Function('mi_get_meta', 'mi_get_meta'));
    $twig->addFunction(new Timber\Twig_Function('mi_get_theme_option', 'mi_get_theme_option'));
    
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

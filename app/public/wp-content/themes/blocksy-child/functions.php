<?php
/**
 * Blocksy Child Theme Functions
 */

// Load Composer autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Include color sync functionality
require_once get_stylesheet_directory() . '/inc/blocksy-color-sync.php';

// Include Twig integration
require_once get_stylesheet_directory() . '/vendor/autoload.php';
require_once get_stylesheet_directory() . '/inc/TwigIntegration.php';

// Initialize Twig
add_action('after_setup_theme', function() {
    \MiAgency\twig();
});

// Add Twig shortcode for demonstration
add_shortcode('twig_button', function($atts) {
    $atts = shortcode_atts([
        'text' => 'Click Me',
        'url' => '#',
        'variant' => 'primary',
        'size' => 'medium'
    ], $atts);
    
    return \MiAgency\twig()->render('components/button.twig', $atts);
});

// Add Twig card shortcode
add_shortcode('twig_card', function($atts, $content = '') {
    $atts = shortcode_atts([
        'title' => '',
        'variant' => 'default',
        'link' => ''
    ], $atts);
    
    $atts['content'] = $content;
    
    return \MiAgency\twig()->render('components/card.twig', $atts);
});

// Enqueue main styles
function blocksy_child_enqueue_styles() {
    // Get the parent theme version for cache busting
    $parent_theme = wp_get_theme('blocksy');
    $parent_version = $parent_theme->get('Version');
    
    // Enqueue parent theme styles
    wp_enqueue_style('blocksy-parent-style', 
        get_template_directory_uri() . '/style.css',
        array(),
        $parent_version
    );
    
    // Enqueue child theme styles (minimal, just theme declaration)
    wp_enqueue_style('blocksy-child-style',
        get_stylesheet_uri(),
        array('blocksy-parent-style'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue Google Fonts
    wp_enqueue_style('google-fonts', 
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap',
        array(),
        null
    );
    
    // Enqueue theme integration CSS (works with theme.json)
    wp_enqueue_style('villa-theme-integration',
        get_stylesheet_directory_uri() . '/assets/css/theme-integration.css',
        array('blocksy-child-style'),
        filemtime(get_stylesheet_directory() . '/assets/css/theme-integration.css')
    );
}
add_action('wp_enqueue_scripts', 'blocksy_child_enqueue_styles');

// Carbon Fields setup - this MUST come first before any other includes
require_once get_stylesheet_directory() . '/inc/carbon-fields-setup.php';

// Boot Carbon Fields
add_action('after_setup_theme', 'blocksy_child_carbon_fields_boot');
function blocksy_child_carbon_fields_boot() {
    \Carbon_Fields\Carbon_Fields::boot();
}

// Include theme JSON helpers
require_once get_stylesheet_directory() . '/inc/theme-json-helpers.php';

// Register custom block category for Twig blocks
function mi_register_twig_block_category($categories) {
    return array_merge(
        $categories,
        [
            [
                'slug'  => 'twig-blocks',
                'title' => __('Twig Blocks', 'blocksy-child'),
                'icon'  => 'layout',
            ],
        ]
    );
}
add_filter('block_categories_all', 'mi_register_twig_block_category', 10, 1);

// Load only Twig demo block
function mi_load_twig_blocks() {
    // Register the block directly here instead of requiring a file
    \Carbon_Fields\Block::make(__('Twig Demo', 'blocksy-child'))
        ->set_icon('layout')
        ->set_category('twig-blocks')
        ->add_fields([
            \Carbon_Fields\Field::make('text', 'title', __('Card Title', 'blocksy-child'))
                ->set_default_value('Twig Demo Card'),
            \Carbon_Fields\Field::make('textarea', 'content', __('Card Content', 'blocksy-child'))
                ->set_default_value('This is a card component rendered with Twig.')
        ])
        ->set_render_callback(function ($fields) {
            try {
                // Use Twig to render the component
                $twig = \MiAgency\twig();
                $data = [
                    'title' => $fields['title'] ?? 'Twig Demo Card',
                    'content' => $fields['content'] ?? 'This is a card component rendered with Twig.',
                    'variant' => 'elevated'
                ];
                
                // Return the rendered content instead of echoing
                return $twig->render('components/card.twig', $data);
            } catch (\Exception $e) {
                return '<div style="padding: 20px; background: #fee; color: #c00;">Error: ' . esc_html($e->getMessage()) . '</div>';
            }
        });
}
add_action('carbon_fields_register_fields', 'mi_load_twig_blocks', 5);

/**
 * Dynamic Complete Theme.json REST API
 * Returns ALL theme.json data that the block editor uses
 */
add_action('rest_api_init', function() {
    register_rest_route('wp/v2', '/theme-json', array(
        'methods' => 'GET',
        'callback' => 'get_dynamic_theme_json_data',
        'permission_callback' => '__return_true',
    ));
});

function get_dynamic_theme_json_data() {
    // Check if WP_Theme_JSON_Resolver exists (WordPress 5.8+)
    if (!class_exists('WP_Theme_JSON_Resolver')) {
        return rest_ensure_response(array(
            'error' => 'WP_Theme_JSON_Resolver not available',
            'message' => 'This feature requires WordPress 5.8 or higher'
        ));
    }
    
    // Get the complete WP_Theme_JSON object that the editor uses
    $theme_json_resolver = WP_Theme_JSON_Resolver::get_merged_data();
    
    // Get the raw data from the resolver (this is what the editor actually uses)
    $complete_data = $theme_json_resolver->get_data();
    
    // Also get the global settings and styles for comparison/backup
    $global_settings = wp_get_global_settings();
    $global_styles = wp_get_global_styles();
    
    // Get all custom CSS properties that are generated
    $custom_properties = $theme_json_resolver->get_custom_css();
    
    // Get stylesheet that would be generated
    $stylesheet = $theme_json_resolver->get_stylesheet();
    
    // Return everything the block editor has access to
    return rest_ensure_response(array(
        // The complete merged theme.json data (theme + user + core defaults)
        'merged_data' => $complete_data,
        
        // Individual components for easier access
        'settings' => $complete_data['settings'] ?? array(),
        'styles' => $complete_data['styles'] ?? array(),
        'customTemplates' => $complete_data['customTemplates'] ?? array(),
        'templateParts' => $complete_data['templateParts'] ?? array(),
        'patterns' => $complete_data['patterns'] ?? array(),
        
        // Processed/computed values that the editor actually uses
        'global_settings' => $global_settings,
        'global_styles' => $global_styles,
        
        // Generated CSS and properties
        'custom_properties' => $custom_properties,
        'stylesheet' => $stylesheet,
        
        // Additional useful data
        'version' => $complete_data['version'] ?? 2,
        'source' => 'wp_theme_json_resolver_merged', // So you know this is the complete data
    ));
}

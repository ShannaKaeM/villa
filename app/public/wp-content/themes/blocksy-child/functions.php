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

// Remove global editor overrides - blocks handle their own editor styles
// function blocksy_child_enqueue_editor_styles() {
//     add_editor_style('assets/css/editor-overrides.css');
// }
// add_action('after_setup_theme', 'blocksy_child_enqueue_editor_styles');

// Carbon Fields setup - this MUST come first before any other includes
require_once get_stylesheet_directory() . '/inc/carbon-fields-setup.php';

// Boot Carbon Fields
add_action('after_setup_theme', 'blocksy_child_carbon_fields_boot');
function blocksy_child_carbon_fields_boot() {
    \Carbon_Fields\Carbon_Fields::boot();
}

// Include theme JSON helpers
require_once get_stylesheet_directory() . '/inc/theme-json-helpers.php';

// Register Carbon Fields blocks
require_once get_stylesheet_directory() . '/inc/mi-cpt-registration.php';

// Include Carbon Fields property fields
require_once get_stylesheet_directory() . '/inc/mi-property-fields.php';

// All importers and migration tools have been removed after successful data migration

/**
 * Add your custom functions below this line
 */

/**
 * Register custom block category
 */
function mi_register_block_category($categories) {
    return array_merge(
        $categories,
        [
            [
                'slug'  => 'miblocks',
                'title' => __('miBlocks', 'blocksy-child'),
                'icon'  => 'layout',
            ],
        ]
    );
}
add_filter('block_categories_all', 'mi_register_block_category', 10, 1);

/**
 * Load custom blocks
 */
function mi_load_custom_blocks() {
    global $mi_loaded_blocks;
    $mi_loaded_blocks = [];
    
    // Get all block directories
    $blocks_dir = get_stylesheet_directory() . '/blocks';
    
    // Check if directory exists
    if (!is_dir($blocks_dir)) {
        error_log('miBlocks: Blocks directory does not exist: ' . $blocks_dir);
        return;
    }
    
    // Get all subdirectories
    $block_folders = array_filter(glob($blocks_dir . '/*'), 'is_dir');
    
    // Debug: Log found folders
    error_log('miBlocks: Found block folders: ' . print_r(array_map('basename', $block_folders), true));
    
    // Load each block's index.php file
    foreach ($block_folders as $block_folder) {
        $index_file = $block_folder . '/index.php';
        
        if (file_exists($index_file)) {
            error_log('miBlocks: Loading block: ' . basename($block_folder));
            $mi_loaded_blocks[] = basename($block_folder);
            require_once $index_file;
        } else {
            error_log('miBlocks: No index.php found in: ' . basename($block_folder));
        }
    }
}
// Load blocks when Carbon Fields is ready to register fields
add_action('carbon_fields_register_fields', 'mi_load_custom_blocks', 5); // Priority 5 to load before block registration

/**
 * Enqueue block assets for frontend
 */
function mi_enqueue_block_assets() {
    // Get all block directories
    $blocks_dir = get_stylesheet_directory() . '/blocks';
    
    // Check if directory exists
    if (!is_dir($blocks_dir)) {
        return;
    }
    
    // Get all subdirectories
    $block_folders = array_filter(glob($blocks_dir . '/*'), 'is_dir');
    
    // Enqueue each block's assets
    foreach ($block_folders as $block_folder) {
        $block_name = basename($block_folder);
        $style_file = $block_folder . '/style.css';
        $script_file = $block_folder . '/script.js';
        
        // Enqueue style if it exists
        if (file_exists($style_file)) {
            wp_enqueue_style(
                'mi-block-' . $block_name,
                get_stylesheet_directory_uri() . '/blocks/' . $block_name . '/style.css',
                array(),
                filemtime($style_file)
            );
        }
        
        // Enqueue script if it exists
        if (file_exists($script_file)) {
            wp_enqueue_script(
                'mi-block-' . $block_name,
                get_stylesheet_directory_uri() . '/blocks/' . $block_name . '/script.js',
                array('jquery'),
                filemtime($script_file),
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'mi_enqueue_block_assets');

/**
 * Register native WordPress blocks
 * 
 * Looks for block.json files and registers them as native blocks
 */
function mi_register_native_blocks() {
    $blocks_dir = get_stylesheet_directory() . '/blocks';
    
    // Check if directory exists
    if (!is_dir($blocks_dir)) {
        error_log('mi_register_native_blocks: Blocks directory does not exist');
        return;
    }
    
    // Find all block.json files
    $block_json_files = glob($blocks_dir . '/*/block.json');
    
    error_log('mi_register_native_blocks: Found block.json files: ' . print_r($block_json_files, true));
    
    foreach ($block_json_files as $block_json) {
        $result = register_block_type(dirname($block_json));
        if (is_wp_error($result)) {
            error_log('mi_register_native_blocks: Error registering block ' . dirname($block_json) . ': ' . $result->get_error_message());
        } else {
            error_log('mi_register_native_blocks: Successfully registered block from ' . dirname($block_json));
        }
    }
}
add_action('init', 'mi_register_native_blocks');

/**
 * Load block-specific CSS files
 * This allows for visual editing via GutenVibes CSS Editor
 */
function mi_load_block_css_files() {
    $blocks_dir = get_stylesheet_directory() . '/blocks';
    
    if (!is_dir($blocks_dir)) {
        return;
    }
    
    // Scan for block directories
    $items = scandir($blocks_dir);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..' || !is_dir($blocks_dir . '/' . $item)) {
            continue;
        }
        
        // Check if this directory has a styles/block.css file
        $css_file = $blocks_dir . '/' . $item . '/styles/block.css';
        
        if (file_exists($css_file)) {
            $block_name = $item;
            $css_url = get_stylesheet_directory_uri() . '/blocks/' . $block_name . '/styles/block.css';
            
            // Enqueue the CSS file
            wp_enqueue_style(
                'miblocks-' . $block_name . '-styles',
                $css_url,
                array(), // No dependencies - true island architecture
                filemtime($css_file) // Use file modification time for cache busting
            );
            
            // Also enqueue block styles in the editor
            add_editor_style('blocks/' . $block_name . '/styles/block.css');
            
            // Check for editor-specific styles
            $editor_css_file = $blocks_dir . '/' . $item . '/styles/editor.css';
            if (file_exists($editor_css_file)) {
                add_editor_style('blocks/' . $block_name . '/styles/editor.css');
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'mi_load_block_css_files');
add_action('enqueue_block_editor_assets', 'mi_load_block_css_files');

// Remove the GutenVibes integration - no longer needed

/**
 * Force Carbon Fields blocks to use sidebar controls
 * This is a workaround for a known issue with Carbon Fields
 */
function mi_carbon_fields_force_sidebar_controls() {
    add_filter('carbon_fields_should_save_field_value', function($save, $value, $field) {
        return $save;
    }, 10, 3);
    
    // This script forces the controls to appear in the sidebar
    echo '<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            wp.data.subscribe(function() {
                setTimeout(function() {
                    var carbonBlocks = document.querySelectorAll(".wp-block-carbon-fields-block-card-loop");
                    carbonBlocks.forEach(function(block) {
                        var controls = block.querySelectorAll(".cf-container__fields");
                        controls.forEach(function(control) {
                            control.style.display = "none";
                        });
                    });
                }, 100);
            });
        });
    </script>';
}
add_action('admin_footer', 'mi_carbon_fields_force_sidebar_controls');

/**
 * Enqueue block assets for editor
 */
function mi_enqueue_block_editor_assets() {
    // Get all block directories
    $blocks_dir = get_stylesheet_directory() . '/blocks';
    
    // Check if directory exists
    if (!is_dir($blocks_dir)) {
        return;
    }
    
    // Get all subdirectories
    $block_folders = array_filter(glob($blocks_dir . '/*'), 'is_dir');
    
    // Enqueue each block's editor assets
    foreach ($block_folders as $block_folder) {
        $block_name = basename($block_folder);
        $editor_style_file = $block_folder . '/editor.css';
        
        // Enqueue editor style if it exists
        if (file_exists($editor_style_file)) {
            wp_enqueue_style(
                'mi-block-' . $block_name . '-editor',
                get_stylesheet_directory_uri() . '/blocks/' . $block_name . '/editor.css',
                array('wp-edit-blocks'),
                filemtime($editor_style_file)
            );
        }
    }
    
    // Add inline CSS to improve the Carbon Fields sidebar appearance
    $custom_css = "
        /* Force Carbon Fields to display in the sidebar */
        .block-editor-block-inspector .components-panel__body {
            display: block !important;
        }
        
        /* Hide the 'Edit as HTML' button for our blocks */
        .wp-block-carbon-fields-block-property-card-loop .block-editor-block-toolbar__slot .components-dropdown-menu,
        .wp-block-carbon-fields-block-property-card-loop .block-editor-block-contextual-toolbar .components-dropdown-menu {
            display: none;
        }
        
        /* Make the block preview cleaner */
        .wp-block-carbon-fields-block-property-card-loop {
            padding: 15px;
            background-color: #f8f9fb;
            border-radius: 8px;
            border: 1px dashed #ccc;
        }
        
        /* Style the fields in the sidebar */
        .cf-field__label {
            font-weight: 600;
            margin-bottom: 5px;
        }
        .cf-field__help {
            font-style: italic;
            opacity: 0.8;
        }
        .cf-field select,
        .cf-field input[type=text],
        .cf-field input[type=number] {
            width: 100%;
        }
        .cf-checkbox__input {
            accent-color: var(--wp-admin-theme-color);
        }
        .cf-separator {
            margin-top: 24px;
            margin-bottom: 16px;
        }
    ";
    
    wp_add_inline_style('wp-edit-blocks', $custom_css);
}
add_action('enqueue_block_editor_assets', 'mi_enqueue_block_editor_assets');

/**
 * Add custom admin body class for Carbon Fields styling
 */
function mi_admin_body_class($classes) {
    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
        if ($screen && $screen->is_block_editor()) {
            $classes .= ' mi-block-editor';
        }
    }
    return $classes;
}
add_filter('admin_body_class', 'mi_admin_body_class');

/**
 * Debug: Show loaded blocks in admin
 */
function mi_debug_loaded_blocks() {
    global $mi_loaded_blocks;
    if (!empty($mi_loaded_blocks) && current_user_can('manage_options')) {
        echo '<div class="notice notice-info"><p>Loaded miBlocks: ' . implode(', ', $mi_loaded_blocks) . '</p></div>';
    }
}
add_action('admin_notices', 'mi_debug_loaded_blocks');

/**
 * Enqueue frontend scripts for blocks
 */
function mi_enqueue_frontend_scripts() {
    // Enqueue scripts for card-loop-native block if it's used on the page
    if (has_block('miblocks/card-loop')) {
        $view_script = get_stylesheet_directory() . '/blocks/card-loop-native/build/view.js';
        
        if (file_exists($view_script)) {
            wp_enqueue_script(
                'miblocks-ajax',
                get_stylesheet_directory_uri() . '/blocks/card-loop-native/build/view.js',
                array('wp-element'),
                filemtime($view_script),
                true
            );
            
            // Localize script with AJAX data
            wp_localize_script('miblocks-ajax', 'miblocks_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('miblocks_ajax_nonce')
            ));
        }
    }
}
add_action('wp_enqueue_scripts', 'mi_enqueue_frontend_scripts');

/**
 * Calculate reading time for a post
 */
function mi_get_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Assuming 200 words per minute
    
    return $reading_time;
}

/**
 * AJAX handler for filtering properties
 */
function mi_ajax_filter_properties() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'miblocks_ajax_nonce')) {
        wp_die('Security check failed');
    }
    
    // Get filter parameters
    $filters = isset($_POST['filter']) ? $_POST['filter'] : array();
    $range_filters = isset($_POST['range']) ? $_POST['range'] : array();
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'property';
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 12;
    $card_style = isset($_POST['card_style']) ? sanitize_text_field($_POST['card_style']) : 'default';
    $columns = isset($_POST['columns']) ? intval($_POST['columns']) : 3;
    
    // Build query arguments
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'post_status' => 'publish'
    );
    
    // Add taxonomy queries if filters are set
    if (!empty($filters)) {
        $tax_query = array('relation' => 'AND');
        
        foreach ($filters as $taxonomy => $terms) {
            if (!empty($terms)) {
                $tax_query[] = array(
                    'taxonomy' => sanitize_text_field($taxonomy),
                    'field' => 'term_id',
                    'terms' => array_map('intval', $terms)
                );
            }
        }
        
        if (count($tax_query) > 1) {
            $args['tax_query'] = $tax_query;
        }
    }
    
    // Add meta queries for range filters (bedrooms, bathrooms)
    if (!empty($range_filters)) {
        $meta_query = array('relation' => 'AND');
        
        if (isset($range_filters['bedrooms']) && $range_filters['bedrooms'] > 0) {
            $meta_query[] = array(
                'key' => 'property_bedrooms',
                'value' => intval($range_filters['bedrooms']),
                'compare' => '>=',
                'type' => 'NUMERIC'
            );
        }
        
        if (isset($range_filters['bathrooms']) && $range_filters['bathrooms'] > 0) {
            $meta_query[] = array(
                'key' => 'property_bathrooms',
                'value' => intval($range_filters['bathrooms']),
                'compare' => '>=',
                'type' => 'NUMERIC'
            );
        }
        
        if (count($meta_query) > 1) {
            $args['meta_query'] = $meta_query;
        }
    }
    
    // Run query
    $query = new WP_Query($args);
    
    // Generate HTML
    ob_start();
    
    if ($query->have_posts()) {
        echo '<div class="view-grid view-grid--fixed-' . $columns . '">';
        while ($query->have_posts()) : $query->the_post();
            // Include the appropriate card template based on post type
            $card_template = get_stylesheet_directory() . '/blocks/card-loop-native/partials/card-' . $post_type . '.php';
            if (file_exists($card_template)) {
                include $card_template;
            } else {
                // Default card template
                include get_stylesheet_directory() . '/blocks/card-loop-native/partials/card-default.php';
            }
        endwhile;
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<div class="empty-state">
                <div class="empty-state__icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>
                <h3 class="empty-state__title">No results found</h3>
                <p class="empty-state__description">Try adjusting your filters or search criteria.</p>
              </div>';
    }
    
    $html = ob_get_clean();
    
    // Return JSON response
    wp_send_json_success(array(
        'html' => $html,
        'found_posts' => $query->found_posts
    ));
}
add_action('wp_ajax_filter_properties', 'mi_ajax_filter_properties');
add_action('wp_ajax_nopriv_filter_properties', 'mi_ajax_filter_properties');

// Track loaded blocks
global $mi_loaded_blocks;
$mi_loaded_blocks = [];

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

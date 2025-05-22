<?php
/**
 * Blocksy Child Theme Functions
 */

// Load Composer autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Enqueue stylesheets in the correct order
function blocksy_child_enqueue_styles() {
    // 1. First load parent theme stylesheet
    wp_enqueue_style(
        'blocksy-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme('blocksy')->get('Version')
    );
    
    // 2. Then load child theme stylesheet for any additional customizations
    wp_enqueue_style(
        'blocksy-child-style',
        get_stylesheet_uri(),
        array('blocksy-parent-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'blocksy_child_enqueue_styles');

// Carbon Fields setup - this MUST come first before any other includes
require_once get_stylesheet_directory() . '/inc/carbon-fields-setup.php';

// Include CPT and taxonomy registration
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
    // Get all block directories
    $blocks_dir = get_stylesheet_directory() . '/blocks';
    
    // Check if directory exists
    if (!is_dir($blocks_dir)) {
        return;
    }
    
    // Get all subdirectories
    $block_folders = array_filter(glob($blocks_dir . '/*'), 'is_dir');
    
    // Load each block's index.php file
    foreach ($block_folders as $block_folder) {
        $index_file = $block_folder . '/index.php';
        
        if (file_exists($index_file)) {
            require_once $index_file;
        }
    }
}
add_action('after_setup_theme', 'mi_load_custom_blocks');

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

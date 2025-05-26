<?php
/**
 * Taxonomy Auto-Discovery System
 *
 * File-based routing for WordPress taxonomies and Carbon Fields term meta
 *
 * @package CarbonBlocks
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Auto-discover and register taxonomies (config.php only)
 */
function carbon_taxonomies_register_all() {
    $taxonomies_dir = get_stylesheet_directory() . '/src/taxonomy/';
    
    if (!is_dir($taxonomies_dir)) {
        return;
    }
    
    // Get all post type assignment directories
    $post_type_dirs = glob($taxonomies_dir . '*', GLOB_ONLYDIR);
    
    foreach ($post_type_dirs as $post_type_dir) {
        $post_types_string = basename($post_type_dir);
        
        // Get taxonomy directories within each post type assignment
        $taxonomy_dirs = glob($post_type_dir . '/*', GLOB_ONLYDIR);
        
        foreach ($taxonomy_dirs as $taxonomy_dir) {
            $taxonomy_slug = basename($taxonomy_dir);
            $config_file = $taxonomy_dir . '/config.php';
            
            if (file_exists($config_file)) {
                // Parse post types from directory name
                $post_types = array_map('trim', explode(',', $post_types_string));
                
                // Set global variables for config.php to use
                global $current_taxonomy_slug, $current_post_types;
                $current_taxonomy_slug = $taxonomy_slug;
                $current_post_types = $post_types;
                
                // Include the taxonomy configuration
                require_once $config_file;
            }
        }
    }
}

/**
 * Auto-discover and register taxonomy field groups
 */
function carbon_taxonomies_register_field_groups_all() {
    $taxonomies_dir = get_stylesheet_directory() . '/src/taxonomy/';
    
    if (!is_dir($taxonomies_dir)) {
        return;
    }
    
    // Get all post type assignment directories
    $post_type_dirs = glob($taxonomies_dir . '*', GLOB_ONLYDIR);
    
    foreach ($post_type_dirs as $post_type_dir) {
        // Get taxonomy directories within each post type assignment
        $taxonomy_dirs = glob($post_type_dir . '/*', GLOB_ONLYDIR);
        
        foreach ($taxonomy_dirs as $taxonomy_dir) {
            $taxonomy_slug = basename($taxonomy_dir);
            
            // Register field groups for this taxonomy
            carbon_taxonomies_register_field_groups($taxonomy_dir, $taxonomy_slug);
        }
    }
}

/**
 * Register field groups for a taxonomy
 */
function carbon_taxonomies_register_field_groups($taxonomy_dir, $taxonomy_slug) {
    $field_groups_dir = $taxonomy_dir . '/field-groups/';
    
    if (!is_dir($field_groups_dir)) {
        return;
    }
    
    $field_group_files = glob($field_groups_dir . '*.php');
    
    foreach ($field_group_files as $field_group_file) {
        // Set global variable for field group files to use
        global $current_taxonomy_slug;
        $current_taxonomy_slug = $taxonomy_slug;
        
        // Include the field group configuration
        require_once $field_group_file;
    }
}

/**
 * Helper function to create taxonomy
 */
function carbon_create_taxonomy($slug, $post_types, $config = []) {
    $defaults = [
        'labels' => [],
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => $slug],
        'hierarchical' => false,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true
    ];
    
    $config = array_merge($defaults, $config);
    
    // Generate labels if not provided
    if (empty($config['labels'])) {
        $singular = ucfirst(str_replace(['-', '_'], ' ', $slug));
        $plural = $singular . 's';
        
        $config['labels'] = [
            'name' => $plural,
            'singular_name' => $singular,
            'search_items' => 'Search ' . $plural,
            'all_items' => 'All ' . $plural,
            'parent_item' => 'Parent ' . $singular,
            'parent_item_colon' => 'Parent ' . $singular . ':',
            'edit_item' => 'Edit ' . $singular,
            'update_item' => 'Update ' . $singular,
            'add_new_item' => 'Add New ' . $singular,
            'new_item_name' => 'New ' . $singular . ' Name',
            'menu_name' => $plural
        ];
    }
    
    register_taxonomy($slug, $post_types, $config);
}

/**
 * Helper function to create taxonomy meta field group
 */
function carbon_create_taxonomy_meta($taxonomy, $title, $fields = [], $config = []) {
    $container = Container::make('term_meta', $title)
        ->where('term_taxonomy', '=', $taxonomy);
    
    if (!empty($fields)) {
        $container->add_fields($fields);
    }
    
    return $container;
}

/**
 * Helper function to create taxonomy meta with tabs
 */
function carbon_create_taxonomy_meta_with_tabs($taxonomy, $title, $tabs = [], $config = []) {
    $container = Container::make('term_meta', $title)
        ->where('term_taxonomy', '=', $taxonomy);
    
    // Add tabs using the correct Carbon Fields syntax
    foreach ($tabs as $tab_id => $tab_config) {
        $container->add_tab($tab_config['title'], $tab_config['fields']);
    }
    
    return $container;
}

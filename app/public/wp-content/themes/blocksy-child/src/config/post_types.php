<?php
/**
 * Post Types Auto-Discovery System
 *
 * File-based routing for Carbon Fields post types and meta fields
 *
 * @package CarbonBlocks
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Auto-discover and register post types (config.php only)
 */
function carbon_post_types_register_all() {
    $post_types_dir = get_stylesheet_directory() . '/src/post-types/';
    
    if (!is_dir($post_types_dir)) {
        return;
    }
    
    // Get all post type directories
    $post_type_dirs = glob($post_types_dir . '*', GLOB_ONLYDIR);
    
    foreach ($post_type_dirs as $post_type_dir) {
        $post_type_slug = basename($post_type_dir);
        $config_file = $post_type_dir . '/config.php';
        
        if (file_exists($config_file)) {
            // Include the post type configuration
            require_once $config_file;
        }
    }
}

/**
 * Auto-discover and register post type field groups
 */
function carbon_post_types_register_field_groups_all() {
    $post_types_dir = get_stylesheet_directory() . '/src/post-types/';
    
    if (!is_dir($post_types_dir)) {
        return;
    }
    
    // Get all post type directories
    $post_type_dirs = glob($post_types_dir . '*', GLOB_ONLYDIR);
    
    foreach ($post_type_dirs as $post_type_dir) {
        $post_type_slug = basename($post_type_dir);
        
        // Register field groups for this post type
        carbon_post_types_register_field_groups($post_type_dir, $post_type_slug);
    }
}

/**
 * Register field groups for a post type
 */
function carbon_post_types_register_field_groups($post_type_dir, $post_type_slug) {
    $field_groups_dir = $post_type_dir . '/field-groups/';
    
    if (!is_dir($field_groups_dir)) {
        return;
    }
    
    $field_group_files = glob($field_groups_dir . '*.php');
    
    foreach ($field_group_files as $field_group_file) {
        // Include the field group configuration
        require_once $field_group_file;
    }
}

/**
 * Helper function to create post type
 */
function carbon_create_post_type($slug, $config = []) {
    $defaults = [
        'labels' => [],
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => ['slug' => $slug],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-admin-post',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt']
    ];
    
    $config = array_merge($defaults, $config);
    
    // Generate labels if not provided
    if (empty($config['labels'])) {
        $singular = ucfirst(str_replace(['-', '_'], ' ', $slug));
        $plural = $singular . 's';
        
        $config['labels'] = [
            'name' => $plural,
            'singular_name' => $singular,
            'add_new' => 'Add New',
            'add_new_item' => 'Add New ' . $singular,
            'edit_item' => 'Edit ' . $singular,
            'new_item' => 'New ' . $singular,
            'view_item' => 'View ' . $singular,
            'search_items' => 'Search ' . $plural,
            'not_found' => 'No ' . strtolower($plural) . ' found',
            'not_found_in_trash' => 'No ' . strtolower($plural) . ' found in trash',
            'all_items' => 'All ' . $plural,
            'menu_name' => $plural,
            'name_admin_bar' => $singular
        ];
    }
    
    register_post_type($slug, $config);
}

/**
 * Helper function to create post meta field group
 */
function carbon_create_post_meta($post_type, $title, $fields = [], $config = []) {
    $container = Container::make('post_meta', $title)
        ->where('post_type', '=', $post_type);
    
    // Apply additional configuration
    if (isset($config['context'])) {
        $container->set_context($config['context']);
    }
    
    if (isset($config['priority'])) {
        $container->set_priority($config['priority']);
    }
    
    if (!empty($fields)) {
        $container->add_fields($fields);
    }
    
    return $container;
}

/**
 * Helper function to create post meta with tabs
 */
function carbon_create_post_meta_with_tabs($post_type, $title, $tabs = [], $config = []) {
    $container = Container::make('post_meta', $title)
        ->where('post_type', '=', $post_type);
    
    // Apply additional configuration
    if (isset($config['context'])) {
        $container->set_context($config['context']);
    }
    
    if (isset($config['priority'])) {
        $container->set_priority($config['priority']);
    }
    
    // Add tabs using the correct Carbon Fields syntax
    foreach ($tabs as $tab_id => $tab_config) {
        $container->add_tab($tab_config['title'], $tab_config['fields']);
    }
    
    return $container;
}
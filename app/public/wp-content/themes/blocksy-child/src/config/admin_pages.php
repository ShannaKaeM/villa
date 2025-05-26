<?php
/**
 * Admin Pages Auto-Discovery System
 *
 * File-based routing for Carbon Fields admin pages
 *
 * @package CarbonBlocks
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Auto-discover and register admin pages
 */
function carbon_admin_pages_register_all() {
    $admin_pages_dir = get_stylesheet_directory() . '/src/admin-pages/';
    
    if (!is_dir($admin_pages_dir)) {
        return;
    }
    
    // Get all page directories
    $page_dirs = glob($admin_pages_dir . '*', GLOB_ONLYDIR);
    
    foreach ($page_dirs as $page_dir) {
        $page_slug = basename($page_dir);
        $page_file = $page_dir . '/page.php';
        
        if (file_exists($page_file)) {
            // Include the page configuration
            require_once $page_file;
        }
        
        // Check for nested sub-pages
        carbon_admin_pages_register_subpages($page_dir, $page_slug);
    }
}

/**
 * Register nested sub-pages
 */
function carbon_admin_pages_register_subpages($parent_dir, $parent_slug) {
    $subdirs = glob($parent_dir . '/*', GLOB_ONLYDIR);
    
    foreach ($subdirs as $subdir) {
        $sub_slug = basename($subdir);
        $sub_page_file = $subdir . '/page.php';
        
        if (file_exists($sub_page_file)) {
            // Include the sub-page configuration
            require_once $sub_page_file;
        }
        
        // Recursively check for more nested pages
        carbon_admin_pages_register_subpages($subdir, $parent_slug . '/' . $sub_slug);
    }
}

/**
 * Helper function to create admin page
 */
function carbon_create_admin_page($config) {
    $defaults = [
        'type' => 'page',
        'parent' => null,
        'title' => 'Admin Page',
        'menu_title' => null,
        'capability' => 'manage_options',
        'slug' => 'carbon-admin-page',
        'icon' => 'dashicons-admin-generic',
        'position' => null,
        'fields' => []
    ];
    
    $config = array_merge($defaults, $config);
    
    if (!$config['menu_title']) {
        $config['menu_title'] = $config['title'];
    }
    
    $container = Container::make('theme_options', $config['title'])
        ->set_page_menu_title($config['menu_title']);
    
    if ($config['position']) {
        $container->set_page_menu_position($config['position']);
    }
    
    if ($config['parent']) {
        $container->set_page_parent($config['parent']);
    } else {
        $container->set_icon($config['icon']);
    }
    
    if (!empty($config['fields'])) {
        $container->add_fields($config['fields']);
    }
    
    return $container;
}
<?php
/**
 * Villa Community Navigation Setup
 * 
 * Creates and manages the main navigation menu
 * 
 * @package Villa Community
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create default navigation menu on theme activation
 */
function villa_create_default_menu() {
    // Check if menu already exists
    $menu_name = 'Main Navigation';
    $menu_exists = wp_get_nav_menu_object($menu_name);
    
    if (!$menu_exists) {
        // Create the menu
        $menu_id = wp_create_nav_menu($menu_name);
        
        if (!is_wp_error($menu_id)) {
            // Add Home page
            wp_update_nav_menu_item($menu_id, 0, array(
                'menu-item-title' => 'Home',
                'menu-item-url' => home_url('/'),
                'menu-item-status' => 'publish',
                'menu-item-type' => 'custom'
            ));
            
            // Add Properties page (check if it exists first)
            $properties_page = get_page_by_path('properties');
            if ($properties_page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => 'Properties',
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $properties_page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
            } else {
                // Create properties page if it doesn't exist
                $properties_page_id = wp_insert_post(array(
                    'post_title' => 'Properties',
                    'post_content' => '[villa_properties]',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => 'properties'
                ));
                
                if ($properties_page_id) {
                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' => 'Properties',
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $properties_page_id,
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish'
                    ));
                }
            }
            
            // Add Test page
            $test_page = get_page_by_path('test');
            if ($test_page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => 'Test',
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $test_page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
            } else {
                // Create test page if it doesn't exist
                $test_page_id = wp_insert_post(array(
                    'post_title' => 'Test',
                    'post_content' => '<h1>Test Page</h1><p>This is a test page for navigation.</p>',
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => 'test'
                ));
                
                if ($test_page_id) {
                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' => 'Test',
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $test_page_id,
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish'
                    ));
                }
            }
            
            // Assign menu to primary location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
}

// Run on admin init to ensure WordPress is fully loaded
add_action('admin_init', 'villa_create_default_menu');

/**
 * Add dashboard link to menu for logged-in users
 */
function villa_add_dashboard_to_menu($items, $args) {
    if ($args->theme_location == 'primary' && is_user_logged_in()) {
        // Add Dashboard link before logout
        $dashboard_link = '<li class="menu-item menu-item-dashboard"><a href="' . home_url('/dashboard/') . '">Dashboard</a></li>';
        
        // Find position to insert (before logout if it exists)
        if (strpos($items, 'Logout') !== false) {
            $items = str_replace('<li class="menu-item"><a href="' . wp_logout_url() . '">Logout</a></li>', 
                               $dashboard_link . '<li class="menu-item"><a href="' . wp_logout_url() . '">Logout</a></li>', 
                               $items);
        } else {
            $items .= $dashboard_link;
        }
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'villa_add_dashboard_to_menu', 10, 2);

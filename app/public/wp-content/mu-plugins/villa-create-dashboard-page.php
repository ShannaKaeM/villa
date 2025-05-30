<?php
/**
 * Create Dashboard Page - One-time script to create the dashboard page
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create the dashboard page if it doesn't exist
 */
function villa_create_dashboard_page() {
    // Check if dashboard page already exists
    $existing_page = get_page_by_path('dashboard');
    
    if (!$existing_page) {
        $page_data = array(
            'post_title' => 'Dashboard',
            'post_content' => '[villa_dashboard]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'dashboard',
            'post_author' => 1,
            'menu_order' => 0
        );
        
        $page_id = wp_insert_post($page_data);
        
        if ($page_id && !is_wp_error($page_id)) {
            // Set page template if needed
            update_post_meta($page_id, '_wp_page_template', 'default');
            
            // Add to menu if primary menu exists
            $menu_name = 'primary';
            $menu = wp_get_nav_menu_object($menu_name);
            
            if ($menu) {
                wp_update_nav_menu_item($menu->term_id, 0, array(
                    'menu-item-title' => 'Dashboard',
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page_id,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
            }
            
            return $page_id;
        }
    }
    
    return false;
}

// Auto-create the page on plugin activation
add_action('init', function() {
    // Only run once
    if (!get_option('villa_dashboard_page_created')) {
        $page_id = villa_create_dashboard_page();
        if ($page_id) {
            update_option('villa_dashboard_page_created', true);
            
            // Add admin notice
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible">';
                echo '<p><strong>Villa Dashboard:</strong> Dashboard page created successfully! ';
                echo '<a href="' . get_permalink(get_page_by_path('dashboard')) . '" target="_blank">View Dashboard</a></p>';
                echo '</div>';
            });
        }
    }
});

// Add admin menu item to manually create page if needed
add_action('admin_menu', function() {
    add_management_page(
        'Create Dashboard Page',
        'Create Dashboard Page',
        'manage_options',
        'villa-create-dashboard',
        'villa_create_dashboard_admin_page'
    );
});

function villa_create_dashboard_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    echo '<div class="wrap">';
    echo '<h1>Create Dashboard Page</h1>';
    
    $existing_page = get_page_by_path('dashboard');
    
    if ($existing_page) {
        echo '<div class="notice notice-info">';
        echo '<p><strong>Dashboard page already exists!</strong></p>';
        echo '<p><a href="' . get_permalink($existing_page) . '" target="_blank" class="button button-primary">View Dashboard</a> ';
        echo '<a href="' . admin_url('post.php?post=' . $existing_page->ID . '&action=edit') . '" class="button">Edit Page</a></p>';
        echo '</div>';
    } else {
        if (isset($_POST['create_page']) && wp_verify_nonce($_POST['_wpnonce'], 'create_dashboard_page')) {
            $page_id = villa_create_dashboard_page();
            if ($page_id) {
                echo '<div class="notice notice-success">';
                echo '<p><strong>Success!</strong> Dashboard page created.</p>';
                echo '<p><a href="' . get_permalink($page_id) . '" target="_blank" class="button button-primary">View Dashboard</a> ';
                echo '<a href="' . admin_url('post.php?post=' . $page_id . '&action=edit') . '" class="button">Edit Page</a></p>';
                echo '</div>';
            } else {
                echo '<div class="notice notice-error">';
                echo '<p><strong>Error:</strong> Could not create dashboard page.</p>';
                echo '</div>';
            }
        } else {
            echo '<p>Create a new dashboard page with the [villa_dashboard] shortcode.</p>';
            echo '<form method="post">';
            wp_nonce_field('create_dashboard_page');
            echo '<p><input type="submit" name="create_page" class="button button-primary" value="Create Dashboard Page" /></p>';
            echo '</form>';
        }
    }
    
    echo '</div>';
}

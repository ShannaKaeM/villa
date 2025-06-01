<?php
/**
 * Simple setup script to make admin user an owner with a property
 * Run this once to set up admin for testing
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Setup admin as owner with a property
 */
function villa_setup_admin_as_owner() {
    // Get current admin user (assuming user ID 1 is admin)
    $admin_user_id = 1;
    $admin_user = get_user_by('ID', $admin_user_id);
    
    if (!$admin_user) {
        return false;
    }
    
    // Create or update user profile with owner role
    $existing_profile = villa_get_user_profile($admin_user_id);
    
    if ($existing_profile) {
        // Update existing profile
        $profile_id = $existing_profile->ID;
        update_post_meta($profile_id, 'profile_villa_roles', array('owner' => true, 'admin' => true));
    } else {
        // Create new profile
        $profile_data = array(
            'post_title' => 'Admin User Profile',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'user_profile',
            'post_author' => $admin_user_id
        );
        
        $profile_id = wp_insert_post($profile_data);
        
        if ($profile_id && !is_wp_error($profile_id)) {
            update_post_meta($profile_id, 'profile_user_id', $admin_user_id);
            update_post_meta($profile_id, 'profile_villa_roles', array('owner' => true, 'admin' => true));
            update_post_meta($profile_id, 'profile_villa_role', 'owner'); // Backward compatibility
        }
    }
    
    // Create a property for the admin
    $property_data = array(
        'post_title' => 'Admin Test Property - 123 Villa Lane',
        'post_content' => 'This is a test property for the admin user to test the property management system.',
        'post_status' => 'publish',
        'post_type' => 'property',
        'post_author' => 1 // System/admin creates property, but ownership is tracked separately
    );
    
    $property_id = wp_insert_post($property_data);
    
    if ($property_id && !is_wp_error($property_id)) {
        // Add property meta fields
        update_post_meta($property_id, 'property_address', '123 Villa Lane, Villa Community');
        update_post_meta($property_id, 'property_type', 'single_family');
        update_post_meta($property_id, 'property_bedrooms', 3);
        update_post_meta($property_id, 'property_bathrooms', 2.5);
        update_post_meta($property_id, 'property_square_feet', 2200);
        update_post_meta($property_id, 'property_listing_status', 'not_listed');
        update_post_meta($property_id, 'property_listing_price', 0);
        
        // Set property owners (this is the key part - assign admin as owner)
        update_post_meta($property_id, 'property_owners', array($admin_user_id));
        
        return $property_id;
    }
    
    return false;
}

// Helper function to get user profile (if not already defined)
if (!function_exists('villa_get_user_profile')) {
    function villa_get_user_profile($user_id) {
        $profiles = get_posts(array(
            'post_type' => 'user_profile',
            'meta_query' => array(
                array(
                    'key' => 'profile_user_id',
                    'value' => $user_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 1
        ));
        
        return !empty($profiles) ? $profiles[0] : null;
    }
}

// Run this setup automatically on admin init (only once)
add_action('admin_init', function() {
    if (current_user_can('administrator') && !get_option('villa_admin_owner_setup_done')) {
        $property_id = villa_setup_admin_as_owner();
        if ($property_id) {
            update_option('villa_admin_owner_setup_done', true);
            add_action('admin_notices', function() use ($property_id) {
                echo '<div class="notice notice-success is-dismissible">';
                echo '<p><strong>Villa Setup Complete!</strong> You are now set up as an owner with a test property (ID: ' . $property_id . '). ';
                echo '<a href="' . get_permalink(get_page_by_path('dashboard')) . '" target="_blank">View Dashboard</a></p>';
                echo '</div>';
            });
        }
    }
});

// Add manual trigger button in admin
add_action('admin_menu', function() {
    add_management_page(
        'Villa Owner Setup',
        'Villa Owner Setup',
        'manage_options',
        'villa-owner-setup',
        'villa_owner_setup_page'
    );
});

function villa_owner_setup_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    echo '<div class="wrap">';
    echo '<h1>Villa Owner Setup</h1>';
    
    if (isset($_POST['setup_owner']) && wp_verify_nonce($_POST['_wpnonce'], 'setup_owner')) {
        $property_id = villa_setup_admin_as_owner();
        if ($property_id) {
            echo '<div class="notice notice-success">';
            echo '<p><strong>Success!</strong> You are now set up as an owner with property ID: ' . $property_id . '</p>';
            echo '<p><a href="' . get_permalink(get_page_by_path('dashboard')) . '" target="_blank" class="button button-primary">View Dashboard</a></p>';
            echo '</div>';
        } else {
            echo '<div class="notice notice-error">';
            echo '<p><strong>Error:</strong> Could not create property.</p>';
            echo '</div>';
        }
    } else {
        echo '<p>This will set you up as an owner with a test property.</p>';
        echo '<form method="post">';
        wp_nonce_field('setup_owner');
        echo '<p><input type="submit" name="setup_owner" class="button button-primary" value="Setup Admin as Owner" /></p>';
        echo '</form>';
    }
    
    echo '</div>';
}

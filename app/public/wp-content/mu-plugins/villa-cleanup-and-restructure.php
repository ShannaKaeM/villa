<?php
/**
 * Villa Community Cleanup and Restructure
 * 1. Remove duplicate users
 * 2. Move custom fields from user meta to User Profile CPT
 * 3. Fix avatar images
 * DELETE THIS FILE AFTER RUNNING ONCE
 * 
 * @package VillaCommunity
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Comprehensive cleanup and restructure
 */
function villa_cleanup_and_restructure() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $results = array(
        'duplicates_removed' => 0,
        'profiles_updated' => 0,
        'avatars_fixed' => 0,
        'errors' => array()
    );
    
    // Step 1: Remove duplicate users
    $results = villa_remove_duplicate_users($results);
    
    // Step 2: Move user meta to CPT fields
    $results = villa_move_fields_to_cpt($results);
    
    // Step 3: Fix avatars
    $results = villa_fix_avatars($results);
    
    // Add admin notice
    add_action('admin_notices', function() use ($results) {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>Villa Community Cleanup & Restructure Complete!</strong></p>';
        echo '<ul>';
        echo '<li>Removed ' . $results['duplicates_removed'] . ' duplicate users</li>';
        echo '<li>Updated ' . $results['profiles_updated'] . ' user profiles</li>';
        echo '<li>Fixed ' . $results['avatars_fixed'] . ' avatars</li>';
        if (!empty($results['errors'])) {
            echo '<li style="color: red;">Errors: ' . implode(', ', $results['errors']) . '</li>';
        }
        echo '</ul>';
        echo '<p>You can now delete the villa-cleanup-and-restructure.php file.</p>';
        echo '</div>';
    });
    
    // Set flag to prevent running again
    update_option('villa_restructure_completed', true);
    
    return $results;
}

/**
 * Remove duplicate users
 */
function villa_remove_duplicate_users($results) {
    // Get all users with villa roles
    $villa_users = get_users(array(
        'meta_key' => 'user_villa_role',
        'meta_compare' => 'EXISTS'
    ));
    
    $seen_names = array();
    
    foreach ($villa_users as $user) {
        $display_name = strtolower(trim($user->display_name));
        
        // Check for duplicates by display name
        if (in_array($display_name, $seen_names)) {
            // This is a duplicate - remove it
            $profile_id = get_user_meta($user->ID, 'profile_post_id', true);
            
            // Delete associated profile post
            if ($profile_id) {
                wp_delete_post($profile_id, true);
            }
            
            // Delete user
            if (wp_delete_user($user->ID)) {
                $results['duplicates_removed']++;
                error_log("Villa Cleanup: Removed duplicate user - {$user->display_name} (ID: {$user->ID})");
            } else {
                $results['errors'][] = "Failed to delete user {$user->display_name}";
            }
            continue;
        }
        
        $seen_names[] = $display_name;
    }
    
    return $results;
}

/**
 * Move user meta fields to CPT fields
 */
function villa_move_fields_to_cpt($results) {
    // Get all remaining users with villa roles
    $villa_users = get_users(array(
        'meta_key' => 'user_villa_role',
        'meta_compare' => 'EXISTS'
    ));
    
    foreach ($villa_users as $user) {
        $profile_id = get_user_meta($user->ID, 'profile_post_id', true);
        
        if (!$profile_id) {
            // Create profile if it doesn't exist
            $profile_id = wp_insert_post(array(
                'post_title' => $user->display_name,
                'post_content' => 'Welcome to Villa Community!',
                'post_status' => 'publish',
                'post_type' => 'user_profile',
                'post_author' => $user->ID,
            ));
            
            if ($profile_id && !is_wp_error($profile_id)) {
                update_post_meta($profile_id, 'linked_user_id', $user->ID);
                update_user_meta($user->ID, 'profile_post_id', $profile_id);
            } else {
                $results['errors'][] = "Failed to create profile for {$user->display_name}";
                continue;
            }
        }
        
        // Map of user meta to CPT meta
        $field_mapping = array(
            'user_villa_role' => 'profile_villa_role',
            'user_villa_address' => 'profile_villa_address',
            'user_phone' => 'profile_phone',
            'user_emergency_contact' => 'profile_emergency_contact',
            'user_emergency_phone' => 'profile_emergency_phone',
            'user_move_in_date' => 'profile_move_in_date',
            'user_property_interest' => 'profile_property_interest',
            'user_company' => 'profile_company',
            'user_job_title' => 'profile_job_title',
            'user_business_type' => 'profile_business_type',
            'user_community_involvement' => 'profile_community_involvement',
            'user_committees' => 'profile_committees',
            'user_interests' => 'profile_interests',
            'user_bio' => 'profile_bio',
            'user_profile_visibility' => 'profile_visibility',
            'user_show_contact' => 'profile_show_contact',
            'user_website' => 'profile_website',
            'user_linkedin' => 'profile_linkedin',
            'user_facebook' => 'profile_facebook',
            'user_instagram' => 'profile_instagram',
            'user_twitter' => 'profile_twitter'
        );
        
        // Move data from user meta to post meta
        foreach ($field_mapping as $user_meta_key => $post_meta_key) {
            $value = get_user_meta($user->ID, $user_meta_key, true);
            if (!empty($value)) {
                update_post_meta($profile_id, $post_meta_key, $value);
                // Optionally remove from user meta to clean up
                // delete_user_meta($user->ID, $user_meta_key);
            }
        }
        
        $results['profiles_updated']++;
    }
    
    return $results;
}

/**
 * Fix avatar images
 */
function villa_fix_avatars($results) {
    $correct_avatar_id = villa_get_correct_avatar_id();
    
    if (!$correct_avatar_id) {
        $results['errors'][] = "Could not find or create correct avatar image";
        return $results;
    }
    
    // Get all user profiles
    $profiles = get_posts(array(
        'post_type' => 'user_profile',
        'post_status' => 'publish',
        'posts_per_page' => -1
    ));
    
    foreach ($profiles as $profile) {
        $current_thumbnail = get_post_thumbnail_id($profile->ID);
        if (!$current_thumbnail || $current_thumbnail != $correct_avatar_id) {
            if (set_post_thumbnail($profile->ID, $correct_avatar_id)) {
                $results['avatars_fixed']++;
            }
        }
    }
    
    return $results;
}

/**
 * Get the correct avatar image ID
 */
function villa_get_correct_avatar_id() {
    $avatar_path = '/wp-content/themes/miGV/miDocs/SITE DATA/Images/Branding/Avatars/avatar-secondary.png';
    $upload_dir = wp_upload_dir();
    
    // Check if avatar already exists in media library
    $existing_avatar = get_posts(array(
        'post_type' => 'attachment',
        'meta_query' => array(
            array(
                'key' => '_wp_attached_file',
                'value' => 'villa-community-avatar.png',
                'compare' => 'LIKE'
            )
        ),
        'posts_per_page' => 1
    ));
    
    if ($existing_avatar) {
        return $existing_avatar[0]->ID;
    }
    
    // Upload the correct avatar to media library
    $avatar_full_path = ABSPATH . 'wp-content/themes/miGV/miDocs/SITE DATA/Images/Branding/Avatars/avatar-secondary.png';
    
    if (!file_exists($avatar_full_path)) {
        error_log("Villa Cleanup: Avatar file not found at {$avatar_full_path}");
        return false;
    }
    
    $file_array = array(
        'name' => 'villa-community-avatar.png',
        'tmp_name' => $avatar_full_path
    );
    
    // Copy file to temp location for upload
    $temp_file = wp_tempnam('villa-community-avatar.png');
    if (!copy($avatar_full_path, $temp_file)) {
        return false;
    }
    $file_array['tmp_name'] = $temp_file;
    
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    $attachment_id = media_handle_sideload($file_array, 0, 'Villa Community Default Avatar');
    
    if (is_wp_error($attachment_id)) {
        error_log("Villa Cleanup: Failed to upload avatar - " . $attachment_id->get_error_message());
        return false;
    }
    
    return $attachment_id;
}

/**
 * Run cleanup on admin init (only once)
 */
function villa_run_restructure_once() {
    if (is_admin() && current_user_can('manage_options')) {
        if (!get_option('villa_restructure_completed')) {
            villa_cleanup_and_restructure();
        }
    }
}
add_action('admin_init', 'villa_run_restructure_once');

/**
 * Add cleanup button to admin menu for manual trigger
 */
function villa_add_restructure_admin_menu() {
    if (current_user_can('manage_options') && !get_option('villa_restructure_completed')) {
        add_management_page(
            'Villa Restructure',
            'Villa Restructure',
            'manage_options',
            'villa-restructure',
            'villa_restructure_admin_page'
        );
    }
}
add_action('admin_menu', 'villa_add_restructure_admin_menu');

/**
 * Admin page for manual cleanup
 */
function villa_restructure_admin_page() {
    if (isset($_POST['run_restructure']) && wp_verify_nonce($_POST['restructure_nonce'], 'villa_restructure')) {
        villa_cleanup_and_restructure();
    }
    
    ?>
    <div class="wrap">
        <h1>Villa Community Cleanup & Restructure</h1>
        <div class="card">
            <h2>Remove Duplicates & Move Fields to CPT</h2>
            <p>This will:</p>
            <ul>
                <li>Remove duplicate users (keeping the first instance of each)</li>
                <li>Move all custom fields from WP User meta to User Profile CPT</li>
                <li>Fix avatar images to use the correct avatar-secondary.png</li>
                <li>Clean up the database structure</li>
            </ul>
            <form method="post">
                <?php wp_nonce_field('villa_restructure', 'restructure_nonce'); ?>
                <p>
                    <input type="submit" name="run_restructure" class="button button-primary" 
                           value="Run Cleanup & Restructure" 
                           onclick="return confirm('Are you sure? This will permanently restructure your user data.');">
                </p>
            </form>
        </div>
    </div>
    <?php
}

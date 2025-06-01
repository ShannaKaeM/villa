<?php
/**
 * Fix User Roles - One-time script to ensure all users have proper WordPress roles
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add admin menu item
add_action('admin_menu', 'villa_add_fix_roles_menu');

function villa_add_fix_roles_menu() {
    add_management_page(
        'Fix User Roles',
        'Fix User Roles',
        'manage_options',
        'villa-fix-roles',
        'villa_fix_roles_page'
    );
}

function villa_fix_roles_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    echo '<div class="wrap">';
    echo '<h1>Fix User Roles</h1>';

    if (isset($_POST['fix_roles']) && wp_verify_nonce($_POST['_wpnonce'], 'fix_user_roles')) {
        villa_fix_all_user_roles();
    } else {
        villa_display_user_roles_status();
    }

    echo '</div>';
}

function villa_display_user_roles_status() {
    echo '<h2>Current User Roles Status</h2>';
    
    $users = get_users(array('number' => -1));
    $users_without_roles = array();
    $users_with_roles = array();
    
    foreach ($users as $user) {
        if (empty($user->roles)) {
            $users_without_roles[] = $user;
        } else {
            $users_with_roles[] = $user;
        }
    }
    
    echo '<h3>Users WITH Roles (' . count($users_with_roles) . ')</h3>';
    if (!empty($users_with_roles)) {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>ID</th><th>Username</th><th>Display Name</th><th>Email</th><th>Roles</th></tr></thead>';
        echo '<tbody>';
        foreach ($users_with_roles as $user) {
            echo '<tr>';
            echo '<td>' . $user->ID . '</td>';
            echo '<td>' . esc_html($user->user_login) . '</td>';
            echo '<td>' . esc_html($user->display_name) . '</td>';
            echo '<td>' . esc_html($user->user_email) . '</td>';
            echo '<td>' . esc_html(implode(', ', $user->roles)) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>All users have roles assigned.</p>';
    }
    
    echo '<h3>Users WITHOUT Roles (' . count($users_without_roles) . ')</h3>';
    if (!empty($users_without_roles)) {
        echo '<div class="notice notice-warning"><p><strong>Warning:</strong> The following users do not have any WordPress roles assigned:</p></div>';
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>ID</th><th>Username</th><th>Display Name</th><th>Email</th><th>Villa Role</th></tr></thead>';
        echo '<tbody>';
        foreach ($users_without_roles as $user) {
            // Try to get villa role from user profile CPT
            $profile = villa_get_user_profile($user->ID);
            $villa_roles = array();
            if ($profile) {
                // Check both old single role and new multiple roles
                $single_role = get_post_meta($profile->ID, 'profile_villa_role', true);
                $multiple_roles = get_post_meta($profile->ID, 'profile_villa_roles', true);
                
                if ($single_role) {
                    $villa_roles[] = $single_role;
                }
                if ($multiple_roles && is_array($multiple_roles)) {
                    $villa_roles = array_merge($villa_roles, array_keys(array_filter($multiple_roles)));
                }
                $villa_roles = array_unique($villa_roles);
            }
            
            echo '<tr>';
            echo '<td>' . $user->ID . '</td>';
            echo '<td>' . esc_html($user->user_login) . '</td>';
            echo '<td>' . esc_html($user->display_name) . '</td>';
            echo '<td>' . esc_html($user->user_email) . '</td>';
            echo '<td>' . esc_html(implode(', ', $villa_roles)) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        
        echo '<form method="post">';
        wp_nonce_field('fix_user_roles');
        echo '<p><input type="submit" name="fix_roles" class="button button-primary" value="Fix All User Roles" /></p>';
        echo '<p><em>This will assign appropriate WordPress roles based on villa roles and user data.</em></p>';
        echo '</form>';
    } else {
        echo '<p>All users have roles assigned.</p>';
    }
}

function villa_fix_all_user_roles() {
    $users = get_users(array('number' => -1));
    $fixed_count = 0;
    $errors = array();
    
    foreach ($users as $user) {
        if (empty($user->roles)) {
            $wp_roles = villa_determine_wp_roles_for_user($user);
            if ($wp_roles) {
                $user_obj = new WP_User($user->ID);
                $user_obj->set_role($wp_roles['primary']);
                foreach ($wp_roles['additional'] as $role) {
                    $user_obj->add_role($role);
                }
                $fixed_count++;
                echo '<p>✓ Fixed role for user: ' . esc_html($user->display_name) . ' (assigned: ' . esc_html($wp_roles['primary']) . ')</p>';
            } else {
                $errors[] = 'Could not determine role for user: ' . $user->display_name;
            }
        }
    }
    
    echo '<div class="notice notice-success"><p><strong>Success!</strong> Fixed roles for ' . $fixed_count . ' users.</p></div>';
    
    if (!empty($errors)) {
        echo '<div class="notice notice-warning"><p><strong>Warnings:</strong></p><ul>';
        foreach ($errors as $error) {
            echo '<li>' . esc_html($error) . '</li>';
        }
        echo '</ul></div>';
    }
}

function villa_determine_wp_roles_for_user($user) {
    // Try to get villa role from user profile CPT
    $profile = villa_get_user_profile($user->ID);
    $villa_roles = array();
    if ($profile) {
        // Check both old single role and new multiple roles
        $single_role = get_post_meta($profile->ID, 'profile_villa_role', true);
        $multiple_roles = get_post_meta($profile->ID, 'profile_villa_roles', true);
        
        if ($single_role) {
            $villa_roles[] = $single_role;
        }
        if ($multiple_roles && is_array($multiple_roles)) {
            $villa_roles = array_merge($villa_roles, array_keys(array_filter($multiple_roles)));
        }
        $villa_roles = array_unique($villa_roles);
    }
    
    // Use the actual custom villa roles that are defined in the system
    $valid_villa_roles = array(
        'owner',
        'bod', 
        'dov',
        'committee',
        'staff',
        'partner',
        'community_member'
    );
    
    // If we have a valid villa role, use it directly as the WordPress role
    if ($villa_roles && !empty($villa_roles)) {
        $primary_role = $villa_roles[0];
        $additional_roles = array_slice($villa_roles, 1);
        return array('primary' => $primary_role, 'additional' => $additional_roles);
    }
    
    // Check if user is admin based on email or username
    if (strpos($user->user_email, 'admin') !== false || 
        strpos($user->user_login, 'admin') !== false ||
        $user->ID == 1) {
        return array('primary' => 'owner', 'additional' => array()); // Use owner role instead of administrator
    }
    
    // Default to community_member for regular users
    return array('primary' => 'community_member', 'additional' => array());
}

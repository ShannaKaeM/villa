<?php
/**
 * Villa Frontend Dashboard System
 * A comprehensive user dashboard for property management, support tickets, and community features
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include all dashboard modules
require_once plugin_dir_path(__FILE__) . 'villa-dashboard-properties.php';
require_once plugin_dir_path(__FILE__) . 'villa-dashboard-tickets.php';
require_once plugin_dir_path(__FILE__) . 'villa-dashboard-announcements.php';
require_once plugin_dir_path(__FILE__) . 'villa-dashboard-additional.php';
require_once plugin_dir_path(__FILE__) . 'villa-dashboard-post-types.php';
require_once plugin_dir_path(__FILE__) . 'villa-groups-cpt.php';

/**
 * Enqueue dashboard styles and scripts
 */
function villa_dashboard_enqueue_assets() {
    // Only enqueue on pages with the dashboard shortcode
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'villa_dashboard')) {
        wp_enqueue_style(
            'villa-dashboard-styles',
            plugin_dir_url(__FILE__) . 'villa-dashboard-styles.css',
            array(),
            '1.0.0'
        );
        
        wp_enqueue_script(
            'villa-dashboard-scripts',
            plugin_dir_url(__FILE__) . 'villa-dashboard-scripts.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('villa-dashboard-scripts', 'villa_dashboard_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('villa_dashboard_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'villa_dashboard_enqueue_assets');

/**
 * Main dashboard shortcode
 */
function villa_dashboard_shortcode($atts) {
    if (!is_user_logged_in()) {
        return '<div class="villa-dashboard-login">Please <a href="' . wp_login_url(get_permalink()) . '">log in</a> to access your dashboard.</div>';
    }
    
    $user = wp_get_current_user();
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    // For admin users, create test profile data if it doesn't exist
    if (current_user_can('administrator') && empty($user_roles)) {
        villa_create_test_profile_for_admin($user->ID);
        $user_roles = villa_get_user_villa_roles($user->ID);
    }
    
    // Allow admin users to bypass role check for testing
    if (empty($user_roles) && !current_user_can('administrator')) {
        return '<div class="villa-dashboard-no-access">Your account does not have access to the dashboard. Please contact an administrator.</div>';
    }
    
    ob_start();
    villa_render_dashboard($user, $user_roles);
    return ob_get_clean();
}
add_shortcode('villa_dashboard', 'villa_dashboard_shortcode');

/**
 * Render the main dashboard interface
 */
function villa_render_dashboard($user, $user_roles) {
    $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'properties';
    
    ?>
    <div class="villa-dashboard">
        <!-- Dashboard Navigation -->
        <div class="dashboard-nav">
            <div class="dashboard-tabs">
                <?php if (villa_user_can_access_properties($user_roles)): ?>
                    <a href="?tab=properties" class="dashboard-tab <?php echo $current_tab === 'properties' ? 'active' : ''; ?>" data-tab="properties">
                        Properties
                    </a>
                <?php endif; ?>
                
                <?php if (villa_user_can_access_tickets($user_roles)): ?>
                    <a href="?tab=tickets" class="dashboard-tab <?php echo $current_tab === 'tickets' ? 'active' : ''; ?>" data-tab="tickets">
                        Support Tickets
                    </a>
                <?php endif; ?>
                
                <?php if (villa_user_can_access_announcements($user_roles)): ?>
                    <a href="?tab=announcements" class="dashboard-tab <?php echo $current_tab === 'announcements' ? 'active' : ''; ?>" data-tab="announcements">
                        Announcements
                    </a>
                <?php endif; ?>
                
                <?php if (villa_user_can_access_owner_portal($user_roles)): ?>
                    <a href="?tab=owner-portal" class="dashboard-tab <?php echo $current_tab === 'owner-portal' ? 'active' : ''; ?>" data-tab="owner-portal">
                        Owner Portal
                    </a>
                <?php endif; ?>
                
                <?php if (villa_user_can_access_business($user_roles)): ?>
                    <a href="?tab=business" class="dashboard-tab <?php echo $current_tab === 'business' ? 'active' : ''; ?>" data-tab="business">
                        Business Listings
                    </a>
                <?php endif; ?>
                
                <?php if (villa_user_can_access_committees($user_roles)): ?>
                    <a href="?tab=groups" class="dashboard-tab <?php echo $current_tab === 'groups' ? 'active' : ''; ?>" data-tab="groups">
                        Groups & Committees
                    </a>
                <?php endif; ?>
                
                <?php if (villa_user_can_access_billing($user_roles)): ?>
                    <a href="?tab=billing" class="dashboard-tab <?php echo $current_tab === 'billing' ? 'active' : ''; ?>" data-tab="billing">
                        Billing
                    </a>
                <?php endif; ?>
                
                <a href="?tab=profile" class="dashboard-tab <?php echo $current_tab === 'profile' ? 'active' : ''; ?>" data-tab="profile">
                    Profile
                </a>
            </div>
        </div>
        
        <!-- Dashboard Content -->
        <div class="dashboard-content">
            <?php
            switch ($current_tab) {
                case 'properties':
                    if (villa_user_can_access_properties($user_roles)) {
                        villa_render_dashboard_properties($user);
                    } else {
                        echo '<div class="access-denied">You do not have access to properties.</div>';
                    }
                    break;
                    
                case 'tickets':
                    if (villa_user_can_access_tickets($user_roles)) {
                        villa_render_dashboard_tickets($user);
                    } else {
                        echo '<div class="access-denied">You do not have access to support tickets.</div>';
                    }
                    break;
                    
                case 'announcements':
                    if (villa_user_can_access_announcements($user_roles)) {
                        villa_render_dashboard_announcements($user);
                    } else {
                        echo '<div class="access-denied">You do not have access to announcements.</div>';
                    }
                    break;
                    
                case 'owner-portal':
                    if (villa_user_can_access_owner_portal($user_roles)) {
                        villa_render_dashboard_owner_portal($user);
                    } else {
                        echo '<div class="access-denied">You do not have access to the owner portal.</div>';
                    }
                    break;
                    
                case 'business':
                    if (villa_user_can_access_business($user_roles)) {
                        villa_render_dashboard_business($user);
                    } else {
                        echo '<div class="access-denied">You do not have access to business listings.</div>';
                    }
                    break;
                    
                case 'committees':
                case 'groups':
                    if (villa_user_can_access_committees($user_roles)) {
                        villa_render_dashboard_groups($user);
                    } else {
                        echo '<div class="access-denied">You do not have access to groups.</div>';
                    }
                    break;
                    
                case 'billing':
                    if (villa_user_can_access_billing($user_roles)) {
                        villa_render_dashboard_billing($user);
                    } else {
                        echo '<div class="access-denied">You do not have access to billing.</div>';
                    }
                    break;
                    
                case 'profile':
                    villa_render_dashboard_profile($user);
                    break;
                    
                default:
                    echo '<div class="dashboard-welcome">Welcome to your Villa Community dashboard!</div>';
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Permission check functions
 */
function villa_user_can_access_properties($user_roles) {
    $allowed_roles = array('owner', 'bod', 'staff', 'property_manager');
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_access_tickets($user_roles) {
    $allowed_roles = array('owner', 'bod', 'staff', 'property_manager', 'community_member');
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_access_announcements($user_roles) {
    // All roles can access announcements
    return !empty($user_roles);
}

function villa_user_can_access_owner_portal($user_roles) {
    $allowed_roles = array('owner', 'bod');
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_access_business($user_roles) {
    $allowed_roles = array('partner', 'bod', 'staff');
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_access_committees($user_roles) {
    $allowed_roles = array('bod', 'committee_member', 'staff');
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_access_billing($user_roles) {
    $allowed_roles = array('owner', 'bod');
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_view_billing($user_id) {
    $user_roles = villa_get_user_villa_roles($user_id);
    return villa_user_can_access_billing($user_roles);
}

/**
 * Get user's villa roles
 */
function villa_get_user_villa_roles($user_id) {
    $profile = villa_get_user_profile($user_id);
    $roles = array();
    
    if ($profile) {
        // Check both old single role and new multiple roles
        $single_role = get_post_meta($profile->ID, 'profile_villa_role', true);
        $multiple_roles = get_post_meta($profile->ID, 'profile_villa_roles', true);
        
        if ($single_role) {
            $roles[] = $single_role;
        }
        
        if ($multiple_roles && is_array($multiple_roles)) {
            $roles = array_merge($roles, array_keys(array_filter($multiple_roles)));
        }
        
        $roles = array_unique($roles);
    }
    
    return $roles;
}

/**
 * Get user profile CPT
 */
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

/**
 * Get unread announcements count for user
 */
function villa_get_unread_announcements_count($user_id) {
    $read_announcements = get_user_meta($user_id, 'villa_read_announcements', true);
    if (!is_array($read_announcements)) {
        $read_announcements = array();
    }
    
    $all_announcements = get_posts(array(
        'post_type' => 'villa_announcement',
        'post_status' => 'publish',
        'numberposts' => -1
    ));
    
    $unread_count = 0;
    foreach ($all_announcements as $announcement) {
        if (!in_array($announcement->ID, $read_announcements)) {
            $unread_count++;
        }
    }
    
    return $unread_count;
}

/**
 * Render recent activity widget
 */
function villa_render_recent_activity($user_id) {
    // Get recent tickets, property updates, etc.
    echo '<p>Recent activity will be displayed here...</p>';
}

/**
 * Create test profile data for admin users
 */
function villa_create_test_profile_for_admin($user_id) {
    // Create a test profile for the admin user
    $profile = array(
        'post_title' => 'Admin Test Profile',
        'post_content' => '',
        'post_status' => 'publish',
        'post_type' => 'user_profile',
        'meta_input' => array(
            'profile_user_id' => $user_id,
            'profile_villa_roles' => array('owner' => true, 'bod' => true)
        )
    );
    
    $profile_id = wp_insert_post($profile);
    
    // Add some test properties for the admin user
    $property = array(
        'post_title' => 'Admin Test Property',
        'post_content' => '',
        'post_status' => 'publish',
        'post_type' => 'property',
        'meta_input' => array(
            'property_user_id' => $user_id,
            'property_listing_status' => 'for_sale'
        )
    );
    
    $property_id = wp_insert_post($property);
}

/**
 * AJAX handlers
 */

// Load tab content dynamically
function villa_ajax_load_tab_content() {
    check_ajax_referer('villa_dashboard_nonce', 'nonce');
    
    $tab = sanitize_text_field($_POST['tab']);
    $user = wp_get_current_user();
    
    ob_start();
    
    switch ($tab) {
        case 'properties':
            villa_render_dashboard_properties($user);
            break;
        case 'tickets':
            villa_render_dashboard_tickets($user);
            break;
        case 'announcements':
            villa_render_dashboard_announcements($user);
            break;
        // Add other tabs as needed
    }
    
    $content = ob_get_clean();
    
    wp_send_json_success(array('content' => $content));
}
add_action('wp_ajax_villa_load_tab_content', 'villa_ajax_load_tab_content');

// Delete property
function villa_ajax_delete_property() {
    check_ajax_referer('villa_dashboard_nonce', 'nonce');
    
    $property_id = intval($_POST['property_id']);
    $user = wp_get_current_user();
    
    // Check if user owns this property
    $property = get_post($property_id);
    if (!$property || $property->post_author != $user->ID) {
        wp_send_json_error(array('message' => 'You do not have permission to delete this property.'));
    }
    
    if (wp_delete_post($property_id, true)) {
        wp_send_json_success(array('message' => 'Property deleted successfully.'));
    } else {
        wp_send_json_error(array('message' => 'Failed to delete property.'));
    }
}
add_action('wp_ajax_villa_delete_property', 'villa_ajax_delete_property');

// Toggle property listing
function villa_ajax_toggle_property_listing() {
    check_ajax_referer('villa_dashboard_nonce', 'nonce');
    
    $property_id = intval($_POST['property_id']);
    $user = wp_get_current_user();
    
    // Check if user owns this property
    $property = get_post($property_id);
    if (!$property || $property->post_author != $user->ID) {
        wp_send_json_error(array('message' => 'You do not have permission to modify this property.'));
    }
    
    $current_status = get_post_meta($property_id, 'property_listing_status', true);
    $new_status = ($current_status === 'not_listed') ? 'for_sale' : 'not_listed';
    
    update_post_meta($property_id, 'property_listing_status', $new_status);
    
    wp_send_json_success(array('message' => 'Property listing status updated.'));
}
add_action('wp_ajax_villa_toggle_property_listing', 'villa_ajax_toggle_property_listing');

// Mark announcement as read
function villa_ajax_mark_announcement_read() {
    check_ajax_referer('villa_dashboard_nonce', 'nonce');
    
    $announcement_id = intval($_POST['announcement_id']);
    $user_id = get_current_user_id();
    
    // Get current read users
    $read_users = get_post_meta($announcement_id, 'announcement_read_users', true);
    if (!is_array($read_users)) {
        $read_users = array();
    }
    
    // Add current user to read list
    if (!in_array($user_id, $read_users)) {
        $read_users[] = $user_id;
        update_post_meta($announcement_id, 'announcement_read_users', $read_users);
    }
    
    wp_send_json_success();
}
add_action('wp_ajax_villa_mark_announcement_read', 'villa_ajax_mark_announcement_read');

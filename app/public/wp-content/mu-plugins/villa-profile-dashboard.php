<?php
/**
 * Villa Community Profile Dashboard Integration
 * Links User Profile CPT with membership system and provides dashboard management
 * 
 * @package VillaCommunity
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add profile management to user dashboard
 */
function villa_add_profile_dashboard_widget() {
    if (!is_user_logged_in()) {
        return;
    }
    
    $current_user = wp_get_current_user();
    $profile_id = get_user_meta($current_user->ID, 'profile_post_id', true);
    
    echo '<div class="villa-profile-dashboard">';
    echo '<h3>My Profile</h3>';
    
    if ($profile_id) {
        $profile = get_post($profile_id);
        $profile_url = get_permalink($profile_id);
        $edit_url = get_edit_post_link($profile_id);
        
        echo '<div class="profile-summary">';
        echo '<div class="profile-avatar">';
        if (has_post_thumbnail($profile_id)) {
            echo get_the_post_thumbnail($profile_id, 'thumbnail');
        } else {
            echo '<div class="no-avatar">No Photo</div>';
        }
        echo '</div>';
        
        echo '<div class="profile-info">';
        echo '<h4>' . esc_html($profile->post_title) . '</h4>';
        
        $villa_roles = get_post_meta($profile_id, 'profile_villa_roles', true);
        $villa_address = get_post_meta($profile_id, 'profile_villa_address', true);
        
        if ($villa_roles && is_array($villa_roles)) {
            $active_roles = array_keys(array_filter($villa_roles));
            if (!empty($active_roles)) {
                $role_labels = array();
                foreach ($active_roles as $role) {
                    $role_labels[] = ucwords(str_replace('_', ' ', $role));
                }
                echo '<p><strong>Roles:</strong> ' . esc_html(implode(', ', $role_labels)) . '</p>';
            }
        }
        
        if ($villa_address) {
            echo '<p><strong>Address:</strong> ' . esc_html($villa_address) . '</p>';
        }
        
        echo '<div class="profile-actions">';
        echo '<a href="' . esc_url($profile_url) . '" class="button">View Profile</a>';
        if (current_user_can('edit_post', $profile_id)) {
            echo '<a href="' . esc_url($edit_url) . '" class="button">Edit Profile</a>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<p>No profile found. <a href="#" onclick="villa_create_profile()">Create Profile</a></p>';
    }
    
    echo '</div>';
}

/**
 * Create profile for existing user
 */
function villa_create_user_profile($user_id = null) {
    if (!$user_id) {
        $user_id = get_current_user_id();
    }
    
    if (!$user_id) {
        return false;
    }
    
    $user = get_user_by('ID', $user_id);
    if (!$user) {
        return false;
    }
    
    // Check if profile already exists
    $existing_profile = get_user_meta($user_id, 'profile_post_id', true);
    if ($existing_profile) {
        return $existing_profile;
    }
    
    // Create new profile
    $profile_id = wp_insert_post(array(
        'post_title' => $user->display_name,
        'post_content' => 'Welcome to Villa Community!',
        'post_status' => 'publish',
        'post_type' => 'user_profile',
        'post_author' => $user_id,
    ));
    
    if ($profile_id && !is_wp_error($profile_id)) {
        // Link user to profile
        update_post_meta($profile_id, 'linked_user_id', $user_id);
        update_user_meta($user_id, 'profile_post_id', $profile_id);
        
        // Set default avatar
        $default_avatar_id = villa_get_default_avatar_id();
        if ($default_avatar_id) {
            update_post_meta($profile_id, '_thumbnail_id', $default_avatar_id);
        }
        
        return $profile_id;
    }
    
    return false;
}

/**
 * Get user profile by user ID (if not already defined)
 */
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

/**
 * Get user by profile ID
 */
function villa_get_profile_user($profile_id) {
    $user_id = get_post_meta($profile_id, 'linked_user_id', true);
    if ($user_id) {
        return get_user_by('ID', $user_id);
    }
    return null;
}

/**
 * Display user profiles directory
 */
function villa_display_profiles_directory($atts = array()) {
    $atts = shortcode_atts(array(
        'role' => '',
        'limit' => 20,
        'columns' => 3,
        'show_contact' => 'members_only'
    ), $atts);
    
    $args = array(
        'post_type' => 'user_profile',
        'post_status' => 'publish',
        'posts_per_page' => $atts['limit'],
        'orderby' => 'title',
        'order' => 'ASC'
    );
    
    // Filter by role if specified
    if ($atts['role']) {
        $args['meta_query'] = array(
            array(
                'key' => 'profile_role',
                'value' => $atts['role'],
                'compare' => '='
            )
        );
    }
    
    $profiles = get_posts($args);
    
    if (empty($profiles)) {
        return '<p>No profiles found.</p>';
    }
    
    $output = '<div class="villa-profiles-directory columns-' . intval($atts['columns']) . '">';
    
    foreach ($profiles as $profile) {
        $user = villa_get_profile_user($profile->ID);
        if (!$user) continue;
        
        // Check visibility permissions
        $visibility = get_post_meta($profile->ID, 'profile_visibility', true);
        if ($visibility === 'private' && $user->ID !== get_current_user_id()) {
            continue;
        }
        if ($visibility === 'members' && !is_user_logged_in()) {
            continue;
        }
        
        $output .= '<div class="profile-card">';
        
        // Avatar
        $output .= '<div class="profile-avatar">';
        if (has_post_thumbnail($profile->ID)) {
            $output .= get_the_post_thumbnail($profile->ID, 'medium');
        } else {
            $output .= '<div class="no-avatar">No Photo</div>';
        }
        $output .= '</div>';
        
        // Profile info
        $output .= '<div class="profile-info">';
        $output .= '<h4><a href="' . get_permalink($profile->ID) . '">' . esc_html($profile->post_title) . '</a></h4>';
        
        $villa_roles = get_post_meta($profile->ID, 'profile_villa_roles', true);
        $company = get_post_meta($profile->ID, 'profile_company', true);
        $job_title = get_post_meta($profile->ID, 'profile_job_title', true);
        
        if ($villa_roles && is_array($villa_roles)) {
            $active_roles = array_keys(array_filter($villa_roles));
            if (!empty($active_roles)) {
                $role_labels = array();
                foreach ($active_roles as $role) {
                    $role_labels[] = ucwords(str_replace('_', ' ', $role));
                }
                $output .= '<p class="role">' . esc_html(implode(', ', $role_labels)) . '</p>';
            }
        }
        
        if ($company && $job_title) {
            $output .= '<p class="job">' . esc_html($job_title) . ' at ' . esc_html($company) . '</p>';
        } elseif ($job_title) {
            $output .= '<p class="job">' . esc_html($job_title) . '</p>';
        } elseif ($company) {
            $output .= '<p class="job">' . esc_html($company) . '</p>';
        }
        
        // Contact info (if allowed)
        $show_contact = get_post_meta($profile->ID, 'profile_show_contact', true);
        if ($show_contact && ($atts['show_contact'] === 'always' || is_user_logged_in())) {
            $phone = get_post_meta($profile->ID, 'profile_phone', true);
            if ($phone) {
                $output .= '<p class="contact"><a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a></p>';
            }
        }
        
        $output .= '<p class="excerpt">' . wp_trim_words($profile->post_content, 20) . '</p>';
        $output .= '</div>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Register shortcode for profiles directory
 */
add_shortcode('villa_profiles', 'villa_display_profiles_directory');

/**
 * Add profile management to admin bar
 */
function villa_add_profile_admin_bar($wp_admin_bar) {
    if (!is_user_logged_in()) {
        return;
    }
    
    $current_user = wp_get_current_user();
    $profile_id = get_user_meta($current_user->ID, 'profile_post_id', true);
    
    if ($profile_id) {
        $wp_admin_bar->add_node(array(
            'id' => 'villa-profile',
            'title' => 'My Profile',
            'href' => get_permalink($profile_id),
        ));
        
        $wp_admin_bar->add_node(array(
            'id' => 'villa-edit-profile',
            'parent' => 'villa-profile',
            'title' => 'Edit Profile',
            'href' => get_edit_post_link($profile_id),
        ));
    }
}
add_action('admin_bar_menu', 'villa_add_profile_admin_bar', 100);

/**
 * Auto-create profile when user registers
 */
function villa_auto_create_profile_on_registration($user_id) {
    villa_create_user_profile($user_id);
}
add_action('user_register', 'villa_auto_create_profile_on_registration');

/**
 * Sync user data with profile
 */
function villa_sync_user_profile_data($user_id, $old_user_data = null) {
    $profile_id = get_user_meta($user_id, 'profile_post_id', true);
    if (!$profile_id) {
        return;
    }
    
    $user = get_user_by('ID', $user_id);
    if (!$user) {
        return;
    }
    
    // Update profile title if display name changed
    $current_title = get_the_title($profile_id);
    if ($current_title !== $user->display_name) {
        wp_update_post(array(
            'ID' => $profile_id,
            'post_title' => $user->display_name,
        ));
    }
}
add_action('profile_update', 'villa_sync_user_profile_data', 10, 2);

/**
 * Add CSS for profile dashboard
 */
function villa_profile_dashboard_styles() {
    ?>
    <style>
    .villa-profile-dashboard {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .profile-summary {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }
    
    .profile-avatar img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
    }
    
    .no-avatar {
        width: 80px;
        height: 80px;
        background: #ddd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #666;
    }
    
    .profile-actions {
        margin-top: 15px;
    }
    
    .profile-actions .button {
        margin-right: 10px;
    }
    
    .villa-profiles-directory {
        display: grid;
        gap: 20px;
        margin: 20px 0;
    }
    
    .villa-profiles-directory.columns-2 {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
    
    .villa-profiles-directory.columns-3 {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    
    .villa-profiles-directory.columns-4 {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
    
    .profile-card {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .profile-card .profile-avatar img {
        width: 100px;
        height: 100px;
        margin-bottom: 15px;
    }
    
    .profile-card h4 {
        margin: 10px 0 5px;
    }
    
    .profile-card .role {
        color: #666;
        font-weight: bold;
        margin: 5px 0;
    }
    
    .profile-card .job {
        color: #888;
        font-size: 14px;
        margin: 5px 0;
    }
    
    .profile-card .contact {
        margin: 10px 0;
    }
    
    .profile-card .excerpt {
        font-size: 14px;
        color: #666;
        margin-top: 10px;
    }
    </style>
    <?php
}
add_action('wp_head', 'villa_profile_dashboard_styles');
add_action('admin_head', 'villa_profile_dashboard_styles');

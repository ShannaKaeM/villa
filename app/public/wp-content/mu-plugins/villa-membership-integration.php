<?php
/**
 * Villa Community Membership Integration
 * Enhances Ultimate Member with custom functionality
 * 
 * @package VillaCommunity
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize membership integration
 */
function villa_membership_init() {
    // Only proceed if Ultimate Member is active
    if (!class_exists('UM')) {
        return;
    }
    
    // Add custom user roles for villa community
    villa_add_custom_user_roles();
    
    // Customize Ultimate Member forms
    add_action('init', 'villa_customize_um_forms');
    
    // Add custom profile fields
    add_action('um_after_register_fields', 'villa_add_custom_profile_fields');
    
    // Customize member directory
    add_filter('um_member_directory_sort_fields', 'villa_custom_member_sort_fields');
    
    // Add custom email templates
    add_filter('um_email_templates', 'villa_custom_email_templates');
}
add_action('plugins_loaded', 'villa_membership_init');

/**
 * Add custom user roles for villa community
 */
function villa_add_custom_user_roles() {
    // Property Owner role
    add_role('property_owner', 'Property Owner', array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
        'publish_posts' => true,
        'upload_files' => true,
        'edit_published_posts' => true,
        'delete_published_posts' => true,
    ));
    
    // Business Owner role
    add_role('business_owner', 'Business Owner', array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
        'publish_posts' => true,
        'upload_files' => true,
        'edit_published_posts' => true,
        'delete_published_posts' => true,
    ));
    
    // Community Member role (default)
    add_role('community_member', 'Community Member', array(
        'read' => true,
        'edit_posts' => false,
        'delete_posts' => false,
        'publish_posts' => false,
        'upload_files' => false,
    ));
}

/**
 * Customize Ultimate Member forms
 */
function villa_customize_um_forms() {
    if (!function_exists('UM')) {
        return;
    }
    
    // Add custom CSS for forms
    add_action('wp_head', function() {
        ?>
        <style>
        .um-form {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .um-form .um-field-title {
            color: var(--wp--preset--color--primary, #2c3e50);
            font-weight: 600;
        }
        
        .um-form input[type="text"],
        .um-form input[type="email"],
        .um-form input[type="password"],
        .um-form textarea,
        .um-form select {
            border: 2px solid #e1e8ed;
            border-radius: 4px;
            padding: 12px;
            transition: border-color 0.3s ease;
        }
        
        .um-form input:focus,
        .um-form textarea:focus,
        .um-form select:focus {
            border-color: var(--wp--preset--color--primary, #3498db);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        .um-button {
            background: var(--wp--preset--color--primary, #3498db);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .um-button:hover {
            background: var(--wp--preset--color--secondary, #2980b9);
        }
        </style>
        <?php
    });
}

/**
 * Add custom profile fields for villa community
 */
function villa_add_custom_profile_fields() {
    if (!function_exists('UM')) {
        return;
    }
    
    // Add property interest field
    echo '<div class="um-field um-field-select um-field-property_interest">';
    echo '<div class="um-field-label"><label for="property_interest">Property Interest</label></div>';
    echo '<div class="um-field-area">';
    echo '<select name="property_interest" id="property_interest">';
    echo '<option value="">Select your interest...</option>';
    echo '<option value="buying">Buying Property</option>';
    echo '<option value="selling">Selling Property</option>';
    echo '<option value="renting">Renting Property</option>';
    echo '<option value="investing">Property Investment</option>';
    echo '</select>';
    echo '</div></div>';
    
    // Add business type field
    echo '<div class="um-field um-field-select um-field-business_type">';
    echo '<div class="um-field-label"><label for="business_type">Business Type</label></div>';
    echo '<div class="um-field-area">';
    echo '<select name="business_type" id="business_type">';
    echo '<option value="">Select business type...</option>';
    echo '<option value="restaurant">Restaurant</option>';
    echo '<option value="retail">Retail</option>';
    echo '<option value="service">Service</option>';
    echo '<option value="healthcare">Healthcare</option>';
    echo '<option value="education">Education</option>';
    echo '<option value="other">Other</option>';
    echo '</select>';
    echo '</div></div>';
}

/**
 * Customize member directory sort fields
 */
function villa_custom_member_sort_fields($fields) {
    $fields['property_interest'] = 'Property Interest';
    $fields['business_type'] = 'Business Type';
    $fields['user_registered'] = 'Join Date';
    
    return $fields;
}

/**
 * Add custom email templates
 */
function villa_custom_email_templates($templates) {
    $templates['villa_welcome'] = array(
        'key' => 'villa_welcome',
        'title' => 'Villa Community Welcome',
        'subject' => 'Welcome to Villa Community!',
        'body' => 'Hi {display_name},<br><br>Welcome to the Villa Community! We\'re excited to have you join our community of property owners, business owners, and residents.<br><br>Your account details:<br>Username: {username}<br>Email: {email}<br><br>You can now:<br>• Browse and list properties<br>• Connect with local businesses<br>• Participate in community discussions<br>• Access member-only content<br><br>Get started by completing your profile: {profile_url}<br><br>Best regards,<br>The Villa Community Team',
        'description' => 'Email sent to new Villa Community members'
    );
    
    return $templates;
}

/**
 * Create default Ultimate Member forms and pages
 */
function villa_create_um_pages() {
    if (!function_exists('UM')) {
        return;
    }
    
    // Create login page
    $login_page = array(
        'post_title' => 'Login',
        'post_content' => '[ultimatemember form_id="login"]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_slug' => 'login'
    );
    
    if (!get_page_by_path('login')) {
        wp_insert_post($login_page);
    }
    
    // Create registration page
    $register_page = array(
        'post_title' => 'Register',
        'post_content' => '[ultimatemember form_id="register"]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_slug' => 'register'
    );
    
    if (!get_page_by_path('register')) {
        wp_insert_post($register_page);
    }
    
    // Create member directory page
    $members_page = array(
        'post_title' => 'Members',
        'post_content' => '[ultimatemember form_id="members"]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_slug' => 'members'
    );
    
    if (!get_page_by_path('members')) {
        wp_insert_post($members_page);
    }
    
    // Create user profile page
    $profile_page = array(
        'post_title' => 'User Profile',
        'post_content' => '[ultimatemember form_id="profile"]',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_slug' => 'user'
    );
    
    if (!get_page_by_path('user')) {
        wp_insert_post($profile_page);
    }
}

// Create pages when plugin is activated
register_activation_hook(__FILE__, 'villa_create_um_pages');

/**
 * Add membership menu items to navigation
 */
function villa_add_membership_menu_items($items, $args) {
    if ($args->theme_location == 'primary') {
        if (is_user_logged_in()) {
            $items .= '<li class="menu-item"><a href="' . um_user_profile_url() . '">My Profile</a></li>';
            $items .= '<li class="menu-item"><a href="' . wp_logout_url() . '">Logout</a></li>';
        } else {
            $items .= '<li class="menu-item"><a href="/login">Login</a></li>';
            $items .= '<li class="menu-item"><a href="/register">Register</a></li>';
        }
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'villa_add_membership_menu_items', 10, 2);

/**
 * Restrict content based on membership
 */
function villa_restrict_content($content) {
    if (is_singular('property') && !is_user_logged_in()) {
        return '<div class="membership-notice">
            <h3>Members Only Content</h3>
            <p>Please <a href="/login">login</a> or <a href="/register">register</a> to view full property details.</p>
        </div>';
    }
    
    return $content;
}
add_filter('the_content', 'villa_restrict_content');

/**
 * Add user dashboard widget
 */
function villa_add_dashboard_widget() {
    if (!is_user_logged_in()) {
        return;
    }
    
    $current_user = wp_get_current_user();
    $user_role = $current_user->roles[0] ?? 'subscriber';
    
    echo '<div class="villa-user-dashboard">';
    echo '<h3>Welcome back, ' . $current_user->display_name . '!</h3>';
    
    switch ($user_role) {
        case 'property_owner':
            echo '<p>Manage your properties and connect with potential buyers.</p>';
            echo '<a href="/wp-admin/edit.php?post_type=property" class="button">Manage Properties</a>';
            break;
            
        case 'business_owner':
            echo '<p>Promote your business and connect with the community.</p>';
            echo '<a href="/wp-admin/edit.php?post_type=business" class="button">Manage Business</a>';
            break;
            
        default:
            echo '<p>Explore properties and businesses in your community.</p>';
            echo '<a href="/properties" class="button">Browse Properties</a>';
            echo '<a href="/businesses" class="button">Browse Businesses</a>';
    }
    
    echo '</div>';
}

/**
 * Helper function to get user profile URL
 */
function villa_get_profile_url($user_id = null) {
    if (!function_exists('um_user_profile_url')) {
        return home_url('/user/');
    }
    
    return um_user_profile_url($user_id);
}

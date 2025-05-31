<?php
/**
 * Villa Dashboard - Custom Post Types
 * Registers all custom post types needed for the dashboard system
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom post types for dashboard system
 */
function villa_register_dashboard_post_types() {
    
    // Support Ticket Post Type
    register_post_type('support_ticket', array(
        'labels' => array(
            'name' => 'Support Tickets',
            'singular_name' => 'Support Ticket',
            'add_new' => 'Add New Ticket',
            'add_new_item' => 'Add New Support Ticket',
            'edit_item' => 'Edit Support Ticket',
            'new_item' => 'New Support Ticket',
            'view_item' => 'View Support Ticket',
            'search_items' => 'Search Support Tickets',
            'not_found' => 'No support tickets found',
            'not_found_in_trash' => 'No support tickets found in trash'
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-sos',
        'supports' => array('title', 'editor', 'author', 'custom-fields'),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'show_in_rest' => true
    ));
    
    // Announcement Post Type
    register_post_type('announcement', array(
        'labels' => array(
            'name' => 'Announcements',
            'singular_name' => 'Announcement',
            'add_new' => 'Add New Announcement',
            'add_new_item' => 'Add New Announcement',
            'edit_item' => 'Edit Announcement',
            'new_item' => 'New Announcement',
            'view_item' => 'View Announcement',
            'search_items' => 'Search Announcements',
            'not_found' => 'No announcements found',
            'not_found_in_trash' => 'No announcements found in trash'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite' => array('slug' => 'announcements'),
        'show_in_rest' => true,
        'capability_type' => 'post',
        'map_meta_cap' => true
    ));
    
    // Meeting Post Type
    register_post_type('meeting', array(
        'labels' => array(
            'name' => 'Meetings',
            'singular_name' => 'Meeting',
            'add_new' => 'Add New Meeting',
            'add_new_item' => 'Add New Meeting',
            'edit_item' => 'Edit Meeting',
            'new_item' => 'New Meeting',
            'view_item' => 'View Meeting',
            'search_items' => 'Search Meetings',
            'not_found' => 'No meetings found',
            'not_found_in_trash' => 'No meetings found in trash'
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => array('title', 'editor', 'custom-fields'),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'show_in_rest' => true
    ));
}
add_action('init', 'villa_register_dashboard_post_types');

/**
 * Add custom meta boxes for dashboard post types
 */
function villa_add_dashboard_meta_boxes() {
    
    // Support Ticket Meta Box
    add_meta_box(
        'ticket_details',
        'Ticket Details',
        'villa_ticket_meta_box_callback',
        'support_ticket',
        'normal',
        'high'
    );
    
    // Announcement Meta Box
    add_meta_box(
        'announcement_details',
        'Announcement Details',
        'villa_announcement_meta_box_callback',
        'announcement',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'villa_add_dashboard_meta_boxes');

/**
 * Support ticket meta box callback
 */
function villa_ticket_meta_box_callback($post) {
    wp_nonce_field('villa_ticket_meta_box', 'villa_ticket_meta_box_nonce');
    
    $property_id = get_post_meta($post->ID, 'ticket_property_id', true);
    $type = get_post_meta($post->ID, 'ticket_type', true);
    $category = get_post_meta($post->ID, 'ticket_category', true);
    $priority = get_post_meta($post->ID, 'ticket_priority', true);
    $status = get_post_meta($post->ID, 'ticket_status', true);
    
    // Get properties for dropdown
    $properties = get_posts(array(
        'post_type' => 'property',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="ticket_property_id">Related Property</label></th>
            <td>
                <select id="ticket_property_id" name="ticket_property_id">
                    <option value="">Select Property</option>
                    <?php foreach ($properties as $property): ?>
                        <option value="<?php echo $property->ID; ?>" <?php selected($property_id, $property->ID); ?>>
                            <?php echo esc_html($property->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="ticket_type">Ticket Type</label></th>
            <td>
                <select id="ticket_type" name="ticket_type">
                    <option value="work_order" <?php selected($type, 'work_order'); ?>>Work Order</option>
                    <option value="maintenance" <?php selected($type, 'maintenance'); ?>>Maintenance Request</option>
                    <option value="support" <?php selected($type, 'support'); ?>>General Support</option>
                    <option value="complaint" <?php selected($type, 'complaint'); ?>>Complaint</option>
                    <option value="suggestion" <?php selected($type, 'suggestion'); ?>>Suggestion</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="ticket_category">Category</label></th>
            <td>
                <select id="ticket_category" name="ticket_category">
                    <option value="">Select Category</option>
                    <option value="plumbing" <?php selected($category, 'plumbing'); ?>>Plumbing</option>
                    <option value="electrical" <?php selected($category, 'electrical'); ?>>Electrical</option>
                    <option value="hvac" <?php selected($category, 'hvac'); ?>>HVAC</option>
                    <option value="landscaping" <?php selected($category, 'landscaping'); ?>>Landscaping</option>
                    <option value="security" <?php selected($category, 'security'); ?>>Security</option>
                    <option value="cleaning" <?php selected($category, 'cleaning'); ?>>Cleaning</option>
                    <option value="amenities" <?php selected($category, 'amenities'); ?>>Amenities</option>
                    <option value="administrative" <?php selected($category, 'administrative'); ?>>Administrative</option>
                    <option value="other" <?php selected($category, 'other'); ?>>Other</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="ticket_priority">Priority</label></th>
            <td>
                <select id="ticket_priority" name="ticket_priority">
                    <option value="low" <?php selected($priority, 'low'); ?>>Low</option>
                    <option value="medium" <?php selected($priority, 'medium'); ?>>Medium</option>
                    <option value="high" <?php selected($priority, 'high'); ?>>High</option>
                    <option value="urgent" <?php selected($priority, 'urgent'); ?>>Urgent</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="ticket_status">Status</label></th>
            <td>
                <select id="ticket_status" name="ticket_status">
                    <option value="open" <?php selected($status, 'open'); ?>>Open</option>
                    <option value="in_progress" <?php selected($status, 'in_progress'); ?>>In Progress</option>
                    <option value="resolved" <?php selected($status, 'resolved'); ?>>Resolved</option>
                    <option value="closed" <?php selected($status, 'closed'); ?>>Closed</option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save meta box data
 */
function villa_save_dashboard_meta_boxes($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['villa_ticket_meta_box_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['villa_ticket_meta_box_nonce'], 'villa_ticket_meta_box')) {
        return;
    }
    
    // Save ticket meta
    $ticket_fields = array(
        'ticket_property_id', 'ticket_type', 'ticket_category', 
        'ticket_priority', 'ticket_status'
    );
    
    foreach ($ticket_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Update last modified time
    update_post_meta($post_id, 'ticket_last_update', current_time('mysql'));
}
add_action('save_post', 'villa_save_dashboard_meta_boxes');

/**
 * Announcement meta box callback
 */
function villa_announcement_meta_box_callback($post) {
    wp_nonce_field('villa_announcement_meta_box', 'villa_announcement_meta_box_nonce');
    
    $type = get_post_meta($post->ID, 'announcement_type', true);
    $priority = get_post_meta($post->ID, 'announcement_priority', true);
    $target_roles = get_post_meta($post->ID, 'announcement_target_roles', true);
    $expiry_date = get_post_meta($post->ID, 'announcement_expiry_date', true);
    $pinned = get_post_meta($post->ID, 'announcement_pinned', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="announcement_type">Type</label></th>
            <td>
                <select id="announcement_type" name="announcement_type">
                    <option value="general" <?php selected($type, 'general'); ?>>General</option>
                    <option value="maintenance" <?php selected($type, 'maintenance'); ?>>Maintenance</option>
                    <option value="events" <?php selected($type, 'events'); ?>>Events</option>
                    <option value="policy" <?php selected($type, 'policy'); ?>>Policy Updates</option>
                    <option value="emergency" <?php selected($type, 'emergency'); ?>>Emergency</option>
                    <option value="owner_only" <?php selected($type, 'owner_only'); ?>>Owner Only</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="announcement_priority">Priority</label></th>
            <td>
                <select id="announcement_priority" name="announcement_priority">
                    <option value="normal" <?php selected($priority, 'normal'); ?>>Normal</option>
                    <option value="high" <?php selected($priority, 'high'); ?>>High</option>
                    <option value="urgent" <?php selected($priority, 'urgent'); ?>>Urgent</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="announcement_expiry_date">Expiry Date</label></th>
            <td><input type="date" id="announcement_expiry_date" name="announcement_expiry_date" value="<?php echo esc_attr($expiry_date); ?>" /></td>
        </tr>
        <tr>
            <th><label for="announcement_pinned">Pinned</label></th>
            <td><input type="checkbox" id="announcement_pinned" name="announcement_pinned" value="1" <?php checked($pinned, '1'); ?> /></td>
        </tr>
    </table>
    <?php
}

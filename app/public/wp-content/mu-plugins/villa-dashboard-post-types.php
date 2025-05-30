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
    
    // Property Post Type
    register_post_type('property', array(
        'labels' => array(
            'name' => 'Properties',
            'singular_name' => 'Property',
            'add_new' => 'Add New Property',
            'add_new_item' => 'Add New Property',
            'edit_item' => 'Edit Property',
            'new_item' => 'New Property',
            'view_item' => 'View Property',
            'search_items' => 'Search Properties',
            'not_found' => 'No properties found',
            'not_found_in_trash' => 'No properties found in trash'
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-admin-home',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'rewrite' => array('slug' => 'properties'),
        'show_in_rest' => true,
        'capability_type' => 'post',
        'map_meta_cap' => true
    ));
    
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
    
    // Roadmap Item Post Type
    register_post_type('roadmap_item', array(
        'labels' => array(
            'name' => 'Roadmap Items',
            'singular_name' => 'Roadmap Item',
            'add_new' => 'Add New Roadmap Item',
            'add_new_item' => 'Add New Roadmap Item',
            'edit_item' => 'Edit Roadmap Item',
            'new_item' => 'New Roadmap Item',
            'view_item' => 'View Roadmap Item',
            'search_items' => 'Search Roadmap Items',
            'not_found' => 'No roadmap items found',
            'not_found_in_trash' => 'No roadmap items found in trash'
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-chart-line',
        'supports' => array('title', 'editor', 'custom-fields'),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'show_in_rest' => true
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
    
    // Financial Report Post Type
    register_post_type('financial_report', array(
        'labels' => array(
            'name' => 'Financial Reports',
            'singular_name' => 'Financial Report',
            'add_new' => 'Add New Financial Report',
            'add_new_item' => 'Add New Financial Report',
            'edit_item' => 'Edit Financial Report',
            'new_item' => 'New Financial Report',
            'view_item' => 'View Financial Report',
            'search_items' => 'Search Financial Reports',
            'not_found' => 'No financial reports found',
            'not_found_in_trash' => 'No financial reports found in trash'
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-chart-pie',
        'supports' => array('title', 'editor', 'custom-fields'),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'show_in_rest' => true
    ));
    
    // Remove the business_listing post type since user has existing business CPT
    // register_post_type('business_listing', array(
    //     'labels' => array(
    //         'name' => 'Business Listings',
    //         'singular_name' => 'Business Listing',
    //         'add_new' => 'Add New Business',
    //         'add_new_item' => 'Add New Business',
    //         'edit_item' => 'Edit Business',
    //         'new_item' => 'New Business',
    //         'view_item' => 'View Business',
    //         'search_items' => 'Search Businesses',
    //         'not_found' => 'No businesses found',
    //         'not_found_in_trash' => 'No businesses found in trash',
    //         'menu_name' => 'Business Listings'
    //     ),
    //     'public' => true,
    //     'publicly_queryable' => true,
    //     'show_ui' => true,
    //     'show_in_menu' => true,
    //     'menu_icon' => 'dashicons-store',
    //     'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
    //     'capability_type' => 'post',
    //     'map_meta_cap' => true,
    //     'show_in_rest' => true
    // ));

    // Committee Post Type
    register_post_type('committee', array(
        'labels' => array(
            'name' => 'Committees',
            'singular_name' => 'Committee',
            'add_new' => 'Add New Committee',
            'add_new_item' => 'Add New Committee',
            'edit_item' => 'Edit Committee',
            'new_item' => 'New Committee',
            'view_item' => 'View Committee',
            'search_items' => 'Search Committees',
            'not_found' => 'No committees found',
            'not_found_in_trash' => 'No committees found in trash'
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'custom-fields'),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'show_in_rest' => true
    ));
    
    // Billing Statement Post Type
    register_post_type('billing_statement', array(
        'labels' => array(
            'name' => 'Billing Statements',
            'singular_name' => 'Billing Statement',
            'add_new' => 'Add New Billing Statement',
            'add_new_item' => 'Add New Billing Statement',
            'edit_item' => 'Edit Billing Statement',
            'new_item' => 'New Billing Statement',
            'view_item' => 'View Billing Statement',
            'search_items' => 'Search Billing Statements',
            'not_found' => 'No billing statements found',
            'not_found_in_trash' => 'No billing statements found in trash'
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-money-alt',
        'supports' => array('title', 'custom-fields'),
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
    
    // Property Meta Box
    add_meta_box(
        'property_details',
        'Property Details',
        'villa_property_meta_box_callback',
        'property',
        'normal',
        'high'
    );
    
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
    
    // Business Listing Meta Box
    // add_meta_box(
    //     'business_details',
    //     'Business Details',
    //     'villa_business_meta_box_callback',
    //     'business_listing',
    //     'normal',
    //     'high'
    // );
}
add_action('add_meta_boxes', 'villa_add_dashboard_meta_boxes');

/**
 * Property meta box callback
 */
function villa_property_meta_box_callback($post) {
    wp_nonce_field('villa_property_meta_box', 'villa_property_meta_box_nonce');
    
    $address = get_post_meta($post->ID, 'property_address', true);
    $type = get_post_meta($post->ID, 'property_type', true);
    $bedrooms = get_post_meta($post->ID, 'property_bedrooms', true);
    $bathrooms = get_post_meta($post->ID, 'property_bathrooms', true);
    $square_feet = get_post_meta($post->ID, 'property_square_feet', true);
    $listing_status = get_post_meta($post->ID, 'property_listing_status', true);
    $listing_price = get_post_meta($post->ID, 'property_listing_price', true);
    $owners = get_post_meta($post->ID, 'property_owners', true);
    
    // Get all users with owner-related roles
    $owner_users = get_users(array(
        'role__in' => array('owner', 'bod', 'administrator'),
        'orderby' => 'display_name',
        'order' => 'ASC'
    ));
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="property_owners">Property Owners</label></th>
            <td>
                <select id="property_owners" name="property_owners[]" multiple="multiple" style="width: 100%; min-height: 120px;">
                    <?php foreach ($owner_users as $user) : ?>
                        <option value="<?php echo esc_attr($user->ID); ?>" 
                                <?php echo (is_array($owners) && in_array($user->ID, $owners)) ? 'selected' : ''; ?>>
                            <?php echo esc_html($user->display_name . ' (' . $user->user_email . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description">Hold Ctrl/Cmd to select multiple owners. Leave empty for unassigned properties.</p>
            </td>
        </tr>
        <tr>
            <th><label for="property_address">Address</label></th>
            <td><input type="text" id="property_address" name="property_address" value="<?php echo esc_attr($address); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="property_type">Property Type</label></th>
            <td>
                <select id="property_type" name="property_type">
                    <option value="">Select Type</option>
                    <option value="villa" <?php selected($type, 'villa'); ?>>Villa</option>
                    <option value="condo" <?php selected($type, 'condo'); ?>>Condo</option>
                    <option value="townhouse" <?php selected($type, 'townhouse'); ?>>Townhouse</option>
                    <option value="apartment" <?php selected($type, 'apartment'); ?>>Apartment</option>
                    <option value="commercial" <?php selected($type, 'commercial'); ?>>Commercial</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="property_bedrooms">Bedrooms</label></th>
            <td><input type="number" id="property_bedrooms" name="property_bedrooms" value="<?php echo esc_attr($bedrooms); ?>" min="0" /></td>
        </tr>
        <tr>
            <th><label for="property_bathrooms">Bathrooms</label></th>
            <td><input type="number" id="property_bathrooms" name="property_bathrooms" value="<?php echo esc_attr($bathrooms); ?>" min="0" step="0.5" /></td>
        </tr>
        <tr>
            <th><label for="property_square_feet">Square Feet</label></th>
            <td><input type="number" id="property_square_feet" name="property_square_feet" value="<?php echo esc_attr($square_feet); ?>" min="0" /></td>
        </tr>
        <tr>
            <th><label for="property_listing_status">Listing Status</label></th>
            <td>
                <select id="property_listing_status" name="property_listing_status">
                    <option value="not_listed" <?php selected($listing_status, 'not_listed'); ?>>Not Listed</option>
                    <option value="for_sale" <?php selected($listing_status, 'for_sale'); ?>>For Sale</option>
                    <option value="for_rent" <?php selected($listing_status, 'for_rent'); ?>>For Rent</option>
                    <option value="sold" <?php selected($listing_status, 'sold'); ?>>Sold</option>
                    <option value="rented" <?php selected($listing_status, 'rented'); ?>>Rented</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="property_listing_price">Listing Price</label></th>
            <td><input type="number" id="property_listing_price" name="property_listing_price" value="<?php echo esc_attr($listing_price); ?>" min="0" step="0.01" /></td>
        </tr>
    </table>
    <?php
}

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
    if (!isset($_POST['villa_property_meta_box_nonce']) && !isset($_POST['villa_ticket_meta_box_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (isset($_POST['villa_property_meta_box_nonce'])) {
        if (!wp_verify_nonce($_POST['villa_property_meta_box_nonce'], 'villa_property_meta_box')) {
            return;
        }
        
        // Save property meta
        $property_fields = array(
            'property_address', 'property_type', 'property_bedrooms', 
            'property_bathrooms', 'property_square_feet', 'property_listing_status', 
            'property_listing_price'
        );
        
        foreach ($property_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
        
        // Handle property_owners separately as it's an array
        if (isset($_POST['property_owners']) && is_array($_POST['property_owners'])) {
            $owners = array_map('intval', $_POST['property_owners']);
            update_post_meta($post_id, 'property_owners', $owners);
        } else {
            // If no owners selected, clear the field
            update_post_meta($post_id, 'property_owners', array());
        }
    }
    
    if (isset($_POST['villa_ticket_meta_box_nonce'])) {
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

/**
 * Business listing meta box callback
 */
function villa_business_meta_box_callback($post) {
    wp_nonce_field('villa_business_meta_box', 'villa_business_meta_box_nonce');
    
    $type = get_post_meta($post->ID, 'business_type', true);
    $phone = get_post_meta($post->ID, 'business_phone', true);
    $website = get_post_meta($post->ID, 'business_website', true);
    $hours = get_post_meta($post->ID, 'business_hours', true);
    $address = get_post_meta($post->ID, 'business_address', true);
    $status = get_post_meta($post->ID, 'listing_status', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="business_type">Business Type</label></th>
            <td>
                <select id="business_type" name="business_type">
                    <option value="restaurant" <?php selected($type, 'restaurant'); ?>>Restaurant</option>
                    <option value="retail" <?php selected($type, 'retail'); ?>>Retail</option>
                    <option value="service" <?php selected($type, 'service'); ?>>Service</option>
                    <option value="fitness" <?php selected($type, 'fitness'); ?>>Fitness</option>
                    <option value="entertainment" <?php selected($type, 'entertainment'); ?>>Entertainment</option>
                    <option value="other" <?php selected($type, 'other'); ?>>Other</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="business_phone">Phone</label></th>
            <td><input type="tel" id="business_phone" name="business_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="business_website">Website</label></th>
            <td><input type="url" id="business_website" name="business_website" value="<?php echo esc_attr($website); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="business_address">Address</label></th>
            <td><input type="text" id="business_address" name="business_address" value="<?php echo esc_attr($address); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="business_hours">Business Hours</label></th>
            <td><textarea id="business_hours" name="business_hours" rows="3" class="large-text"><?php echo esc_textarea($hours); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="listing_status">Listing Status</label></th>
            <td>
                <select id="listing_status" name="listing_status">
                    <option value="active" <?php selected($status, 'active'); ?>>Active</option>
                    <option value="inactive" <?php selected($status, 'inactive'); ?>>Inactive</option>
                    <option value="pending" <?php selected($status, 'pending'); ?>>Pending Review</option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Add admin columns for properties
 */
add_filter('manage_villa_property_posts_columns', 'villa_property_admin_columns');
add_action('manage_villa_property_posts_custom_column', 'villa_property_admin_column_content', 10, 2);

/**
 * Add custom columns to property admin list
 */
function villa_property_admin_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['property_owners'] = 'Owners';
    $new_columns['property_address'] = 'Address';
    $new_columns['property_type'] = 'Type';
    $new_columns['property_listing_status'] = 'Status';
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}

/**
 * Display content for custom property admin columns
 */
function villa_property_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'property_owners':
            $owners = get_post_meta($post_id, 'property_owners', true);
            if (!empty($owners) && is_array($owners)) {
                $owner_names = array();
                foreach ($owners as $owner_id) {
                    $user = get_user_by('ID', $owner_id);
                    if ($user) {
                        $owner_names[] = $user->display_name;
                    }
                }
                echo esc_html(implode(', ', $owner_names));
            } else {
                echo '<em>Unassigned</em>';
            }
            break;
        case 'property_address':
            $address = get_post_meta($post_id, 'property_address', true);
            echo esc_html($address ?: '-');
            break;
        case 'property_type':
            $type = get_post_meta($post_id, 'property_type', true);
            echo esc_html(ucfirst($type ?: '-'));
            break;
        case 'property_listing_status':
            $status = get_post_meta($post_id, 'property_listing_status', true);
            echo esc_html(ucwords(str_replace('_', ' ', $status ?: 'not listed')));
            break;
    }
}

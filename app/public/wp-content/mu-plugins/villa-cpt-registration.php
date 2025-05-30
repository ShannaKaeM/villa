<?php
/**
 * Custom Post Type and Taxonomy Registration
 * 
 * Registers all custom post types and taxonomies for the Villa Community site
 */

// Don't allow direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the Property custom post type
 */
function mi_register_property_post_type() {
    $labels = array(
        'name'               => _x('Properties', 'post type general name', 'blocksy-child'),
        'singular_name'      => _x('Property', 'post type singular name', 'blocksy-child'),
        'menu_name'          => _x('Properties', 'admin menu', 'blocksy-child'),
        'name_admin_bar'     => _x('Property', 'add new on admin bar', 'blocksy-child'),
        'add_new'            => _x('Add New', 'property', 'blocksy-child'),
        'add_new_item'       => __('Add New Property', 'blocksy-child'),
        'new_item'           => __('New Property', 'blocksy-child'),
        'edit_item'          => __('Edit Property', 'blocksy-child'),
        'view_item'          => __('View Property', 'blocksy-child'),
        'all_items'          => __('All Properties', 'blocksy-child'),
        'search_items'       => __('Search Properties', 'blocksy-child'),
        'parent_item_colon'  => __('Parent Properties:', 'blocksy-child'),
        'not_found'          => __('No properties found.', 'blocksy-child'),
        'not_found_in_trash' => __('No properties found in Trash.', 'blocksy-child')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Property listings for rental or sale.', 'blocksy-child'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'property'),
        'capability_type'    => array('property', 'properties'),
        'capabilities'       => array(
            'create_posts'       => 'create_properties',
            'edit_post'          => 'edit_property',
            'edit_posts'         => 'edit_properties',
            'edit_others_posts'  => 'edit_others_properties',
            'publish_posts'      => 'publish_properties',
            'read_post'          => 'read_property',
            'read_private_posts' => 'read_private_properties',
            'delete_post'        => 'delete_property',
            'delete_posts'       => 'delete_properties'
        ),
        'map_meta_cap'       => true,
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-admin-home',
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest'       => true, // Enable Gutenberg editor
    );

    register_post_type('property', $args);
    
    // Add meta box for property owner assignment
    add_action('add_meta_boxes', 'villa_add_property_owner_meta_box');
    add_action('save_post', 'villa_save_property_owner_meta_box');
    
    // Add admin columns
    add_filter('manage_property_posts_columns', 'villa_property_admin_columns');
    add_action('manage_property_posts_custom_column', 'villa_property_admin_column_content', 10, 2);
    
    // Set up property capabilities
    add_action('admin_init', 'villa_setup_property_capabilities');
}
add_action('init', 'mi_register_property_post_type');

/**
 * Set up property capabilities - allow editing but not creating new properties
 */
function villa_setup_property_capabilities() {
    // Get roles that should be able to edit properties
    $roles_to_update = array('administrator', 'bod', 'owner');
    
    foreach ($roles_to_update as $role_name) {
        $role = get_role($role_name);
        if ($role) {
            // Grant editing capabilities
            $role->add_cap('edit_property');
            $role->add_cap('edit_properties');
            $role->add_cap('edit_others_properties');
            $role->add_cap('read_property');
            $role->add_cap('read_private_properties');
            
            // Only administrators can create/delete properties
            if ($role_name === 'administrator') {
                $role->add_cap('create_properties');
                $role->add_cap('publish_properties');
                $role->add_cap('delete_property');
                $role->add_cap('delete_properties');
            }
        }
    }
}

/**
 * Add property owner assignment meta box
 */
function villa_add_property_owner_meta_box() {
    add_meta_box(
        'villa_property_owners',
        'Property Owners & Details',
        'villa_property_owner_meta_box_callback',
        'property',
        'normal',
        'high'
    );
}

/**
 * Property owner meta box callback
 */
function villa_property_owner_meta_box_callback($post) {
    wp_nonce_field('villa_property_owner_meta_box', 'villa_property_owner_meta_box_nonce');
    
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
                    <option value="house" <?php selected($type, 'house'); ?>>House</option>
                    <option value="cottage" <?php selected($type, 'cottage'); ?>>Cottage</option>
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
 * Save property owner meta box data
 */
function villa_save_property_owner_meta_box($post_id) {
    // Check if nonce is valid
    if (!isset($_POST['villa_property_owner_meta_box_nonce'])) {
        return;
    }
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['villa_property_owner_meta_box_nonce'], 'villa_property_owner_meta_box')) {
        return;
    }
    
    // Check if user has permission to edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save property meta fields
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

/**
 * Add custom columns to properties admin list
 */
function villa_property_admin_columns($columns) {
    $new_columns = array();
    
    // Keep checkbox and title
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    
    // Add custom columns
    $new_columns['owners'] = 'Owners';
    $new_columns['address'] = 'Address';
    $new_columns['type'] = 'Type';
    $new_columns['status'] = 'Status';
    
    // Keep date
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}

/**
 * Display content for custom admin columns
 */
function villa_property_admin_column_content($column, $post_id) {
    switch ($column) {
        case 'owners':
            $owners = get_post_meta($post_id, 'property_owners', true);
            if (is_array($owners) && !empty($owners)) {
                $owner_names = array();
                foreach ($owners as $owner_id) {
                    $user = get_user_by('ID', $owner_id);
                    if ($user) {
                        $owner_names[] = $user->display_name;
                    }
                }
                echo esc_html(implode(', ', $owner_names));
            } else {
                echo '<span style="color: #999;">Unassigned</span>';
            }
            break;
            
        case 'address':
            $address = get_post_meta($post_id, 'property_address', true);
            echo $address ? esc_html($address) : '<span style="color: #999;">—</span>';
            break;
            
        case 'type':
            $type = get_post_meta($post_id, 'property_type', true);
            echo $type ? esc_html(ucfirst($type)) : '<span style="color: #999;">—</span>';
            break;
            
        case 'status':
            $status = get_post_meta($post_id, 'property_listing_status', true);
            if ($status) {
                $status_labels = array(
                    'not_listed' => 'Not Listed',
                    'for_sale' => 'For Sale',
                    'for_rent' => 'For Rent',
                    'sold' => 'Sold',
                    'rented' => 'Rented'
                );
                echo esc_html($status_labels[$status] ?? ucfirst(str_replace('_', ' ', $status)));
            } else {
                echo '<span style="color: #999;">—</span>';
            }
            break;
    }
}

/**
 * Register Property Type taxonomy
 */
function mi_register_property_type_taxonomy() {
    $labels = array(
        'name'              => _x('Property Types', 'taxonomy general name', 'blocksy-child'),
        'singular_name'     => _x('Property Type', 'taxonomy singular name', 'blocksy-child'),
        'search_items'      => __('Search Property Types', 'blocksy-child'),
        'all_items'         => __('All Property Types', 'blocksy-child'),
        'parent_item'       => __('Parent Property Type', 'blocksy-child'),
        'parent_item_colon' => __('Parent Property Type:', 'blocksy-child'),
        'edit_item'         => __('Edit Property Type', 'blocksy-child'),
        'update_item'       => __('Update Property Type', 'blocksy-child'),
        'add_new_item'      => __('Add New Property Type', 'blocksy-child'),
        'new_item_name'     => __('New Property Type Name', 'blocksy-child'),
        'menu_name'         => __('Property Types', 'blocksy-child'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'property-type'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('property_type', array('property'), $args);
}
add_action('init', 'mi_register_property_type_taxonomy');

/**
 * Register Location taxonomy
 */
function mi_register_location_taxonomy() {
    $labels = array(
        'name'              => _x('Locations', 'taxonomy general name', 'blocksy-child'),
        'singular_name'     => _x('Location', 'taxonomy singular name', 'blocksy-child'),
        'search_items'      => __('Search Locations', 'blocksy-child'),
        'all_items'         => __('All Locations', 'blocksy-child'),
        'parent_item'       => __('Parent Location', 'blocksy-child'),
        'parent_item_colon' => __('Parent Location:', 'blocksy-child'),
        'edit_item'         => __('Edit Location', 'blocksy-child'),
        'update_item'       => __('Update Location', 'blocksy-child'),
        'add_new_item'      => __('Add New Location', 'blocksy-child'),
        'new_item_name'     => __('New Location Name', 'blocksy-child'),
        'menu_name'         => __('Locations', 'blocksy-child'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'location'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('location', array('property', 'business', 'user_profile', 'article'), $args);
}
add_action('init', 'mi_register_location_taxonomy');

/**
 * Register Amenity taxonomy
 */
function mi_register_amenity_taxonomy() {
    $labels = array(
        'name'              => _x('Amenities', 'taxonomy general name', 'blocksy-child'),
        'singular_name'     => _x('Amenity', 'taxonomy singular name', 'blocksy-child'),
        'search_items'      => __('Search Amenities', 'blocksy-child'),
        'all_items'         => __('All Amenities', 'blocksy-child'),
        'parent_item'       => __('Parent Amenity', 'blocksy-child'),
        'parent_item_colon' => __('Parent Amenity:', 'blocksy-child'),
        'edit_item'         => __('Edit Amenity', 'blocksy-child'),
        'update_item'       => __('Update Amenity', 'blocksy-child'),
        'add_new_item'      => __('Add New Amenity', 'blocksy-child'),
        'new_item_name'     => __('New Amenity Name', 'blocksy-child'),
        'menu_name'         => __('Amenities', 'blocksy-child'),
    );

    $args = array(
        'hierarchical'      => true, // Making it hierarchical (like categories)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'amenity'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('amenity', array('property'), $args);
}
add_action('init', 'mi_register_amenity_taxonomy');

/**
 * Register Business Type taxonomy
 */
function mi_register_business_type_taxonomy() {
    $labels = array(
        'name'              => _x('Business Types', 'taxonomy general name', 'blocksy-child'),
        'singular_name'     => _x('Business Type', 'taxonomy singular name', 'blocksy-child'),
        'search_items'      => __('Search Business Types', 'blocksy-child'),
        'all_items'         => __('All Business Types', 'blocksy-child'),
        'parent_item'       => __('Parent Business Type', 'blocksy-child'),
        'parent_item_colon' => __('Parent Business Type:', 'blocksy-child'),
        'edit_item'         => __('Edit Business Type', 'blocksy-child'),
        'update_item'       => __('Update Business Type', 'blocksy-child'),
        'add_new_item'      => __('Add New Business Type', 'blocksy-child'),
        'new_item_name'     => __('New Business Type Name', 'blocksy-child'),
        'menu_name'         => __('Business Types', 'blocksy-child'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'business-type'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('business_type', array('business'), $args);
}
add_action('init', 'mi_register_business_type_taxonomy');

/**
 * Register Article Type taxonomy
 */
function mi_register_article_type_taxonomy() {
    $labels = array(
        'name'              => _x('Article Types', 'taxonomy general name', 'blocksy-child'),
        'singular_name'     => _x('Article Type', 'taxonomy singular name', 'blocksy-child'),
        'search_items'      => __('Search Article Types', 'blocksy-child'),
        'all_items'         => __('All Article Types', 'blocksy-child'),
        'parent_item'       => __('Parent Article Type', 'blocksy-child'),
        'parent_item_colon' => __('Parent Article Type:', 'blocksy-child'),
        'edit_item'         => __('Edit Article Type', 'blocksy-child'),
        'update_item'       => __('Update Article Type', 'blocksy-child'),
        'add_new_item'      => __('Add New Article Type', 'blocksy-child'),
        'new_item_name'     => __('New Article Type Name', 'blocksy-child'),
        'menu_name'         => __('Article Types', 'blocksy-child'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'article-type'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('article_type', array('article'), $args); // Attached to article post type
}
add_action('init', 'mi_register_article_type_taxonomy');

/**
 * Register User Type taxonomy
 */
function mi_register_user_type_taxonomy() {
    $labels = array(
        'name'              => _x('User Types', 'taxonomy general name', 'blocksy-child'),
        'singular_name'     => _x('User Type', 'taxonomy singular name', 'blocksy-child'),
        'search_items'      => __('Search User Types', 'blocksy-child'),
        'all_items'         => __('All User Types', 'blocksy-child'),
        'parent_item'       => __('Parent User Type', 'blocksy-child'),
        'parent_item_colon' => __('Parent User Type:', 'blocksy-child'),
        'edit_item'         => __('Edit User Type', 'blocksy-child'),
        'update_item'       => __('Update User Type', 'blocksy-child'),
        'add_new_item'      => __('Add New User Type', 'blocksy-child'),
        'new_item_name'     => __('New User Type Name', 'blocksy-child'),
        'menu_name'         => __('User Types', 'blocksy-child'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'user-type'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('user_type', array('user_profile'), $args); // For user profiles
}
add_action('init', 'mi_register_user_type_taxonomy');

/**
 * Register Business custom post type
 */
function mi_register_business_post_type() {
    $labels = array(
        'name'               => _x('Businesses', 'post type general name', 'blocksy-child'),
        'singular_name'      => _x('Business', 'post type singular name', 'blocksy-child'),
        'menu_name'          => _x('Businesses', 'admin menu', 'blocksy-child'),
        'name_admin_bar'     => _x('Business', 'add new on admin bar', 'blocksy-child'),
        'add_new'            => _x('Add New', 'business', 'blocksy-child'),
        'add_new_item'       => __('Add New Business', 'blocksy-child'),
        'new_item'           => __('New Business', 'blocksy-child'),
        'edit_item'          => __('Edit Business', 'blocksy-child'),
        'view_item'          => __('View Business', 'blocksy-child'),
        'all_items'          => __('All Businesses', 'blocksy-child'),
        'search_items'       => __('Search Businesses', 'blocksy-child'),
        'parent_item_colon'  => __('Parent Businesses:', 'blocksy-child'),
        'not_found'          => __('No businesses found.', 'blocksy-child'),
        'not_found_in_trash' => __('No businesses found in Trash.', 'blocksy-child')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Local businesses in the community.', 'blocksy-child'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'business'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-store',
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest'       => true, // Enable Gutenberg editor
    );

    register_post_type('business', $args);
}
add_action('init', 'mi_register_business_post_type');

/**
 * Register Article custom post type
 */
function mi_register_article_post_type() {
    $labels = array(
        'name'               => _x('Articles', 'post type general name', 'blocksy-child'),
        'singular_name'      => _x('Article', 'post type singular name', 'blocksy-child'),
        'menu_name'          => _x('Articles', 'admin menu', 'blocksy-child'),
        'name_admin_bar'     => _x('Article', 'add new on admin bar', 'blocksy-child'),
        'add_new'            => _x('Add New', 'article', 'blocksy-child'),
        'add_new_item'       => __('Add New Article', 'blocksy-child'),
        'new_item'           => __('New Article', 'blocksy-child'),
        'edit_item'          => __('Edit Article', 'blocksy-child'),
        'view_item'          => __('View Article', 'blocksy-child'),
        'all_items'          => __('All Articles', 'blocksy-child'),
        'search_items'       => __('Search Articles', 'blocksy-child'),
        'parent_item_colon'  => __('Parent Articles:', 'blocksy-child'),
        'not_found'          => __('No articles found.', 'blocksy-child'),
        'not_found_in_trash' => __('No articles found in Trash.', 'blocksy-child')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Articles, guides, and news for the community.', 'blocksy-child'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'article'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-media-document',
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest'       => true, // Enable Gutenberg editor
    );

    register_post_type('article', $args);
}
add_action('init', 'mi_register_article_post_type');

/**
 * Register User Profile custom post type
 */
function mi_register_user_profile_post_type() {
    $labels = array(
        'name'               => _x('User Profiles', 'post type general name', 'blocksy-child'),
        'singular_name'      => _x('User Profile', 'post type singular name', 'blocksy-child'),
        'menu_name'          => _x('User Profiles', 'admin menu', 'blocksy-child'),
        'name_admin_bar'     => _x('User Profile', 'add new on admin bar', 'blocksy-child'),
        'add_new'            => _x('Add New', 'user profile', 'blocksy-child'),
        'add_new_item'       => __('Add New User Profile', 'blocksy-child'),
        'new_item'           => __('New User Profile', 'blocksy-child'),
        'edit_item'          => __('Edit User Profile', 'blocksy-child'),
        'view_item'          => __('View User Profile', 'blocksy-child'),
        'all_items'          => __('All User Profiles', 'blocksy-child'),
        'search_items'       => __('Search User Profiles', 'blocksy-child'),
        'parent_item_colon'  => __('Parent User Profiles:', 'blocksy-child'),
        'not_found'          => __('No user profiles found.', 'blocksy-child'),
        'not_found_in_trash' => __('No user profiles found in Trash.', 'blocksy-child')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('User profiles for community members.', 'blocksy-child'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'user-profile'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 8,
        'menu_icon'          => 'dashicons-admin-users',
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest'       => true, // Enable Gutenberg editor
    );

    register_post_type('user_profile', $args);
}
add_action('init', 'mi_register_user_profile_post_type');

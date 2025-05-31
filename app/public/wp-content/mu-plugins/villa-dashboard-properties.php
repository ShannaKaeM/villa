<?php
/**
 * Villa Dashboard - Property Management Module
 * Handles property ownership, listings, and management
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render properties dashboard tab
 */
function villa_render_dashboard_properties($user) {
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
    $property_id = isset($_GET['property_id']) ? intval($_GET['property_id']) : 0;
    
    switch ($action) {
        case 'new':
            villa_render_property_form($user);
            break;
        case 'edit':
            villa_render_property_form($user, $property_id);
            break;
        case 'view':
            villa_render_property_details($user, $property_id);
            break;
        default:
            villa_render_properties_list($user);
    }
}

/**
 * Render user's properties list
 */
function villa_render_properties_list($user) {
    $user_properties = villa_get_user_properties($user->ID);
    ?>
    <div class="properties-dashboard">
        <div class="properties-header">
            <h2>My Properties</h2>
        </div>
        
        <?php if (empty($user_properties)): ?>
            <div class="no-properties">
                <p>You don't have any properties yet.</p>
            </div>
        <?php else: ?>
            <div class="properties-grid">
                <?php foreach ($user_properties as $property): ?>
                    <div class="villa-card property-card">
                        <?php if (has_post_thumbnail($property->ID)): ?>
                            <div class="villa-card__image property-image">
                                <?php echo get_the_post_thumbnail($property->ID, 'medium'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="villa-card__content property-info">
                            <h3 class="villa-card__title"><?php echo esc_html($property->post_title); ?></h3>
                            
                            <?php
                            $address = get_post_meta($property->ID, 'property_address', true);
                            $status = get_post_meta($property->ID, 'property_status', true);
                            $listing_status = get_post_meta($property->ID, 'property_listing_status', true);
                            $open_tickets = villa_count_property_tickets($property->ID);
                            ?>
                            
                            <?php if ($address): ?>
                                <p class="villa-card__subtitle property-address"><?php echo esc_html($address); ?></p>
                            <?php endif; ?>
                            
                            <div class="villa-card__meta property-meta">
                                <?php if ($status): ?>
                                    <span class="villa-card__tag villa-card__tag--<?php echo esc_attr($status); ?> property-status status-<?php echo esc_attr($status); ?>">
                                        <?php echo esc_html(ucwords(str_replace('_', ' ', $status))); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($listing_status && $listing_status !== 'not_listed'): ?>
                                    <span class="villa-card__tag villa-card__tag--<?php echo esc_attr($listing_status); ?> listing-status listing-<?php echo esc_attr($listing_status); ?>">
                                        <?php echo esc_html(ucwords(str_replace('_', ' ', $listing_status))); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($open_tickets > 0): ?>
                                    <span class="villa-card__tag villa-card__tag--tickets ticket-count">
                                        <?php echo $open_tickets; ?> Open Tickets
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="villa-card__actions property-actions">
                                <a href="?tab=properties&action=view&property_id=<?php echo $property->ID; ?>" class="villa-card__button button">View</a>
                                <a href="?tab=properties&action=edit&property_id=<?php echo $property->ID; ?>" class="villa-card__button button">Edit</a>
                                <a href="?tab=tickets&property_id=<?php echo $property->ID; ?>" class="villa-card__button villa-card__button--secondary button">Tickets</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Render property form (add/edit)
 */
function villa_render_property_form($user, $property_id = 0) {
    $is_edit = $property_id > 0;
    $property = $is_edit ? get_post($property_id) : null;
    
    // Verify ownership for edit
    if ($is_edit && !villa_user_owns_property($user->ID, $property_id)) {
        echo '<div class="error">You do not have permission to edit this property.</div>';
        return;
    }
    
    // Handle form submission
    if ($_POST && wp_verify_nonce($_POST['villa_property_nonce'], 'villa_property_form')) {
        $result = villa_save_property_form($user->ID, $_POST, $property_id);
        if ($result['success']) {
            echo '<div class="success">Property saved successfully!</div>';
            if (!$is_edit) {
                echo '<script>window.location.href = "?tab=properties&action=view&property_id=' . $result['property_id'] . '";</script>';
            }
        } else {
            echo '<div class="error">' . esc_html($result['message']) . '</div>';
        }
    }
    
    // Get existing data
    $title = $is_edit ? $property->post_title : '';
    $description = $is_edit ? $property->post_content : '';
    $address = $is_edit ? get_post_meta($property_id, 'property_address', true) : '';
    $type = $is_edit ? get_post_meta($property_id, 'property_type', true) : '';
    $bedrooms = $is_edit ? get_post_meta($property_id, 'property_bedrooms', true) : '';
    $bathrooms = $is_edit ? get_post_meta($property_id, 'property_bathrooms', true) : '';
    $square_feet = $is_edit ? get_post_meta($property_id, 'property_square_feet', true) : '';
    $listing_status = $is_edit ? get_post_meta($property_id, 'property_listing_status', true) : 'not_listed';
    $listing_price = $is_edit ? get_post_meta($property_id, 'property_listing_price', true) : '';
    $listing_type = $is_edit ? get_post_meta($property_id, 'property_listing_type', true) : '';
    
    ?>
    <div class="property-form-container">
        <div class="form-header">
            <a href="?tab=properties" class="button">← Back to Properties</a>
        </div>
        
        <form method="post" class="property-form" enctype="multipart/form-data">
            <?php wp_nonce_field('villa_property_form', 'villa_property_nonce'); ?>
            
            <div class="form-section">
                <h3>Basic Information</h3>
                
                <div class="form-row">
                    <label for="property_title">Property Name/Title *</label>
                    <input type="text" id="property_title" name="property_title" value="<?php echo esc_attr($title); ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="property_address">Address *</label>
                    <input type="text" id="property_address" name="property_address" value="<?php echo esc_attr($address); ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="property_type">Property Type</label>
                    <select id="property_type" name="property_type">
                        <option value="">Select Type</option>
                        <option value="villa" <?php selected($type, 'villa'); ?>>Villa</option>
                        <option value="condo" <?php selected($type, 'condo'); ?>>Condo</option>
                        <option value="townhouse" <?php selected($type, 'townhouse'); ?>>Townhouse</option>
                        <option value="apartment" <?php selected($type, 'apartment'); ?>>Apartment</option>
                        <option value="commercial" <?php selected($type, 'commercial'); ?>>Commercial</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <label for="property_description">Description</label>
                    <textarea id="property_description" name="property_description" rows="4"><?php echo esc_textarea($description); ?></textarea>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Property Details</h3>
                
                <div class="form-row-group">
                    <div class="form-row">
                        <label for="property_bedrooms">Bedrooms</label>
                        <input type="number" id="property_bedrooms" name="property_bedrooms" value="<?php echo esc_attr($bedrooms); ?>" min="0">
                    </div>
                    
                    <div class="form-row">
                        <label for="property_bathrooms">Bathrooms</label>
                        <input type="number" id="property_bathrooms" name="property_bathrooms" value="<?php echo esc_attr($bathrooms); ?>" min="0" step="0.5">
                    </div>
                    
                    <div class="form-row">
                        <label for="property_square_feet">Square Feet</label>
                        <input type="number" id="property_square_feet" name="property_square_feet" value="<?php echo esc_attr($square_feet); ?>" min="0">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Listing Options</h3>
                
                <div class="form-row">
                    <label for="property_listing_status">Listing Status</label>
                    <select id="property_listing_status" name="property_listing_status">
                        <option value="not_listed" <?php selected($listing_status, 'not_listed'); ?>>Not Listed</option>
                        <option value="for_sale" <?php selected($listing_status, 'for_sale'); ?>>For Sale</option>
                        <option value="for_rent" <?php selected($listing_status, 'for_rent'); ?>>For Rent</option>
                        <option value="sold" <?php selected($listing_status, 'sold'); ?>>Sold</option>
                        <option value="rented" <?php selected($listing_status, 'rented'); ?>>Rented</option>
                    </select>
                </div>
                
                <div class="form-row listing-price-row" style="<?php echo $listing_status === 'not_listed' ? 'display:none;' : ''; ?>">
                    <label for="property_listing_price">Listing Price</label>
                    <input type="number" id="property_listing_price" name="property_listing_price" value="<?php echo esc_attr($listing_price); ?>" min="0" step="0.01">
                </div>
            </div>
            
            <div class="form-section">
                <h3>Property Images</h3>
                <div class="form-row">
                    <label for="property_images">Upload Images</label>
                    <input type="file" id="property_images" name="property_images[]" multiple accept="image/*">
                    <p class="form-help">You can select multiple images to upload.</p>
                </div>
                
                <?php if ($is_edit): ?>
                    <div class="existing-images">
                        <?php villa_display_property_images($property_id); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="button button-primary">
                    <?php echo $is_edit ? 'Update Property' : 'Save Property'; ?>
                </button>
                <a href="?tab=properties" class="button">Cancel</a>
            </div>
        </form>
    </div>
    
    <script>
    document.getElementById('property_listing_status').addEventListener('change', function() {
        const priceRow = document.querySelector('.listing-price-row');
        if (this.value === 'not_listed') {
            priceRow.style.display = 'none';
        } else {
            priceRow.style.display = 'block';
        }
    });
    </script>
    <?php
}

/**
 * Get properties owned by user
 */
function villa_get_user_properties($user_id) {
    return get_posts(array(
        'post_type' => 'property',
        'meta_query' => array(
            array(
                'key' => 'property_owners',
                'value' => $user_id,
                'compare' => 'LIKE'
            )
        ),
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));
}

/**
 * Check if user owns property
 */
function villa_user_owns_property($user_id, $property_id) {
    $owners = get_post_meta($property_id, 'property_owners', true);
    if (is_array($owners)) {
        return in_array($user_id, $owners);
    }
    return false;
}

/**
 * Count tickets for a property
 */
function villa_count_property_tickets($property_id) {
    $tickets = get_posts(array(
        'post_type' => 'support_ticket',
        'meta_query' => array(
            array(
                'key' => 'ticket_property_id',
                'value' => $property_id,
                'compare' => '='
            ),
            array(
                'key' => 'ticket_status',
                'value' => array('open', 'in_progress'),
                'compare' => 'IN'
            )
        ),
        'posts_per_page' => -1
    ));
    return count($tickets);
}

/**
 * Save property form data
 */
function villa_save_property_form($user_id, $form_data, $property_id = 0) {
    $is_edit = $property_id > 0;
    
    // Validate required fields
    if (empty($form_data['property_title']) || empty($form_data['property_address'])) {
        return array('success' => false, 'message' => 'Title and address are required.');
    }
    
    // Prepare post data
    $post_data = array(
        'post_title' => sanitize_text_field($form_data['property_title']),
        'post_content' => sanitize_textarea_field($form_data['property_description']),
        'post_type' => 'property',
        'post_status' => 'publish'
    );
    
    if ($is_edit) {
        $post_data['ID'] = $property_id;
        $result = wp_update_post($post_data);
    } else {
        $post_data['post_author'] = $user_id;
        $result = wp_insert_post($post_data);
        $property_id = $result;
    }
    
    if (is_wp_error($result)) {
        return array('success' => false, 'message' => 'Error saving property.');
    }
    
    // Save meta fields
    update_post_meta($property_id, 'property_address', sanitize_text_field($form_data['property_address']));
    update_post_meta($property_id, 'property_type', sanitize_text_field($form_data['property_type']));
    update_post_meta($property_id, 'property_bedrooms', intval($form_data['property_bedrooms']));
    update_post_meta($property_id, 'property_bathrooms', floatval($form_data['property_bathrooms']));
    update_post_meta($property_id, 'property_square_feet', intval($form_data['property_square_feet']));
    update_post_meta($property_id, 'property_listing_status', sanitize_text_field($form_data['property_listing_status']));
    update_post_meta($property_id, 'property_listing_price', floatval($form_data['property_listing_price']));
    
    // Set property owners (include current user if not edit)
    if (!$is_edit) {
        update_post_meta($property_id, 'property_owners', array($user_id));
    }
    
    // Handle image uploads
    if (!empty($_FILES['property_images']['name'][0])) {
        villa_handle_property_image_uploads($property_id, $_FILES['property_images']);
    }
    
    return array('success' => true, 'property_id' => $property_id);
}

/**
 * Handle property image uploads
 */
function villa_handle_property_image_uploads($property_id, $files) {
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    
    $uploaded_images = array();
    
    foreach ($files['name'] as $key => $value) {
        if ($files['name'][$key]) {
            $file = array(
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            );
            
            $upload = wp_handle_upload($file, array('test_form' => false));
            
            if (!isset($upload['error'])) {
                $attachment = array(
                    'post_mime_type' => $upload['type'],
                    'post_title' => sanitize_file_name($upload['file']),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
                
                $attachment_id = wp_insert_attachment($attachment, $upload['file'], $property_id);
                
                if (!is_wp_error($attachment_id)) {
                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
                    wp_update_attachment_metadata($attachment_id, $attachment_data);
                    $uploaded_images[] = $attachment_id;
                    
                    // Set first image as featured image
                    if (empty(get_post_thumbnail_id($property_id))) {
                        set_post_thumbnail($property_id, $attachment_id);
                    }
                }
            }
        }
    }
    
    return $uploaded_images;
}

/**
 * Display property images
 */
function villa_display_property_images($property_id) {
    $attachments = get_attached_media('image', $property_id);
    
    if (empty($attachments)) {
        echo '<p>No images uploaded yet.</p>';
        return;
    }
    
    echo '<div class="property-images-grid">';
    foreach ($attachments as $attachment) {
        echo '<div class="property-image-item">';
        echo wp_get_attachment_image($attachment->ID, 'thumbnail');
        echo '<button type="button" class="remove-image" data-attachment-id="' . $attachment->ID . '">Remove</button>';
        echo '</div>';
    }
    echo '</div>';
}

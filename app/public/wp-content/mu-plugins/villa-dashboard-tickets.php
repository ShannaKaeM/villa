<?php
/**
 * Villa Dashboard - Support Tickets Module
 * Handles work orders and support tickets tied to properties
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render tickets dashboard tab
 */
function villa_render_dashboard_tickets($user) {
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
    $ticket_id = isset($_GET['ticket_id']) ? intval($_GET['ticket_id']) : 0;
    $property_id = isset($_GET['property_id']) ? intval($_GET['property_id']) : 0;
    
    switch ($action) {
        case 'new':
            villa_render_ticket_form($user, 0, $property_id);
            break;
        case 'edit':
            villa_render_ticket_form($user, $ticket_id);
            break;
        case 'view':
            villa_render_ticket_details($user, $ticket_id);
            break;
        default:
            villa_render_tickets_list($user, $property_id);
    }
}

/**
 * Render user's tickets list
 */
function villa_render_tickets_list($user, $property_id = 0) {
    $user_tickets = villa_get_user_tickets($user->ID, $property_id);
    $user_properties = villa_get_user_properties($user->ID);
    
    ?>
    <div class="tickets-dashboard">
        <div class="tickets-header">
            <h2>Support Tickets</h2>
            <div class="header-actions">
                <?php if ($property_id): ?>
                    <span class="filter-info">Showing tickets for: <?php echo esc_html(get_the_title($property_id)); ?></span>
                    <a href="?tab=tickets" class="button">Show All Tickets</a>
                <?php endif; ?>
                <a href="?tab=tickets&action=new<?php echo $property_id ? '&property_id=' . $property_id : ''; ?>" class="button button-primary">Submit New Ticket</a>
            </div>
        </div>
        
        <?php if (!empty($user_properties)): ?>
        <div class="tickets-filters">
            <label for="property-filter">Filter by Property:</label>
            <select id="property-filter" onchange="filterByProperty(this.value)">
                <option value="">All Properties</option>
                <?php foreach ($user_properties as $property): ?>
                    <option value="<?php echo $property->ID; ?>" <?php selected($property_id, $property->ID); ?>>
                        <?php echo esc_html($property->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        
        <?php if (empty($user_tickets)): ?>
            <div class="no-tickets">
                <p><?php echo $property_id ? 'No tickets found for this property.' : 'You haven\'t submitted any tickets yet.'; ?></p>
                <a href="?tab=tickets&action=new<?php echo $property_id ? '&property_id=' . $property_id : ''; ?>" class="button">Submit Your First Ticket</a>
            </div>
        <?php else: ?>
            <div class="tickets-list">
                <?php foreach ($user_tickets as $ticket): ?>
                    <?php
                    $ticket_property_id = get_post_meta($ticket->ID, 'ticket_property_id', true);
                    $ticket_type = get_post_meta($ticket->ID, 'ticket_type', true);
                    $ticket_priority = get_post_meta($ticket->ID, 'ticket_priority', true);
                    $ticket_status = get_post_meta($ticket->ID, 'ticket_status', true);
                    $ticket_category = get_post_meta($ticket->ID, 'ticket_category', true);
                    $last_update = get_post_meta($ticket->ID, 'ticket_last_update', true);
                    ?>
                    
                    <div class="ticket-card ticket-status-<?php echo esc_attr($ticket_status); ?>">
                        <div class="ticket-header">
                            <h3>
                                <a href="?tab=tickets&action=view&ticket_id=<?php echo $ticket->ID; ?>">
                                    <?php echo esc_html($ticket->post_title); ?>
                                </a>
                            </h3>
                            <div class="ticket-meta">
                                <span class="ticket-id">#<?php echo $ticket->ID; ?></span>
                                <span class="ticket-date"><?php echo get_the_date('M j, Y', $ticket); ?></span>
                            </div>
                        </div>
                        
                        <div class="ticket-info">
                            <?php if ($ticket_property_id): ?>
                                <div class="ticket-property">
                                    <strong>Property:</strong> 
                                    <a href="?tab=properties&action=view&property_id=<?php echo $ticket_property_id; ?>">
                                        <?php echo esc_html(get_the_title($ticket_property_id)); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="ticket-details">
                                <span class="ticket-type type-<?php echo esc_attr($ticket_type); ?>">
                                    <?php echo esc_html(ucwords(str_replace('_', ' ', $ticket_type))); ?>
                                </span>
                                
                                <span class="ticket-category">
                                    <?php echo esc_html(ucwords(str_replace('_', ' ', $ticket_category))); ?>
                                </span>
                                
                                <span class="ticket-priority priority-<?php echo esc_attr($ticket_priority); ?>">
                                    <?php echo esc_html(ucwords($ticket_priority)); ?> Priority
                                </span>
                                
                                <span class="ticket-status status-<?php echo esc_attr($ticket_status); ?>">
                                    <?php echo esc_html(ucwords(str_replace('_', ' ', $ticket_status))); ?>
                                </span>
                            </div>
                            
                            <div class="ticket-excerpt">
                                <?php echo wp_trim_words($ticket->post_content, 20); ?>
                            </div>
                            
                            <?php if ($last_update): ?>
                                <div class="ticket-last-update">
                                    Last updated: <?php echo date('M j, Y g:i A', strtotime($last_update)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="ticket-actions">
                            <a href="?tab=tickets&action=view&ticket_id=<?php echo $ticket->ID; ?>" class="button">View Details</a>
                            <?php if ($ticket_status === 'open' || $ticket_status === 'in_progress'): ?>
                                <a href="?tab=tickets&action=edit&ticket_id=<?php echo $ticket->ID; ?>" class="button">Update</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
    function filterByProperty(propertyId) {
        const url = new URL(window.location);
        if (propertyId) {
            url.searchParams.set('property_id', propertyId);
        } else {
            url.searchParams.delete('property_id');
        }
        window.location.href = url.toString();
    }
    </script>
    <?php
}

/**
 * Render ticket form (new/edit)
 */
function villa_render_ticket_form($user, $ticket_id = 0, $property_id = 0) {
    $is_edit = $ticket_id > 0;
    $ticket = $is_edit ? get_post($ticket_id) : null;
    $user_properties = villa_get_user_properties($user->ID);
    
    // Verify ownership for edit
    if ($is_edit && $ticket->post_author != $user->ID) {
        echo '<div class="error">You do not have permission to edit this ticket.</div>';
        return;
    }
    
    // Handle form submission
    if ($_POST && wp_verify_nonce($_POST['villa_ticket_nonce'], 'villa_ticket_form')) {
        $result = villa_save_ticket_form($user->ID, $_POST, $ticket_id);
        if ($result['success']) {
            echo '<div class="success">Ticket saved successfully!</div>';
            if (!$is_edit) {
                echo '<script>window.location.href = "?tab=tickets&action=view&ticket_id=' . $result['ticket_id'] . '";</script>';
            }
        } else {
            echo '<div class="error">' . esc_html($result['message']) . '</div>';
        }
    }
    
    // Get existing data
    $title = $is_edit ? $ticket->post_title : '';
    $description = $is_edit ? $ticket->post_content : '';
    $ticket_property_id = $is_edit ? get_post_meta($ticket_id, 'ticket_property_id', true) : $property_id;
    $ticket_type = $is_edit ? get_post_meta($ticket_id, 'ticket_type', true) : '';
    $ticket_category = $is_edit ? get_post_meta($ticket_id, 'ticket_category', true) : '';
    $ticket_priority = $is_edit ? get_post_meta($ticket_id, 'ticket_priority', true) : 'medium';
    
    ?>
    <div class="ticket-form-container">
        <div class="form-header">
            <h2><?php echo $is_edit ? 'Update Ticket' : 'Submit New Ticket'; ?></h2>
            <a href="?tab=tickets" class="button">← Back to Tickets</a>
        </div>
        
        <form method="post" class="ticket-form" enctype="multipart/form-data">
            <?php wp_nonce_field('villa_ticket_form', 'villa_ticket_nonce'); ?>
            
            <div class="form-section">
                <h3>Ticket Information</h3>
                
                <div class="form-row">
                    <label for="ticket_title">Subject *</label>
                    <input type="text" id="ticket_title" name="ticket_title" value="<?php echo esc_attr($title); ?>" required>
                </div>
                
                <div class="form-row">
                    <label for="ticket_property_id">Related Property</label>
                    <select id="ticket_property_id" name="ticket_property_id">
                        <option value="">Select Property (Optional)</option>
                        <?php foreach ($user_properties as $property): ?>
                            <option value="<?php echo $property->ID; ?>" <?php selected($ticket_property_id, $property->ID); ?>>
                                <?php echo esc_html($property->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-row-group">
                    <div class="form-row">
                        <label for="ticket_type">Ticket Type *</label>
                        <select id="ticket_type" name="ticket_type" required>
                            <option value="">Select Type</option>
                            <option value="work_order" <?php selected($ticket_type, 'work_order'); ?>>Work Order</option>
                            <option value="maintenance" <?php selected($ticket_type, 'maintenance'); ?>>Maintenance Request</option>
                            <option value="support" <?php selected($ticket_type, 'support'); ?>>General Support</option>
                            <option value="complaint" <?php selected($ticket_type, 'complaint'); ?>>Complaint</option>
                            <option value="suggestion" <?php selected($ticket_type, 'suggestion'); ?>>Suggestion</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <label for="ticket_category">Category</label>
                        <select id="ticket_category" name="ticket_category">
                            <option value="">Select Category</option>
                            <option value="plumbing" <?php selected($ticket_category, 'plumbing'); ?>>Plumbing</option>
                            <option value="electrical" <?php selected($ticket_category, 'electrical'); ?>>Electrical</option>
                            <option value="hvac" <?php selected($ticket_category, 'hvac'); ?>>HVAC</option>
                            <option value="landscaping" <?php selected($ticket_category, 'landscaping'); ?>>Landscaping</option>
                            <option value="security" <?php selected($ticket_category, 'security'); ?>>Security</option>
                            <option value="cleaning" <?php selected($ticket_category, 'cleaning'); ?>>Cleaning</option>
                            <option value="amenities" <?php selected($ticket_category, 'amenities'); ?>>Amenities</option>
                            <option value="administrative" <?php selected($ticket_category, 'administrative'); ?>>Administrative</option>
                            <option value="other" <?php selected($ticket_category, 'other'); ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <label for="ticket_priority">Priority *</label>
                        <select id="ticket_priority" name="ticket_priority" required>
                            <option value="low" <?php selected($ticket_priority, 'low'); ?>>Low</option>
                            <option value="medium" <?php selected($ticket_priority, 'medium'); ?>>Medium</option>
                            <option value="high" <?php selected($ticket_priority, 'high'); ?>>High</option>
                            <option value="urgent" <?php selected($ticket_priority, 'urgent'); ?>>Urgent</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <label for="ticket_description">Description *</label>
                    <textarea id="ticket_description" name="ticket_description" rows="6" required placeholder="Please provide detailed information about your request or issue..."><?php echo esc_textarea($description); ?></textarea>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Attachments</h3>
                <div class="form-row">
                    <label for="ticket_attachments">Upload Files</label>
                    <input type="file" id="ticket_attachments" name="ticket_attachments[]" multiple accept="image/*,.pdf,.doc,.docx">
                    <p class="form-help">You can upload images, PDFs, or documents to help explain your request.</p>
                </div>
                
                <?php if ($is_edit): ?>
                    <div class="existing-attachments">
                        <?php villa_display_ticket_attachments($ticket_id); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="button button-primary">
                    <?php echo $is_edit ? 'Update Ticket' : 'Submit Ticket'; ?>
                </button>
                <a href="?tab=tickets" class="button">Cancel</a>
            </div>
        </form>
    </div>
    <?php
}

/**
 * Get tickets for user
 */
function villa_get_user_tickets($user_id, $property_id = 0) {
    $args = array(
        'post_type' => 'support_ticket',
        'author' => $user_id,
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    if ($property_id) {
        $args['meta_query'] = array(
            array(
                'key' => 'ticket_property_id',
                'value' => $property_id,
                'compare' => '='
            )
        );
    }
    
    return get_posts($args);
}

/**
 * Save ticket form data
 */
function villa_save_ticket_form($user_id, $form_data, $ticket_id = 0) {
    $is_edit = $ticket_id > 0;
    
    // Validate required fields
    if (empty($form_data['ticket_title']) || empty($form_data['ticket_description']) || 
        empty($form_data['ticket_type']) || empty($form_data['ticket_priority'])) {
        return array('success' => false, 'message' => 'Please fill in all required fields.');
    }
    
    // Prepare post data
    $post_data = array(
        'post_title' => sanitize_text_field($form_data['ticket_title']),
        'post_content' => sanitize_textarea_field($form_data['ticket_description']),
        'post_type' => 'support_ticket',
        'post_status' => 'publish'
    );
    
    if ($is_edit) {
        $post_data['ID'] = $ticket_id;
        $result = wp_update_post($post_data);
    } else {
        $post_data['post_author'] = $user_id;
        $result = wp_insert_post($post_data);
        $ticket_id = $result;
    }
    
    if (is_wp_error($result)) {
        return array('success' => false, 'message' => 'Error saving ticket.');
    }
    
    // Save meta fields
    update_post_meta($ticket_id, 'ticket_property_id', intval($form_data['ticket_property_id']));
    update_post_meta($ticket_id, 'ticket_type', sanitize_text_field($form_data['ticket_type']));
    update_post_meta($ticket_id, 'ticket_category', sanitize_text_field($form_data['ticket_category']));
    update_post_meta($ticket_id, 'ticket_priority', sanitize_text_field($form_data['ticket_priority']));
    update_post_meta($ticket_id, 'ticket_last_update', current_time('mysql'));
    
    // Set initial status if new ticket
    if (!$is_edit) {
        update_post_meta($ticket_id, 'ticket_status', 'open');
        update_post_meta($ticket_id, 'ticket_created_date', current_time('mysql'));
    }
    
    // Handle file uploads
    if (!empty($_FILES['ticket_attachments']['name'][0])) {
        villa_handle_ticket_attachments($ticket_id, $_FILES['ticket_attachments']);
    }
    
    // Add activity log entry
    villa_add_ticket_activity($ticket_id, $user_id, $is_edit ? 'updated' : 'created');
    
    return array('success' => true, 'ticket_id' => $ticket_id);
}

/**
 * Handle ticket file attachments
 */
function villa_handle_ticket_attachments($ticket_id, $files) {
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    
    $uploaded_files = array();
    
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
                
                $attachment_id = wp_insert_attachment($attachment, $upload['file'], $ticket_id);
                
                if (!is_wp_error($attachment_id)) {
                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
                    wp_update_attachment_metadata($attachment_id, $attachment_data);
                    $uploaded_files[] = $attachment_id;
                }
            }
        }
    }
    
    return $uploaded_files;
}

/**
 * Display ticket attachments
 */
function villa_display_ticket_attachments($ticket_id) {
    $attachments = get_attached_media('', $ticket_id);
    
    if (empty($attachments)) {
        echo '<p>No attachments.</p>';
        return;
    }
    
    echo '<div class="ticket-attachments">';
    foreach ($attachments as $attachment) {
        $file_url = wp_get_attachment_url($attachment->ID);
        $file_name = basename($file_url);
        
        echo '<div class="attachment-item">';
        if (wp_attachment_is_image($attachment->ID)) {
            echo wp_get_attachment_image($attachment->ID, 'thumbnail');
        } else {
            echo '<span class="file-icon">📄</span>';
        }
        echo '<a href="' . esc_url($file_url) . '" target="_blank">' . esc_html($file_name) . '</a>';
        echo '</div>';
    }
    echo '</div>';
}

/**
 * Add ticket activity log entry
 */
function villa_add_ticket_activity($ticket_id, $user_id, $action, $note = '') {
    $activity_log = get_post_meta($ticket_id, 'ticket_activity_log', true);
    if (!is_array($activity_log)) {
        $activity_log = array();
    }
    
    $activity_log[] = array(
        'user_id' => $user_id,
        'action' => $action,
        'note' => $note,
        'timestamp' => current_time('mysql')
    );
    
    update_post_meta($ticket_id, 'ticket_activity_log', $activity_log);
}

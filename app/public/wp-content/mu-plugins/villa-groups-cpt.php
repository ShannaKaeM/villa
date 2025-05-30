<?php
/**
 * Villa Groups Custom Post Type
 * 
 * Handles committees, staff groups, BOD, and other community groups
 * 
 * @package VillaCommunity
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Groups Custom Post Type
 */
function villa_register_groups_cpt() {
    $labels = array(
        'name'                  => _x('Groups', 'Post type general name', 'migv'),
        'singular_name'         => _x('Group', 'Post type singular name', 'migv'),
        'menu_name'             => _x('Groups', 'Admin Menu text', 'migv'),
        'name_admin_bar'        => _x('Group', 'Add New on Toolbar', 'migv'),
        'add_new'               => __('Add New', 'migv'),
        'add_new_item'          => __('Add New Group', 'migv'),
        'new_item'              => __('New Group', 'migv'),
        'edit_item'             => __('Edit Group', 'migv'),
        'view_item'             => __('View Group', 'migv'),
        'all_items'             => __('All Groups', 'migv'),
        'search_items'          => __('Search Groups', 'migv'),
        'parent_item_colon'     => __('Parent Groups:', 'migv'),
        'not_found'             => __('No groups found.', 'migv'),
        'not_found_in_trash'    => __('No groups found in Trash.', 'migv'),
        'featured_image'        => _x('Group Image', 'Overrides the "Featured Image" phrase', 'migv'),
        'set_featured_image'    => _x('Set group image', 'Overrides the "Set featured image" phrase', 'migv'),
        'remove_featured_image' => _x('Remove group image', 'Overrides the "Remove featured image" phrase', 'migv'),
        'use_featured_image'    => _x('Use as group image', 'Overrides the "Use as featured image" phrase', 'migv'),
        'archives'              => _x('Group archives', 'The post type archive label', 'migv'),
        'insert_into_item'      => _x('Insert into group', 'Overrides the "Insert into post" phrase', 'migv'),
        'uploaded_to_this_item' => _x('Uploaded to this group', 'Overrides the "Uploaded to this post" phrase', 'migv'),
        'filter_items_list'     => _x('Filter groups list', 'Screen reader text for the filter links', 'migv'),
        'items_list_navigation' => _x('Groups list navigation', 'Screen reader text for the pagination', 'migv'),
        'items_list'            => _x('Groups list', 'Screen reader text for the items list', 'migv'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'groups'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-groups',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest'       => true,
        'taxonomies'         => array('group_type'),
    );

    register_post_type('villa_group', $args);
}
add_action('init', 'villa_register_groups_cpt');

/**
 * Register Group Type Taxonomy
 */
function villa_register_group_type_taxonomy() {
    $labels = array(
        'name'                       => _x('Group Types', 'Taxonomy General Name', 'migv'),
        'singular_name'              => _x('Group Type', 'Taxonomy Singular Name', 'migv'),
        'menu_name'                  => __('Group Types', 'migv'),
        'all_items'                  => __('All Group Types', 'migv'),
        'parent_item'                => __('Parent Group Type', 'migv'),
        'parent_item_colon'          => __('Parent Group Type:', 'migv'),
        'new_item_name'              => __('New Group Type Name', 'migv'),
        'add_new_item'               => __('Add New Group Type', 'migv'),
        'edit_item'                  => __('Edit Group Type', 'migv'),
        'update_item'                => __('Update Group Type', 'migv'),
        'view_item'                  => __('View Group Type', 'migv'),
        'separate_items_with_commas' => __('Separate group types with commas', 'migv'),
        'add_or_remove_items'        => __('Add or remove group types', 'migv'),
        'choose_from_most_used'      => __('Choose from the most used', 'migv'),
        'popular_items'              => __('Popular Group Types', 'migv'),
        'search_items'               => __('Search Group Types', 'migv'),
        'not_found'                  => __('Not Found', 'migv'),
        'no_terms'                   => __('No group types', 'migv'),
        'items_list'                 => __('Group types list', 'migv'),
        'items_list_navigation'      => __('Group types list navigation', 'migv'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rewrite'                    => array('slug' => 'group-type'),
    );

    register_taxonomy('group_type', array('villa_group'), $args);
}
add_action('init', 'villa_register_group_type_taxonomy');

/**
 * Create default group types
 */
function villa_create_default_group_types() {
    if (!term_exists('committee', 'group_type')) {
        wp_insert_term('Committee', 'group_type', array(
            'slug' => 'committee',
            'description' => 'Community committees and working groups'
        ));
    }

    if (!term_exists('board-of-directors', 'group_type')) {
        wp_insert_term('Board of Directors', 'group_type', array(
            'slug' => 'board-of-directors',
            'description' => 'Board of Directors members'
        ));
    }

    if (!term_exists('staff', 'group_type')) {
        wp_insert_term('Staff', 'group_type', array(
            'slug' => 'staff',
            'description' => 'Community staff and employees'
        ));
    }

    if (!term_exists('department', 'group_type')) {
        wp_insert_term('Department', 'group_type', array(
            'slug' => 'department',
            'description' => 'Organizational departments'
        ));
    }

    if (!term_exists('volunteer', 'group_type')) {
        wp_insert_term('Volunteer Group', 'group_type', array(
            'slug' => 'volunteer',
            'description' => 'Volunteer groups and teams'
        ));
    }
}
add_action('init', 'villa_create_default_group_types');

/**
 * Add CMB2 fields for Groups
 */
function villa_register_group_fields() {
    if (!function_exists('new_cmb2_box')) {
        return;
    }

    // Group Details
    $group_details = new_cmb2_box(array(
        'id'           => 'group_details',
        'title'        => __('Group Details', 'migv'),
        'object_types' => array('villa_group'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
    ));

    $group_details->add_field(array(
        'name' => __('Group Status', 'migv'),
        'id'   => 'group_status',
        'type' => 'select',
        'options' => array(
            'active'    => __('Active', 'migv'),
            'inactive'  => __('Inactive', 'migv'),
            'forming'   => __('Forming', 'migv'),
            'disbanded' => __('Disbanded', 'migv'),
        ),
        'default' => 'active',
    ));

    $group_details->add_field(array(
        'name' => __('Meeting Schedule', 'migv'),
        'id'   => 'group_meeting_schedule',
        'type' => 'text',
        'desc' => __('e.g., "First Tuesday of each month at 7 PM"', 'migv'),
    ));

    $group_details->add_field(array(
        'name' => __('Meeting Location', 'migv'),
        'id'   => 'group_meeting_location',
        'type' => 'text',
        'desc' => __('Physical or virtual meeting location', 'migv'),
    ));

    $group_details->add_field(array(
        'name' => __('Contact Email', 'migv'),
        'id'   => 'group_contact_email',
        'type' => 'text_email',
    ));

    $group_details->add_field(array(
        'name' => __('Group Purpose', 'migv'),
        'id'   => 'group_purpose',
        'type' => 'textarea',
        'desc' => __('Brief description of the group\'s purpose and goals', 'migv'),
    ));

    $group_details->add_field(array(
        'name' => __('Membership Requirements', 'migv'),
        'id'   => 'group_membership_requirements',
        'type' => 'textarea',
        'desc' => __('Requirements or qualifications for joining this group', 'migv'),
    ));

    $group_details->add_field(array(
        'name' => __('Maximum Members', 'migv'),
        'id'   => 'group_max_members',
        'type' => 'text_small',
        'attributes' => array(
            'type' => 'number',
            'min'  => '1',
        ),
        'desc' => __('Leave blank for unlimited', 'migv'),
    ));

    // Group Members
    $group_members = new_cmb2_box(array(
        'id'           => 'group_members',
        'title'        => __('Group Members', 'migv'),
        'object_types' => array('villa_group'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
    ));

    $group_members->add_field(array(
        'name' => __('Group Leader/Chair', 'migv'),
        'id'   => 'group_leader',
        'type' => 'select',
        'options_cb' => 'villa_get_users_for_select',
        'desc' => __('Select the group leader or chairperson', 'migv'),
    ));

    $group_members->add_field(array(
        'name' => __('Members', 'migv'),
        'id'   => 'group_members_list',
        'type' => 'group',
        'repeatable' => true,
        'options' => array(
            'group_title'   => __('Member {#}', 'migv'),
            'add_button'    => __('Add Member', 'migv'),
            'remove_button' => __('Remove Member', 'migv'),
            'sortable'      => true,
        ),
    ));

    $group_members->add_group_field('group_members_list', array(
        'name' => __('User', 'migv'),
        'id'   => 'user_id',
        'type' => 'select',
        'options_cb' => 'villa_get_users_for_select',
    ));

    $group_members->add_group_field('group_members_list', array(
        'name' => __('Role in Group', 'migv'),
        'id'   => 'role',
        'type' => 'text',
        'desc' => __('e.g., Secretary, Treasurer, Member', 'migv'),
    ));

    $group_members->add_group_field('group_members_list', array(
        'name' => __('Join Date', 'migv'),
        'id'   => 'join_date',
        'type' => 'text_date',
    ));

    $group_members->add_group_field('group_members_list', array(
        'name' => __('Status', 'migv'),
        'id'   => 'status',
        'type' => 'select',
        'options' => array(
            'active'   => __('Active', 'migv'),
            'inactive' => __('Inactive', 'migv'),
            'pending'  => __('Pending', 'migv'),
        ),
        'default' => 'active',
    ));
}
add_action('cmb2_admin_init', 'villa_register_group_fields');

/**
 * Get users for select dropdown
 */
function villa_get_users_for_select() {
    $users = get_users(array(
        'orderby' => 'display_name',
        'order'   => 'ASC',
    ));

    $options = array('' => __('Select a user...', 'migv'));
    
    foreach ($users as $user) {
        $options[$user->ID] = $user->display_name . ' (' . $user->user_email . ')';
    }

    return $options;
}

/**
 * Add groups management to dashboard
 */
function villa_render_dashboard_groups($user) {
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    // Check if user has access to groups management
    if (!in_array('bod', $user_roles) && !in_array('staff', $user_roles) && !in_array('committee', $user_roles)) {
        echo '<div class="access-denied">You do not have access to groups management.</div>';
        return;
    }
    
    // Get user's groups
    $user_groups = villa_get_user_groups($user->ID);
    $all_groups = get_posts(array(
        'post_type' => 'villa_group',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    ?>
    <div class="groups-management-dashboard">
        <div class="dashboard-header">
            <h2>Groups & Committees</h2>
            <?php if (in_array('bod', $user_roles) || in_array('staff', $user_roles)): ?>
                <a href="<?php echo admin_url('post-new.php?post_type=villa_group'); ?>" class="villa-btn villa-btn-primary" target="_blank">Create New Group</a>
            <?php endif; ?>
        </div>
        
        <div class="groups-stats">
            <div class="stat-card">
                <h3><?php echo count($user_groups); ?></h3>
                <p>Your Groups</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count($all_groups); ?></h3>
                <p>Total Groups</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(get_terms('group_type', array('hide_empty' => false))); ?></h3>
                <p>Group Types</p>
            </div>
        </div>
        
        <!-- User's Groups -->
        <div class="user-groups-section">
            <h3>Your Groups</h3>
            
            <?php if (empty($user_groups)): ?>
                <div class="empty-state">
                    <p>You are not a member of any groups yet.</p>
                </div>
            <?php else: ?>
                <div class="groups-grid">
                    <?php foreach ($user_groups as $group): ?>
                        <?php
                        $group_types = get_the_terms($group->ID, 'group_type');
                        $group_status = get_post_meta($group->ID, 'group_status', true);
                        $meeting_schedule = get_post_meta($group->ID, 'group_meeting_schedule', true);
                        $meeting_location = get_post_meta($group->ID, 'group_meeting_location', true);
                        $user_role = villa_get_user_role_in_group($user->ID, $group->ID);
                        ?>
                        
                        <div class="group-card status-<?php echo esc_attr($group_status); ?>">
                            <div class="group-header">
                                <h4><?php echo esc_html($group->post_title); ?></h4>
                                <div class="group-type">
                                    <?php if ($group_types && !is_wp_error($group_types)): ?>
                                        <?php echo esc_html($group_types[0]->name); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="group-meta">
                                <div class="meta-item">
                                    <strong>Your Role:</strong> <?php echo esc_html($user_role ?: 'Member'); ?>
                                </div>
                                
                                <?php if ($meeting_schedule): ?>
                                    <div class="meta-item">
                                        <strong>Meetings:</strong> <?php echo esc_html($meeting_schedule); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($meeting_location): ?>
                                    <div class="meta-item">
                                        <strong>Location:</strong> <?php echo esc_html($meeting_location); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="group-description">
                                <?php echo wp_trim_words($group->post_content, 20); ?>
                            </div>
                            
                            <div class="group-actions">
                                <a href="<?php echo get_permalink($group->ID); ?>" class="villa-btn villa-btn-small villa-btn-outline" target="_blank">View Details</a>
                                <?php if (in_array('bod', $user_roles) || in_array('staff', $user_roles)): ?>
                                    <a href="<?php echo admin_url('post.php?action=edit&post=' . $group->ID); ?>" class="villa-btn villa-btn-small villa-btn-secondary" target="_blank">Edit</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- All Groups Directory -->
        <div class="all-groups-section">
            <h3>All Community Groups</h3>
            
            <div class="groups-filter">
                <select id="group-type-filter" onchange="filterGroups()">
                    <option value="">All Types</option>
                    <?php
                    $group_types = get_terms('group_type', array('hide_empty' => false));
                    foreach ($group_types as $type) {
                        echo '<option value="' . esc_attr($type->slug) . '">' . esc_html($type->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="all-groups-grid">
                <?php foreach ($all_groups as $group): ?>
                    <?php
                    $group_types = get_the_terms($group->ID, 'group_type');
                    $group_status = get_post_meta($group->ID, 'group_status', true);
                    $member_count = villa_get_group_member_count($group->ID);
                    $max_members = get_post_meta($group->ID, 'group_max_members', true);
                    $is_member = villa_is_user_in_group($user->ID, $group->ID);
                    ?>
                    
                    <div class="group-card directory-card" data-group-type="<?php echo $group_types ? esc_attr($group_types[0]->slug) : ''; ?>">
                        <div class="group-header">
                            <h4><?php echo esc_html($group->post_title); ?></h4>
                            <div class="group-type">
                                <?php if ($group_types && !is_wp_error($group_types)): ?>
                                    <?php echo esc_html($group_types[0]->name); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="group-stats">
                            <span class="member-count"><?php echo $member_count; ?> member<?php echo $member_count !== 1 ? 's' : ''; ?></span>
                            <?php if ($max_members): ?>
                                <span class="max-members">/ <?php echo $max_members; ?> max</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="group-description">
                            <?php echo wp_trim_words($group->post_content, 15); ?>
                        </div>
                        
                        <div class="group-actions">
                            <a href="<?php echo get_permalink($group->ID); ?>" class="villa-btn villa-btn-small villa-btn-outline" target="_blank">Learn More</a>
                            <?php if (!$is_member && $group_status === 'active'): ?>
                                <button class="villa-btn villa-btn-small villa-btn-primary" onclick="requestToJoinGroup(<?php echo $group->ID; ?>)">Request to Join</button>
                            <?php elseif ($is_member): ?>
                                <span class="member-badge">Member</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <script>
    function filterGroups() {
        const filter = document.getElementById('group-type-filter').value;
        const cards = document.querySelectorAll('.directory-card');
        
        cards.forEach(card => {
            if (!filter || card.dataset.groupType === filter) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    function requestToJoinGroup(groupId) {
        if (confirm('Would you like to request to join this group?')) {
            // AJAX call to request group membership
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'villa_request_group_membership',
                    group_id: groupId,
                    nonce: '<?php echo wp_create_nonce('villa_group_action'); ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Your request has been sent to the group administrators.');
                    location.reload();
                } else {
                    alert('Error sending request: ' + data.data);
                }
            });
        }
    }
    </script>
    <?php
}

/**
 * Helper functions for group management
 */
function villa_get_user_groups($user_id) {
    $groups = get_posts(array(
        'post_type' => 'villa_group',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'group_leader',
                'value' => $user_id,
                'compare' => '='
            ),
            array(
                'key' => 'group_members_list',
                'value' => serialize(strval($user_id)),
                'compare' => 'LIKE'
            )
        )
    ));
    
    return $groups;
}

function villa_get_user_role_in_group($user_id, $group_id) {
    $leader = get_post_meta($group_id, 'group_leader', true);
    if ($leader == $user_id) {
        return 'Leader';
    }
    
    $members = get_post_meta($group_id, 'group_members_list', true);
    if (is_array($members)) {
        foreach ($members as $member) {
            if (isset($member['user_id']) && $member['user_id'] == $user_id) {
                return $member['role'] ?? 'Member';
            }
        }
    }
    
    return null;
}

function villa_is_user_in_group($user_id, $group_id) {
    return villa_get_user_role_in_group($user_id, $group_id) !== null;
}

function villa_get_group_member_count($group_id) {
    $leader = get_post_meta($group_id, 'group_leader', true);
    $members = get_post_meta($group_id, 'group_members_list', true);
    
    $count = $leader ? 1 : 0;
    if (is_array($members)) {
        $count += count($members);
    }
    
    return $count;
}

/**
 * AJAX handler for group membership requests
 */
function villa_handle_group_membership_request() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'villa_group_action')) {
        wp_die('Security check failed');
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('You must be logged in to request group membership.');
        return;
    }
    
    $group_id = intval($_POST['group_id']);
    $user_id = get_current_user_id();
    
    // Validate group exists
    if (!get_post($group_id) || get_post_type($group_id) !== 'villa_group') {
        wp_send_json_error('Invalid group.');
        return;
    }
    
    // Check if user is already a member
    if (villa_is_user_in_group($user_id, $group_id)) {
        wp_send_json_error('You are already a member of this group.');
        return;
    }
    
    // Get group details
    $group = get_post($group_id);
    $group_leader_id = get_post_meta($group_id, 'group_leader', true);
    $contact_email = get_post_meta($group_id, 'group_contact_email', true);
    
    // Create a notification/request (you could store this as post meta or create a separate system)
    $request_data = array(
        'user_id' => $user_id,
        'group_id' => $group_id,
        'request_date' => current_time('mysql'),
        'status' => 'pending'
    );
    
    // Store the request as post meta (you might want to create a separate table for this)
    $existing_requests = get_post_meta($group_id, 'membership_requests', true) ?: array();
    $existing_requests[] = $request_data;
    update_post_meta($group_id, 'membership_requests', $existing_requests);
    
    // Send notification email to group leader
    if ($group_leader_id) {
        $leader = get_user_by('ID', $group_leader_id);
        $requester = get_user_by('ID', $user_id);
        
        if ($leader && $requester) {
            $subject = sprintf('New membership request for %s', $group->post_title);
            $message = sprintf(
                "Hello %s,\n\n%s (%s) has requested to join the group '%s'.\n\nYou can review and approve this request in the WordPress admin area.\n\nBest regards,\nVilla Community",
                $leader->display_name,
                $requester->display_name,
                $requester->user_email,
                $group->post_title
            );
            
            wp_mail($leader->user_email, $subject, $message);
        }
    }
    
    wp_send_json_success('Your membership request has been sent successfully.');
}
add_action('wp_ajax_villa_request_group_membership', 'villa_handle_group_membership_request');
add_action('wp_ajax_nopriv_villa_request_group_membership', 'villa_handle_group_membership_request');

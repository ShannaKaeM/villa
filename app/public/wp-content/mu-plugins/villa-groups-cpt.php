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
    if (!term_exists('volunteers', 'group_type')) {
        wp_insert_term('Volunteers', 'group_type', array(
            'slug' => 'volunteers',
            'description' => 'Volunteer committees including Executive, Technology, Legal, Built Environment, Budget, and Operations'
        ));
    }

    if (!term_exists('staff', 'group_type')) {
        wp_insert_term('Staff', 'group_type', array(
            'slug' => 'staff',
            'description' => 'Community staff and employees'
        ));
    }
}
add_action('init', 'villa_create_default_group_types');

/**
 * Create staff user roles
 */
function villa_create_staff_roles() {
    // Property Management role
    if (!get_role('villa_property_management')) {
        add_role('villa_property_management', 'Property Management', array(
            'read' => true,
            'edit_posts' => true,
            'edit_others_posts' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'manage_categories' => true,
            'edit_villa_groups' => true,
            'edit_others_villa_groups' => true,
        ));
    }

    // Maintenance role
    if (!get_role('villa_maintenance')) {
        add_role('villa_maintenance', 'Maintenance', array(
            'read' => true,
            'edit_posts' => true,
            'edit_published_posts' => true,
        ));
    }

    // Office Manager role
    if (!get_role('villa_office_manager')) {
        add_role('villa_office_manager', 'Office Manager', array(
            'read' => true,
            'edit_posts' => true,
            'edit_others_posts' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'manage_categories' => true,
        ));
    }

    // Concierge role
    if (!get_role('villa_concierge')) {
        add_role('villa_concierge', 'Concierge', array(
            'read' => true,
            'edit_posts' => true,
            'edit_published_posts' => true,
        ));
    }

    // Director of Villa Operations role
    if (!get_role('villa_director_operations')) {
        add_role('villa_director_operations', 'Director of Villa Operations', array(
            'read' => true,
            'edit_posts' => true,
            'edit_others_posts' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'manage_categories' => true,
            'edit_villa_groups' => true,
            'edit_others_villa_groups' => true,
            'edit_users' => true,
        ));
    }

    // Board hierarchy roles
    if (!get_role('villa_board_president')) {
        add_role('villa_board_president', 'Board President', array(
            'read' => true,
            'edit_posts' => true,
            'edit_others_posts' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'manage_categories' => true,
            'edit_villa_groups' => true,
            'edit_others_villa_groups' => true,
            'edit_users' => true,
            'manage_options' => true,
        ));
    }

    if (!get_role('villa_board_vice_president')) {
        add_role('villa_board_vice_president', 'Board Vice President', array(
            'read' => true,
            'edit_posts' => true,
            'edit_others_posts' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'manage_categories' => true,
            'edit_villa_groups' => true,
            'edit_others_villa_groups' => true,
            'edit_users' => true,
        ));
    }

    if (!get_role('villa_board_treasurer')) {
        add_role('villa_board_treasurer', 'Board Treasurer', array(
            'read' => true,
            'edit_posts' => true,
            'edit_others_posts' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'manage_categories' => true,
            'edit_villa_groups' => true,
            'edit_others_villa_groups' => true,
        ));
    }

    if (!get_role('villa_board_secretary')) {
        add_role('villa_board_secretary', 'Board Secretary', array(
            'read' => true,
            'edit_posts' => true,
            'edit_others_posts' => true,
            'edit_published_posts' => true,
            'publish_posts' => true,
            'manage_categories' => true,
            'edit_villa_groups' => true,
            'edit_others_villa_groups' => true,
        ));
    }
}
add_action('init', 'villa_create_staff_roles');

/**
 * Create default volunteer committees
 */
function villa_create_default_committees() {
    // Only run this once
    if (get_option('villa_default_committees_created')) {
        return;
    }

    // Get the volunteers term
    $volunteers_term = get_term_by('slug', 'volunteers', 'group_type');
    if (!$volunteers_term) {
        return; // Wait for group types to be created first
    }

    $committees = array(
        array(
            'title' => 'Executive Committee',
            'slug' => 'exec',
            'description' => 'Executive leadership and strategic oversight of Villa Capriani community operations and governance.',
            'mission' => 'Providing executive leadership and strategic direction for the Villa Capriani community.',
            'focus_areas' => array(
                'Strategic planning and community vision',
                'Policy development and governance oversight',
                'Inter-committee coordination and communication',
                'Community-wide decision making and leadership'
            )
        ),
        array(
            'title' => 'Technology & Marketing Committee',
            'slug' => 'tech',
            'description' => 'Advancing Villa Capriani through digital innovation and strategic marketing initiatives.',
            'mission' => 'Advancing Villa Capriani through digital innovation and strategic marketing.',
            'focus_areas' => array(
                'Website and digital platform management',
                'Community marketing and social media',
                'Technology infrastructure improvements',
                'Owner communication systems'
            )
        ),
        array(
            'title' => 'Legal & Governance Committee',
            'slug' => 'legal',
            'description' => 'Ensuring compliance, fairness, and legal protection for the Villa Capriani community.',
            'mission' => 'Ensuring compliance, fairness, and legal protection for our community.',
            'focus_areas' => array(
                'HOA rule development and enforcement',
                'Legal matter coordination',
                'Policy updates and compliance',
                'Dispute resolution processes'
            )
        ),
        array(
            'title' => 'Built Environment Committee',
            'slug' => 'built',
            'description' => 'Preserving and enhancing the beauty and functionality of our Spanish Colonial paradise.',
            'mission' => 'Preserving and enhancing the beauty of our Spanish Colonial paradise.',
            'focus_areas' => array(
                'Landscaping design and maintenance',
                'Architectural review and approval',
                'Community appearance standards',
                'Environmental sustainability'
            )
        ),
        array(
            'title' => 'Budget & Revenue Committee',
            'slug' => 'budget',
            'description' => 'Maximizing value and ensuring financial health for all Villa Capriani owners.',
            'mission' => 'Maximizing value and financial health for all owners.',
            'focus_areas' => array(
                'Annual budget development',
                'Revenue optimization strategies',
                'Financial reporting and transparency',
                'Cost management and efficiency'
            )
        ),
        array(
            'title' => 'Operations Review Committee',
            'slug' => 'ops',
            'description' => 'Ensuring excellence in all community operations and services at Villa Capriani.',
            'mission' => 'Ensuring excellence in all community operations and services.',
            'focus_areas' => array(
                'Process improvement and efficiency',
                'Staff performance and development',
                'Quality assurance and standards',
                'Owner satisfaction monitoring'
            )
        )
    );

    foreach ($committees as $committee) {
        // Check if committee already exists
        $existing = get_page_by_path($committee['slug'], OBJECT, 'villa_group');
        if ($existing) {
            continue;
        }

        // Create the committee post
        $post_id = wp_insert_post(array(
            'post_title' => $committee['title'],
            'post_name' => $committee['slug'],
            'post_content' => $committee['description'],
            'post_status' => 'publish',
            'post_type' => 'villa_group'
        ));

        if ($post_id && !is_wp_error($post_id)) {
            // Assign to volunteers group type
            wp_set_post_terms($post_id, array($volunteers_term->term_id), 'group_type');
            
            // Add committee details
            update_post_meta($post_id, 'group_abbreviation', strtoupper($committee['slug']));
            update_post_meta($post_id, 'group_mission', $committee['mission']);
            update_post_meta($post_id, 'group_focus_areas', $committee['focus_areas']);
            update_post_meta($post_id, 'group_type_display', 'Committee');
            update_post_meta($post_id, 'group_meeting_frequency', 'Monthly');
            update_post_meta($post_id, 'group_status', 'Active');
        }
    }

    // Mark as completed
    update_option('villa_default_committees_created', true);
}
add_action('init', 'villa_create_default_committees', 20); // Run after group types are created

/**
 * Clean up old group type terms and create Staff group
 */
function villa_cleanup_and_create_staff_group() {
    // Only run this once
    if (get_option('villa_staff_group_created')) {
        return;
    }

    // Remove old/duplicate group type terms
    $old_terms = array('committee', 'board-of-directors', 'department', 'volunteer');
    foreach ($old_terms as $term_slug) {
        $term = get_term_by('slug', $term_slug, 'group_type');
        if ($term) {
            wp_delete_term($term->term_id, 'group_type');
        }
    }

    // Get the staff term
    $staff_term = get_term_by('slug', 'staff', 'group_type');
    if (!$staff_term) {
        return; // Wait for group types to be created first
    }

    // Create Staff group to hold all staff roles
    $existing_staff_group = get_page_by_path('staff-group', OBJECT, 'villa_group');
    if (!$existing_staff_group) {
        $staff_group_id = wp_insert_post(array(
            'post_title' => 'Staff',
            'post_name' => 'staff-group',
            'post_content' => 'Villa Capriani staff members including Property Management, Maintenance, Office Manager, Concierge, and Director of Villa Operations.',
            'post_status' => 'publish',
            'post_type' => 'villa_group'
        ));

        if ($staff_group_id && !is_wp_error($staff_group_id)) {
            // Assign to staff group type
            wp_set_post_terms($staff_group_id, array($staff_term->term_id), 'group_type');
            
            // Add staff group details
            update_post_meta($staff_group_id, 'group_abbreviation', 'STAFF');
            update_post_meta($staff_group_id, 'group_mission', 'Providing excellent service and operations for Villa Capriani community.');
            update_post_meta($staff_group_id, 'group_focus_areas', array(
                'Property management and maintenance',
                'Owner services and support',
                'Community operations',
                'Administrative functions'
            ));
            update_post_meta($staff_group_id, 'group_type_display', 'Staff');
            update_post_meta($staff_group_id, 'group_status', 'Active');
        }
    }

    // Mark as completed
    update_option('villa_staff_group_created', true);
}
add_action('init', 'villa_cleanup_and_create_staff_group', 25); // Run after committees are created

/**
 * Assign admin user to test committees for fortune telling purposes
 */
function villa_assign_admin_test_committees() {
    // Only run this once
    if (get_option('villa_admin_test_committees_assigned')) {
        return;
    }

    // Get admin user (assuming user ID 1, but let's be safe)
    $admin_users = get_users(array('role' => 'administrator', 'number' => 1));
    if (empty($admin_users)) {
        return;
    }
    
    $admin_user = $admin_users[0];
    $admin_id = $admin_user->ID;

    // Get TECH and EXEC committees
    $tech_committee = get_page_by_path('tech', OBJECT, 'villa_group');
    $exec_committee = get_page_by_path('exec', OBJECT, 'villa_group');

    if ($tech_committee) {
        // Add admin to TECH committee as member
        $tech_members = get_post_meta($tech_committee->ID, 'group_members_list', true) ?: array();
        $tech_members[] = array(
            'user_id' => $admin_id,
            'role' => 'Tech Member',
            'joined_date' => current_time('mysql'),
            'status' => 'active'
        );
        update_post_meta($tech_committee->ID, 'group_members_list', $tech_members);
    }

    if ($exec_committee) {
        // Add admin to EXEC committee as member
        $exec_members = get_post_meta($exec_committee->ID, 'group_members_list', true) ?: array();
        $exec_members[] = array(
            'user_id' => $admin_id,
            'role' => 'Exec Member',
            'joined_date' => current_time('mysql'),
            'status' => 'active'
        );
        update_post_meta($exec_committee->ID, 'group_members_list', $exec_members);
    }

    // Mark as completed
    update_option('villa_admin_test_committees_assigned', true);
}
add_action('init', 'villa_assign_admin_test_committees', 25); // Run after committees are created

/**
 * Assign admin to committees for testing
 */
function villa_assign_admin_to_committees() {
    $admin_users = get_users(array('role' => 'administrator'));
    if (empty($admin_users)) {
        return;
    }
    
    $admin_user = $admin_users[0];
    
    // Get all volunteer committees
    $committees = get_posts(array(
        'post_type' => 'villa_group',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'group_type',
                'field' => 'slug',
                'terms' => 'volunteers'
            )
        )
    ));
    
    foreach ($committees as $committee) {
        $abbreviation = get_post_meta($committee->ID, 'group_abbreviation', true);
        
        // Assign admin to TECH and EXEC committees as member
        if (in_array($abbreviation, array('TECH', 'EXEC'))) {
            $existing_members = get_post_meta($committee->ID, 'group_members_list', true);
            if (!is_array($existing_members)) {
                $existing_members = array();
            }
            
            // Check if admin is already a member
            $already_member = false;
            foreach ($existing_members as $member) {
                if (isset($member['user_id']) && $member['user_id'] == $admin_user->ID) {
                    $already_member = true;
                    break;
                }
            }
            
            if (!$already_member) {
                $role = ($abbreviation === 'TECH') ? 'Tech Member' : 'Exec Member';
                $existing_members[] = array(
                    'user_id' => $admin_user->ID,
                    'role' => $role,
                    'date_joined' => current_time('Y-m-d')
                );
                
                update_post_meta($committee->ID, 'group_members_list', $existing_members);
            }
        }
    }
}
add_action('init', 'villa_assign_admin_to_committees', 25); // Run after committees are created

/**
 * Assign admin staff roles for testing
 */
function villa_assign_admin_staff_roles() {
    $admin_users = get_users(array('role' => 'administrator'));
    if (empty($admin_users)) {
        return;
    }
    
    $admin_user = $admin_users[0];
    
    // Give admin some staff roles for testing
    $admin_user->add_role('villa_property_management');
    $admin_user->add_role('villa_board_president');
}
add_action('init', 'villa_assign_admin_staff_roles', 26);

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
        'name' => __('Group Coordinator', 'migv'),
        'id'   => 'group_coordinator',
        'type' => 'select',
        'options_cb' => 'villa_get_users_for_select',
        'desc' => __('Select the group coordinator', 'migv'),
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
        'type' => 'select',
        'options' => array(
            'Member' => __('Member', 'migv'),
            'Coordinator' => __('Coordinator', 'migv'),
            'Tech Member' => __('Tech Member', 'migv'),
            'Legal Member' => __('Legal Member', 'migv'),
            'Built Member' => __('Built Member', 'migv'),
            'Budget Member' => __('Budget Member', 'migv'),
            'Ops Member' => __('Ops Member', 'migv'),
            'Exec Member' => __('Exec Member', 'migv'),
        ),
        'default' => 'Member',
        'desc' => __('Role within the group', 'migv'),
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
        
        <div class="villa-stats-grid groups-stats">
            <div class="villa-stat-card">
                <div class="villa-stat-number"><?php echo count($user_groups); ?></div>
                <div class="villa-stat-label">Your Groups</div>
            </div>
            <div class="villa-stat-card">
                <div class="villa-stat-number"><?php echo count($all_groups); ?></div>
                <div class="villa-stat-label">Total Groups</div>
            </div>
            <div class="villa-stat-card">
                <div class="villa-stat-number"><?php echo count(get_terms('group_type', array('hide_empty' => false))); ?></div>
                <div class="villa-stat-label">Group Types</div>
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
                        
                        <div class="villa-card group-card status-<?php echo esc_attr($group_status); ?>">
                            <?php if (has_post_thumbnail($group->ID)): ?>
                                <div class="villa-card__image">
                                    <?php echo get_the_post_thumbnail($group->ID, 'medium'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="villa-card__content">
                                <div class="villa-card__text">
                                <h3 class="villa-card__title group-title"><?php echo esc_html($group->post_title); ?></h3>
                                
                                <div class="villa-card__meta group-meta">
                                    <?php if ($group_types && !is_wp_error($group_types)): ?>
                                        <span class="villa-card__tag villa-card__tag--status group-type">
                                            <?php echo esc_html($group_types[0]->name); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($group_status): ?>
                                        <span class="villa-card__tag villa-card__tag--<?php echo esc_attr($group_status); ?> group-status">
                                            <?php echo esc_html(ucwords(str_replace('_', ' ', $group_status))); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($user_role): ?>
                                        <span class="villa-card__tag villa-card__tag--status user-role">
                                            <?php echo esc_html($user_role); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($group->post_content): ?>
                                    <p class="villa-card__description group-description">
                                        <?php echo wp_trim_words($group->post_content, 20); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if ($meeting_schedule || $meeting_location): ?>
                                    <div class="group-meeting-info">
                                        <?php if ($meeting_schedule): ?>
                                            <div class="meeting-item">
                                                <strong>Meetings:</strong> <?php echo esc_html($meeting_schedule); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($meeting_location): ?>
                                            <div class="meeting-item">
                                                <strong>Location:</strong> <?php echo esc_html($meeting_location); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                </div> <!-- villa-card__text -->
                                
                                <div class="villa-card__actions group-actions">
                                    <a href="<?php echo get_permalink($group->ID); ?>" class="villa-card__button" target="_blank">View Details</a>
                                    <?php if (in_array('bod', $user_roles) || in_array('staff', $user_roles)): ?>
                                        <a href="<?php echo admin_url('post.php?action=edit&post=' . $group->ID); ?>" class="villa-card__button villa-card__button--secondary" target="_blank">Edit Group</a>
                                    <?php endif; ?>
                                </div>
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
                    
                    <div class="villa-card group-card directory-card" data-group-type="<?php echo $group_types ? esc_attr($group_types[0]->slug) : ''; ?>">
                        <?php if (has_post_thumbnail($group->ID)): ?>
                            <div class="villa-card__image">
                                <?php echo get_the_post_thumbnail($group->ID, 'medium'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="villa-card__content">
                            <div class="villa-card__text">
                            <h3 class="villa-card__title group-title"><?php echo esc_html($group->post_title); ?></h3>
                            
                            <div class="villa-card__meta group-meta">
                                <?php if ($group_types && !is_wp_error($group_types)): ?>
                                    <span class="villa-card__tag villa-card__tag--status group-type">
                                        <?php echo esc_html($group_types[0]->name); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($group_status): ?>
                                    <span class="villa-card__tag villa-card__tag--<?php echo esc_attr($group_status); ?> group-status">
                                        <?php echo esc_html(ucwords(str_replace('_', ' ', $group_status))); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($group->post_content): ?>
                                <p class="villa-card__description group-description">
                                    <?php echo wp_trim_words($group->post_content, 15); ?>
                                </p>
                            <?php endif; ?>
                            
                            <div class="group-stats">
                                <span class="member-count"><?php echo $member_count; ?> member<?php echo $member_count !== 1 ? 's' : ''; ?></span>
                                <?php if ($max_members): ?>
                                    <span class="max-members">/ <?php echo $max_members; ?> max</span>
                                <?php endif; ?>
                            </div>
                            </div> <!-- villa-card__text -->
                            
                            <div class="villa-card__actions group-actions">
                                <a href="<?php echo get_permalink($group->ID); ?>" class="villa-card__button" target="_blank">Learn More</a>
                                <?php if (!$is_member && $group_status === 'active'): ?>
                                    <button class="villa-card__button villa-card__button--primary" onclick="requestToJoinGroup(<?php echo $group->ID; ?>)">Request to Join</button>
                                <?php elseif ($is_member): ?>
                                    <span class="member-badge">Member</span>
                                <?php endif; ?>
                            </div>
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
                'key' => 'group_coordinator',
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
    $coordinator = get_post_meta($group_id, 'group_coordinator', true);
    if ($coordinator == $user_id) {
        return 'Coordinator';
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
    $coordinator = get_post_meta($group_id, 'group_coordinator', true);
    $members = get_post_meta($group_id, 'group_members_list', true);
    
    $count = $coordinator ? 1 : 0;
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
    $group_coordinator_id = get_post_meta($group_id, 'group_coordinator', true);
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
    
    // Send notification email to group coordinator
    if ($group_coordinator_id) {
        $coordinator = get_user_by('ID', $group_coordinator_id);
        $requester = get_user_by('ID', $user_id);
        
        if ($coordinator && $requester) {
            $subject = sprintf('New membership request for %s', $group->post_title);
            $message = sprintf(
                "Hello %s,\n\n%s (%s) has requested to join the group '%s'.\n\nYou can review and approve this request in the WordPress admin area.\n\nBest regards,\nVilla Community",
                $coordinator->display_name,
                $requester->display_name,
                $requester->user_email,
                $group->post_title
            );
            
            wp_mail($coordinator->user_email, $subject, $message);
        }
    }
    
    wp_send_json_success('Your membership request has been sent successfully.');
}
add_action('wp_ajax_villa_request_group_membership', 'villa_handle_group_membership_request');
add_action('wp_ajax_nopriv_villa_request_group_membership', 'villa_handle_group_membership_request');

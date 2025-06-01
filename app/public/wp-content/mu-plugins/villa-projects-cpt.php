<?php
/**
 * Villa Projects CPT - Unified Project Management System
 * Handles: Roadmap, Committee Boards, Surveys, Announcements, Events, Tasks
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Villa Projects Custom Post Type
 */
function villa_register_projects_cpt() {
    $labels = array(
        'name'                  => _x('Villa Projects', 'Post Type General Name', 'villa-community'),
        'singular_name'         => _x('Villa Project', 'Post Type Singular Name', 'villa-community'),
        'menu_name'             => __('Villa Projects', 'villa-community'),
        'name_admin_bar'        => __('Villa Project', 'villa-community'),
        'archives'              => __('Project Archives', 'villa-community'),
        'attributes'            => __('Project Attributes', 'villa-community'),
        'parent_item_colon'     => __('Parent Project:', 'villa-community'),
        'all_items'             => __('All Projects', 'villa-community'),
        'add_new_item'          => __('Add New Project', 'villa-community'),
        'add_new'               => __('Add New', 'villa-community'),
        'new_item'              => __('New Project', 'villa-community'),
        'edit_item'             => __('Edit Project', 'villa-community'),
        'update_item'           => __('Update Project', 'villa-community'),
        'view_item'             => __('View Project', 'villa-community'),
        'view_items'            => __('View Projects', 'villa-community'),
        'search_items'          => __('Search Projects', 'villa-community'),
        'not_found'             => __('Not found', 'villa-community'),
        'not_found_in_trash'    => __('Not found in Trash', 'villa-community'),
        'featured_image'        => __('Featured Image', 'villa-community'),
        'set_featured_image'    => __('Set featured image', 'villa-community'),
        'remove_featured_image' => __('Remove featured image', 'villa-community'),
        'use_featured_image'    => __('Use as featured image', 'villa-community'),
        'insert_into_item'      => __('Insert into project', 'villa-community'),
        'uploaded_to_this_item' => __('Uploaded to this project', 'villa-community'),
        'items_list'            => __('Projects list', 'villa-community'),
        'items_list_navigation' => __('Projects list navigation', 'villa-community'),
        'filter_items_list'     => __('Filter projects list', 'villa-community'),
    );

    $args = array(
        'label'                 => __('Villa Project', 'villa-community'),
        'description'           => __('Villa Community Project Management System', 'villa-community'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'taxonomies'            => array('project_type', 'project_status', 'assigned_group', 'priority', 'visibility'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 25,
        'menu_icon'             => 'dashicons-clipboard',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rest_base'             => 'villa-projects',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );

    register_post_type('villa_projects', $args);
}
add_action('init', 'villa_register_projects_cpt', 0);

/**
 * Register Project Type Taxonomy
 */
function villa_register_project_type_taxonomy() {
    $labels = array(
        'name'                       => _x('Project Types', 'Taxonomy General Name', 'villa-community'),
        'singular_name'              => _x('Project Type', 'Taxonomy Singular Name', 'villa-community'),
        'menu_name'                  => __('Project Types', 'villa-community'),
        'all_items'                  => __('All Project Types', 'villa-community'),
        'parent_item'                => __('Parent Project Type', 'villa-community'),
        'parent_item_colon'          => __('Parent Project Type:', 'villa-community'),
        'new_item_name'              => __('New Project Type Name', 'villa-community'),
        'add_new_item'               => __('Add New Project Type', 'villa-community'),
        'edit_item'                  => __('Edit Project Type', 'villa-community'),
        'update_item'                => __('Update Project Type', 'villa-community'),
        'view_item'                  => __('View Project Type', 'villa-community'),
        'separate_items_with_commas' => __('Separate project types with commas', 'villa-community'),
        'add_or_remove_items'        => __('Add or remove project types', 'villa-community'),
        'choose_from_most_used'      => __('Choose from the most used', 'villa-community'),
        'popular_items'              => __('Popular Project Types', 'villa-community'),
        'search_items'               => __('Search Project Types', 'villa-community'),
        'not_found'                  => __('Not Found', 'villa-community'),
        'no_terms'                   => __('No project types', 'villa-community'),
        'items_list'                 => __('Project types list', 'villa-community'),
        'items_list_navigation'      => __('Project types list navigation', 'villa-community'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => false,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rest_base'                  => 'project-types',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('project_type', array('villa_projects'), $args);
}
add_action('init', 'villa_register_project_type_taxonomy', 0);

/**
 * Register Project Status Taxonomy
 */
function villa_register_project_status_taxonomy() {
    $labels = array(
        'name'                       => _x('Project Status', 'Taxonomy General Name', 'villa-community'),
        'singular_name'              => _x('Project Status', 'Taxonomy Singular Name', 'villa-community'),
        'menu_name'                  => __('Project Status', 'villa-community'),
        'all_items'                  => __('All Status', 'villa-community'),
        'parent_item'                => __('Parent Status', 'villa-community'),
        'parent_item_colon'          => __('Parent Status:', 'villa-community'),
        'new_item_name'              => __('New Status Name', 'villa-community'),
        'add_new_item'               => __('Add New Status', 'villa-community'),
        'edit_item'                  => __('Edit Status', 'villa-community'),
        'update_item'                => __('Update Status', 'villa-community'),
        'view_item'                  => __('View Status', 'villa-community'),
        'separate_items_with_commas' => __('Separate status with commas', 'villa-community'),
        'add_or_remove_items'        => __('Add or remove status', 'villa-community'),
        'choose_from_most_used'      => __('Choose from the most used', 'villa-community'),
        'popular_items'              => __('Popular Status', 'villa-community'),
        'search_items'               => __('Search Status', 'villa-community'),
        'not_found'                  => __('Not Found', 'villa-community'),
        'no_terms'                   => __('No status', 'villa-community'),
        'items_list'                 => __('Status list', 'villa-community'),
        'items_list_navigation'      => __('Status list navigation', 'villa-community'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => false,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rest_base'                  => 'project-status',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('project_status', array('villa_projects'), $args);
}
add_action('init', 'villa_register_project_status_taxonomy', 0);

/**
 * Register Assigned Group Taxonomy
 */
function villa_register_assigned_group_taxonomy() {
    $labels = array(
        'name'                       => _x('Assigned Groups', 'Taxonomy General Name', 'villa-community'),
        'singular_name'              => _x('Assigned Group', 'Taxonomy Singular Name', 'villa-community'),
        'menu_name'                  => __('Assigned Groups', 'villa-community'),
        'all_items'                  => __('All Groups', 'villa-community'),
        'parent_item'                => __('Parent Group', 'villa-community'),
        'parent_item_colon'          => __('Parent Group:', 'villa-community'),
        'new_item_name'              => __('New Group Name', 'villa-community'),
        'add_new_item'               => __('Add New Group', 'villa-community'),
        'edit_item'                  => __('Edit Group', 'villa-community'),
        'update_item'                => __('Update Group', 'villa-community'),
        'view_item'                  => __('View Group', 'villa-community'),
        'separate_items_with_commas' => __('Separate groups with commas', 'villa-community'),
        'add_or_remove_items'        => __('Add or remove groups', 'villa-community'),
        'choose_from_most_used'      => __('Choose from the most used', 'villa-community'),
        'popular_items'              => __('Popular Groups', 'villa-community'),
        'search_items'               => __('Search Groups', 'villa-community'),
        'not_found'                  => __('Not Found', 'villa-community'),
        'no_terms'                   => __('No groups', 'villa-community'),
        'items_list'                 => __('Groups list', 'villa-community'),
        'items_list_navigation'      => __('Groups list navigation', 'villa-community'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => false,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rest_base'                  => 'assigned-groups',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('assigned_group', array('villa_projects'), $args);
}
add_action('init', 'villa_register_assigned_group_taxonomy', 0);

/**
 * Register Priority Taxonomy
 */
function villa_register_priority_taxonomy() {
    $labels = array(
        'name'                       => _x('Priority Levels', 'Taxonomy General Name', 'villa-community'),
        'singular_name'              => _x('Priority Level', 'Taxonomy Singular Name', 'villa-community'),
        'menu_name'                  => __('Priority Levels', 'villa-community'),
        'all_items'                  => __('All Priority Levels', 'villa-community'),
        'parent_item'                => __('Parent Priority', 'villa-community'),
        'parent_item_colon'          => __('Parent Priority:', 'villa-community'),
        'new_item_name'              => __('New Priority Name', 'villa-community'),
        'add_new_item'               => __('Add New Priority', 'villa-community'),
        'edit_item'                  => __('Edit Priority', 'villa-community'),
        'update_item'                => __('Update Priority', 'villa-community'),
        'view_item'                  => __('View Priority', 'villa-community'),
        'separate_items_with_commas' => __('Separate priorities with commas', 'villa-community'),
        'add_or_remove_items'        => __('Add or remove priorities', 'villa-community'),
        'choose_from_most_used'      => __('Choose from the most used', 'villa-community'),
        'popular_items'              => __('Popular Priorities', 'villa-community'),
        'search_items'               => __('Search Priorities', 'villa-community'),
        'not_found'                  => __('Not Found', 'villa-community'),
        'no_terms'                   => __('No priorities', 'villa-community'),
        'items_list'                 => __('Priorities list', 'villa-community'),
        'items_list_navigation'      => __('Priorities list navigation', 'villa-community'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => false,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rest_base'                  => 'priority-levels',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('priority', array('villa_projects'), $args);
}
add_action('init', 'villa_register_priority_taxonomy', 0);

/**
 * Register Visibility Taxonomy
 */
function villa_register_visibility_taxonomy() {
    $labels = array(
        'name'                       => _x('Visibility Levels', 'Taxonomy General Name', 'villa-community'),
        'singular_name'              => _x('Visibility Level', 'Taxonomy Singular Name', 'villa-community'),
        'menu_name'                  => __('Visibility Levels', 'villa-community'),
        'all_items'                  => __('All Visibility Levels', 'villa-community'),
        'parent_item'                => __('Parent Visibility', 'villa-community'),
        'parent_item_colon'          => __('Parent Visibility:', 'villa-community'),
        'new_item_name'              => __('New Visibility Name', 'villa-community'),
        'add_new_item'               => __('Add New Visibility', 'villa-community'),
        'edit_item'                  => __('Edit Visibility', 'villa-community'),
        'update_item'                => __('Update Visibility', 'villa-community'),
        'view_item'                  => __('View Visibility', 'villa-community'),
        'separate_items_with_commas' => __('Separate visibility with commas', 'villa-community'),
        'add_or_remove_items'        => __('Add or remove visibility', 'villa-community'),
        'choose_from_most_used'      => __('Choose from the most used', 'villa-community'),
        'popular_items'              => __('Popular Visibility', 'villa-community'),
        'search_items'               => __('Search Visibility', 'villa-community'),
        'not_found'                  => __('Not Found', 'villa-community'),
        'no_terms'                   => __('No visibility', 'villa-community'),
        'items_list'                 => __('Visibility list', 'villa-community'),
        'items_list_navigation'      => __('Visibility list navigation', 'villa-community'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => false,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => false,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rest_base'                  => 'visibility-levels',
        'rest_controller_class'      => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('visibility', array('villa_projects'), $args);
}
add_action('init', 'villa_register_visibility_taxonomy', 0);

/**
 * Create default taxonomy terms on activation
 */
function villa_create_default_project_terms() {
    // Project Types
    $project_types = array(
        'roadmap' => 'Roadmap Item',
        'committee_board' => 'Committee Board',
        'survey' => 'Survey/Voting',
        'announcement' => 'Announcement',
        'event' => 'Event',
        'task' => 'Task'
    );

    foreach ($project_types as $slug => $name) {
        if (!term_exists($slug, 'project_type')) {
            wp_insert_term($name, 'project_type', array('slug' => $slug));
        }
    }

    // Project Status
    $project_status = array(
        'concept' => 'Concept',
        'planning' => 'Planning',
        'active' => 'Active',
        'review' => 'Under Review',
        'voting' => 'Community Voting',
        'completed' => 'Completed',
        'archived' => 'Archived'
    );

    foreach ($project_status as $slug => $name) {
        if (!term_exists($slug, 'project_status')) {
            wp_insert_term($name, 'project_status', array('slug' => $slug));
        }
    }

    // Assigned Groups
    $assigned_groups = array(
        'tech' => 'Technology & Marketing',
        'legal' => 'Legal & Governance',
        'built' => 'Built Environment',
        'budget' => 'Budget & Revenue',
        'ops' => 'Operations Review',
        'exec' => 'Executive Committee',
        'community' => 'Community Wide',
        'staff' => 'Staff'
    );

    foreach ($assigned_groups as $slug => $name) {
        if (!term_exists($slug, 'assigned_group')) {
            wp_insert_term($name, 'assigned_group', array('slug' => $slug));
        }
    }

    // Priority Levels
    $priorities = array(
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'urgent' => 'Urgent',
        'emergency' => 'Emergency'
    );

    foreach ($priorities as $slug => $name) {
        if (!term_exists($slug, 'priority')) {
            wp_insert_term($name, 'priority', array('slug' => $slug));
        }
    }

    // Visibility Levels
    $visibility_levels = array(
        'public' => 'Public',
        'members' => 'Members Only',
        'committee' => 'Committee Only',
        'staff' => 'Staff Only',
        'board' => 'Board Only',
        'private' => 'Private'
    );

    foreach ($visibility_levels as $slug => $name) {
        if (!term_exists($slug, 'visibility')) {
            wp_insert_term($name, 'visibility', array('slug' => $slug));
        }
    }
}

// Create terms on theme activation or plugin activation
add_action('after_switch_theme', 'villa_create_default_project_terms');
add_action('wp_loaded', 'villa_create_default_project_terms');

/**
 * Add custom columns to projects admin list
 */
function villa_projects_custom_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['project_type'] = __('Type', 'villa-community');
    $new_columns['project_status'] = __('Status', 'villa-community');
    $new_columns['assigned_group'] = __('Assigned To', 'villa-community');
    $new_columns['priority'] = __('Priority', 'villa-community');
    $new_columns['visibility'] = __('Visibility', 'villa-community');
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_villa_projects_posts_columns', 'villa_projects_custom_columns');

/**
 * Display custom column content
 */
function villa_projects_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'project_type':
            $terms = get_the_terms($post_id, 'project_type');
            if ($terms && !is_wp_error($terms)) {
                $term_names = wp_list_pluck($terms, 'name');
                echo esc_html(implode(', ', $term_names));
            } else {
                echo '—';
            }
            break;
            
        case 'project_status':
            $terms = get_the_terms($post_id, 'project_status');
            if ($terms && !is_wp_error($terms)) {
                $term_names = wp_list_pluck($terms, 'name');
                echo esc_html(implode(', ', $term_names));
            } else {
                echo '—';
            }
            break;
            
        case 'assigned_group':
            $terms = get_the_terms($post_id, 'assigned_group');
            if ($terms && !is_wp_error($terms)) {
                $term_names = wp_list_pluck($terms, 'name');
                echo esc_html(implode(', ', $term_names));
            } else {
                echo '—';
            }
            break;
            
        case 'priority':
            $terms = get_the_terms($post_id, 'priority');
            if ($terms && !is_wp_error($terms)) {
                $term_names = wp_list_pluck($terms, 'name');
                echo esc_html(implode(', ', $term_names));
            } else {
                echo '—';
            }
            break;
            
        case 'visibility':
            $terms = get_the_terms($post_id, 'visibility');
            if ($terms && !is_wp_error($terms)) {
                $term_names = wp_list_pluck($terms, 'name');
                echo esc_html(implode(', ', $term_names));
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_villa_projects_posts_custom_column', 'villa_projects_custom_column_content', 10, 2);

/**
 * Make custom columns sortable
 */
function villa_projects_sortable_columns($columns) {
    $columns['project_type'] = 'project_type';
    $columns['project_status'] = 'project_status';
    $columns['assigned_group'] = 'assigned_group';
    $columns['priority'] = 'priority';
    $columns['visibility'] = 'visibility';
    
    return $columns;
}
add_filter('manage_edit-villa_projects_sortable_columns', 'villa_projects_sortable_columns');

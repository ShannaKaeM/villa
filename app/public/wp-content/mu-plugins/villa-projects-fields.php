<?php
/**
 * Villa Projects CMB2 Fields
 * Custom fields for the unified project management system
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize CMB2 fields for Villa Projects
 */
function villa_projects_cmb2_fields() {
    
    // Project Details Meta Box
    $project_details = new_cmb2_box(array(
        'id'            => 'villa_project_details',
        'title'         => __('Project Details', 'villa-community'),
        'object_types'  => array('villa_projects'),
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
        'classes'       => 'villa-ai-ready-block',
        'data'          => array(
            'block-type'    => 'villa-project-details',
            'ai-editable'   => 'true',
            'live-preview'  => 'true'
        ),
    ));

    // Project Goals
    $project_details->add_field(array(
        'name'       => __('Project Goals', 'villa-community'),
        'desc'       => __('What are the main objectives of this project?', 'villa-community'),
        'id'         => 'project_goals',
        'type'       => 'textarea',
        'attributes' => array(
            'rows' => 4,
            'data-ai-field' => 'project_goals',
            'data-live-target' => '.project-goals-display',
            'data-json-key' => 'goals'
        ),
    ));

    // Project Description (Enhanced)
    $project_details->add_field(array(
        'name'       => __('Detailed Description', 'villa-community'),
        'desc'       => __('Comprehensive project description with background and context', 'villa-community'),
        'id'         => 'project_description_detailed',
        'type'       => 'wysiwyg',
        'options'    => array(
            'wpautop' => true,
            'media_buttons' => true,
            'textarea_rows' => 8,
        ),
        'attributes' => array(
            'data-ai-field' => 'project_description',
            'data-live-target' => '.project-description-display',
            'data-json-key' => 'description'
        ),
    ));

    // Start Date
    $project_details->add_field(array(
        'name'       => __('Start Date', 'villa-community'),
        'desc'       => __('When should this project begin?', 'villa-community'),
        'id'         => 'project_start_date',
        'type'       => 'text_date',
        'date_format' => 'Y-m-d',
        'attributes' => array(
            'data-ai-field' => 'start_date',
            'data-live-target' => '.project-start-date-display',
            'data-json-key' => 'startDate'
        ),
    ));

    // Target End Date
    $project_details->add_field(array(
        'name'       => __('Target End Date', 'villa-community'),
        'desc'       => __('When should this project be completed?', 'villa-community'),
        'id'         => 'project_end_date',
        'type'       => 'text_date',
        'date_format' => 'Y-m-d',
        'attributes' => array(
            'data-ai-field' => 'end_date',
            'data-live-target' => '.project-end-date-display',
            'data-json-key' => 'endDate'
        ),
    ));

    // Project Lead/Manager
    $project_details->add_field(array(
        'name'       => __('Project Lead', 'villa-community'),
        'desc'       => __('Who is responsible for managing this project?', 'villa-community'),
        'id'         => 'project_lead',
        'type'       => 'select',
        'options_cb' => 'villa_get_villa_users_for_select',
        'attributes' => array(
            'data-ai-field' => 'project_lead',
            'data-live-target' => '.project-lead-display',
            'data-json-key' => 'projectLead'
        ),
    ));

    // Additional Team Members
    $project_details->add_field(array(
        'name'       => __('Team Members', 'villa-community'),
        'desc'       => __('Select additional team members for this project', 'villa-community'),
        'id'         => 'project_team_members',
        'type'       => 'multicheck',
        'options_cb' => 'villa_get_villa_users_for_multicheck',
        'attributes' => array(
            'data-ai-field' => 'team_members',
            'data-live-target' => '.project-team-display',
            'data-json-key' => 'teamMembers'
        ),
    ));

    // Budget Information Meta Box
    $budget_box = new_cmb2_box(array(
        'id'            => 'villa_project_budget',
        'title'         => __('Budget Information', 'villa-community'),
        'object_types'  => array('villa_projects'),
        'context'       => 'normal',
        'priority'      => 'default',
        'show_names'    => true,
        'classes'       => 'villa-ai-ready-block',
        'data'          => array(
            'block-type'    => 'villa-project-budget',
            'ai-editable'   => 'true',
            'live-preview'  => 'true'
        ),
    ));

    // Estimated Budget
    $budget_box->add_field(array(
        'name'       => __('Estimated Budget', 'villa-community'),
        'desc'       => __('Estimated total cost for this project', 'villa-community'),
        'id'         => 'project_estimated_budget',
        'type'       => 'text_money',
        'before_field' => '$',
        'attributes' => array(
            'data-ai-field' => 'estimated_budget',
            'data-live-target' => '.project-budget-display',
            'data-json-key' => 'estimatedBudget'
        ),
    ));

    // Budget Category
    $budget_box->add_field(array(
        'name'       => __('Budget Category', 'villa-community'),
        'desc'       => __('Which budget category does this fall under?', 'villa-community'),
        'id'         => 'project_budget_category',
        'type'       => 'select',
        'options'    => array(
            'maintenance' => __('Maintenance & Repairs', 'villa-community'),
            'improvement' => __('Property Improvements', 'villa-community'),
            'landscaping' => __('Landscaping', 'villa-community'),
            'amenities' => __('Amenities & Recreation', 'villa-community'),
            'security' => __('Security & Safety', 'villa-community'),
            'administrative' => __('Administrative', 'villa-community'),
            'emergency' => __('Emergency Fund', 'villa-community'),
            'other' => __('Other', 'villa-community'),
        ),
        'attributes' => array(
            'data-ai-field' => 'budget_category',
            'data-live-target' => '.project-budget-category-display',
            'data-json-key' => 'budgetCategory'
        ),
    ));

    // Budget Approval Required
    $budget_box->add_field(array(
        'name'       => __('Budget Approval Required', 'villa-community'),
        'desc'       => __('Does this project require budget committee or board approval?', 'villa-community'),
        'id'         => 'project_budget_approval',
        'type'       => 'select',
        'options'    => array(
            'none' => __('No Approval Required', 'villa-community'),
            'committee' => __('Committee Approval', 'villa-community'),
            'board' => __('Board Approval', 'villa-community'),
            'community' => __('Community Vote Required', 'villa-community'),
        ),
        'attributes' => array(
            'data-ai-field' => 'budget_approval',
            'data-live-target' => '.project-approval-display',
            'data-json-key' => 'budgetApproval'
        ),
    ));

    // Communication & Voting Meta Box
    $communication_box = new_cmb2_box(array(
        'id'            => 'villa_project_communication',
        'title'         => __('Communication & Voting', 'villa-community'),
        'object_types'  => array('villa_projects'),
        'context'       => 'side',
        'priority'      => 'default',
        'show_names'    => true,
        'classes'       => 'villa-ai-ready-block',
        'data'          => array(
            'block-type'    => 'villa-project-communication',
            'ai-editable'   => 'true',
            'live-preview'  => 'true'
        ),
    ));

    // Requires Community Input
    $communication_box->add_field(array(
        'name'       => __('Community Input Required', 'villa-community'),
        'desc'       => __('Does this project need community feedback or voting?', 'villa-community'),
        'id'         => 'project_community_input',
        'type'       => 'checkbox',
        'attributes' => array(
            'data-ai-field' => 'community_input',
            'data-live-target' => '.project-input-display',
            'data-json-key' => 'communityInput'
        ),
    ));

    // Notification Settings
    $communication_box->add_field(array(
        'name'       => __('Notification Level', 'villa-community'),
        'desc'       => __('How should the community be notified about this project?', 'villa-community'),
        'id'         => 'project_notification_level',
        'type'       => 'select',
        'options'    => array(
            'none' => __('No Notifications', 'villa-community'),
            'committee' => __('Committee Only', 'villa-community'),
            'members' => __('All Members', 'villa-community'),
            'announcement' => __('Public Announcement', 'villa-community'),
            'emergency' => __('Emergency Alert', 'villa-community'),
        ),
        'attributes' => array(
            'data-ai-field' => 'notification_level',
            'data-live-target' => '.project-notification-display',
            'data-json-key' => 'notificationLevel'
        ),
    ));

    // Can Convert to Event
    $communication_box->add_field(array(
        'name'       => __('Can Convert to Event', 'villa-community'),
        'desc'       => __('Can this project be converted to a calendar event?', 'villa-community'),
        'id'         => 'project_can_convert_event',
        'type'       => 'checkbox',
        'attributes' => array(
            'data-ai-field' => 'convert_to_event',
            'data-live-target' => '.project-event-display',
            'data-json-key' => 'canConvertToEvent'
        ),
    ));

    // Progress Tracking Meta Box
    $progress_box = new_cmb2_box(array(
        'id'            => 'villa_project_progress',
        'title'         => __('Progress Tracking', 'villa-community'),
        'object_types'  => array('villa_projects'),
        'context'       => 'side',
        'priority'      => 'default',
        'show_names'    => true,
        'classes'       => 'villa-ai-ready-block',
        'data'          => array(
            'block-type'    => 'villa-project-progress',
            'ai-editable'   => 'true',
            'live-preview'  => 'true'
        ),
    ));

    // Progress Percentage
    $progress_box->add_field(array(
        'name'       => __('Progress Percentage', 'villa-community'),
        'desc'       => __('What percentage of this project is complete?', 'villa-community'),
        'id'         => 'project_progress_percentage',
        'type'       => 'text',
        'attributes' => array(
            'type' => 'number',
            'min' => '0',
            'max' => '100',
            'step' => '5',
            'data-ai-field' => 'progress_percentage',
            'data-live-target' => '.project-progress-display',
            'data-json-key' => 'progressPercentage'
        ),
    ));

    // Next Milestone
    $progress_box->add_field(array(
        'name'       => __('Next Milestone', 'villa-community'),
        'desc'       => __('What is the next major milestone for this project?', 'villa-community'),
        'id'         => 'project_next_milestone',
        'type'       => 'textarea_small',
        'attributes' => array(
            'rows' => 3,
            'data-ai-field' => 'next_milestone',
            'data-live-target' => '.project-milestone-display',
            'data-json-key' => 'nextMilestone'
        ),
    ));

    // Last Update Date
    $progress_box->add_field(array(
        'name'       => __('Last Update', 'villa-community'),
        'desc'       => __('When was this project last updated?', 'villa-community'),
        'id'         => 'project_last_update',
        'type'       => 'text_date_timestamp',
        'date_format' => 'Y-m-d',
        'attributes' => array(
            'data-ai-field' => 'last_update',
            'data-live-target' => '.project-update-display',
            'data-json-key' => 'lastUpdate'
        ),
    ));

    // Attachments Meta Box
    $attachments_box = new_cmb2_box(array(
        'id'            => 'villa_project_attachments',
        'title'         => __('Project Attachments', 'villa-community'),
        'object_types'  => array('villa_projects'),
        'context'       => 'normal',
        'priority'      => 'low',
        'show_names'    => true,
        'classes'       => 'villa-ai-ready-block',
        'data'          => array(
            'block-type'    => 'villa-project-attachments',
            'ai-editable'   => 'false',
            'live-preview'  => 'true'
        ),
    ));

    // Project Documents
    $attachments_box->add_field(array(
        'name'       => __('Project Documents', 'villa-community'),
        'desc'       => __('Upload relevant documents, plans, or files', 'villa-community'),
        'id'         => 'project_documents',
        'type'       => 'file_list',
        'preview_size' => array(100, 100),
        'query_args' => array('type' => 'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        'attributes' => array(
            'data-ai-field' => 'project_documents',
            'data-live-target' => '.project-documents-display',
            'data-json-key' => 'projectDocuments'
        ),
    ));

    // Project Images
    $attachments_box->add_field(array(
        'name'       => __('Project Images', 'villa-community'),
        'desc'       => __('Upload relevant images or photos', 'villa-community'),
        'id'         => 'project_images',
        'type'       => 'file_list',
        'preview_size' => array(100, 100),
        'query_args' => array('type' => 'image'),
        'attributes' => array(
            'data-ai-field' => 'project_images',
            'data-live-target' => '.project-images-display',
            'data-json-key' => 'projectImages'
        ),
    ));
}
add_action('cmb2_admin_init', 'villa_projects_cmb2_fields');

/**
 * Get Villa users for select dropdown
 */
function villa_get_villa_users_for_select() {
    $users = get_users(array(
        'meta_key' => 'villa_role',
        'meta_compare' => 'EXISTS',
        'orderby' => 'display_name',
        'order' => 'ASC'
    ));
    
    $options = array('' => __('Select a user...', 'villa-community'));
    
    foreach ($users as $user) {
        $villa_role = get_user_meta($user->ID, 'villa_role', true);
        $display_text = $user->display_name;
        if ($villa_role) {
            $display_text .= ' (' . ucfirst(str_replace('_', ' ', $villa_role)) . ')';
        }
        $options[$user->ID] = $display_text;
    }
    
    return $options;
}

/**
 * Get Villa users for multicheck
 */
function villa_get_villa_users_for_multicheck() {
    $users = get_users(array(
        'meta_key' => 'villa_role',
        'meta_compare' => 'EXISTS',
        'orderby' => 'display_name',
        'order' => 'ASC'
    ));
    
    $options = array();
    
    foreach ($users as $user) {
        $villa_role = get_user_meta($user->ID, 'villa_role', true);
        $display_text = $user->display_name;
        if ($villa_role) {
            $display_text .= ' (' . ucfirst(str_replace('_', ' ', $villa_role)) . ')';
        }
        $options[$user->ID] = $display_text;
    }
    
    return $options;
}

/**
 * Auto-save last update timestamp when project is saved
 */
function villa_auto_update_project_timestamp($post_id) {
    // Only for villa_projects post type
    if (get_post_type($post_id) !== 'villa_projects') {
        return;
    }
    
    // Don't update on autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Update the last update timestamp
    update_post_meta($post_id, 'project_last_update', current_time('timestamp'));
}
add_action('save_post', 'villa_auto_update_project_timestamp');

/**
 * Set default values for new projects
 */
function villa_set_default_project_values($post_id) {
    // Only for villa_projects post type
    if (get_post_type($post_id) !== 'villa_projects') {
        return;
    }
    
    // Only for new posts
    if (get_post_status($post_id) !== 'auto-draft') {
        return;
    }
    
    // Set default progress to 0%
    if (!get_post_meta($post_id, 'project_progress_percentage', true)) {
        update_post_meta($post_id, 'project_progress_percentage', '0');
    }
    
    // Set default notification level
    if (!get_post_meta($post_id, 'project_notification_level', true)) {
        update_post_meta($post_id, 'project_notification_level', 'committee');
    }
    
    // Set default budget approval
    if (!get_post_meta($post_id, 'project_budget_approval', true)) {
        update_post_meta($post_id, 'project_budget_approval', 'none');
    }
}
add_action('save_post', 'villa_set_default_project_values');

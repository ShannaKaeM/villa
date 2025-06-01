<?php
/**
 * Villa Projects Sample Data
 * Creates sample projects for testing the system
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create sample projects for testing
 */
function villa_create_sample_projects() {
    // Check if we already have sample projects
    $existing_projects = get_posts(array(
        'post_type' => 'villa_projects',
        'meta_key' => 'is_sample_data',
        'meta_value' => '1',
        'posts_per_page' => 1
    ));
    
    if (!empty($existing_projects)) {
        return; // Sample data already exists
    }
    
    $sample_projects = array(
        array(
            'title' => 'Pool Area Renovation Project',
            'type' => 'roadmap',
            'status' => 'planning',
            'group' => 'BUILT',
            'priority' => 'high',
            'visibility' => 'public',
            'goals' => 'Renovate the community pool area to include new decking, updated lighting, and improved seating areas for residents.',
            'description' => 'This major renovation project will transform our pool area into a modern, comfortable space for all residents to enjoy. The project includes new composite decking, LED lighting, and comfortable outdoor furniture.',
            'start_date' => date('Y-m-d', strtotime('+2 months')),
            'end_date' => date('Y-m-d', strtotime('+8 months')),
            'budget' => 75000
        ),
        array(
            'title' => 'Community Garden Initiative',
            'type' => 'committee_board',
            'status' => 'active',
            'group' => 'BUILT',
            'priority' => 'medium',
            'visibility' => 'members',
            'goals' => 'Establish a community garden where residents can grow vegetables and herbs together.',
            'description' => 'Create designated garden plots for residents who want to grow their own vegetables and herbs. This initiative will include raised beds, a tool shed, and a composting area.',
            'start_date' => date('Y-m-d', strtotime('-1 month')),
            'end_date' => date('Y-m-d', strtotime('+4 months')),
            'budget' => 5000
        ),
        array(
            'title' => 'Resident Satisfaction Survey 2024',
            'type' => 'survey',
            'status' => 'voting',
            'group' => 'COMMUNITY',
            'priority' => 'medium',
            'visibility' => 'public',
            'goals' => 'Gather feedback from residents about community services, amenities, and areas for improvement.',
            'description' => 'Annual survey to understand resident satisfaction levels and identify priority areas for community improvements.',
            'start_date' => date('Y-m-d', strtotime('-1 week')),
            'end_date' => date('Y-m-d', strtotime('+3 weeks')),
            'budget' => 500
        ),
        array(
            'title' => 'Summer Community BBQ Event',
            'type' => 'event',
            'status' => 'planning',
            'group' => 'COMMUNITY',
            'priority' => 'low',
            'visibility' => 'public',
            'goals' => 'Host a community BBQ event to bring residents together and strengthen neighborhood bonds.',
            'description' => 'Annual summer BBQ featuring grilled food, games for kids, and live music. This event helps build community spirit and allows neighbors to connect.',
            'start_date' => date('Y-m-d', strtotime('+2 months')),
            'end_date' => date('Y-m-d', strtotime('+2 months')),
            'budget' => 2000
        ),
        array(
            'title' => 'Security Camera System Upgrade',
            'type' => 'roadmap',
            'status' => 'review',
            'group' => 'TECH',
            'priority' => 'high',
            'visibility' => 'committee',
            'goals' => 'Upgrade the community security camera system with modern HD cameras and improved monitoring capabilities.',
            'description' => 'Replace aging security cameras with new HD models, improve coverage areas, and implement cloud-based monitoring system.',
            'start_date' => date('Y-m-d', strtotime('+1 month')),
            'end_date' => date('Y-m-d', strtotime('+6 months')),
            'budget' => 25000
        ),
        array(
            'title' => 'Update Community Bylaws',
            'type' => 'task',
            'status' => 'active',
            'group' => 'LEGAL',
            'priority' => 'medium',
            'visibility' => 'committee',
            'goals' => 'Review and update community bylaws to reflect current regulations and resident needs.',
            'description' => 'Comprehensive review of existing bylaws with updates for modern community management practices.',
            'start_date' => date('Y-m-d', strtotime('-2 weeks')),
            'end_date' => date('Y-m-d', strtotime('+10 weeks')),
            'budget' => 3000
        )
    );
    
    foreach ($sample_projects as $project_data) {
        // Create the post
        $post_id = wp_insert_post(array(
            'post_title' => $project_data['title'],
            'post_content' => $project_data['description'],
            'post_status' => 'publish',
            'post_type' => 'villa_projects',
            'post_author' => 1 // Admin user
        ));
        
        if ($post_id && !is_wp_error($post_id)) {
            // Set taxonomies
            wp_set_object_terms($post_id, $project_data['type'], 'project_type');
            wp_set_object_terms($post_id, $project_data['status'], 'project_status');
            wp_set_object_terms($post_id, $project_data['group'], 'assigned_group');
            wp_set_object_terms($post_id, $project_data['priority'], 'priority');
            wp_set_object_terms($post_id, $project_data['visibility'], 'visibility');
            
            // Set custom fields
            update_post_meta($post_id, 'project_goals', $project_data['goals']);
            update_post_meta($post_id, 'project_start_date', $project_data['start_date']);
            update_post_meta($post_id, 'project_end_date', $project_data['end_date']);
            update_post_meta($post_id, 'budget_allocated', $project_data['budget']);
            update_post_meta($post_id, 'is_sample_data', '1');
            
            // Assign to admin user for testing
            update_post_meta($post_id, 'assigned_to', array(1));
        }
    }
}

// Hook to create sample data when the plugin is activated
add_action('init', 'villa_create_sample_projects');

/**
 * Add admin menu item to create sample data manually
 */
function villa_projects_sample_data_menu() {
    add_submenu_page(
        'tools.php',
        'Villa Projects Sample Data',
        'Projects Sample Data',
        'manage_options',
        'villa-projects-sample-data',
        'villa_projects_sample_data_page'
    );
}
add_action('admin_menu', 'villa_projects_sample_data_menu');

/**
 * Sample data admin page
 */
function villa_projects_sample_data_page() {
    if (isset($_POST['create_sample_data']) && wp_verify_nonce($_POST['_wpnonce'], 'villa_create_sample_data')) {
        villa_create_sample_projects();
        echo '<div class="notice notice-success"><p>Sample projects created successfully!</p></div>';
    }
    
    if (isset($_POST['delete_sample_data']) && wp_verify_nonce($_POST['_wpnonce'], 'villa_delete_sample_data')) {
        villa_delete_sample_projects();
        echo '<div class="notice notice-success"><p>Sample projects deleted successfully!</p></div>';
    }
    
    $sample_count = count(get_posts(array(
        'post_type' => 'villa_projects',
        'meta_key' => 'is_sample_data',
        'meta_value' => '1',
        'posts_per_page' => -1
    )));
    
    ?>
    <div class="wrap">
        <h1>Villa Projects Sample Data</h1>
        <p>This tool helps you create or remove sample project data for testing the Villa Projects system.</p>
        
        <div class="card">
            <h2>Current Status</h2>
            <p><strong>Sample Projects:</strong> <?php echo $sample_count; ?> projects found</p>
        </div>
        
        <div class="card">
            <h2>Create Sample Data</h2>
            <p>Creates 6 sample projects with different types, statuses, and assigned groups.</p>
            <form method="post">
                <?php wp_nonce_field('villa_create_sample_data'); ?>
                <button type="submit" name="create_sample_data" class="button button-primary">Create Sample Projects</button>
            </form>
        </div>
        
        <?php if ($sample_count > 0): ?>
        <div class="card">
            <h2>Delete Sample Data</h2>
            <p style="color: #d63384;"><strong>Warning:</strong> This will permanently delete all sample projects.</p>
            <form method="post">
                <?php wp_nonce_field('villa_delete_sample_data'); ?>
                <button type="submit" name="delete_sample_data" class="button button-secondary" onclick="return confirm('Are you sure you want to delete all sample projects?')">Delete Sample Projects</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Delete sample projects
 */
function villa_delete_sample_projects() {
    $sample_projects = get_posts(array(
        'post_type' => 'villa_projects',
        'meta_key' => 'is_sample_data',
        'meta_value' => '1',
        'posts_per_page' => -1
    ));
    
    foreach ($sample_projects as $project) {
        wp_delete_post($project->ID, true);
    }
}

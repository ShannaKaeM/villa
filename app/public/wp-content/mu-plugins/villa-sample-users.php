<?php
/**
 * Villa Community Sample Users Generator
 * Creates 20 sample user profiles for testing and demonstration
 * 
 * @package VillaCommunity
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate 20 sample users for Villa Community
 */
function villa_create_sample_users() {
    // Prevent duplicate creation
    if (get_option('villa_sample_users_created')) {
        wp_die('Sample users have already been created. To recreate them, first delete the existing ones or reset the villa_sample_users_created option.');
    }
    
    // Sample user data with realistic Villa Community information
    $sample_users = array(
        // OWNER ROLE
        array(
            'username' => 'maria_rodriguez',
            'email' => 'maria.rodriguez@villacommunity.com',
            'first_name' => 'Maria',
            'last_name' => 'Rodriguez',
            'display_name' => 'Maria Rodriguez',
            'role' => 'owner',
            'villa_roles' => array('owner'),
            'villa_address' => '1234 Villa Grande Blvd',
            'phone' => '(555) 123-4567',
            'emergency_contact' => 'Carlos Rodriguez',
            'emergency_phone' => '(555) 123-4568',
            'move_in_date' => '2018-03-15',
            'property_interest' => 'owner',
            'company' => 'Rodriguez Real Estate Group',
            'job_title' => 'CEO & Founder',
            'business_type' => 'real_estate',
            'community_involvement' => 'active',
            'committees' => array('legal', 'budget'),
            'interests' => 'Real estate investment, community development, tennis, and family time.',
            'bio' => 'Maria is the visionary behind Villa Community, bringing over 20 years of real estate experience to create a premier living destination.',
        ),

        // BOD MEMBERS
        array(
            'username' => 'james_thompson',
            'email' => 'james.thompson@villacommunity.com',
            'first_name' => 'James',
            'last_name' => 'Thompson',
            'display_name' => 'James Thompson',
            'role' => 'bod',
            'villa_roles' => array('bod'),
            'villa_address' => '2567 Villa Vista Way',
            'phone' => '(555) 234-5678',
            'emergency_contact' => 'Sarah Thompson',
            'emergency_phone' => '(555) 234-5679',
            'move_in_date' => '2019-06-20',
            'property_interest' => 'owner',
            'company' => 'Thompson Financial Advisors',
            'job_title' => 'Senior Financial Advisor',
            'business_type' => 'service',
            'community_involvement' => 'active',
            'committees' => array('budget', 'legal'),
            'interests' => 'Financial planning, golf, reading, and community governance.',
            'bio' => 'James serves on the Board of Directors and brings extensive financial expertise to guide Villa Community\'s fiscal responsibility.',
        ),

        array(
            'username' => 'susan_chen',
            'email' => 'susan.chen@villacommunity.com',
            'first_name' => 'Susan',
            'last_name' => 'Chen',
            'display_name' => 'Dr. Susan Chen',
            'role' => 'bod',
            'villa_roles' => array('bod'),
            'villa_address' => '3891 Villa Heights Dr',
            'phone' => '(555) 345-6789',
            'emergency_contact' => 'David Chen',
            'emergency_phone' => '(555) 345-6790',
            'move_in_date' => '2020-01-10',
            'property_interest' => 'owner',
            'company' => 'Villa Medical Center',
            'job_title' => 'Chief Medical Officer',
            'business_type' => 'healthcare',
            'community_involvement' => 'active',
            'committees' => array('operations', 'grounds'),
            'interests' => 'Healthcare advocacy, gardening, yoga, and medical research.',
            'bio' => 'Dr. Chen leads our healthcare initiatives and ensures Villa Community maintains the highest standards of resident wellness.',
        ),

        // DOV MEMBERS
        array(
            'username' => 'robert_williams',
            'email' => 'robert.williams@dov.gov',
            'first_name' => 'Robert',
            'last_name' => 'Williams',
            'display_name' => 'Robert Williams',
            'role' => 'dov',
            'villa_roles' => array('dov'),
            'villa_address' => 'DOV Administrative Office',
            'phone' => '(555) 456-7890',
            'emergency_contact' => 'Linda Williams',
            'emergency_phone' => '(555) 456-7891',
            'move_in_date' => '2021-04-01',
            'property_interest' => 'resident',
            'company' => 'Department of Villages',
            'job_title' => 'Community Development Director',
            'business_type' => 'service',
            'community_involvement' => 'active',
            'committees' => array('legal', 'operations'),
            'interests' => 'Urban planning, community development, cycling, and public service.',
            'bio' => 'Robert oversees community development and ensures Villa Community meets all regulatory requirements and development standards.',
        ),

        // COMMITTEE MEMBERS
        array(
            'username' => 'jennifer_davis',
            'email' => 'jennifer.davis@villacommunity.com',
            'first_name' => 'Jennifer',
            'last_name' => 'Davis',
            'display_name' => 'Jennifer Davis',
            'role' => 'committee',
            'villa_roles' => array('committee'),
            'villa_address' => '4567 Villa Park Lane',
            'phone' => '(555) 567-8901',
            'emergency_contact' => 'Michael Davis',
            'emergency_phone' => '(555) 567-8902',
            'move_in_date' => '2021-08-15',
            'property_interest' => 'owner',
            'company' => 'Davis Marketing Solutions',
            'job_title' => 'Marketing Director',
            'business_type' => 'service',
            'community_involvement' => 'active',
            'committees' => array('technology'),
            'interests' => 'Digital marketing, social media, photography, and community events.',
            'bio' => 'Jennifer leads our technology and marketing initiatives, helping Villa Community maintain a strong digital presence.',
        ),

        array(
            'username' => 'michael_johnson',
            'email' => 'michael.johnson@villacommunity.com',
            'first_name' => 'Michael',
            'last_name' => 'Johnson',
            'display_name' => 'Michael Johnson',
            'role' => 'committee',
            'villa_roles' => array('committee'),
            'villa_address' => '5678 Villa Garden Court',
            'phone' => '(555) 678-9012',
            'emergency_contact' => 'Lisa Johnson',
            'emergency_phone' => '(555) 678-9013',
            'move_in_date' => '2020-11-30',
            'property_interest' => 'owner',
            'company' => 'Johnson Landscaping',
            'job_title' => 'Landscape Architect',
            'business_type' => 'service',
            'community_involvement' => 'active',
            'committees' => array('grounds'),
            'interests' => 'Landscape design, sustainable gardening, hiking, and environmental conservation.',
            'bio' => 'Michael ensures Villa Community maintains beautiful, sustainable landscapes that enhance our quality of life.',
        ),

        // STAFF MEMBERS
        array(
            'username' => 'lisa_martinez',
            'email' => 'lisa.martinez@villacommunity.com',
            'first_name' => 'Lisa',
            'last_name' => 'Martinez',
            'display_name' => 'Lisa Martinez',
            'role' => 'staff',
            'villa_roles' => array('staff'),
            'villa_address' => 'Staff Housing Unit A',
            'phone' => '(555) 789-0123',
            'emergency_contact' => 'Ana Martinez',
            'emergency_phone' => '(555) 789-0124',
            'move_in_date' => '2022-02-01',
            'property_interest' => 'resident',
            'company' => 'Villa Community Management',
            'job_title' => 'Community Manager',
            'business_type' => 'service',
            'community_involvement' => 'active',
            'committees' => array('operations'),
            'interests' => 'Community management, event planning, fitness, and customer service.',
            'bio' => 'Lisa manages day-to-day operations and ensures all residents receive exceptional service and support.',
        ),

        array(
            'username' => 'david_brown',
            'email' => 'david.brown@villacommunity.com',
            'first_name' => 'David',
            'last_name' => 'Brown',
            'display_name' => 'David Brown',
            'role' => 'staff',
            'villa_roles' => array('staff'),
            'villa_address' => 'Staff Housing Unit B',
            'phone' => '(555) 890-1234',
            'emergency_contact' => 'Karen Brown',
            'emergency_phone' => '(555) 890-1235',
            'move_in_date' => '2021-12-15',
            'property_interest' => 'resident',
            'company' => 'Villa Community Maintenance',
            'job_title' => 'Maintenance Supervisor',
            'business_type' => 'maintenance',
            'community_involvement' => 'moderate',
            'committees' => array('operations'),
            'interests' => 'Home improvement, woodworking, fishing, and problem-solving.',
            'bio' => 'David leads our maintenance team and ensures Villa Community facilities are always in top condition.',
        ),

        // BUSINESS PARTNERS
        array(
            'username' => 'sarah_wilson',
            'email' => 'sarah.wilson@villabistro.com',
            'first_name' => 'Sarah',
            'last_name' => 'Wilson',
            'display_name' => 'Sarah Wilson',
            'role' => 'partner',
            'villa_roles' => array('partner'),
            'villa_address' => 'Villa Bistro - Commercial District',
            'phone' => '(555) 901-2345',
            'emergency_contact' => 'Tom Wilson',
            'emergency_phone' => '(555) 901-2346',
            'move_in_date' => '2022-05-01',
            'property_interest' => 'investor',
            'company' => 'Villa Bistro & Catering',
            'job_title' => 'Owner & Executive Chef',
            'business_type' => 'restaurant',
            'community_involvement' => 'moderate',
            'committees' => array(),
            'interests' => 'Culinary arts, local sourcing, wine pairing, and community dining experiences.',
            'bio' => 'Sarah brings gourmet dining to Villa Community with her award-winning bistro and catering services.',
        ),

        array(
            'username' => 'carlos_garcia',
            'email' => 'carlos.garcia@villafitness.com',
            'first_name' => 'Carlos',
            'last_name' => 'Garcia',
            'display_name' => 'Carlos Garcia',
            'role' => 'partner',
            'villa_roles' => array('partner'),
            'villa_address' => 'Villa Fitness Center',
            'phone' => '(555) 012-3456',
            'emergency_contact' => 'Maria Garcia',
            'emergency_phone' => '(555) 012-3457',
            'move_in_date' => '2021-09-01',
            'property_interest' => 'investor',
            'company' => 'Villa Fitness & Wellness',
            'job_title' => 'Fitness Director',
            'business_type' => 'recreation',
            'community_involvement' => 'active',
            'committees' => array(),
            'interests' => 'Personal training, nutrition, wellness coaching, and community health.',
            'bio' => 'Carlos promotes healthy living through comprehensive fitness and wellness programs for all residents.',
        ),

        // COMMUNITY MEMBERS
        array(
            'username' => 'nancy_taylor',
            'email' => 'nancy.taylor@email.com',
            'first_name' => 'Nancy',
            'last_name' => 'Taylor',
            'display_name' => 'Nancy Taylor',
            'role' => 'community_member',
            'villa_roles' => array('community_member'),
            'villa_address' => '6789 Villa Sunset Drive',
            'phone' => '(555) 123-4567',
            'emergency_contact' => 'John Taylor',
            'emergency_phone' => '(555) 123-4568',
            'move_in_date' => '2022-01-15',
            'property_interest' => 'owner',
            'company' => 'Retired',
            'job_title' => 'Retired Teacher',
            'business_type' => '',
            'community_involvement' => 'moderate',
            'committees' => array(),
            'interests' => 'Reading, gardening, volunteering, and spending time with grandchildren.',
            'bio' => 'Nancy is a retired educator who enjoys the peaceful lifestyle and community activities at Villa Community.',
        ),

        array(
            'username' => 'kevin_anderson',
            'email' => 'kevin.anderson@email.com',
            'first_name' => 'Kevin',
            'last_name' => 'Anderson',
            'display_name' => 'Kevin Anderson',
            'role' => 'community_member',
            'villa_roles' => array('community_member'),
            'villa_address' => '7890 Villa Creek Way',
            'phone' => '(555) 234-5678',
            'emergency_contact' => 'Amy Anderson',
            'emergency_phone' => '(555) 234-5679',
            'move_in_date' => '2023-03-01',
            'property_interest' => 'owner',
            'company' => 'Tech Solutions Inc',
            'job_title' => 'Software Engineer',
            'business_type' => 'service',
            'community_involvement' => 'occasional',
            'committees' => array(),
            'interests' => 'Technology, gaming, cycling, and smart home automation.',
            'bio' => 'Kevin works remotely as a software engineer and appreciates the modern amenities and tech-friendly environment.',
        ),

        array(
            'username' => 'patricia_white',
            'email' => 'patricia.white@email.com',
            'first_name' => 'Patricia',
            'last_name' => 'White',
            'display_name' => 'Patricia White',
            'role' => 'community_member',
            'villa_roles' => array('community_member'),
            'villa_address' => '8901 Villa Meadow Lane',
            'phone' => '(555) 345-6789',
            'emergency_contact' => 'Robert White',
            'emergency_phone' => '(555) 345-6790',
            'move_in_date' => '2022-07-20',
            'property_interest' => 'owner',
            'company' => 'White Design Studio',
            'job_title' => 'Interior Designer',
            'business_type' => 'service',
            'community_involvement' => 'active',
            'committees' => array(),
            'interests' => 'Interior design, art, home decorating, and community beautification.',
            'bio' => 'Patricia brings her design expertise to help make Villa Community beautiful and welcoming for all residents.',
        ),

        array(
            'username' => 'daniel_lee',
            'email' => 'daniel.lee@email.com',
            'first_name' => 'Daniel',
            'last_name' => 'Lee',
            'display_name' => 'Daniel Lee',
            'role' => 'community_member',
            'villa_roles' => array('community_member'),
            'villa_address' => '9012 Villa Ridge Road',
            'phone' => '(555) 456-7890',
            'emergency_contact' => 'Helen Lee',
            'emergency_phone' => '(555) 456-7891',
            'move_in_date' => '2023-01-10',
            'property_interest' => 'buyer',
            'company' => 'Lee Investment Group',
            'job_title' => 'Investment Advisor',
            'business_type' => 'service',
            'community_involvement' => 'new',
            'committees' => array(),
            'interests' => 'Investment strategies, financial planning, tennis, and networking.',
            'bio' => 'Daniel is exploring property investment opportunities and enjoys the professional networking within Villa Community.',
        ),

        array(
            'username' => 'barbara_harris',
            'email' => 'barbara.harris@email.com',
            'first_name' => 'Barbara',
            'last_name' => 'Harris',
            'display_name' => 'Barbara Harris',
            'role' => 'community_member',
            'villa_roles' => array('community_member'),
            'villa_address' => '1123 Villa Palm Court',
            'phone' => '(555) 567-8901',
            'emergency_contact' => 'William Harris',
            'emergency_phone' => '(555) 567-8902',
            'move_in_date' => '2021-11-05',
            'property_interest' => 'owner',
            'company' => 'Harris Healthcare Consulting',
            'job_title' => 'Healthcare Consultant',
            'business_type' => 'healthcare',
            'community_involvement' => 'moderate',
            'committees' => array(),
            'interests' => 'Healthcare advocacy, wellness programs, book clubs, and community health.',
            'bio' => 'Barbara advocates for resident wellness and helps coordinate health and wellness programs in the community.',
        ),

        array(
            'username' => 'thomas_clark',
            'email' => 'thomas.clark@email.com',
            'first_name' => 'Thomas',
            'last_name' => 'Clark',
            'display_name' => 'Thomas Clark',
            'role' => 'community_member',
            'villa_roles' => array('community_member'),
            'villa_address' => '2234 Villa Oak Street',
            'phone' => '(555) 678-9012',
            'emergency_contact' => 'Mary Clark',
            'emergency_phone' => '(555) 678-9013',
            'move_in_date' => '2022-09-15',
            'property_interest' => 'owner',
            'company' => 'Clark Legal Services',
            'job_title' => 'Attorney',
            'business_type' => 'service',
            'community_involvement' => 'occasional',
            'committees' => array(),
            'interests' => 'Legal research, community law, golf, and family time.',
            'bio' => 'Thomas provides legal guidance to residents and ensures Villa Community operates within all legal frameworks.',
        ),

        array(
            'username' => 'carol_rodriguez',
            'email' => 'carol.rodriguez@email.com',
            'first_name' => 'Carol',
            'last_name' => 'Rodriguez',
            'display_name' => 'Carol Rodriguez',
            'role' => 'community_member',
            'villa_roles' => array('community_member'),
            'villa_address' => '3345 Villa Pine Avenue',
            'phone' => '(555) 789-0123',
            'emergency_contact' => 'Luis Rodriguez',
            'emergency_phone' => '(555) 789-0124',
            'move_in_date' => '2023-02-28',
            'property_interest' => 'renter',
            'company' => 'Rodriguez Education Services',
            'job_title' => 'Educational Coordinator',
            'business_type' => 'education',
            'community_involvement' => 'active',
            'committees' => array(),
            'interests' => 'Education, children\'s programs, reading, and community learning initiatives.',
            'bio' => 'Carol coordinates educational programs and activities for families and children in Villa Community.',
        ),

        array(
            'username' => 'mark_thompson',
            'email' => 'mark.thompson@email.com',
            'first_name' => 'Mark',
            'last_name' => 'Thompson',
            'display_name' => 'Mark Thompson',
            'role' => 'community_member',
            'villa_roles' => array('community_member'),
            'villa_address' => '4456 Villa Maple Drive',
            'phone' => '(555) 890-1234',
            'emergency_contact' => 'Jennifer Thompson',
            'emergency_phone' => '(555) 890-1235',
            'move_in_date' => '2022-12-01',
            'property_interest' => 'owner',
            'company' => 'Thompson Construction',
            'job_title' => 'General Contractor',
            'business_type' => 'maintenance',
            'community_involvement' => 'moderate',
            'committees' => array(),
            'interests' => 'Construction, home improvement, woodworking, and community building projects.',
            'bio' => 'Mark assists with construction and maintenance projects, helping keep Villa Community in excellent condition.',
        ),

        array(
            'username' => 'linda_walker',
            'email' => 'linda.walker@email.com',
            'first_name' => 'Linda',
            'last_name' => 'Walker',
            'display_name' => 'Linda Walker',
            'role' => 'community_member',
            'villa_roles' => array('community_member'),
            'villa_address' => '5567 Villa Elm Circle',
            'phone' => '(555) 901-2345',
            'emergency_contact' => 'Paul Walker',
            'emergency_phone' => '(555) 901-2346',
            'move_in_date' => '2021-05-15',
            'property_interest' => 'owner',
            'company' => 'Walker Real Estate',
            'job_title' => 'Real Estate Agent',
            'business_type' => 'real_estate',
            'community_involvement' => 'active',
            'committees' => array(),
            'interests' => 'Real estate, community development, swimming, and social events.',
            'bio' => 'Linda helps new residents find their perfect home in Villa Community and organizes social events.',
        ),

        array(
            'username' => 'richard_hall',
            'email' => 'richard.hall@email.com',
            'first_name' => 'Richard',
            'last_name' => 'Hall',
            'display_name' => 'Richard Hall',
            'role' => 'community_member',
            'villa_roles' => array('community_member'),
            'villa_address' => '6678 Villa Cedar Lane',
            'phone' => '(555) 012-3456',
            'emergency_contact' => 'Susan Hall',
            'emergency_phone' => '(555) 012-3457',
            'move_in_date' => '2023-04-10',
            'property_interest' => 'investor',
            'company' => 'Hall Investment Partners',
            'job_title' => 'Investment Manager',
            'business_type' => 'service',
            'community_involvement' => 'observer',
            'committees' => array(),
            'interests' => 'Investment analysis, market research, photography, and travel.',
            'bio' => 'Richard evaluates investment opportunities in Villa Community and enjoys the upscale lifestyle and amenities.',
        ),
    );

    return $sample_users;
}

/**
 * Get or create Villa avatar attachment for avatars
 */
function villa_get_default_avatar_id() {
    // Check if we already have the avatar in media library
    $existing_avatar = get_posts(array(
        'post_type' => 'attachment',
        'meta_query' => array(
            array(
                'key' => '_villa_default_avatar',
                'value' => '1',
                'compare' => '='
            )
        ),
        'posts_per_page' => 1
    ));
    
    if (!empty($existing_avatar)) {
        return $existing_avatar[0]->ID;
    }
    
    // Path to the Villa avatar
    $avatar_path = get_template_directory() . '/miDocs/SITE DATA/Images/Branding/Avatars/avatar-secondary.png';
    
    if (!file_exists($avatar_path)) {
        return 0;
    }
    
    // Upload the avatar to media library
    $upload_dir = wp_upload_dir();
    $filename = 'villa-community-avatar.png';
    $new_path = $upload_dir['path'] . '/' . $filename;
    
    // Copy file to uploads directory
    if (!copy($avatar_path, $new_path)) {
        return 0;
    }
    
    // Create attachment
    $attachment = array(
        'post_mime_type' => 'image/png',
        'post_title' => 'Villa Community Default Avatar',
        'post_content' => '',
        'post_status' => 'inherit'
    );
    
    $attachment_id = wp_insert_attachment($attachment, $new_path);
    
    if (!is_wp_error($attachment_id)) {
        // Generate attachment metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $new_path);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
        
        // Mark as default avatar
        update_post_meta($attachment_id, '_villa_default_avatar', '1');
        
        return $attachment_id;
    }
    
    return 0;
}

/**
 * Create WordPress users from sample data
 */
function villa_generate_sample_users() {
    $sample_users = villa_create_sample_users();
    $created_users = array();
    $default_avatar_id = villa_get_default_avatar_id();
    
    foreach ($sample_users as $user_data) {
        // Check if user already exists
        if (username_exists($user_data['username']) || email_exists($user_data['email'])) {
            continue;
        }
        
        // Create the user
        $user_id = wp_create_user(
            $user_data['username'],
            wp_generate_password(), // Generate random password
            $user_data['email']
        );
        
        if (is_wp_error($user_id)) {
            continue;
        }
        
        // Update user meta
        wp_update_user(array(
            'ID' => $user_id,
            'first_name' => $user_data['first_name'],
            'last_name' => $user_data['last_name'],
            'display_name' => $user_data['display_name'],
        ));
        
        // Set user role
        $user = new WP_User($user_id);
        $user->set_role($user_data['role']);
        
        // Create corresponding user profile CPT
        $profile_id = wp_insert_post(array(
            'post_title' => $user_data['display_name'],
            'post_content' => $user_data['bio'],
            'post_status' => 'publish',
            'post_type' => 'user_profile',
            'post_author' => $user_id,
        ));
        
        if ($profile_id && !is_wp_error($profile_id)) {
            // Link the user to the profile
            update_post_meta($profile_id, 'linked_user_id', $user_id);
            update_user_meta($user_id, 'profile_post_id', $profile_id);
            
            // Set Villa avatar as featured image (default avatar)
            if ($default_avatar_id) {
                update_post_meta($profile_id, '_thumbnail_id', $default_avatar_id);
            }
            
            // Add profile-specific meta
            $villa_roles_array = array();
            foreach ($user_data['villa_roles'] as $role) {
                $villa_roles_array[$role] = 'on';
            }
            update_post_meta($profile_id, 'profile_villa_roles', $villa_roles_array);
            update_post_meta($profile_id, 'profile_villa_address', $user_data['villa_address']);
            update_post_meta($profile_id, 'profile_company', $user_data['company']);
            update_post_meta($profile_id, 'profile_job_title', $user_data['job_title']);
            update_post_meta($profile_id, 'profile_phone', $user_data['phone']);
            update_post_meta($profile_id, 'profile_emergency_contact', $user_data['emergency_contact']);
            update_post_meta($profile_id, 'profile_emergency_phone', $user_data['emergency_phone']);
            update_post_meta($profile_id, 'profile_move_in_date', $user_data['move_in_date']);
            update_post_meta($profile_id, 'profile_property_interest', $user_data['property_interest']);
            update_post_meta($profile_id, 'profile_business_type', $user_data['business_type']);
            update_post_meta($profile_id, 'profile_community_involvement', $user_data['community_involvement']);
            update_post_meta($profile_id, 'profile_committees', $user_data['committees']);
            update_post_meta($profile_id, 'profile_interests', $user_data['interests']);
            update_post_meta($profile_id, 'profile_visibility', 'members');
            update_post_meta($profile_id, 'profile_show_contact', true);
        }
        
        $created_users[] = array(
            'user_id' => $user_id,
            'profile_id' => $profile_id,
            'username' => $user_data['username'],
            'role' => $user_data['role'],
            'name' => $user_data['display_name']
        );
    }
    
    // Mark sample users as created to prevent duplicates
    if (!empty($created_users)) {
        update_option('villa_sample_users_created', true);
    }
    
    return $created_users;
}

/**
 * Admin function to generate sample users
 */
function villa_admin_generate_users() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    if (isset($_POST['generate_sample_users']) && wp_verify_nonce($_POST['_wpnonce'], 'generate_sample_users')) {
        $created_users = villa_generate_sample_users();
        
        $message = sprintf(
            'Successfully created %d sample users and profiles.',
            count($created_users)
        );
        
        add_action('admin_notices', function() use ($message) {
            echo '<div class="notice notice-success"><p>' . esc_html($message) . '</p></div>';
        });
    }
}

/**
 * Add admin menu for sample user generation
 */
function villa_add_sample_users_menu() {
    add_management_page(
        'Generate Sample Users',
        'Sample Users',
        'manage_options',
        'villa-sample-users',
        'villa_sample_users_page'
    );
}
add_action('admin_menu', 'villa_add_sample_users_menu');

/**
 * Sample users admin page
 */
function villa_sample_users_page() {
    villa_admin_generate_users();
    ?>
    <div class="wrap">
        <h1>Villa Community Sample Users</h1>
        <p>Generate 20 sample users with realistic data for testing and demonstration purposes.</p>
        
        <form method="post" action="">
            <?php wp_nonce_field('generate_sample_users'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Sample Users</th>
                    <td>
                        <p>This will create 20 sample users with the following roles:</p>
                        <ul>
                            <li><strong>Owner (1):</strong> Maria Rodriguez - Community founder</li>
                            <li><strong>BOD (2):</strong> James Thompson, Dr. Susan Chen</li>
                            <li><strong>DOV (1):</strong> Robert Williams - Department of Villages</li>
                            <li><strong>Committee (2):</strong> Jennifer Davis, Michael Johnson</li>
                            <li><strong>Staff (2):</strong> Lisa Martinez, David Brown</li>
                            <li><strong>Partners (2):</strong> Sarah Wilson (Bistro), Carlos Garcia (Fitness)</li>
                            <li><strong>Community Members (10):</strong> Various residents with different backgrounds</li>
                        </ul>
                        <p><strong>Note:</strong> Each user will have a corresponding User Profile CPT with detailed information.</p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="generate_sample_users" class="button-primary" value="Generate Sample Users" />
            </p>
        </form>
    </div>
    <?php
}

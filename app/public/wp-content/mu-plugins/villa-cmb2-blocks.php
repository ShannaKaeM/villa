<?php
/**
 * Villa CMB2 Blocks
 * 
 * Creates Gutenberg blocks using CMB2 fields for Villa Community
 * 
 * @package VillaCommunity
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register CMB2 blocks
 */
function villa_register_cmb2_blocks() {
    if (!function_exists('new_cmb2_box')) {
        return;
    }

    // Property Showcase Block
    villa_register_property_showcase_block();
    
    // Business Directory Block
    villa_register_business_directory_block();
    
    // Community Stats Block
    villa_register_community_stats_block();
    
    // Announcements Block
    villa_register_announcements_block();
    
    // Dashboard Properties Block
    villa_register_dashboard_properties_block();
    
    // Dashboard Tickets Block
    villa_register_dashboard_tickets_block();
    
    // Dashboard Groups Block
    villa_register_dashboard_groups_block();
    
    // Dashboard Business Block
    villa_register_dashboard_business_block();
    
    // Dashboard Announcements Block
    villa_register_dashboard_announcements_block();
    
    // Dashboard Owner Portal Block
    villa_register_dashboard_owner_portal_block();
}
add_action('cmb2_admin_init', 'villa_register_cmb2_blocks');

/**
 * Property Showcase Block
 */
function villa_register_property_showcase_block() {
    $cmb = new_cmb2_box(array(
        'id'           => 'villa_property_showcase',
        'title'        => __('Property Showcase', 'migv'),
        'object_types' => array('page', 'post'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
        'cmb2_theme_options' => array(
            'block' => true,
            'block_title' => __('Property Showcase', 'migv'),
            'block_description' => __('Display featured properties in a grid layout', 'migv'),
            'block_category' => 'villa-community',
            'block_icon' => 'admin-home',
            'block_keywords' => array('property', 'real estate', 'villa'),
        ),
    ));

    $cmb->add_field(array(
        'name' => __('Block Title', 'migv'),
        'id'   => 'showcase_title',
        'type' => 'text',
        'default' => 'Featured Properties',
    ));

    $cmb->add_field(array(
        'name' => __('Number of Properties', 'migv'),
        'id'   => 'showcase_count',
        'type' => 'text_small',
        'attributes' => array(
            'type' => 'number',
            'min'  => '1',
            'max'  => '12',
        ),
        'default' => '6',
    ));

    $cmb->add_field(array(
        'name' => __('Property Type Filter', 'migv'),
        'id'   => 'showcase_type',
        'type' => 'select',
        'options' => array(
            'all'        => __('All Types', 'migv'),
            'villa'      => __('Villas Only', 'migv'),
            'apartment'  => __('Apartments Only', 'migv'),
            'condo'      => __('Condos Only', 'migv'),
            'townhouse'  => __('Townhouses Only', 'migv'),
            'commercial' => __('Commercial Only', 'migv'),
        ),
        'default' => 'all',
    ));

    $cmb->add_field(array(
        'name' => __('Status Filter', 'migv'),
        'id'   => 'showcase_status',
        'type' => 'select',
        'options' => array(
            'all'       => __('All Statuses', 'migv'),
            'available' => __('Available Only', 'migv'),
            'sold'      => __('Sold Only', 'migv'),
            'rented'    => __('Rented Only', 'migv'),
        ),
        'default' => 'available',
    ));

    $cmb->add_field(array(
        'name' => __('Layout Style', 'migv'),
        'id'   => 'showcase_layout',
        'type' => 'select',
        'options' => array(
            'grid'     => __('Grid Layout', 'migv'),
            'carousel' => __('Carousel/Slider', 'migv'),
            'list'     => __('List Layout', 'migv'),
        ),
        'default' => 'grid',
    ));

    $cmb->add_field(array(
        'name' => __('Show Property Details', 'migv'),
        'id'   => 'showcase_show_details',
        'type' => 'multicheck',
        'options' => array(
            'price'     => __('Price', 'migv'),
            'bedrooms'  => __('Bedrooms', 'migv'),
            'bathrooms' => __('Bathrooms', 'migv'),
            'sqft'      => __('Square Feet', 'migv'),
            'status'    => __('Status', 'migv'),
        ),
        'default' => array('price', 'bedrooms', 'bathrooms'),
    ));
}

/**
 * Business Directory Block
 */
function villa_register_business_directory_block() {
    $cmb = new_cmb2_box(array(
        'id'           => 'villa_business_directory',
        'title'        => __('Business Directory', 'migv'),
        'object_types' => array('page', 'post'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
        'cmb2_theme_options' => array(
            'block' => true,
            'block_title' => __('Business Directory', 'migv'),
            'block_description' => __('Display community businesses in a directory format', 'migv'),
            'block_category' => 'villa-community',
            'block_icon' => 'store',
            'block_keywords' => array('business', 'directory', 'community'),
        ),
    ));

    $cmb->add_field(array(
        'name' => __('Block Title', 'migv'),
        'id'   => 'directory_title',
        'type' => 'text',
        'default' => 'Community Business Directory',
    ));

    $cmb->add_field(array(
        'name' => __('Business Type Filter', 'migv'),
        'id'   => 'directory_type',
        'type' => 'select',
        'options' => array(
            'all'         => __('All Types', 'migv'),
            'restaurant'  => __('Restaurants', 'migv'),
            'retail'      => __('Retail', 'migv'),
            'service'     => __('Services', 'migv'),
            'healthcare'  => __('Healthcare', 'migv'),
            'recreation'  => __('Recreation', 'migv'),
        ),
        'default' => 'all',
    ));

    $cmb->add_field(array(
        'name' => __('Layout Style', 'migv'),
        'id'   => 'directory_layout',
        'type' => 'select',
        'options' => array(
            'cards' => __('Card Layout', 'migv'),
            'list'  => __('List Layout', 'migv'),
            'table' => __('Table Layout', 'migv'),
        ),
        'default' => 'cards',
    ));

    $cmb->add_field(array(
        'name' => __('Show Search Filter', 'migv'),
        'id'   => 'directory_search',
        'type' => 'checkbox',
        'desc' => __('Allow users to search and filter businesses', 'migv'),
    ));
}

/**
 * Community Stats Block
 */
function villa_register_community_stats_block() {
    $cmb = new_cmb2_box(array(
        'id'           => 'villa_community_stats',
        'title'        => __('Community Statistics', 'migv'),
        'object_types' => array('page', 'post'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
        'cmb2_theme_options' => array(
            'block' => true,
            'block_title' => __('Community Stats', 'migv'),
            'block_description' => __('Display community statistics and metrics', 'migv'),
            'block_category' => 'villa-community',
            'block_icon' => 'chart-bar',
            'block_keywords' => array('stats', 'statistics', 'community', 'metrics'),
        ),
    ));

    $cmb->add_field(array(
        'name' => __('Block Title', 'migv'),
        'id'   => 'stats_title',
        'type' => 'text',
        'default' => 'Community at a Glance',
    ));

    $cmb->add_field(array(
        'name' => __('Statistics to Display', 'migv'),
        'id'   => 'stats_display',
        'type' => 'multicheck',
        'options' => array(
            'total_properties' => __('Total Properties', 'migv'),
            'available_properties' => __('Available Properties', 'migv'),
            'total_members' => __('Total Members', 'migv'),
            'active_businesses' => __('Active Businesses', 'migv'),
            'committees' => __('Active Committees', 'migv'),
            'open_tickets' => __('Open Support Tickets', 'migv'),
        ),
        'default' => array('total_properties', 'total_members', 'active_businesses'),
    ));

    $cmb->add_field(array(
        'name' => __('Layout Style', 'migv'),
        'id'   => 'stats_layout',
        'type' => 'select',
        'options' => array(
            'horizontal' => __('Horizontal Layout', 'migv'),
            'grid'       => __('Grid Layout', 'migv'),
            'vertical'   => __('Vertical Layout', 'migv'),
        ),
        'default' => 'horizontal',
    ));
}

/**
 * Announcements Block
 */
function villa_register_announcements_block() {
    $cmb = new_cmb2_box(array(
        'id'           => 'villa_announcements',
        'title'        => __('Community Announcements', 'migv'),
        'object_types' => array('page', 'post'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
        'cmb2_theme_options' => array(
            'block' => true,
            'block_title' => __('Announcements', 'migv'),
            'block_description' => __('Display recent community announcements', 'migv'),
            'block_category' => 'villa-community',
            'block_icon' => 'megaphone',
            'block_keywords' => array('announcements', 'news', 'community'),
        ),
    ));

    $cmb->add_field(array(
        'name' => __('Block Title', 'migv'),
        'id'   => 'announcements_title',
        'type' => 'text',
        'default' => 'Latest Announcements',
    ));

    $cmb->add_field(array(
        'name' => __('Number of Announcements', 'migv'),
        'id'   => 'announcements_count',
        'type' => 'text_small',
        'attributes' => array(
            'type' => 'number',
            'min'  => '1',
            'max'  => '10',
        ),
        'default' => '3',
    ));

    $cmb->add_field(array(
        'name' => __('Announcement Type Filter', 'migv'),
        'id'   => 'announcements_type',
        'type' => 'select',
        'options' => array(
            'all'         => __('All Types', 'migv'),
            'general'     => __('General', 'migv'),
            'maintenance' => __('Maintenance', 'migv'),
            'emergency'   => __('Emergency', 'migv'),
            'event'       => __('Events', 'migv'),
        ),
        'default' => 'all',
    ));

    $cmb->add_field(array(
        'name' => __('Show Date', 'migv'),
        'id'   => 'announcements_show_date',
        'type' => 'checkbox',
        'desc' => __('Display announcement date', 'migv'),
    ));

    $cmb->add_field(array(
        'name' => __('Show Excerpt', 'migv'),
        'id'   => 'announcements_show_excerpt',
        'type' => 'checkbox',
        'desc' => __('Display announcement excerpt', 'migv'),
    ));
}

/**
 * Dashboard Properties Block
 */
function villa_register_dashboard_properties_block() {
    $cmb = new_cmb2_box(array(
        'id'           => 'villa_dashboard_properties_block',
        'title'        => __('Villa Dashboard - Properties Management', 'migv'),
        'object_types' => array('post', 'page'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
    ));

    $cmb->add_field(array(
        'name' => __('Block Title', 'migv'),
        'id'   => 'dashboard_properties_title',
        'type' => 'text',
        'default' => 'My Properties',
    ));

    $cmb->add_field(array(
        'name' => __('Show Add Property Form', 'migv'),
        'id'   => 'dashboard_properties_show_form',
        'type' => 'checkbox',
        'default' => true,
    ));

    $cmb->add_field(array(
        'name' => __('Properties Per Page', 'migv'),
        'id'   => 'dashboard_properties_per_page',
        'type' => 'text_small',
        'attributes' => array(
            'type' => 'number',
            'min'  => '1',
            'max'  => '20',
        ),
        'default' => '10',
    ));
}

/**
 * Dashboard Tickets Block
 */
function villa_register_dashboard_tickets_block() {
    $cmb = new_cmb2_box(array(
        'id'           => 'villa_dashboard_tickets_block',
        'title'        => __('Villa Dashboard - Support Tickets', 'migv'),
        'object_types' => array('post', 'page'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
    ));

    $cmb->add_field(array(
        'name' => __('Block Title', 'migv'),
        'id'   => 'dashboard_tickets_title',
        'type' => 'text',
        'default' => 'Support Tickets',
    ));

    $cmb->add_field(array(
        'name' => __('Show Create Ticket Form', 'migv'),
        'id'   => 'dashboard_tickets_show_form',
        'type' => 'checkbox',
        'default' => true,
    ));

    $cmb->add_field(array(
        'name' => __('Default Ticket Status Filter', 'migv'),
        'id'   => 'dashboard_tickets_default_filter',
        'type' => 'select',
        'options' => array(
            'all'        => __('All Tickets', 'migv'),
            'open'       => __('Open Tickets', 'migv'),
            'in_progress' => __('In Progress', 'migv'),
            'resolved'   => __('Resolved', 'migv'),
        ),
        'default' => 'open',
    ));
}

/**
 * Dashboard Groups Block
 */
function villa_register_dashboard_groups_block() {
    $cmb = new_cmb2_box(array(
        'id'           => 'villa_dashboard_groups_block',
        'title'        => __('Villa Dashboard - Groups & Committees', 'migv'),
        'object_types' => array('post', 'page'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
    ));

    $cmb->add_field(array(
        'name' => __('Block Title', 'migv'),
        'id'   => 'dashboard_groups_title',
        'type' => 'text',
        'default' => 'Groups & Committees',
    ));

    $cmb->add_field(array(
        'name' => __('Show All Groups Directory', 'migv'),
        'id'   => 'dashboard_groups_show_directory',
        'type' => 'checkbox',
        'default' => true,
    ));

    $cmb->add_field(array(
        'name' => __('Group Type Filter', 'migv'),
        'id'   => 'dashboard_groups_type_filter',
        'type' => 'select',
        'options' => array(
            'all'                => __('All Group Types', 'migv'),
            'committee'          => __('Committees Only', 'migv'),
            'board-of-directors' => __('Board of Directors', 'migv'),
            'staff'              => __('Staff Groups', 'migv'),
            'volunteer'          => __('Volunteer Groups', 'migv'),
        ),
        'default' => 'all',
    ));
}

/**
 * Dashboard Business Block
 */
function villa_register_dashboard_business_block() {
    $cmb = new_cmb2_box(array(
        'id'           => 'villa_dashboard_business_block',
        'title'        => __('Villa Dashboard - Business Management', 'migv'),
        'object_types' => array('post', 'page'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
    ));

    $cmb->add_field(array(
        'name' => __('Block Title', 'migv'),
        'id'   => 'dashboard_business_title',
        'type' => 'text',
        'default' => 'Business Listings',
    ));

    $cmb->add_field(array(
        'name' => __('Show Add Business Form', 'migv'),
        'id'   => 'dashboard_business_show_form',
        'type' => 'checkbox',
        'default' => true,
    ));

    $cmb->add_field(array(
        'name' => __('Show User\'s Businesses Only', 'migv'),
        'id'   => 'dashboard_business_user_only',
        'type' => 'checkbox',
        'default' => true,
        'desc' => __('If unchecked, will show all businesses (for admin view)', 'migv'),
    ));
}

/**
 * Dashboard Announcements Block
 */
function villa_register_dashboard_announcements_block() {
    $cmb = new_cmb2_box(array(
        'id'           => 'villa_dashboard_announcements_block',
        'title'        => __('Villa Dashboard - Announcements', 'migv'),
        'object_types' => array('post', 'page'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
    ));

    $cmb->add_field(array(
        'name' => __('Block Title', 'migv'),
        'id'   => 'dashboard_announcements_title',
        'type' => 'text',
        'default' => 'Announcements',
    ));
}

/**
 * Dashboard Owner Portal Block
 */
function villa_register_dashboard_owner_portal_block() {
    $cmb = new_cmb2_box(array(
        'id'           => 'villa_dashboard_owner_portal_block',
        'title'        => __('Villa Dashboard - Owner Portal', 'migv'),
        'object_types' => array('post', 'page'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
    ));

    $cmb->add_field(array(
        'name' => __('Block Title', 'migv'),
        'id'   => 'dashboard_owner_portal_title',
        'type' => 'text',
        'default' => 'Owner Portal',
    ));
}

/**
 * Register block category
 */
function villa_register_block_category($categories) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'villa-community',
                'title' => __('Villa Community', 'migv'),
                'icon'  => 'admin-home',
            ),
        )
    );
}
add_filter('block_categories_all', 'villa_register_block_category');

/**
 * Render blocks with Timber/Twig
 */
function villa_render_cmb2_blocks() {
    // Property Showcase Block Render
    add_action('cmb2_render_villa_property_showcase', function($block_data) {
        if (class_exists('Timber')) {
            $context = Timber::context();
            $context['block'] = $block_data;
            
            // Get properties based on block settings
            $args = array(
                'post_type' => 'property',
                'posts_per_page' => $block_data['showcase_count'] ?? 6,
                'post_status' => 'publish',
            );
            
            if (!empty($block_data['showcase_type']) && $block_data['showcase_type'] !== 'all') {
                $args['meta_query'][] = array(
                    'key' => 'property_type',
                    'value' => $block_data['showcase_type'],
                    'compare' => '='
                );
            }
            
            if (!empty($block_data['showcase_status']) && $block_data['showcase_status'] !== 'all') {
                $args['meta_query'][] = array(
                    'key' => 'property_status',
                    'value' => $block_data['showcase_status'],
                    'compare' => '='
                );
            }
            
            $context['properties'] = Timber::get_posts($args);
            
            Timber::render('blocks/property-showcase.twig', $context);
        }
    });
    
    // Business Directory Block Render
    add_action('cmb2_render_villa_business_directory', function($block_data) {
        if (class_exists('Timber')) {
            $context = Timber::context();
            $context['block'] = $block_data;
            
            // Get businesses based on block settings
            $args = array(
                'post_type' => 'business', // Use your existing business CPT
                'posts_per_page' => -1,
                'post_status' => 'publish',
            );
            
            if (!empty($block_data['directory_type']) && $block_data['directory_type'] !== 'all') {
                $args['meta_query'][] = array(
                    'key' => 'business_type',
                    'value' => $block_data['directory_type'],
                    'compare' => '='
                );
            }
            
            $context['businesses'] = Timber::get_posts($args);
            
            Timber::render('blocks/business-directory.twig', $context);
        }
    });
    
    // Community Stats Block Render
    add_action('cmb2_render_villa_community_stats', function($block_data) {
        if (class_exists('Timber')) {
            $context = Timber::context();
            $context['block'] = $block_data;
            
            // Calculate stats
            $stats = array();
            $display_stats = $block_data['stats_display'] ?? array();
            
            if (in_array('total_properties', $display_stats)) {
                $stats['total_properties'] = wp_count_posts('property')->publish;
            }
            
            if (in_array('available_properties', $display_stats)) {
                $available = get_posts(array(
                    'post_type' => 'property',
                    'meta_key' => 'property_status',
                    'meta_value' => 'available',
                    'numberposts' => -1,
                    'fields' => 'ids'
                ));
                $stats['available_properties'] = count($available);
            }
            
            if (in_array('total_members', $display_stats)) {
                $stats['total_members'] = count_users()['total_users'];
            }
            
            if (in_array('active_businesses', $display_stats)) {
                $stats['active_businesses'] = wp_count_posts('business')->publish;
            }
            
            $context['stats'] = $stats;
            
            Timber::render('blocks/community-stats.twig', $context);
        }
    });
    
    // Announcements Block Render
    add_action('cmb2_render_villa_announcements', function($block_data) {
        if (class_exists('Timber')) {
            $context = Timber::context();
            $context['block'] = $block_data;
            
            // Get announcements based on block settings
            $args = array(
                'post_type' => 'announcement',
                'posts_per_page' => $block_data['announcements_count'] ?? 3,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
            );
            
            if (!empty($block_data['announcements_type']) && $block_data['announcements_type'] !== 'all') {
                $args['meta_query'][] = array(
                    'key' => 'announcement_type',
                    'value' => $block_data['announcements_type'],
                    'compare' => '='
                );
            }
            
            $context['announcements'] = Timber::get_posts($args);
            
            Timber::render('blocks/announcements.twig', $context);
        }
    });
}
add_action('init', 'villa_render_cmb2_blocks');

/**
 * Render Property Showcase Block
 */
function villa_render_property_showcase_block($block_data) {
    if (!function_exists('Timber\\Timber::render')) {
        return '<p>Timber is required for this block to work.</p>';
    }
    
    // Get block settings
    $title = $block_data['showcase_title'] ?? '';
    $property_count = intval($block_data['showcase_count'] ?? 6);
    $status_filter = $block_data['showcase_status'] ?? 'available';
    $layout = $block_data['showcase_layout'] ?? 'grid';
    $show_details = $block_data['showcase_show_details'] ?? array();
    
    // Query properties
    $args = array(
        'post_type' => 'property',
        'posts_per_page' => $property_count,
        'post_status' => 'publish',
        'meta_query' => array()
    );
    
    // Add status filter if not 'all'
    if ($status_filter !== 'all') {
        $args['meta_query'][] = array(
            'key' => 'property_status',
            'value' => $status_filter,
            'compare' => '='
        );
    }
    
    $properties_query = new WP_Query($args);
    $properties = array();
    
    if ($properties_query->have_posts()) {
        while ($properties_query->have_posts()) {
            $properties_query->the_post();
            $properties[] = Timber\Timber::get_post();
        }
        wp_reset_postdata();
    }
    
    // Prepare context for Twig
    $context = array(
        'block' => array(
            'showcase_title' => $title,
            'showcase_layout' => $layout,
            'showcase_show_details' => $show_details
        ),
        'properties' => $properties
    );
    
    return Timber\Timber::render('blocks/property-showcase.twig', $context);
}

/**
 * Render Business Directory Block
 */
function villa_render_business_directory_block($block_data) {
    $title = $block_data['directory_title'] ?? 'Business Directory';
    $business_count = intval($block_data['directory_count'] ?? 9);
    $category_filter = $block_data['directory_category'] ?? 'all';
    $layout = $block_data['directory_layout'] ?? 'grid';
    
    // Query businesses
    $args = array(
        'post_type' => 'business',
        'posts_per_page' => $business_count,
        'post_status' => 'publish'
    );
    
    if ($category_filter !== 'all') {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'business_category',
                'field' => 'slug',
                'terms' => $category_filter
            )
        );
    }
    
    $businesses = get_posts($args);
    
    ob_start();
    ?>
    <div class="villa-business-directory layout-<?php echo esc_attr($layout); ?>">
        <?php if ($title): ?>
            <h2 class="directory-title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        
        <?php if ($businesses): ?>
            <div class="businesses-container">
                <?php foreach ($businesses as $business): ?>
                    <?php
                    $business_name = get_post_meta($business->ID, 'business_name', true) ?: $business->post_title;
                    $business_phone = get_post_meta($business->ID, 'business_phone', true);
                    $business_email = get_post_meta($business->ID, 'business_email', true);
                    $business_website = get_post_meta($business->ID, 'business_website', true);
                    $business_address = get_post_meta($business->ID, 'business_address', true);
                    ?>
                    
                    <div class="business-card">
                        <?php if (has_post_thumbnail($business->ID)): ?>
                            <div class="business-image">
                                <?php echo get_the_post_thumbnail($business->ID, 'medium'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="business-content">
                            <h3 class="business-name"><?php echo esc_html($business_name); ?></h3>
                            
                            <div class="business-description">
                                <?php echo wp_trim_words($business->post_content, 20); ?>
                            </div>
                            
                            <div class="business-contact">
                                <?php if ($business_phone): ?>
                                    <div class="contact-item">
                                        <strong>Phone:</strong> <a href="tel:<?php echo esc_attr($business_phone); ?>"><?php echo esc_html($business_phone); ?></a>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($business_email): ?>
                                    <div class="contact-item">
                                        <strong>Email:</strong> <a href="mailto:<?php echo esc_attr($business_email); ?>"><?php echo esc_html($business_email); ?></a>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($business_website): ?>
                                    <div class="contact-item">
                                        <strong>Website:</strong> <a href="<?php echo esc_url($business_website); ?>" target="_blank">Visit Website</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-businesses">No businesses found.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render Community Stats Block
 */
function villa_render_community_stats_block($block_data) {
    $title = $block_data['stats_title'] ?? 'Community Stats';
    $show_stats = $block_data['stats_show'] ?? array();
    
    ob_start();
    ?>
    <div class="villa-community-stats">
        <?php if ($title): ?>
            <h2 class="stats-title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        
        <div class="stats-container">
            <?php if (in_array('properties', $show_stats)): ?>
                <div class="stat-item">
                    <div class="stat-number"><?php echo wp_count_posts('property')->publish; ?></div>
                    <div class="stat-label">Properties</div>
                </div>
            <?php endif; ?>
            
            <?php if (in_array('businesses', $show_stats)): ?>
                <div class="stat-item">
                    <div class="stat-number"><?php echo wp_count_posts('business')->publish; ?></div>
                    <div class="stat-label">Businesses</div>
                </div>
            <?php endif; ?>
            
            <?php if (in_array('members', $show_stats)): ?>
                <div class="stat-item">
                    <div class="stat-number"><?php echo count_users()['total_users']; ?></div>
                    <div class="stat-label">Community Members</div>
                </div>
            <?php endif; ?>
            
            <?php if (in_array('groups', $show_stats)): ?>
                <div class="stat-item">
                    <div class="stat-number"><?php echo wp_count_posts('villa_group')->publish; ?></div>
                    <div class="stat-label">Active Groups</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render Announcements Block
 */
function villa_render_announcements_block($block_data) {
    $title = $block_data['announcements_title'] ?? 'Latest Announcements';
    $announcement_count = intval($block_data['announcements_count'] ?? 3);
    $show_excerpt = isset($block_data['announcements_show_excerpt']) ? $block_data['announcements_show_excerpt'] : true;
    
    // Query announcements
    $announcements = get_posts(array(
        'post_type' => 'announcement',
        'posts_per_page' => $announcement_count,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    ));
    
    ob_start();
    ?>
    <div class="villa-announcements-block">
        <?php if ($title): ?>
            <h2 class="announcements-title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        
        <?php if ($announcements): ?>
            <div class="announcements-list">
                <?php foreach ($announcements as $announcement): ?>
                    <div class="announcement-item">
                        <h3 class="announcement-title">
                            <a href="<?php echo get_permalink($announcement->ID); ?>">
                                <?php echo esc_html($announcement->post_title); ?>
                            </a>
                        </h3>
                        
                        <div class="announcement-meta">
                            <span class="announcement-date"><?php echo get_the_date('F j, Y', $announcement->ID); ?></span>
                        </div>
                        
                        <?php if ($show_excerpt): ?>
                            <div class="announcement-excerpt">
                                <?php echo wp_trim_words($announcement->post_content, 25); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="announcements-footer">
                <a href="/announcements" class="view-all-link">View All Announcements</a>
            </div>
        <?php else: ?>
            <p class="no-announcements">No announcements available.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render Dashboard Properties Block
 */
function villa_render_dashboard_properties_block($block_data) {
    if (!is_user_logged_in()) {
        return '<div class="villa-login-required">Please log in to view your properties.</div>';
    }
    
    $user = wp_get_current_user();
    $title = $block_data['dashboard_properties_title'] ?? 'My Properties';
    $show_form = $block_data['dashboard_properties_show_form'] ?? true;
    $per_page = intval($block_data['dashboard_properties_per_page'] ?? 10);
    
    ob_start();
    ?>
    <div class="villa-dashboard-properties-block">
        <div class="dashboard-block-header">
            <h2><?php echo esc_html($title); ?></h2>
        </div>
        
        <div class="dashboard-block-content">
            <?php villa_render_dashboard_properties($user); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render Dashboard Tickets Block
 */
function villa_render_dashboard_tickets_block($block_data) {
    if (!is_user_logged_in()) {
        return '<div class="villa-login-required">Please log in to view your support tickets.</div>';
    }
    
    $user = wp_get_current_user();
    $title = $block_data['dashboard_tickets_title'] ?? 'Support Tickets';
    $show_form = $block_data['dashboard_tickets_show_form'] ?? true;
    $default_filter = $block_data['dashboard_tickets_default_filter'] ?? 'open';
    
    ob_start();
    ?>
    <div class="villa-dashboard-tickets-block">
        <div class="dashboard-block-header">
            <h2><?php echo esc_html($title); ?></h2>
        </div>
        
        <div class="dashboard-block-content">
            <?php villa_render_dashboard_tickets($user); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render Dashboard Groups Block
 */
function villa_render_dashboard_groups_block($block_data) {
    if (!is_user_logged_in()) {
        return '<div class="villa-login-required">Please log in to view groups.</div>';
    }
    
    $user = wp_get_current_user();
    $title = $block_data['dashboard_groups_title'] ?? 'Groups & Committees';
    $show_directory = $block_data['dashboard_groups_show_directory'] ?? true;
    $type_filter = $block_data['dashboard_groups_type_filter'] ?? 'all';
    
    ob_start();
    ?>
    <div class="villa-dashboard-groups-block">
        <div class="dashboard-block-header">
            <h2><?php echo esc_html($title); ?></h2>
        </div>
        
        <div class="dashboard-block-content">
            <?php villa_render_dashboard_groups($user); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render Dashboard Business Block
 */
function villa_render_dashboard_business_block($block_data) {
    if (!is_user_logged_in()) {
        return '<div class="villa-login-required">Please log in to manage businesses.</div>';
    }
    
    $user = wp_get_current_user();
    $title = $block_data['dashboard_business_title'] ?? 'Business Listings';
    $show_form = $block_data['dashboard_business_show_form'] ?? true;
    $user_only = $block_data['dashboard_business_user_only'] ?? true;
    
    ob_start();
    ?>
    <div class="villa-dashboard-business-block">
        <div class="dashboard-block-header">
            <h2><?php echo esc_html($title); ?></h2>
        </div>
        
        <div class="dashboard-block-content">
            <?php villa_render_dashboard_business($user); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render Dashboard Announcements Block
 */
function villa_render_dashboard_announcements_block($block_data) {
    if (!is_user_logged_in()) {
        return '<div class="villa-login-required">Please log in to view announcements.</div>';
    }
    
    $user = wp_get_current_user();
    $title = $block_data['dashboard_announcements_title'] ?? 'Announcements';
    
    ob_start();
    ?>
    <div class="villa-dashboard-announcements-block">
        <div class="dashboard-block-header">
            <h2><?php echo esc_html($title); ?></h2>
        </div>
        
        <div class="dashboard-block-content">
            <?php villa_render_dashboard_announcements($user); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Render Dashboard Owner Portal Block
 */
function villa_render_dashboard_owner_portal_block($block_data) {
    if (!is_user_logged_in()) {
        return '<div class="villa-login-required">Please log in to access the owner portal.</div>';
    }
    
    $user = wp_get_current_user();
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    if (!villa_user_can_access_owner_portal($user_roles)) {
        return '<div class="villa-access-denied">You do not have access to the owner portal.</div>';
    }
    
    $title = $block_data['dashboard_owner_portal_title'] ?? 'Owner Portal';
    
    ob_start();
    ?>
    <div class="villa-dashboard-owner-portal-block">
        <div class="dashboard-block-header">
            <h2><?php echo esc_html($title); ?></h2>
        </div>
        
        <div class="dashboard-block-content">
            <?php villa_render_dashboard_owner_portal($user); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

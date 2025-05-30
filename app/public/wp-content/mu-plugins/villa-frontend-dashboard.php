<?php
/**
 * Villa Frontend Dashboard System
 * A comprehensive user dashboard for property management, support tickets, and community features
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include all dashboard modules
require_once plugin_dir_path(__FILE__) . 'villa-dashboard-properties.php';
require_once plugin_dir_path(__FILE__) . 'villa-dashboard-tickets.php';
require_once plugin_dir_path(__FILE__) . 'villa-dashboard-announcements.php';
require_once plugin_dir_path(__FILE__) . 'villa-dashboard-additional.php';
require_once plugin_dir_path(__FILE__) . 'villa-dashboard-post-types.php';
require_once plugin_dir_path(__FILE__) . 'villa-groups-cpt.php';

/**
 * Enqueue dashboard styles and scripts
 */
function villa_dashboard_enqueue_assets() {
    // Only enqueue on pages with the dashboard shortcode
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'villa_dashboard')) {
        wp_enqueue_style(
            'villa-dashboard-styles',
            plugin_dir_url(__FILE__) . 'villa-dashboard-styles.css',
            array(),
            '1.0.0'
        );
        
        wp_enqueue_script(
            'villa-dashboard-scripts',
            plugin_dir_url(__FILE__) . 'villa-dashboard-scripts.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('villa-dashboard-scripts', 'villa_dashboard_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('villa_dashboard_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'villa_dashboard_enqueue_assets');

/**
 * Add body class for dashboard pages and hide header
 */
function villa_dashboard_body_class($classes) {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'villa_dashboard')) {
        $classes[] = 'villa-dashboard-page';
    }
    return $classes;
}
add_filter('body_class', 'villa_dashboard_body_class');

/**
 * Style dashboard pages with modern Tailwind-inspired design
 */
function villa_dashboard_page_styles() {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'villa_dashboard')) {
        echo '<style>
            /* Hide default WordPress header on dashboard */
            .villa-dashboard-page #masthead {
                display: none !important;
            }
            
            /* Hide unwanted WordPress content */
            .villa-dashboard-page .widget-area,
            .villa-dashboard-page #secondary,
            .villa-dashboard-page .sidebar,
            .villa-dashboard-page .wp-block-archives,
            .villa-dashboard-page .wp-block-categories,
            .villa-dashboard-page .wp-block-recent-comments,
            .villa-dashboard-page .wp-block-recent-posts,
            .villa-dashboard-page .wp-block-search,
            .villa-dashboard-page .wp-block-tag-cloud,
            .villa-dashboard-page .widget,
            .villa-dashboard-page #comments,
            .villa-dashboard-page .comments-area,
            .villa-dashboard-page .post-navigation,
            .villa-dashboard-page .posts-navigation {
                display: none !important;
            }
            
            /* Full height layout */
            .villa-dashboard-page html,
            .villa-dashboard-page body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            
            .villa-dashboard-page .site {
                height: 100vh;
                display: flex;
                flex-direction: column;
            }
            
            .villa-dashboard-page .site-main {
                flex: 1;
                padding: 0;
                margin: 0;
                max-width: none;
            }
            
            .villa-dashboard-page .entry-content {
                margin: 0;
                padding: 0;
                height: 100%;
            }
            
            .villa-dashboard-page .entry-header {
                display: none;
            }
            
            /* Dashboard container - full screen */
            .villa-dashboard-page .villa-dashboard-container {
                height: 100vh;
                display: flex;
                background: var(--wp--preset--color--gray-50, #f9fafb);
                margin: 0;
                padding: 0;
                max-width: none;
                border-radius: 0;
                box-shadow: none;
            }
            
            /* Fixed sidebar - FULL SCREEN HEIGHT */
            .villa-dashboard-page .villa-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 288px; /* w-72 = 18rem = 288px */
                height: 100vh; /* Full screen height */
                background: var(--wp--preset--color--primary-dark, #1d4ed8);
                display: flex;
                flex-direction: column;
                gap: 20px;
                padding: 24px;
                z-index: 50;
                overflow-y: auto;
            }
            
            /* Sidebar logo area */
            .villa-dashboard-page .villa-sidebar-header {
                display: flex;
                height: 64px;
                align-items: center;
                flex-shrink: 0;
            }
            
            .villa-dashboard-page .villa-sidebar-logo {
                height: 32px;
                width: auto;
                filter: brightness(0) invert(1); /* Make logo white */
            }
            
            /* Sidebar navigation */
            .villa-dashboard-page .villa-sidebar nav {
                display: flex;
                flex: 1;
                flex-direction: column;
            }
            
            .villa-dashboard-page .villa-sidebar ul {
                display: flex;
                flex: 1;
                flex-direction: column;
                gap: 28px;
                list-style: none;
                margin: 0;
                padding: 0;
            }
            
            .villa-dashboard-page .villa-sidebar .nav-section ul {
                gap: 4px;
                margin: 0 -8px;
            }
            
            .villa-dashboard-page .villa-sidebar li {
                margin: 0;
            }
            
            .villa-dashboard-page .villa-sidebar a {
                display: flex;
                gap: 12px;
                border-radius: 6px;
                padding: 8px;
                font-size: 14px;
                line-height: 1.5;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.2s ease;
                align-items: center;
            }
            
            .villa-dashboard-page .villa-sidebar a:not(.active) {
                color: var(--wp--preset--color--primary-light, #93c5fd);
            }
            
            .villa-dashboard-page .villa-sidebar a:not(.active):hover {
                background: var(--wp--preset--color--primary, #2563eb);
                color: var(--wp--preset--color--white, #ffffff);
            }
            
            .villa-dashboard-page .villa-sidebar a.active {
                background: var(--wp--preset--color--primary, #2563eb);
                color: var(--wp--preset--color--white, #ffffff);
            }
            
            /* Sidebar icons */
            .villa-dashboard-page .villa-sidebar .nav-icon {
                width: 24px;
                height: 24px;
                flex-shrink: 0;
            }
            
            /* Teams section */
            .villa-dashboard-page .villa-sidebar .teams-header {
                font-size: 12px;
                line-height: 1.5;
                font-weight: 600;
                color: var(--wp--preset--color--primary-light, #93c5fd);
                margin-bottom: 8px;
            }
            
            .villa-dashboard-page .villa-sidebar .team-initial {
                display: flex;
                width: 24px;
                height: 24px;
                flex-shrink: 0;
                align-items: center;
                justify-content: center;
                border-radius: 6px;
                border: 1px solid var(--wp--preset--color--primary-light, #93c5fd);
                background: var(--wp--preset--color--primary, #2563eb);
                font-size: 10px;
                font-weight: 500;
                color: var(--wp--preset--color--white, #ffffff);
            }
            
            /* Settings at bottom */
            .villa-dashboard-page .villa-sidebar .settings-section {
                margin-top: auto;
            }
            
            /* Main content area */
            .villa-dashboard-page .villa-content {
                margin-left: 288px; /* Same as sidebar width */
                flex: 1;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            
            /* Top header bar - MATCHING SIDEBAR COLORS */
            .villa-dashboard-page .villa-top-header {
                position: sticky;
                top: 0;
                z-index: 40;
                display: flex;
                height: 64px;
                align-items: center;
                gap: 16px;
                border-bottom: 1px solid var(--wp--preset--color--primary, #2563eb);
                background: var(--wp--preset--color--primary-dark, #1d4ed8);
                padding: 0 16px;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
                flex-shrink: 0; /* Prevent header from shrinking */
            }
            
            /* Mobile menu button (hidden on desktop) */
            .villa-dashboard-page .mobile-menu-btn {
                display: none;
                margin: -10px;
                padding: 10px;
                color: var(--wp--preset--color--primary-light, #93c5fd);
                transition: color 0.2s ease;
            }
            
            .villa-dashboard-page .mobile-menu-btn:hover {
                color: var(--wp--preset--color--white, #ffffff);
            }
            
            /* Search form */
            .villa-dashboard-page .search-form {
                display: grid;
                flex: 1;
                grid-template-columns: 1fr;
                position: relative;
            }
            
            .villa-dashboard-page .search-input {
                grid-column: 1;
                grid-row: 1;
                display: block;
                width: 100%;
                background: var(--wp--preset--color--primary, #2563eb);
                padding-left: 32px;
                padding-right: 12px;
                padding-top: 8px;
                padding-bottom: 8px;
                font-size: 14px;
                color: var(--wp--preset--color--white, #ffffff);
                outline: none;
                border: 1px solid var(--wp--preset--color--primary-light, #93c5fd);
                border-radius: 6px;
            }
            
            .villa-dashboard-page .search-input::placeholder {
                color: var(--wp--preset--color--primary-light, #93c5fd);
            }
            
            .villa-dashboard-page .search-icon {
                grid-column: 1;
                grid-row: 1;
                pointer-events: none;
                width: 16px;
                height: 16px;
                align-self: center;
                color: var(--wp--preset--color--primary-light, #93c5fd);
                margin-left: 8px;
            }
            
            /* Header actions */
            .villa-dashboard-page .header-actions {
                display: flex;
                align-items: center;
                gap: 16px;
            }
            
            .villa-dashboard-page .notification-btn {
                margin: -10px;
                padding: 10px;
                color: var(--wp--preset--color--primary-light, #93c5fd);
                transition: color 0.2s ease;
            }
            
            .villa-dashboard-page .notification-btn:hover {
                color: var(--wp--preset--color--white, #ffffff);
            }
            
            /* User profile dropdown */
            .villa-dashboard-page .user-profile {
                display: flex;
                align-items: center;
                margin: -6px;
                padding: 6px;
                position: relative;
                border-radius: 6px;
                transition: background-color 0.2s ease;
            }
            
            .villa-dashboard-page .user-profile:hover {
                background: var(--wp--preset--color--primary, #2563eb);
            }
            
            .villa-dashboard-page .user-avatar {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: var(--wp--preset--color--gray-50, #f9fafb);
                border: 2px solid var(--wp--preset--color--primary-light, #93c5fd);
            }
            
            .villa-dashboard-page .user-info {
                margin-left: 12px;
                font-size: 14px;
                line-height: 1.5;
                font-weight: 600;
                color: var(--wp--preset--color--white, #ffffff);
            }
            
            /* Main content - IMPROVED SCROLLING */
            .villa-dashboard-page .main-content {
                flex: 1;
                padding: 40px 16px;
                overflow-y: auto; /* Allow content to scroll */
                min-height: 0; /* Important for flex scrolling */
            }
            
            .villa-dashboard-page .content-inner {
                padding: 0 16px;
            }
            
            /* Mobile responsive */
            @media (max-width: 1024px) {
                .villa-dashboard-page .villa-sidebar {
                    transform: translateX(-100%);
                    transition: transform 0.3s ease;
                }
                
                .villa-dashboard-page .villa-content {
                    margin-left: 0;
                }
                
                .villa-dashboard-page .mobile-menu-btn {
                    display: block;
                }
                
                .villa-dashboard-page .header-separator {
                    display: block;
                    height: 24px;
                    width: 1px;
                    background: var(--wp--preset--color--gray-900, #111827);
                    opacity: 0.1;
                }
            }
            
            @media (min-width: 1024px) {
                .villa-dashboard-page .header-separator {
                    display: none;
                }
            }
        </style>';
    }
}
add_action('wp_head', 'villa_dashboard_page_styles');

/**
 * Main dashboard shortcode
 */
function villa_dashboard_shortcode($atts) {
    if (!is_user_logged_in()) {
        return '<div class="villa-dashboard-login">Please <a href="' . wp_login_url(get_permalink()) . '">log in</a> to access your dashboard.</div>';
    }
    
    $user = wp_get_current_user();
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    // For admin users, assign owner and bod roles if they don't have any villa roles
    if (current_user_can('administrator') && empty($user_roles)) {
        villa_assign_admin_villa_roles($user->ID);
        $user_roles = villa_get_user_villa_roles($user->ID);
    }
    
    // Check if user has access
    if (empty($user_roles) && !current_user_can('administrator')) {
        return '<div class="villa-dashboard-no-access">Your account does not have access to the dashboard. Please contact an administrator.</div>';
    }
    
    // Ensure CSS is loaded
    wp_enqueue_style('villa-dashboard-styles');

    ob_start();
    
    // Add a simple test to make sure we're rendering
    echo '<!-- Villa Dashboard Starting -->';
    
    villa_render_dashboard($user, $user_roles);
    
    echo '<!-- Villa Dashboard Ending -->';
    
    return ob_get_clean();
}
add_shortcode('villa_dashboard', 'villa_dashboard_shortcode');

/**
 * Render the main dashboard interface
 */
function villa_render_dashboard($user, $user_roles) {
    $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'profile';
    
    // Get meta box settings
    $page_id = get_the_ID();
    $dashboard_title = get_post_meta($page_id, 'dashboard_title', true) ?: 'Villa Community Dashboard';
    $show_welcome = get_post_meta($page_id, 'show_welcome_message', true);
    $welcome_message = get_post_meta($page_id, 'welcome_message', true);
    
    ?>
    <div class="villa-dashboard-container">
        <!-- Dashboard Sidebar -->
        <div class="villa-sidebar">
            <div class="villa-sidebar-header">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-white.svg" alt="Villa Logo" class="villa-sidebar-logo">
            </div>
            <nav>
                <ul>
                    <!-- Main Navigation -->
                    <li class="nav-section">
                        <ul>
                            <?php if (villa_user_can_access_properties($user_roles)): ?>
                                <li>
                                    <a href="?tab=properties" class="<?php echo $current_tab === 'properties' ? 'active' : ''; ?>">
                                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Properties
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (villa_user_can_access_tickets($user_roles)): ?>
                                <li>
                                    <a href="?tab=tickets" class="<?php echo $current_tab === 'tickets' ? 'active' : ''; ?>">
                                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                        </svg>
                                        Tickets
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (villa_user_can_access_announcements($user_roles)): ?>
                                <li>
                                    <a href="?tab=announcements" class="<?php echo $current_tab === 'announcements' ? 'active' : ''; ?>">
                                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                        Announcements
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (villa_user_can_access_owner_portal($user_roles)): ?>
                                <li>
                                    <a href="?tab=owner-portal" class="<?php echo $current_tab === 'owner-portal' ? 'active' : ''; ?>">
                                        <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Owner Portal
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    
                    <!-- Business Section -->
                    <?php if (villa_user_can_access_business($user_roles) || villa_user_can_access_committees($user_roles) || villa_user_can_access_billing($user_roles)): ?>
                        <li class="nav-section">
                            <div class="teams-header">BUSINESS</div>
                            <ul>
                                <?php if (villa_user_can_access_business($user_roles)): ?>
                                    <li>
                                        <a href="?tab=business" class="<?php echo $current_tab === 'business' ? 'active' : ''; ?>">
                                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            Directory
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (villa_user_can_access_committees($user_roles)): ?>
                                    <li>
                                        <a href="?tab=groups" class="<?php echo $current_tab === 'groups' ? 'active' : ''; ?>">
                                            <i class="fas fa-users"></i>
                                            <span>Groups</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (villa_user_can_access_billing($user_roles)): ?>
                                    <li>
                                        <a href="?tab=billing" class="<?php echo $current_tab === 'billing' ? 'active' : ''; ?>">
                                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            Billing
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Settings at bottom -->
                    <li class="settings-section">
                        <ul>
                            <li>
                                <a href="?tab=profile" class="<?php echo $current_tab === 'profile' ? 'active' : ''; ?>">
                                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Settings
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
        
        <!-- Main Content Area -->
        <div class="villa-content">
            <!-- Top Header Bar -->
            <div class="villa-top-header">
                <button class="mobile-menu-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div class="search-form">
                    <input type="search" class="search-input" placeholder="Search...">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search search-icon">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <div class="header-actions">
                    <button class="notification-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                    </button>
                    <div class="user-profile">
                        <img src="<?php echo get_avatar_url($user->ID); ?>" alt="<?php echo esc_attr($user->display_name); ?>" class="user-avatar">
                        <div class="user-info">
                            <?php echo esc_html($user->display_name); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="main-content">
                <div class="content-inner">
                    <?php if ($show_welcome && $welcome_message && $current_tab === 'profile'): ?>
                        <div class="villa-welcome-message">
                            <?php echo wp_kses_post($welcome_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php
                    // Simplified switch - only Profile for testing
                    switch ($current_tab) {
                        case 'properties':
                            if (villa_user_can_access_properties($user_roles)) {
                                villa_render_dashboard_properties($user);
                            } else {
                                echo '<div class="dashboard-no-access">You do not have permission to access properties.</div>';
                            }
                            break;
                            
                        case 'profile':
                            villa_render_dashboard_profile($user);
                            break;
                            
                        case 'groups':
                        case 'committees':
                            if (villa_user_can_access_committees($user_roles)) {
                                villa_render_dashboard_committees($user);
                            } else {
                                echo '<div class="dashboard-no-access">You do not have permission to access groups.</div>';
                            }
                            break;
                        
                        default:
                            echo '<div class="dashboard-welcome">Welcome to your Villa Community dashboard! Navigate using the sidebar.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Permission check functions
 */
function villa_user_can_access_properties($user_roles) {
    $allowed_roles = array('owner', 'bod', 'staff', 'property_manager');
    
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_access_tickets($user_roles) {
    $allowed_roles = array('owner', 'bod', 'staff', 'property_manager', 'community_member');
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_access_announcements($user_roles) {
    // All roles can access announcements
    return !empty($user_roles);
}

function villa_user_can_access_owner_portal($user_roles) {
    $allowed_roles = array('owner', 'bod');
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_access_business($user_roles) {
    $allowed_roles = array('partner', 'bod', 'staff');
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_access_committees($user_roles) {
    $allowed_roles = array(
        'bod', 
        'villa_board_president', 
        'villa_board_vice_president', 
        'villa_board_treasurer', 
        'villa_board_secretary',
        'committee_member', 
        'villa_property_management',
        'villa_maintenance',
        'villa_office_manager',
        'villa_concierge',
        'villa_director_operations'
    );
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_access_billing($user_roles) {
    $allowed_roles = array('owner', 'bod');
    return !empty(array_intersect($user_roles, $allowed_roles));
}

function villa_user_can_view_billing($user_id) {
    $user_roles = villa_get_user_villa_roles($user_id);
    return villa_user_can_access_billing($user_roles);
}

/**
 * Get user's villa roles
 */
function villa_get_user_villa_roles($user_id) {
    $profile = villa_get_user_profile($user_id);
    $roles = array();
    
    if ($profile) {
        // Check both old single role and new multiple roles
        $single_role = get_post_meta($profile->ID, 'profile_villa_role', true);
        $multiple_roles = get_post_meta($profile->ID, 'profile_villa_roles', true);
        
        if ($single_role) {
            $roles[] = $single_role;
        }
        
        if ($multiple_roles && is_array($multiple_roles)) {
            $roles = array_merge($roles, array_keys(array_filter($multiple_roles)));
        }
        
        $roles = array_unique($roles);
    }
    
    return $roles;
}

/**
 * Get user profile CPT
 */
function villa_get_user_profile($user_id) {
    $profiles = get_posts(array(
        'post_type' => 'user_profile',
        'meta_query' => array(
            array(
                'key' => 'profile_user_id',
                'value' => $user_id,
                'compare' => '='
            )
        ),
        'posts_per_page' => 1
    ));
    
    return !empty($profiles) ? $profiles[0] : null;
}

/**
 * Assign admin villa roles
 */
function villa_assign_admin_villa_roles($user_id) {
    $profile = villa_get_user_profile($user_id);
    if (!$profile) {
        $profile = array(
            'post_title' => 'Admin Profile',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'user_profile',
            'meta_input' => array(
                'profile_user_id' => $user_id,
                'profile_villa_roles' => array('owner' => true, 'bod' => true)
            )
        );
        $profile_id = wp_insert_post($profile);
    } else {
        update_post_meta($profile->ID, 'profile_villa_roles', array('owner' => true, 'bod' => true));
    }
    
    // Assign admin to some existing properties for testing
    villa_assign_admin_to_properties($user_id);
}

/**
 * Assign admin to existing properties
 */
function villa_assign_admin_to_properties($user_id) {
    // Get existing properties
    $properties = get_posts(array(
        'post_type' => 'property',
        'posts_per_page' => 5, // Assign to first 5 properties
        'post_status' => 'publish'
    ));
    
    foreach ($properties as $property) {
        // Get existing owners
        $existing_owners = get_post_meta($property->ID, 'property_owners', true);
        if (!is_array($existing_owners)) {
            $existing_owners = array();
        }
        
        // Add admin as owner if not already assigned
        if (!in_array($user_id, $existing_owners)) {
            $existing_owners[] = $user_id;
            update_post_meta($property->ID, 'property_owners', $existing_owners);
        }
    }
}

/**
 * Get unread announcements count for user
 */
function villa_get_unread_announcements_count($user_id) {
    $read_announcements = get_user_meta($user_id, 'villa_read_announcements', true);
    if (!is_array($read_announcements)) {
        $read_announcements = array();
    }
    
    $all_announcements = get_posts(array(
        'post_type' => 'villa_announcement',
        'post_status' => 'publish',
        'numberposts' => -1
    ));
    
    $unread_count = 0;
    foreach ($all_announcements as $announcement) {
        if (!in_array($announcement->ID, $read_announcements)) {
            $unread_count++;
        }
    }
    
    return $unread_count;
}

/**
 * Render recent activity widget
 */
function villa_render_recent_activity($user_id) {
    // Get recent tickets, property updates, etc.
    echo '<p>Recent activity will be displayed here...</p>';
}

/**
 * AJAX handlers
 */

// Load tab content dynamically
function villa_ajax_load_tab_content() {
    check_ajax_referer('villa_dashboard_nonce', 'nonce');
    
    $tab = sanitize_text_field($_POST['tab']);
    $user = wp_get_current_user();
    
    ob_start();
    
    switch ($tab) {
        case 'properties':
            villa_render_dashboard_properties($user);
            break;
        case 'tickets':
            villa_render_dashboard_tickets($user);
            break;
        case 'announcements':
            villa_render_dashboard_announcements($user);
            break;
        case 'owner-portal':
            villa_render_dashboard_owner_portal($user);
            break;
        case 'business':
            villa_render_dashboard_business($user);
            break;
        case 'groups':
            villa_render_dashboard_groups($user);
            break;
        case 'billing':
            villa_render_dashboard_billing($user);
            break;
        case 'profile':
            villa_render_dashboard_profile($user);
            break;
    }
    
    $content = ob_get_clean();
    
    wp_send_json_success(array('content' => $content));
}
add_action('wp_ajax_villa_load_tab_content', 'villa_ajax_load_tab_content');

// Delete property
function villa_ajax_delete_property() {
    check_ajax_referer('villa_dashboard_nonce', 'nonce');
    
    $property_id = intval($_POST['property_id']);
    $user = wp_get_current_user();
    
    // Check if user owns this property
    $property = get_post($property_id);
    if (!$property || $property->post_author != $user->ID) {
        wp_send_json_error(array('message' => 'You do not have permission to delete this property.'));
    }
    
    if (wp_delete_post($property_id, true)) {
        wp_send_json_success(array('message' => 'Property deleted successfully.'));
    } else {
        wp_send_json_error(array('message' => 'Failed to delete property.'));
    }
}
add_action('wp_ajax_villa_delete_property', 'villa_ajax_delete_property');

// Toggle property listing
function villa_ajax_toggle_property_listing() {
    check_ajax_referer('villa_dashboard_nonce', 'nonce');
    
    $property_id = intval($_POST['property_id']);
    $user = wp_get_current_user();
    
    // Check if user owns this property
    $property = get_post($property_id);
    if (!$property || $property->post_author != $user->ID) {
        wp_send_json_error(array('message' => 'You do not have permission to modify this property.'));
    }
    
    $current_status = get_post_meta($property_id, 'property_listing_status', true);
    $new_status = ($current_status === 'not_listed') ? 'for_sale' : 'not_listed';
    
    update_post_meta($property_id, 'property_listing_status', $new_status);
    
    wp_send_json_success(array('message' => 'Property listing status updated.'));
}
add_action('wp_ajax_villa_toggle_property_listing', 'villa_ajax_toggle_property_listing');

// Mark announcement as read
function villa_ajax_mark_announcement_read() {
    check_ajax_referer('villa_dashboard_nonce', 'nonce');
    
    $announcement_id = intval($_POST['announcement_id']);
    $user_id = get_current_user_id();
    
    // Get current read users
    $read_users = get_post_meta($announcement_id, 'announcement_read_users', true);
    if (!is_array($read_users)) {
        $read_users = array();
    }
    
    // Add current user to read list
    if (!in_array($user_id, $read_users)) {
        $read_users[] = $user_id;
        update_post_meta($announcement_id, 'announcement_read_users', $read_users);
    }
    
    wp_send_json_success();
}
add_action('wp_ajax_villa_mark_announcement_read', 'villa_ajax_mark_announcement_read');

/**
 * Add admin menu for villa role management
 */
add_action('admin_menu', function() {
    add_management_page(
        'Villa Role Assignment',
        'Villa Roles',
        'manage_options',
        'villa-role-assignment',
        'villa_role_assignment_admin_page'
    );
});

/**
 * Admin page for villa role assignment
 */
function villa_role_assignment_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }
    
    $user = wp_get_current_user();
    
    // Handle form submission
    if (isset($_POST['assign_roles']) && wp_verify_nonce($_POST['_wpnonce'], 'assign_villa_roles')) {
        villa_assign_admin_villa_roles($user->ID);
        echo '<div class="notice notice-success"><p><strong>Success!</strong> Villa roles assigned. You now have Owner and BOD access.</p></div>';
    }
    
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    echo '<div class="wrap">';
    echo '<h1>Villa Role Assignment</h1>';
    
    echo '<div class="card">';
    echo '<h2>Current Villa Roles</h2>';
    if (!empty($user_roles)) {
        echo '<p><strong>Your current villa roles:</strong> ' . implode(', ', array_keys(array_filter($user_roles))) . '</p>';
    } else {
        echo '<p>You currently have no villa roles assigned.</p>';
    }
    echo '</div>';
    
    echo '<div class="card">';
    echo '<h2>Assign Villa Roles</h2>';
    echo '<p>This will assign you Owner and BOD roles, and link you to existing properties for testing.</p>';
    echo '<form method="post">';
    wp_nonce_field('assign_villa_roles');
    echo '<p><input type="submit" name="assign_roles" class="button button-primary" value="Assign Villa Roles" /></p>';
    echo '</form>';
    echo '</div>';
    
    echo '</div>';
}
